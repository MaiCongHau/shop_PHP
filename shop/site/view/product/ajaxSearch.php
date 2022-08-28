<ul class="list-unstyled">
    <?php global $router?>
    <?php foreach($products as $product): ?>
    <li>
        <a class="product-name" href="<?=$router->generate('product-detail',["slugName"=>slugify($product->getName()),"id"=>$product->getId()])?>" title="<?=$product->getName()?>"> 
        <!-- Mỗi khi bấm vào đường dẫn search ra rồi thì nó sẽ lấy id gắn vào attr href của thẻ a rồi qua trang show để show sp đó ra -->
            <img style="width:50px" src="../upload/<?=$product->getFeaturedImage()?>" alt="">
            <?=$product->getName()?>
        </a>
    </li>
    <?php endforeach ?>
</ul>