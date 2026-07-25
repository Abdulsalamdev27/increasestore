<?php
require_once __DIR__ . "/../includes/header.php";
?>

<section class="w-full">

    <!-- ===================================== -->
    <!-- PAGE HEADER -->
    <!-- ===================================== -->

    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5 mb-8">

        <div>

            <h1 class="text-3xl font-bold text-gray-800">
                Create Order
            </h1>

            <p class="text-gray-500 mt-2">
                Create a customer order using accepted products available in your inventory.
            </p>

        </div>

        <div class="flex flex-wrap gap-3">

            <a
                href="orders.php"
                class="px-5 py-3 rounded-xl border border-gray-300 hover:bg-gray-100 transition">

                View Orders

            </a>

            <button
                type="button"
                id="refreshProducts"
                class="px-5 py-3 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700 transition">

                Refresh Products

            </button>

        </div>

    </div>

    <!-- ===================================== -->
    <!-- RESPONSE BOX -->
    <!-- ===================================== -->

    <div
        id="responseBox"
        class="hidden rounded-xl px-5 py-4 mb-6 text-sm font-medium">
    </div>

    <!-- ===================================== -->
    <!-- CUSTOMER INFORMATION -->
    <!-- ===================================== -->

    <div
        class="bg-white rounded-2xl shadow border border-gray-200 overflow-hidden">

        <div class="border-b px-6 py-5">

            <h2 class="text-xl font-semibold">
                Customer Information
            </h2>

            <p class="text-gray-500 text-sm mt-1">
                Enter the customer's details before adding products.
            </p>

        </div>

        <div class="p-6">

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

                <!-- Customer Name -->

                <div>

                    <label class="block text-sm font-medium mb-2">
                        Customer Name <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="text"
                        id="customer_name"
                        class="input"
                        placeholder="John Doe">

                </div>

                <!-- Phone -->

                <div>

                    <label class="block text-sm font-medium mb-2">
                        Phone Number <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="text"
                        id="customer_phone"
                        class="input"
                        placeholder="08012345678">

                </div>

                <!-- Email -->

                <div>

                    <label class="block text-sm font-medium mb-2">
                        Email Address
                    </label>

                    <input
                        type="email"
                        id="customer_email"
                        class="input"
                        placeholder="customer@email.com">

                </div>

                <!-- Payment Method -->

                <div>

                    <label class="block text-sm font-medium mb-2">
                        Payment Method
                    </label>

                    <select
                        id="payment_method"
                        class="input">

                        <option value="cash">
                            Cash
                        </option>

                        <option value="transfer">
                            Bank Transfer
                        </option>

                        <option value="card">
                            Card
                        </option>

                        <option value="pos">
                            POS
                        </option>

                    </select>

                </div>

                <!-- Payment Status -->

                <div>

                    <label class="block text-sm font-medium mb-2">
                        Payment Status
                    </label>

                    <select
                        id="payment_status"
                        class="input">

                        <option value="paid">
                            Paid
                        </option>

                        <option value="unpaid">
                            Unpaid
                        </option>

                        <option value="partial">
                            Partially Paid
                        </option>

                    </select>

                </div>

                <!-- Order Number -->

                <div>

                    <label class="block text-sm font-medium mb-2">
                        Order Number
                    </label>

                    <input
                        type="text"
                        id="order_number"
                        class="input bg-gray-100"
                        readonly>

                </div>

                <!-- Order Date -->

                <div>

                    <label class="block text-sm font-medium mb-2">
                        Order Date
                    </label>

                    <input
                        type="datetime-local"
                        id="order_date"
                        class="input">

                </div>

                <!-- Sales Person -->

                <div>

                    <label class="block text-sm font-medium mb-2">
                        Sales Person
                    </label>

                    <input
                        type="text"
                        id="cashier_name"
                        class="input bg-gray-100"
                        readonly
                        value="<?php echo htmlspecialchars($_SESSION['admin_name'] ?? 'Administrator'); ?>">

                </div>

                <!-- Notes -->

                <div class="md:col-span-2 xl:col-span-3">

                    <label class="block text-sm font-medium mb-2">
                        Order Notes
                    </label>

                    <textarea
                        id="order_notes"
                        rows="4"
                        class="input resize-none"
                        placeholder="Optional notes for this order..."></textarea>

                </div>

            </div>

        </div>

    </div>

    <!-- ===================================== -->
    <!-- PRODUCT SEARCH -->
    <!-- ===================================== -->

    <div
        class="bg-white rounded-2xl shadow border border-gray-200 overflow-hidden mt-8">

        <div class="border-b px-6 py-5">

            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

                <div>

                    <h2 class="text-xl font-semibold">
                        Accepted Products
                    </h2>

                    <p class="text-sm text-gray-500 mt-1">
                        Search products and add them to the shopping cart.
                    </p>

                </div>

                <div class="flex items-center gap-3">

                    <span
                        id="availableCount"
                        class="px-4 py-2 rounded-full bg-green-100 text-green-700 text-sm font-semibold">

                        0 Available

                    </span>

                    <span
                        id="productCount"
                        class="px-4 py-2 rounded-full bg-indigo-100 text-indigo-700 text-sm font-semibold">

                        0 Products

                    </span>

                </div>

            </div>

        </div>

        <div class="p-6">

            <!-- ================================ -->
            <!-- SEARCH ROW -->
            <!-- ================================ -->

            <div class="grid lg:grid-cols-4 gap-5">

                <!-- Product Search -->

                <div>

                    <label class="block text-sm font-medium mb-2">
                        Search Product
                    </label>

                    <input
                        type="text"
                        id="productSearch"
                        class="input"
                        placeholder="Product name, SKU or barcode">

                </div>

                <!-- Barcode -->

                <div>

                    <label class="block text-sm font-medium mb-2">
                        Barcode Scanner
                    </label>

                    <input
                        type="text"
                        id="barcodeSearch"
                        autocomplete="off"
                        class="input"
                        placeholder="Scan barcode">

                </div>

                <!-- Category -->

                <div>

                    <label class="block text-sm font-medium mb-2">
                        Category
                    </label>

                    <select
                        id="categoryFilter"
                        class="input">

                        <option value="">
                            All Categories
                        </option>

                    </select>

                </div>

                <!-- Stock -->

                <div>

                    <label class="block text-sm font-medium mb-2">
                        Stock
                    </label>

                    <select
                        id="stockFilter"
                        class="input">

                        <option value="">
                            All Products
                        </option>

                        <option value="available">
                            Available Only
                        </option>

                        <option value="low">
                            Low Stock
                        </option>

                        <option value="out">
                            Out Of Stock
                        </option>

                    </select>

                </div>

            </div>

            <!-- ================================ -->
            <!-- SECOND ROW -->
            <!-- ================================ -->

            <div class="grid lg:grid-cols-3 gap-5 mt-5">

                <!-- Sort -->

                <div>

                    <label class="block text-sm font-medium mb-2">
                        Sort Products
                    </label>

                    <select
                        id="sortProducts"
                        class="input">

                        <option value="name">
                            Product Name
                        </option>

                        <option value="price">
                            Selling Price
                        </option>

                        <option value="quantity">
                            Quantity
                        </option>

                        <option value="created">
                            Latest Products
                        </option>

                    </select>

                </div>

                <!-- Refresh -->

                <div class="flex items-end">

                    <button
                        type="button"
                        id="refreshProducts"
                        class="w-full px-5 py-3 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700 transition">

                        Refresh Products

                    </button>

                </div>

                <!-- Clear -->

                <div class="flex items-end">

                    <button
                        type="button"
                        id="clearSearch"
                        class="w-full px-5 py-3 rounded-xl bg-red-500 text-white hover:bg-red-600 transition">

                        Clear Search

                    </button>

                </div>

            </div>

            <!-- ================================ -->
            <!-- PRODUCT PREVIEW -->
            <!-- ================================ -->

            <div
                id="selectedProductCard"
                class="hidden mt-8 rounded-2xl border border-indigo-200 bg-indigo-50 p-6">

                <div class="flex justify-between items-center">

                    <div>

                        <h3
                            id="selectedProductName"
                            class="text-xl font-semibold">

                            Product Name

                        </h3>

                        <p
                            id="selectedProductBarcode"
                            class="text-gray-500 mt-1">

                            Barcode

                        </p>

                    </div>

                    <div class="text-right">

                        <div
                            id="selectedProductPrice"
                            class="text-2xl font-bold text-green-600">

                            ₦0.00

                        </div>

                        <div
                            id="selectedProductQty"
                            class="text-sm text-gray-500 mt-2">

                            Qty : 0

                        </div>

                    </div>

                </div>

            </div>

            <!-- ================================ -->
            <!-- LOADER -->
            <!-- ================================ -->

            <div
                id="productLoader"
                class="hidden py-12 text-center">

                <svg
                    class="animate-spin h-10 w-10 mx-auto text-indigo-600"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24">

                    <circle
                        class="opacity-25"
                        cx="12"
                        cy="12"
                        r="10"
                        stroke="currentColor"
                        stroke-width="4">
                    </circle>

                    <path
                        class="opacity-75"
                        fill="currentColor"
                        d="M4 12a8 8 0 018-8v4l3-3-3-3v4A10 10 0 002 12h2z">
                    </path>

                </svg>

                <p class="text-gray-500 mt-4">

                    Loading accepted products...

                </p>

            </div>

        </div>

    </div>
    <!-- ===================================== -->
    <!-- AVAILABLE PRODUCTS TABLE -->
    <!-- ===================================== -->

    <div
        class="bg-white rounded-2xl shadow border border-gray-200 overflow-hidden mt-8">

        <div
            class="px-6 py-5 border-b flex items-center justify-between">

            <div>

                <h2 class="text-xl font-semibold">

                    Available Products

                </h2>

                <p class="text-sm text-gray-500 mt-1">

                    Products approved for sale in this store.

                </p>

            </div>

        </div>

        <div class="overflow-x-auto">

            <table class="min-w-full">

                <thead class="bg-gray-100">

                    <tr>

                        <th class="px-5 py-4 text-left font-semibold">
                            Product
                        </th>

                        <th class="px-5 py-4 text-left font-semibold">
                            Barcode
                        </th>

                        <th class="px-5 py-4 text-left font-semibold">
                            SKU
                        </th>

                        <th class="px-5 py-4 text-left font-semibold">
                            Category
                        </th>

                        <th class="px-5 py-4 text-center font-semibold">
                            Qty
                        </th>

                        <th class="px-5 py-4 text-right font-semibold">
                            Price
                        </th>

                        <th class="px-5 py-4 text-center font-semibold">
                            Status
                        </th>

                        <th class="px-5 py-4 text-center font-semibold">
                            Action
                        </th>

                    </tr>

                </thead>

                <tbody id="productsTable">

                    <tr>

                        <td
                            colspan="8"
                            class="text-center py-12 text-gray-500">

                            Loading accepted products...

                        </td>

                    </tr>

                </tbody>

            </table>

        </div>

        <!-- Pagination -->

        <div
            class="px-6 py-4 border-t flex items-center justify-between">

            <div
                id="paginationInfo"
                class="text-sm text-gray-500">

                Showing 0 of 0 products

            </div>

            <div
                id="paginationButtons"
                class="flex gap-2">

            </div>

        </div>

    </div>



    <!-- ===================================== -->
    <!-- SHOPPING CART -->
    <!-- ===================================== -->

    <div
        class="bg-white rounded-2xl shadow border border-gray-200 overflow-hidden mt-8">

        <!-- Header -->

        <div
            class="px-6 py-5 border-b flex justify-between items-center">

            <div>

                <h2 class="text-xl font-semibold">

                    Shopping Cart

                </h2>

                <p class="text-sm text-gray-500 mt-1">

                    Products selected for this order.

                </p>

            </div>

            <div class="flex items-center gap-3">

                <span
                    id="cartCount"
                    class="px-3 py-1 rounded-full bg-indigo-100 text-indigo-700 font-semibold">

                    0 Items

                </span>

                <button
                    type="button"
                    id="clearCartBtn"
                    class="px-4 py-2 rounded-lg bg-red-500 hover:bg-red-600 text-white">

                    Clear Cart

                </button>

            </div>

        </div>

        <!-- Cart Table -->

        <div class="overflow-x-auto">

            <table class="min-w-full">

                <thead class="bg-gray-100">

                    <tr>

                        <th class="px-5 py-4 text-left">

                            Product

                        </th>

                        <th class="px-5 py-4 text-left">

                            Barcode

                        </th>

                        <th class="px-5 py-4 text-center">

                            Quantity

                        </th>

                        <th class="px-5 py-4 text-right">

                            Unit Price

                        </th>

                        <th class="px-5 py-4 text-right">

                            Total

                        </th>

                        <th class="px-5 py-4 text-center">

                            Action

                        </th>

                    </tr>

                </thead>

                <tbody id="cartTable">

                    <tr id="emptyCartRow">

                        <td
                            colspan="6"
                            class="text-center py-12 text-gray-500">

                            No products added to cart.

                        </td>

                    </tr>

                </tbody>

            </table>

        </div>

        <!-- Cart Summary -->

        <div
            class="border-t bg-gray-50 px-6 py-6">

            <div class="grid lg:grid-cols-3 gap-5">

                <div
                    class="bg-white rounded-xl border p-5">

                    <p class="text-gray-500 text-sm">

                        Total Items

                    </p>

                    <h2
                        id="totalItems"
                        class="text-3xl font-bold mt-2">

                        0

                    </h2>

                </div>

                <div
                    class="bg-white rounded-xl border p-5">

                    <p class="text-gray-500 text-sm">

                        Total Quantity

                    </p>

                    <h2
                        id="totalQuantity"
                        class="text-3xl font-bold mt-2">

                        0

                    </h2>

                </div>

                <div
                    class="bg-white rounded-xl border p-5">

                    <p class="text-gray-500 text-sm">

                        Cart Total

                    </p>

                    <h2
                        id="cartTotal"
                        class="text-3xl font-bold text-green-600 mt-2">

                        ₦0.00

                    </h2>

                </div>

            </div>

        </div>

    </div>



    <!-- ===================================== -->
    <!-- CART ROW TEMPLATE -->
    <!-- Hidden -->
    <!-- ===================================== -->

    <table class="hidden">

        <tbody>

            <tr id="cartRowTemplate">

                <td class="px-5 py-4">

                    <div>

                        <div
                            class="product-name font-semibold">

                        </div>

                        <div
                            class="sku text-xs text-gray-500 mt-1">

                        </div>

                    </div>

                </td>

                <td
                    class="barcode px-5 py-4">

                </td>

                <td class="px-5 py-4">

                    <div class="flex justify-center items-center gap-2">

                        <button
                            class="decreaseQty w-8 h-8 rounded-lg bg-gray-200 hover:bg-gray-300">

                            −

                        </button>

                        <input
                            type="number"
                            min="1"
                            class="cartQty input w-20 text-center">

                        <button
                            class="increaseQty w-8 h-8 rounded-lg bg-gray-200 hover:bg-gray-300">

                            +

                        </button>

                    </div>

                </td>

                <td
                    class="product-price px-5 py-4 text-right">

                    ₦0.00

                </td>

                <td
                    class="product-total px-5 py-4 text-right font-semibold">

                    ₦0.00

                </td>

                <td
                    class="px-5 py-4 text-center">

                    <button
                        class="removeCartItem px-3 py-2 rounded-lg bg-red-500 hover:bg-red-600 text-white">

                        Remove

                    </button>

                </td>

            </tr>

        </tbody>

    </table>



    <!-- ===================================== -->
    <!-- CHECKOUT -->
    <!-- ===================================== -->

    <div
        class="bg-white rounded-2xl shadow border border-gray-200 overflow-hidden mt-8 mb-10">

        <div
            class="px-6 py-5 border-b">

            <h2 class="text-xl font-semibold">

                Checkout

            </h2>

            <p class="text-sm text-gray-500 mt-1">

                Review the order before completing checkout.

            </p>

        </div>

        <div class="p-6">

            <div class="grid lg:grid-cols-2 gap-8">

                <!-- ======================== -->
                <!-- PAYMENT INFORMATION -->
                <!-- ======================== -->

                <div>

                    <h3
                        class="font-semibold text-lg mb-5">

                        Payment Information

                    </h3>

                    <div class="space-y-5">

                        <!-- Discount -->

                        <div>

                            <label class="label">

                                Discount (₦)

                            </label>

                            <input
                                type="number"
                                id="discount"
                                class="input"
                                min="0"
                                value="0"
                                step="0.01">

                        </div>

                        <!-- Tax -->

                        <div>

                            <label class="label">

                                Tax (₦)

                            </label>

                            <input
                                type="number"
                                id="tax"
                                class="input"
                                min="0"
                                value="0"
                                step="0.01">

                        </div>

                        <!-- Shipping -->

                        <div>

                            <label class="label">

                                Shipping (₦)

                            </label>

                            <input
                                type="number"
                                id="shipping"
                                class="input"
                                min="0"
                                value="0"
                                step="0.01">

                        </div>

                        <!-- Amount Paid -->

                        <div>

                            <label class="label">

                                Amount Paid

                            </label>

                            <input
                                type="number"
                                id="amount_paid"
                                class="input"
                                min="0"
                                value="0"
                                step="0.01">

                        </div>

                        <!-- Balance -->

                        <div>

                            <label class="label">

                                Balance

                            </label>

                            <input
                                type="text"
                                id="balance"
                                class="input bg-gray-100"
                                readonly
                                value="₦0.00">

                        </div>

                        <!-- Notes -->

                        <div>

                            <label class="label">

                                Order Notes

                            </label>

                            <textarea
                                id="order_notes"
                                rows="4"
                                class="input resize-none"
                                placeholder="Optional notes..."></textarea>

                        </div>

                    </div>

                </div>

                <!-- ======================== -->
                <!-- ORDER SUMMARY -->
                <!-- ======================== -->

                <div>

                    <div
                        class="rounded-2xl border bg-gray-50 p-6">

                        <h3
                            class="text-lg font-semibold mb-6">

                            Order Summary

                        </h3>

                        <div class="space-y-4">

                            <div class="flex justify-between">

                                <span>

                                    Total Items

                                </span>

                                <strong id="summaryItems">

                                    0

                                </strong>

                            </div>

                            <div class="flex justify-between">

                                <span>

                                    Total Quantity

                                </span>

                                <strong id="summaryQty">

                                    0

                                </strong>

                            </div>

                            <div class="flex justify-between">

                                <span>

                                    Subtotal

                                </span>

                                <strong id="subtotalAmount">

                                    ₦0.00

                                </strong>

                            </div>

                            <div class="flex justify-between">

                                <span>

                                    Grand Total

                                </span>

                                <strong
                                    id="grandTotal"
                                    class="text-green-600">

                                    ₦0.00

                                </strong>

                            </div>

                            <div class="flex justify-between">

                                <span>

                                    Amount Paid

                                </span>

                                <strong id="summaryPaid">

                                    ₦0.00

                                </strong>

                            </div>

                            <div
                                class="flex justify-between border-t pt-4">

                                <span class="font-bold">

                                    Balance

                                </span>

                                <strong
                                    id="summaryBalance"
                                    class="text-red-600">

                                    ₦0.00

                                </strong>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            <!-- ======================== -->
            <!-- ACTION BUTTONS -->
            <!-- ======================== -->

            <div
                class="flex flex-wrap justify-end gap-4 mt-10">

                <button
                    type="button"
                    id="cancelOrderBtn"
                    class="px-6 py-3 rounded-xl border border-gray-300 hover:bg-gray-100">

                    Cancel

                </button>

                <button
                    type="button"
                    id="saveDraftBtn"
                    class="px-6 py-3 rounded-xl bg-yellow-500 hover:bg-yellow-600 text-white">

                    Save Draft

                </button>

                <button
                    type="button"
                    id="checkoutBtn"
                    class="px-8 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold">

                    Complete Order

                </button>

            </div>

        </div>

    </div>



    <!-- ===================================== -->
    <!-- PROCESSING MODAL -->
    <!-- ===================================== -->

    <div
        id="checkoutLoader"
        class="hidden fixed inset-0 z-50 bg-black/40 flex items-center justify-center">

        <div
            class="bg-white rounded-2xl shadow-xl p-8 w-96 text-center">

            <svg
                class="animate-spin h-10 w-10 mx-auto text-indigo-600"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24">

                <circle
                    class="opacity-25"
                    cx="12"
                    cy="12"
                    r="10"
                    stroke="currentColor"
                    stroke-width="4">
                </circle>

                <path
                    class="opacity-75"
                    fill="currentColor"
                    d="M4 12a8 8 0 018-8v4l3-3-3-3v4A10 10 0 002 12h2z">
                </path>

            </svg>

            <h3
                class="font-semibold text-lg mt-5">

                Processing Order...

            </h3>

            <p
                class="text-gray-500 text-sm mt-2">

                Please wait while your order is being created.

            </p>

        </div>

    </div>

