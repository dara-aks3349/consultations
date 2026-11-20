<?php
// Настройки подключения к БД
$host = 'localhost';
$dbname = 'consultations_db';
$username = 'root'; 
$password = '';     

try {
    $pdo = new PDO("mysql:host=$host;port=3307;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Временно выводим реальную ошибку, чтобы понять, в чем дело
    die("Ошибка подключения: " . $e->getMessage());
}

session_start();
$_SESSION['user_id'] = 3; 
?>