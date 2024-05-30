<?php
session_start();

// Підключення до бази даних
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "users";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Функція для очищення та екранування вхідних даних
function clean_input($data) {
    global $conn;
    $data = htmlspecialchars($data);
    $data = $conn->real_escape_string($data);
    return $data;
}

// Обробка форми авторизації
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = clean_input($_POST["email"]);
    $password = clean_input($_POST["password"]);

    // Перевірка наявності користувача з введеними даними
    $query = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // Отримання даних користувача
        $row = $result->fetch_assoc();
        $user_id = $row['id'];
        $firstname = $row['firstname'];

        // Перевірка ролі користувача (наприклад, чи він адміністратор)
        // Якщо користувач - звичайний користувач, перенаправити на сторінку привітання
        // В іншому випадку (наприклад, адміністратор), перенаправити на адміністративну панель
        if ($row['role'] == 'user') {
            $_SESSION["user_id"] = $user_id;
            $_SESSION["firstname"] = $firstname;
            header("Location: welcome.php");
            exit;
        } else {
            $_SESSION["user_id"] = $user_id;
            $_SESSION["firstname"] = $firstname;
            header("Location: admin_panel.php");
            exit;
        }
    } else {
        echo "Помилка авторизації.";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Авторизація</title>
</head>
<body>
<h2>Авторизація</h2>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Електронна пошта: <input type="email" name="email"><br>
    Пароль: <input type="password" name="password"><br>
    <input type="submit" value="Увійти">
</form>
<p>Ще не маєте облікового запису? <a href="register.php">Зареєструватися</a></p>
</body>
</html>