</section>

<?php
require_once __DIR__ . "/../includes/footer.php";
?>


<script>

/*====================================================
=            PART 1 - CONFIGURATION                  =
====================================================*/

const API_BASE_URL = "<?php echo API_BASE_URL; ?>";

const API_BASE = API_BASE_URL;

const PRODUCTS_API =
    API_BASE + "/dashboard/accepted-products/list.php";

const PRODUCT_VIEW_API =
    API_BASE + "/dashboard/accepted-products/view.php";

const CREATE_ORDER_API =
    API_BASE + "/dashboard/orders/create.php";

/*====================================================
=            JWT AUTHENTICATION                      =
====================================================*/

const token = localStorage.getItem("token");

/*
|--------------------------------------------------------------------------
| Check Login
|--------------------------------------------------------------------------
*/

if (!token) {

    alert("Your session has expired.");

    window.location.href = "../login.php";

}

/*====================================================
=            GLOBAL AJAX SETUP                       =
====================================================*/

$.ajaxSetup({

    cache: false,

    dataType: "json",

    headers: {

        Authorization: "Bearer " + token

    },

    beforeSend: function (xhr) {

        xhr.setRequestHeader(
            "Authorization",
            "Bearer " + token
        );

    },

    complete: function (xhr) {

        /*
        |--------------------------------------------------------------------------
        | Session Expired
        |--------------------------------------------------------------------------
        */

        if (xhr.status === 401) {

            localStorage.removeItem("token");

            showMessage(
                "error",
                "Your session has expired. Please login again."
            );

            setTimeout(function () {

                window.location.href = "../login.php";

            }, 1200);

        }

    }

});

