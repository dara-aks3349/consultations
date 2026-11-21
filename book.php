<?php
require 'db.php';

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
    
    // Проверка на пустое поле (обязательность)
    if (empty($topic)) {
        $error = "Укажите тему обращения";
    } 
    // Проверка на прошедшее время (запрет записи в прошлое)
    elseif (strtotime($slot['start_time']) < time()) {
        $error = "Ошибка: Запись на прошедшее время запрещена";
    } 
    else {
        // Попытка записи в БД (проверка на двойную запись через уникальность)
        try {
            $insert = $pdo->prepare("INSERT INTO appointments (slot_id, student_id, topic) VALUES (?, ?, ?)");
            $insert->execute([$slot_id, $_SESSION['user_id'], $topic]);
            $success = "Вы успешно записаны на консультацию!";
        } catch (PDOException $e) {
            // Если сработал UNIQUE индекс (код ошибки 23000)
            if ($e->getCode() == 23000) {
                $error = "Ошибка: Этот интервал уже занят другим студентом!";
            } else {
                $error = "Ошибка при записи. Попробуйте позже.";
            }
        }
    }
}

// Проверяем, записан ли уже текущий студент на этот слот (для вывода кнопки удаления)
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
    <div class="container">
        <a href="index.php" class="back-link">&larr; Вернуться к списку</a>
        <h1>Запись на консультацию</h1>

        <div class="card">
            <h2><?= htmlspecialchars($slot['subject']) ?></h2>
            <p><strong>Преподаватель:</strong> <?= htmlspecialchars($slot['teacher_name']) ?></p>
            <p><strong>Дата и время:</strong> <?= date('d.m.Y H:i', strtotime($slot['start_time'])) ?></p>

            <!-- Вывод сообщений об успехе или ошибке -->
            <?php if ($success): ?>
                <div class="alert alert-success">
                    <?= $success ?>
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="alert alert-error">
                    <?= $error ?>
                </div>
            <?php endif; ?>

            <!-- Если студент УЖЕ записан, показываем кнопку отмены -->
            <?php if ($is_booked || $success): ?>
                <hr style="margin: 20px 0; border: 0; border-top: 1px solid #eee;">
                <form method="POST" onsubmit="return confirm('Вы уверены, что хотите отменить запись?');">
                    <button type="submit" name="delete_appointment" class="btn" style="background-color: #e74c3c; width: 100%;">Отменить запись</button>
                </form>
            
            <!-- Если НЕ записан, показываем форму записи -->
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
</body>
</html>