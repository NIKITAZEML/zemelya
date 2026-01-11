<?php
require_once __DIR__ . '/../check/check.php';
require_once __DIR__ . '/../config/db.php';

// Получаем ID сотрудника
$manager_id = $_GET['id'] ?? null;

if (!$manager_id) {
    header('Location: ../employees.php');
    exit;
}

try {
    // Получаем данные сотрудника
    $sql = "SELECT * FROM manager WHERE manager_id = :manager_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':manager_id' => $manager_id]);
    $manager = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$manager) {
        die('Сотрудник не найден');
    }

} catch (PDOException $e) {
    die('Ошибка при получении данных сотрудника: ' . $e->getMessage());
}

// Обработка сохранения
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $sql = "
            UPDATE manager SET
                name = :name,
                surname = :surname,
                phone = :phone,
                email = :email,
                is_active = :is_active
            WHERE manager_id = :manager_id
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':name' => $_POST['name'],
            ':surname' => $_POST['surname'],
            ':phone' => $_POST['phone'],
            ':email' => $_POST['email'],
            ':is_active' => isset($_POST['is_active']) ? true : false,
            ':manager_id' => $manager_id
        ]);

        header('Location: ../employees.php?success=1');
        exit;

    } catch (PDOException $e) {
        $error = 'Ошибка при обновлении сотрудника: ' . $e->getMessage();
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

    <title>Редактирование сотрудника <?= htmlspecialchars($manager['manager_id']) ?> | Турагентство "Земеля"</title>
</head>

<body class="bg-gray-100">

<?php if (!empty($error)): ?>
    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
        <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

<main>
    <section class="bg-gray-100 py-5 px-4">
        <h1 class="text-2xl font-bold text-gray-800">Редактирование сотрудника</h1>
        <p class="text-gray-600">ID: <?= htmlspecialchars($manager['manager_id']) ?></p>
    </section>

    <section class="bg-gray-100 pb-10 px-4">
        <div class="bg-white rounded-2xl shadow-md p-8">
            <form method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Имя -->
                <div>
                    <label class="block mb-1 text-gray-700 font-medium">Имя *</label>
                    <input type="text" name="name" required
                           value="<?= htmlspecialchars($manager['name']) ?>"
                           class="w-full border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                <!-- Фамилия -->
                <div>
                    <label class="block mb-1 text-gray-700 font-medium">Фамилия *</label>
                    <input type="text" name="surname" required
                           value="<?= htmlspecialchars($manager['surname']) ?>"
                           class="w-full border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                <!-- Телефон -->
                <div>
                    <label class="block mb-1 text-gray-700 font-medium">Телефон *</label>
                    <input type="text" name="phone" required
                           value="<?= htmlspecialchars($manager['phone']) ?>"
                           class="w-full border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                <!-- Email -->
                <div>
                    <label class="block mb-1 text-gray-700 font-medium">Email *</label>
                    <input type="email" name="email" required
                           value="<?= htmlspecialchars($manager['email']) ?>"
                           class="w-full border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                <!-- Активность -->
                <div class="flex items-center gap-2 mt-6">
                    <input type="checkbox" name="is_active" value="1"
                           <?= $manager['is_active'] ? 'checked' : '' ?>
                           class="w-4 h-4 text-blue-600 border-gray-300 rounded">
                    <label class="text-gray-700 font-medium">Активен</label>
                </div>

                <!-- Кнопки -->
                <div class="md:col-span-2 flex justify-center gap-4 mt-6">
                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-8 rounded-xl shadow transition">
                        Сохранить изменения
                    </button>
                    <a href="../employees.php"
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
