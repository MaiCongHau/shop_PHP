
<?php 
 use Firebase\JWT\JWT;
 use Firebase\JWT\Key; // để mã hóa giải mã 
class CustomerController
{
    function info()
    {
        $customerRepository = new CustomerRepository;
        $customer= $customerRepository->findEmail($_SESSION["email"]);
        require ABSPATH_SITE. "view/customer/info.php";
    }
    function updateInfo()
    {
        $customerRepository = new CustomerRepository;
        $customer= $customerRepository->findEmail($_SESSION["email"]);
       
        $customer->setName($_POST["fullname"]);
        $customer->setMobile($_POST["mobile"]);
        $current_password = $_POST["current_password"];
        $new_password = $_POST["password"];
        $db_password = $customer->getPassword();

        if($current_password)
        {
            if(password_verify($current_password,$db_password))
            {
                $encodePassword = password_hash( $new_password , PASSWORD_BCRYPT);
                $customer-> setPassword( $encodePassword);
                $_SESSION["success"] = "Bạn đã thay đổi mật khẩu thành công";
            }
            else 
            {
                $_SESSION["error"]= "Bạn nhập sai mật khẩu hiện tại";
                header("location:?c=customer&a=info");
                exit;
            }
        }

        if( $customerRepository->update($customer))
        {   
            $_SESSION["name"] = $customer->getName();
            $_SESSION["success"] = "Bạn đã cập nhật thông tin tài khoản thành công";
        }
        else{
            $_SESSION["error"]= $customerRepository->getError();
        }
      header("location:/");
    }
    function shipping()
    {
        $customerRepository = new CustomerRepository;
        $customer= $customerRepository->findEmail($_SESSION["email"]);
        require ABSPATH_SITE. "layout/variable_address.php";
        require ABSPATH_SITE. "view/customer/shipping.php";
    }
    function updateShipping()
    {
        $customerRepository = new CustomerRepository;
        $customer= $customerRepository->findEmail($_SESSION["email"]);
        $customer->setShippingName($_POST["fullname"]);
        $customer->setShippingMobile($_POST["mobile"]);
        $customer->setWardId($_POST["ward"]);
        $customer->setHousenumberStreet($_POST["address"]);
        if( $customerRepository->update($customer))
        {   
            $_SESSION["name"] = $customer->getName();
            $_SESSION["success"] = "Bạn đã cập nhật thành công thông tin giao hàng";
        }
        else{
            $_SESSION["error"]= $customerRepository->getError();
        }
        header("location:/");
    }
    function forgotPassword()
    {
       // gửi email để reset tài khoản 
       $email =$_POST["email"];
       $customerRepository = new CustomerRepository; 
       $customer = $customerRepository ->findEmail($email);
       if(empty($customer))
       {
        $_SESSION["error"] = "$email không tồn tại";
        header("location:/");
        exit;
       }
       $key = JWT_KEY;
       $payload = [
        "email"=>$email
           ];
       $code = JWT::encode($payload, $key, 'HS256');
       $acticeUrl = get_domain()."/index.php?c=customer&a=resetPassword&code=$code";
       $mailserver = new MailService;
       $content = "
           Chào  $email, <br>
           Vui lòng click vào link bên dưới để thiết lập lại <br>
           <a href='$acticeUrl'>Reset Password</a> " ;
       $mailserver->send($email,"Active account",$content);
       $_SESSION["success"] = "Vui lòng check mail để ResetPassword";
       header("location:/");
    }
    function resetPassword()
    {
        $code= $_GET["code"];
        try {
            $decoded = JWT::decode($code, new Key(JWT_KEY, 'HS256'));
            $email = $decoded ->email;
            $customerRepository = new CustomerRepository; 
            $customer =$customerRepository ->findEmail($email);
            // trường hợp ko có thằng $customer, tức là đã bị xóa 
            if(!$customer)
            {
                $_SESSION["error"] = "Email $email không tồn tại";
                header("location:/");
            }   
            require ABSPATH_SITE. "view/customer/resetPassword.php";

        } catch (\Throwable $th) {
            echo "Dừng đi bn !!";
        }
    }
    function updatePassword()
    {
        // phải dùng mã code đã mã hóa ko dùng email để dò lên database
        $code= $_POST["code"];
        var_dump( $code);
        try {
            $decoded = JWT::decode($code, new Key(JWT_KEY, 'HS256'));
            $email = $decoded ->email;
            $customerRepository = new CustomerRepository; 
            $customer =$customerRepository ->findEmail($email);
            $new_password = $_POST["password"];
            $hashPassword = password_hash($new_password,PASSWORD_BCRYPT);
            $customer->setPassword(  $hashPassword );
            $customerRepository->update($customer);
            $_SESSION["success"] = "Bạn đã thay đổi password thành công";
            header("location:/");
        } catch (\Throwable $th) {
            echo "Dừng đi bn !!";
        }
    }
}
?>