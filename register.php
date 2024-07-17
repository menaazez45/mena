<?php
include("connect.php");

$is_owner_option_visible = true; // Default value

// التحقق مما إذا كان هناك حساب واحد كصاحب الموقع
$sql = "SELECT COUNT(*) AS count FROM users WHERE is_owner = 1";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

if ($row['count'] > 0) {
    $is_owner_option_visible = false;
}

// التحقق مما إذا كان المستخدم قد دفع بالفعل
$payment_verified = isset($_GET['payment_verified']) && $_GET['payment_verified'] == 'true';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!$payment_verified) {
        echo "يرجى إكمال عملية الدفع أولاً.";
        exit;
    }

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $is_owner = isset($_POST['is_owner']) ? 1 : 0;

    $errors = [];

    // تحقق من صحة المدخلات
    if (empty($username) || empty($password) || empty($confirm_password)) {
        $errors[] = "جميع الحقول مطلوبة.";
    } elseif ($password !== $confirm_password) {
        $errors[] = "كلمتا المرور غير متطابقتين.";
    } elseif (strlen($password) < 6) {
        $errors[] = "كلمة المرور يجب أن تكون على الأقل 6 أحرف.";
    }

    if (empty($errors)) {
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
            $errors[] = "اسم المستخدم موجود بالفعل. اختر اسم مستخدم آخر.";
        } else {
            // إدخال المستخدم الجديد في قاعدة البيانات
            $sql = "INSERT INTO users (username, password, is_owner) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssi", $username, $password, $is_owner);

            if ($stmt->execute()) {
                echo "تم إنشاء الحساب بنجاح. يمكنك الآن تسجيل الدخول.";
                exit; // إنهاء السكربت بعد الرسالة الناجحة
            } else {
                $errors[] = "حدث خطأ أثناء إنشاء الحساب. حاول مرة أخرى.";
            }
        }

        $stmt->close();
    }

    $conn->close();
}
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
        .payment-section {
            text-align: center;
            margin-top: 20px;
        }
        .payment-section a {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px 0;
            color: #fff;
            background-color: #007bff;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
        }
        .payment-section a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h1>إنشاء حساب</h1>
        <?php if (!empty($errors)): ?>
            <div class="error">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <?php if (!$payment_verified): ?>
            <div class="payment-section">
                <p>يرجى إكمال عملية الدفع لتتمكن من إنشاء الحساب.</p>
                <!-- رابط الدفع -->
                <a href="https://www.paypal.com/ncp/payment/GHKYMMPDKXTFG" target="_blank">ادفع الآن عبر PayPal</a>
                <p>بعد الدفع، يرجى العودة إلى هذه الصفحة لتسجيل حسابك.</p>
            </div>
        <?php else: ?>
            <form action="register.php" method="post">
                <label for="username">اسم المستخدم:</label>
                <input type="text" name="username" id="username" required>
                <label for="password">كلمة المرور:</label>
                <input type="password" name="password" id="password" required>
                <label for="confirm_password">تأكيد كلمة المرور:</label>
                <input type="password" name="confirm_password" id="confirm_password" required>
                <?php if ($is_owner_option_visible): ?>
                    <label for="is_owner">
                        <input type="checkbox" name="is_owner" id="is_owner"> هل أنت صاحب الموقع؟
                    </label>
                <?php endif; ?>
                <button type="submit">إنشاء الحساب</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
