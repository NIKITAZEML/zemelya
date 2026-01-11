<?php
  require_once __DIR__ . '/../config/db.php';

  $sql = "
    SELECT 
        c.client_id,
        c.name,
        c.surname,
        c.phone,
        c.email,
        c.passport,
        ct.client_type_name
    FROM client c
    LEFT JOIN client_type ct ON c.client_type_id = ct.client_type_id
    ORDER BY c.client_id ASC
    ";

    $stmt = $pdo->query($sql);
    $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>