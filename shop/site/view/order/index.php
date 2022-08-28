<?php require ABSPATH_SITE. "layout/header.php" ?>
<main id="maincontent" class="page-main">
    <div class="container">
        <div class="row">
            <div class="col-xs-9">
                <ol class="breadcrumb">
                    <li><a href="/" target="_self">Trang chủ</a></li>
                    <li><span>/</span></li>
                    <li class="active"><span>Tài khoản</span></li>
                </ol>
            </div>
            <div class="clearfix"></div>
            <aside class="col-md-3">
                <div class="inner-aside">
                    <div class="category">
                        <ul>
                            <li>
                                <a href="index.php?c=customer&a=info" title="Thông tin tài khoản" target="_self">Thông tin
                                    tài khoản
                                </a>
                            </li>
                            <li>
                                <a href="index.php?c=customer&a=shipping" title="Địa chỉ giao hàng mặc định"
                                    target="_self">Địa chỉ giao hàng mặc định
                                </a>
                            </li>
                            <li class="active">
                                <a href="index.php?c=order&a=index" target="_self">Đơn hàng của tôi
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </aside>
            <div class="col-md-9 order">
                <div class="row">
                    <div class="col-xs-6">
                        <h4 class="home-title">Đơn hàng của tôi</h4>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-12">
                        <!-- Mỗi đơn hàng -->
                        <?php foreach ($orders as $key => $order):?>
                        <div class="row">
                            <div class="col-md-12">
                                <h5>Đơn hàng <a
                                        href="?c=order&a=show&id=<?=$order->getId()?>">#<?=$order->getId()?></a>
                                </h5>
                                <span class="date">
                                    Đặt hàng <?=$order->getCreatedDate()?> </span>
                                <hr>
                                <!-- // $order-> getOrderItems(); // ta được 
                                        //                                             array (size=4)
                                        //   0 => 
                                        //     object(OrderItem)[11]
                                        //       protected 'product_id' => string '2' (length=1)
                                        //       protected 'order_id' => string '237' (length=3)
                                        //       protected 'qty' => string '1' (length=1)
                                        //       protected 'unit_price' => string '190000' (length=6)
                                        //       protected 'total_price' => string '190000' (length=6)
                                        //   1 => 
                                        //     object(OrderItem)[12]
                                        //       protected 'product_id' => string '3' (length=1)
                                        //       protected 'order_id' => string '237' (length=3)
                                        //       protected 'qty' => string '1' (length=1)
                                        //       protected 'unit_price' => string '849000' (length=6)
                                        //       protected 'total_price' => string '849000' (length=6)
                                        // muốn truy cập zô thằng zon nữa phải dùng foreach -->
                                <?php 
                                        foreach ( $order-> getOrderItems() as $key => $orderItem):
                                        $product = $orderItem->getProduct()
                                        ?>
                                <div class="row">
                                        
                                    <div class="col-md-2">
                                        <img src="../upload/<?=$product->getFeaturedImage()?>" alt="" class="img-responsive">
                                    </div>
                                    <div class="col-md-3">
                                        <a class="product-name" href="<?=$router->generate('product-detail',["slugName"=>slugify($product->getName()),"id"=>$product->getId()])?>"> <?=$product->getName()?> </a>
                                    </div>
                                    <div class="col-md-2">
                                        Số lượng: <?=$orderItem->getQty()?>
                                    </div>
                                    <div class="col-md-2">
                                      <?= $order->getStatus()->getDescription()?>
                                    </div>
                                    <div class="col-md-3">
                                        Giao hàng ngày <?=$order->getDeliveredDate()?>
                                    </div>
                                </div>
                                <?php endforeach ?>

                            </div>
                        </div>
                        <?php endforeach ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php require ABSPATH_SITE. "layout/footer.php" ?>