<?php
require_once __DIR__ . "/../includes/header.php";
?>

<section class="w-full">

    <!-- ========================================= -->
    <!-- PAGE HEADER -->
    <!-- ========================================= -->

    <article class="mb-6">

        <div class="flex items-center justify-between">

            <div>

                <h1 class="text-2xl font-bold font-title">
                    Create Order
                </h1>

                <p class="text-gray-500 mt-1">
                    Create a new customer order and generate receipt.
                </p>

            </div>

            <div class="flex gap-3">

                <button
                    id="refreshProducts"
                    class="px-5 py-3 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700 transition">

                    Refresh Products

                </button>

                <button
                    id="clearOrder"
                    class="px-5 py-3 rounded-xl bg-red-500 text-white hover:bg-red-600 transition">

                    Clear Order

                </button>

            </div>

        </div>

    </article>

    <!-- ========================================= -->
    <!-- RESPONSE MESSAGE -->
    <!-- ========================================= -->

    <div
        id="responseBox"
        class="hidden mb-6 rounded-xl px-5 py-4 text-sm font-medium">
    </div>

    <!-- ========================================= -->
    <!-- ORDER STATISTICS -->
    <!-- ========================================= -->

    <article
        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">

        <!-- Products -->

        <div class="bg-white rounded-2xl shadow border border-gray-200 p-6">

            <p class="text-sm text-gray-500">
                Products in Cart
            </p>

            <h2
                id="cartItemsCount"
                class="text-3xl font-bold mt-2 text-indigo-600">

                0

            </h2>

        </div>

        <!-- Quantity -->

        <div class="bg-white rounded-2xl shadow border border-gray-200 p-6">

            <p class="text-sm text-gray-500">
                Total Quantity
            </p>

            <h2
                id="cartQuantity"
                class="text-3xl font-bold mt-2 text-green-600">

                0

            </h2>

        </div>

        <!-- Total -->

        <div class="bg-white rounded-2xl shadow border border-gray-200 p-6">

            <p class="text-sm text-gray-500">
                Order Total
            </p>

            <h2
                id="cartTotal"
                class="text-3xl font-bold mt-2 text-blue-600">

                ₦0.00

            </h2>

        </div>

        <!-- Balance -->

        <div class="bg-white rounded-2xl shadow border border-gray-200 p-6">

            <p class="text-sm text-gray-500">
                Balance Due
            </p>

            <h2
                id="balanceDue"
                class="text-3xl font-bold mt-2 text-red-600">

                ₦0.00

            </h2>

        </div>

    </article>

    <!-- ========================================= -->
    <!-- CUSTOMER INFORMATION -->
    <!-- ========================================= -->

    <article
        class="bg-white rounded-2xl border border-gray-200 shadow-lg overflow-hidden">

        <!-- Header -->

        <div class="border-b border-gray-200 px-6 py-5">

            <h2 class="text-lg font-semibold">

                Customer Information

            </h2>

            <p class="text-sm text-gray-500 mt-1">

                Enter customer details before creating the order.

            </p>

        </div>

        <!-- Body -->

        <div class="p-6">

            <div class="grid lg:grid-cols-2 gap-6">

                <!-- Customer Name -->

                <div>

                    <label class="block text-sm font-medium mb-2">

                        Customer Name *

                    </label>

                    <input
                        type="text"
                        id="customerName"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-indigo-500"
                        placeholder="Enter customer name">

                </div>

                <!-- Phone -->

                <div>

                    <label class="block text-sm font-medium mb-2">

                        Phone Number *

                    </label>

                    <input
                        type="text"
                        id="customerPhone"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-indigo-500"
                        placeholder="08012345678">

                </div>

                <!-- Email -->

                <div>

                    <label class="block text-sm font-medium mb-2">

                        Email Address

                    </label>

                    <input
                        type="email"
                        id="customerEmail"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-indigo-500"
                        placeholder="customer@email.com">

                </div>

                <!-- Payment -->

                <div>

                    <label class="block text-sm font-medium mb-2">

                        Payment Method *

                    </label>

                    <select
                        id="paymentMethod"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3">

                        <option value="">
                            Select Payment Method
                        </option>

                        <option value="Cash">
                            Cash
                        </option>

                        <option value="POS">
                            POS
                        </option>

                        <option value="Transfer">
                            Bank Transfer
                        </option>

                        <option value="Card">
                            Card
                        </option>

                        <option value="Credit">
                            Credit
                        </option>

                    </select>

                </div>

                <!-- Notes -->

                <div class="lg:col-span-2">

                    <label class="block text-sm font-medium mb-2">

                        Notes

                    </label>

                    <textarea
                        id="orderNotes"
                        rows="4"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-indigo-500"
                        placeholder="Additional order notes..."></textarea>

                </div>

            </div>

        </div>

    </article>


    <!-- ===================================================== -->
    <!-- PRODUCT SEARCH -->
    <!-- ===================================================== -->

    <section class="bg-white rounded-2xl shadow border border-gray-200 mt-8">

        <div class="border-b px-6 py-5">

            <div class="flex items-center justify-between">

                <div>

                    <h2 class="text-lg font-semibold">

                        Search Products

                    </h2>

                    <p class="text-sm text-gray-500 mt-1">

                        Search products by name, barcode or category.

                    </p>

                </div>

                <div>

                    <span
                        id="productCount"
                        class="px-4 py-2 rounded-full bg-indigo-100 text-indigo-700 font-semibold">

                        0 Products

                    </span>

                </div>

            </div>

        </div>

        <div class="p-6">

            <div class="grid lg:grid-cols-4 gap-5">

                <div>

                    <label class="block mb-2 font-medium text-sm">

                        Search

                    </label>

                    <input
                        type="text"
                        id="productSearch"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3"
                        placeholder="Search Product">

                </div>

                <div>

                    <label class="block mb-2 font-medium text-sm">

                        Barcode

                    </label>

                    <input
                        type="text"
                        id="barcodeSearch"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3"
                        placeholder="Scan Barcode">

                </div>

                <div>

                    <label class="block mb-2 font-medium text-sm">

                        Category

                    </label>

                    <select
                        id="categoryFilter"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3">

                        <option value="">All Categories</option>

                    </select>

                </div>

                <div class="flex items-end gap-3">

                    <button
                        id="searchBtn"
                        class="flex-1 py-3 rounded-xl bg-indigo-600 text-white">

                        Search

                    </button>

                    <button
                        id="clearSearch"
                        class="flex-1 py-3 rounded-xl bg-red-500 text-white">

                        Clear

                    </button>

                </div>

            </div>

        </div>

    </section>



    <!-- ===================================================== -->
    <!-- PRODUCTS TABLE -->
    <!-- ===================================================== -->

    <section
        class="bg-white rounded-2xl shadow border border-gray-200 mt-8 overflow-hidden">

        <div class="border-b px-6 py-4">

            <div class="flex items-center justify-between">

                <div>

                    <h2 class="font-semibold text-lg">

                        Available Products

                    </h2>

                    <p class="text-sm text-gray-500 mt-1">

                        Select products to add into cart.

                    </p>

                </div>

                <div>

                    <span
                        id="availableCount"
                        class="px-4 py-2 rounded-full bg-green-100 text-green-700 font-semibold">

                        0 Available

                    </span>

                </div>

            </div>

        </div>


        <!-- Loader -->

        <div
            id="productLoader"
            class="hidden py-16 text-center">

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

            <p class="mt-4 text-gray-500">

                Loading Products...

            </p>

        </div>


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

                        <th class="px-5 py-4 text-left">

                            Category

                        </th>

                        <th class="px-5 py-4 text-left">

                            Store

                        </th>

                        <th class="px-5 py-4 text-center">

                            Qty

                        </th>

                        <th class="px-5 py-4 text-right">

                            Price

                        </th>

                        <th class="px-5 py-4 text-center">

                            Status

                        </th>

                        <th class="px-5 py-4 text-center">

                            Action

                        </th>

                    </tr>

                </thead>

                <tbody
                    id="productsTable">

                    <tr>

                        <td
                            colspan="8"
                            class="py-16 text-center text-gray-500">

                            No products available.

                        </td>

                    </tr>

                </tbody>

            </table>

        </div>

    </section>



    <!-- ===================================================== -->
    <!-- PAGINATION -->
    <!-- ===================================================== -->

    <section
        class="bg-white rounded-2xl shadow border border-gray-200 mt-6 p-5">

        <div class="flex justify-between items-center">

            <div>

                Showing

                <span id="pageStart">0</span>

                -

                <span id="pageEnd">0</span>

                of

                <span id="totalProducts">0</span>

            </div>

            <div class="flex gap-2">

                <button
                    id="firstPage"
                    class="px-4 py-2 border rounded-lg">

                    First

                </button>

                <button
                    id="previousPage"
                    class="px-4 py-2 border rounded-lg">

                    Previous

                </button>

                <span
                    id="pageIndicator"
                    class="px-4 py-2 bg-indigo-600 rounded-lg text-white">

                    Page 1

                </span>

                <button
                    id="nextPage"
                    class="px-4 py-2 border rounded-lg">

                    Next

                </button>

                <button
                    id="lastPage"
                    class="px-4 py-2 border rounded-lg">

                    Last

                </button>

            </div>

        </div>

    </section>



    <!-- ===================================================== -->
    <!-- PRODUCT DETAILS MODAL -->
    <!-- ===================================================== -->

    <div
        id="productModal"
        class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50">

        <div
            class="bg-white rounded-2xl w-full max-w-5xl shadow-xl">

            <div
                class="flex items-center justify-between border-b px-6 py-4">

                <h3 class="text-xl font-semibold">

                    Product Details

                </h3>

                <button
                    id="closeProductModal"
                    class="text-3xl">

                    &times;

                </button>

            </div>

            <div
                id="productModalBody"
                class="p-6">

                Loading...

            </div>

        </div>

    </div>

    <!-- ===================================== -->
    <!-- SHOPPING CART -->
    <!-- ===================================== -->

    <section class="grid lg:grid-cols-3 gap-6 mt-8">

        <!-- ============================== -->
        <!-- CART ITEMS -->
        <!-- ============================== -->

        <article class="lg:col-span-2 bg-white rounded-2xl border border-gray-200 shadow-lg overflow-hidden">

            <div class="border-b border-gray-200 px-6 py-5 flex items-center justify-between">

                <div>

                    <h2 class="text-lg font-semibold">

                        Shopping Cart

                    </h2>

                    <p class="text-sm text-gray-500 mt-1">

                        Products added to this order.

                    </p>

                </div>

                <span
                    id="cartCount"
                    class="bg-indigo-100 text-indigo-700 px-4 py-2 rounded-full font-semibold">

                    0 Items

                </span>

            </div>

            <div class="overflow-x-auto">

                <table class="min-w-full">

                    <thead class="bg-gray-100">

                        <tr>

                            <th class="px-5 py-4 text-left">

                                Product

                            </th>

                            <th class="px-5 py-4 text-center">

                                Qty

                            </th>

                            <th class="px-5 py-4 text-right">

                                Price

                            </th>

                            <th class="px-5 py-4 text-right">

                                Total

                            </th>

                            <th class="px-5 py-4 text-center">

                                Action

                            </th>

                        </tr>

                    </thead>

                    <tbody
                        id="cartTable">

                        <tr id="emptyCart">

                            <td
                                colspan="5"
                                class="text-center py-16 text-gray-500">

                                No products added.

                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </article>

        <!-- ============================== -->
        <!-- ORDER SUMMARY -->
        <!-- ============================== -->

        <article class="bg-white rounded-2xl border border-gray-200 shadow-lg p-6">

            <h2 class="text-lg font-semibold mb-6">

                Order Summary

            </h2>

            <div class="space-y-4">

                <div class="flex justify-between">

                    <span>

                        Subtotal

                    </span>

                    <strong id="subtotal">

                        ₦0.00

                    </strong>

                </div>

                <div>

                    <label class="block text-sm font-medium mb-2">

                        Discount

                    </label>

                    <input
                        type="number"
                        id="discount"
                        value="0"
                        min="0"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3">
                </div>

                <div>

                    <label class="block text-sm font-medium mb-2">

                        Tax

                    </label>

                    <input
                        type="number"
                        id="tax"
                        value="0"
                        min="0"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3">
                </div>

                <div>

                    <label class="block text-sm font-medium mb-2">

                        Shipping

                    </label>

                    <input
                        type="number"
                        id="shipping"
                        value="0"
                        min="0"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3">
                </div>

                <hr>

                <div class="flex justify-between text-lg font-bold">

                    <span>

                        Grand Total

                    </span>

                    <span
                        id="grandTotal"
                        class="text-indigo-700">

                        ₦0.00

                    </span>

                </div>

            </div>

        </article>

    </section>

    <!-- ===================================== -->
    <!-- PAYMENT -->
    <!-- ===================================== -->

    <section class="grid lg:grid-cols-2 gap-6 mt-8">

        <article class="bg-white rounded-2xl border border-gray-200 shadow-lg p-6">

            <h2 class="text-lg font-semibold mb-6">

                Payment Information

            </h2>

            <div class="grid md:grid-cols-2 gap-5">

                <div>

                    <label class="block mb-2 text-sm font-medium">

                        Payment Method

                    </label>

                    <select
                        id="paymentMethod"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3">

                        <option value="Cash">

                            Cash

                        </option>

                        <option value="Transfer">

                            Transfer

                        </option>

                        <option value="POS">

                            POS

                        </option>

                        <option value="Card">

                            Card

                        </option>

                    </select>

                </div>

                <div>

                    <label class="block mb-2 text-sm font-medium">

                        Amount Paid

                    </label>

                    <input
                        type="number"
                        id="amountPaid"
                        value="0"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3">

                </div>

            </div>

            <div class="mt-5">

                <label class="block mb-2 text-sm font-medium">

                    Notes

                </label>

                <textarea
                    id="notes"
                    rows="4"
                    class="w-full rounded-xl border border-gray-300 px-4 py-3"
                    placeholder="Order notes..."></textarea>

            </div>

        </article>

        <!-- ============================== -->
        <!-- BALANCE -->
        <!-- ============================== -->

        <article class="bg-white rounded-2xl border border-gray-200 shadow-lg p-6">

            <h2 class="text-lg font-semibold mb-6">

                Payment Summary

            </h2>

            <div class="space-y-5">

                <div class="flex justify-between">

                    <span>

                        Total Amount

                    </span>

                    <strong
                        id="summaryTotal">

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

                <hr>

                <div class="flex justify-between text-xl font-bold">

                    <span>

                        Balance

                    </span>

                    <span
                        id="balanceAmount"
                        class="text-red-600">

                        ₦0.00

                    </span>

                </div>

            </div>

        </article>

    </section>

    <!-- ===================================== -->
    <!-- ACTION BUTTONS -->
    <!-- ===================================== -->

    <section class="mt-8 flex justify-end gap-4">

        <button
            id="clearOrder"
            class="px-6 py-3 rounded-xl bg-gray-200 hover:bg-gray-300">

            Clear Order

        </button>

        <button
            id="saveDraft"
            class="px-6 py-3 rounded-xl bg-yellow-500 text-white hover:bg-yellow-600">

            Save Draft

        </button>

        <button
            id="createOrder"
            class="px-8 py-3 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700">

            Create Order

        </button>

    </section>



