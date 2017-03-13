<?php
require_once ( 'PHPMailerAutoload.php' ); //他のファイルも読み込むのでこれだけでok
function send_mail($to,$subject,$body,$fromname){
    $from = "isitjustmekazu@gmail.com"; //俺のアドレス
    $smtp_user = "isitjustmekazu@gmail.com";
    $smtp_password = "kazoogon";
    
    $mail = new PHPMailer();
    $mail->IsSMTP();
    $mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
    $mail->SMTPAuth = true;
    $mail->CharSet = 'utf-8';
    $mail->SMTPSecure = 'tls';
    $mail->Host = "smtp.gmail.com";
    $mail->Port = 587;
    $mail->IsHTML(false);
    $mail->Username = $smtp_user;
    $mail->Password = $smtp_password; 
    $mail->SetFrom($smtp_user);
    $mail->From     = $fromaddress;
    $mail->Subject = $subject;
    $mail->Body = $body;
    $mail->AddAddress($to);
    
    if( !$mail -> Send() ){
        $message  = "Message was not sent<br/ >";
        $message .= "Mailer Error: " . $mailer->ErrorInfo;
    } else {
        $message  = "Message has been sent";
    }
    
    echo $message;
}
    
    ?>