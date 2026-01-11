<?php
require_once __DIR__ . '/../check/check.php';
require_once __DIR__ . '/../config/db.php';

// Получаем ID клиента
$hotel_id = $_GET['id'] ?? null;

if (!$hotel_id) {
    header('Location: /hotels.php?error=Не указан ID отеля');
    exit;
}

try {
    // Начинаем транзакцию
    $pdo->beginTransaction();
    
    // Удаляем клиента
    $deleteHotel = $pdo->prepare("DELETE FROM hotel WHERE hotel_id = ?");
    $deleteHotel->execute([$hotel_id]);
    
    // Подтверждаем транзакцию
    $pdo->commit();
    
    // Перенаправляем с сообщением об успехе
    header('Location: /hotels.php?success=Отель успешно удален');
    exit;
    
} catch (PDOException $e) {
    // Откатываем транзакцию в случае ошибки
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    header('Location: hotels.php?error=Ошибка при удалении отеля: ' . urlencode($e->getMessage()));
    exit;
}
?>