</section>

<?php
require_once __DIR__ . "/../includes/footer.php";
?>



<script>

/*
|--------------------------------------------------------------------------
| PART 3A
| API CONFIGURATION
|--------------------------------------------------------------------------
*/

const API_BASE = window.API_BASE || "/increasestore/e-api";

const PRODUCTS_API =
    API_BASE + "/dashboard/accepted-products/list.php";

const PRODUCT_DETAILS_API =
    API_BASE + "/dashboard/accepted-products/view.php";

/*
|--------------------------------------------------------------------------
| AUTHENTICATION
|--------------------------------------------------------------------------
*/

const token = localStorage.getItem("auth_token");

if (!token) {

    window.location.href = "../login.php";

}

/*
|--------------------------------------------------------------------------
| GLOBAL VARIABLES
|--------------------------------------------------------------------------
*/

let products = [];

let filteredProducts = [];

let cart = JSON.parse(
    localStorage.getItem("order_cart") || "[]"
);

let currentPage = 1;

let rowsPerPage = 10;

let totalPages = 1;

let totalProducts = 0;

let selectedProduct = null;

/*
|--------------------------------------------------------------------------
| DOM ELEMENTS
|--------------------------------------------------------------------------
*/

const productSearch = $("#productSearch");

const barcodeSearch = $("#barcodeSearch");