/*====================================================
=            GLOBAL VARIABLES                        =
====================================================*/

let allProducts = [];

let filteredProducts = [];

let cart = [];

let currentPage = 1;

const rowsPerPage = 10;

let totalPages = 1;

let lastOrderId = null;

let selectedProduct = null;

/*====================================================
=            DOM REFERENCES                          =
====================================================*/

/*
|--------------------------------------------------------------------------
| Product Elements
|--------------------------------------------------------------------------
*/

const productsTable = $("#productsTable");

const productSearch = $("#productSearch");

const barcodeSearch = $("#barcodeSearch");

const categoryFilter = $("#categoryFilter");

const stockFilter = $("#stockFilter");

const sortProducts = $("#sortProducts");

const availableCount = $("#availableCount");

const productCount = $("#productCount");

const loader = $("#productLoader");

const selectedProductCard = $("#selectedProductCard");

const selectedProductName = $("#selectedProductName");

const selectedProductBarcode = $("#selectedProductBarcode");

const selectedProductPrice = $("#selectedProductPrice");

const selectedProductQty = $("#selectedProductQty");

/*
|--------------------------------------------------------------------------
| Cart Elements
|--------------------------------------------------------------------------
*/

const cartTable = $("#cartTable");

const cartCount = $("#cartCount");

