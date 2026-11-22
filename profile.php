<?php
require 'db.php';

// Защита: только для авторизованных студентов
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header("Location: login.php");
    exit;
}

// Получаем записи текущего студента
$stmt = $pdo->prepare("SELECT a.id as app_id, a.topic, a.status, s.subject, s.start_time, u.full_name as teacher_name 
                       FROM appointments a 
                       JOIN slots s ON a.slot_id = s.id 
                       JOIN users u ON s.teacher_id = u.id 
                       WHERE a.student_id = ? 
                       ORDER BY s.start_time DESC");
$stmt->execute([$_SESSION['user_id']]);
$appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Личный кабинет</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="site-header">
        <a href="index.php" class="brand">
            <span class="brand-dot"></span>
            Консультации
        </a>
        <span class="tagline">Личный кабинет</span>
    </header>

    <div class="container">
        <div class="top-bar">
            <a href="index.php" class="back-link">&larr; На главную</a>
            <a href="logout.php" class="btn btn-logout">Выйти</a>
        </div>
        <h1>Мои консультации</h1>
        
        <?php if (empty($appointments)): ?>
            <p class="text-center">У вас пока нет записей на консультации.</p>
        <?php else: ?>
            <table class="slots-table">
                <thead>
                    <tr>
                        <th>Предмет</th>
                        <th>Преподаватель</th>
                        <th>Дата и время</th>
                        <th>Тема вопроса</th>
                        <th>Статус</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($appointments as $app): ?>
                        <tr>
                            <td data-label="Предмет:"><?= htmlspecialchars($app['subject']) ?></td>
                            <td data-label="Преподаватель:"><?= htmlspecialchars($app['teacher_name']) ?></td>
                            <td data-label="Время:"><?= date('d.m.Y H:i', strtotime($app['start_time'])) ?></td>
                            <td data-label="Тема:"><?= htmlspecialchars($app['topic']) ?></td>
                            <td data-label="Статус:">
                                <?php if ($app['status'] == 'active'): ?>
                                    <span class="status-active">Активна</span>
                                <?php else: ?>
                                    <span class="status-completed">Завершена</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <footer class="site-footer">
        <span class="footer-line">© <?= date('Y') ?> Система консультаций</span>
    </footer>
</body>
</html>