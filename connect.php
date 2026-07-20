<?php
$conn=mysqli_connect("localhost","root","","librarycs");
date_default_timezone_set('Asia/Bangkok'); 
if ($conn){
    // echo "เชื่อมต่อได้";
    mysqli_query($conn,"SET NAMES UTF8");
}
else{
    echo "เชื่อต่อไม่ได้";
}
?>