const totalItems = $("#totalItems");

const totalQuantity = $("#totalQuantity");

const cartTotal = $("#cartTotal");

/*
|--------------------------------------------------------------------------
| Summary
|--------------------------------------------------------------------------
*/

const summaryItems = $("#summaryItems");

const summaryQty = $("#summaryQty");

const summaryTotal = $("#summaryTotal");

const summaryPaid = $("#summaryPaid");

const summaryBalance = $("#summaryBalance");

/*
|--------------------------------------------------------------------------
| Payment Fields
|--------------------------------------------------------------------------
*/

const amountPaid = $("#amount_paid");

const balance = $("#balance");

/*
|--------------------------------------------------------------------------
| Customer Fields
|--------------------------------------------------------------------------
*/

const customerName = $("#customer_name");

const customerPhone = $("#customer_phone");

const customerEmail = $("#customer_email");

const paymentMethod = $("#payment_method");

const paymentStatus = $("#payment_status");

const orderNotes = $("#order_notes");

/*
|--------------------------------------------------------------------------
| Checkout
|--------------------------------------------------------------------------
*/

const checkoutBtn = $("#checkoutBtn");

const checkoutLoader = $("#checkoutLoader");

const responseBox = $("#responseBox");

/*====================================================
=            HELPER FUNCTIONS                        =
====================================================*/

/*
|--------------------------------------------------------------------------
| Format Money
|--------------------------------------------------------------------------
*/

function formatMoney(value) {

    value = Number(value);

    if (isNaN(value)) {

        value = 0;

    }

    return value.toLocaleString("en-NG", {

        minimumFractionDigits: 2,

        maximumFractionDigits: 2

    });

}

/*
|--------------------------------------------------------------------------
| Escape HTML
|--------------------------------------------------------------------------
*/

function escapeHtml(text) {

    if (text === null || text === undefined) {

        return "";

    }

    return $("<div>").text(text).html();

}

/*
|--------------------------------------------------------------------------
| Response Message
|--------------------------------------------------------------------------
*/

function showMessage(type, message) {

    responseBox
        .removeClass(
            "hidden bg-red-100 bg-green-100 text-red-700 text-green-700"
        );

    if (type === "success") {

        responseBox.addClass(
            "bg-green-100 text-green-700"
        );

    } else {

        responseBox.addClass(
            "bg-red-100 text-red-700"
        );

    }

    responseBox.text(message);

    $("html, body").animate({

        scrollTop: 0

    }, 250);

    setTimeout(function () {

        responseBox.addClass("hidden");

    }, 4000);

}

/*
|--------------------------------------------------------------------------
| Product Loader
|--------------------------------------------------------------------------
*/

function showProductLoader() {

    loader.removeClass("hidden");

}

function hideProductLoader() {

    loader.addClass("hidden");

}

/*
|--------------------------------------------------------------------------
| Checkout Loader
|--------------------------------------------------------------------------
*/

function showCheckoutLoader() {

    checkoutLoader
        .removeClass("hidden")
        .addClass("flex");

    checkoutBtn
        .prop("disabled", true)
        .text("Processing...");

}

function hideCheckoutLoader() {

    checkoutLoader
        .removeClass("flex")
        .addClass("hidden");

    checkoutBtn
        .prop("disabled", false)
        .text("Complete Order");

}

/*====================================================
=            PAGE INITIALIZATION                     =
====================================================*/

$(document).ready(function () {

    showProductLoader();

    generateOrderNumber();

    loadDraft();

    loadProducts();

    calculateTotals();

});

/*====================================================
= PART 2A - LOAD PRODUCTS
====================================================*/

function loadProducts() {

    showProductLoader();

    $.ajax({

        url: PRODUCTS_API,

        type: "GET",

        dataType: "json",

        headers: {
            Authorization: "Bearer " + token
        },

        data: {

            page: currentPage,

            limit: rowsPerPage,

            search: productSearch.val().trim(),

            category: categoryFilter.val(),

            stock: stockFilter.val(),

            sort: sortProducts.val()

        },

        success: function (response) {

            hideProductLoader();

            if (!response.status) {

                productsTable.html(`
                    <tr>
                        <td colspan="8"
                            class="text-center py-10 text-red-500">
                            ${response.message || "No products found."}
                        </td>
                    </tr>
                `);

                availableCount.text("0 Available");
                productCount.text("0 Products");

                return;

            }

            processProducts(response);

        },

        error: function (xhr) {

            hideProductLoader();

            if (xhr.status === 401) {

                showMessage(
                    "error",
                    "Your session has expired. Please login again."
                );

                localStorage.removeItem("token");

                setTimeout(function () {

                    window.location.href = "../login.php";

                }, 1200);

                return;

            }

            console.error(xhr.responseText);

            showMessage(
                "error",
                "Unable to load products."
            );

        }

    });

}


/*====================================================
= PART 2B - SEARCH, FILTERS & SORTING
====================================================*/

/*
|--------------------------------------------------------------------------
| Product Search
|--------------------------------------------------------------------------
*/

productSearch.on("keyup", function () {

    currentPage = 1;

    loadProducts();

});

/*
|--------------------------------------------------------------------------
| Barcode Search
|--------------------------------------------------------------------------
*/

barcodeSearch.on("keypress", function (e) {

    if (e.which !== 13) return;

    e.preventDefault();

    const barcode = $(this).val().trim();

    if (barcode === "") return;

    const product = allProducts.find(function (item) {

        return item.barcode === barcode;

    });

    if (!product) {

        showMessage(
            "error",
            "Product not found."
        );

        return;

    }

    if (Number(product.quantity) <= 0) {

        showMessage(
            "error",
            "Product is out of stock."
        );

        return;

    }

    addToCart(product.id);

    $(this).val("");

});

/*
|--------------------------------------------------------------------------
| Category Filter
|--------------------------------------------------------------------------
*/

categoryFilter.on("change", function () {

    currentPage = 1;

    loadProducts();

});

/*
|--------------------------------------------------------------------------
| Stock Filter
|--------------------------------------------------------------------------
*/

stockFilter.on("change", function () {

    currentPage = 1;

    loadProducts();

});

/*
|--------------------------------------------------------------------------
| Sort Products
|--------------------------------------------------------------------------
*/

sortProducts.on("change", function () {

    currentPage = 1;

    loadProducts();

});

/*
|--------------------------------------------------------------------------
| Refresh Products
|--------------------------------------------------------------------------
*/

