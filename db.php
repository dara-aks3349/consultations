<?php
$host = 'localhost';
$dbname = 'consultations_db';
$username = 'root'; 
$password = '';     

try {
    $pdo = new PDO("mysql:host=$host;port=3307;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Извините, произошла ошибка подключения к базе данных.");
}

session_start();
?>