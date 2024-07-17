<?php
session_start();
include("connect.php");

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    echo "هذه الصفحة متاحة فقط لصاحب الموقع.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $descrip = $_POST['descrip'];
    $file = $_FILES['file'];
    $target_dir = "website/videos/";

    // تحقق من وجود مجلد الفيديوهات، وإن لم يكن موجودًا، قم بإنشائه
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $target_file = $target_dir . basename($file["name"]);
    $uploadOk = 1;
    $videoFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // أنواع الملفات المسموح بها
    $allowedTypes = array("mp4", "avi", "mov", "3gp", "mpeg");
    if (!in_array($videoFileType, $allowedTypes)) {
        echo "عذرًا، فقط ملفات MP4, AVI, MOV, 3GP, MPEG مسموح بها.";
        $uploadOk = 0;
    }

    // التحقق من وجود الملف بالفعل
    if (file_exists($target_file)) {
        echo "عذرًا، الملف موجود بالفعل.";
        $uploadOk = 0;
    }

    // التحقق من حجم الملف
    if ($file["size"] > 9900000000*9900000000*9900000000*9900000000*9900000000*9900000000*9900000000*9900000000*9900000000*9900000000*9900000000*9900000000*9900000000*9900000000*9900000000*9900000000*9900000000*9900000000) {
        echo "عذرًا، الملف كبير جدًا.";
        $uploadOk = 0;
    }

    // التحقق من حالة $uploadOk
    if ($uploadOk == 0) {
        echo "عذرًا، لم يتم رفع ملفك.";
    } else {
        if (move_uploaded_file($file["tmp_name"], $target_file)) {
            $user_id = $_SESSION['user_id']; // الحصول على معرف المستخدم من الجلسة
            $sql = "INSERT INTO videos (title, descrip, filename, user_id) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssi", $title, $descrip, $target_file, $user_id);

            if ($stmt->execute()) {
                // إعادة توجيه المستخدم إلى صفحة عرض الفيديوهات
                header("Location: show.php");
                exit;
            } else {
                echo "خطأ: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "عذرًا، حدث خطأ أثناء رفع ملفك.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>رفع فيديو</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 90%;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
            margin-top: 20px;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin: 10px 0 5px;
            color: #333;
        }
        input[type="text"], textarea, input[type="file"] {
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            margin-bottom: 20px;
        }
        button {
            padding: 10px;
            border-radius: 5px;
            border: none;
            background-color: #007bff;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>رفع فيديو</h1>
        <form action="upload.php" method="post" enctype="multipart/form-data">
            <label for="title">عنوان الفيديو:</label>
            <input type="text" name="title" id="title" required>
            <label for="descrip">وصف الفيديو:</label>
            <textarea name="descrip" id="descrip" rows="4"></textarea>
            <label for="file">اختر الفيديو:</label>
            <input type="file" name="file" id="file" accept="video/*" required>
            <button type="submit">رفع الفيديو</button>
        </form>
    </div>
</body>
</html>