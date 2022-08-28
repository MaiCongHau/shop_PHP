<?php 
class PaymentController {
    function checkout()
    {
        $cartStorage = new CartStorage; 
        $cart =  $cartStorage->fetch();
        $email = "khachvanglai@gmail.com";
        if(!empty($_SESSION["email"]))
        {
            $email = $_SESSION["email"];
        }
        $customerRepository = new CustomerRepository;
        $customer = $customerRepository->findEmail($email);
        require ABSPATH_SITE. "layout/variable_address.php";
        require ABSPATH_SITE. "view/payment/checkout.php";
    }
    function order () // trong checkout.php
    {
        //check đơn hàng trước(kiểm tra số lượng sản phẩm còn trong kho không)
        //Sản phẩm ko còn trong kho thì không cho đặt hàng 
        $cartStorage = new CartStorage; 
        $cart =  $cartStorage->fetch(); // lấy các sp mà mình order 
        // $cart ta được ví dụ   
        //     protected 'items' => 
        //     array (size=1)
        //       5 => 
        //         array (size=6)
        //           'img' => string 'kemLuaLamDepDaBeaumore.jpg' (length=26)
        //           'name' => string 'Kem lụa làm đẹp da Beaumore- 30ml' (length=39)
        //           'product_id' => string '5' (length=1)
        //           'qty' => string '1' (length=1)
        //           'unit_price' => string '1500000' (length=7)
        //           'total_price' => int 1500000
        //   protected 'total_price' => int 1500000
        //   protected 'total_product_number' => int 1
        $items = $cart->getItems();
        $productRepository = new ProductRepository;
        foreach ( $items  as $key => $item) {
           $product_id = $item["product_id"];
           $product = $productRepository ->find( $product_id);
           if($product->getInventoryQty()<$item["qty"])
           {
            $_SESSION["error"] = "Xin lỗi sản phẩm {$product->getName()} này không đủ sl, nó chỉ còn {$product->getInventoryQty()}"; 
            header("location:/");
            exit;
           }
        }
        // OK hết thì lưu đơn hàng
        
        $email = "khachvanglai@gmail.com";
        if(!empty($_SESSION["email"]))
        {
            $email = $_SESSION["email"];
        }
        $customerRepository = new CustomerRepository;
        $customer = $customerRepository->findEmail($email); // thông tin khách hàng
        
        $tranportRepository = new TransportRepository();
		$trasport = $tranportRepository->findByProvinceId($_POST["province"]);
		$shipping_fee = $trasport->getPrice();
        
        $orderRepository = new OrderRepository();
        $data=[];
        $data["created_date"]  = date("Y-m-d H:i:s"); 
		$data["order_status_id"] = 1;
        $data["staff_id"] = null;
		$data["customer_id"] =$customer->getId() ;
		$data["shipping_fullname"] = $_POST["fullname"];
		$data["shipping_mobile"]=$_POST["mobile"];
		$data["payment_method"] =$_POST["payment_method"];
		$data["shipping_ward_id"] =$_POST["ward"];
		$data["shipping_housenumber_street"] = $_POST["address"];
		$data["shipping_fee"] = $shipping_fee;
		$data["delivered_date"] = date("Y-m-d H:i:s", strtotime("+3 days"));

      
        $orderItemRepository = new OrderItemRepository; 
        if($orderId =  $orderRepository->save($data) )
        {
            $items =  $cart->getItems();
            foreach($items as $item) {
                $dataItem["product_id"] = $item["product_id"]; // lấy từng item ra rồi add vô   $dataItem
                $dataItem["order_id"] = $orderId; 
                $dataItem["qty"] = $item["qty"]; 
                $dataItem["unit_price"] = $item["unit_price"]; 
                $dataItem["total_price"] = $item["total_price"]; 
                $orderItemRepository->save($dataItem);
                // Sau đó ta cập nhật lại kho hàng trong bảng product
                $product = $productRepository ->find($dataItem["product_id"]); // mỗi lần nó đặt thành công thì nó trừ ra rồi vập nhật vô thằng product
                $updateInventoryQty = $product->getInventoryQty() -  $dataItem["qty"];
                $product->setInventoryQty($updateInventoryQty);
                $productRepository ->update( $product);
            }
            $_SESSION["success"]= "Bạn đã đặt đơn hàng thành công";
            $cartStorage-> clear(); // nếu nó éo chịu xóa cookie thì mơ trình duyệt lên rồi tự xóa 
            
        }
        else{
            $_SESSION["error"]= $orderItemRepository->getError();
        }
        header("location:/");
      
    }
}
  
?>