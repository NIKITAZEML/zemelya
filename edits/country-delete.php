<?php
require_once __DIR__ . '/../check/check.php';
require_once __DIR__ . '/../config/db.php';

// Получаем ID клиента
$country_id = $_GET['id'] ?? null;

if (!$country_id) {
    header('Location: /countries.php?error=Не указан ID страны');
    exit;
}

try {
    // Начинаем транзакцию
    $pdo->beginTransaction();
    
    // Удаляем клиента
    $deleteCountry = $pdo->prepare("DELETE FROM country WHERE country_id = ?");
    $deleteCountry->execute([$country_id]);
    
    // Подтверждаем транзакцию
    $pdo->commit();
    
    // Перенаправляем с сообщением об успехе
    header('Location: /countries.php?success=Страна успешно удалена');
    exit;
    
} catch (PDOException $e) {
    // Откатываем транзакцию в случае ошибки
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    header('Location: countries.php?error=Ошибка при удалении страны: ' . urlencode($e->getMessage()));
    exit;
}
?>