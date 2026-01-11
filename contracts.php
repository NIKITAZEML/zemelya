<?php
require_once __DIR__ . '/check/check.php';
require_once __DIR__ . '/queries/contract_list.php';

// Получаем сообщения из URL
$success = $_GET['success'] ?? null;
$error = $_GET['error'] ?? null;
?>
<!DOCTYPE html>
<html lang="ru">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./template/style.css">
  <link rel="stylesheet" href="./template/null.css">
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>


  <title>Договоры | Турагентство "Земеля"</title>
</head>

<body class="bg-gray-100">
  <header class="bg-sky-600">
    <nav aria-label="Global" class="mx-auto flex max-w-7xl items-center justify-between lg:px-8">
      <div class="flex lg:flex-1">
        <a href="#" class="-m-1.5 p-1.5">
          <a href="/">
            <img src="./images/zemelya-logo.png" alt="" class="h-20 w-auto" />
          </a>
        </a>
      </div>
      <div class="flex lg:hidden">
        <button type="button" command="show-modal" commandfor="mobile-menu"
          class=" inline-flex items-center justify-center rounded-md p-2.5 text-gray-400">
          <span class="sr-only">Open main menu</span>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon"
            aria-hidden="true" class="size-6">
            <path d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
        </button>
      </div>
      <el-popover-group class="hidden lg:flex lg:gap-x-12">
        <a href="/contracts.php" class="text-sm/6 font-semibold text-white">Договоры</a>
        <a href="/clients.php" class="text-sm/6 font-semibold text-white">Клиенты</a>
        <a href="/employees.php" class="text-sm/6 font-semibold text-white">Сотрудники</a>
        <a href="/tours.php" class="text-sm/6 font-semibold text-white">Туры</a>
        <a href="/countries.php" class="text-sm/6 font-semibold text-white">Страны</a>
        <a href="/hotels.php" class="text-sm/6 font-semibold text-white">Отели</a>
        <a href="/payments.php" class="text-sm/6 font-semibold text-white">Платежи</a>
      </el-popover-group>
      <?php if (isset($_SESSION['user'])): ?>
        <div class="hidden lg:flex lg:flex-1 lg:justify-end">
          <div class="text-sm/6 font-semibold text-white"><?= htmlspecialchars($_SESSION['user']['email']) ?></div>
          <a href="/auth/logout.php" class="ml-3">

            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
              <path fill="#fff"
                d="M5 21q-.825 0-1.412-.587T3 19V5q0-.825.588-1.412T5 3h7v2H5v14h7v2zm11-4l-1.375-1.45l2.55-2.55H9v-2h8.175l-2.55-2.55L16 7l5 5z" />
            </svg>

          </a>
        </div>
      <?php else: ?>
        <div class="hidden lg:flex lg:flex-1 lg:justify-end">
          <a href="/auth.php" class="text-sm/6 font-semibold text-white">Войти <span aria-hidden="true">&rarr;</span></a>
        </div>
      <?php endif; ?>
    </nav>
    <el-dialog>
      <dialog id="mobile-menu" class="backdrop:bg-transparent lg:hidden">
        <div tabindex="0" class="fixed inset-0 focus:outline-none">
          <el-dialog-panel
            class="fixed inset-y-0 right-0 z-50 w-full overflow-y-auto bg-gray-900 p-6 sm:max-w-sm sm:ring-1 sm:ring-gray-100/10">
            <div class="flex items-center justify-between">
              <a href="#" class="-m-1.5 p-1.5">

                <img src="./images/zemelya-logo.png" alt="" class="h-20 w-auto" />
              </a>
              <button type="button" command="close" commandfor="mobile-menu"
                class="-m-2.5 rounded-md p-2.5 text-gray-400">
                <span class="sr-only">Close menu</span>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon"
                  aria-hidden="true" class="size-6">
                  <path d="M6 18 18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
              </button>
            </div>
            <div class="mt-6 flow-root">
              <div class="-my-6 divide-y divide-white/10">
                <div class="space-y-2 py-6">
                  <a href="/contracts.php"
                    class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-white hover:bg-white/5">Договоры</a>
                  <a href="/clients.php"
                    class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-white hover:bg-white/5">Клиенты</a>
                  <a href="/employees.php"
                    class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-white hover:bg-white/5">Сотрудники</a>
                  <a href="/tours.php"
                    class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-white hover:bg-white/5">Туры</a>
                  <a href="/countries.php"
                    class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-white hover:bg-white/5">Страны</a>
                  <a href="/hotels.php"
                    class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-white hover:bg-white/5">Отели</a>
                  <a href="payments.php"
                    class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-white hover:bg-white/5">Платежи</a>
                </div>
                <div class="py-6">
                  <?php if (isset($_SESSION['user'])): ?>
                    <div class="flex flex-1 justify-start">
                      <div class="text-sm/6 font-semibold text-white"><?= htmlspecialchars($_SESSION['user']['email']) ?>
                      </div>
                      <a href="/auth/logout.php" class="ml-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                          <path fill="#fff"
                            d="M5 21q-.825 0-1.412-.587T3 19V5q0-.825.588-1.412T5 3h7v2H5v14h7v2zm11-4l-1.375-1.45l2.55-2.55H9v-2h8.175l-2.55-2.55L16 7l5 5z" />
                        </svg>
                      </a>
                    </div>
                  <?php else: ?>
                    <a href="/auth.php"
                      class="-mx-3 block rounded-lg px-3 py-2.5 text-base/7 font-semibold text-white hover:bg-white/5">Войти</a>
                  <?php endif; ?>
                </div>

              </div>
            </div>
          </el-dialog-panel>
        </div>
      </dialog>
    </el-dialog>
  </header>

  <main>
    <!-- Сообщения об успехе/ошибке -->
    <?php if ($success): ?>
      <div class="mx-4 my-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
        Договор успешно изменен
      </div>
    <?php endif; ?>

    <?php if ($error): ?>
      <div class="mx-4 my-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
        Ошибка изменения договора. Обратитесь к администратору
      </div>
    <?php endif; ?>


    <!-- Таблица договоров и форма добавления -->
    <section class="min-h-screen bg-gray-100 py-10 px-4">
      <!-- Таблица договоров -->
      <div class="bg-white rounded-2xl shadow-md p-6 mb-10 overflow-x-auto">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Список договоров</h1>

        <table class="w-full border border-gray-200 rounded-xl overflow-hidden">
          <thead class="bg-gray-50">
            <tr class="text-left text-gray-700 font-medium">
              <th class="p-3 border-b">ID</th>
              <th class="p-3 border-b">Номер</th>
              <th class="p-3 border-b">Дата заключения</th>
              <th class="p-3 border-b">Начало тура</th>
              <th class="p-3 border-b">Окончание тура</th>
              <th class="p-3 border-b">Стоимость</th>
              <th class="p-3 border-b">Клиент</th>
              <th class="p-3 border-b">Менеджер</th>
              <th class="p-3 border-b">Тур</th>
              <th class="p-3 border-b">Статус</th>
              <th class="p-3 border-b">Действия</th>
            </tr>
          </thead>

          <tbody class="text-gray-700">

            <?php foreach ($contracts as $row): ?>
              <tr class="border-b">
                <td class="p-3"><?= $row['contract_id'] ?></td>
                <td class="p-3"><?= $row['contract_number'] ?></td>
                <td class="p-3"><?= $row['contract_date'] ?></td>
                <td class="p-3"><?= $row['start_date'] ?></td>
                <td class="p-3"><?= $row['end_date'] ?></td>
                <td class="p-3"><?= $row['total_cost'] ?></td>

                <td class="p-3"><?= $row['client_fullname'] ?></td>
                <td class="p-3"><?= $row['manager_fullname'] ?></td>
                <td class="p-3"><?= $row['tour_name'] ?></td>
                <td class="p-3"><?= $row['status_name'] ?></td>

                <td class="p-3 flex gap-2">
                  <a href="/edits/contract-edit.php?id=<?= $row['contract_id'] ?>"
                    class="px-3 py-1 text-sm bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg">Изменить</a>
                  <a href="/edits/contract-delete.php?id=<?= $row['contract_id'] ?>"
                    class="px-3 py-1 text-sm bg-red-600 hover:bg-red-700 text-white rounded-lg"
                    onclick="return confirm('Вы уверены, что хотите удалить договор <?= htmlspecialchars(addslashes($row['contract_number'])) ?>?')">Удалить</a>
                </td>
              </tr>
            <?php endforeach; ?>

          </tbody>
        </table>
        <a name="endList"></a>
        <!-- Сообщения об успехе/ошибке -->
          <?php if ($success): ?>
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
              Договор успешно изменен
            </div>
          <?php endif; ?>

          <?php if ($error): ?>
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
              Ошибка изменения договора. Обратитесь к администратору
            </div>
          <?php endif; ?>
      </div>

      <!-- Форма добавления договора -->
      <div class="bg-white rounded-2xl shadow-md p-8 w-full max-w-3xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Добавить новый договор</h1>

        <!-- Контейнер для сообщений -->
        <div id="message-container" class="mb-4"></div>

        <form id="adding-form" data-form="create_contract.php" class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block mb-1 text-gray-700 font-medium">Номер договора *</label>
            <input type="text" name="contract_number" required
              class="w-full border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none" />
          </div>

          <div>
            <label class="block mb-1 text-gray-700 font-medium">Дата заключения *</label>
            <input type="date" name="contract_date" required
              class="w-full border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none" />
          </div>

          <div>
            <label class="block mb-1 text-gray-700 font-medium">Дата начала тура</label>
            <input type="date" name="start_date"
              class="w-full border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none" />
          </div>

          <div>
            <label class="block mb-1 text-gray-700 font-medium">Дата окончания тура</label>
            <input type="date" name="end_date"
              class="w-full border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none" />
          </div>

          <div>
            <label class="block mb-1 text-gray-700 font-medium">Стоимость</label>
            <input type="number" step="0.01" name="total_cost"
              class="w-full border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none" />
          </div>

          <div>
            <label class="block mb-1 text-gray-700 font-medium">ID клиента *</label>
            <input type="number" name="client_id" required min="1"
              class="w-full border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none" />
          </div>

          <div>
            <label class="block mb-1 text-gray-700 font-medium">ID менеджера *</label>
            <input type="number" name="manager_id" required min="1"
              class="w-full border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none" />
          </div>

          <div>
            <label class="block mb-1 text-gray-700 font-medium">ID тура *</label>
            <input type="number" name="tour_id" required min="1"
              class="w-full border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none" />
          </div>

          <div>
            <label class="block mb-1 text-gray-700 font-medium">Статус *</label>
            <select name="status_id" required
              class="w-full border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
              <option value="">Выберите статус</option>
              <option value="1">В обработке</option>
              <option value="2">Забронировано</option>
              <option value="3">Оплачено</option>
              <option value="4">Завершено</option>
            </select>
          </div>

          <div class="md:col-span-2 flex justify-center mt-4">
            <button type="submit"
              class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-6 rounded-xl shadow transition duration-200">
              Добавить договор
            </button>
          </div>
        </form>
      </div>
    </section>

  </main>



  <footer class="bg-neutral-primary-soft rounded-base shadow-xs bg-sky-600 text-white border-default">
    <div class="w-full max-w-screen-xl mx-auto p-4 md:py-3">
      <div class="sm:flex sm:items-center sm:justify-between">
        <a href="/" class="flex items-center mb-4 sm:mb-0 space-x-3 rtl:space-x-reverse">
          <img src="./images/zemelya-logo.png" class="h-20" alt="Flowbite Logo" />
        </a>
        <ul class="flex flex-wrap items-center mb-6 text-sm font-medium text-body sm:mb-0">
          <li>
            <a href="/contracts.php" class="hover:underline me-4 md:me-6">Договоры</a>
          </li>
          <li>
            <a href="/clients.php" class="hover:underline me-4 md:me-6">Клиенты</a>
          </li>
          <li>
            <a href="/employees.php" class="hover:underline me-4 md:me-6">Сотрудники</a>
          </li>
          <li>
            <a href="/tours.php" class="hover:underline me-4 md:me-6">Туры</a>
          </li>
          <li>
            <a href="/countries.php" class="hover:underline me-4 md:me-6">Страны</a>
          </li>
          <li>
            <a href="/hotels.php" class="hover:underline me-4 md:me-6">Отели</a>
          </li>
          <li>
            <a href="/payments.php" class="hover:underline me-4 md:me-6">Платежи</a>
          </li>
        </ul>
      </div>
    </div>
  </footer>


  <script src="./template/script.js"></script>
</body>

</html>