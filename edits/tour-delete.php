<?php
require_once __DIR__ . '/../check/check.php';
require_once __DIR__ . '/../config/db.php';

// Получаем ID клиента
$tour_id = $_GET['id'] ?? null;

if (!$tour_id) {
    header('Location: /tours.php?error=Не указан ID тура');
    exit;
}

try {
    // Начинаем транзакцию
    $pdo->beginTransaction();
    
    // Удаляем клиента
    $deleteTour = $pdo->prepare("DELETE FROM tour WHERE tour_id = ?");
    $deleteTour->execute([$tour_id]);
    
    // Подтверждаем транзакцию
    $pdo->commit();
    
    // Перенаправляем с сообщением об успехе
    header('Location: /tours.php?success=Тур успешно удален');
    exit;
    
} catch (PDOException $e) {
    // Откатываем транзакцию в случае ошибки
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    header('Location: tours.php?error=Ошибка при удалении тура: ' . urlencode($e->getMessage()));
    exit;
}
?>