<?php
include("connect.php");
session_start();

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    echo "طلب غير صالح.";
    exit;
}

$user_id = $_SESSION['user_id'];
$video_id = $_GET['id'];

$sql = "SELECT filename FROM videos WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $video_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "لا يمكنك حذف هذا الفيديو.";
    exit;
}

$video = $result->fetch_assoc();
$filename = $video['filename'];

$sql = "DELETE FROM videos WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $video_id, $user_id);

if ($stmt->execute()) {
    unlink($filename); // حذف الملف من الخادم
    echo "تم حذف الفيديو بنجاح.";
} else {
    echo "خطأ: " . $stmt->error;
}
?>
