<?php
// بدء جلسة العمل
session_start();
include("connect.php"); // التأكد من أن ملف connect.php يحتوي على إعدادات الاتصال بقاعدة البيانات

// التحقق من إرسال طلب تحديث حالة المراجعة
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["review_id"])) {
    $review_id = $_POST["review_id"];
    $new_status = $_POST["new_status"];
    $update_sql = "UPDATE messages SET review_status = ? WHERE id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("si", $new_status, $review_id);
    $stmt->execute();
    $stmt->close();
}

// جلب الرسائل من جدول messages
$sql = "SELECT id, name, message, email, phone, review_status FROM messages";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>عرض الرسائل</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 15px;
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
            <th>الاسم</th>
            <th>الرسالة</th>
            <th>البريد الإلكتروني</th>
            <th>الهاتف</th>
            <th>حالة المراجعة</th>
            <th>تحديث الحالة</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            // عرض البيانات لكل صف
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["id"] . "</td>";
                echo "<td>" . $row["name"] . "</td>";
                echo "<td>" . $row["message"] . "</td>";
                echo "<td>" . $row["email"] . "</td>";
                echo "<td>" . $row["phone"] . "</td>";
                echo "<td>" . $row["review_status"] . "</td>";
                echo "<td>
                    <form method='post' action=''>
                        <input type='hidden' name='review_id' value='" . $row["id"] . "'>
                        <select name='new_status'>
                            <option value='يحتاج مراجعة' " . ($row["review_status"] == 'يحتاج مراجعة' ? 'selected' : '') . ">يحتاج مراجعة</option>
                            <option value='تمت المراجعة' " . ($row["review_status"] == 'تمت المراجعة' ? 'selected' : '') . ">تمت المراجعة</option>
                        </select>
                        <button type='submit'>تحديث</button>
                    </form>
                </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7'>لا توجد رسائل</td></tr>";
        }
        $conn->close();
        ?>
    </table>
</body>
</html>