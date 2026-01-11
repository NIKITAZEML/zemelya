<?php
require_once __DIR__ . '/../check/check.php';
require_once __DIR__ . '/../config/db.php';

// ID страны
$country_id = $_GET['id'] ?? null;

if (!$country_id) {
    header('Location: ../countries.php');
    exit;
}

try {
    // Получаем страну
    $sql = "
        SELECT *
        FROM country
        WHERE country_id = :country_id
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':country_id' => $country_id]);
    $country = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$country) {
        die('Страна не найдена');
    }

} catch (PDOException $e) {
    die('Ошибка при получении данных страны: ' . $e->getMessage());
}

// Сохранение изменений
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $country_name = trim($_POST['country_name'] ?? '');
        $is_visa_required = $_POST['is_visa_required'] ?? null;

        $errors = [];

        if ($country_name === '') {
            $errors[] = 'Название страны обязательно';
        }

        if ($is_visa_required === null || !in_array($is_visa_required, ['0', '1'], true)) {
            $errors[] = 'Укажите, требуется ли виза';
        } else {
            $is_visa_required = (int)$is_visa_required;
        }

        if (!empty($errors)) {
            $error = implode('<br>', $errors);
        } else {
            $sql = "
                UPDATE country SET
                    country_name = :country_name,
                    is_visa_required = :is_visa_required
                WHERE country_id = :country_id
            ";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':country_name' => $country_name,
                ':is_visa_required' => $is_visa_required,
                ':country_id' => $country_id
            ]);

            header('Location: ../countries.php?success=1');
            exit;
        }

    } catch (PDOException $e) {
        $error = 'Ошибка при обновлении страны: ' . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <title>Редактирование страны <?= htmlspecialchars($country['country_id']) ?></title>
</head>

<body class="bg-gray-100">

<?php if (!empty($error)): ?>
    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
        <?= $error ?>
    </div>
<?php endif; ?>

<main>
    <section class="bg-gray-100 py-5 px-4">
        <h1 class="text-2xl font-bold text-gray-800">Редактирование страны</h1>
        <p class="text-gray-600">ID: <?= htmlspecialchars($country['country_id']) ?></p>
    </section>

    <section class="bg-gray-100 pb-10 px-4">
        <div class="bg-white rounded-2xl shadow-md p-8 max-w-3xl mx-auto">
            <form method="POST" class="grid grid-cols-1 gap-6">

                <!-- Название страны -->
                <div>
                    <label class="block mb-1 text-gray-700 font-medium">Название страны *</label>
                    <input type="text" name="country_name" required
                           value="<?= htmlspecialchars($country['country_name']) ?>"
                           class="w-full border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                <!-- Виза -->
                <div>
                    <label class="block mb-1 text-gray-700 font-medium">Требуется виза *</label>
                    <select name="is_visa_required" required
                            class="w-full border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-blue-500 outline-none">
                        <option value="">-- Выберите --</option>
                        <option value="1" <?= $country['is_visa_required'] ? 'selected' : '' ?>>Да</option>
                        <option value="0" <?= !$country['is_visa_required'] ? 'selected' : '' ?>>Нет</option>
                    </select>
                </div>

                <!-- Кнопки -->
                <div class="flex justify-center gap-4 mt-6">
                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-8 rounded-xl shadow transition">
                        Сохранить изменения
                    </button>
                    <a href="../countries.php"
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
