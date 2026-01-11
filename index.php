<?php 
  session_start();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./template/style.css">
    <link rel="stylesheet" href="./template/null.css">

    <link type="image/x-icon" href="./favicon.ico" rel="shortcut icon">
    <link type="Image/x-icon" href="./favicon.ico" rel="icon">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>


    <title>Добро пожаловать | Турагентство "Земеля"</title>
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
      <button type="button" command="show-modal" commandfor="mobile-menu" class=" inline-flex items-center justify-center rounded-md p-2.5 text-gray-400">
        <span class="sr-only">Open main menu</span>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon" aria-hidden="true" class="size-6">
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
          
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="#fff" d="M5 21q-.825 0-1.412-.587T3 19V5q0-.825.588-1.412T5 3h7v2H5v14h7v2zm11-4l-1.375-1.45l2.55-2.55H9v-2h8.175l-2.55-2.55L16 7l5 5z"/></svg>
         
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
        <el-dialog-panel class="fixed inset-y-0 right-0 z-50 w-full overflow-y-auto bg-gray-900 p-6 sm:max-w-sm sm:ring-1 sm:ring-gray-100/10">
          <div class="flex items-center justify-between">
            <a href="#" class="-m-1.5 p-1.5">
             
              <img src="./images/zemelya-logo.png" alt="" class="h-20 w-auto" />
            </a>
            <button type="button" command="close" commandfor="mobile-menu" class="-m-2.5 rounded-md p-2.5 text-gray-400">
              <span class="sr-only">Close menu</span>
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon" aria-hidden="true" class="size-6">
                <path d="M6 18 18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
              </svg>
            </button>
          </div>
          <div class="mt-6 flow-root">
            <div class="-my-6 divide-y divide-white/10">
              <div class="space-y-2 py-6">
                <a href="/contracts.php" class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-white hover:bg-white/5">Договоры</a>
                <a href="/clients.php" class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-white hover:bg-white/5">Клиенты</a>
                <a href="/employees.php" class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-white hover:bg-white/5">Сотрудники</a>
                <a href="/tours.php" class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-white hover:bg-white/5">Туры</a>
                <a href="/countries.php" class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-white hover:bg-white/5">Страны</a>
                <a href="/hotels.php" class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-white hover:bg-white/5">Отели</a>
                <a href="payments.php" class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-white hover:bg-white/5">Платежи</a>
              </div>
              <div class="py-6">
                <?php if (isset($_SESSION['user'])): ?>
                  <div class="flex flex-1 justify-start">
                    <div class="text-sm/6 font-semibold text-white"><?= htmlspecialchars($_SESSION['user']['email']) ?></div>
                    <a href="/auth/logout.php" class="ml-3">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="#fff" d="M5 21q-.825 0-1.412-.587T3 19V5q0-.825.588-1.412T5 3h7v2H5v14h7v2zm11-4l-1.375-1.45l2.55-2.55H9v-2h8.175l-2.55-2.55L16 7l5 5z"/></svg>
                    </a>
                  </div>
                <?php else: ?>
                  <a href="/auth.php" class="-mx-3 block rounded-lg px-3 py-2.5 text-base/7 font-semibold text-white hover:bg-white/5">Войти</a>
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
    <section class="bg-gray-100 py-14">
        <div class="max-w-5xl mx-auto px-4 text-center">
            <div class="flex justify-center mb-6">
            <img src="./images/zemelya-logo-dark.png" alt="Земеля" class="h-50 drop-shadow-lg">
            </div>

            <h1 class="text-4xl font-bold text-gray-800 mb-4">
            Добро пожаловать в рабочий портал «Земеля»
            </h1>

            <p class="text-gray-900 text-lg max-w-3xl mx-auto mb-8">
            Здесь сотрудники туристической фирмы могут управлять турами, клиентами, заказами и пользоваться другими инструментами учёта.
            </p>

            
        </div>
        <p class="text-center mt-5 text-gray-600 p-6 sm:p-0">
            Если Вы попали сюда случайно, перейдите на наш основоной сайт по <a class="underline" href="#">ссылке</a>
        </p>
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