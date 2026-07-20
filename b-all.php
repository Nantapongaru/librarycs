<?php
session_start();
require_once 'config/db.php';

// ตรวจสอบการเข้าสู่ระบบ
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$user_id = $_SESSION['user_id'];
$action = $_GET['action'] ?? '';

header('Content-Type: application/json');

/**
 * ฟังก์ชันช่วยคำนวณยอดสุทธิพร้อมโปรโมชัน
 */
function calculateOrderTotal($pdo, $order_id, $promo_name) {
    $stmt = $pdo->prepare("SELECT SUM(price * quantity) as subtotal, SUM(quantity) as total_qty FROM order_items WHERE order_id = ?");
    $stmt->execute([$order_id]);
    $res = $stmt->fetch();
    
    $subtotal = (float)($res['subtotal'] ?: 0);
    $total_qty = (int)($res['total_qty'] ?: 0);
    $discount_amount = 0;

    if ($promo_name && !in_array($promo_name, ['ไม่มี', 'ไม่มีโปรโมชัน'])) {
        $stmt_p = $pdo->prepare("SELECT min_quantity, discount_percentage FROM promotions WHERE promo_name = ?");
        $stmt_p->execute([$promo_name]);
        $promo = $stmt_p->fetch();
        
        if ($promo && $total_qty >= $promo['min_quantity']) {
            $discount_pct = (float)$promo['discount_percentage'];
            $discount_amount = $subtotal * ($discount_pct / 100);
        }
    }

    $net_total = $subtotal - $discount_amount;

    return [
        'subtotal' => $subtotal,
        'discount' => $discount_amount,
        'net' => $net_total,
        'total_qty' => $total_qty
    ];
}