$("#refreshProducts").on("click", function () {

    loadProducts();

});

/*
|--------------------------------------------------------------------------
| Clear Search
|--------------------------------------------------------------------------
*/

$("#clearSearch").on("click", function () {

    productSearch.val("");

    barcodeSearch.val("");

    categoryFilter.val("");

    stockFilter.val("");

    sortProducts.val("name");

    currentPage = 1;

    loadProducts();

});

/*
|--------------------------------------------------------------------------
| Barcode Preview Card
|--------------------------------------------------------------------------
*/

barcodeSearch.on("input", function () {

    const code = $(this).val().trim();

    if (code.length < 3) {

        $("#selectedProductCard").addClass("hidden");

        return;

    }

    const product = allProducts.find(function (item) {

        return item.barcode === code;

    });

    if (!product) {

        $("#selectedProductCard").addClass("hidden");

        return;

    }

    $("#selectedProductCard").removeClass("hidden");

    $("#selectedProductName").text(product.product_name);

    $("#selectedProductBarcode").text(product.barcode);

    $("#selectedProductPrice").text(
        "₦" + formatMoney(product.selling_price)
    );

    $("#selectedProductQty").text(
        "Qty : " + product.quantity
    );

});

/*
|--------------------------------------------------------------------------
| Hide Product Card
|--------------------------------------------------------------------------
*/

productSearch.on("keyup", function () {

    if ($(this).val().trim() === "") {

        $("#selectedProductCard").addClass("hidden");

    }

});

barcodeSearch.on("keyup", function () {

    if ($(this).val().trim() === "") {

        $("#selectedProductCard").addClass("hidden");

    }

});

/*
|--------------------------------------------------------------------------
| Populate Category Dropdown
|--------------------------------------------------------------------------
*/

function populateCategories() {

    const categories = [];

    allProducts.forEach(function (product) {

        if (
            product.category &&
            !categories.includes(product.category)
        ) {

            categories.push(product.category);

        }

    });

    categories.sort();

    categoryFilter.html(
        `<option value="">All Categories</option>`
    );

    categories.forEach(function (category) {

        categoryFilter.append(`
            <option value="${category}">
                ${category}
            </option>
        `);

    });

}

/*====================================================
= PART 2C - RENDER PRODUCTS TABLE & PAGINATION
====================================================*/

/*
|--------------------------------------------------------------------------
| Process API Response
|--------------------------------------------------------------------------
*/

function processProducts(response) {

    allProducts = response.data || [];

    filteredProducts = [...allProducts];

    totalPages = Number(response.pagination?.total_pages || 1);

    currentPage = Number(response.pagination?.current_page || 1);

    populateCategories();

    renderProducts();

    renderPagination();

}

/*
|--------------------------------------------------------------------------
| Render Products Table
|--------------------------------------------------------------------------
*/

function renderProducts() {

    productsTable.empty();

    if (filteredProducts.length === 0) {

        productsTable.html(`
            <tr>
                <td colspan="8"
                    class="py-12 text-center text-gray-500">
                    No available products found.
                </td>
            </tr>
        `);

        availableCount.text("0 Available");

        productCount.text("0 Products");

        return;

    }

    availableCount.text(filteredProducts.length + " Available");

    productCount.text(filteredProducts.length + " Products");

    filteredProducts.forEach(function (product) {

        let badge = "";

        if (Number(product.quantity) <= 0) {

            badge = `
                <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs">
                    Out of Stock
                </span>
            `;

        } else if (
            Number(product.quantity) <= Number(product.minimum_stock)
        ) {

            badge = `
                <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs">
                    Low Stock
                </span>
            `;

        } else {

            badge = `
                <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs">
                    Available
                </span>
            `;

        }

        productsTable.append(`

            <tr class="hover:bg-gray-50">

                <td class="px-6 py-4">

                    <div>

                        <div class="font-semibold">

                            ${escapeHtml(product.product_name)}

                        </div>

                        <div class="text-xs text-gray-500">

                            ${escapeHtml(product.description || "")}

                        </div>

                    </div>

                </td>

                <td class="px-6 py-4">

                    ${escapeHtml(product.barcode)}

                </td>

                <td class="px-6 py-4">

                    ${escapeHtml(product.sku || "-")}

                </td>

                <td class="px-6 py-4">

                    ${escapeHtml(product.category || "-")}

                </td>

                <td class="px-6 py-4 text-center">

                    ${product.quantity}

                </td>

                <td class="px-6 py-4 text-right">

                    ₦${formatMoney(product.selling_price)}

                </td>

                <td class="px-6 py-4 text-center">

                    ${badge}

                </td>

                <td class="px-6 py-4 text-center">

                    <button
                        class="bg-indigo-600
                               hover:bg-indigo-700
                               text-white
                               px-4 py-2
                               rounded-lg addToCartBtn"
                        data-id="${product.id}">

                        Add to Cart

                    </button>

                </td>

            </tr>

        `);

    });

}

/*
|--------------------------------------------------------------------------
| Add To Cart Click
|--------------------------------------------------------------------------
*/

$(document).on("click", ".addToCartBtn", function () {

    addToCart($(this).data("id"));

});

/*
|--------------------------------------------------------------------------
| Pagination
|--------------------------------------------------------------------------
*/

function renderPagination() {

    $("#pagination").remove();

    if (totalPages <= 1) {

        return;

    }

    let html = `

        <div
            id="pagination"
            class="flex justify-center items-center gap-2 py-6">

    `;

    html += `

        <button
            class="pageBtn px-3 py-2 border rounded-lg"
            data-page="${currentPage - 1}"
            ${currentPage === 1 ? "disabled" : ""}>

            Previous

        </button>

    `;

    for (let i = 1; i <= totalPages; i++) {

        html += `

            <button
                class="pageBtn
                       px-3 py-2
                       rounded-lg
                       ${
                           i === currentPage
                               ? "bg-indigo-600 text-white"
                               : "border"
                       }"
                data-page="${i}">

                ${i}

            </button>

        `;

    }

    html += `

        <button
            class="pageBtn px-3 py-2 border rounded-lg"
            data-page="${currentPage + 1}"
            ${currentPage === totalPages ? "disabled" : ""}>

            Next

        </button>

    `;

    html += `</div>`;

    $(".overflow-x-auto:last").after(html);

}

/*
|--------------------------------------------------------------------------
| Pagination Click
|--------------------------------------------------------------------------
*/

$(document).on("click", ".pageBtn", function () {

    const page = Number($(this).data("page"));

    if (
        page < 1 ||
        page > totalPages
    ) {
        return;
    }

    currentPage = page;

    loadProducts();

});

/*====================================================
= PART 3A - SHOPPING CART LOGIC
====================================================*/

/*
|--------------------------------------------------------------------------
| Add Product To Cart
|--------------------------------------------------------------------------
*/

function addToCart(productId) {

    const product = allProducts.find(function (item) {

        return Number(item.id) === Number(productId);

    });

    if (!product) {

        showMessage(
            "error",
            "Product not found."
        );

        return;

    }

    if (Number(product.quantity) <= 0) {

        showMessage(
            "error",
            "Product is out of stock."
        );

        return;

    }

    const existing = cart.find(function (item) {

        return Number(item.id) === Number(productId);

    });

    if (existing) {

        if (existing.quantity >= Number(product.quantity)) {

            showMessage(
                "error",
                "Maximum available stock reached."
            );

            return;

        }

        existing.quantity++;

        existing.total =
            existing.quantity *
            existing.selling_price;

    } else {

        cart.push({

            id: Number(product.id),

            product_name: product.product_name,

            barcode: product.barcode,

            sku: product.sku,

            category: product.category,

            selling_price: Number(product.selling_price),

            available_quantity: Number(product.quantity),

            quantity: 1,

            total: Number(product.selling_price)

        });

    }

    renderCart();

    calculateTotals();

    saveDraft();

    showMessage(
        "success",
        product.product_name + " added to cart."
    );

}

