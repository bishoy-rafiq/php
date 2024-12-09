<?php
require 'db.php';


if (isset($_GET['email'])) {
    $email = $_GET['email'];


    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

   
    if ($user) {
       
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
          
            $name = $_POST['name'];
            $phone = $_POST['phone'];
            $city = $_POST['city'];
            $location = $_POST['location'];

         
            if (!empty($name) && !empty($phone) && !empty($city) && !empty($location)) {
              
                $stmt = $pdo->prepare("UPDATE users SET name = ?, phone = ?, city = ?, location = ? WHERE email = ?");
                $stmt->execute([$name, $phone, $city, $location, $email]);

                // إعادة توجيه المستخدم إلى الصفحة الرئيسية بعد استكمال البيانات
                header("Location: --");
                exit;
            } else {
                echo "يرجى إدخال جميع البيانات.";
            }
        }
    } else {
        echo "البريد الإلكتروني غير موجود في النظام.";
    }
} else {
    echo "طلب غير صالح.";
}
?>

