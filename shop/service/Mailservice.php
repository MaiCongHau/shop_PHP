<?php 
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
    class MailService{
        function send($to,$subject,$content )
        {
            //Load Composer's autoloader
require '../vendor/autoload.php';

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);
$mail->CharSet = 'UTF-8';// fix tiếng Việt
try {
    //Server settings
    $mail->SMTPDebug = 0;                    // nếu muốn Debug ra xem đã gửi thành công hay ko thì thay thế 0 =SMTP::DEBUG_SERVER;
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = SMTP_HOST;                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = SMTP_USERNAME;                     //SMTP username
    $mail->Password   = SMTP_SECRET;                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients, gửi cho ai 
    $mail->setFrom(SMTP_USERNAME);
    $mail->addAddress($to);     //Add a recipient

    // //Attachments
    // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $content;
   
    $mail->send();
    // echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
        }
    }
?>