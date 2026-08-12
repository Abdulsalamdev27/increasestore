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

                <div class="flex items-center gap-3">

                    <button
                        id="clearCart"
                        class="px-4 py-2 rounded-lg bg-red-500 hover:bg-red-600 text-white">

                        Remove All

                    </button>

                    <span
                        id="cartCount"
                        class="bg-indigo-100 text-indigo-700 px-4 py-2 rounded-full font-semibold">

                        0 Items

                    </span>

                </div>

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

    <!-- ===================================== -->
    <!-- PAYMENT + CUSTOMER -->
    <!-- ===================================== -->

    <article class="bg-white rounded-2xl border border-gray-200 shadow-lg p-6">

        <div class="flex items-center justify-between mb-6">

            <div>

                <h2 class="text-lg font-semibold">
                    Payment Information
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Customer details and payment information.
                </p>

            </div>

        </div>

        <!-- RESPONSE -->

        <div
            id="responseBox"
            class="hidden rounded-xl px-4 py-3 mb-5">
        </div>

        <!-- ============================== -->
        <!-- CUSTOMER DETAILS -->
        <!-- ============================== -->

        <div class="rounded-xl border border-gray-200 bg-gray-50 p-5 mb-6">

            <div class="flex items-center justify-between mb-4">

                <h3 class="font-semibold">
                    Customer Details
                </h3>

                <span
                    id="customerStatus"
                    class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs">

                    Walk-in Customer

                </span>

            </div>

            <div class="grid md:grid-cols-2 gap-5">

                <div>

                    <label class="block mb-2 text-sm font-medium">
                        Customer Name
                    </label>

                    <input
                        type="text"
                        id="customerName"
                        placeholder="Walk-in Customer"
                        class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3">

                </div>

                <div>

                    <label class="block mb-2 text-sm font-medium">
                        Phone Number
                    </label>

                    <input
                        type="text"
                        id="customerPhone"
                        placeholder="080xxxxxxxx"
                        class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3">

                </div>

                <div>

                    <label class="block mb-2 text-sm font-medium">
                        Email Address
                    </label>

                    <input
                        type="email"
                        id="customerEmail"
                        placeholder="customer@email.com"
                        class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3">

                </div>

                <div>

                    <label class="block mb-2 text-sm font-medium">
                        Customer Code
                    </label>

                    <input
                        type="text"
                        id="customerCode"
                        class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3">

                </div>

            </div>

        </div>

        <!-- PAYMENT -->

        <div class="grid md:grid-cols-2 gap-5">

            <div>

                <label class="block mb-2 text-sm font-medium">
                    Payment Method
                </label>

                <select
                    id="paymentMethod"
                    class="w-full rounded-xl border border-gray-300 px-4 py-3">

                    <option value="cash">Cash</option>
                    <option value="transfer">Transfer</option>
                    <option value="pos">POS</option>
                    <option value="card">Card</option>
                    <option value="mobile_money">Mobile Money</option>
                    <option value="other">Other</option>

                </select>

            </div>

            <div>

                <label class="block mb-2 text-sm font-medium">
                    Payment Status
                </label>

                <input
                    type="text"
                    id="paymentStatus"
                    readonly
                    value="Pending"
                    class="w-full rounded-xl border border-gray-300 bg-gray-100 px-4 py-3">

            </div>

            <div>

                <label class="block mb-2 text-sm font-medium">
                    Amount Paid
                </label>

                <input
                    type="number"
                    id="amountPaid"
                    value="0"
                    min="0"
                    class="w-full rounded-xl border border-gray-300 px-4 py-3">

            </div>

            <!-- <div>

                <label class="block mb-2 text-sm font-medium">
                    Balance
                </label>

                <input
                    type="text"
                    id="balanceDisplay"
                    value="₦0.00"
                    class="w-full rounded-xl border border-gray-300 bg-gray-100 px-4 py-3">

            </div> -->

        </div>

        <div class="mt-5">

            <label class="block mb-2 text-sm font-medium">
                Notes
            </label>

            <textarea
                id="notes"
                rows="4"
                placeholder="Order notes..."
                class="w-full rounded-xl border border-gray-300 px-4 py-3"></textarea>

        </div>

        <!-- BUTTON -->

        <div class="mt-6">

            <button
                type="button"
                id="createOrderBtn"
                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl py-3 font-semibold">

                Create Order

            </button>

        </div>

    </article>

    <!-- ===================================== -->
    <!-- PAYMENT SUMMARY -->
    <!-- ===================================== -->

    <article class="bg-white rounded-2xl border border-gray-200 shadow-lg p-6">

        <h2 class="text-lg font-semibold mb-6">
            Payment Summary
        </h2>

        <div class="space-y-5">

            <div class="flex justify-between">

                <span>Total Amount</span>

                <strong id="summaryTotal">
                    ₦0.00
                </strong>

            </div>

            <div class="flex justify-between">

                <span>Amount Paid</span>

                <strong id="summaryPaid">
                    ₦0.00
                </strong>

            </div>

            <hr>

            <div class="flex justify-between text-xl font-bold">

                <span>Balance</span>

                <span
                    id="balanceAmount"
                    class="text-red-600">

                    ₦0.00

                </span>

            </div>

        </div>

    </article>

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

