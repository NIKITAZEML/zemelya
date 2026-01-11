<?php

require_once __DIR__ . '/../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Данные из формы
        $tour_name  = $_POST['tour_name'] ?? '';
        $country_id = $_POST['country_id'] ?? '';
        $hotel_id   = $_POST['hotel_id'] ?? '';
        $duration   = $_POST['duration'] ?? '';
        $price      = $_POST['price'] ?? '';
        $description = $_POST['description'] ?? null;

        // Валидация
        $errors = [];

        if (empty($tour_name)) {
            $errors[] = 'Название тура обязательно';
        }

        if (empty($country_id) || !is_numeric($country_id)) {
            $errors[] = 'ID страны обязателен';
        }

        if (empty($hotel_id) || !is_numeric($hotel_id)) {
            $errors[] = 'ID отеля обязателен';
        }

        if (empty($duration) || !is_numeric($duration)) {
            $errors[] = 'Длительность обязательна';
        }

        if (empty($price) || !is_numeric($price)) {
            $errors[] = 'Цена обязательна';
        }

        if (!empty($errors)) {
            echo json_encode([
                'success' => false,
                'message' => 'Ошибки валидации',
                'errors' => $errors
            ]);
            exit;
        }

        // SQL
        $sql = "
            INSERT INTO tour (
                tour_name,
                country_id,
                hotel_id,
                duration,
                price,
                description
            ) VALUES (
                :tour_name,
                :country_id,
                :hotel_id,
                :duration,
                :price,
                :description
            )
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':tour_name' => $tour_name,
            ':country_id' => $country_id,
            ':hotel_id' => $hotel_id,
            ':duration' => $duration,
            ':price' => $price,
            ':description' => $description
        ]);

        echo json_encode([
            'success' => true,
            'message' => 'Тур успешно добавлен',
            'tour_id' => $pdo->lastInsertId()
        ]);

    } catch (PDOException $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Ошибка БД: ' . $e->getMessage()
        ]);
    }

    exit;
}
