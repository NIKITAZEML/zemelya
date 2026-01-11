<?php

require_once __DIR__ . '/../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Получаем данные
        $name = $_POST['name'] ?? '';
        $surname = $_POST['surname'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $email = $_POST['email'] ?? '';
        $passport = $_POST['passport'] ?? '';
        $client_type_id = $_POST['client_type_id'] ?? '';

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

        if (empty($passport)) {
            $errors[] = 'Паспорт обязателен';
        }

        if (empty($client_type_id) || !is_numeric($client_type_id)) {
            $errors[] = 'Тип клиента обязателен';
        }

        // Если есть ошибки
        if (!empty($errors)) {
            echo json_encode([
                'success' => false,
                'message' => 'Ошибки валидации',
                'errors' => $errors
            ]);
            exit;
        }

        // SQL
        $sql = "INSERT INTO client (
            name,
            surname,
            phone,
            email,
            passport,
            client_type_id
        ) VALUES (
            :name,
            :surname,
            :phone,
            :email,
            :passport,
            :client_type_id
        )";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':name' => $name,
            ':surname' => $surname,
            ':phone' => $phone,
            ':email' => $email,
            ':passport' => $passport,
            ':client_type_id' => $client_type_id
        ]);

        // ID нового клиента
        $new_client_id = $pdo->lastInsertId();

        echo json_encode([
            'success' => true,
            'message' => 'Клиент успешно добавлен',
            'client_id' => $new_client_id
        ]);

    } catch (PDOException $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Ошибка БД: ' . $e->getMessage()
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Ошибка: ' . $e->getMessage()
        ]);
    }

    exit;
}
