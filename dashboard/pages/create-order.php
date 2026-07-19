<?php
require_once __DIR__ . "/../includes/header.php";
?>

<section class="w-full">

    <!-- ============================= -->
    <!-- PAGE HEADER -->
    <!-- ============================= -->

    <article class="mb-6">

        <div class="flex items-center justify-between">

            <div>

                <h1 class="font-title font-bold text-2xl">
                    Create Order
                </h1>

                <p class="text-sm text-gray-600 mt-1">
                    Create a customer order from products available in this store.
                </p>

            </div>

            <a
                href="orders.php"
                class="px-5 py-2 rounded-xl border border-gray-300 hover:bg-gray-50 transition">

                View Orders

            </a>

        </div>

    </article>

    <!-- ============================= -->
    <!-- RESPONSE -->
    <!-- ============================= -->

    <div
        id="responseBox"
        class="hidden mb-6 px-4 py-3 rounded-xl text-sm font-medium">
    </div>

    <!-- ============================= -->
    <!-- CUSTOMER INFORMATION -->
    <!-- ============================= -->

    <article
        class="bg-white rounded-2xl border border-gray-200 shadow-lg overflow-hidden">

        <div
            class="border-b border-gray-200 px-6 py-4">

            <h2
                class="font-title font-semibold text-lg">

                Customer Information

            </h2>

            <p class="text-sm text-gray-500 mt-1">

                Enter customer details before creating an order.

            </p>

        </div>

        <div class="p-6">

            <form
                id="orderForm"
                autocomplete="off">

                <div class="grid md:grid-cols-2 gap-5">

                    <!-- Customer Name -->

                    <div>

                        <label class="label">

                            Customer Name

                        </label>

                        <input
                            type="text"
                            id="customer_name"
                            name="customer_name"
                            class="input"
                            placeholder="Enter customer name">

                    </div>

                    <!-- Phone -->

                    <div>

                        <label class="label">

                            Phone Number

                        </label>

                        <input
                            type="text"
                            id="customer_phone"
                            name="customer_phone"
                            class="input"
                            placeholder="080xxxxxxxx">

                    </div>

                    <!-- Email -->

                    <div>

                        <label class="label">

                            Email Address

                        </label>

                        <input
                            type="email"
                            id="customer_email"
                            name="customer_email"
                            class="input"
                            placeholder="customer@email.com">

                    </div>

                    <!-- Payment -->

                    <div>

                        <label class="label">

                            Payment Method

                        </label>

                        <select
                            id="payment_method"
                            name="payment_method"
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

                        <label class="label">

                            Payment Status

                        </label>

                        <select
                            id="payment_status"
                            name="payment_status"
                            class="input">

                            <option value="paid">

                                Paid

                            </option>

                            <option value="unpaid">

                                Unpaid

                            </option>

                        </select>

                    </div>

                    <!-- Order Number -->

                    <div>

                        <label class="label">

                            Order Number

                        </label>

                        <input
                            type="text"
                            id="order_number"
                            name="order_number"
                            class="input bg-gray-100"
                            readonly>

                    </div>

                </div>

            </form>

        </div>

    </article>

    <!-- ============================= -->
    <!-- PRODUCT SECTION -->
    <!-- Next Part -->
    <!-- ============================= -->

    <article
        class="bg-white rounded-2xl border border-gray-200 shadow-lg mt-8">

        <div
            class="border-b border-gray-200 px-6 py-4">

            <h2
                class="font-title font-semibold text-lg">

                Products

            </h2>

            <p class="text-sm text-gray-500 mt-1">

                Search available products and add them to the shopping cart.

            </p>

        </div>

        <div class="p-6">

<!-- ============================= -->
<!-- PRODUCT SEARCH -->
<!-- ============================= -->

<article
    class="bg-white rounded-2xl border border-gray-200 shadow-lg mt-8">

    <div
        class="border-b border-gray-200 px-6 py-4">

        <div class="flex justify-between items-center">

            <div>

                <h2
                    class="font-title font-semibold text-lg">

                    Products

                </h2>

                <p class="text-sm text-gray-500 mt-1">

                    Search products by name, SKU or barcode, then add them to the cart.

                </p>

            </div>

            <div>

                <span
                    id="productCount"
                    class="px-3 py-1 rounded-full bg-indigo-100 text-indigo-700 text-sm font-semibold">

                    0 Products

                </span>

            </div>

        </div>

    </div>

    <div class="p-6 space-y-6">

        <!-- Search Row -->

        <div class="grid lg:grid-cols-3 gap-5">

            <!-- Search Product -->

            <div>

                <label class="label">

                    Search Product

                </label>

                <input
                    type="text"
                    id="productSearch"
                    class="input"
                    placeholder="Search product name...">

            </div>

            <!-- Barcode -->

            <div>

                <label class="label">

                    Scan Barcode

                </label>

                <input
                    type="text"
                    id="barcodeSearch"
                    class="input"
                    autocomplete="off"
                    placeholder="Scan barcode using laser scanner">

            </div>

            <!-- Category -->

            <div>

                <label class="label">

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

        </div>

        <!-- Second Row -->

        <div class="grid lg:grid-cols-4 gap-5">

            <!-- Stock -->

            <div>

                <label class="label">

                    Stock

                </label>

                <select
                    id="stockFilter"
                    class="input">

                    <option value="">

                        All

                    </option>

                    <option value="available">

                        Available Only

                    </option>

                    <option value="low">

                        Low Stock

                    </option>

                    <option value="out">

                        Out of Stock

                    </option>

                </select>

            </div>

            <!-- Sort -->

            <div>

                <label class="label">

                    Sort By

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

                        Latest Added

                    </option>

                </select>

            </div>

            <!-- Refresh -->

            <div class="flex items-end">

                <button
                    type="button"
                    id="refreshProducts"
                    class="w-full py-3 rounded-xl border border-gray-300 hover:bg-gray-100 transition">

                    Refresh Products

                </button>

            </div>

            <!-- Clear -->

            <div class="flex items-end">

                <button
                    type="button"
                    id="clearSearch"
                    class="w-full py-3 rounded-xl bg-red-500 text-white hover:bg-red-600 transition">

                    Clear Search

                </button>

            </div>

        </div>

        <!-- Selected Product -->

        <div
            id="selectedProductCard"
            class="hidden rounded-2xl border border-indigo-200 bg-indigo-50 p-5">

            <div
                class="flex justify-between items-center">

                <div>

                    <h3
                        id="selectedProductName"
                        class="font-semibold text-lg">

                        -

                    </h3>

                    <p
                        id="selectedProductBarcode"
                        class="text-sm text-gray-500 mt-1">

                        -

                    </p>

                </div>

                <div
                    class="text-right">

                    <div
                        id="selectedProductPrice"
                        class="font-bold text-xl text-green-600">

                        ₦0.00

                    </div>

                    <div
                        id="selectedProductQty"
                        class="text-sm text-gray-500">

                        Qty : 0

                    </div>

                </div>

            </div>

        </div>

        <!-- Loading -->

        <div
            id="productLoader"
            class="hidden py-10 text-center">

            <svg
                class="animate-spin h-8 w-8 mx-auto text-indigo-600"
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

            <p class="mt-3 text-gray-500">

                Loading available products...

            </p>

        </div>

    </div>

