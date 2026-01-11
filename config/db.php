<?php
$host = 'localhost';
$port = '5432';
$dbname = 'travel_agency';
$user = 'postgres';
$password = 'postgres';

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die("Ошибка подключения к БД: " . $e->getMessage());
}
?>