try {
    switch ($action) {
        case 'checkout':
            $data = json_decode(file_get_contents('php://input'), true);
            $pdo->beginTransaction();
            $sql_order = "INSERT INTO orders (user_id, total_amount, cash_received, change_amount, promo_name, status, created_at) 
                          VALUES (?, ?, ?, ?, ?, 'pending', NOW())";
            $stmt_order = $pdo->prepare($sql_order);
            $stmt_order->execute([
                $user_id, 
                $data['total'], 
                $data['cash'], 
                $data['change'], 
                $data['promo_name']
            ]);
            $order_id = $pdo->lastInsertId();

            $sql_item = "INSERT INTO order_items (order_id, product_name, price, quantity) VALUES (?, ?, ?, ?)";
            $stmt_item = $pdo->prepare($sql_item);
            foreach ($data['items'] as $item) {
                $stmt_item->execute([$order_id, $item['name'], $item['price'], $item['qty']]);
            }
            $pdo->commit();
            echo json_encode(['success' => true, 'order_id' => $order_id]);
            break;

        case 'complete_order':
            $oid = $_GET['id'];
            $stmt_orig = $pdo->prepare("SELECT cash_received, promo_name FROM orders WHERE id = ?");
            $stmt_orig->execute([$oid]);
            $orig = $stmt_orig->fetch();
            
            if ($orig) {
                $calc = calculateOrderTotal($pdo, $oid, $orig['promo_name']);
                $new_change = (float)$orig['cash_received'] - $calc['net'];

                $stmt = $pdo->prepare("UPDATE orders SET status = 'completed', total_amount = ?, change_amount = ? WHERE id = ?");
                $res = $stmt->execute([$calc['net'], $new_change, $oid]);
                echo json_encode(['success' => $res]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Order not found']);
            }
            break;

        case 'cancel_order':
            $oid = $_GET['id'];
            $stmt = $pdo->prepare("UPDATE orders SET status = 'cancelled' WHERE id = ?");
            $res = $stmt->execute([$oid]);
            echo json_encode(['success' => $res]);
            break;

        case 'update_qty':
        case 'delete_item':
        case 'replace_item':
            $order_id = $_GET['order_id'];
            $item_id = $_GET['item_id'];
            $pdo->beginTransaction();
            
            if ($action === 'update_qty') {
                $delta = (int)$_GET['delta'];
                $pdo->prepare("UPDATE order_items SET quantity = quantity + ? WHERE id = ?")->execute([$delta, $item_id]);
                $pdo->prepare("DELETE FROM order_items WHERE quantity <= 0 AND id = ?")->execute([$item_id]);
            } elseif ($action === 'delete_item') {
                $pdo->prepare("DELETE FROM order_items WHERE id = ?")->execute([$item_id]);
            } elseif ($action === 'replace_item') {
                $new_p_id = $_GET['new_product_id'];
                $new_p = $pdo->query("SELECT name, price FROM products WHERE id = $new_p_id")->fetch();
                if ($new_p) {
                    $pdo->prepare("UPDATE order_items SET product_name = ?, price = ? WHERE id = ?")->execute([$new_p['name'], $new_p['price'], $item_id]);
                }
            }
            $pdo->commit();
            echo json_encode(['success' => true]);
            break;

        case 'get_live_data':
            $f_date = $_GET['f_date'] ?? '';
            $f_month = $_GET['f_month'] ?? '';
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $limit = 10;
            $offset = ($page - 1) * $limit;
            
            // กำหนดเงื่อนไขการกรอง
            $where_clause = "DATE(o.created_at) = CURDATE() AND o.status != 'cancelled'";
            $params = [];
            if(!empty($f_date)) { 
                $where_clause = "DATE(o.created_at) = :f_date AND o.status != 'cancelled'"; 
                $params[':f_date'] = $f_date; 
            }
            elseif(!empty($f_month)) { 
                $where_clause = "DATE_FORMAT(o.created_at, '%Y-%m') = :f_month AND o.status != 'cancelled'"; 
                $params[':f_month'] = $f_month; 
            }

            // 1. ดึงออเดอร์ที่รอทำ (Pending) - ไม่ต้องทำ Pagination
            $pending_raw = $pdo->query("SELECT o.*, GROUP_CONCAT(CONCAT(oi.id, '::', oi.product_name, '::', oi.quantity, '::', oi.price) SEPARATOR '||') as items_raw 
                                        FROM orders o 
                                        JOIN order_items oi ON o.id = oi.order_id 
                                        WHERE o.status = 'pending' 
                                        GROUP BY o.id 
                                        ORDER BY o.created_at ASC")->fetchAll();
            
            $pending = [];
            foreach ($pending_raw as $order) {
                $calc = calculateOrderTotal($pdo, $order['id'], $order['promo_name']);
                $order['current_item_sum'] = $calc['net'];
                $order['change_amount'] = (float)$order['cash_received'] - $calc['net'];
                $pending[] = $order;
            }
            
            // 2. ดึงสรุปยอดรวม (ยอดขาย และ จำนวนแก้ว)
            $stmt_sales = $pdo->prepare("SELECT SUM(total_amount) FROM orders o WHERE $where_clause"); 
            $stmt_sales->execute($params);
            $sales = $stmt_sales->fetchColumn() ?: 0;
            
            $stmt_qty = $pdo->prepare("SELECT SUM(oi.quantity) FROM order_items oi JOIN orders o ON oi.order_id = o.id WHERE $where_clause"); 
            $stmt_qty->execute($params);
            $cups = $stmt_qty->fetchColumn() ?: 0;

            // 3. คำนวณจำนวนหน้าทั้งหมดสำหรับการแบ่งหน้า
            $stmt_count = $pdo->prepare("SELECT COUNT(DISTINCT o.id) FROM orders o WHERE $where_clause");
            $stmt_count->execute($params);
            $total_records = (int)$stmt_count->fetchColumn();
            $total_pages = ceil($total_records / $limit);

            // 4. ดึงรายการออเดอร์สำหรับการแสดงในหน้า Summary (ประวัติการขาย) แบบมี Pagination
            $sql_list = "SELECT o.*, 
                        GROUP_CONCAT(CONCAT(oi.product_name, ' x', oi.quantity) SEPARATOR '||') as items_desc, 
                        SUM(oi.quantity) as order_total_qty 
                        FROM orders o 
                        JOIN order_items oi ON o.id = oi.order_id 
                        WHERE $where_clause 
                        GROUP BY o.id 
                        ORDER BY o.created_at DESC 
                        LIMIT $limit OFFSET $offset";
            
            $stmt_list = $pdo->prepare($sql_list);
            $stmt_list->execute($params);
            $list = $stmt_list->fetchAll();

            echo json_encode([
                'pending_orders' => $pending,
                'today_sales' => (float)$sales,
                'total_cups' => (int)$cups,
                'orders_list' => $list,
                'pagination' => [
                    'current_page' => $page,
                    'total_pages' => $total_pages,
                    'total_records' => $total_records,
                    'limit' => $limit
                ],
                'filter_label' => !empty($f_date) ? $f_date : (!empty($f_month) ? $f_month : 'วันนี้')
            ]);
            break;

        default:
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
            break;
    }
} catch (Exception $e) {
    if($pdo->inTransaction()) $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}