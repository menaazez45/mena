<?php
include("connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $is_owner = isset($_POST['is_owner']) ? 1 : 0;

    // تحقق من صحة المدخلات
    if ($password !== $confirm_password) {
        echo "كلمتا المرور غير متطابقتين.";
    } else {
        // تأمين المدخلات
        $username = $conn->real_escape_string($username);
        $password = $conn->real_escape_string($password);
        $password = password_hash($password, PASSWORD_BCRYPT); // تشفير كلمة المرور

        // التحقق من أن اسم المستخدم غير موجود بالفعل
        $sql = "SELECT id FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            echo "اسم المستخدم موجود بالفعل. اختر اسم مستخدم آخر.";
        } else {
            // إدخال المستخدم الجديد في قاعدة البيانات
            $sql = "INSERT INTO users (username, password, is_owner) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssi", $username, $password, $is_owner);

            if ($stmt->execute()) {
                echo "تم إنشاء الحساب بنجاح. يمكنك الآن تسجيل الدخول.";
            } else {
                echo "حدث خطأ أثناء إنشاء الحساب. حاول مرة أخرى.";
            }
        }

        $stmt->close();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إنشاء حساب</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .register-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #0056b3;
        }
        .error {
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h1>إنشاء حساب</h1>
        <form action="register.php" method="post">
            <label for="username">اسم المستخدم:</label>
            <input type="text" name="username" id="username" required>
            <label for="password">كلمة المرور:</label>
            <input type="password" name="password" id="password" required>
            <label for="confirm_password">تأكيد كلمة المرور:</label>
            <input type="password" name="confirm_password" id="confirm_password" required>
            <label for="is_owner">
                <input type="checkbox" name="is_owner" id="is_owner"> هل أنت صاحب الموقع؟
            </label>
            <button type="submit">إنشاء الحساب</button>
        </form>
    </div>
</body>
</html>
