<?php
require 'db.php';

// Защита: только для преподавателей
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Если вообще не вошел - на вход
    exit;
}

if ($_SESSION['role'] != 'teacher') {
    // Если вошел, но не преподаватель - показываем текст
    die("<h2 style='text-align:center; margin-top: 50px;'>Доступ только для преподавателей. <br>
    <a href='index.php'>Вернуться на главную</a></h2>");
}

$slot_error = '';
$slot_success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_slot'])) {
    $subject = trim($_POST['subject'] ?? '');
    $start_time = $_POST['start_time'] ?? '';
    
    if (!empty($subject) && !empty($start_time)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO slots (teacher_id, subject, start_time) VALUES (?, ?, ?)");
            $stmt->execute([$_SESSION['user_id'], $subject, $start_time]);
            $slot_success = "Интервал успешно создан!";
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $slot_error = "Ошибка: У вас уже есть консультация на это время!";
            } else {
                $slot_error = "Ошибка при создании: " . $e->getMessage();
            }
        }
    }
}

$stmt = $pdo->prepare("SELECT * FROM slots WHERE teacher_id = ? ORDER BY start_time ASC");
$stmt->execute([$_SESSION['user_id']]);
$my_slots = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sql_app = "SELECT a.topic, a.status, s.subject, s.start_time, u.full_name as student_name 
            FROM appointments a 
            JOIN slots s ON a.slot_id = s.id 
            JOIN users u ON a.student_id = u.id 
            WHERE s.teacher_id = ? 
            ORDER BY s.start_time ASC";
$stmt_app = $pdo->prepare($sql_app);
$stmt_app->execute([$_SESSION['user_id']]);
$bookings = $stmt_app->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Панель преподавателя</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="site-header">
        <a href="teacher.php" class="brand">
            <span class="brand-dot"></span>
            Консультации
        </a>
        <span class="tagline">Панель преподавателя</span>
    </header>

    <div class="container">
        <div class="top-bar">
            <span>Преподаватель: <strong><?= htmlspecialchars($_SESSION['name']) ?></strong></span>
            <a href="logout.php" class="btn btn-logout">Выйти</a>
        </div>
        <h1>Панель преподавателя</h1>
        
        <div class="card teacher-card">
            <h3>Создать новый интервал для консультации</h3>
            
            <?php if ($slot_error): ?>
                <div class="alert alert-error"><?= $slot_error ?></div>
            <?php endif; ?>
            <?php if ($slot_success): ?>
                <div class="alert alert-success"><?= $slot_success ?></div>
            <?php endif; ?>

            <form method="POST" class="teacher-form">
                <div class="form-group">
                    <label>Название предмета</label>
                    <input type="text" name="subject" required>
                </div>
                <div class="form-group">
                    <label>Дата и время</label>
                    <input type="datetime-local" name="start_time" required>
                </div>
                <button type="submit" name="create_slot" class="btn btn-large">Создать слот</button>
            </form>
        </div>

        <h3 class="section-title">Записи студентов на мои консультации</h3>
        <?php if (empty($bookings)): ?>
            <p>Пока никто не записался на ваши консультации.</p>
        <?php else: ?>
            <table class="slots-table">
                <thead>
                    <tr>
                        <th>Студент</th>
                        <th>Предмет</th>
                        <th>Дата и время</th>
                        <th>Тема вопроса</th>
                        <th>Статус</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bookings as $b): ?>
                        <tr>
                            <td data-label="Студент:"><?= htmlspecialchars($b['student_name']) ?></td>
                            <td data-label="Предмет:"><?= htmlspecialchars($b['subject']) ?></td>
                            <td data-label="Время:"><?= date('d.m.Y H:i', strtotime($b['start_time'])) ?></td>
                            <td data-label="Тема:"><?= htmlspecialchars($b['topic']) ?></td>
                            <td data-label="Статус:">
                                <?php if ($b['status'] == 'active'): ?>
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

        <h3 class="section-title">Мои созданные интервалы</h3>
        <table class="slots-table">
            <thead>
                <tr>
                    <th>Предмет</th>
                    <th>Дата и время</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($my_slots as $s): ?>
                    <tr>
                        <td data-label="Предмет:"><?= htmlspecialchars($s['subject']) ?></td>
                        <td data-label="Время:"><?= date('d.m.Y H:i', strtotime($s['start_time'])) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <footer class="site-footer">
        <span class="footer-line">© <?= date('Y') ?> Система консультаций</span>
    </footer>
</body>
</html>