/*
|--------------------------------------------------------------------------
| Increase Quantity
|--------------------------------------------------------------------------
*/

$(document).on("click", ".increaseQty", function () {

    const id = Number($(this).data("id"));

    const item = cart.find(function (cartItem) {

        return cartItem.id === id;

    });

    if (!item) return;

    if (item.quantity >= item.available_quantity) {

        showMessage(
            "error",
            "Maximum stock reached."
        );

        return;

    }

    item.quantity++;

    item.total =
        item.quantity *
        item.selling_price;

    renderCart();

    calculateTotals();

    saveDraft();

});

/*
|--------------------------------------------------------------------------
| Decrease Quantity
|--------------------------------------------------------------------------
*/

$(document).on("click", ".decreaseQty", function () {

    const id = Number($(this).data("id"));

    const item = cart.find(function (cartItem) {

        return cartItem.id === id;

    });

    if (!item) return;

    item.quantity--;

    if (item.quantity <= 0) {

        cart = cart.filter(function (cartItem) {

            return cartItem.id !== id;

        });

    } else {

        item.total =
            item.quantity *
            item.selling_price;

    }

    renderCart();

    calculateTotals();

    saveDraft();

});

/*
|--------------------------------------------------------------------------
| Quantity Changed Manually
|--------------------------------------------------------------------------
*/

$(document).on("change", ".cartQty", function () {

    const id = Number($(this).data("id"));

    let qty = parseInt($(this).val());

    const item = cart.find(function (cartItem) {

        return cartItem.id === id;

    });

    if (!item) return;

    if (isNaN(qty) || qty < 1) {

        qty = 1;

    }

    if (qty > item.available_quantity) {

        qty = item.available_quantity;

        showMessage(
            "error",
            "Maximum stock reached."
        );

    }

    item.quantity = qty;

    item.total =
        item.quantity *
        item.selling_price;

    renderCart();

    calculateTotals();

    saveDraft();

});

/*
|--------------------------------------------------------------------------
| Remove Cart Item
|--------------------------------------------------------------------------
*/

$(document).on("click", ".removeCartItem", function () {

    const id = Number($(this).data("id"));

    cart = cart.filter(function (item) {

        return item.id !== id;

    });

    renderCart();

    calculateTotals();

    saveDraft();

    showMessage(
        "success",
        "Product removed from cart."
    );

});

/*
|--------------------------------------------------------------------------
| Clear Cart
|--------------------------------------------------------------------------
*/

$("#clearCartBtn").on("click", function () {

    if (cart.length === 0) {

        return;

    }

    if (!confirm("Clear all items from cart?")) {

        return;

    }

    cart = [];

    renderCart();

    calculateTotals();

    saveDraft();

    showMessage(
        "success",
        "Cart cleared successfully."
    );

});

/*
|--------------------------------------------------------------------------
| Check Cart Empty
|--------------------------------------------------------------------------
*/

function cartIsEmpty() {

    return cart.length === 0;

}

/*
|--------------------------------------------------------------------------
| Get Cart JSON
|--------------------------------------------------------------------------
*/

function getCartData() {

    return cart.map(function (item) {

        return {

            product_id: item.id,

            quantity: item.quantity,

            selling_price: item.selling_price,

            total: item.total

        };

    });

}


/*====================================================
= PART 3B - RENDER SHOPPING CART
====================================================*/

/*
|--------------------------------------------------------------------------
| Render Cart
|--------------------------------------------------------------------------
*/

function renderCart() {

    cartTable.empty();

    /*
    |--------------------------------------------------------------------------
    | Empty Cart
    |--------------------------------------------------------------------------
    */

    if (cart.length === 0) {

        cartTable.html(`

            <tr id="emptyCartRow">

                <td
                    colspan="6"
                    class="text-center py-12 text-gray-500">

                    No product has been added to the cart.

                </td>

            </tr>

        `);

        cartCount.text("0 Items");

        totalItems.text("0");

        totalQuantity.text("0");

        cartTotal.text("₦0.00");

        summaryItems.text("0");

        summaryQty.text("0");

        summaryTotal.text("₦0.00");

        summaryPaid.text(
            "₦" +
            formatMoney(amountPaid.val() || 0)
        );

        summaryBalance.text("₦0.00");

        return;

    }

    /*
    |--------------------------------------------------------------------------
    | Cart Totals
    |--------------------------------------------------------------------------
    */

    let items = 0;

    let qty = 0;

    let total = 0;

    /*
    |--------------------------------------------------------------------------
    | Build Cart Rows
    |--------------------------------------------------------------------------
    */

    cart.forEach(function (item) {

        items++;

        qty += Number(item.quantity);

        total += Number(item.total);

        cartTable.append(`

            <tr
                class="border-b hover:bg-gray-50"
                data-id="${item.id}">

                <td class="px-6 py-4">

                    <div>

                        <div class="font-semibold">

                            ${escapeHtml(item.product_name)}

                        </div>

                        <div
                            class="text-xs text-gray-500">

                            ${escapeHtml(item.sku || "-")}

                        </div>

                    </div>

                </td>

                <td class="px-6 py-4">

                    ${escapeHtml(item.barcode)}

                </td>

                <td class="px-6 py-4">

                    <div
                        class="flex items-center justify-center gap-2">

                        <button
                            class="decreaseQty
                                   w-8 h-8
                                   rounded-lg
                                   bg-gray-100
                                   hover:bg-gray-200"
                            data-id="${item.id}">

                            -

                        </button>

                        <input
                            type="number"
                            class="cartQty input w-20 text-center"
                            min="1"
                            max="${item.available_quantity}"
                            value="${item.quantity}"
                            data-id="${item.id}">

                        <button
                            class="increaseQty
                                   w-8 h-8
                                   rounded-lg
                                   bg-gray-100
                                   hover:bg-gray-200"
                            data-id="${item.id}">

                            +

                        </button>

                    </div>

                </td>

                <td
                    class="px-6 py-4 text-right">

                    ₦${formatMoney(item.selling_price)}

                </td>

                <td
                    class="px-6 py-4 text-right font-semibold">

                    ₦${formatMoney(item.total)}

                </td>

                <td
                    class="px-6 py-4 text-center">

                    <button
                        class="removeCartItem
                               px-3 py-2
                               rounded-lg
                               bg-red-500
                               hover:bg-red-600
                               text-white"
                        data-id="${item.id}">

                        Remove

                    </button>

                </td>

            </tr>

        `);

    });

    /*
    |--------------------------------------------------------------------------
    | Update Summary
    |--------------------------------------------------------------------------
    */

    cartCount.text(items + " Item(s)");

    totalItems.text(items);

    totalQuantity.text(qty);

    cartTotal.text(
        "₦" + formatMoney(total)
    );

    summaryItems.text(items);

    summaryQty.text(qty);

    summaryTotal.text(
        "₦" + formatMoney(total)
    );

    summaryPaid.text(
        "₦" +
        formatMoney(amountPaid.val() || 0)
    );

    const paid =
        parseFloat(amountPaid.val()) || 0;

    const balanceValue =
        paid - total;

    summaryBalance.text(
        "₦" + formatMoney(balanceValue)
    );

    balance.val(
        "₦" + formatMoney(balanceValue)
    );

}

/*
|--------------------------------------------------------------------------
| Auto Re-render Paid Amount
|--------------------------------------------------------------------------
*/

amountPaid.on("keyup change", function () {

    renderCart();

});