const categoryFilter = $("#categoryFilter");

const productsTable = $("#productsTable");

const productLoader = $("#productLoader");

const productModal = $("#productModal");

const productModalBody = $("#productModalBody");

const productCount = $("#productCount");

const availableCount = $("#availableCount");

const pageIndicator = $("#pageIndicator");

const pageStart = $("#pageStart");

const pageEnd = $("#pageEnd");

const totalProductsText = $("#totalProducts");

const responseBox = $("#responseBox");

/*
|--------------------------------------------------------------------------
| LOADER
|--------------------------------------------------------------------------
*/

function showLoader() {

    productLoader.removeClass("hidden");

}

function hideLoader() {

    productLoader.addClass("hidden");

}

/*
|--------------------------------------------------------------------------
| RESPONSE MESSAGE
|--------------------------------------------------------------------------
*/

function showMessage(type, message) {

    responseBox
        .removeClass(
            "hidden bg-green-100 bg-red-100 text-green-700 text-red-700"
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

    setTimeout(function () {

        responseBox.addClass("hidden");

    }, 4000);

}

/*
|--------------------------------------------------------------------------
| FORMAT MONEY
|--------------------------------------------------------------------------
*/

function formatMoney(amount) {

    return Number(amount).toLocaleString(
        "en-NG",
        {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }
    );

}

/*
|--------------------------------------------------------------------------
| ESCAPE HTML
|--------------------------------------------------------------------------
*/

function escapeHtml(text) {

    if (text === null || text === undefined) {

        return "";

    }

    return $("<div>")
        .text(text)
        .html();

}


/*
|--------------------------------------------------------------------------
| PART 3B
| LOAD PRODUCTS
|--------------------------------------------------------------------------
*/

function loadProducts(page = 1) {

    currentPage = page;

    showLoader();

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

            barcode: barcodeSearch.val().trim(),

            category: categoryFilter.val(),

            sort_by: "name",

            sort_order: "ASC"

        },

        success: function(response) {

            hideLoader();

            if (!response.status) {

                showMessage(
                    "error",
                    response.message
                );

                productsTable.html(`

                    <tr>

                        <td
                            colspan="8"
                            class="text-center py-16 text-red-500">

                            ${response.message}

                        </td>

                    </tr>

                `);

                return;

            }

            products = response.data || [];

            filteredProducts = products;

            totalProducts =
                response.pagination.total;

            totalPages =
                response.pagination.total_pages;

            renderProducts();

            updatePagination();

        },

        error: function(xhr) {

            hideLoader();

            console.error(xhr);

            if (xhr.status === 401) {

                localStorage.removeItem("auth_token");

                window.location.href = "../login.php";

                return;

            }

            showMessage(
                "error",
                "Unable to load products."
            );

        }

    });

}

