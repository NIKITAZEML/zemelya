<?php
require_once __DIR__ . '/../config/db.php';

$query = "
    SELECT 
        h.hotel_id AS id,
        h.hotel_name AS name,
        h.stars,
        h.address,
        h.contact_phone,
        CASE 
            WHEN h.stars = 1 THEN '1 звезда'
            WHEN h.stars = 2 THEN '2 звезды'
            WHEN h.stars = 3 THEN '3 звезды'
            WHEN h.stars = 4 THEN '4 звезды'
            WHEN h.stars = 5 THEN '5 звезд'
            ELSE 'Не указано'
        END AS category
    FROM hotel h
    ORDER BY h.hotel_id
";

$result = $pdo->query($query);
$hotels = $result->fetchAll(PDO::FETCH_ASSOC);
?>
