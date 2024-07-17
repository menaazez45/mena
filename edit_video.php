<?php
include("connect.php");
session_start();

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    echo "طلب غير صالح.";
    exit;
}

$user_id = $_SESSION['user_id'];
$video_id = $_GET['id'];

$sql = "SELECT * FROM videos WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $video_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "لا يمكنك تعديل هذا الفيديو.";
    exit;
}

$video = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $descrip = $_POST['descrip'];

    $sql = "UPDATE videos SET title = ?, descrip = ? WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssii", $title, $descrip, $video_id, $user_id);

    if ($stmt->execute()) {
        echo "تم تعديل الفيديو بنجاح.";
    } else {
        echo "خطأ: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل الفيديو</title>
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
        input[type="text"], textarea {
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
        <h1>تعديل الفيديو</h1>
        <form action="edit_video.php?id=<?php echo $video_id; ?>" method="post">
            <label for="title">عنوان الفيديو:</label>
            <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($video['title']); ?>" required>
            <label for="descrip">وصف الفيديو:</label>
            <textarea name="descrip" id="descrip" rows="4"><?php echo htmlspecialchars($video['descrip']); ?></textarea>
            <button type="submit">تعديل الفيديو</button>
        </form>
    </div>
</body>
</html>
