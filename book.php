<?php
require 'db.php';

// Проверка: только студент может записываться
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    die("Доступ разрешен только студентам. <a href='index.php'>На главную</a>");
}

$slot_id = $_GET['slot_id'] ?? null;
if (!$slot_id) {
    die("Не выбран слот для записи. <a href='index.php'>Вернуться к списку</a>");
}

// Получаем информацию о слоте
$stmt = $pdo->prepare("SELECT s.*, u.full_name as teacher_name FROM slots s JOIN users u ON s.teacher_id = u.id WHERE s.id = ?");
$stmt->execute([$slot_id]);
$slot = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$slot) {
    die("Запись не найдена. <a href='index.php'>Вернуться к списку</a>");
}

$error = '';
$success = '';

// 1. Обработка УДАЛЕНИЯ (отмены записи)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_appointment'])) {
    $delete = $pdo->prepare("DELETE FROM appointments WHERE slot_id = ? AND student_id = ?");
    $delete->execute([$slot_id, $_SESSION['user_id']]);
    header("Location: index.php");
    exit;
}

// 2. Обработка СОЗДАНИЯ (записи на консультацию)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_appointment'])) {
    $topic = trim($_POST['topic'] ?? '');
    
    if (empty($topic)) {
        $error = "Укажите тему обращения";
    } 
    elseif (strtotime($slot['start_time']) < time()) {
        $error = "Ошибка: Запись на прошедшее время запрещена";
    } 
    else {
        try {
            $insert = $pdo->prepare("INSERT INTO appointments (slot_id, student_id, topic) VALUES (?, ?, ?)");
            $insert->execute([$slot_id, $_SESSION['user_id'], $topic]);
            $success = "Вы успешно записаны на консультацию!";
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $error = "Ошибка: Этот интервал уже занят другим студентом!";
            } else {
                $error = "Ошибка при записи. Попробуйте позже.";
            }
        }
    }
}

// Проверяем, записан ли уже текущий студент на этот слот
$check = $pdo->prepare("SELECT id FROM appointments WHERE slot_id = ? AND student_id = ?");
$check->execute([$slot_id, $_SESSION['user_id']]);
$is_booked = $check->fetch();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Запись на консультацию</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="site-header">
        <a href="index.php" class="brand">
            <span class="brand-dot"></span>
            Консультации
        </a>
        <span class="tagline">Запись на консультацию</span>
    </header>

    <div class="container">
        <a href="index.php" class="back-link">&larr; Вернуться к списку</a>
        <h1>Запись на консультацию</h1>

        <div class="card">
            <h2><?= htmlspecialchars($slot['subject']) ?></h2>
            <p><strong>Преподаватель:</strong> <?= htmlspecialchars($slot['teacher_name']) ?></p>
            <p><strong>Дата и время:</strong> <?= date('d.m.Y H:i', strtotime($slot['start_time'])) ?></p>

            <?php if ($success): ?>
                <div class="alert alert-success"><?= $success ?></div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="alert alert-error"><?= $error ?></div>
            <?php endif; ?>

            <?php if ($is_booked || $success): ?>
                <hr style="margin: 20px 0; border: 0; border-top: 1px solid #eee;">
                <form method="POST" onsubmit="return confirm('Вы уверены, что хотите отменить запись?');">
                    <button type="submit" name="delete_appointment" class="btn" style="background-color: #e74c3c; width: 100%;">Отменить запись</button>
                </form>
            <?php else: ?>
                <form method="POST" class="booking-form">
                    <div class="form-group">
                        <label for="topic">Тема обращения <span class="required">*</span></label>
                        <textarea id="topic" name="topic" rows="3" required><?= isset($_POST['topic']) ? htmlspecialchars($_POST['topic']) : '' ?></textarea>
                    </div>
                    <button type="submit" name="create_appointment" class="btn btn-large">Подтвердить запись</button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <footer class="site-footer">
        <span class="footer-line">© <?= date('Y') ?> Система консультаций</span>
    </footer>
</body>
</html>