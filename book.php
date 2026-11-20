<?php
require 'db.php';

$slot_id = $_GET['slot_id'] ?? null;
if (!$slot_id) {
    die("Не выбран слот. <a href='index.php'>Вернуться</a>");
}

$stmt = $pdo->prepare("SELECT s.*, u.full_name as teacher_name FROM slots s JOIN users u ON s.teacher_id = u.id WHERE s.id = ?");
$stmt->execute([$slot_id]);
$slot = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$slot) {
    die("Запись не найдена. <a href='index.php'>Вернуться</a>");
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Карточка консультации</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <a href="index.php" class="back-link">&larr; Вернуться к списку</a>
        <h1>Карточка консультации</h1>

        <div class="card">
            <h2><?= htmlspecialchars($slot['subject']) ?></h2>
            <p><strong>Преподаватель:</strong> <?= htmlspecialchars($slot['teacher_name']) ?></p>
            <p><strong>Дата и время:</strong> <?= date('d.m.Y H:i', strtotime($slot['start_time'])) ?></p>
            
            <br>
            <button class="btn" disabled>Запись откроется на следующем этапе</button>
        </div>
    </div>
</body>
</html>