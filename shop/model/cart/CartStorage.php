<?php 
class CartStorage {
	function store($cart) {
		//serialize: chuyền object to string
		// O:4:"Cart":3:{s:8:"*items";a:5:{i:3;a:6:{s:3:"img";s:26:"kemLamSangVungDaBikini.jpg";s:4:"name";s:45:"Kem làm sáng vùng da bikini Beaumore- 50ml";s:10:"product_id";s:1:"3";s:3:"qty";i:3;s:10:"unit_price";s:6:"849000";s:11:"total_price";i:2547000;}
		$_SESSION["cart"] = serialize($cart); // set thằng SESSION chủ yếu là mình muốn chuyễn Object thành chuỗi để lưu thôi vì mấy thằng này nó éo lưu dạng Object
		setcookie("cart", serialize($cart),  time() + 3600,"/");//keep one day//keep one day, SET thằng COOKIE do COOKIE lưu lâu hơn SESSION
		// lưu vô database để khi qua máy tính khác mình thấy chức năng này làm sau
	}

	function fetch() {
		if (empty($_SESSION["cart"])) { // Session ko có 
			if (empty($_COOKIE["cart"])) { // Cookie ko có 
				
				$cart = new Cart();
				return $cart; // khởi tạo thằng cart 
			}
			//update session;
			$_SESSION["cart"] = $_COOKIE["cart"]; // Cookie có thì lấy nó gán zô cho session 
		}
		
		// Bấm sp mới hoặc bấm lần 2 
		// $_SESSION["cart] bây giờ đang là String, giống z  
		// O:4:"Cart":3:{s:8:"*items";a:5:{i:3;a:6:{s:3:"img";s:26:"kemLamSangVungDaBikini.jpg";s:4:"name";s:45:"Kem làm sáng vùng da bikini Beaumore- 50ml";s:10:"product_id";s:1:"3";s:3:"qty";i:3;s:10:"unit_price";s:6:"849000";s:11:"total_price";i:2547000;}
		
		$cart = unserialize($_SESSION["cart"]); //serialize: chuyền stirng to object
		// thì nó ra được giống z 
		// 		object(Cart)[5]
		//   protected 'items' => 
		//     array (size=5)
		//       3 => 
		//         array (size=6)
		//           'img' => string 'kemLamSangVungDaBikini.jpg' (length=26)
		//           'name' => string 'Kem làm sáng vùng da bikini Beaumore- 50ml' (length=45)
		//           'product_id' => string '3' (length=1)
		//           'qty' => int 3
		//           'unit_price' => string '849000' (length=6)
		//           'total_price' => int 2547000
		//       9 => 
		//         array (size=6)
		//           'img' => string 'suaTamSandrasMychai250ml.jpg' (length=28)
		//           'name' => string 'Sữa tắm Sandras Mỹ chai 250ml' (length=35)
		//           'product_id' => string '9' (length=1)
		//           'qty' => int 6
		//           'unit_price' => string '210000' (length=6)
		//           'total_price' => int 1260000 
		return $cart;
	}

	function clear() {
		session_id() || session_start();
		unset($_SESSION["cart"]);
		setcookie("cart", null,  time() - 3600,"/");//keep one day
	}
}
 ?>