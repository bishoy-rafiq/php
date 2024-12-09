<?php
require 'db.php';
    

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHmailer\Exception;
use PHPMailer\PHPMailer\SMTP;  

require __DIR__ . '/vendor/autoload.php';  


$mail = new PHPMailer(true);
$mail->SMTPDebug = SMTP::DEBUG_SERVER;


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['email'])) {
        $email = $_POST['email'];
        $verification_code = rand(100000, 999999); 

       
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
           
            $stmt = $pdo->prepare("UPDATE users SET verification_code = ? WHERE email = ?");
            $stmt->execute([$verification_code, $email]);
        } else {
          
            $stmt = $pdo->prepare("INSERT INTO users (email, verification_code, is_verified) VALUES (?, ?, FALSE)");
            $stmt->execute([$email, $verification_code]);
        }

      
        try {
           
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';  // خادم SMTP لـ Gmail
            $mail->SMTPAuth = true;
            $mail->Username = 'bishorafek0@gmail.com';  // استبدل هذا بعنوان بريدك الإلكتروني
            $mail->Password = 'Password';  // كلمة مرور التطبيق في حالة تفعيل التحقق بخطوتين
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
        
            // إعداد المرسل والمستقبل
            $mail->setFrom('bishorafek0@gmail.com', 'Mailer');
            $mail->addAddress($email); 
    
            // محتوى البريد الإلكتروني
            $mail->isHTML(true);
            $mail->Subject = 'رمز التحقق الخاص بك';
            $mail->Body    = "رمز التحقق الخاص بك هو: $verification_code";
            $mail->CharSet = 'UTF-8';
            
           
            $mail->send();
            echo 'تم إرسال البريد الإلكتروني بنجاح';
    
            // تحويل المستخدم إلى صفحة إدخال الرمز
            header("Location: verify.php?email=" . urlencode($email));
            exit;

        } catch (Exception $e) {
            echo "لم يتم إرسال البريد الإلكتروني. خطأ: {$mail->ErrorInfo}";
        }

    } else {
        echo "يرجى إدخال بريد إلكتروني.";
    }
} else {
    echo "طلب غير صالح.";
}
?>

