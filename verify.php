<?php
require 'db.php';

if (isset($_GET['email'])) {
    $email = $_GET['email'];

    // جلب بيانات المستخدم بناءً على البريد الإلكتروني
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        // إذا كان الطلب من نوع POST
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // قراءة رمز التحقق من الطلب
            $verification_code = $_POST['verification_code'];

            // التحقق من صحة رمز التحقق
            if ($user['verification_code'] == $verification_code) {
                // تحديث حالة التحقق
                $stmt = $pdo->prepare("UPDATE users SET is_verified = TRUE WHERE email = ?");
                $stmt->execute([$email]);

                // التحقق من اكتمال البيانات
                if (empty($user['name']) || empty($user['phone']) || empty($user['address'])) {
                    header("Location: fill_data.php?email=" . urlencode($email));
                    exit;
                } else {
                    header("Location: register.php");
                    exit;
                }
            } else {
                echo "رمز التحقق غير صحيح.";
            }
        } else {
            echo "يجب إرسال رمز التحقق عبر POST.";
        }
    } else {
        header("Location: registerEmail.php");
        exit;
    }
} else {
    echo "طلب غير صالح.";
}
?>
