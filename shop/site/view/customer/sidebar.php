<aside class="col-md-3">
    <div class="inner-aside">
        <div class="category">
            <ul>
                <li class="<?= $_GET["a"] == "info" ? "active" :""?>">
                    <a href="index.php?c=customer&a=info" title="Thông tin tài khoản" target="_self">Thông tin
                        tài khoản
                    </a>
                </li>
                <li class="<?= $_GET["a"] == "shipping" ? "active" :""?>">
                    <a href="index.php?c=customer&a=shipping" title="Địa chỉ giao hàng mặc định" target="_self">Địa chỉ
                        giao hàng mặc định
                    </a>
                </li>
                <li class="<?= in_array($_GET["a"] ,["index","show"]) ? "active" :""?>">
                    <a href="index.php?c=order&a=index" target="_self">Đơn hàng của tôi
                    </a>
                </li>
            </ul>
        </div>
    </div>
</aside>