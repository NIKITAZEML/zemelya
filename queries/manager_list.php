<?php
require_once __DIR__ . '/../config/db.php';

$sql = "
SELECT 
    m.manager_id,
    m.name,
    m.surname,
    m.phone,
    m.email,
    CASE
        WHEN m.is_active = TRUE THEN 'Активен'
        ELSE 'Уволен'
    END AS status_name
FROM manager m
ORDER BY m.manager_id ASC
";

$stmt = $pdo->query($sql);
$managers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
