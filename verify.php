<?php
require 'db.php';


if (isset($_GET['email'])) {
    $email = $_GET['email'];

    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

  
    if ($user) {
       
        if ($user['is_verified'] == TRUE) {
            if (empty($user['name']) || empty($user['phone']) || empty($user['address'])) {
              
                header("Location: register.php?email=" . urlencode($email));  
                exit;
            } else {
                
                header("Location: main_page.php");
                exit;
            }
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $verification_code = $_POST['verification_code'];

            if ($user['verification_code'] == $verification_code) {
                $stmt = $pdo->prepare("UPDATE users SET is_verified = TRUE WHERE email = ?");
                $stmt->execute([$email]);

                if (empty($user['name']) || empty($user['phone']) || empty($user['address'])) {
                    header("Location: fill_data.php?email=" . urlencode($email));
                    exit;
                } else {
                    header("Location: main_page.php");
                    exit;
                }
            } else {
                echo "رمز التحقق غير صحيح.";
            }
        }
    } else {
        header("Location: registerEmail.php");  
        exit;
    }
} else {
    echo "طلب غير صالح.";
}
?>