/*====================================================
= PART 4A - ORDER TOTALS ENGINE
====================================================*/

/*
|--------------------------------------------------------------------------
| Calculate Order Totals
|--------------------------------------------------------------------------
*/

function calculateTotals() {

    let subtotal = 0;

    let totalItemsCount = 0;

    let totalQuantityCount = 0;

    /*
    |--------------------------------------------------------------------------
    | Calculate Cart Totals
    |--------------------------------------------------------------------------
    */

    cart.forEach(function (item) {

        subtotal += Number(item.total);

        totalItemsCount++;

        totalQuantityCount += Number(item.quantity);

    });

    /*
    |--------------------------------------------------------------------------
    | Additional Charges
    |--------------------------------------------------------------------------
    */

    const discount =
        parseFloat($("#discount").val()) || 0;

    const tax =
        parseFloat($("#tax").val()) || 0;

    const shipping =
        parseFloat($("#shipping").val()) || 0;

    /*
    |--------------------------------------------------------------------------
    | Grand Total
    |--------------------------------------------------------------------------
    */

    let grandTotal =
        subtotal -
        discount +
        tax +
        shipping;

    if (grandTotal < 0) {

        grandTotal = 0;

    }

    /*
    |--------------------------------------------------------------------------
    | Payment
    |--------------------------------------------------------------------------
    */

    const paid =
        parseFloat($("#amount_paid").val()) || 0;

    const balanceAmount =
        paid - grandTotal;

    /*
    |--------------------------------------------------------------------------
    | Cart Summary
    |--------------------------------------------------------------------------
    */

    cartCount.text(totalItemsCount + " Item(s)");

    totalItems.text(totalItemsCount);

    totalQuantity.text(totalQuantityCount);

    cartTotal.text(
        "₦" + formatMoney(subtotal)
    );

    /*
    |--------------------------------------------------------------------------
    | Checkout Summary
    |--------------------------------------------------------------------------
    */

    summaryItems.text(totalItemsCount);

    summaryQty.text(totalQuantityCount);

    summaryTotal.text(
        "₦" + formatMoney(grandTotal)
    );

    summaryPaid.text(
        "₦" + formatMoney(paid)
    );

    summaryBalance.text(
        "₦" + formatMoney(balanceAmount)
    );

    /*
    |--------------------------------------------------------------------------
    | Hidden / Readonly Fields
    |--------------------------------------------------------------------------
    */

    $("#subtotalAmount").text(
        "₦" + formatMoney(subtotal)
    );

    $("#grandTotal").text(
        "₦" + formatMoney(grandTotal)
    );

    balance.val(
        "₦" + formatMoney(balanceAmount)
    );

}

/*====================================================
= PART 4B - CHECKOUT VALIDATION
====================================================*/

/*
|--------------------------------------------------------------------------
| Checkout Button
|--------------------------------------------------------------------------
*/

checkoutBtn.on("click", function () {

    if (!validateOrder()) {

        return;

    }

    const orderData = collectOrderData();

    submitOrder(orderData);

});

/*
|--------------------------------------------------------------------------
| Validate Order
|--------------------------------------------------------------------------
*/

function validateOrder() {

    /*
    |--------------------------------------------------------------------------
    | Customer Information
    |--------------------------------------------------------------------------
    */

    const customerName =
        $("#customer_name").val().trim();

    const customerPhone =
        $("#customer_phone").val().trim();

    const paymentMethod =
        $("#payment_method").val();

    if (customerName === "") {

        showMessage(
            "error",
            "Customer name is required."
        );

        $("#customer_name").focus();

        return false;

    }

    if (customerPhone === "") {

        showMessage(
            "error",
            "Customer phone number is required."
        );

        $("#customer_phone").focus();

        return false;

    }

    if (paymentMethod === "") {

        showMessage(
            "error",
            "Please select a payment method."
        );

        $("#payment_method").focus();

        return false;

    }

    /*
    |--------------------------------------------------------------------------
    | Cart Validation
    |--------------------------------------------------------------------------
    */

    if (cart.length === 0) {

        showMessage(
            "error",
            "Your shopping cart is empty."
        );

        return false;

    }

    /*
    |--------------------------------------------------------------------------
    | Product Quantity Validation
    |--------------------------------------------------------------------------
    */

    for (const item of cart) {

        if (item.quantity <= 0) {

            showMessage(
                "error",
                item.product_name +
                " has an invalid quantity."
            );

            return false;

        }

        if (item.quantity > item.available_quantity) {

            showMessage(
                "error",
                item.product_name +
                " only has " +
                item.available_quantity +
                " item(s) available."
            );

            return false;

        }

    }

    /*
    |--------------------------------------------------------------------------
    | Payment Validation
    |--------------------------------------------------------------------------
    */

    const grandTotal =
        parseFloat(
            $("#grandTotal")
                .text()
                .replace(/[₦,]/g, "")
        ) || 0;

    const amountPaid =
        parseFloat($("#amount_paid").val()) || 0;

    if (amountPaid < 0) {

        showMessage(
            "error",
            "Invalid payment amount."
        );

        return false;

    }

    if (
        amountPaid < grandTotal &&
        !confirm(
            "Customer has not paid the full amount.\n\nContinue with this order?"
        )
    ) {

        return false;

    }

    return true;

}

/*
|--------------------------------------------------------------------------
| Collect Order Data
|--------------------------------------------------------------------------
*/

function collectOrderData() {

    const subtotal =
        cart.reduce(function (sum, item) {

            return sum + Number(item.total);

        }, 0);

    const discount =
        parseFloat($("#discount").val()) || 0;

    const tax =
        parseFloat($("#tax").val()) || 0;

    const shipping =
        parseFloat($("#shipping").val()) || 0;

    const grandTotal =
        subtotal -
        discount +
        tax +
        shipping;

    const amountPaid =
        parseFloat($("#amount_paid").val()) || 0;

    return {

        customer_name:
            $("#customer_name").val().trim(),

        customer_phone:
            $("#customer_phone").val().trim(),

        customer_email:
            $("#customer_email").val().trim(),

        payment_method:
            $("#payment_method").val(),

        payment_status:
            $("#payment_status").val(),

        order_number:
            $("#order_number").val(),

        notes:
            $("#order_notes").val().trim(),

        subtotal: subtotal,

        discount: discount,

        tax: tax,

        shipping: shipping,

        total_amount: grandTotal,

        amount_paid: amountPaid,

        balance:
            amountPaid - grandTotal,

        items: getCartData()

    };

}


/*====================================================
= PART 4C - SUBMIT ORDER
====================================================*/

/*
|--------------------------------------------------------------------------
| Show Checkout Loader
|--------------------------------------------------------------------------
*/

function showCheckoutLoader() {

    checkoutLoader
        .removeClass("hidden")
        .addClass("flex");

    checkoutBtn
        .prop("disabled", true)
        .text("Processing...");

}

/*
|--------------------------------------------------------------------------
| Hide Checkout Loader
|--------------------------------------------------------------------------
*/

function hideCheckoutLoader() {

    checkoutLoader
        .removeClass("flex")
        .addClass("hidden");

    checkoutBtn
        .prop("disabled", false)
        .text("Complete Order");

}

/*
|--------------------------------------------------------------------------
| Submit Order
|--------------------------------------------------------------------------
*/

