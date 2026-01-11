<?php
require_once __DIR__ . '/../check/check.php';
require_once __DIR__ . '/../config/db.php';

// Получаем ID клиента
$client_id = $_GET['id'] ?? null;

if (!$client_id) {
    header('Location: ../clients.php');
    exit;
}

try {
    // Получаем данные клиента
    $sql = "
        SELECT 
            c.*,
            ct.client_type_name
        FROM client c
        LEFT JOIN client_type ct ON c.client_type_id = ct.client_type_id
        WHERE c.client_id = :client_id
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':client_id' => $client_id]);
    $client = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$client) {
        die('Клиент не найден');
    }

} catch (PDOException $e) {
    die('Ошибка при получении данных клиента: ' . $e->getMessage());
}

// Обработка сохранения
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $sql = "
            UPDATE client SET
                name = :name,
                surname = :surname,
                phone = :phone,
                email = :email,
                passport = :passport,
                client_type_id = :client_type_id
            WHERE client_id = :client_id
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':name' => $_POST['name'],
            ':surname' => $_POST['surname'],
            ':phone' => $_POST['phone'],
            ':email' => $_POST['email'],
            ':passport' => $_POST['passport'],
            ':client_type_id' => $_POST['client_type_id'],
            ':client_id' => $client_id
        ]);

        header('Location: ../clients.php?success=1');
        exit;

    } catch (PDOException $e) {
        $error = 'Ошибка при обновлении клиента: ' . $e->getMessage();
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

    <title>Редактирование клиента <?= htmlspecialchars($client['client_id']) ?> | Турагентство "Земеля"</title>
</head>

<body class="bg-gray-100">

<?php if (!empty($error)): ?>
    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
        <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

<main>
    <section class="bg-gray-100 py-5 px-4">
        <h1 class="text-2xl font-bold text-gray-800">Редактирование клиента</h1>
        <p class="text-gray-600">ID: <?= htmlspecialchars($client['client_id']) ?></p>
    </section>

    <section class="bg-gray-100 pb-10 px-4">
        <div class="bg-white rounded-2xl shadow-md p-8">
            <form method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Имя -->
                <div>
                    <label class="block mb-1 text-gray-700 font-medium">Имя *</label>
                    <input type="text" name="name" required
                           value="<?= htmlspecialchars($client['name']) ?>"
                           class="w-full border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                <!-- Фамилия -->
                <div>
                    <label class="block mb-1 text-gray-700 font-medium">Фамилия *</label>
                    <input type="text" name="surname" required
                           value="<?= htmlspecialchars($client['surname']) ?>"
                           class="w-full border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                <!-- Телефон -->
                <div>
                    <label class="block mb-1 text-gray-700 font-medium">Телефон *</label>
                    <input type="text" name="phone" required
                           value="<?= htmlspecialchars($client['phone']) ?>"
                           class="w-full border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                <!-- Email -->
                <div>
                    <label class="block mb-1 text-gray-700 font-medium">Email</label>
                    <input type="email" name="email"
                           value="<?= htmlspecialchars($client['email']) ?>"
                           class="w-full border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                <!-- Паспорт -->
                <div>
                    <label class="block mb-1 text-gray-700 font-medium">Паспорт *</label>
                    <input type="text" name="passport" required
                           value="<?= htmlspecialchars($client['passport']) ?>"
                           class="w-full border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                <!-- Тип клиента -->
                <div>
                    <label class="block mb-1 text-gray-700 font-medium">Тип клиента *</label>
                    <select name="client_type_id" required
                            class="w-full border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-blue-500 outline-none">
                        <option value="1" <?= $client['client_type_id'] == 1 ? 'selected' : '' ?>>Физическое лицо</option>
                        <option value="2" <?= $client['client_type_id'] == 2 ? 'selected' : '' ?>>Юридическое лицо</option>
                    </select>
                    
                </div>

                <!-- Кнопки -->
                <div class="md:col-span-2 flex justify-center gap-4 mt-6">
                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-8 rounded-xl shadow transition">
                        Сохранить изменения
                    </button>
                    <a href="../clients.php"
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
