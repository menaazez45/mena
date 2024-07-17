<?php

// تحقق من زر الإرسال
if(isset($_POST['submit'])) {
    // بيانات قاعدة البيانات
 include("connect.php");


    // الحصول على بيانات المستخدم من نموذج إنشاء الحساب
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $message = $_POST['message'];


    // استعلام لإدخال بيانات المستخدم إلى قاعدة البيانات
    $sql = "INSERT INTO messages ( message, email,name,phone) VALUES ('$message','$email','$name','$phone')";

    if (mysqli_query($conn, $sql)) {
        echo "تم الارسال بنجاح!";
       
            header("Location: show.php");
            exit(); // 
    } else {
        echo "خطأ: " . $sql . "<br>" . mysqli_error($conn);
    }

    // إغلاق الاتصال
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ارسال رساله</title>
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
        #phone{
width: 290px;
height: 25px;
        }
    </style>
</head>
<body>
    <h2>ارسال رساله</h2>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for="name">اسم المستخدم:</label>
        <input type="text" id="name" name="name" required><br>
        <label for="email">البريد الإلكتروني:</label>
        <input type="email" id="email" name="email" required><br>
        <label for="phone">رقم هاتف:</label>
        <input type="phone" id="phone" name="phone" required><br><br>
 <label for="message">ارسال</label>
 <input type="text" required name="message"> 

        <input type="submit" name="submit" value="ارسال">
    </form>
    <br>
    
</body>
</html>