// function showMessage(type, message) {

//     responseBox
//         .removeClass(
//             "hidden bg-green-100 bg-red-100 text-green-700 text-red-700"
//         );

//     if (type === "success") {

//         responseBox.addClass(
//             "bg-green-100 text-green-700"
//         );

//     } else {

//         responseBox.addClass(
//             "bg-red-100 text-red-700"
//         );

//     }

//     responseBox.text(message);

//     setTimeout(function () {

//         responseBox.addClass("hidden");

//     }, 4000);

// }

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

    const savedCart = localStorage.getItem("order_cart");

    if (savedCart) {

        cart = JSON.parse(savedCart);

    } else {

        cart = [];

    }

    console.log("Cart Loaded:", cart);

}


//clear cart

$(document).on("click", "#clearCart", function () {

    if (cart.length === 0) {

        showMessage(
            "warning",
            "Your cart is already empty."
        );

        return;

    }

    if (!confirm("Are you sure you want to remove all items from the cart?")) {

        return;

    }

    console.log("Clearing Cart:", cart);

    cart = [];

    saveCart();

    renderCart();

    updateSummary();

    console.log("Cart Cleared:", cart);

    showMessage(
        "success",
        "All products have been removed from the cart."
    );

});

$(document).ready(function () {

    loadCart();

    renderCart();

    updateSummary();

});


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
| ADD PRODUCT TO CART
|--------------------------------------------------------------------------
*/

$(document).on("click", ".addToCart", function () {

    console.log("Add button clicked");

    const productId = Number($(this).data("id"));

    console.log("Product ID:", productId);


    const product = products.find(function (item) {

        return Number(item.inventory_id) === productId;

    });


    console.log("Selected Product:", product);

console.log("Full Product:", product);
console.log("All Keys:", Object.keys(product || {}));


    if (!product) {

        showMessage(
            "error",
            "Product not found"
        );

        return;

    }


    console.log("Product Name:", product.name);
    console.log("Selling Price:", product.selling_price);
    console.log("Quantity:", product.quantity);
    console.log("Full Product:", product);


    const existing = getCartItem(product.inventory_id);


    if (existing) {

        showMessage(
            "warning",
            "Product already exists in cart"
        );

        return;

    }


    const cartItem = {
        inventory_id: Number(product.inventory_id),

        product_id: Number(
            product.product?.id ??
            product.product_id ??
            0
        ),

        store_id: Number(
            product.store?.id ??
            product.store_id ??
            0
        ),

        name:
            product.product?.name ??
            product.name ??
            "",

        barcode:
            product.product?.barcode ??
            product.barcode ??
            "",

        selling_price: Number(
            product.product?.selling_price ??
            product.selling_price ??
            0
        ),

        available_quantity: Number(
            product.quantity ?? 0
        ),

        quantity: 1,

        store_name:
            product.store?.name ??
            product.store_name ??
            ""
    };


    console.log("Cart Item:", cartItem);


    cart.push(cartItem);

    console.log("Updated Cart:", cart);


    saveCart();


    if (typeof renderCart === "function") {

        console.log("Calling renderCart()");

        renderCart();

    } else {

        console.error("renderCart is not defined");

    }


    updateSummary();


    showMessage(
        "success",
        "Product added to cart successfully"
    );

});

