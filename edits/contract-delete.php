<?php
require_once __DIR__ . '/../check/check.php';
require_once __DIR__ . '/../config/db.php';

// Получаем ID договора
$contract_id = $_GET['id'] ?? null;

if (!$contract_id) {
    header('Location: /contracts.php?error=Не указан ID договора');
    exit;
}

try {
    // Начинаем транзакцию
    $pdo->beginTransaction();
    
    // Сначала удаляем связанные платежи (если есть таблица payment)
    $deletePayments = $pdo->prepare("DELETE FROM payment WHERE contract_id = ?");
    $deletePayments->execute([$contract_id]);
    
    // Удаляем договор
    $deleteContract = $pdo->prepare("DELETE FROM contract WHERE contract_id = ?");
    $deleteContract->execute([$contract_id]);
    
    // Подтверждаем транзакцию
    $pdo->commit();
    
    // Перенаправляем с сообщением об успехе
    header('Location: /contracts.php?success=Договор успешно удален');
    exit;
    
} catch (PDOException $e) {
    // Откатываем транзакцию в случае ошибки
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    header('Location: contracts.php?error=Ошибка при удалении договора: ' . urlencode($e->getMessage()));
    exit;
}
?>