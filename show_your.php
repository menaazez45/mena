<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "يرجى تسجيل الدخول لعرض الرسائل.";
    exit;
}

$user_id = $_SESSION['user_id'];

include("connect.php");

// جلب الرسائل التي أرسلها المستخدم
$sql = "SELECT id, message, created_at FROM messages WHERE user_id = ?";
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
    <title>عرض الرسائل المرسلة</title>
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        h1 {
            text-align: center;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>عرض الرسائل المرسلة</h1>
        <?php
        if ($result->num_rows > 0) {
            echo "<table>";
            echo "<tr><th>الرقم</th><th>الرسالة</th><th>تاريخ الإرسال</th></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                echo "<td>" . htmlspecialchars($row['message']) . "</td>";
                echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>لا توجد رسائل لعرضها.</p>";
        }
        $conn->close();
        ?>
    </div>
</body>
</html>
