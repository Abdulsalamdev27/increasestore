<!-- ==========================================
     MOBILE OVERLAY
=========================================== -->
<div
  id="overlay"
  class="fixed inset-0 z-40 hidden bg-black/40 lg:hidden">
</div>


<!-- ==========================================
     SIDEBAR
=========================================== -->
<aside
  id="sidebar"
  class="fixed top-0 left-0 z-50 h-full w-72
         bg-white shadow-xl
         transform -translate-x-full
         lg:translate-x-0
         transition-transform duration-300">

  <!-- ==========================================
       SIDEBAR HEADER
  =========================================== -->
  <div
    class="flex items-center justify-between
           border-b border-gray-200
           px-6 py-4">

    <h2
      class="flex items-center gap-2
             text-lg font-bold text-gray-800">

      <i class="ph-bold ph-storefront text-xl text-indigo-600"></i>

      <a
        href="index.php"
        class="text-2xl font-bold text-color-two font-title">
        Increase Store
      </a>

    </h2>


    <!-- Mobile Close -->
    <button
      id="closeSidebar"
      type="button"
      class="text-gray-500 hover:text-gray-700 lg:hidden">

      <svg
        xmlns="http://www.w3.org/2000/svg"
        class="h-6 w-6"
        fill="none"
        viewBox="0 0 24 24"
        stroke-width="2"
        stroke="currentColor">

        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          d="M6 18L18 6M6 6l12 12" />

      </svg>

    </button>

  </div>


  <!-- ==========================================
       NAVIGATION
  =========================================== -->
  <nav
    class="h-full space-y-6 overflow-y-auto p-4 pb-24">


    <!-- ======================================
         MAIN
    ======================================= -->
    <div>

      <p
        class="mb-2 px-3 text-xs font-semibold
               uppercase tracking-wide text-gray-400">

        Main

      </p>


      <!-- Dashboard -->
      <a
        href="index.php"
        class="flex items-center gap-3 rounded-lg
               px-3 py-2.5
               text-gray-700
               hover:bg-gray-100
               hover:text-indigo-600">

        <i class="ph-bold ph-house-simple text-lg"></i>

        <span>Dashboard</span>

      </a>

    </div>


    <!-- ======================================
         STORE
    ======================================= -->
    <details class="group">

      <summary
        class="flex cursor-pointer
               items-center justify-between
               rounded-lg px-3 py-2.5
               text-gray-700
               hover:bg-gray-100
               hover:text-indigo-600">

        <span class="flex items-center gap-3">

          <i class="ph-bold ph-storefront text-lg"></i>

          <span>Store</span>

        </span>


        <i
          class="ph-bold ph-caret-down
                 transition-transform
                 group-open:rotate-180">
        </i>

      </summary>


      <div class="ml-8 mt-2 space-y-1">

        <a
          href="create-store.php"
          class="block rounded-md py-1.5 text-sm
                 text-gray-600
                 hover:text-indigo-600">

          Create Store

        </a>


        <a
          href="view-stores.php"
          class="block rounded-md py-1.5 text-sm
                 text-gray-600
                 hover:text-indigo-600">

          View Stores

        </a>

      </div>

    </details>


    <!-- ======================================
         STAFF
    ======================================= -->
    <details class="group">

      <summary
        class="flex cursor-pointer
               items-center justify-between
               rounded-lg px-3 py-2.5
               text-gray-700
               hover:bg-gray-100
               hover:text-indigo-600">

        <span class="flex items-center gap-3">

          <i class="ph-bold ph-users-three text-lg"></i>

          <span>Staff</span>

        </span>


        <i
          class="ph-bold ph-caret-down
                 transition-transform
                 group-open:rotate-180">
        </i>

      </summary>


      <div class="ml-8 mt-2 space-y-1">

        <a
          href="create-staff.php"
          class="block rounded-md py-1.5 text-sm
                 text-gray-600
                 hover:text-indigo-600">

          Create Staff

        </a>


        <a
          href="view-all-staff.php"
          class="block rounded-md py-1.5 text-sm
                 text-gray-600
                 hover:text-indigo-600">

          View Staff

        </a>

      </div>

    </details>


    <!-- ======================================
         PRODUCTS
    ======================================= -->
    <details class="group">

      <summary
        class="flex cursor-pointer
               items-center justify-between
               rounded-lg px-3 py-2.5
               text-gray-700
               hover:bg-gray-100
               hover:text-indigo-600">

        <span class="flex items-center gap-3">

          <i class="ph-bold ph-package text-lg"></i>

          <span>Products</span>

        </span>


        <i
          class="ph-bold ph-caret-down
                 transition-transform
                 group-open:rotate-180">
        </i>

      </summary>


      <div class="ml-8 mt-2 space-y-1">

        <a
          href="create-product.php"
          class="block rounded-md py-1.5 text-sm
                 text-gray-600
                 hover:text-indigo-600">

          Create Product

        </a>


        <a
          href="view-all-product.php"
          class="block rounded-md py-1.5 text-sm
                 text-gray-600
                 hover:text-indigo-600">

          View Products

        </a>


        <a
          href="accepted-products.php"
          class="block rounded-md py-1.5 text-sm
                 text-gray-600
                 hover:text-indigo-600">

          Search Products

        </a>

      </div>

    </details>


    <!-- ======================================
         PRODUCT TRANSFERS
    ======================================= -->
    <details class="group">

      <summary
        class="flex cursor-pointer
               items-center justify-between
               rounded-lg px-3 py-2.5
               text-gray-700
               hover:bg-gray-100
               hover:text-indigo-600">

        <span class="flex items-center gap-3">

          <i class="ph-bold ph-arrows-left-right text-lg"></i>

          <span>Product Transfers</span>

        </span>


        <i
          class="ph-bold ph-caret-down
                 transition-transform
                 group-open:rotate-180">
        </i>

      </summary>


      <div class="ml-8 mt-2 space-y-1">

        <!-- Create Transfer -->
        <a
          href="create-transfer-product.php"
          class="block rounded-md py-1.5 text-sm
                 text-gray-600
                 hover:text-indigo-600">

          Create Transfer

        </a>


        <!-- View Transfers -->
        <a
          href="view-all-Transfer-product.php"
          class="block rounded-md py-1.5 text-sm
                 text-gray-600
                 hover:text-indigo-600">

          View Transfers

        </a>

      </div>

    </details>


    <!-- ======================================
         ORDERS
    ======================================= -->
    <details class="group">

      <summary
        class="flex cursor-pointer
               items-center justify-between
               rounded-lg px-3 py-2.5
               text-gray-700
               hover:bg-gray-100
               hover:text-indigo-600">

        <span class="flex items-center gap-3">

          <i class="ph-bold ph-shopping-cart text-lg"></i>

          <span>Orders</span>

        </span>


        <i
          class="ph-bold ph-caret-down
                 transition-transform
                 group-open:rotate-180">
        </i>

      </summary>


      <div class="ml-8 mt-2 space-y-1">

        <!-- Create Order -->
        <a
          href="create-order.php"
          class="block rounded-md py-1.5 text-sm
                 text-gray-600
                 hover:text-indigo-600">

          Create Order

        </a>


        <!-- View Orders -->
        <a
          href="view-all-orders.php"
          class="block rounded-md py-1.5 text-sm
                 text-gray-600
                 hover:text-indigo-600">

          View Orders

        </a>

      </div>

    </details>


    <!-- ======================================
         INVENTORY
    ======================================= -->
    <!-- <details class="group">

      <summary
        class="flex cursor-pointer
               items-center justify-between
               rounded-lg px-3 py-2.5
               text-gray-700
               hover:bg-gray-100
               hover:text-indigo-600">

        <span class="flex items-center gap-3">

          <i class="ph-bold ph-warehouse text-lg"></i>

          <span>Inventory</span>

        </span>


        <i
          class="ph-bold ph-caret-down
                 transition-transform
                 group-open:rotate-180">
        </i>

      </summary>


      <div class="ml-8 mt-2 space-y-1">

        <a
          href="inventory.php"
          class="block rounded-md py-1.5 text-sm
                 text-gray-600
                 hover:text-indigo-600">

          Inventory Overview

        </a>


        <a
          href="stock-adjustment.php"
          class="block rounded-md py-1.5 text-sm
                 text-gray-600
                 hover:text-indigo-600">

          Stock Adjustment

        </a>

      </div>

    </details> -->


    <!-- ======================================
         REPORTS
    ======================================= -->
    <!-- <details class="group">

      <summary
        class="flex cursor-pointer
               items-center justify-between
               rounded-lg px-3 py-2.5
               text-gray-700
               hover:bg-gray-100
               hover:text-indigo-600">

        <span class="flex items-center gap-3">

          <i class="ph-bold ph-chart-line-up text-lg"></i>

          <span>Reports</span>

        </span>


        <i
          class="ph-bold ph-caret-down
                 transition-transform
                 group-open:rotate-180">
        </i>

      </summary>


      <div class="ml-8 mt-2 space-y-1">

        <a
          href="sales-report.php"
          class="block rounded-md py-1.5 text-sm
                 text-gray-600
                 hover:text-indigo-600">

          Sales Report

        </a>


        <a
          href="inventory-report.php"
          class="block rounded-md py-1.5 text-sm
                 text-gray-600
                 hover:text-indigo-600">

          Inventory Report

        </a>

      </div>

    </details> -->
    <!-- ======================================
        STOCK OUT
    ======================================= -->
    <details class="group">

      <summary
        class="flex cursor-pointer
              items-center justify-between
              rounded-lg px-3 py-2.5
              text-gray-700
              hover:bg-gray-100
              hover:text-indigo-600">

        <span class="flex items-center gap-3">

          <i class="ph-bold ph-trend-down text-lg"></i>

          <span>        
            <a
            href="out-stock.php"
            class="block rounded-md py-1.5 text-sm
                  text-gray-600
                  hover:text-indigo-600">

            Create Stock Out

          </a>
        </span>
          

        </span>


        <!-- <i
          class="ph-bold ph-caret-down
                transition-transform
                group-open:rotate-180">
        </i> -->

      </summary>


      <div class="ml-8 mt-2 space-y-1">

        <!-- Create Stock Out -->
        <!-- <a
          href="out-stock.php"
          class="block rounded-md py-1.5 text-sm
                text-gray-600
                hover:text-indigo-600">

          Create Stock Out

        </a> -->


        <!-- View Stock Out -->
        <!-- <a
          href="view-all-stock-out.php"
          class="block rounded-md py-1.5 text-sm
                text-gray-600
                hover:text-indigo-600">

          View Stock Out

        </a> -->

      </div>

    </details>


  </nav>

</aside>