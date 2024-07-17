<?php
include("connect.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $sql = "SELECT id, username, is_owner FROM users WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $username, $is_owner);
        $stmt->fetch();
        $_SESSION['user_id'] = $id;
        $_SESSION['username'] = $username;
        $_SESSION['is_owner'] = $is_owner;
        
        header("Location: videos.php");
    } else {
        echo "اسم المستخدم أو كلمة المرور غير صحيحة.";
    }
    
    $stmt->close();
}
$conn->close();
?>
