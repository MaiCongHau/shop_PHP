<?php 
    use Firebase\JWT\JWT;
    use Firebase\JWT\Key;
    class RegisterController{
        function create()
        {
            $secret = "6LcBTL0gAAAAAI6qlHXZ57xGxS9D2zEk3ZQJr_57"; // secret key
            $gRecaptchaResponse = $_POST["g-recaptcha-response"];
            $remoteIp = "127.0.0.1";
            $recaptcha = new \ReCaptcha\ReCaptcha($secret);
            $resp = $recaptcha->setExpectedHostname(get_host_name())
                            ->verify($gRecaptchaResponse, $remoteIp);
            if ($resp->isSuccess()) {
                // Verified!
              
            } else {
                $errors = $resp->getErrorCodes();
            } 
           $customerRepository = new CustomerRepository;
           $data =[
            "name"=> $_POST["fullname"],
            "password" => password_hash($_POST["password"],PASSWORD_BCRYPT), // để đẩy lên nó mã hóa
            "mobile"=>$_POST["mobile"],
            "email" =>$_POST["email"],
            "login_by"=>"form", // kiểu login
            "shipping_name"=>"",
            "shipping_mobile"=>"",
            "ward_id"=>null,
            "is_active"=>0, // chưa active sẽ gửi email xác nhận xem đúng ko thì cho cái trường này lên 1
            "housenumber_street"=>""
           ];
           if($customerRepository->save($data))
           {
            $_SESSION["success"] = "Đã tạo tài khoản thành công";
            // gửi email để kích hoạt tài khoản 
            $email =$_POST["email"];
            $key = JWT_KEY;
            $payload = [
             "email"=>$email
                ];
            $code = JWT::encode($payload, $key, 'HS256');
            $acticeUrl = get_domain_site()."/index.php?c=register&a=active&code=$code";
            $mailserver = new MailService;
            $content = "
                Chào  $email, <br>
                Vui lòng click vào link bên dưới để kích hoạt tài khoản <br>
                <a href='$acticeUrl'>Activate Account</a> " ;
            $mailserver->send( $email,"Active account",$content);
           }else 
           {
            $_SESSION["error"] =$customerRepository->getError() ;
           }
           header("location:index.php");
        }

        function active()
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
                $customer-> setIsActive(1); // set lại thằng active lên 1 
                $customerRepository ->update($customer); // xong update nó lên csdl
                $_SESSION["success"]= "Tài khoản đã được active";
                // cho phép login luôn 
                $_SESSION["email"] = $email;  // dùng ở thằng header
                $_SESSION["name"] = $customer->getName();// dùng ở thằng header
                header("location:/");
            } catch (\Throwable $th) {
                echo "Dừng đi bn !!";
            }
           
        }

        function notExistingEmail()
        {
            $email = $_GET["email"];
            $customerRepository = new CustomerRepository;
            $customer = $customerRepository->findEmail($email);
            // var_dump( $customer);
            if(!$customer)
            {
                echo "true";
                return;
            }
            echo "false";
            return ;
          
        }
    }
?>