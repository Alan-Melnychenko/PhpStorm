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

// Функція для очищення та екранування вхідних даних
function clean_input($data) {
    global $conn;
    $data = htmlspecialchars($data);
    $data = $conn->real_escape_string($data);
    return $data;
}

// Обробка форми реєстрації
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = clean_input($_POST["firstname"]);
    $lastname = clean_input($_POST["lastname"]);
    $email = clean_input($_POST["email"]);
    $password = clean_input($_POST["password"]);
    $role = clean_input($_POST["role"]);

    // Масив для зберігання повідомлень про помилки
    $errors = array();

    // Перевірка, чи всі поля заповнені
    if (empty($firstname)) {
        $errors[] = "Введіть ім'я";
    }

    if (empty($lastname)) {
        $errors[] = "Введіть прізвище";
    }

    if (empty($email)) {
        $errors[] = "Введіть електронну пошту";
    }

    if (empty($password)) {
        $errors[] = "Введіть пароль";
    }

    // Перевірка формату електронної пошти
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Введіть коректну електронну пошту";
    }

    // Перевірка мінімальної довжини паролю
    if (strlen($password) < 6) {
        $errors[] = "Пароль повинен містити принаймні 6 символів";
    }

    // Якщо масив помилок порожній, значить дані пройшли валідацію
    if (empty($errors)) {
        // Перевірка, чи існує введений email в базі даних
        $query = "SELECT * FROM users WHERE email = '$email'";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            echo "Даний користувач вже існує.";
        } else {
            // Вставка даних в таблицю users
            $query = "INSERT INTO users (firstname, lastname, email, password, role) VALUES ('$firstname', '$lastname', '$email', '$password', '$role')";

            if ($conn->query($query) === TRUE) {
                header("Location: success.php");
                exit;
            } else {
                echo "Помилка при реєстрації: " . $conn->error;
            }
        }
    } else {
        // Виведення повідомлень про помилки
        foreach ($errors as $error) {
            echo $error . "<br>";
        }
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Реєстрація</title>
</head>
<body>
<h2>Реєстрація</h2>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Ім'я: <input type="text" name="firstname"><br>
    Прізвище: <input type="text" name="lastname"><br>
    Електронна пошта: <input type="email" name="email"><br>
    Пароль: <input type="password" name="password"><br>
    Роль:
    <select name="role">
        <option value="user">Звичайний користувач</option>
        <option value="admin">Адміністратор</option>
    </select><br>
    <input type="submit" value="Зареєструватися">
</form>
<p>Вже маєте обліковий запис? <a href="login.php">Увійти</a></p>
</body>
</html>