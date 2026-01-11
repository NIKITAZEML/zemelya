<?php
require_once __DIR__ . '/../config/db.php';

$sql = "
SELECT
    country_id,
    country_name,
    CASE 
        WHEN is_visa_required = TRUE THEN 'Требуется'
        ELSE 'Не требуется'
    END AS visa_status
FROM country
ORDER BY country_id ASC
";

$stmt = $pdo->query($sql);
$countries = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
