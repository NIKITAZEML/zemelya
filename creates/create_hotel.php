<?php
require_once __DIR__ . '/../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $hotel_name = trim($_POST['hotel_name'] ?? '');
        $stars = $_POST['stars'] ?? '';
        $address = trim($_POST['address'] ?? '');
        $contact_phone = trim($_POST['contact_phone'] ?? '');

        $errors = [];

        if ($hotel_name === '') {
            $errors[] = 'Название отеля обязательно';
        }

        if ($address === '') {
            $errors[] = 'Адрес обязателен';
        }

        if ($contact_phone === '') {
            $errors[] = 'Контактный телефон обязателен';
        }

        if (!is_numeric($stars) || $stars < 1 || $stars > 5) {
            $errors[] = 'Количество звезд должно быть от 1 до 5';
        }

        if (!empty($errors)) {
            echo json_encode([
                'success' => false,
                'errors' => $errors
            ]);
            exit;
        }

        $sql = "
            INSERT INTO hotel (
                hotel_name,
                stars,
                address,
                contact_phone
            ) VALUES (
                :hotel_name,
                :stars,
                :address,
                :contact_phone
            )
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':hotel_name' => $hotel_name,
            ':stars' => (int)$stars,
            ':address' => $address,
            ':contact_phone' => $contact_phone
        ]);

        echo json_encode([
            'success' => true,
            'message' => 'Отель успешно добавлен',
            'hotel_id' => $pdo->lastInsertId()
        ]);

    } catch (PDOException $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Ошибка БД: ' . $e->getMessage()
        ]);
    }

    exit;
}
