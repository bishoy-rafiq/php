<?php
require 'db.php';

header("Content-Type: application/json; charset=UTF-8");

$requestMethod = $_SERVER['REQUEST_METHOD'];

switch ($requestMethod) {
    case 'GET':
        // جلب جميع المستخدمين
        $stmt = $pdo->query("SELECT id, email, name, phone, address, is_verified FROM users");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($users);
        break;

    case 'POST':
        // إضافة مستخدم جديد
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['email'], $data['name'], $data['phone'], $data['address'])) {
            $stmt = $pdo->prepare("INSERT INTO users (email, name, phone, address, is_verified) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$data['email'], $data['name'], $data['phone'], $data['address'], false]);
            echo json_encode(["message" => "تمت الإضافة بنجاح"]);
        } else {
            echo json_encode(["error" => "بيانات غير كاملة"]);
        }
        break;

    case 'PUT':
        // تعديل بيانات مستخدم
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['id'], $data['email'], $data['name'], $data['phone'], $data['address'])) {
            $stmt = $pdo->prepare("UPDATE users SET email = ?, name = ?, phone = ?, address = ? WHERE id = ?");
            $stmt->execute([$data['email'], $data['name'], $data['phone'], $data['address'], $data['id']]);
            echo json_encode(["message" => "تم التعديل بنجاح"]);
        } else {
            echo json_encode(["error" => "بيانات غير كاملة"]);
        }
        break;

    case 'DELETE':
        // حذف مستخدم
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['id'])) {
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$data['id']]);
            echo json_encode(["message" => "تم الحذف بنجاح"]);
        } else {
            echo json_encode(["error" => "معرف المستخدم غير موجود"]);
        }
        break;

    default:
       
        echo json_encode(["error" => "نوع الطلب غير مدعوم"]);
        break;
}
?>