/*
|--------------------------------------------------------------------------
| SEARCH
|--------------------------------------------------------------------------
*/

$("#searchBtn").on("click", function () {

    loadProducts(1);

});

productSearch.on("keypress", function (e) {

    if (e.which === 13) {

        loadProducts(1);

    }

});

/*
|--------------------------------------------------------------------------
| AUTO SEARCH
|--------------------------------------------------------------------------
*/

let searchTimeout = null;

productSearch.on("keyup", function () {

    clearTimeout(searchTimeout);

    searchTimeout = setTimeout(function () {

        loadProducts(1);

    }, 400);

});

/*
|--------------------------------------------------------------------------
| BARCODE SEARCH
|--------------------------------------------------------------------------
*/

barcodeSearch.on("keypress", function (e) {

    if (e.which !== 13) {

        return;

    }

    loadProducts(1);

});

/*
|--------------------------------------------------------------------------
| CATEGORY FILTER
|--------------------------------------------------------------------------
*/

categoryFilter.on("change", function () {

    loadProducts(1);

});

/*
|--------------------------------------------------------------------------
| REFRESH
|--------------------------------------------------------------------------
*/

$("#refreshProducts").on("click", function () {

    loadProducts(currentPage);

});

/*
|--------------------------------------------------------------------------
| CLEAR SEARCH
|--------------------------------------------------------------------------
*/

