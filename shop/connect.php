<?php
// create connect
$conn = new mysqli(SERVERNAME,USERNAME,PASSWORD,DBNAME);
if($conn->connect_error)
{
    die("Kết nối thất bại: ". $conn->connect_erro);
}
mysqli_set_charset($conn,"utf8");
?>