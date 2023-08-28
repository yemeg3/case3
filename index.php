<?php
session_start();

$servername = "localhost";
$username = "gamemeg9_root";
$password = "vostcorp12Qaq";
$dbname = "gamemeg9_root";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if (isset($_POST['login'])) {
    $login_username = $_POST['username'];
    $login_password = $_POST['password'];

    $login_sql = "SELECT * FROM users WHERE username = '$login_username'";
    $login_result = $conn->query($login_sql);

    if ($login_result->num_rows > 0) {
        $user_data = $login_result->fetch_assoc();
        if (password_verify($login_password, $user_data['password'])) {
            $_SESSION['user_id'] = $user_data['id'];
            $_SESSION['username'] = $user_data['username'];

                header("Location: dashboard.php");

            exit();
        } else {
            $login_error = "Invalid login credentials.";
        }
    } else {
        $login_error = "Invalid login credentials.";
    }
}


if (isset($_POST['register'])) {
    $register_username = $_POST['new_username'];
    $register_password = $_POST['new_password'];


    $hashed_password = password_hash($register_password, PASSWORD_DEFAULT);


    $register_sql = "INSERT INTO users (username, password) VALUES ('$register_username', '$hashed_password')";
    if ($conn->query($register_sql) === TRUE) {
        $register_success = "Registration successful. You can now log in.";
    } else {
        $register_error = "Error: " . $conn->error;
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Diary - Регистрация и авторизация</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Travel Diary</h1>
    </header>

    <main>
        <div class="container_login">
            <h2>Авторизация</h2>
            <form method="post">
                <label for="login_username" class="container_login2">Имя пользователя: <input type="text" id="login_username" name="username" required></label><br>
                <label for="login_password" class="container_login2" >Пароль: <input type="password" id="login_password" name="password" required></label><br>
                <button type="submit" name="login">Войти</button>
            </form>
        </div>

        <div class="container_login">
            <h2>Регистрация</h2>
            <form method="post">
                <label for="new_username">Новое имя пользователя: <input type="text" id="new_username" name="new_username" required></label><br>
                <label for="new_password">Новый пароль: <input type="password" id="new_password" name="new_password" required></label><br>
                <button type="submit" name="register">Зарегистрироваться</button>
            </form>
            <?php
            if (isset($login_error)) {
                echo "<p style='color: red;'>$login_error</p>";
            }

            if (isset($register_success)) {
                echo "<p style='color: green;'>$register_success</p>";
            } elseif (isset($register_error)) {
                echo "<p style='color: red;'>$register_error</p>";
            }
            ?>
        </div>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Travel Diary</p>
    </footer>
</body>
</html>
