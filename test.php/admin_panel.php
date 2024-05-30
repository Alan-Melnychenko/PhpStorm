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

// Функція для відображення користувачів
function displayUsers($conn) {
    $query = "SELECT id, firstname, lastname FROM users";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "ID: " . $row["id"] . " - Ім'я: " . $row["firstname"] . " " . $row["lastname"] . "<br>";
        }
    } else {
        echo "Немає користувачів.";
    }
}
?>

    <!DOCTYPE html>
    <html>
    <head>
        <title>Адміністративний інтерфейс</title>
    </head>
    <body>
    <h2>Адміністративний інтерфейс</h2>

    <h3>Список користувачів:</h3>
    <?php displayUsers($conn); ?>

    <h3>Видалення користувача:</h3>
    <form method="post" action="delete_user.php">
        ID користувача: <input type="text" name="user_id"><br>
        <input type="submit" name="delete" value="Видалити користувача">
    </form>
    <p><a href="login.php">Вийти</a></p>
    </body>
    </html>

<?php
// Закриваємо з'єднання з базою даних після виконання всіх запитів
$conn->close();
?>