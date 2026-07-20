<?php
include("connect.php");
$id_m=$_POST['id_m'];
$name_m=$_POST['name_m'];
$tel_m=$_POST['tel_m'];
$user_m=$_POST['user_m'];
$pass_m=$_POST['pass_m'];
$c1=mysqli_query($conn,"select * from data_depart where id_depart='$id_m'");
$re_c1=mysqli_fetch_array($c1);
$r_c1=mysqli_num_rows($c1);
$c2=mysqli_query($conn,"select * from member where id_m='$id_m'");
$r_c2=mysqli_num_rows($c2);
if($r_c1>0 and $r_c2==0){
    $status_m=$re_c1['status_data'];
    mysqli_query($conn,"insert into member (id_m,name_m,tel_m,status_m,user_m,pass_m) value ('$id_m','$name_m','$tel_m','$status_m','$user_m','$pass_m')");
    $Alert='<script type="text/javascript">';
    $Alert.='alert("ลงทะเบียนเรียบร้อย");';
    $Alert.='</script>';
    echo $Alert;
    echo "<meta http-equiv='refresh' content='1;URL=login.php'>";
}
else{
    $Alert='<script type="text/javascript">';
    $Alert.='alert("ไม่สามารถลงทะเบียนได้");';
    $Alert.='</script>';
    echo $Alert;
    echo "<meta http-equiv='refresh' content='1;URL=form_regis.php'>";
}


?>