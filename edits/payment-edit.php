<?php
require_once __DIR__ . '/../check/check.php';
require_once __DIR__ . '/../config/db.php';

// Получаем ID платежа
$payment_id = $_GET['id'] ?? null;

if (!$payment_id) {
    header('Location: ../payments.php');
    exit;
}

try {
    // Получаем данные платежа
    $sql = "
        SELECT 
            p.payment_id,
            p.contract_id,
            p.payment_date,
            p.amount,
            p.payment_method
        FROM payment p
        WHERE p.payment_id = :payment_id
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':payment_id' => $payment_id]);
    $payment = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$payment) {
        die('Платеж не найден');
    }

    // Получаем список контрактов для выпадающего списка
    $contracts_sql = "SELECT contract_id FROM contract ORDER BY contract_id";
    $contracts_stmt = $pdo->query($contracts_sql);
    $contracts = $contracts_stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die('Ошибка при получении данных платежа: ' . $e->getMessage());
}

// Обработка сохранения
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $sql = "
            UPDATE payment SET
                contract_id = :contract_id,
                payment_date = :payment_date,
                amount = :amount,
                payment_method = :payment_method
            WHERE payment_id = :payment_id
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':contract_id' => $_POST['contract_id'],
            ':payment_date' => $_POST['payment_date'],
            ':amount' => $_POST['amount'],
            ':payment_method' => $_POST['payment_method'],
            ':payment_id' => $payment_id
        ]);

        header('Location: ../payments.php?success=1');
        exit;

    } catch (PDOException $e) {
        $error = 'Ошибка при обновлении платежа: ' . $e->getMessage();
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

    <title>Редактирование платежа <?= htmlspecialchars($payment['payment_id']) ?> | Турагентство "Земеля"</title>
</head>

<body class="bg-gray-100">

<?php if (!empty($error)): ?>
    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
        <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

<main>
    <section class="bg-gray-100 py-5 px-4">
        <h1 class="text-2xl font-bold text-gray-800">Редактирование платежа</h1>
        <p class="text-gray-600">ID: <?= htmlspecialchars($payment['payment_id']) ?></p>
    </section>

    <section class="bg-gray-100 pb-10 px-4">
        <div class="bg-white rounded-2xl shadow-md p-8">
            <form method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- ID контракта -->
                <div>
                    <label class="block mb-1 text-gray-700 font-medium">Контракт *</label>
                    <select name="contract_id" required
                            class="w-full border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-blue-500 outline-none">
                        <option value="">Выберите контракт...</option>
                        <?php foreach ($contracts as $contract): ?>
                            <option value="<?= $contract['contract_id'] ?>" 
                                    <?= $payment['contract_id'] == $contract['contract_id'] ? 'selected' : '' ?>>
                                Контракт №<?= $contract['contract_id'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Дата платежа -->
                <div>
                    <label class="block mb-1 text-gray-700 font-medium">Дата платежа *</label>
                    <input type="date" name="payment_date" required
                           value="<?= htmlspecialchars($payment['payment_date']) ?>"
                           class="w-full border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                <!-- Сумма -->
                <div>
                    <label class="block mb-1 text-gray-700 font-medium">Сумма *</label>
                    <input type="number" name="amount" required step="0.01" min="0"
                           value="<?= htmlspecialchars($payment['amount']) ?>"
                           class="w-full border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                <!-- Способ оплаты -->
                <div>
                    <label class="block mb-1 text-gray-700 font-medium">Способ оплаты *</label>
                    <select name="payment_method" required
                            class="w-full border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-blue-500 outline-none">
                        <option value="">Выберите способ...</option>
                        <option value="Карта" <?= $payment['payment_method'] == 'Карта' ? 'selected' : '' ?>>Карта</option>
                        <option value="Наличные" <?= $payment['payment_method'] == 'Наличные' ? 'selected' : '' ?>>Наличные</option>
                        <option value="Перевод" <?= $payment['payment_method'] == 'Перевод' ? 'selected' : '' ?>>Перевод</option>
                        <option value="Онлайн" <?= $payment['payment_method'] == 'Онлайн' ? 'selected' : '' ?>>Онлайн</option>
                    </select>
                </div>

                <!-- Кнопки -->
                <div class="md:col-span-2 flex justify-center gap-4 mt-6">
                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-8 rounded-xl shadow transition">
                        Сохранить изменения
                    </button>
                    <a href="../payments.php"
                       class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2.5 px-8 rounded-xl shadow transition">
                        Отмена
                    </a>
                </div>

            </form>
        </div>
    </section>
</main>

</body>
</html>