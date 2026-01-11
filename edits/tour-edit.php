<?php
require_once __DIR__ . '/../check/check.php';
require_once __DIR__ . '/../config/db.php';

// Получаем ID тура
$tour_id = $_GET['id'] ?? null;

if (!$tour_id) {
    header('Location: ../tours.php');
    exit;
}

try {
    // Получаем данные тура
    $sql = "
        SELECT *
        FROM tour
        WHERE tour_id = :tour_id
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':tour_id' => $tour_id]);
    $tour = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$tour) {
        die('Тур не найден');
    }

} catch (PDOException $e) {
    die('Ошибка при получении данных тура: ' . $e->getMessage());
}

// Обработка сохранения изменений
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $sql = "
            UPDATE tour SET
                tour_name = :tour_name,
                country_id = :country_id,
                hotel_id = :hotel_id,
                duration = :duration,
                price = :price,
                description = :description
            WHERE tour_id = :tour_id
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':tour_name'  => $_POST['tour_name'],
            ':country_id' => $_POST['country_id'],
            ':hotel_id'   => $_POST['hotel_id'],
            ':duration'   => $_POST['duration'],
            ':price'      => $_POST['price'],
            ':description'=> $_POST['description'] ?: null,
            ':tour_id'    => $tour_id
        ]);

        header('Location: ../tours.php?success=1');
        exit;

    } catch (PDOException $e) {
        $error = 'Ошибка при обновлении тура: ' . $e->getMessage();
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

    <title>Редактирование тура <?= htmlspecialchars($tour['tour_id']) ?> | Турагентство "Земеля"</title>
</head>

<body class="bg-gray-100">

<?php if (!empty($error)): ?>
    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
        <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

<main>
    <section class="bg-gray-100 py-5 px-4">
        <h1 class="text-2xl font-bold text-gray-800">Редактирование тура</h1>
        <p class="text-gray-600">ID: <?= htmlspecialchars($tour['tour_id']) ?></p>
    </section>

    <section class="bg-gray-100 pb-10 px-4">
        <div class="bg-white rounded-2xl shadow-md p-8">

            <form method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Название тура -->
                <div class="md:col-span-2">
                    <label class="block mb-1 text-gray-700 font-medium">Название тура *</label>
                    <input type="text" name="tour_name" required
                           value="<?= htmlspecialchars($tour['tour_name']) ?>"
                           class="w-full border border-gray-300 rounded-xl py-2 px-3
                                  focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                <!-- Страна -->
                <div>
                    <label class="block mb-1 text-gray-700 font-medium">ID страны *</label>
                    <input type="number" name="country_id" required min="1"
                           value="<?= htmlspecialchars($tour['country_id']) ?>"
                           class="w-full border border-gray-300 rounded-xl py-2 px-3
                                  focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                <!-- Отель -->
                <div>
                    <label class="block mb-1 text-gray-700 font-medium">ID отеля *</label>
                    <input type="number" name="hotel_id" required min="1"
                           value="<?= htmlspecialchars($tour['hotel_id']) ?>"
                           class="w-full border border-gray-300 rounded-xl py-2 px-3
                                  focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                <!-- Длительность -->
                <div>
                    <label class="block mb-1 text-gray-700 font-medium">Длительность (дней) *</label>
                    <input type="number" name="duration" required min="1"
                           value="<?= htmlspecialchars($tour['duration']) ?>"
                           class="w-full border border-gray-300 rounded-xl py-2 px-3
                                  focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                <!-- Цена -->
                <div>
                    <label class="block mb-1 text-gray-700 font-medium">Цена *</label>
                    <input type="number" name="price" step="0.01" required
                           value="<?= htmlspecialchars($tour['price']) ?>"
                           class="w-full border border-gray-300 rounded-xl py-2 px-3
                                  focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                <!-- Описание -->
                <div class="md:col-span-2">
                    <label class="block mb-1 text-gray-700 font-medium">Описание</label>
                    <textarea name="description" rows="4"
                              class="w-full border border-gray-300 rounded-xl py-2 px-3
                                     focus:ring-2 focus:ring-blue-500 outline-none"><?= htmlspecialchars($tour['description']) ?></textarea>
                </div>

                <!-- Кнопки -->
                <div class="md:col-span-2 flex justify-center gap-4 mt-6">
                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold
                                   py-2.5 px-8 rounded-xl shadow transition">
                        Сохранить изменения
                    </button>
                    <a href="../tours.php"
                       class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold
                              py-2.5 px-8 rounded-xl shadow transition">
                        Отмена
                    </a>
                </div>

            </form>

        </div>
    </section>
</main>

</body>
</html>
