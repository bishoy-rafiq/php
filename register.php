<?php
require 'db.php';

if (isset($_GET['email'])) {
    $email = $_GET['email'];

    // جلب بيانات المستخدم بناءً على البريد الإلكتروني
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // قراءة بيانات المستخدم من النموذج
            $name = trim($_POST['name'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $city = trim($_POST['city'] ?? '');
            $location = trim($_POST['location'] ?? '');

            // التحقق من أن جميع الحقول قد تم تعبئتها
            if (!empty($name) && !empty($phone) && !empty($city) && !empty($location)) {
                // تحديث بيانات المستخدم في قاعدة البيانات
                $stmt = $pdo->prepare("UPDATE users SET name = ?, phone = ?, city = ?, location = ? WHERE email = ?");
                $stmt->execute([$name, $phone, $city, $location, $email]);

                // إعادة توجيه المستخدم إلى الصفحة الرئيسية بعد استكمال البيانات
                header("Location: main_page.php"); // عدل "main_page.php" إلى الصفحة المناسبة
                exit;
            } else {
                echo "يرجى إدخال جميع البيانات المطلوبة.";
            }
        }
    } else {
        echo "البريد الإلكتروني غير موجود في النظام.";
    }
} else {
    echo "طلب غير صالح.";
}
?>
