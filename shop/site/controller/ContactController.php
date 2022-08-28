<?php
class ContactController extends ProductRepository {
    function form()
    { // hiễn thị form
      require ABSPATH_SITE. "view/contact/form.php";
    } 
    function send()
    {
        // send email to shop owner
      sleep(5);
      $mailservice = new  MailService;
      $to = "maiconghau263@gmail.com";
      $subject= "Shop xin chủ shop";
      $email = $_POST["email"];
      $name = $_POST["fullname"];
      $mobile = $_POST["mobile"];
      $title = $_POST["content"];
      $content ="Đặt hàng $email $name $mobile <br>
      $title ";
      $mailservice->send($to,$subject,$content );
      echo "Đã gửi mail thành công tới chủ shop";
    }
}  
?>