function submitOrder(orderData) {

    showCheckoutLoader();

    $.ajax({

        url: API_BASE + "/dashboard/orders/create.php",

        type: "POST",

        contentType: "application/json",

        data: JSON.stringify(orderData),

        dataType: "json",

        headers: {

            Authorization: "Bearer " + token

        },

        success: function (response) {

            hideCheckoutLoader();

            if (!response.status) {

                showMessage(
                    "error",
                    response.message || "Unable to create order."
                );

                return;

            }

            /*
            |--------------------------------------------------------------------------
            | Save Last Order
            |--------------------------------------------------------------------------
            */

            lastOrderId = response.order_id;

            /*
            |--------------------------------------------------------------------------
            | Clear Draft
            |--------------------------------------------------------------------------
            */

            localStorage.removeItem("draft_order");

            /*
            |--------------------------------------------------------------------------
            | Success Message
            |--------------------------------------------------------------------------
            */

            showMessage(
                "success",
                response.message || "Order created successfully."
            );

            /*
            |--------------------------------------------------------------------------
            | Reset Form
            |--------------------------------------------------------------------------
            */

            resetOrder();

            /*
            |--------------------------------------------------------------------------
            | Redirect To Receipt
            |--------------------------------------------------------------------------
            */

            if (response.order_id) {

                setTimeout(function () {

                    window.location.href =
                        "receipt.php?id=" +
                        response.order_id;

                }, 1000);

            }

        },

        error: function (xhr) {

            hideCheckoutLoader();

            /*
            |--------------------------------------------------------------------------
            | Unauthorized
            |--------------------------------------------------------------------------
            */

            if (xhr.status === 401) {

                showMessage(
                    "error",
                    "Your login session has expired."
                );

                setTimeout(function () {

                    window.location.href =
                        "../login.php";

                }, 1500);

                return;

            }

            let message = "Server error.";

            if (
                xhr.responseJSON &&
                xhr.responseJSON.message
            ) {

                message = xhr.responseJSON.message;

            } else if (xhr.responseText) {

                try {

                    const json = JSON.parse(xhr.responseText);

                    message = json.message;

                } catch (e) {}

            }

            showMessage(
                "error",
                message
            );

            console.error(xhr);

        }

    });

}

/*====================================================
= PART 5A - RESET, DRAFTS & INITIALIZATION
====================================================*/

/*
|--------------------------------------------------------------------------
| Reset Order
|--------------------------------------------------------------------------
*/

function resetOrder() {

    /*
    |--------------------------------------------------------------------------
    | Customer Information
    |--------------------------------------------------------------------------
    */

    $("#orderForm")[0].reset();

    /*
    |--------------------------------------------------------------------------
    | Payment Defaults
    |--------------------------------------------------------------------------
    */

    $("#payment_method").val("cash");

    $("#payment_status").val("paid");

    amountPaid.val(0);

    balance.val("₦0.00");

    /*
    |--------------------------------------------------------------------------
    | Empty Cart
    |--------------------------------------------------------------------------
    */

    cart = [];

    renderCart();

    calculateTotals();

    /*
    |--------------------------------------------------------------------------
    | Clear Search
    |--------------------------------------------------------------------------
    */

    productSearch.val("");

    barcodeSearch.val("");

    categoryFilter.val("");

    stockFilter.val("");

    sortProducts.val("name");

    /*
    |--------------------------------------------------------------------------
    | Hide Product Card
    |--------------------------------------------------------------------------
    */

    $("#selectedProductCard").addClass("hidden");

    /*
    |--------------------------------------------------------------------------
    | Reload Products
    |--------------------------------------------------------------------------
    */

    currentPage = 1;

    loadProducts();

}

/*
|--------------------------------------------------------------------------
| Clear Cart
|--------------------------------------------------------------------------
*/

$("#clearCartBtn").on("click", function () {

    if (cart.length === 0) {

        return;

    }

    if (!confirm("Remove every item from the cart?")) {

        return;

    }

    cart = [];

    renderCart();

    calculateTotals();

    showMessage(
        "success",
        "Cart cleared successfully."
    );

});

/*
|--------------------------------------------------------------------------
| Cancel Order
|--------------------------------------------------------------------------
*/

$("#cancelOrderBtn").on("click", function () {

    if (
        confirm(
            "Cancel this order and clear everything?"
        )
    ) {

        localStorage.removeItem("draft_order");

        resetOrder();

    }

});

/*
|--------------------------------------------------------------------------
| Save Draft
|--------------------------------------------------------------------------
*/

function saveDraft() {

    const draft = {

        customer_name:
            $("#customer_name").val(),

        customer_phone:
            $("#customer_phone").val(),

        customer_email:
            $("#customer_email").val(),

        payment_method:
            $("#payment_method").val(),

        payment_status:
            $("#payment_status").val(),

        amount_paid:
            amountPaid.val(),

        cart: cart

    };

    localStorage.setItem(

        "draft_order",

        JSON.stringify(draft)

    );

    showMessage(

        "success",

        "Draft saved."

    );

}

$("#saveDraftBtn").on("click", function () {

    saveDraft();

});

/*
|--------------------------------------------------------------------------
| Load Draft
|--------------------------------------------------------------------------
*/

function loadDraft() {

    const draft =

        localStorage.getItem("draft_order");

    if (!draft) {

        return;

    }

    try {

        const data =

            JSON.parse(draft);

        $("#customer_name").val(

            data.customer_name || ""

        );

        $("#customer_phone").val(

            data.customer_phone || ""

        );

        $("#customer_email").val(

            data.customer_email || ""

        );

        $("#payment_method").val(

            data.payment_method || "cash"

        );

        $("#payment_status").val(

            data.payment_status || "paid"

        );

        amountPaid.val(

            data.amount_paid || 0

        );

        cart =

            data.cart || [];

        renderCart();

        calculateTotals();

    } catch (e) {

        console.error(e);

        localStorage.removeItem(

            "draft_order"

        );

    }

}

/*
|--------------------------------------------------------------------------
| Print Receipt
|--------------------------------------------------------------------------
*/

function printReceipt(orderId) {

    if (!orderId) {

        return;

    }

    window.open(

        "receipt.php?id=" + orderId,

        "_blank"

    );

}

/*
|--------------------------------------------------------------------------
| Go To Orders
|--------------------------------------------------------------------------
*/

function goToOrders() {

    window.location.href =

        "orders.php";

}

/*
|--------------------------------------------------------------------------
| New Order
|--------------------------------------------------------------------------
*/

function newOrder() {

    resetOrder();

    $("html, body").animate({

        scrollTop: 0

    }, 300);

}

/*
|--------------------------------------------------------------------------
| Auto Save Before Leaving
|--------------------------------------------------------------------------
*/

window.addEventListener(

    "beforeunload",

    function () {

        if (cart.length > 0) {

            saveDraft();

        }

    }

);

/*
|--------------------------------------------------------------------------
| Network Status
|--------------------------------------------------------------------------
*/

window.addEventListener(

    "offline",

    function () {

        showMessage(

            "error",

            "No internet connection."

        );

    }

);

window.addEventListener(

    "online",

    function () {

        showMessage(

            "success",

            "Internet connection restored."

        );

    }

);

/*
|--------------------------------------------------------------------------
| Keyboard Shortcuts
|--------------------------------------------------------------------------
*/

$(document).keydown(function (e) {

    /*
    | F2 → Product Search
    */

    if (e.key === "F2") {

        e.preventDefault();

        productSearch.focus();

    }

    /*
    | F4 → Checkout
    */

    if (e.key === "F4") {

        e.preventDefault();

        checkoutBtn.click();

    }

    /*
    | ESC → Clear Search
    */

    if (e.key === "Escape") {

        productSearch.val("");

        barcodeSearch.val("");

        applyFilters();

    }

});

/*
|--------------------------------------------------------------------------
| Page Initialization
|--------------------------------------------------------------------------
*/

$(document).ready(function () {

    loadProducts();

    loadDraft();

    calculateTotals();

});


</script>


