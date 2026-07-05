<!-- Overlay (Mobile) -->
<div id="overlay"
  class="fixed inset-0 bg-black/40 hidden z-40 lg:hidden"></div>

<!-- Sidebar -->
<aside id="sidebar"
  class="fixed top-0 left-0 z-50 h-full w-72 bg-white shadow-xl
         transform -translate-x-full lg:translate-x-0
         transition-transform duration-300">

  <!-- Header -->
  <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
    <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
      <i class="ph ph-qr-code text-xl"></i>
              <a href="/Einvite/index.php"
          class="text-2xl font-bold text-color-two font-title">
          Increase Store
        </a>
    </h2>

    <button id="closeSidebar"
      class="lg:hidden text-gray-500 hover:text-gray-700">
      <!-- Heroicon: X -->
      <svg xmlns="http://www.w3.org/2000/svg"
        class="w-6 h-6"
        fill="none" viewBox="0 0 24 24"
        stroke-width="2" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round"
          d="M6 18L18 6M6 6l12 12" />
      </svg>
    </button>
  </div>

  <!-- Menu -->
  <nav class="p-4 space-y-6 overflow-y-auto h-full">

    <!-- MAIN -->
    <div>
      <p class="text-xs uppercase text-gray-400 mb-2">Main</p>

      <a href="index.php"
        class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-100">
        <i class="ph-bold ph-house-simple text-lg"></i>
        <span>Dashboard</span>
      </a>
    </div>

    <!-- EVENT -->
    <details class="group">
      <summary
        class="flex items-center justify-between cursor-pointer px-3 py-2 rounded-lg hover:bg-gray-100">
        <span class="flex items-center gap-3">
          <i class="ph-bold ph-calendar-heart text-lg"></i>
          Store
        </span>
        <i class="ph-bold ph-caret-down group-open:rotate-180 transition"></i>
      </summary>

      <div class="ml-8 mt-2 space-y-1">
        <a href="create-store.php"
          class="block py-1 text-sm hover:text-blue-600">
          Create Store
        </a>
        <a href="view-stores.php"
          class="block py-1 text-sm hover:text-blue-600">
          View Store
        </a>
      </div>
    </details>

    <!-- staff -->
    <details class="group">
      <summary
        class="flex items-center justify-between cursor-pointer px-3 py-2 rounded-lg hover:bg-gray-100">
        <span class="flex items-center gap-3">
          <i class="ph-bold ph-building-office text-lg"></i>
          Staff
        </span>
        <i class="ph-bold ph-caret-down group-open:rotate-180 transition"></i>
      </summary>

      <div class="ml-8 mt-2 space-y-1">
        <a href="create-staff.php" class="block py-1 text-sm hover:text-blue-600">
          Create Staff
        </a>
        <a href="view-all-staff.php" class="block py-1 text-sm hover:text-blue-600">
          View Staff
        </a>
      </div>
    </details>

    <!-- HANDOUT -->
    <details class="group">
      <summary
        class="flex items-center justify-between cursor-pointer px-3 py-2 rounded-lg hover:bg-gray-100">
        <span class="flex items-center gap-3">
          <i class="ph-bold ph-notebook text-lg"></i>
          Handout QR Code
        </span>
        <i class="ph-bold ph-caret-down group-open:rotate-180 transition"></i>
      </summary>

      <div class="ml-8 mt-2 space-y-1">
        <a href="create-handout.php" class="block py-1 text-sm hover:text-blue-600">
          Create Handout
        </a>
        <a href="view-all-handout.php" class="block py-1 text-sm hover:text-blue-600">
          View Handouts
        </a>
      </div>
    </details>

    <!-- CHURCH -->
    <details class="group">
      <summary
        class="flex items-center justify-between cursor-pointer px-3 py-2 rounded-lg hover:bg-gray-100">
        <span class="flex items-center gap-3">
          <i class="ph-bold ph-book-open text-lg"></i>
          Church QR Code
        </span>
        <i class="ph-bold ph-caret-down group-open:rotate-180 transition"></i>
      </summary>

      <div class="ml-8 mt-2 space-y-1">
        <a href="create-church.php" class="block py-1 text-sm hover:text-blue-600">
          Create Church
        </a>
        <a href="view-all-church.php" class="block py-1 text-sm hover:text-blue-600">
          View Churches
        </a>
      </div>
    </details>

    <!-- BILLING -->
    <details class="group">
      <summary
        class="flex items-center justify-between cursor-pointer px-3 py-2 rounded-lg hover:bg-gray-100">
        <span class="flex items-center gap-3">
          <i class="ph-bold ph-credit-card text-lg"></i>
          Billing
        </span>
        <i class="ph-bold ph-caret-down group-open:rotate-180 transition"></i>
      </summary>

      <div class="ml-8 mt-2 space-y-1">
        <a href="subscribe.php" class="block py-1 text-sm hover:text-blue-600">
          Subscribe
        </a>
        <a href="billing-history.php" class="block py-1 text-sm hover:text-blue-600">
          History
        </a>
      </div>
    </details>

  </nav>
</aside>