function renderCart() {

    const cartBody = $("#cartTable");

    cartBody.empty();

    if (cart.length === 0) {

        cartBody.html(`

        <tr id="emptyCart">

            <td colspan="5" class="text-center py-16 text-gray-500">

                No products added.

            </td>

        </tr>

        `);

        updateSummary();

        return;

    }

    cart.forEach(function(item, index){

        const lineTotal =
            Number(item.quantity) *
            Number(item.selling_price);

        cartBody.append(`

        <tr class="border-b">

            <td class="px-5 py-4">

                <div class="font-medium">

                    ${item.name}

                </div>

                <div class="text-xs text-gray-500">

                    ${item.barcode || ""}

                </div>

            </td>

            <td class="px-5 py-4 text-center">

                <div class="flex items-center justify-center gap-2">

                    <button
                        class="decreaseQty
                               w-8
                               h-8
                               rounded
                               bg-gray-200
                               hover:bg-gray-300"
                        data-index="${index}">

                        -

                    </button>

                    <span class="w-8 text-center font-semibold">

                        ${item.quantity}

                    </span>

                    <button
                        class="increaseQty
                               w-8
                               h-8
                               rounded
                               bg-indigo-600
                               hover:bg-indigo-700
                               text-white"
                        data-index="${index}">

                        +

                    </button>

                </div>

            </td>

            <td class="px-5 py-4 text-right">

                ₦${formatMoney(item.selling_price)}

            </td>

            <td class="px-5 py-4 text-right font-semibold">

                ₦${formatMoney(lineTotal)}

            </td>

            <td class="px-5 py-4 text-center">

                <button
                    class="removeItem
                           px-3
                           py-2
                           rounded-lg
                           bg-red-500
                           hover:bg-red-600
                           text-white"
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

// remove
$(document).on("click", ".removeItem", function () {

    const index = Number($(this).data("index"));

    const item = cart[index];

    if (!item) {

        showMessage(
            "error",
            "Product not found in cart."
        );

        return;

    }

    if (!confirm(`Are you sure you want to remove "${item.name}" from the cart?`)) {

        return;

    }

    console.log("Removing Item:", item);

    cart.splice(index, 1);

    console.log("Updated Cart:", cart);

    saveCart();

    renderCart();

    updateSummary();

    showMessage(
        "success",
        "Product removed successfully."
    );

});

// increase
$(document).on("click", ".increaseQty", function () {

    const index = $(this).data("index");

    const item = cart[index];

    if (item.quantity >= item.available_quantity) {

        showMessage(
            "warning",
            "Cannot add more. Stock limit reached."
        );

        return;

    }

    item.quantity++;

    saveCart();

    renderCart();

    updateSummary();

    showMessage(
        "success",
        "Quantity updated successfully."
    );

});

// Decrease
$(document).on("click", ".decreaseQty", function () {

    const index = $(this).data("index");

    if (cart[index].quantity > 1) {

        cart[index].quantity--;

    } else {

        cart.splice(index, 1);

    }

    saveCart();

    renderCart();

    updateSummary();

});

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

    /*
    |--------------------------------------------------------------------------
    | Cart Summary
    |--------------------------------------------------------------------------
    */

    $("#subtotal").text(
        "₦" + formatMoney(totals.subtotal)
    );

    $("#grandTotal").text(
        "₦" + formatMoney(totals.grandTotal)
    );

    $("#cartCount").text(
        cart.length + " Items"
    );

    let qty = 0;

    cart.forEach(function(item){

        qty += Number(item.quantity) || 0;

    });

    $("#totalQty").text(qty);

    $("#balance").text(
        "₦" + formatMoney(totals.balance)
    );

    /*
    |--------------------------------------------------------------------------
    | Payment Summary
    |--------------------------------------------------------------------------
    */

    $("#summaryTotal").text(
        "₦" + formatMoney(totals.grandTotal)
    );

    $("#summaryPaid").text(
        "₦" + formatMoney(totals.amountPaid)
    );

    $("#balanceAmount").text(
        "₦" + formatMoney(totals.balance)
    );

    /*
    |--------------------------------------------------------------------------
    | Balance Color
    |--------------------------------------------------------------------------
    */

    if (totals.balance <= 0) {

        $("#balanceAmount")
            .removeClass("text-red-600")
            .addClass("text-green-600");

    } else {

        $("#balanceAmount")
            .removeClass("text-green-600")
            .addClass("text-red-600");

    }

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




/*
|--------------------------------------------------------------------------
| CREATE ORDER
|--------------------------------------------------------------------------
*/

$("#createOrderBtn").on("click", function (e) {

    e.preventDefault();

    if (cart.length === 0) {

        showMessage(
            "Cart is empty."
        );

        return;

    }

    const btn = $(this);

    btn
        .text("Creating Order...")
        .prop("disabled", true);

    const totals = calculateTotals();


    /*
    |--------------------------------------------------------------------------
    | PAYMENT STATUS
    |--------------------------------------------------------------------------
    */

    let paymentStatus = "pending";

    const amountPaid =
        Number($("#amountPaid").val()) || 0;

    if (amountPaid >= totals.grandTotal) {

        paymentStatus = "paid";

    } else if (amountPaid > 0) {

        paymentStatus = "partial";

    }

    /*
    |--------------------------------------------------------------------------
    | BUILD ITEMS
    |--------------------------------------------------------------------------
    */

    const items = cart.map(function(item){

        return {

            inventory_id: item.inventory_id,

            product_name: item.name,

            barcode: item.barcode,

            store_name: item.store_name,

            selling_price: Number(item.selling_price),

            quantity: Number(item.quantity),

            line_total:
                Number(item.quantity) *
                Number(item.selling_price)

        };

    });

    /*
    |--------------------------------------------------------------------------
    | REQUEST PAYLOAD
    |--------------------------------------------------------------------------
    */

const payload = {

    customer_name:
        $("#customerName").val(),

    customer_phone:
        $("#customerPhone").val(),

    customer_email:
        $("#customerEmail").val(),

    customer_code:
        $("#customerCode").val(),

    payment_method:
        $("#paymentMethod").val(),

    payment_status:
        paymentStatus,

    subtotal:
        totals.subtotal,

    discount:
        totals.discount,

    tax:
        totals.tax,

    shipping:
        totals.shipping,

    total_amount:
        totals.grandTotal,

    amount_paid:
        amountPaid,

    balance:
        totals.balance,

    notes:
        $("#notes").val(),

    /*
    |--------------------------------------------------------------------------
    | SEND THE ACTUAL CART
    |--------------------------------------------------------------------------
    */

    items: cart

};


/*
|--------------------------------------------------------------------------
| DEBUG PAYLOAD
|--------------------------------------------------------------------------
*/

console.log(
    "Payload being sent:",
    payload
);

console.log(
    "Cart being sent:",
    JSON.stringify(
        cart,
        null,
        2
    )
);

    console.log(payload);

    $.ajax({

        url:
            API_BASE +
            "/dashboard/orders/create.php",

        method:
            "POST",

        contentType:
            "application/json",

        dataType:
            "json",

        headers: {

            Authorization:
                "Bearer " +
                localStorage.getItem("auth_token")

        },

        data:
            JSON.stringify(payload),

        success(res) {

            console.log("Order Response:", res);

            if (res.status) {

                /*
                |--------------------------------------------------------------------------
                | KEEP ORDER ID
                |--------------------------------------------------------------------------
                */

                const orderId = res.data.order_id;

                console.log(
                    "Created Order ID:",
                    orderId
                );


                showMessage(
                    "success",
                    res.message
                );


                /*
                |--------------------------------------------------------------------------
                | CLEAR CART
                |--------------------------------------------------------------------------
                */

                cart = [];

                saveCart();

                renderCart();

                updateSummary();


                /*
                |--------------------------------------------------------------------------
                | GO TO RECEIPT
                |--------------------------------------------------------------------------
                */

                setTimeout(function() {

                    window.location.href =
                        "order-receipt.php?id=" +
                        encodeURIComponent(orderId);

                }, 1000);

            } else {

                showMessage(
                    "error",
                    res.message
                );

            }

        },

error: function(xhr, status, error){

    console.log("XHR:", xhr);

    console.log("Status:", status);

    console.log("Error:", error);

    console.log("Response:", xhr.responseText);

    showMessage(
        xhr.responseJSON?.message ||
        xhr.responseText ||
        "Unable to create order."
    );

},

        complete(){

            btn
                .text("Create Order")
                .prop("disabled", false);

        }

    });

});





</script>