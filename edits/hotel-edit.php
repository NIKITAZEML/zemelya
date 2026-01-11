<?php
require_once __DIR__ . '/../check/check.php';
require_once __DIR__ . '/../config/db.php';

// Получаем ID отеля
$hotel_id = $_GET['id'] ?? null;

if (!$hotel_id) {
    header('Location: ../hotels.php');
    exit;
}

try {
    // Получаем данные отеля
    $sql = "
        SELECT 
            hotel_id,
            hotel_name,
            stars,
            address,
            contact_phone
        FROM hotel
        WHERE hotel_id = :hotel_id
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':hotel_id' => $hotel_id]);
    $hotel = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$hotel) {
        die('Отель не найден');
    }

} catch (PDOException $e) {
    die('Ошибка при получении данных отеля: ' . $e->getMessage());
}

// Обработка сохранения
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $sql = "
            UPDATE hotel SET
                hotel_name = :hotel_name,
                stars = :stars,
                address = :address,
                contact_phone = :contact_phone
            WHERE hotel_id = :hotel_id
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':hotel_name' => $_POST['hotel_name'],
            ':stars' => $_POST['stars'],
            ':address' => $_POST['address'],
            ':contact_phone' => $_POST['contact_phone'],
            ':hotel_id' => $hotel_id
        ]);

        header('Location: ../hotels.php?success=1');
        exit;

    } catch (PDOException $e) {
        $error = 'Ошибка при обновлении отеля: ' . $e->getMessage();
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

    <title>Редактирование отеля <?= htmlspecialchars($hotel['hotel_id']) ?> | Турагентство "Земеля"</title>
</head>

<body class="bg-gray-100">

<?php if (!empty($error)): ?>
    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
        <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

<main>
    <section class="bg-gray-100 py-5 px-4">
        <h1 class="text-2xl font-bold text-gray-800">Редактирование отеля</h1>
        <p class="text-gray-600">ID: <?= htmlspecialchars($hotel['hotel_id']) ?></p>
    </section>

    <section class="bg-gray-100 pb-10 px-4">
        <div class="bg-white rounded-2xl shadow-md p-8">
            <form method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Название отеля -->
                <div class="md:col-span-2">
                    <label class="block mb-1 text-gray-700 font-medium">Название отеля *</label>
                    <input type="text" name="hotel_name" required maxlength="100"
                           value="<?= htmlspecialchars($hotel['hotel_name']) ?>"
                           class="w-full border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                <!-- Количество звезд -->
                <div>
                    <label class="block mb-1 text-gray-700 font-medium">Количество звезд *</label>
                    <select name="stars" required
                            class="w-full border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-blue-500 outline-none">
                        <option value="">Выберите...</option>
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <option value="<?= $i ?>" <?= $hotel['stars'] == $i ? 'selected' : '' ?>>
                                <?= $i ?> <?= $i == 1 ? 'звезда' : ($i < 5 ? 'звезды' : 'звезд') ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>

                <!-- Контактный телефон -->
                <div>
                    <label class="block mb-1 text-gray-700 font-medium">Контактный телефон *</label>
                    <input type="text" name="contact_phone" required maxlength="30"
                           value="<?= htmlspecialchars($hotel['contact_phone']) ?>"
                           class="w-full border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                <!-- Адрес -->
                <div class="md:col-span-2">
                    <label class="block mb-1 text-gray-700 font-medium">Адрес *</label>
                    <textarea name="address" required maxlength="255" rows="3"
                              class="w-full border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-blue-500 outline-none"><?= htmlspecialchars($hotel['address']) ?></textarea>
                </div>

                <!-- Кнопки -->
                <div class="md:col-span-2 flex justify-center gap-4 mt-6">
                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-8 rounded-xl shadow transition">
                        Сохранить изменения
                    </button>
                    <a href="../hotels.php"
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