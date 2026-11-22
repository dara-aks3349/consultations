<?php
require 'db.php';

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = trim($_POST['full_name'] ?? '');
    $login = trim($_POST['login'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $role = $_POST['role'] ?? 'student';

    if (empty($full_name) || empty($login) || empty($password)) {
        $error = "Заполните все поля";
    } else {
        $check = $pdo->prepare("SELECT id FROM users WHERE login = ?");
        $check->execute([$login]);
        if ($check->fetch()) {
            $error = "Пользователь с таким логином уже существует";
        } else {
            $stmt = $pdo->prepare("INSERT INTO users (full_name, login, password, role) VALUES (?, ?, ?, ?)");
            $stmt->execute([$full_name, $login, $password, $role]);
            
            $_SESSION['user_id'] = $pdo->lastInsertId();
            $_SESSION['role'] = $role;
            $_SESSION['name'] = $full_name;
            $_SESSION['login'] = $login;
            
            header("Location: " . ($role == 'teacher' ? "teacher.php" : "index.php"));
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Регистрация</h1>
        <div class="card auth-card">
            <?php if ($error): ?>
                <div class="alert alert-error"><?= $error ?></div>
            <?php endif; ?>
            
            <form method="POST" class="auth-form">
                <div class="form-group">
                    <label>ФИО</label>
                    <input type="text" name="full_name" required>
                </div>
                <div class="form-group">
                    <label>Логин</label>
                    <input type="text" name="login" required>
                </div>
                <div class="form-group">
                    <label>Пароль</label>
                    <input type="password" name="password" required>
                </div>
                <div class="form-group">
                    <label>Роль</label>
                    <select name="role">
                        <option value="student">Студент</option>
                        <option value="teacher">Преподаватель</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-large">Зарегистрироваться</button>
            </form>
            
            <p class="auth-link">
                Уже есть аккаунт? <a href="login.php">Войти</a>
            </p>
        </div>
    </div>
</body>
</html>