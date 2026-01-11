<?php
require_once __DIR__ . '/../config/db.php';

try {
    $sql = "
         SELECT 
            p.payment_id,
            p.contract_id,
            c.contract_number,
            p.payment_date,
            p.amount,
            p.payment_method
        FROM payment p
        LEFT JOIN contract c ON p.contract_id = c.contract_id
        ORDER BY p.payment_date ASC
    ";

    $stmt = $pdo->query($sql);
    
    if (!$stmt) {
        throw new Exception("Ошибка выполнения SQL запроса");
    }
    
    $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Проверка наличия данных
    if (empty($payments)) {
        echo "Нет данных в таблице payment";
    }
    
} catch (Exception $e) {
    die("Ошибка при получении данных: " . $e->getMessage());
}
?>