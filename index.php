<?php
require 'db.php';

$sql = "SELECT s.id, s.subject, s.start_time, u.full_name as teacher_name 
        FROM slots s 
        JOIN users u ON s.teacher_id = u.id 
        LEFT JOIN appointments a ON s.id = a.slot_id 
        WHERE a.id IS NULL AND s.start_time > NOW()
        ORDER BY s.start_time ASC";
$stmt = $pdo->query($sql);
$slots = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Список консультаций</title>
    <!-- Вот эта строчка подключает стили. Проверьте, что она есть! -->
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Расписание консультаций</h1>

        <?php if (empty($slots)): ?>
            <p>Свободных консультаций нет.</p>
        <?php else: ?>
            <table class="slots-table">
                <thead>
                    <tr>
                        <th>Преподаватель</th>
                        <th>Предмет</th>
                        <th>Дата и время</th>
                        <th>Действие</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($slots as $slot): ?>
                        <tr>
                            <td data-label="Преподаватель:"><?= htmlspecialchars($slot['teacher_name']) ?></td>
                            <td data-label="Предмет:"><?= htmlspecialchars($slot['subject']) ?></td>
                            <td data-label="Время:"><?= date('d.m.Y H:i', strtotime($slot['start_time'])) ?></td>
                            <td data-label="Действие:">
                                <a href="book.php?slot_id=<?= $slot['id'] ?>" class="btn">Записаться</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>