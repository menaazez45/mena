<?php
session_start();
include("connect.php"); // التأكد من أن ملف connect.php يحتوي على إعدادات الاتصال بقاعدة البيانات

// التحقق من صلاحيات المستخدم
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    echo "<p style='color: red; text-align: center;'>هذه الصفحة متاحة فقط لصاحب الموقع.</p>";
    exit;
}

// التحقق من إرسال النموذج
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['message'])) {
        $message = $_POST['message'];

        // تحضير وإعداد استعلام الإدخال
        $sql = "INSERT INTO sendforteacher (message) VALUES (?)";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("s", $message);

            if ($stmt->execute()) {
                $success_message = "تم إرسال رسالتك بنجاح!";
            } else {
                $error_message = "حدث خطأ أثناء إرسال الرسالة: " . $stmt->error;
            }

            $stmt->close();
        } else {
            $error_message = "حدث خطأ في تحضير الاستعلام: " . $conn->error;
        }
    } else {
        $error_message = "الرسالة مفقودة.";
    }

    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إرسال رسالة</title>
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
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 400px;
            max-width: 100%;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
        }
        textarea {
            width: 100%;
            height: 150px;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
            border: 1px solid #ccc;
            box-sizing: border-box;
            font-size: 16px;
        }
        button {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 4px;
            background-color: #007bff;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #0056b3;
        }
        .message {
            text-align: center;
            margin: 10px 0;
            font-size: 16px;
        }
        .success {
            color: green;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>إرسال رسالة</h1>
        <form method="post" action="">
            <label for="message">الرسالة:</label>
            <textarea id="message" name="message" required></textarea><br>
            <button type="submit">إرسال</button>
            <?php if (isset($success_message)): ?>
                <p class="message success"><?php echo $success_message; ?></p>
            <?php endif; ?>
            <?php if (isset($error_message)): ?>
                <p class="message error"><?php echo $error_message; ?></p>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>