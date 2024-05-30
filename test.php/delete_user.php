<?php
// Підключення до бази даних
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "users";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Перевірка, чи було відправлено запит на видалення користувача
if (isset($_POST['delete'])) {
    $user_id = $_POST['user_id'];

    // Запит на видалення користувача з бази даних
    $sql = "DELETE FROM users WHERE id = '$user_id'";

    if ($conn->query($sql) === TRUE) {
        echo "Користувача успішно видалено.";
    } else {
        echo "Помилка при видаленні користувача: " . $conn->error;
    }
}

// Закриття з'єднання з базою даних
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Повернутися до адміністративної панелі</title>
</head>
<body>
<br>
<a href="admin_panel.php">Повернутися до адміністративної панелі</a>
</body>
</html>

