<?php  
// // load model
// require "../config.php";
// require "../connect.php";

// require "../bootstrap.php";
// require_once '../vendor/autoload.php';
// require_once '../service/Mailservice.php';
// session_start();
// //Router

// $c =$_GET["c"]?? "home"; // ban đầu zô thì kiếm thằng para là $_GET["c"], nếu có thì nó lấy thằng $_GET["c"] gán cho $c, nếu không thì nó lấy thằng "student"
// $a = $_GET["a"]?? "index";// ban đầu zô thì kiếm thằng para là $_GET["a"], nếu có thì nó lấy thằng $_GET["a"] gán cho $a, nếu không thì nó lấy thằng "list"
// $controllerName = ucfirst($c)."Controller"; // in hoa chữ cái đầu tiên, ta được StudentController
// require "controller/".$controllerName. ".php"; // ra cái link
// $controller = new $controllerName(); // tạo object: thằng này là StudentController, SubjectController
// $controller->$a(); // truy cập tới function $a là list();
?>
