<?php
session_start();

// Перевірка, чи користувач авторизований
if (!isset($_SESSION["user_id"]) || !isset($_SESSION["firstname"])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION["user_id"];
$firstname = $_SESSION["firstname"];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Ласкаво просимо, <?php echo $firstname; ?></title>
</head>
<body>
<h2>Ласкаво просимо, <?php echo $firstname; ?>!</h2>
<p>Ви успішно авторизувалися.</p>
<p><a href="login.php">Вийти</a></p>
</body>
</html>