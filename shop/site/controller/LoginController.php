<?php 
    class LoginController{
        function form()
        {   
            global $router;
            $email = $_POST["email"];
            $password = $_POST["password"];
            $customerRepository = new CustomerRepository;
            $customer = $customerRepository->findEmail($email);
            
            if($customer)
            {
                $encodePassword = $customer->getPassword();
                if(password_verify($password, $encodePassword))
                {
                    if( $customer->getIsActive()=="1")
                    {
                        $_SESSION["success"] = "Login thành công";
                        $_SESSION["email"]= $email; // dùng ở thằng header 
                        $_SESSION["name"] = $customer ->getName();// dùng ở thằng header
                    }
                    else{
                        $_SESSION["error"] = "Vui lòng kích hoạt tài khoản bằng cách click vào link email đã đăng kí";
                    }
                    header("location:". $router->generate('home')); 
                    exit;
                
                }
            }
            header("location:". $router->generate('home'));   
            $_SESSION["error"] = "Vui lòng nhập lại email và mật khẩu";

        }
        function google()
        {

        }
        function facebook()
        {

        }
        function logout()
        {
            global $router;
            session_destroy();
            header("location:". $router->generate('home'));
        }
    }
?>