</article>


<!-- ============================= -->
<!-- AVAILABLE PRODUCTS TABLE -->
<!-- ============================= -->

<article
    class="bg-white rounded-2xl border border-gray-200 shadow-lg mt-8 overflow-hidden">

    <div
        class="border-b border-gray-200 px-6 py-4 flex justify-between items-center">

        <div>

            <h2 class="font-title font-semibold text-lg">

                Available Products

            </h2>

            <p class="text-sm text-gray-500 mt-1">

                Only products available in this store inventory can be added to the cart.

            </p>

        </div>

        <span
            id="availableCount"
            class="px-3 py-1 rounded-full bg-green-100 text-green-700 font-semibold text-sm">

            0 Available

        </span>

    </div>

    <div class="overflow-x-auto">

        <table class="min-w-full">

            <thead class="bg-gray-100">

                <tr>

                    <th class="px-6 py-4 text-left text-sm font-semibold">

                        Product

                    </th>

                    <th class="px-6 py-4 text-left text-sm font-semibold">

                        Barcode

                    </th>

                    <th class="px-6 py-4 text-left text-sm font-semibold">

                        SKU

                    </th>

                    <th class="px-6 py-4 text-left text-sm font-semibold">

                        Category

                    </th>

                    <th class="px-6 py-4 text-center text-sm font-semibold">

                        Available Qty

                    </th>

                    <th class="px-6 py-4 text-right text-sm font-semibold">

                        Selling Price

                    </th>

                    <th class="px-6 py-4 text-center text-sm font-semibold">

                        Status

                    </th>

                    <th class="px-6 py-4 text-center text-sm font-semibold">

                        Action

                    </th>

                </tr>

            </thead>

            <tbody
                id="productsTable"
                class="divide-y divide-gray-100">

                <tr>

                    <td
                        colspan="8"
                        class="text-center py-16 text-gray-500">

                        Loading products...

                    </td>

                </tr>

            </tbody>

        </table>

    </div>

</article>



        </div>

    </article>

<!-- ======================================== -->
<!-- SHOPPING CART -->
<!-- ======================================== -->

    <article
        class="bg-white rounded-2xl border border-gray-200 shadow-lg mt-8 overflow-hidden">

        <!-- Header -->

        <div
            class="flex justify-between items-center px-6 py-4 border-b">

            <div>

                <h2 class="font-title font-semibold text-lg">

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
                    class="px-4 py-2 rounded-lg bg-red-500 text-white hover:bg-red-600 transition">

                    Clear Cart

                </button>

            </div>

        </div>

        <!-- Table -->

        <div class="overflow-x-auto">

            <table class="min-w-full">

                <thead class="bg-gray-50">

                    <tr>

                        <th class="px-6 py-3 text-left">

                            Product

                        </th>

                        <th class="px-6 py-3 text-left">

                            Barcode

                        </th>

                        <th class="px-6 py-3 text-center">

                            Qty

                        </th>

                        <th class="px-6 py-3 text-right">

                            Price

                        </th>

                        <th class="px-6 py-3 text-right">

                            Total

                        </th>

                        <th class="px-6 py-3 text-center">

                            Action

                        </th>

                    </tr>

                </thead>

                <tbody id="cartTable">

                    <tr id="emptyCartRow">

                        <td
                            colspan="6"
                            class="py-12 text-center text-gray-500">

                            No product has been added to the cart.

                        </td>

                    </tr>

                </tbody>

            </table>

        </div>

        <!-- Footer -->

        <div
            class="border-t bg-gray-50 px-6 py-5">

            <div class="grid md:grid-cols-3 gap-6">

                <!-- Items -->

                <div
                    class="bg-white rounded-xl border p-4">

                    <p class="text-sm text-gray-500">

                        Total Items

                    </p>

                    <h2
                        id="totalItems"
                        class="text-2xl font-bold mt-2">

                        0

                    </h2>

                </div>

                <!-- Quantity -->

                <div
                    class="bg-white rounded-xl border p-4">

                    <p class="text-sm text-gray-500">

                        Total Quantity

                    </p>

                    <h2
                        id="totalQuantity"
                        class="text-2xl font-bold mt-2">

                        0

                    </h2>

                </div>

                <!-- Amount -->

                <div
                    class="bg-white rounded-xl border p-4">

                    <p class="text-sm text-gray-500">

                        Cart Total

                    </p>

                    <h2
                        id="cartTotal"
                        class="text-2xl font-bold text-green-600 mt-2">

                        ₦0.00

                    </h2>

                </div>

            </div>

        </div>

    </article>

