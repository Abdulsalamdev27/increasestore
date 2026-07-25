<?php
$userEmail = htmlspecialchars($authUser['email'] ?? 'Account');
?>

<!-- NAVBAR -->
<section class="bg-white sticky top-0 z-40 border-b border-gray-200">
  <div class="w-full px-5 py-3">
    <nav class="flex items-center justify-between">
      <div class="flex items-center gap-6">


        <a href="/Einvite/index.php"
          class="text-2xl font-bold text-color-two font-title">
          Increase store
        </a>
      </div>


        <div class="flex relative" x-data="{ open: false }">
          <button
            @click="open = !open"
            class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-100 transition"
          >
            <i class="ph ph-user-circle text-2xl text-gray-600"></i>

            <span class="hidden md:block text-sm font-medium text-gray-700">
              <?= $userEmail ?>
            </span>

            <i class="ph ph-caret-down text-sm text-gray-500"></i>
          </button>

          <!-- DROPDOWN -->
          <div
            x-show="open"
            @click.outside="open = false"
            x-transition
            class="absolute right-0 mt-2 w-44 bg-white border border-gray-200 rounded-lg shadow-lg overflow-hidden"
          >
            <a href="/Einvite/dashboard/pages/settings.php"
              class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
              Settings
            </a>

            <a href="<?= BASE_URL ?>/logout.php"
              class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
              Logout
            </a>
          </div>
            <div class="flex items-center gap-6">

                            <!-- ✅ MOBILE SIDEBAR TOGGLE (STYLE SAFE) -->
                    <button
                    id="openSidebar"
                    class="lg:hidden relative w-8 h-8 flex items-center justify-center"
                    >
                            <i class="ph-bold ph-list"></i>

                    <!-- HAMBURGER / X -->
                    <span class="hamburger block w-6 h-0.5 bg-gray-700 rounded transition-all duration-300"></span>
                    <span class="hamburger block w-6 h-0.5 bg-gray-700 rounded absolute transition-all duration-300"></span>
                    <span class="hamburger block w-6 h-0.5 bg-gray-700 rounded absolute transition-all duration-300"></span>
                    </button>
            </div>          
        </div>      


    </nav>
  </div>
</section>