$("#clearSearch").on("click", function () {

    productSearch.val("");

    barcodeSearch.val("");

    categoryFilter.val("");

    loadProducts(1);

});

/*
|--------------------------------------------------------------------------
| INITIAL PAGE LOAD
|--------------------------------------------------------------------------
*/

$(function () {

    loadProducts();

});


/*
|--------------------------------------------------------------------------
| PART 3C
| RENDER PRODUCTS
|--------------------------------------------------------------------------
*/

function renderProducts() {

    if (!products.length) {

        productsTable.html(`

            <tr>

                <td
                    colspan="8"
                    class="py-16 text-center text-gray-500">

                    No products available.

                </td>

            </tr>

        `);

        productCount.text("0 Products");

        availableCount.text("0 Available");

        return;

    }

    let html = "";

    products.forEach(function(item) {

        let badge = `
            <span
                class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs">

                Available

            </span>
        `;

        if (Number(item.quantity) <= 0) {

            badge = `
                <span
                    class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs">

                    Out of Stock

                </span>
            `;

        } else if (

            item.product.minimum_stock &&
            Number(item.quantity) <= Number(item.product.minimum_stock)

        ) {

            badge = `
                <span
                    class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs">

                    Low Stock

                </span>
            `;

        }

        html += `

        <tr class="border-b hover:bg-gray-50 transition">

            <td class="px-5 py-4">

                <div>

                    <div class="font-semibold">

                        ${escapeHtml(item.product.name)}

                    </div>

                    <div class="text-xs text-gray-500">

                        ${escapeHtml(item.product.unit)}

                    </div>

                </div>

            </td>

            <td class="px-5 py-4">

                ${escapeHtml(item.product.barcode)}

            </td>

            <td class="px-5 py-4">

                ${escapeHtml(item.product.category)}

            </td>

            <td class="px-5 py-4">

                ${escapeHtml(item.store.name)}

            </td>

            <td class="px-5 py-4 text-center font-semibold">

                ${item.quantity}

            </td>

            <td class="px-5 py-4 text-right font-semibold text-green-700">

                ₦${formatMoney(item.product.selling_price)}

            </td>

            <td class="px-5 py-4 text-center">

                ${badge}

            </td>

            <td class="px-5 py-4">

                <div class="flex justify-center gap-2">

                    <button

                        class="viewProduct
                               px-3
                               py-2
                               rounded-lg
                               bg-slate-100
                               hover:bg-slate-200"

                        data-id="${item.inventory_id}">

                        View

                    </button>

                    <button

                        class="addToCart
                               px-3
                               py-2
                               rounded-lg
                               bg-indigo-600
                               text-white
                               hover:bg-indigo-700"

                        data-id="${item.inventory_id}">

                        Add

                    </button>

                </div>

            </td>

        </tr>

        `;

    });

    productsTable.html(html);

    productCount.text(totalProducts + " Products");

    availableCount.text(products.length + " Available");

}

/*
|--------------------------------------------------------------------------
| UPDATE PAGINATION
|--------------------------------------------------------------------------
*/

function updatePagination() {

    pageIndicator.text(

        "Page " +
        currentPage +
        " of " +
        totalPages

    );

    pageStart.text(

        totalProducts === 0
            ? 0
            : ((currentPage - 1) * rowsPerPage) + 1

    );

    pageEnd.text(

        Math.min(

            currentPage * rowsPerPage,

            totalProducts

        )

    );

    totalProductsText.text(totalProducts);

}