<!-- Hidden Template (JavaScript will clone this) -->

    <table class="hidden">

        <tbody>

            <tr id="cartRowTemplate">

                <td class="px-6 py-4">

                    <div>

                        <h4 class="font-semibold product-name">

                            Product Name

                        </h4>

                        <small class="text-gray-500 sku">

                            SKU

                        </small>

                    </div>

                </td>

                <td class="px-6 py-4 barcode">

                    000000000

                </td>

                <td class="px-6 py-4">

                    <div class="flex justify-center items-center gap-2">

                        <button
                            class="decreaseQty w-8 h-8 rounded-lg bg-gray-100 hover:bg-gray-200">

                            -

                        </button>

                        <input
                            type="number"
                            min="1"
                            class="cartQty input w-20 text-center"
                            value="1">

                        <button
                            class="increaseQty w-8 h-8 rounded-lg bg-gray-100 hover:bg-gray-200">

                            +

                        </button>

                    </div>

                </td>

                <td
                    class="px-6 py-4 text-right product-price">

                    ₦0.00

                </td>

                <td
                    class="px-6 py-4 text-right product-total font-semibold">

                    ₦0.00

                </td>

                <td
                    class="px-6 py-4 text-center">

                    <button
                        class="removeCartItem px-3 py-2 rounded-lg bg-red-500 text-white hover:bg-red-600">

                        Remove

                    </button>

                </td>

            </tr>

        </tbody>

    </table>    

</section>

<!-- ======================================== -->
<!-- CHECKOUT -->
<!-- ======================================== -->

<article
    class="bg-white rounded-2xl border border-gray-200 shadow-lg mt-8 overflow-hidden mb-8">

    <div
        class="border-b border-gray-200 px-6 py-4">

        <h2
            class="font-title font-semibold text-lg">

            Checkout

        </h2>

        <p
            class="text-sm text-gray-500 mt-1">

            Confirm the order and complete checkout.

        </p>

    </div>

    <div class="p-6">

        <div class="grid lg:grid-cols-2 gap-8">

            <!-- Payment Information -->

            <div>

                <h3
                    class="font-semibold text-lg mb-5">

                    Payment Information

                </h3>

                <div class="space-y-5">

                    <div>

                        <label class="label">

                            Amount Paid

                        </label>

                        <input
                            type="number"
                            id="amount_paid"
                            class="input"
                            value="0"
                            min="0"
                            step="0.01">

                    </div>

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

                    <div>

                        <label class="label">

                            Cashier

                        </label>

                        <input
                            type="text"
                            id="cashier_name"
                            class="input bg-gray-100"
                            readonly
                            value="<?php echo htmlspecialchars($_SESSION['admin_name'] ?? 'Administrator'); ?>">

                    </div>

                </div>

            </div>

            <!-- Checkout Summary -->

            <div>

                <div
                    class="border rounded-2xl p-6 bg-gray-50">

                    <h3
                        class="font-semibold text-lg mb-6">

                        Checkout Summary

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

                                Grand Total

                            </span>

                            <strong
                                id="summaryTotal"
                                class="text-green-600">

                                ₦0.00

                            </strong>

                        </div>

                        <div class="flex justify-between">

                            <span>

                                Amount Paid

                            </span>

                            <strong
                                id="summaryPaid">

                                ₦0.00

                            </strong>

                        </div>

                        <div class="flex justify-between border-t pt-4">

                            <span
                                class="font-bold">

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

        <!-- Buttons -->

        <div
            class="flex justify-end gap-4 mt-10">

            <button
                type="button"
                id="cancelOrderBtn"
                class="px-6 py-3 rounded-xl border border-gray-300 hover:bg-gray-100 transition">

                Cancel

            </button>

            <button
                type="button"
                id="saveDraftBtn"
                class="px-6 py-3 rounded-xl bg-yellow-500 text-white hover:bg-yellow-600 transition">

                Save Draft

            </button>

            <button
                type="button"
                id="checkoutBtn"
                class="px-8 py-3 rounded-xl bg-indigo-600 text-white font-semibold hover:bg-indigo-700 transition">

                Complete Order

            </button>

        </div>

    </div>

</article>

<!-- ======================================== -->
<!-- LOADING MODAL -->
<!-- ======================================== -->

<div
    id="checkoutLoader"
    class="hidden fixed inset-0 bg-black/40 z-50 flex items-center justify-center">

    <div
        class="bg-white rounded-2xl shadow-xl p-8 w-80 text-center">

        <svg
            class="animate-spin h-10 w-10 mx-auto text-indigo-600"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24">

            <circle
                cx="12"
                cy="12"
                r="10"
                stroke="currentColor"
                stroke-width="4"
                class="opacity-25">
            </circle>

            <path
                fill="currentColor"
                class="opacity-75"
                d="M4 12a8 8 0 018-8v4l3-3-3-3v4A10 10 0 002 12h2z">
            </path>

        </svg>

        <h3
            class="mt-5 font-semibold text-lg">

            Processing Order...

        </h3>

        <p
            class="text-sm text-gray-500 mt-2">

            Please wait while we complete your order.

        </p>

    </div>

</div>

<?php
require_once __DIR__ . "/../includes/footer.php";
?>

<script>

const API_BASE_URL = "<?php echo API_BASE_URL; ?>";

/*====================================================
=            PART 2A - VARIABLES & CONFIG            =
====================================================*/

const API_BASE = API_BASE_URL;

const PRODUCTS_API = API_BASE + "/dashboard/orders/accepted-products.php";

let allProducts = [];

let filteredProducts = [];

let cart = [];

let currentPage = 1;

const rowsPerPage = 10;

/*====================================================
=            DOM ELEMENTS                            =
====================================================*/

const productsTable = $("#productsTable");

const productSearch = $("#productSearch");

const barcodeSearch = $("#barcodeSearch");

const categoryFilter = $("#categoryFilter");

const stockFilter = $("#stockFilter");

const sortProducts = $("#sortProducts");

const availableCount = $("#availableCount");

const productCount = $("#productCount");

const loader = $("#productLoader");

/*====================================================
=            LOAD PRODUCTS                           =
====================================================*/

$(document).ready(function () {

    loadProducts();

});

/*====================================================
=            FETCH PRODUCTS                          =
====================================================*/

