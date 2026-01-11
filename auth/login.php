<?php
session_start();
require_once __DIR__ . '/../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Ищем пользователя
    $stmt = $pdo->prepare("SELECT * FROM manager WHERE email = :email LIMIT 1");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        $_SESSION['error'] = "Пользователь не найден";
        header("Location: /auth.php");
        exit;
    }

    // Проверяем пароль
    if (!password_verify($password, $user['password'])) {
        $_SESSION['error'] = "Неверный пароль";
        header("Location: /auth.php");
        exit;
    }

    // Авторизация успешна → записываем данные в сессию
    $_SESSION['user'] = [
        'id' => $user['manager_id'],
        'email' => $user['email'],
        'name' => $user['name'] ?? ''
    ];

    header("Location: /"); // переходим на главную
    exit;
}
