<?php
require_once __DIR__ . '/../check/check.php';
require_once __DIR__ . '/../config/db.php';

// Получаем ID клиента
$payment_id = $_GET['id'] ?? null;

if (!$payment_id) {
    header('Location: /payments.php?error=Не указан ID платежа');
    exit;
}

try {
    // Начинаем транзакцию
    $pdo->beginTransaction();
    
    // Удаляем клиента
    $deletePayment = $pdo->prepare("DELETE FROM payment WHERE payment_id = ?");
    $deletePayment->execute([$payment_id]);
    
    // Подтверждаем транзакцию
    $pdo->commit();
    
    // Перенаправляем с сообщением об успехе
    header('Location: /payments.php?success=Платеж успешно удален');
    exit;
    
} catch (PDOException $e) {
    // Откатываем транзакцию в случае ошибки
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    header('Location: payments.php?error=Ошибка при удалении платежа: ' . urlencode($e->getMessage()));
    exit;
}
?>