/*
|--------------------------------------------------------------------------
| PART 3D
| PRODUCT DETAILS MODAL
|--------------------------------------------------------------------------
*/

$(document).on("click", ".viewProduct", function () {

    const inventoryId = $(this).data("id");

    loadProductDetails(inventoryId);

});

/*
|--------------------------------------------------------------------------
| LOAD PRODUCT DETAILS
|--------------------------------------------------------------------------
*/

function loadProductDetails(inventoryId) {

    productModal
        .removeClass("hidden")
        .addClass("flex");

    productModalBody.html(`

        <div class="py-16 text-center">

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

            <p class="mt-4 text-gray-500">

                Loading Product...

            </p>

        </div>

    `);

    $.ajax({

        url: PRODUCT_DETAILS_API,

        type: "GET",

        dataType: "json",

        headers: {

            Authorization: "Bearer " + token

        },

        data: {

            id: inventoryId

        },

        success: function(response) {

            if (!response.status) {

                productModalBody.html(`

                    <div class="text-center py-10 text-red-500">

                        ${response.message}

                    </div>

                `);

                return;

            }

            const product = response.data.product;

            const store = response.data.store;

            const quantity = response.data.quantity;

            productModalBody.html(`

<div class="grid lg:grid-cols-2 gap-8">

    <div>

        <h3 class="text-lg font-semibold mb-5">

            Product Information

        </h3>

        <table class="w-full text-sm">

            <tr>

                <td class="py-2 font-medium">

                    Product

                </td>

                <td>

                    ${escapeHtml(product.name)}

                </td>

            </tr>

            <tr>

                <td class="py-2 font-medium">

                    Barcode

                </td>

                <td>

                    ${escapeHtml(product.barcode)}

                </td>

            </tr>

            <tr>

                <td class="py-2 font-medium">

                    SKU

                </td>

                <td>

                    ${escapeHtml(product.sku)}

                </td>

            </tr>

            <tr>

                <td class="py-2 font-medium">

                    Category

                </td>

                <td>

                    ${escapeHtml(product.category)}

                </td>

            </tr>

            <tr>

                <td class="py-2 font-medium">

                    Unit

                </td>

                <td>

                    ${escapeHtml(product.unit)}

                </td>

            </tr>

            <tr>

                <td class="py-2 font-medium">

                    Cost Price

                </td>

                <td>

                    ₦${formatMoney(product.cost_price)}

                </td>

            </tr>

            <tr>

                <td class="py-2 font-medium">

                    Selling Price

                </td>

                <td>

                    ₦${formatMoney(product.selling_price)}

                </td>

            </tr>

            <tr>

                <td class="py-2 font-medium">

                    Available Qty

                </td>

                <td>

                    ${quantity}

                </td>

            </tr>

            <tr>

                <td class="py-2 font-medium">

                    Description

                </td>

                <td>

                    ${product.description || "-"}

                </td>

            </tr>

        </table>

    </div>

    <div>

        <h3 class="text-lg font-semibold mb-5">

            Store Information

        </h3>

        <table class="w-full text-sm">

            <tr>

                <td class="py-2 font-medium">

                    Store

                </td>

                <td>

                    ${escapeHtml(store.name)}

                </td>

            </tr>

            <tr>

                <td class="py-2 font-medium">

                    Address

                </td>

                <td>

                    ${store.address || "-"}

                </td>

            </tr>

            <tr>

                <td class="py-2 font-medium">

                    Phone

                </td>

                <td>

                    ${store.phone || "-"}

                </td>

            </tr>

            <tr>

                <td class="py-2 font-medium">

                    Email

                </td>

                <td>

                    ${store.email || "-"}

                </td>

            </tr>

            <tr>

                <td class="py-2 font-medium">

                    Inventory ID

                </td>

                <td>

                    ${response.data.inventory_id}

                </td>

            </tr>

            <tr>

                <td class="py-2 font-medium">

                    Last Updated

                </td>

                <td>

                    ${response.data.updated_at}

                </td>

            </tr>

        </table>

    </div>

</div>

            `);

        },

        error: function(xhr) {

            productModalBody.html(`

                <div class="text-center py-10 text-red-500">

                    ${xhr.responseJSON?.message || "Unable to load product details."}

                </div>

            `);

        }

    });

}

/*
|--------------------------------------------------------------------------
| CLOSE PRODUCT MODAL
|--------------------------------------------------------------------------
*/

$("#closeProductModal").on("click", function () {

    productModal
        .removeClass("flex")
        .addClass("hidden");

});

productModal.on("click", function(e){

    if(e.target === this){

        productModal
            .removeClass("flex")
            .addClass("hidden");

    }

});



////teste


