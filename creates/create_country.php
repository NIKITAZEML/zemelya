<?php
require_once __DIR__ . '/../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $country_name = trim($_POST['country_name'] ?? '');
        $is_visa_required = $_POST['is_visa_required'] ?? null;

        $errors = [];

        if ($country_name === '') {
            $errors[] = 'Название страны обязательно';
        }

        if ($is_visa_required === null || !in_array($is_visa_required, ['0', '1'], true)) {
            $errors[] = 'Укажите, требуется ли виза';
        } else {
            $is_visa_required = (int)$is_visa_required; // ← FIX
        }

        if (!empty($errors)) {
            echo json_encode([
                'success' => false,
                'errors' => $errors
            ]);
            exit;
        }

        $sql = "
            INSERT INTO country (country_name, is_visa_required)
            VALUES (:country_name, :is_visa_required)
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':country_name' => $country_name,
            ':is_visa_required' => $is_visa_required
        ]);

        echo json_encode([
            'success' => true,
            'message' => 'Страна успешно добавлена',
            'country_id' => $pdo->lastInsertId()
        ]);

    } catch (PDOException $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Ошибка БД: ' . $e->getMessage()
        ]);
    }

    exit;
}

