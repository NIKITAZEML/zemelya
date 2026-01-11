<?php
require_once __DIR__ . '/../config/db.php';

$sql = "
SELECT
    t.tour_id,
    t.tour_name,
    t.duration,
    t.price,
    t.description,

    c.country_name,
    CASE 
        WHEN c.is_visa_required = TRUE THEN 'Да'
        ELSE 'Нет'
    END AS visa_required,

    h.hotel_name,
    h.stars
FROM tour t
LEFT JOIN country c ON t.country_id = c.country_id
LEFT JOIN hotel h ON t.hotel_id = h.hotel_id
ORDER BY t.tour_id ASC
";

$stmt = $pdo->query($sql);
$tours = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
