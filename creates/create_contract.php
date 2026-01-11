<?php

require_once __DIR__ . '/../config/db.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Получаем данные из формы
        $contract_number = $_POST['contract_number'] ?? '';
        $contract_date = $_POST['contract_date'] ?? '';
        $start_date = $_POST['start_date'] ?? '';
        $end_date = $_POST['end_date'] ?? '';
        $total_cost = $_POST['total_cost'] ?? '';
        $client_id = $_POST['client_id'] ?? '';
        $manager_id = $_POST['manager_id'] ?? '';
        $tour_id = $_POST['tour_id'] ?? '';
        $status_id = $_POST['status_id'] ?? '';
        
        // Валидация данных
        $errors = [];
        
        if (empty($contract_number)) {
            $errors[] = 'Номер договора обязателен';
        }
        
        if (empty($contract_date)) {
            $errors[] = 'Дата заключения обязательна';
        }
        
        if (empty($client_id) || !is_numeric($client_id)) {
            $errors[] = 'ID клиента обязателен и должен быть числом';
        }
        
        if (empty($manager_id) || !is_numeric($manager_id)) {
            $errors[] = 'ID менеджера обязателен и должен быть числом';
        }
        
        if (empty($tour_id) || !is_numeric($tour_id)) {
            $errors[] = 'ID тура обязателен и должен быть числом';
        }
        
        if (empty($status_id) || !is_numeric($status_id)) {
            $errors[] = 'Статус обязателен';
        }
        
        // Если есть ошибки, выводим их
        if (!empty($errors)) {
            die(json_encode([
                'success' => false,
                'message' => 'Ошибки валидации',
                'errors' => $errors
            ]));
        }
        
        // Подготовка SQL запроса
        $sql = "INSERT INTO contract (
            contract_number, 
            contract_date, 
            start_date, 
            end_date, 
            total_cost, 
            client_id, 
            manager_id, 
            tour_id, 
            status_id
        ) VALUES (
            :contract_number,
            :contract_date,
            :start_date,
            :end_date,
            :total_cost,
            :client_id,
            :manager_id,
            :tour_id,
            :status_id
        )";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':contract_number' => $contract_number,
            ':contract_date' => $contract_date,
            ':start_date' => $start_date,
            ':end_date' => $end_date,
            ':total_cost' => $total_cost,
            ':client_id' => $client_id,
            ':manager_id' => $manager_id,
            ':tour_id' => $tour_id,
            ':status_id' => $status_id
        ]);
        
        // Получаем ID нового договора
        $new_contract_id = $pdo->lastInsertId();
        
        // Успешный ответ
        echo json_encode([
            'success' => true,
            'message' => 'Договор успешно добавлен!',
            'contract_id' => $new_contract_id
        ]);
        
    } catch (PDOException $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Ошибка базы данных: ' . $e->getMessage()
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Ошибка: ' . $e->getMessage()
        ]);
    }
    
    exit;
}
?>