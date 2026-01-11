<?php

require_once __DIR__ . '/../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Получаем данные
        $contract_id = $_POST['contract_id'] ?? '';
        $payment_date = $_POST['payment_date'] ?? '';
        $amount = $_POST['amount'] ?? '';
        $payment_method = $_POST['payment_method'] ?? '';

        // Валидация
        $errors = [];

        if (empty($contract_id) || !is_numeric($contract_id)) {
            $errors[] = 'Контракт обязателен';
        }

        if (empty($payment_date)) {
            $errors[] = 'Дата платежа обязательна';
        } else {
            // Проверка формата даты
            $date = DateTime::createFromFormat('Y-m-d', $payment_date);
            if (!$date || $date->format('Y-m-d') !== $payment_date) {
                $errors[] = 'Неверный формат даты';
            }
        }

        if (empty($amount) || !is_numeric($amount)) {
            $errors[] = 'Сумма обязательна';
        } elseif ($amount <= 0) {
            $errors[] = 'Сумма должна быть больше нуля';
        }

        if (empty($payment_method)) {
            $errors[] = 'Способ оплаты обязателен';
        }

        if (strlen($payment_method) > 50) {
            $errors[] = 'Способ оплаты не должен превышать 50 символов';
        }

        // Проверка существования контракта
        if (empty($errors) && !empty($contract_id)) {
            $check_sql = "SELECT contract_id FROM contract WHERE contract_id = :contract_id";
            $check_stmt = $pdo->prepare($check_sql);
            $check_stmt->execute([':contract_id' => $contract_id]);
            
            if (!$check_stmt->fetch()) {
                $errors[] = 'Контракт с указанным ID не существует';
            }
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
        $sql = "INSERT INTO payment (
            contract_id,
            payment_date,
            amount,
            payment_method
        ) VALUES (
            :contract_id,
            :payment_date,
            :amount,
            :payment_method
        )";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':contract_id' => (int)$contract_id,
            ':payment_date' => $payment_date,
            ':amount' => (float)$amount,
            ':payment_method' => $payment_method
        ]);

        // ID нового платежа
        $new_payment_id = $pdo->lastInsertId();

        echo json_encode([
            'success' => true,
            'message' => 'Платеж успешно добавлен',
            'payment_id' => $new_payment_id
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