function loadProducts() {

    loader.removeClass("hidden");

    $.ajax({

        url: PRODUCTS_API,

        method: "GET",

        dataType: "json",

        success: function (response) {

            loader.addClass("hidden");

            if (!response.status) {

                showMessage("error", response.message);

                return;

            }

            allProducts = response.data || [];

            filteredProducts = [...allProducts];

            populateCategories();

            renderProducts();

        },

        error: function () {

            loader.addClass("hidden");

            showMessage("error", "Unable to load products.");

        }

    });

}

/*====================================================
=            POPULATE CATEGORY FILTER                =
====================================================*/

function populateCategories() {

    let categories = [];

    allProducts.forEach(function (product) {

        if (
            product.category &&
            !categories.includes(product.category)
        ) {

            categories.push(product.category);

        }

    });

    categoryFilter.html(

        `<option value="">All Categories</option>`

    );

    categories.sort();

    categories.forEach(function (cat) {

        categoryFilter.append(

            `<option value="${cat}">${cat}</option>`

        );

    });

}

/*====================================================
=            RENDER PRODUCTS                         =
====================================================*/

function renderProducts() {

    let html = "";

    if (filteredProducts.length === 0) {

        html = `

        <tr>

            <td colspan="8"
                class="text-center py-12 text-gray-500">

                No products available.

            </td>

        </tr>

        `;

        productsTable.html(html);

        availableCount.text("0 Available");

        productCount.text("0 Products");

        return;

    }

    availableCount.text(filteredProducts.length + " Available");

    productCount.text(filteredProducts.length + " Products");

    const start = (currentPage - 1) * rowsPerPage;

    const end = start + rowsPerPage;

    const rows = filteredProducts.slice(start, end);

    rows.forEach(function (product) {

        let badge = "";

        switch (product.status) {

            case "available":

                badge = `
                    <span
                        class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs">

                        Available

                    </span>
                `;

                break;

            case "out_of_stock":

                badge = `
                    <span
                        class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs">

                        Out of Stock

                    </span>
                `;

                break;

            default:

                badge = `
                    <span
                        class="px-3 py-1 rounded-full bg-gray-100 text-gray-700 text-xs">

                        ${product.status}

                    </span>
                `;

        }

        html += `

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

                    class="bg-indigo-600 hover:bg-indigo-700
                           text-white px-4 py-2 rounded-lg"

                    onclick="addToCart(${product.id})">

                    Add to Cart

                </button>

            </td>

        </tr>

        `;

    });

    productsTable.html(html);

}

/*====================================================
=            HELPERS                                 =
====================================================*/

function formatMoney(value) {

    return Number(value).toLocaleString("en-NG", {

        minimumFractionDigits: 2,

        maximumFractionDigits: 2

    });

}

function escapeHtml(text) {

    if (text === null || text === undefined) return "";

    return $("<div>").text(text).html();

}

function showMessage(type, message) {

    const box = $("#responseBox");

    box.removeClass("hidden bg-red-100 bg-green-100 text-red-700 text-green-700");

    if (type === "success") {

        box.addClass("bg-green-100 text-green-700");

    } else {

        box.addClass("bg-red-100 text-red-700");

    }

    box.text(message);

    setTimeout(function () {

        box.addClass("hidden");

    }, 4000);

}

/*====================================================
=            PART 2B - SEARCH + BARCODE + FILTERS    =
====================================================*/

/*
|--------------------------------------------------------------------------
| Product Search
|--------------------------------------------------------------------------
*/

productSearch.on("keyup", function () {

    applyFilters();

});

/*
|--------------------------------------------------------------------------
| Barcode Scanner
|--------------------------------------------------------------------------
|
| Works with USB laser barcode scanners.
| When the scanner sends Enter, the product is automatically added.
|
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

        showMessage("error", "Product not found.");

        $(this).val("");

        return;

    }

    if (product.quantity <= 0) {

        showMessage("error", "Product is out of stock.");

        $(this).val("");

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

    applyFilters();

});

/*
|--------------------------------------------------------------------------
| Stock Filter
|--------------------------------------------------------------------------
*/

stockFilter.on("change", function () {

    applyFilters();

});

/*
|--------------------------------------------------------------------------
| Sort Products
|--------------------------------------------------------------------------
*/

sortProducts.on("change", function () {

    applyFilters();

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

    filteredProducts = [...allProducts];

    renderProducts();

});

/*====================================================
=            APPLY FILTERS                           =
====================================================*/

function applyFilters() {

    const keyword = productSearch.val().toLowerCase().trim();

    const category = categoryFilter.val();

    const stock = stockFilter.val();

    filteredProducts = allProducts.filter(function (product) {

        let match = true;

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if (keyword !== "") {

            const text = (

                (product.product_name || "") +

                " " +

                (product.barcode || "") +

                " " +

                (product.sku || "") +

                " " +

                (product.category || "")

            ).toLowerCase();

            if (!text.includes(keyword)) {

                match = false;

            }

        }

        /*
        |--------------------------------------------------------------------------
        | Category
        |--------------------------------------------------------------------------
        */

        if (

            category !== "" &&

            product.category !== category

        ) {

            match = false;

        }

        /*
        |--------------------------------------------------------------------------
        | Stock
        |--------------------------------------------------------------------------
        */

        if (stock === "available") {

            if (product.quantity <= 0) {

                match = false;

            }

        }

        if (stock === "low") {

            if (

                product.quantity >

                product.minimum_stock

            ) {

                match = false;

            }

        }

        if (stock === "out") {

            if (product.quantity > 0) {

                match = false;

            }

        }

        return match;

    });

    sortFilteredProducts();

    currentPage = 1;

    renderProducts();

}

/*====================================================
=            SORT PRODUCTS                           =
====================================================*/

function sortFilteredProducts() {

    const sort = sortProducts.val();

    switch (sort) {

        case "price":

            filteredProducts.sort(function (a, b) {

                return Number(a.selling_price) -

                    Number(b.selling_price);

            });

            break;

        case "quantity":

            filteredProducts.sort(function (a, b) {

                return b.quantity - a.quantity;

            });

            break;

        case "created":

            filteredProducts.sort(function (a, b) {

                return new Date(b.created_at) -

                    new Date(a.created_at);

            });

            break;

        default:

            filteredProducts.sort(function (a, b) {

                return a.product_name.localeCompare(

                    b.product_name

                );

            });

    }

}

