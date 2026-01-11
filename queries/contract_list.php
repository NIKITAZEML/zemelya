<?php
  require_once __DIR__ . '/../config/db.php';

// Запрос
$sql = "
SELECT 
    c.contract_id,
    c.contract_number,
    c.contract_date,
    c.start_date,
    c.end_date,
    c.total_cost,

    cl.surname || ' ' || cl.name AS client_fullname,
    m.surname || ' ' || m.name AS manager_fullname,
    t.tour_name,

    CASE 
      WHEN c.status_id = 1 THEN 'В обработке'
      WHEN c.status_id = 2 THEN 'Забронировано'
      WHEN c.status_id = 3 THEN 'Оплачено'
      WHEN c.status_id = 4 THEN 'Завершено'
      ELSE 'Неизвестно'
    END AS status_name

FROM contract c
LEFT JOIN client cl ON c.client_id = cl.client_id
LEFT JOIN manager m ON c.manager_id = m.manager_id
LEFT JOIN tour t ON c.tour_id = t.tour_id
LEFT JOIN contract_status cs ON c.status_id = cs.status_id

ORDER BY c.contract_id ASC
";

$stmt = $pdo->query($sql);
$contracts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>