/*
|--------------------------------------------------------------------------
| HELPERS
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Response Alert
|--------------------------------------------------------------------------
*/

function showMessage(type, message) {

    const box = $("#responseBox");

    box.removeClass("hidden");

    box.removeClass(
        "bg-green-100 text-green-700",
        "bg-red-100 text-red-700",
        "bg-yellow-100 text-yellow-700"
    );

    if (type === "success") {

        box.addClass("bg-green-100 text-green-700");

    } else if (type === "warning") {

        box.addClass("bg-yellow-100 text-yellow-700");

    } else {

        box.addClass("bg-red-100 text-red-700");

    }

    box.html(message);

    setTimeout(function () {

        box.addClass("hidden");

    }, 4000);

}

/*
|--------------------------------------------------------------------------
| Loader
|--------------------------------------------------------------------------
*/

function showLoader() {

    $("#productLoader").removeClass("hidden");

}

function hideLoader() {

    $("#productLoader").addClass("hidden");

}

/*
|--------------------------------------------------------------------------
| Format Money
|--------------------------------------------------------------------------
*/

function formatMoney(amount) {

    return Number(amount || 0).toLocaleString("en-NG", {

        minimumFractionDigits: 2,

        maximumFractionDigits: 2

    });

}

/*
|--------------------------------------------------------------------------
| Parse Number
|--------------------------------------------------------------------------
*/

