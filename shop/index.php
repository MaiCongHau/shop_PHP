<?php 
// header("location:site"); 
session_start(); 
require_once "config.php";
require_once ABSPATH . "bootstrap.php"; //ABSPATH:shop thằng này dùng để lấy đường dẫn của các model
require_once ABSPATH_SITE . "load.php"; //ABSPATH_SITE: shop/site/ thằng này dùng để lấy đường dẫn của các controller 

require 'vendor/autoload.php';
$router = new AltoRouter();
// khi mới vào nó ko chạy liền, mà nó bắt đầu kiểm tra cái đường dẫn của mình nó có map với cái nào ko, nếu nó map với cái nào thì nó sẽ gán giá trị map này thông qua thằng $router->match();

// Trang chủ
$router->map( 'GET', '/', ["HomeController", "index"], "home"); // ["HomeController", "index"]: gọi Homecontroller xong gọi hàm index(), chứ nó ko cần tạo đối tượng, gọi dạng static
// "home" : tên của route 

// Sản phẩm 
$router->map( 'GET', '/san-pham', ["ProductController", "index"], "product");

//trang chính sách đổi trả
$router->map( 'GET', '/chinh-sach-doi-tra', ["InformationController", "returnPolicy"], 'returnPolicy');

// trang chính sách thanh toán
$router->map( 'GET', '/chinh-sach-thanh-toan', ["InformationController", "paymentPolicy"], 'paymentPolicy');

// trang chính sách giao hàng
$router->map( 'GET', '/chinh-sach-giao-hang', ["InformationController", "deliveryPolicy"], 'deliveryPolicy');
// trang liên hệ 
$router->map( 'GET', '/lien-he', ["ContactController", "form"], 'contact-form');

// trang chi tiết sản phẩm
// không được dùng slug-name do không hiểu dấu - trong tên
// slugName: là tên sp
// i là kiểu dữ liệu interger
$router->map('GET', '/san-pham/[*:slugName]-[i:id]', function($slugName, $id) {
	$_GET["id"] = $id;
  	call_user_func_array(["ProductController", "show"],[]);
}, 'product-detail');

// trang danh mục
// không đực dùng slug-name do không được đặt tên biến có dấu -
// danh-muc/kem-chong-nang-1
$router->map('GET', '/danh-muc/[*:slugName]-[i:categoryId]', function($slugName, $categoryId) {
	$_GET["category_id"] = $categoryId; // giả $_GET cho thằng trên server nó bắt dc mà xử lý 
  	call_user_func_array(["ProductController", "index"],[]);
}, 'category');

// khoảng giá
// khoang-gia/200000-300000
$router->map('GET', '/khoang-gia/[*:priceRange]', function($priceRange) {
	$_GET["price-range"] = $priceRange; // giả $_GET cho thằng trên server nó bắt dc mà xử lý 
  	call_user_func_array(["ProductController", "index"],[]);
}, 'price-range');

// Tìm kiếm
$router->map('GET', '/search', function() {
    call_user_func_array(["ProductController", "index"],[]);
  }, 'search');

// match current request url
$match = $router->match();
// var_dump($match );
// E:\xampp\htdocs\backend\shop\index.php:19:
// array (size=3)
//   'target' => 
//     array (size=2)
//       0 => string 'HomeController' (length=14)
//       1 => string 'index' (length=5)
//   'params' => 
//     array (size=0)
//       empty
//   'name' => string 'home' (length=4)
$routname = $match["name"] ??null ;




// lưu ý ta dùng $router->generate('product') để thay đổi đường dẫn tại cái nút có VD: c==.. để khi ta đổi đường dẫn nó vẫn có thể sử dụng dc 
// var_dump($router->generate('product'));


// var_dump($match['target']); là cái fuction trong thằng $router->map


// call closure or throw 404 status
if( is_array($match) && is_callable( $match['target'] ) ) // is_array($match): là thằng được map có tồn tại dưới array ko
//is_callable( $match['target'] ) : kiểm tra biến này có gọi được không, 
{
	call_user_func_array( $match['target'], $match['params'] ); 
} else {
	// no route was matched
  //Router
  // nếu nó ko match với đường dẫn đẹp thì nó chạy lại thằng đường dẫn xấu
    $c =$_GET["c"]?? "home"; // ban đầu zô thì kiếm thằng para là $_GET["c"], nếu có thì nó lấy thằng $_GET["c"] gán cho $c, nếu không thì nó lấy thằng "student"
    $a = $_GET["a"]?? "index";// ban đầu zô thì kiếm thằng para là $_GET["a"], nếu có thì nó lấy thằng $_GET["a"] gán cho $a, nếu không thì nó lấy thằng "list"
    $controllerName = ucfirst($c)."Controller"; // in hoa chữ cái đầu tiên, ta được StudentController
    // require "controller/".$controllerName. ".php"; // ra cái link
    $controller = new $controllerName(); // tạo object: thằng này là StudentController, SubjectController
    $controller->$a(); // truy cập tới function $a là list();
}
function slugify($str)
{
    $str = trim(mb_strtolower($str));
    $str = preg_replace('/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/', 'a', $str);
    $str = preg_replace('/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/', 'e', $str);
    $str = preg_replace('/(ì|í|ị|ỉ|ĩ)/', 'i', $str);
    $str = preg_replace('/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/', 'o', $str);
    $str = preg_replace('/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/', 'u', $str);
    $str = preg_replace('/(ỳ|ý|ỵ|ỷ|ỹ)/', 'y', $str);
    $str = preg_replace('/(đ)/', 'd', $str);
    $str = preg_replace('/[^a-z0-9-\s]/', '', $str);
    $str = preg_replace('/([\s]+)/', '-', $str);
    return $str;
}

?>