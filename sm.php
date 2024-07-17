<?php
// بدء جلسة العمل
session_start();
include("connect.php"); // التأكد من أن ملف connect.php يحتوي على إعدادات الاتصال بقاعدة البيانات

// التحقق من إرسال طلب تحديث حالة المراجعة
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["review_id"], $_POST["new_status"])) {
    $review_id = $_POST["review_id"];
    $new_status = $_POST["new_status"];
    
    // التأكد من أن حالة المراجعة هي قيمة صحيحة
    $valid_statuses = ['مراجعة', 'غير مراجعة']; // تحديث بناءً على القيم الممكنة في قاعدة البيانات
    if (in_array($new_status, $valid_statuses)) {
        $update_sql = "UPDATE messages SET review_status = ? WHERE id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("si", $new_status, $review_id);
        $stmt->execute();
        $stmt->close();
    } else {
        echo "حالة المراجعة غير صحيحة.";
    }
}

// جلب الرسائل من جدول sendforteacher
$sql = "SELECT id, message FROM sendforteacher";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>عرض الرسائل</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #333;
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
    </style>
</head>
<body>
    <h1>عرض الرسائل</h1>
    <table>
        <tr>
            <th>الرقم</th>
            <th>الرسالة</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            // عرض البيانات لكل صف
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row["id"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["message"]) . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='2'>لا توجد رسائل</td></tr>";
        }
        $conn->close();
        ?>
    </table>
</body>
</html>
