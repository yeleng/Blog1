<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
 use PHPMailer\PHPMailer\PHPMailer;

function SendEmail($receiver,$subject,$body){
    try {
        //Server settings
        $mail = new PHPMailer(); 
        $mail->SMTPDebug = 0;                                 // Enable verbose debug output
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->CharSet='UTF-8';
        $mail->Host = 'smtp.sina.cn';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = '13227855005m@sina.cn';                 // SMTP username
        $mail->Password = 'wrm461184988';                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 25;                                    // TCP port to connectto
    
        //Recipients
        $mail->setFrom('13227855005m@sina.cn', 'Blog'); //后面这个是发送时候的昵称
        $mail->addAddress($receiver,'Acmer');     //收件人的地址和对收件人的称号
        // 这里是收件人的地址和收件人的昵称
        //$mail->addAddress('w461184988@163.com');               // Name is optional
       // $mail->addReplyTo('info@example.com', 'Information');
        //$mail->addCC('cc@example.com');
       // $mail->addBCC('bcc@example.com');
    
        //Attachments
        // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
        // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
    
        //Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $body;
        //$mail->AltBody = 'this body is wu,i juse test';
       // return 0;
        $mail->send();
    } catch (Exception $e) {
        echo 'Message could not be sent.';
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    }
}