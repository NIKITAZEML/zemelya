<?php
require_once __DIR__ . '/../check/check.php';
require_once __DIR__ . '/../config/db.php';

// Получаем ID клиента
$manager_id = $_GET['id'] ?? null;

if (!$manager_id) {
    header('Location: /employees.php?error=Не указан ID сотрудника');
    exit;
}

try {
    // Начинаем транзакцию
    $pdo->beginTransaction();
    
    // Удаляем клиента
    $deleteManager = $pdo->prepare("DELETE FROM manager WHERE manager_id = ?");
    $deleteManager->execute([$manager_id]);
    
    // Подтверждаем транзакцию
    $pdo->commit();
    
    // Перенаправляем с сообщением об успехе
    header('Location: /employees.php?success=Сотрудник успешно удален');
    exit;
    
} catch (PDOException $e) {
    // Откатываем транзакцию в случае ошибки
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    header('Location: employees.php?error=Ошибка при удалении сотрудника: ' . urlencode($e->getMessage()));
    exit;
}
?>