<?php
require_once __DIR__ . '/../check/check.php';
require_once __DIR__ . '/../config/db.php';


// Получаем ID договора из URL
$contract_id = $_GET['id'] ?? null;

if (!$contract_id) {
    header('Location: ../contracts.php');
    exit;
}

try {
    // Получаем данные договора
    $sql = "
        SELECT 
            c.*,
            cl.name AS client_name,
            cl.surname AS client_surname,
            m.name AS manager_name,
            m.surname AS manager_surname,
            t.tour_name,
            cs.status_contract
        FROM contract c
        LEFT JOIN client cl ON c.client_id = cl.client_id
        LEFT JOIN manager m ON c.manager_id = m.manager_id
        LEFT JOIN tour t ON c.tour_id = t.tour_id
        LEFT JOIN contract_status cs ON c.status_id = cs.status_id
        WHERE c.contract_id = :contract_id
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':contract_id' => $contract_id]);
    $contract = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$contract) {
        die("Договор не найден");
    }

} catch (PDOException $e) {
    die("Ошибка при получении данных договора: " . $e->getMessage());
}

// Обработка сохранения изменений
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $sql = "
            UPDATE contract SET
                contract_number = :contract_number,
                contract_date = :contract_date,
                start_date = :start_date,
                end_date = :end_date,
                total_cost = :total_cost,
                client_id = :client_id,
                manager_id = :manager_id,
                tour_id = :tour_id,
                status_id = :status_id
            WHERE contract_id = :contract_id
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':contract_number' => $_POST['contract_number'],
            ':contract_date' => $_POST['contract_date'],
            ':start_date' => $_POST['start_date'] ?: null,
            ':end_date' => $_POST['end_date'] ?: null,
            ':total_cost' => $_POST['total_cost'] ?: null,
            ':client_id' => $_POST['client_id'],
            ':manager_id' => $_POST['manager_id'],
            ':tour_id' => $_POST['tour_id'],
            ':status_id' => $_POST['status_id'],
            ':contract_id' => $contract_id
        ]);

        // Перенаправляем обратно на страницу договоров
        header('Location: ../contracts.php?success=1');
        exit;

    } catch (PDOException $e) {
        $error = "Ошибка при обновлении договора: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./template/style.css">
    <link rel="stylesheet" href="./template/null.css">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>


    <title>Редактирование договора <?= htmlspecialchars($contract['contract_id']) ?> | Турагентство "Земеля"</title>
</head>

<body class="bg-gray-100">
   

    <?php if (!empty($error)): ?>
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <main>
        <section class="bg-gray-100 py-5 px-4">
            <h1 class="text-2xl font-bold text-gray-800">Редактирование договора</h1>
            <p class="text-gray-600">ID: <?= htmlspecialchars($contract['contract_id']) ?></p>
        </section>
        <section class="bg-gray-100 pb-10 px-4">
             <div class="bg-white rounded-2xl shadow-md p-8">
            <form method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Номер договора -->
                <div>
                    <label class="block mb-1 text-gray-700 font-medium">Номер договора *</label>
                    <input type="text" name="contract_number" required
                           value="<?= htmlspecialchars($contract['contract_number']) ?>"
                           class="w-full border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none" />
                </div>
                
                <!-- Дата заключения -->
                <div>
                    <label class="block mb-1 text-gray-700 font-medium">Дата заключения *</label>
                    <input type="date" name="contract_date" required
                           value="<?= htmlspecialchars($contract['contract_date']) ?>"
                           class="w-full border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none" />
                </div>
                
                <!-- Дата начала тура -->
                <div>
                    <label class="block mb-1 text-gray-700 font-medium">Дата начала тура</label>
                    <input type="date" name="start_date"
                           value="<?= htmlspecialchars($contract['start_date']) ?>"
                           class="w-full border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none" />
                </div>
                
                <!-- Дата окончания тура -->
                <div>
                    <label class="block mb-1 text-gray-700 font-medium">Дата окончания тура</label>
                    <input type="date" name="end_date"
                           value="<?= htmlspecialchars($contract['end_date']) ?>"
                           class="w-full border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none" />
                </div>
                
                <!-- Стоимость -->
                <div>
                    <label class="block mb-1 text-gray-700 font-medium">Стоимость</label>
                    <input type="number" step="0.01" name="total_cost"
                           value="<?= htmlspecialchars($contract['total_cost']) ?>"
                           class="w-full border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none" />
                </div>
                
                <!-- ID клиента -->
                <div>
                    <label class="block mb-1 text-gray-700 font-medium">ID клиента *</label>
                    <input type="number" name="client_id" required min="1"
                           value="<?= htmlspecialchars($contract['client_id']) ?>"
                           class="w-full border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none" />
                    <?php if (isset($contract['client_name'])): ?>
                    <p class="text-sm text-gray-500 mt-1">Текущий клиент: <?= htmlspecialchars($contract['client_name'] . ' ' . $contract['client_surname']) ?></p>
                    <?php endif; ?>
                </div>
                
                <!-- ID менеджера -->
                <div>
                    <label class="block mb-1 text-gray-700 font-medium">ID менеджера *</label>
                    <input type="number" name="manager_id" required min="1"
                           value="<?= htmlspecialchars($contract['manager_id']) ?>"
                           class="w-full border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none" />
                    <?php if (isset($contract['manager_name'])): ?>
                    <p class="text-sm text-gray-500 mt-1">Текущий менеджер: <?= htmlspecialchars($contract['manager_name'] . ' ' . $contract['manager_surname']) ?></p>
                    <?php endif; ?>
                </div>
                
                <!-- ID тура -->
                <div>
                    <label class="block mb-1 text-gray-700 font-medium">ID тура *</label>
                    <input type="number" name="tour_id" required min="1"
                           value="<?= htmlspecialchars($contract['tour_id']) ?>"
                           class="w-full border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none" />
                    <?php if (isset($contract['tour_name'])): ?>
                    <p class="text-sm text-gray-500 mt-1">Текущий тур: <?= htmlspecialchars($contract['tour_name']) ?></p>
                    <?php endif; ?>
                </div>
                
                <!-- Статус -->
                <div>
                    <label class="block mb-1 text-gray-700 font-medium">Статус *</label>
                    <select name="status_id" required
                            class="w-full border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                        <option value="1" <?= $contract['status_id'] == 1 ? 'selected' : '' ?>>В обработке</option>
                        <option value="2" <?= $contract['status_id'] == 2 ? 'selected' : '' ?>>Забронировано</option>
                        <option value="3" <?= $contract['status_id'] == 3 ? 'selected' : '' ?>>Оплачено</option>
                        <option value="4" <?= $contract['status_id'] == 4 ? 'selected' : '' ?>>Завершено</option>
                    </select>
                    <?php if (isset($contract['status_name'])): ?>
                    <p class="text-sm text-gray-500 mt-1">Текущий статус: <?= htmlspecialchars($contract['status_name']) ?></p>
                    <?php endif; ?>
                </div>
                
                <!-- Кнопки -->
                <div class="md:col-span-2 flex justify-center gap-4 mt-6">
                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-8 rounded-xl shadow transition duration-200">
                        Сохранить изменения
                    </button>
                    <a href="../contracts.php"
                       class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2.5 px-8 rounded-xl shadow transition duration-200">
                        Отмена
                    </a>
                </div>
            </form>
        </div>
        </section>
    </main>

    


    <script src="./template/script.js"></script>
</body>

</html>