<?php
class HomeController  {
    function index()
    {
        	// $array_conds: lấy sản phẩm dựa theo cột
            // $array_sorts: sắp xếp tăng hay giảm
            // $page: trang thứ mấy
            // $qty_per_page: số lượng sản phẩm mỗi trang
       $productRepository = new ProductRepository();
       $conds = [];
       $sorts = ["featured"=>"DESC"]; //SELECT * FROM view_product ORDER BY featured DESC: lấy cột featured giảm dần
       $page =1 ;
       // lấy 4 sản phẩm nổi bật 
       $item_per_page = 4;
       $featuredProducts = $productRepository->getBy($conds, $sorts, $page, $item_per_page );
       // lấy các sản phẩm mới nhất 
       $sorts = ["created_date"=>"DESC"];
       $lastedProducts = $productRepository->getBy($conds, $sorts, $page, $item_per_page );
       
       // lấy danh mục sản phẩm 
       $categoryRepository = new CategoryRepository;
       $categories=$categoryRepository->getAll();
       $categoryProducts=[];
       foreach ($categories as $key => $category) {
            $conds = [
                "category_id" => [
                    "type" => "=",
                    "val" => $category->getId()
                ]   
            ]; 
            // type "="
            // val = "1"  
            $products = $productRepository->getBy($conds, $sorts, $page, $item_per_page );
            $categoryProducts [$category->getName()]= $products;
       }
       // bắt buộc ta phải dùng đường dẫn tuyệt đối 
    //    require ABSPATH_SITE. "view/home/index.php";
    require ABSPATH_SITE. "view/home/index.php";
    } 
}  
?>