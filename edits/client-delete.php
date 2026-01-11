<?php
require_once __DIR__ . '/../check/check.php';
require_once __DIR__ . '/../config/db.php';

// Получаем ID клиента
$client_id = $_GET['id'] ?? null;

if (!$client_id) {
    header('Location: /clients.php?error=Не указан ID договора');
    exit;
}

try {
    // Начинаем транзакцию
    $pdo->beginTransaction();
    
    // Удаляем клиента
    $deleteClient = $pdo->prepare("DELETE FROM client WHERE client_id = ?");
    $deleteClient->execute([$client_id]);
    
    // Подтверждаем транзакцию
    $pdo->commit();
    
    // Перенаправляем с сообщением об успехе
    header('Location: /clients.php?success=Клиент успешно удален');
    exit;
    
} catch (PDOException $e) {
    // Откатываем транзакцию в случае ошибки
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    header('Location: clients.php?error=Ошибка при удалении клиента: ' . urlencode($e->getMessage()));
    exit;
}
?>