/*====================================================
=            AUTO SEARCH AFTER SCAN                  =
====================================================*/

barcodeSearch.on("input", function () {

    const code = $(this).val().trim();

    if (code.length < 4) return;

    const product = allProducts.find(function (item) {

        return item.barcode === code;

    });

    if (!product) return;

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

/*====================================================
=            HIDE PRODUCT CARD                       =
====================================================*/

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


/*====================================================
=            PART 2C-1 - ADD TO CART                 =
====================================================*/

function addToCart(productId) {

    const product = allProducts.find(function (item) {
        return Number(item.id) === Number(productId);
    });

    if (!product) {
        showMessage("error", "Product not found.");
        return;
    }

    if (Number(product.quantity) <= 0) {
        showMessage("error", "Product is out of stock.");
        return;
    }

    const existing = cart.find(function (item) {
        return Number(item.id) === Number(productId);
    });

    if (existing) {

        if (existing.quantity >= Number(product.quantity)) {
            showMessage("error", "Maximum available stock reached.");
            return;
        }

        existing.quantity++;

        existing.total =
            Number(existing.quantity) *
            Number(existing.selling_price);

    } else {

        cart.push({

            id: product.id,

            product_name: product.product_name,

            barcode: product.barcode,

            sku: product.sku,

            category: product.category,

            price: Number(product.selling_price),

            selling_price: Number(product.selling_price),

            available_quantity: Number(product.quantity),

            quantity: 1,

            total: Number(product.selling_price)

        });

    }

    renderCart();

    calculateTotals();

    showMessage("success", "Product added to cart.");

}

/*====================================================
=            RENDER CART                             =
====================================================*/

function renderCart() {

    const tbody = $("#cartTable");

    tbody.empty();

    if (cart.length === 0) {

        tbody.html(`

            <tr id="emptyCartRow">

                <td
                    colspan="6"
                    class="text-center py-12 text-gray-500">

                    No product has been added to the cart.

                </td>

            </tr>

        `);

        $("#cartCount").text("0 Items");

        $("#totalItems").text("0");

        $("#totalQuantity").text("0");

        $("#cartTotal").text("₦0.00");

        return;

    }

    let totalItems = 0;

    let totalQty = 0;

    let cartTotal = 0;

    cart.forEach(function (item) {

        totalItems++;

        totalQty += Number(item.quantity);

        cartTotal += Number(item.total);

        tbody.append(`

            <tr
                data-id="${item.id}"
                class="border-b hover:bg-gray-50">

                <td class="px-6 py-4">

                    <div>

                        <div class="font-semibold">

                            ${escapeHtml(item.product_name)}

                        </div>

                        <div class="text-xs text-gray-500">

                            ${escapeHtml(item.sku || "-")}

                        </div>

                    </div>

                </td>

                <td class="px-6 py-4">

                    ${escapeHtml(item.barcode)}

                </td>

                <td class="px-6 py-4">

                    <div
                        class="flex justify-center items-center gap-2">

                        <button

                            class="decreaseQty
                                   w-8 h-8 rounded-lg
                                   bg-gray-100 hover:bg-gray-200"

                            data-id="${item.id}">

                            -

                        </button>

                        <input

                            type="number"

                            min="1"

                            max="${item.available_quantity}"

                            value="${item.quantity}"

                            class="cartQty
                                   input
                                   w-20
                                   text-center"

                            data-id="${item.id}">

                        <button

                            class="increaseQty
                                   w-8 h-8 rounded-lg
                                   bg-gray-100 hover:bg-gray-200"

                            data-id="${item.id}">

                            +

                        </button>

                    </div>

                </td>

                <td class="px-6 py-4 text-right">

                    ₦${formatMoney(item.selling_price)}

                </td>

                <td
                    class="px-6 py-4 text-right font-semibold">

                    ₦${formatMoney(item.total)}

                </td>

                <td class="px-6 py-4 text-center">

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

    $("#cartCount").text(totalItems + " Item(s)");

    $("#totalItems").text(totalItems);

    $("#totalQuantity").text(totalQty);

    $("#cartTotal").text("₦" + formatMoney(cartTotal));

    $("#summaryItems").text(totalItems);

    $("#summaryQty").text(totalQty);

}

/*====================================================
=            PART 2B - SEARCH + BARCODE + FILTERS    =
====================================================*/

/*
|--------------------------------------------------------------------------
| Product Search
|--------------------------------------------------------------------------
*/

productSearch.on("keyup", function () {

    applyFilters();

});

/*
|--------------------------------------------------------------------------
| Barcode Scanner
|--------------------------------------------------------------------------
|
| Works with USB laser barcode scanners.
| When the scanner sends Enter, the product is automatically added.
|
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

        showMessage("error", "Product not found.");

        $(this).val("");

        return;

    }

    if (product.quantity <= 0) {

        showMessage("error", "Product is out of stock.");

        $(this).val("");

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

    applyFilters();

});

/*
|--------------------------------------------------------------------------
| Stock Filter
|--------------------------------------------------------------------------
*/

stockFilter.on("change", function () {

    applyFilters();

});

/*
|--------------------------------------------------------------------------
| Sort Products
|--------------------------------------------------------------------------
*/

sortProducts.on("change", function () {

    applyFilters();

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

    filteredProducts = [...allProducts];

    renderProducts();

});

/*====================================================
=            APPLY FILTERS                           =
====================================================*/

function applyFilters() {

    const keyword = productSearch.val().toLowerCase().trim();

    const category = categoryFilter.val();

    const stock = stockFilter.val();

    filteredProducts = allProducts.filter(function (product) {

        let match = true;

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if (keyword !== "") {

            const text = (

                (product.product_name || "") +

                " " +

                (product.barcode || "") +

                " " +

                (product.sku || "") +

                " " +

                (product.category || "")

            ).toLowerCase();

            if (!text.includes(keyword)) {

                match = false;

            }

        }

        /*
        |--------------------------------------------------------------------------
        | Category
        |--------------------------------------------------------------------------
        */

        if (

            category !== "" &&

            product.category !== category

        ) {

            match = false;

        }

        /*
        |--------------------------------------------------------------------------
        | Stock
        |--------------------------------------------------------------------------
        */

        if (stock === "available") {

            if (product.quantity <= 0) {

                match = false;

            }

        }

        if (stock === "low") {

            if (

                product.quantity >

                product.minimum_stock

            ) {

                match = false;

            }

        }

        if (stock === "out") {

            if (product.quantity > 0) {

                match = false;

            }

        }

        return match;

    });

    sortFilteredProducts();

    currentPage = 1;

    renderProducts();

}

/*====================================================
=            SORT PRODUCTS                           =
====================================================*/

function sortFilteredProducts() {

    const sort = sortProducts.val();

    switch (sort) {

        case "price":

            filteredProducts.sort(function (a, b) {

                return Number(a.selling_price) -

                    Number(b.selling_price);

            });

            break;

        case "quantity":

            filteredProducts.sort(function (a, b) {

                return b.quantity - a.quantity;

            });

            break;

        case "created":

            filteredProducts.sort(function (a, b) {

                return new Date(b.created_at) -

                    new Date(a.created_at);

            });

            break;

        default:

            filteredProducts.sort(function (a, b) {

                return a.product_name.localeCompare(

                    b.product_name

                );

            });

    }

}

/*====================================================
=            AUTO SEARCH AFTER SCAN                  =
====================================================*/

barcodeSearch.on("input", function () {

    const code = $(this).val().trim();

    if (code.length < 4) return;

    const product = allProducts.find(function (item) {

        return item.barcode === code;

    });

    if (!product) return;

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

/*====================================================
=            HIDE PRODUCT CARD                       =
====================================================*/

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

/*====================================================
=     PART 2C-3 - UPDATE TOTALS + HELPER FUNCTIONS   =
====================================================*/

/*
|--------------------------------------------------------------------------
| Calculate Order Totals
|--------------------------------------------------------------------------
*/

function calculateTotals() {

    let subtotal = 0;
    let totalItems = 0;
    let totalQty = 0;

    cart.forEach(function (item) {

        subtotal += Number(item.total);

        totalItems++;

        totalQty += Number(item.quantity);

    });

    const discount = parseFloat($("#discount").val()) || 0;

    const tax = parseFloat($("#tax").val()) || 0;

    const shipping = parseFloat($("#shipping").val()) || 0;

    let grandTotal = subtotal - discount + tax + shipping;

    if (grandTotal < 0) {

        grandTotal = 0;

    }

    const amountPaid = parseFloat($("#amount_paid").val()) || 0;

    const balance = amountPaid - grandTotal;

    /*
    |--------------------------------------------------------------------------
    | Shopping Cart
    |--------------------------------------------------------------------------
    */

    $("#cartCount").text(totalItems + " Item(s)");

    $("#totalItems").text(totalItems);

    $("#totalQuantity").text(totalQty);

    $("#cartTotal").text("₦" + formatMoney(subtotal));

    /*
    |--------------------------------------------------------------------------
    | Order Summary
    |--------------------------------------------------------------------------
    */

    $("#subtotalAmount").text("₦" + formatMoney(subtotal));

    $("#grandTotal").text("₦" + formatMoney(grandTotal));

    /*
    |--------------------------------------------------------------------------
    | Checkout Summary
    |--------------------------------------------------------------------------
    */

    $("#summaryItems").text(totalItems);

    $("#summaryQty").text(totalQty);

    $("#summaryTotal").text("₦" + formatMoney(grandTotal));

    $("#summaryPaid").text("₦" + formatMoney(amountPaid));

    $("#summaryBalance").text("₦" + formatMoney(balance));

    $("#balance").val("₦" + formatMoney(balance));

}

/*
|--------------------------------------------------------------------------
| Auto Recalculate
|--------------------------------------------------------------------------
*/

$("#discount").on("keyup change", function () {

    calculateTotals();

});

$("#tax").on("keyup change", function () {

    calculateTotals();

});

$("#shipping").on("keyup change", function () {

    calculateTotals();

});

$("#amount_paid").on("keyup change", function () {

    calculateTotals();

});

/*
|--------------------------------------------------------------------------
| Currency Formatter
|--------------------------------------------------------------------------
*/

function formatMoney(amount) {

    amount = Number(amount);

    if (isNaN(amount)) {

        amount = 0;

    }

    return amount.toLocaleString("en-NG", {

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

    const box = $("#responseBox");

    box.removeClass(
        "hidden bg-green-100 bg-red-100 text-green-700 text-red-700"
    );

    if (type === "success") {

        box.addClass("bg-green-100 text-green-700");

    } else {

        box.addClass("bg-red-100 text-red-700");

    }

    box.text(message);

    $("html, body").animate(
        {
            scrollTop: 0
        },
        300
    );

    setTimeout(function () {

        box.addClass("hidden");

    }, 3000);

}

/*
|--------------------------------------------------------------------------
| Reset Order
|--------------------------------------------------------------------------
*/

function resetOrder() {

    cart = [];

    renderCart();

    calculateTotals();

    $("#customer_name").val("");

    $("#customer_phone").val("");

    $("#customer_email").val("");

    $("#discount").val(0);

    $("#tax").val(0);

    $("#shipping").val(0);

    $("#amount_paid").val(0);

    $("#order_notes").val("");

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

/*
|--------------------------------------------------------------------------
| Check Empty Cart
|--------------------------------------------------------------------------
*/

function cartIsEmpty() {

    return cart.length === 0;

}

/*
|--------------------------------------------------------------------------
| Initialize Totals
|--------------------------------------------------------------------------
*/

$(document).ready(function () {

    calculateTotals();

});

/*====================================================
=      PART 2D-1 - CHECKOUT VALIDATION               =
====================================================*/

/*
|--------------------------------------------------------------------------
| Checkout Button
|--------------------------------------------------------------------------
*/

$("#checkoutBtn").on("click", function () {

    if (!validateOrder()) {
        return;
    }

    const orderData = collectOrderData();

    submitOrder(orderData);

});

/*====================================================
=      VALIDATE ORDER                               =
====================================================*/

function validateOrder() {

    /*
    |--------------------------------------------------------------------------
    | Customer Information
    |--------------------------------------------------------------------------
    */

    const customerName = $("#customer_name").val().trim();

    const customerPhone = $("#customer_phone").val().trim();

    const paymentMethod = $("#payment_method").val();

    if (customerName === "") {

        showMessage("error", "Customer name is required.");

        $("#customer_name").focus();

        return false;

    }

    if (customerPhone === "") {

        showMessage("error", "Customer phone is required.");

        $("#customer_phone").focus();

        return false;

    }

    if (paymentMethod === "") {

        showMessage("error", "Select payment method.");

        $("#payment_method").focus();

        return false;

    }

    /*
    |--------------------------------------------------------------------------
    | Cart Validation
    |--------------------------------------------------------------------------
    */

    if (cart.length === 0) {

        showMessage("error", "Your cart is empty.");

        return false;

    }

    /*
    |--------------------------------------------------------------------------
    | Product Validation
    |--------------------------------------------------------------------------
    */

    for (let i = 0; i < cart.length; i++) {

        const item = cart[i];

        if (item.quantity <= 0) {

            showMessage("error", item.product_name + " quantity is invalid.");

            return false;

        }

        if (item.quantity > item.available_quantity) {

            showMessage(
                "error",
                item.product_name +
                " has only " +
                item.available_quantity +
                " in stock."
            );

            return false;

        }

    }

    /*
    |--------------------------------------------------------------------------
    | Payment Validation
    |--------------------------------------------------------------------------
    */

    const grandTotal = parseFloat(
        $("#grandTotal")
            .text()
            .replace(/[₦,]/g, "")
    ) || 0;

    const amountPaid = parseFloat(
        $("#amount_paid").val()
    ) || 0;

    if (amountPaid < grandTotal) {

        if (
            !confirm(
                "Customer has not paid the full amount.\n\nContinue anyway?"
            )
        ) {

            return false;

        }

    }

    return true;

}

/*====================================================
=      COLLECT ORDER DATA                           =
====================================================*/

function collectOrderData() {

    const subtotal = parseFloat(
        $("#subtotalAmount")
            .text()
            .replace(/[₦,]/g, "")
    ) || 0;

    const discount = parseFloat($("#discount").val()) || 0;

    const tax = parseFloat($("#tax").val()) || 0;

    const shipping = parseFloat($("#shipping").val()) || 0;

    const grandTotal = parseFloat(
        $("#grandTotal")
            .text()
            .replace(/[₦,]/g, "")
    ) || 0;

    const amountPaid = parseFloat($("#amount_paid").val()) || 0;

    const balance = amountPaid - grandTotal;

    return {

        customer_name: $("#customer_name").val().trim(),

        customer_phone: $("#customer_phone").val().trim(),

        customer_email: $("#customer_email").val().trim(),

        payment_method: $("#payment_method").val(),

        notes: $("#order_notes").val().trim(),

        subtotal: subtotal,

        discount: discount,

        tax: tax,

        shipping: shipping,

        total_amount: grandTotal,

        amount_paid: amountPaid,

        balance: balance,

        items: getCartData()

    };

}

/*====================================================
=      SHOW LOADER                                  =
====================================================*/

function showCheckoutLoader() {

    $("#checkoutLoader")
        .removeClass("hidden")
        .addClass("flex");

    $("#checkoutBtn")
        .prop("disabled", true)
        .text("Processing...");

}

/*====================================================
=      HIDE LOADER                                  =
====================================================*/

function hideCheckoutLoader() {

    $("#checkoutLoader")
        .removeClass("flex")
        .addClass("hidden");

    $("#checkoutBtn")
        .prop("disabled", false)
        .text("Complete Order");

}


/*====================================================
=      PART 2D-2 - AJAX REQUEST                      =
====================================================*/

function submitOrder(orderData) {

    showCheckoutLoader();

    $.ajax({

        url: API_BASE + "/dashboard/orders/create.php",

        type: "POST",

        contentType: "application/json",

        data: JSON.stringify(orderData),

        dataType: "json",

        headers: {

            Authorization: "Bearer " + localStorage.getItem("token")

        },

        success: function (response) {

            hideCheckoutLoader();

            if (!response.status) {

                showMessage(

                    "error",

                    response.message ||

                    "Unable to complete order."

                );

                return;

            }

            /*
            |--------------------------------------------------------------------------
            | Success
            |--------------------------------------------------------------------------
            */

            showMessage(

                "success",

                response.message ||

                "Order created successfully."

            );

            /*
            |--------------------------------------------------------------------------
            | Save Order ID
            |--------------------------------------------------------------------------
            */

            window.lastOrderId = response.order_id;

            /*
            |--------------------------------------------------------------------------
            | Clear Draft
            |--------------------------------------------------------------------------
            */

            localStorage.removeItem("draft_order");

            /*
            |--------------------------------------------------------------------------
            | Reset Order
            |--------------------------------------------------------------------------
            */

            resetOrder();

            /*
            |--------------------------------------------------------------------------
            | Receipt
            |--------------------------------------------------------------------------
            */

            if (response.order_id) {

                setTimeout(function () {

                    window.location.href =
                        "receipt.php?id=" + response.order_id;

                }, 1000);

            }

        },

        error: function (xhr) {

            hideCheckoutLoader();

            let message = "Server error.";

            /*
            |--------------------------------------------------------------------------
            | JSON Error
            |--------------------------------------------------------------------------
            */

            if (

                xhr.responseJSON &&

                xhr.responseJSON.message

            ) {

                message = xhr.responseJSON.message;

            }

            /*
            |--------------------------------------------------------------------------
            | Plain Text Error
            |--------------------------------------------------------------------------
            */

            else if (xhr.responseText) {

                try {

                    const json = JSON.parse(xhr.responseText);

                    message = json.message;

                }

                catch (e) {

                    message = xhr.responseText;

                }

            }

            showMessage("error", message);

            console.error(xhr);

        }

    });

}

/*====================================================
=      GLOBAL AJAX ERROR                             =
====================================================*/

$(document).ajaxError(function (

    event,

    xhr,

    settings,

    thrownError

) {

    console.error("AJAX ERROR");

    console.error(settings.url);

    console.error(xhr.status);

    console.error(thrownError);

});

/*====================================================
=      CONNECTION CHECK                              =
====================================================*/

$(document).ajaxStart(function () {

    $("#checkoutBtn")

        .prop("disabled", true);

});

$(document).ajaxStop(function () {

    $("#checkoutBtn")

        .prop("disabled", false);

});

/*====================================================
=      NETWORK STATUS                                =
====================================================*/

window.addEventListener("offline", function () {

    showMessage(

        "error",

        "No internet connection."

    );

});

window.addEventListener("online", function () {

    showMessage(

        "success",

        "Internet connection restored."

    );

});


/*====================================================
=      PART 2D-3 - COMPLETE ORDER HELPERS            =
====================================================*/

/*
|--------------------------------------------------------------------------
| Reset Complete Order Form
|--------------------------------------------------------------------------
*/

function resetOrder() {

    /*
    |--------------------------------------------------------------------------
    | Customer Information
    |--------------------------------------------------------------------------
    */

    $("#customer_name").val("");

    $("#customer_phone").val("");

    $("#customer_email").val("");

    $("#payment_method").val("cash");

    $("#order_notes").val("");

    /*
    |--------------------------------------------------------------------------
    | Order Summary
    |--------------------------------------------------------------------------
    */

    $("#discount").val(0);

    $("#tax").val(0);

    $("#shipping").val(0);

    $("#amount_paid").val(0);

    $("#balance").val("₦0.00");

    /*
    |--------------------------------------------------------------------------
    | Cart
    |--------------------------------------------------------------------------
    */

    cart = [];

    renderCart();

    calculateTotals();

    /*
    |--------------------------------------------------------------------------
    | Product Search
    |--------------------------------------------------------------------------
    */

    $("#productSearch").val("");

    $("#barcodeSearch").val("");

    $("#categoryFilter").val("");

    $("#stockFilter").val("");

    $("#sortProducts").val("name");

    /*
    |--------------------------------------------------------------------------
    | Reload Products
    |--------------------------------------------------------------------------
    */

    loadProducts();

}

/*====================================================
=      PRINT RECEIPT                                =
====================================================*/

function printReceipt(orderId) {

    if (!orderId) return;

    window.open(

        "receipt.php?id=" + orderId,

        "_blank"

    );

}

/*====================================================
=      VIEW ORDER                                   =
====================================================*/

function viewOrder(orderId) {

    if (!orderId) return;

    window.location.href =

        "view-order.php?id=" + orderId;

}

/*====================================================
=      GO TO ORDERS PAGE                            =
====================================================*/

function goToOrders() {

    window.location.href =

        "orders.php";

}

/*====================================================
=      NEW ORDER                                    =
====================================================*/

function newOrder() {

    resetOrder();

    $("html, body").animate({

        scrollTop: 0

    }, 300);

}

/*====================================================
=      AFTER SUCCESS                                =
====================================================*/

function orderCompleted(response) {

    /*
    |--------------------------------------------------------------------------
    | Remove Draft
    |--------------------------------------------------------------------------
    */

    localStorage.removeItem("draft_order");

    /*
    |--------------------------------------------------------------------------
    | Store Last Order
    |--------------------------------------------------------------------------
    */

    window.lastOrderId = response.order_id;

    /*
    |--------------------------------------------------------------------------
    | Success Message
    |--------------------------------------------------------------------------
    */

    showMessage(

        "success",

        response.message ||

        "Order completed successfully."

    );

    /*
    |--------------------------------------------------------------------------
    | Ask User
    |--------------------------------------------------------------------------
    */

    setTimeout(function () {

        const action = confirm(

            "Order completed successfully.\n\n" +

            "Press OK to print the receipt.\n" +

            "Press Cancel to create another order."

        );

        if (action) {

            printReceipt(response.order_id);

        }

        resetOrder();

    }, 500);

}

/*====================================================
=      SAVE DRAFT                                  =
====================================================*/

function saveDraft() {

    localStorage.setItem(

        "draft_order",

        JSON.stringify(cart)

    );

}

/*====================================================
=      LOAD DRAFT                                  =
====================================================*/

function loadDraft() {

    const draft =

        localStorage.getItem("draft_order");

    if (!draft) return;

    try {

        cart = JSON.parse(draft);

        renderCart();

        calculateTotals();

    }

    catch (e) {

        console.error(e);

        localStorage.removeItem("draft_order");

    }

}

/*====================================================
=      BEFORE LEAVING PAGE                          =
====================================================*/

window.addEventListener(

    "beforeunload",

    function (e) {

        if (cart.length === 0) return;

        saveDraft();

    }

);

/*====================================================
=      PAGE INITIALIZATION                          =
====================================================*/

$(document).ready(function () {

    loadDraft();

    calculateTotals();

    loadProducts();

});

/*====================================================
=      KEYBOARD SHORTCUTS                           =
====================================================*/

$(document).keydown(function (e) {

    /*
    |--------------------------------------------------------------------------
    | F2 = Product Search
    |--------------------------------------------------------------------------
    */

    if (e.key === "F2") {

        e.preventDefault();

        $("#productSearch").focus();

    }

    /*
    |--------------------------------------------------------------------------
    | F4 = Checkout
    |--------------------------------------------------------------------------
    */

    if (e.key === "F4") {

        e.preventDefault();

        $("#checkoutBtn").click();

    }

    /*
    |--------------------------------------------------------------------------
    | ESC = Clear Search
    |--------------------------------------------------------------------------
    */

    if (e.key === "Escape") {

        $("#productSearch").val("");

        $("#barcodeSearch").val("");

        applyFilters();

    }

});

/*====================================================
=      END OF POS SYSTEM JAVASCRIPT                 =
====================================================*/



</script>




