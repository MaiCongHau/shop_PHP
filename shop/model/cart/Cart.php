<?php 
class Cart
{
	protected $items;
	protected $total_price;
	protected $total_product_number;

	function __construct($items = array(), $total_price = 0, $total_product_number = 0){
		$this->items = $items;
		$this->total_price = $total_price;
		$this->total_product_number = $total_product_number;
	}

	function getItems(){
		return $this->items;
	}

	function getTotalPrice(){
		return $this->total_price;
	}

	function getTotalProductNumber(){
		return $this->total_product_number;
	}

	function setItems($items){
		$this->items = $items;
		return $this;
	}

	function setTotalPrice($total_price){
		$this->total_price = $total_price;
		return $this;
	}

	function setTotalProductNumber($total_product_number){
		$this->total_product_number = $total_product_number;
		return $this;
	}

	function addProduct($product_id, $qty) {
		// khi bầm lần 2  nếu mà là cùng sp thì nó cũng gửi có id với qty như cũ thôi, khác thì khác
		$productRepository = new ProductRepository();
		$product = $productRepository->find($product_id); // lấy product có nhiều gtri nên ta chỉ cần lấy thứ ta muốn
		$item = array(
			"product_id" => $product_id,
			"name" => $product->getName(),
			"img" => $product->getFeaturedImage(),
			"qty" => $qty, // SLượng
			"unit_price" => $product->getSalePrice(), // giá đầu
			"total_price" => $product->getSalePrice() * $qty, // giá tổng

		);
		$this->addItem($item);
	}

	protected function addItem($item) {
		global $router;
		$product_id = $item["product_id"];
		$img = $item["img"];
		$name = $item["name"];
		$total_price = $item["total_price"];
		$qty = $item["qty"];
		$unit_price = $item["unit_price"];
		if (!array_key_exists($product_id, $this->items)) { // đầu vô là match vô đây vì $this->items khởi tạo chưa có gì hết, mục tiêu là add các giá trị của item vô thằng $items
			$this->items[$product_id] = array( // VD  protected 'items' =>
											//   3 => 
											// 	array (size=6)
											// 	  'img' => string 'kemLamSangVungDaBikini.jpg' (length=26)
											// 	  'name' => string 'Kem làm sáng vùng da bikini Beaumore- 50ml' (length=45)
											// 	  'product_id' => string '3' (length=1)
											// 	  'qty' => string '1' (length=1)
											// 	  'unit_price' => string '849000' (length=6)
											// 	  'total_price' => int 849000
				"img" => $img,
				"name" => $name,
				"product_id" => $product_id,
				"qty" => $qty,
				"unit_price" => $unit_price, 
				"total_price" => $total_price,
				"product_url" => $router->generate("product-detail",['slugName'=>slugify($name),
				'id'=>$product_id
				])
			);
			
		}
		else { // nếu bấm lại sp cũ thì nó match zô đây 
			$this->items[$product_id]["qty"]+= $qty; // nghĩa là gán thằng có key là [$product_id] và value là ["qty"] có giá trị, tức nghĩa là nó tăng thằng qty lên 1 
			$this->items[$product_id]["total_price"] = $this->items[$product_id]["qty"] * $unit_price;

			// Nghĩa là nó biến thằng $items thành
			// VD  protected 'items' =>
							//   3 => 
							// 	array (size=6)
							// 	  'img' => string 'kemLamSangVungDaBikini.jpg' (length=26)
							// 	  'name' => string 'Kem làm sáng vùng da bikini Beaumore- 50ml' (length=45)
							// 	  'product_id' => string '3' (length=1)
			//Chú ý			// 	  'qty' => string '2' (length=1)
							// 	  'unit_price' => string '849000' (length=6)
			//Chú ý			// 	  'total_price' => int 849000 +++ 
			
		}

		$this->total_price += $unit_price * $qty; // giá đầu * số lần bấm
		$this->total_product_number += $qty; // sl bấm cộng dồn lại
	}

	function deleteProduct($product_id) {
		if (array_key_exists($product_id, $this->items)) {
			unset($this->items[$product_id]);
		}
		//Recalculate total_product_number & total_price
		$this->total_price = 0;
		$this->total_product_number = 0;
		// ý là khi mà mình add vào nhiều sp mà mình tăng hay giảm có 1 thằng à thì lúc này các sp còn lại nó chuôi zô $this->items thì lúc này có total nó mới thay đổi dc lúc này foreach mình dùng để cập nhật cái giá tổng ở dưới 
		
		foreach ($this->items as $item) {
			$this->total_price += $item["unit_price"] * $item["qty"];
			$this->total_product_number += $item["qty"];
		}
	}
	
	
	function convertToArray() {
		$a = array();
		$a["items"] = $this->items;
		$a["total_product_number"] = $this->total_product_number;
		$a["total_price"] = $this->total_price;
		return $a;
	}
}