<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "يرجى تسجيل الدخول لعرض الفيديوهات.";
    exit;
}

$user_id = $_SESSION['user_id'];

include("connect.php");

$sql = "SELECT * FROM videos WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>عرض الفيديوهات</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 90%;
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .video-item {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #fafafa;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .video-item h2 {
            margin: 0;
            font-size: 18px;
            color: #333;
        }
        video {
            max-width: 100%;
            border-radius: 5px;
        }
        .button-group {
            margin-top: 10px;
        }
        .button-group a {
            display: inline-block;
            padding: 8px 15px;
            border-radius: 5px;
            color: #fff;
            text-decoration: none;
            font-size: 14px;
            margin: 0 5px;
            transition: background-color 0.3s, transform 0.2s;
        }
        .button-group a.edit-button {
            background-color: #007bff;
        }
        .button-group a.edit-button:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }
        .button-group a.delete-button {
            background-color: #dc3545;
        }
        .button-group a.delete-button:hover {
            background-color: #c82333;
            transform: scale(1.05);
        }
        .share-buttons {
            margin-top: 10px;
        }
        .share-buttons a {
            display: inline-block;
            padding: 8px 15px;
            border-radius: 5px;
            color: #fff;
            text-decoration: none;
            font-size: 14px;
            margin: 0 5px;
            transition: background-color 0.3s, transform 0.2s;
        }
        .share-buttons a.facebook-share {
            background-color: #3b5998;
        }
        .share-buttons a.facebook-share:hover {
            background-color: #2d4373;
        }
        .share-buttons a.twitter-share {
            background-color: #1da1f2;
        }
        .share-buttons a.twitter-share:hover {
            background-color: #1a91da;
        }
        .share-buttons a.whatsapp-share {
            background-color: #25d366;
        }
        .share-buttons a.whatsapp-share:hover {
            background-color: #1ac15f;
        }
        .upload-button {
            display: block;
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: #fff;
            font-size: 16px;
            text-align: center;
            cursor: pointer;
            text-decoration: none;
            margin-bottom: 20px;
            transition: background-color 0.3s, transform 0.2s;
        }
        .upload-button:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }
        /* Navbar styling */
        .navbar {
            background-color: #333;
            overflow: hidden;
            padding: 10px 0;
        }
        .navbar a {
            float: left;
            display: block;
            color: #f2f2f2;
            text-align: center;
            padding: 14px 20px;
            text-decoration: none;
        }
        .navbar a:hover {
            background-color: #ddd;
            color: black;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="upload.php">رفع فيديو</a>
        <a href="send.php">إرسال رسالة</a>
        <a href="send for teacher.php">إرسال رسالة خاصة بصاحب الموقع</a>
        <a href="sm.php">عرض</a>
        <a href="show_your.php">عرض رسائلك</a>
    </div>
    <div class="container">
        <h1>عرض الفيديوهات</h1>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $videoUrl = htmlspecialchars($row['filename']);
                $shareUrl = urlencode($videoUrl); // ترميز URL لمشاركته
                echo "<div class='video-item'>";
                echo "<h2>" . htmlspecialchars($row['title']) . "</h2>";
                echo "<video width='320' height='240' controls>
                        <source src='" . $videoUrl . "' type='video/mp4'>
                        Your browser does not support the video tag.
                      </video>";
                echo "<div class='button-group'>";
                echo "<a href='edit_video.php?id=" . $row['id'] . "' class='edit-button'>تعديل</a>";
                echo "<a href='delete_video.php?id=" . $row['id'] . "' class='delete-button'>حذف</a>";
                echo "</div>";
                echo "<div class='share-buttons'>";
                echo "<a href='https://www.facebook.com/sharer/sharer.php?u=" . $shareUrl . "' class='facebook-share' target='_blank'>مشاركة على فيسبوك</a>";
                echo "<a href='https://twitter.com/intent/tweet?url=" . $shareUrl . "' class='twitter-share' target='_blank'>مشاركة على تويتر</a>";
                echo "<a href='https://wa.me/?text=" . $shareUrl . "' class='whatsapp-share' target='_blank'>مشاركة على واتساب</a>";
                echo "</div>";
                echo "</div>";
            }
        } else {
            echo "لا توجد فيديوهات لعرضها.";
        }
        $conn->close();
        ?>
    </div>
</body>
</html>
