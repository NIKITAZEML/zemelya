<?php

require_once __DIR__ . '/../config/db.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Данные из формы
        $name = $_POST['name'] ?? '';
        $surname = $_POST['surname'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $is_active = isset($_POST['is_active']) ? true : false;

        // Валидация
        $errors = [];

        if (empty($name)) {
            $errors[] = 'Имя обязательно';
        }

        if (empty($surname)) {
            $errors[] = 'Фамилия обязательна';
        }

        if (empty($phone)) {
            $errors[] = 'Телефон обязателен';
        }

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Корректный email обязателен';
        }

        if (empty($password) || strlen($password) < 6) {
            $errors[] = 'Пароль должен быть не менее 6 символов';
        }

        // Проверка email на уникальность
        $checkStmt = $pdo->prepare("SELECT manager_id FROM manager WHERE email = :email");
        $checkStmt->execute([':email' => $email]);
        if ($checkStmt->fetch()) {
            $errors[] = 'Сотрудник с таким email уже существует';
        }

        if (!empty($errors)) {
            echo json_encode([
                'success' => false,
                'message' => 'Ошибки валидации',
                'errors' => $errors
            ]);
            exit;
        }

        // Хэшируем пароль
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // SQL
        $sql = "INSERT INTO manager (
            name,
            surname,
            phone,
            email,
            password,
            is_active
        ) VALUES (
            :name,
            :surname,
            :phone,
            :email,
            :password,
            :is_active
        )";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':name' => $name,
            ':surname' => $surname,
            ':phone' => $phone,
            ':email' => $email,
            ':password' => $password_hash,
            ':is_active' => $is_active
        ]);

        echo json_encode([
            'success' => true,
            'message' => 'Сотрудник успешно добавлен',
            'manager_id' => $pdo->lastInsertId()
        ]);

    } catch (PDOException $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Ошибка БД: ' . $e->getMessage()
        ]);
    }

    exit;
}
