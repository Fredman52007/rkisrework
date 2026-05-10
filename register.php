<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = $_POST['username'];
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (username, password_hash, role, can_search) VALUES (?, ?, 'employee', 0)");
    $stmt->bind_param("ss", $user, $pass);

    if ($stmt->execute()) {
        header("Location: login.php?msg=success");
    } else {
        $error = "Ошибка: пользователь с таким логином уже существует.";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Регистрация</title>
</head>
<body>
    <div class="auth-container">
        <h2>Регистрация нового сотрудника</h2>
        <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Логин" required>
            <input type="password" name="password" placeholder="Пароль" required>
            <button type="submit">Зарегистрироваться</button>
        </form>
        <p><a href="login.php">Уже есть аккаунт? Войти</a></p>
    </div>
</body>
</html>