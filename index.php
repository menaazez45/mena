<?php
session_start();
include("connect.php");

// التحقق من إرسال النموذج
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // تأمين المدخلات
    $username = $conn->real_escape_string($username);
    $password = $conn->real_escape_string($password);

    $sql = "SELECT id, username, password, is_owner FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $username, $hashed_password, $is_owner);
        $stmt->fetch();

        // التحقق من كلمة المرور
        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $username;
            $_SESSION['is_admin'] = $is_owner; // تعيين صلاحية المستخدم

            header("Location: show.php"); // توجيه إلى صفحة إرسال الرسائل
            exit();
        } else {
            $error_message = "اسم المستخدم أو كلمة المرور غير صحيحة.";
        }
    } else {
        $error_message = "اسم المستخدم أو كلمة المرور غير صحيحة.";
    }

    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول</title>
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
        .login-container {
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
        .register-link {
            display: block;
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
        }
        .register-link a {
            color: #007bff;
            text-decoration: none;
        }
        .register-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>تسجيل الدخول</h1>
        <form action="index.php" method="post">
            <label for="username">اسم المستخدم:</label>
            <input type="text" name="username" id="username" required>
            <label for="password">كلمة المرور:</label>
            <input type="password" name="password" id="password" required>
            <button type="submit">تسجيل الدخول</button>
            <?php if (isset($error_message)): ?>
                <p class="error"><?php echo $error_message; ?></p>
            <?php endif; ?>
        </form>
        <div class="register-link">
            <p>ليس لديك حساب؟ <a href="register.php">إنشاء حساب جديد</a></p>
        </div>
    </div>
</body>
</html>
