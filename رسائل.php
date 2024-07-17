<?php
// تحقق من زر الإرسال
if(isset($_POST['submit'])) {
    // بيانات قاعدة البيانات
    include("connect.php");
    
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}



    // الحصول على بيانات المستخدم من نموذج تسجيل الدخول
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
   
    // استعلام للتحقق من اسم المستخدم وكلمة المرور
    $sql = "SELECT * FROM `users` WHERE username = '$username' AND password = '$password' AND email='$email'";
    $result = mysqli_query($conn, $sql);

 
// استعلام للتحقق من اسم المستخدم وكلمة المرور
$sql = "SELECT * FROM `users` WHERE username = '$username' AND password = '$password' AND email='$email'";
$result = mysqli_query($conn, $sql);

// التحقق من وجود صف واحد على الأقل
if (mysqli_num_rows($result) == 1) {
    // تسجيل الدخول ناجح
    session_start();
    $_SESSION['username'] = $username;
    $_SESSION['email'] = $email;
    $_SESSION['password'] = $password;

    // استعلام للحصول على قيمة الرقم العضوي
    $row = mysqli_fetch_assoc($result);
    $prm = $row['prm'];
    $_SESSION['prm'] = $prm;

    header("Location: عرض.php");
    exit(); // تأكد من إيقاف تنفيذ السكريبت بعد التوجيه
} else {
    // فشل تسجيل الدخول
    echo "خطأ: اسم المستخدم أو كلمة المرور غير صحيحة!";
}


    // إغلاق الاتصال
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>صفحة تسجيل الدخول</title>
</head>
<style>
  body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
        }
        h2 {
            color: #333;
        }
        form {
            width: 300px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        label {
            display: block;
            text-align: right;
            margin-bottom: 10px;
            font-weight: bold;
            color: #333;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 3px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            border: none;
            color: #fff;
            border-radius: 3px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        a {
            text-decoration: none;
            color: #007bff;
            transition: color 0.3s;
        }
        a:hover {
            color: #0056b3;
        }
    </style>
<body>
    <h2>تسجيل الدخول</h2>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for="username">اسم المستخدم:</label>
        <input type="text" id="username" name="username" required><br><br>
        <label for="email">الحساب:</label>

        <input type="email" id="email" name="email" required><br><br>

        <label for="password">كلمة المرور:</label>

        <input type="password" id="password" name="password" required><br><br>

        <input type="submit" name="submit" value="تسجيل الدخول">
    </form>
    <br>
    
</body>
</html>