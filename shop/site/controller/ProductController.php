<?php 
    class ProductController{
        function index()
        {
            $productRepository = new ProductRepository;
            $cond =[];
            $sorts=[];
            $page = $_GET["page"]?? 1;
            $item_per_page =9;
            $category_name_final = "Tất cả sản phẩm";
            // $category_id = !empty($_GET["category_id"])?$_GET["category_id"]:null;
            $category_id = $_GET["category_id"]??null;
            // $products= $productRepository->getBy($cond,$sort,$page,$item_per_page);
            $price_range = $_GET["price-range"]??null;
            // lấy danh mục sp 
            $categoryRepository = new CategoryRepository;
            $categoryProduct = $categoryRepository ->getAll();
           
            // SELECT * FROM  category WHERE category_id = 1
            if($category_id)
            {
                $cond=[
                    "category_id"=>[
                        "type"=>"=",
                        "val"=> $category_id
                    ]
                ];
                $category_name = $categoryRepository->find($category_id);
                $category_name_final =  $category_name->getName();
            }
            // SELECT * FROM view_product WHERE sale_price BETWEEN 100000 AND 200000
            if($price_range)
            {
                $temp = explode("-",$price_range);
                $start = $temp[0];
                $end = $temp[1];
                $cond=[
                    "sale_price"=>[
                        "type"=>"BETWEEN",
                        "val"=> "$start AND $end"
                    ]
                ];
            // SELECT * FROM view_product WHERE sale_price >= 1000000
                if( $end == "greater")
                {
                    $cond=[
                        "sale_price"=>[
                            "type"=>">=",
                            "val"=> "$start"
                        ]
                    ];
                }
            }
            // SELECT * FROM view_product ORDER BY sale_price ASC 
            $sort = $_GET["sort"] ?? null;
            if($sort)
            {
                $temp = explode("-", $sort);
                $start = $temp[0];
                $end = $temp[1];
                $mapCol = ["price"=>"sale_price","alpha"=>"name","created"=>"created_date"];
                $column = $mapCol[$start];
                $sorts = [$column=>$end]; // truyền null nó sẽ lỗi, phải truyển array
            }
            
            // Search 
            // SELECT * view_product WHERE name LIKE %$search%
            $search = $_GET["search"] ?? null;
            if($search)
            {
                $cond=[
                    "name"=>[
                        "type"=>"LIKE",
                        "val"=> "'%$search%'" // phải có '' biểu thị chuỗi
                    ]
                ];
            }

            $products= $productRepository->getBy($cond,$sorts,$page,$item_per_page); 
            $totalProduct = $productRepository->getBy($cond,$sorts);
            $pageNumber = ceil(count($totalProduct)/$item_per_page);
            
            require ABSPATH_SITE. "view/product/index.php";
        }
        function show()
        {
            $id = $_GET["id"];
            $productRepository = new ProductRepository;
            $product =  $productRepository->find( $id );
             // lấy danh mục sp 
            $categoryRepository = new CategoryRepository;
            $categoryProduct = $categoryRepository ->getAll();   
            $price_range = $_GET["price-range"]??null;

            $category_id = $_GET["category_id"]??null;
            $category_id = $product->getCategoryId();
            // SELECT * FROM view_product WHERE category_id = $category_id AND id != $id 
            // nghĩa là lấy hết tất cả các mục của thằng có category_id và trừ ra thằng có id đang show 
            $conds = [
                "category_id" => [
                    "type" => "=",
                    "val"  => $category_id
                ],
                "id" =>[
                    "type"=> "!=",
                    "val" => $id
                ]

            ];
           $relatedProducts = $productRepository->getBy($conds);


            require ABSPATH_SITE. "view/product/show.php";
        }
        function ajaxSearch()
        {
            $pattern = $_GET["pattern"];
            $productRepository = new ProductRepository;
            $products =  $productRepository->getByPattern( $pattern );
            require ABSPATH_SITE. "view/product/ajaxSearch.php";
        }
        function storeComment()
        {
            $data = [
                "email" => $_POST["email"],
                "fullname" => $_POST["fullname"],
                "star" => $_POST["rating"],
                "created_date" => date("Y-m_d H:i:s"),
                "description" => $_POST["description"],
                "product_id" => $_POST["product_id"],
            ];
            $commentRepository = new CommentRepository;
            $commentRepository->save($data);

            // lấy danh sách comment bao gồm cái mới lưu vào database 
            $productRepository = new ProductRepository;
            $product =  $productRepository->find( $_POST["product_id"] ); // trả về 1 Object, thì trong cái Object ấy nó sẽ có thuộc tính "id"
            $comments =  $product->getComments(); // nó mới lấy "id" trỏ xuống lấy dữ liệu
           require ABSPATH_SITE. "layout/comments.php";

        }
    }
?>