function number(value) {

    return Number(value || 0);

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
| Empty Value
|--------------------------------------------------------------------------
*/

function empty(value) {

    return value === null ||
        value === undefined ||
        value === "";

}

/*
|--------------------------------------------------------------------------
| Generate Badge
|--------------------------------------------------------------------------
*/

function stockBadge(quantity, minimumStock = 0) {

    quantity = Number(quantity);

    minimumStock = Number(minimumStock);

    if (quantity <= 0) {

        return `
            <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs">
                Out of Stock
            </span>
        `;

    }

    if (minimumStock > 0 && quantity <= minimumStock) {

        return `
            <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs">
                Low Stock
            </span>
        `;

    }

    return `
        <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs">
            Available
        </span>
    `;

}

/*
|--------------------------------------------------------------------------
| Reset Customer Form
|--------------------------------------------------------------------------
*/

function resetCustomerForm() {

    $("#customerName").val("");

    $("#customerPhone").val("");

    $("#customerEmail").val("");

    $("#paymentMethod").val("");

    $("#discount").val(0);

    $("#tax").val(0);

    $("#shipping").val(0);

    $("#amountPaid").val(0);

    $("#notes").val("");

}

/*
|--------------------------------------------------------------------------
| Clear Product Search
|--------------------------------------------------------------------------
*/

function clearSearch() {

    $("#productSearch").val("");

    $("#barcodeSearch").val("");

    $("#categoryFilter").val("");

    $("#storeFilter").val("");

    $("#stockFilter").val("");

    $("#sortProducts").val("name");

}

/*
|--------------------------------------------------------------------------
| Reset Entire Order
|--------------------------------------------------------------------------
*/

function resetOrder() {

    cart = [];

    saveCart();

    renderCart();

    updateTotals();

    resetCustomerForm();

}

/*
|--------------------------------------------------------------------------
| Update Statistics Cards
|--------------------------------------------------------------------------
*/

function updateStatistics() {

    let totalItems = 0;

    let subtotal = 0;

    cart.forEach(function(item){

        totalItems += Number(item.quantity);

        subtotal += Number(item.quantity) *
                    Number(item.selling_price);

    });

    $("#cartItems").text(totalItems);

    $("#cartSubtotal").text("₦" + formatMoney(subtotal));

    $("#availableProducts").text(products.length);

}

/*
|--------------------------------------------------------------------------
| Save Cart
|--------------------------------------------------------------------------
*/

function saveCart() {

    localStorage.setItem(
        "order_cart",
        JSON.stringify(cart)
    );

}

/*
|--------------------------------------------------------------------------
| Load Cart
|--------------------------------------------------------------------------
*/

function loadCart() {

    cart = JSON.parse(
        localStorage.getItem("order_cart") || "[]"
    );

}

/*
|--------------------------------------------------------------------------
| Find Cart Item
|--------------------------------------------------------------------------
*/

function getCartItem(inventoryId) {

    return cart.find(function(item){

        return Number(item.inventory_id) === Number(inventoryId);

    });

}

/*
|--------------------------------------------------------------------------
| Calculate Totals
|--------------------------------------------------------------------------
*/

function calculateTotals() {

    let subtotal = 0;

    cart.forEach(function(item){

        subtotal += Number(item.quantity) *
                    Number(item.selling_price);

    });

    const discount = number($("#discount").val());

    const tax = number($("#tax").val());

    const shipping = number($("#shipping").val());

    const total = subtotal - discount + tax + shipping;

    const paid = number($("#amountPaid").val());

    const balance = total - paid;

    return {

        subtotal,

        discount,

        tax,

        shipping,

        total,

        paid,

        balance

    };

}

/*
|--------------------------------------------------------------------------
| Update Totals UI
|--------------------------------------------------------------------------
*/

function updateTotals() {

    const totals = calculateTotals();

    $("#subtotal").text(
        "₦" + formatMoney(totals.subtotal)
    );

    $("#totalAmount").text(
        "₦" + formatMoney(totals.total)
    );

    $("#balance").text(
        "₦" + formatMoney(totals.balance)
    );

    updateStatistics();

}


/*
|--------------------------------------------------------------------------
| Add Product To Cart Button
|--------------------------------------------------------------------------
*/

$(document).on("click", ".addToCart", function () {

    console.log("Add button clicked");

    const productId = $(this).data("id");

    console.log("Product ID:", productId);


    const product = products.find(function(item){

        return Number(item.inventory_id) === Number(productId);

    });


    console.log("Selected Product:", product);


    if (!product) {

        showMessage(
            "error",
            "Product not found"
        );

        return;

    }


    const existing = getCartItem(product.inventory_id);


    // Prevent duplicate product
    if (existing) {

        showMessage(
            "warning",
            "Product already exists in cart"
        );

        return;

    }


    cart.push({

        inventory_id: product.inventory_id,

        name: product.name,

        barcode: product.barcode,

        selling_price: product.selling_price,

        available_quantity: product.quantity,

        quantity: 1

    });


    console.log("Updated Cart:", cart);


    saveCart();

    renderCart();

    updateTotals();


    showMessage(
        "success",
        "Product added to cart successfully"
    );

});

function renderCart() {
    console.log("renderCart function called");


    const cartBody = $("#cartTable");

    console.log("Cart body:", cartBody);


    console.log("Current cart:", cart);


    cartBody.empty();

    console.log("Rendering Cart:", cart);


    if(cart.length === 0){

        cartBody.html(`

        <tr id="emptyCart">

            <td colspan="5"
                class="text-center py-16 text-gray-500">

                No products added.

            </td>

        </tr>

        `);


        updateSummary();

        return;

    }



    cart.forEach(function(item,index){


        console.log("Rendering Item:", item);



        const lineTotal =
            Number(item.quantity) *
            Number(item.selling_price);



        cartBody.append(`

        <tr class="border-b">

            <td class="px-5 py-4">

                ${item.name}

            </td>


            <td class="px-5 py-4 text-center">

                ${item.quantity}

            </td>


            <td class="px-5 py-4 text-right">

                ₦${formatMoney(item.selling_price)}

            </td>


            <td class="px-5 py-4 text-right">

                ₦${formatMoney(lineTotal)}

            </td>


            <td class="px-5 py-4 text-center">

                <button 
                class="removeItem bg-red-500 text-white px-3 py-2 rounded"
                data-index="${index}">

                Remove

                </button>

            </td>


        </tr>

        `);


    });



    updateSummary();

} 

/*
|--------------------------------------------------------------------------
| CALCULATE TOTALS
|--------------------------------------------------------------------------
*/

function calculateTotals() {

    let subtotal = 0;


    cart.forEach(function(item) {

        const price = Number(item.selling_price) || 0;

        const quantity = Number(item.quantity) || 0;


        subtotal += price * quantity;

    });


    const discount =
        Number($("#discount").val()) || 0;


    const tax =
        Number($("#tax").val()) || 0;


    const shipping =
        Number($("#shipping").val()) || 0;


    const grandTotal =
        (subtotal - discount) + tax + shipping;


    const amountPaid =
        Number($("#amountPaid").val()) || 0;


    const balance =
        grandTotal - amountPaid;



    return {

        subtotal,

        discount,

        tax,

        shipping,

        grandTotal,

        amountPaid,

        balance

    };

}


/*
|--------------------------------------------------------------------------
| UPDATE SUMMARY
|--------------------------------------------------------------------------
*/

function updateSummary() {

    const totals = calculateTotals();


    // Subtotal
    $("#subtotal").text(
        "₦" + formatMoney(totals.subtotal)
    );


    // Grand Total
    $("#grandTotal").text(
        "₦" + formatMoney(totals.grandTotal)
    );


    // Number of different products in cart
    $("#cartCount").text(
        cart.length
    );


    // Total quantity of all products
    let qty = 0;


    cart.forEach(function(item){

        qty += Number(item.quantity) || 0;

    });


    $("#totalQty").text(qty);


    // Balance
    $("#balance").text(
        "₦" + formatMoney(totals.balance)
    );


}


/*
|--------------------------------------------------------------------------
| AUTO UPDATE TOTALS
|--------------------------------------------------------------------------
*/

$("#discount, #tax, #shipping, #amountPaid")
.on("keyup change", function () {

    console.log("Updating totals...");

    updateSummary();

});









</script>