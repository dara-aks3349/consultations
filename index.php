<?php
require 'db.php';

// Проверка авторизации
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$sql = "SELECT s.id, s.subject, s.start_time, u.full_name as teacher_name 
        FROM slots s 
        JOIN users u ON s.teacher_id = u.id 
        LEFT JOIN appointments a ON s.id = a.slot_id 
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
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        
        <!-- Меню пользователя -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <span>Вы вошли как: <strong><?= htmlspecialchars($_SESSION['name']) ?></strong></span>
            <div>
                <?php if ($_SESSION['role'] == 'student'): ?>
                    <a href="profile.php" class="btn" style="margin-right: 10px;">Мои записи</a>
                <?php endif; ?>
                <a href="logout.php" class="btn" style="background: #e74c3c;">Выйти</a>
            </div>
        </div>

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