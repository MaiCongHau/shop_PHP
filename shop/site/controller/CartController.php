<?php 
class CartController {
	protected $cartStorage;

	function __construct() {
		$this->cartStorage = new CartStorage(); // ko dùng CartRepository do nó làm liên quan đến file ko liên quan đến server nữa
	}

	function display() 
	{
		$cart = $this->cartStorage->fetch();// lấy trong session hoặc cookie
		echo json_encode($cart->convertToArray()); // gửi qua thằng client 
	}

	function add() {
		// $this->cartStorage->clear();
		$product_id = $_GET["product_id"]; // 1
		$qty = $_GET["qty"]; // 1 
		$cart = $this->cartStorage->fetch(); // muốn truy cập zô thằng CartStorage phải thông qua biến cartStorage lúc này thì thằng session["cart"] với thằng cookie["cart] chưa có dữ liệu thì nó khởi tạo thằng $cart rổng 

		$cart->addProduct($product_id, $qty);  // add sp 
		// bấm lần đầu thì $cart cái giá trị của nó là 
		// VD :
		// Object
		// protected 'items' => 
		// array (size=2)
		//   3 => 
		// 	array (size=6)
		// 	  'img' => string 'kemLamSangVungDaBikini.jpg' (length=26)
		// 	  'name' => string 'Kem làm sáng vùng da bikini Beaumore- 50ml' (length=45)
		// 	  'product_id' => string '3' (length=1)
		// 	  'qty' => string '1' (length=1)
		// 	  'unit_price' => string '849000' (length=6)
		// 	  'total_price' => int 849000
		// protected 'total_price' => int 1059000
		// protected 'total_product_number' => int 2

		// lần 2 nó cũng ra Object nếu mà bấm cùng 1 sp thì thằng qty nó sẽ tăng lên 1 
		// 	object(Cart)[2]
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
		// protected 'total_price' => int 1059000
		// protected 'total_product_number' => int 2

		$this->cartStorage->store($cart); // chủ yếu là chuyển thằng $cart thằng String rồi lưu lần lượt vào session và cookie, lưu xuống session và cookie 

		// lưu ý lúc này thằng $cart nó vẫn là Object
		//Đổi đối tượng -> chuỗi
		//Đổi tượng thành array, sau đó từ array -> chuỗi
		
		// hàm convertToArray() viết ở thằng $cart nó sẽ biến thằng $cart từ Object sang array
		// 		array (size=3)
		//   'items' => 
		//     array (size=2)
		//       3 => 
		//         array (size=6)
		//           'img' => string 'kemLamSangVungDaBikini.jpg' (length=26)
		//           'name' => string 'Kem làm sáng vùng da bikini Beaumore- 50ml' (length=45)
		//           'product_id' => string '3' (length=1)
		//           'qty' => string '1' (length=1)
		//           'unit_price' => string '849000' (length=6)
		//           'total_price' => int 849000
		//   'total_product_number' => int 5
		//   'total_price' => int 1689000
	
		echo json_encode($cart->convertToArray()); // phải array mới json dc nên mới convert, xong lấy thằng này gửi lại cho client, qua thằng Javascript xem làm gì
	}

	function update() {
		$product_id = $_GET["product_id"];
		$qty = $_GET["qty"];
		$cart = $this->cartStorage->fetch();

		$cart->deleteProduct($product_id); // delete này thì nó chỉ delete thằng nào mà mình thay đổi còn các thằng mà mình ko thay đổi thì nó vẫn giữ nguyên rồi qua thằng delete này xem nó chạy sao
		$cart->addProduct($product_id, $qty); 

		$this->cartStorage->store($cart);

		echo json_encode($cart->convertToArray());
	}

	function delete() {
		$product_id = $_GET["product_id"];
		$cart = $this->cartStorage->fetch();

		$cart->deleteProduct($product_id);

		$this->cartStorage->store($cart);

		echo json_encode($cart->convertToArray());
	}
	
}