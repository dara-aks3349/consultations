<?php
require 'db.php';

// Если уже вошел, перенаправляем на главную
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login = trim($_POST['login'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!empty($login) && !empty($password)) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE login = ?");
        $stmt->execute([$login]);
        $user = $stmt->fetch();

        // В учебных целях проверяем пароль в открытом виде
        if ($user && $password === '12345') { 
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['name'] = $user['full_name'];
            $_SESSION['login'] = $user['login'];
            
            // Разделение ролей: преподаватель идет в панель, студент на главную
            if ($user['role'] == 'teacher') {
                header("Location: teacher.php");
            } else {
                header("Location: index.php");
            }
            exit;
        } else {
            $error = "Неверный логин или пароль";
        }
    } else {
        $error = "Заполните все поля";
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход в систему</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="site-header">
        <a href="login.php" class="brand">
            <span class="brand-dot"></span>
            Консультации
        </a>
        <span class="tagline">Вход в систему</span>
    </header>

    <div class="container">
        <h1>Вход в систему</h1>
        <div class="card" style="max-width: 400px;">
            <?php if ($error): ?>
                <div class="alert alert-error"><?= $error ?></div>
            <?php endif; ?>
            <form method="POST" style="text-align: left;">
                <div class="form-group">
                    <label>Логин</label>
                    <input type="text" name="login" required>
                </div>
                <div class="form-group">
                    <label>Пароль</label>
                    <input type="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-large">Войти</button>
            </form>
            <p style="margin-top: 20px; font-size: 14px; color: #7f8c8d; text-align: center;">
                Тестовые аккаунты (пароль: 12345):<br>
                Студент: <b>student1</b> | Преподаватель: <b>teacher1</b>
            </p>
            <p style="margin-top: 15px; text-align: center; font-size: 14px;">
                Нет аккаунта? <a href="register.php" style="color: #2980b9;">Зарегистрироваться</a>
            </p>
        </div>
    </div>

    <footer class="site-footer">
        <span class="footer-line">© <?= date('Y') ?> Система консультаций</span>
    </footer>
</body>
</html>