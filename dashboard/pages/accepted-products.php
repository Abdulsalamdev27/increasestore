<?php
require_once __DIR__ . "/../includes/header.php";
?>

<section class="w-full">

    <!-- ===================================== -->
    <!-- PAGE HEADER -->
    <!-- ===================================== -->

    <article class="mb-6">

        <div class="flex items-center justify-between">

            <div>

                <h1 class="text-2xl font-bold font-title">

                    Accepted Products

                </h1>

                <p class="text-gray-500 mt-1">

                    Browse products currently available in store inventory.

                </p>

            </div>

            <div>

                <button
                    id="refreshProducts"
                    class="px-5 py-2 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700 transition">

                    Refresh

                </button>

            </div>

        </div>

    </article>

    <!-- ===================================== -->
    <!-- RESPONSE MESSAGE -->
    <!-- ===================================== -->

    <div
        id="responseBox"
        class="hidden mb-6 rounded-xl px-5 py-4 text-sm font-medium">
    </div>

    <!-- ===================================== -->
    <!-- SEARCH & FILTERS -->
    <!-- ===================================== -->

    <article
        class="bg-white rounded-2xl border border-gray-200 shadow-lg overflow-hidden">

        <div
            class="border-b border-gray-200 px-6 py-5">

            <div class="flex justify-between items-center">

                <div>

                    <h2 class="font-semibold text-lg">

                        Search Products

                    </h2>

                    <p class="text-sm text-gray-500 mt-1">

                        Search products available for ordering.

                    </p>

                </div>

                <div>

                    <span
                        id="productCount"
                        class="bg-indigo-100 text-indigo-700 px-4 py-2 rounded-full font-semibold">

                        0 Products

                    </span>

                </div>

            </div>

        </div>

        <div class="p-6">

            <div class="grid lg:grid-cols-3 gap-5">

                <!-- Search -->

                <div>

                    <label class="block mb-2 text-sm font-medium">

                        Search Product

                    </label>

                    <input
                        type="text"
                        id="productSearch"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Search by product name">

                </div>

                <!-- Barcode -->

                <div>

                    <label class="block mb-2 text-sm font-medium">

                        Barcode

                    </label>

                    <input
                        type="text"
                        id="barcodeSearch"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-indigo-500"
                        placeholder="Scan barcode">

                </div>

                <!-- Category -->

                <div>

                    <label class="block mb-2 text-sm font-medium">

                        Category

                    </label>

                    <select
                        id="categoryFilter"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3">

                        <option value="">

                            All Categories

                        </option>

                    </select>

                </div>

            </div>

            <div class="grid lg:grid-cols-4 gap-5 mt-5">

                <!-- Store -->

                <div>

                    <label class="block mb-2 text-sm font-medium">

                        Store

                    </label>

                    <select
                        id="storeFilter"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3">

                        <option value="">

                            All Stores

                        </option>

                    </select>

                </div>

                <!-- Stock -->

                <div>

                    <label class="block mb-2 text-sm font-medium">

                        Stock

                    </label>

                    <select
                        id="stockFilter"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3">

                        <option value="">

                            All

                        </option>

                        <option value="available">

                            Available

                        </option>

                        <option value="low">

                            Low Stock

                        </option>

                        <option value="out">

                            Out Of Stock

                        </option>

                    </select>

                </div>

                <!-- Sort -->

                <div>

                    <label class="block mb-2 text-sm font-medium">

                        Sort By

                    </label>

                    <select
                        id="sortProducts"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3">

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

                            Latest

                        </option>

                    </select>

                </div>

                <!-- Buttons -->

                <div class="flex items-end gap-3">

                    <button
                        id="searchBtn"
                        class="flex-1 py-3 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700 transition">

                        Search

                    </button>

                    <button
                        id="clearSearch"
                        class="flex-1 py-3 rounded-xl bg-red-500 text-white hover:bg-red-600 transition">

                        Clear

                    </button>

                </div>

            </div>

        </div>

    </article>

        <!-- ===================================== -->
    <!-- AVAILABLE PRODUCTS -->
    <!-- ===================================== -->

    <article
        class="bg-white rounded-2xl border border-gray-200 shadow-lg mt-8 overflow-hidden">

        <!-- Header -->

        <div
            class="flex items-center justify-between px-6 py-4 border-b border-gray-200">

            <div>

                <h2 class="text-lg font-semibold">

                    Available Products

                </h2>

                <p class="text-sm text-gray-500 mt-1">

                    Products currently available in inventory.

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

        <!-- Loading -->

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

            <p class="mt-3 text-gray-500">

                Loading accepted products...

            </p>

        </div>

        <!-- Products Table -->

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

                        <th class="px-5 py-4 text-left font-semibold">

                            Store

                        </th>

                        <th class="px-5 py-4 text-center font-semibold">

                            Quantity

                        </th>

                        <th class="px-5 py-4 text-right font-semibold">

                            Selling Price

                        </th>

                        <th class="px-5 py-4 text-center font-semibold">

                            Status

                        </th>

                        <th class="px-5 py-4 text-center font-semibold">

                            Action

                        </th>

                    </tr>

                </thead>

                <tbody
                    id="productsTable"
                    class="divide-y divide-gray-100">

                    <tr id="emptyRow">

                        <td
                            colspan="9"
                            class="text-center py-16 text-gray-500">

                            No products found.

                        </td>

                    </tr>

                </tbody>

            </table>

        </div>

    </article>

    <!-- ===================================== -->
    <!-- PAGINATION -->
    <!-- ===================================== -->

    <article
        class="bg-white rounded-2xl border border-gray-200 shadow-lg mt-6 p-5">

        <div
            class="flex flex-col md:flex-row items-center justify-between gap-5">

            <!-- Summary -->

            <div
                class="text-sm text-gray-600">

                Showing

                <strong id="pageStart">

                    0

                </strong>

                -

                <strong id="pageEnd">

                    0

                </strong>

                of

                <strong id="totalProducts">

                    0

                </strong>

                products

            </div>

            <!-- Pagination Buttons -->

            <div
                class="flex items-center gap-2">

                <button
                    id="firstPage"
                    class="px-4 py-2 rounded-lg border hover:bg-gray-100">

                    First

                </button>

                <button
                    id="previousPage"
                    class="px-4 py-2 rounded-lg border hover:bg-gray-100">

                    Previous

                </button>

                <span
                    id="pageIndicator"
                    class="px-4 py-2 rounded-lg bg-indigo-600 text-white font-semibold">

                    Page 1

                </span>

                <button
                    id="nextPage"
                    class="px-4 py-2 rounded-lg border hover:bg-gray-100">

                    Next

                </button>

                <button
                    id="lastPage"
                    class="px-4 py-2 rounded-lg border hover:bg-gray-100">

                    Last

                </button>

            </div>

        </div>

    </article>


<!-- ============================= -->
<!-- VIEW PRODUCT MODAL -->
<!-- ============================= -->

<div
    id="viewProductModal"
    class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">

    <div class="bg-white rounded-2xl shadow-xl w-full max-w-4xl">

        <div class="flex justify-between items-center border-b px-6 py-4">

            <h3 class="text-xl font-semibold">
                Product Details
            </h3>

            <button
                id="closeViewModal"
                class="text-3xl leading-none">

                &times;

            </button>

        </div>

        <div
            id="viewProductBody"
            class="p-6">

            Loading...

        </div>

    </div>

</div>


</section>



<?php
require_once __DIR__ . "/../includes/footer.php";
?>

<script>

const API_BASE = window.API_BASE || "/increasestore/e-api";

const PRODUCTS_API =
    API_BASE + "/dashboard/accepted-products/list.php";

/*
|--------------------------------------------------------------------------
| JWT TOKEN
|--------------------------------------------------------------------------
*/

const token = localStorage.getItem("auth_token");

if (!token) {
    window.location.href = "login.php";
}



/*
|--------------------------------------------------------------------------
| GLOBAL VARIABLES
|--------------------------------------------------------------------------
*/

let currentPage = 1;

let rowsPerPage = 10;

let totalPages = 1;

let totalProducts = 0;

let products = [];

/*
|--------------------------------------------------------------------------
| ELEMENTS
|--------------------------------------------------------------------------
*/

const productSearch = $("#productSearch");

const barcodeSearch = $("#barcodeSearch");

const categoryFilter = $("#categoryFilter");

const storeFilter = $("#storeFilter");

const stockFilter = $("#stockFilter");

const sortProducts = $("#sortProducts");

const productsTable = $("#productsTable");

/*
|--------------------------------------------------------------------------
| RESPONSE
|--------------------------------------------------------------------------
*/

function showMessage(type, message) {

    const box = $("#responseBox");

    box.removeClass("hidden");

    box.removeClass(
        "bg-green-100 text-green-700 bg-red-100 text-red-700"
    );

    if (type === "success") {

        box.addClass(
            "bg-green-100 text-green-700"
        );

    } else {

        box.addClass(
            "bg-red-100 text-red-700"
        );

    }

    box.text(message);

}

/*
|--------------------------------------------------------------------------
| LOADER
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

            Authorization:
                "Bearer " + token

        },

        data: {

            page: currentPage,

            limit: rowsPerPage,

            search:
                productSearch.val().trim(),

            barcode:
                barcodeSearch.val().trim(),

            category:
                categoryFilter.val(),

            store_id:
                storeFilter.val(),

            stock:
                stockFilter.val(),

            sort_by:
                sortProducts.val(),

            sort_order: "ASC"

        },

        success: function(response) {

            hideLoader();

            if (!response.status) {

                showMessage(
                    "error",
                    response.message
                );

                return;

            }

            products = response.data || [];

            totalProducts =
                response.pagination.total;

            totalPages =
                response.pagination.total_pages;

            renderProducts();

            updatePagination();

        },

        error: function(xhr) {

            hideLoader();

            if (xhr.status === 401) {

                localStorage.removeItem("token");

                window.location.href =
                    "login.php";

                return;

            }

            console.error(xhr);

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

$("#searchBtn").on("click", function() {

    loadProducts(1);

});

productSearch.on("keyup", function(e) {

    if (e.key === "Enter") {

        loadProducts(1);

    }

});

barcodeSearch.on("keyup", function(e) {

    if (e.key === "Enter") {

        loadProducts(1);

    }

});

categoryFilter.on("change", function() {

    loadProducts(1);

});

storeFilter.on("change", function() {

    loadProducts(1);

});

stockFilter.on("change", function() {

    loadProducts(1);

});

sortProducts.on("change", function() {

    loadProducts(1);

});

/*
|--------------------------------------------------------------------------
| REFRESH
|--------------------------------------------------------------------------
*/

$("#refreshProducts").click(function(){

    loadProducts(currentPage);

});

/*
|--------------------------------------------------------------------------
| CLEAR SEARCH
|--------------------------------------------------------------------------
*/

$("#clearSearch").click(function(){

    productSearch.val("");

    barcodeSearch.val("");

    categoryFilter.val("");

    storeFilter.val("");

    stockFilter.val("");

    sortProducts.val("name");

    loadProducts(1);

});



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
| RENDER PRODUCTS
|--------------------------------------------------------------------------
*/

function renderProducts() {

    if (!products.length) {

        productsTable.html(`

            <tr>

                <td
                    colspan="9"
                    class="text-center py-16 text-gray-500">

                    No products found.

                </td>

            </tr>

        `);

        $("#productCount").text("0 Products");

        $("#availableCount").text("0 Available");

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

        if (item.quantity <= 0) {

            badge = `
                <span
                    class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs">

                    Out

                </span>
            `;

        } else if (
            item.product.minimum_stock &&
            item.quantity <= item.product.minimum_stock
        ) {

            badge = `
                <span
                    class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs">

                    Low

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

                ${escapeHtml(item.product.sku)}

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

    $("#productCount").text(totalProducts + " Products");

    $("#availableCount").text(products.length + " Available");

}

/*
|--------------------------------------------------------------------------
| PAGINATION
|--------------------------------------------------------------------------
*/

function updatePagination() {

    $("#pageIndicator").text(

        "Page " +
        currentPage +
        " of " +
        totalPages

    );

    $("#pageStart").text(

        totalProducts === 0
            ? 0
            : ((currentPage - 1) * rowsPerPage) + 1

    );

    $("#pageEnd").text(

        Math.min(
            currentPage * rowsPerPage,
            totalProducts
        )

    );

    $("#totalProducts").text(totalProducts);

}

/*
|--------------------------------------------------------------------------
| PAGINATION EVENTS
|--------------------------------------------------------------------------
*/

$("#firstPage").click(function(){

    if(currentPage > 1){

        loadProducts(1);

    }

});

$("#previousPage").click(function(){

    if(currentPage > 1){

        loadProducts(currentPage - 1);

    }

});

$("#nextPage").click(function(){

    if(currentPage < totalPages){

        loadProducts(currentPage + 1);

    }

});

$("#lastPage").click(function(){

    if(currentPage < totalPages){

        loadProducts(totalPages);

    }

});

/*
|--------------------------------------------------------------------------
| VIEW PRODUCT
|--------------------------------------------------------------------------
*/

$(document).on("click", ".viewProduct", function () {

    const id = $(this).data("id");

    loadProductDetails(id);

});


/*
|--------------------------------------------------------------------------
| CART
|--------------------------------------------------------------------------
*/

let cart = JSON.parse(
    localStorage.getItem("order_cart") || "[]"
);




function loadProductDetails(id){

    $.ajax({

        url: API_BASE + "/dashboard/accepted-products/view.php",

        type:"GET",

        headers:{
            Authorization:"Bearer " + token
        },

        data:{
            id:id
        },

        dataType:"json",

        success:function(res){

            if(!res.status){

                showMessage("error",res.message);

                return;

            }

            const p = res.data.product;
            const s = res.data.store;

            $("#viewProductBody").html(`

<div class="grid md:grid-cols-2 gap-8">

<div>

<h4 class="font-semibold text-lg mb-4">
Product Information
</h4>

<table class="w-full text-sm">

<tr>
<td class="py-2 font-medium">Product</td>
<td>${p.name}</td>
</tr>

<tr>
<td class="py-2 font-medium">Barcode</td>
<td>${p.barcode}</td>
</tr>

<tr>
<td class="py-2 font-medium">SKU</td>
<td>${p.sku}</td>
</tr>

<tr>
<td class="py-2 font-medium">Category</td>
<td>${p.category}</td>
</tr>

<tr>
<td class="py-2 font-medium">Unit</td>
<td>${p.unit}</td>
</tr>

<tr>
<td class="py-2 font-medium">Cost Price</td>
<td>₦${Number(p.cost_price).toLocaleString()}</td>
</tr>

<tr>
<td class="py-2 font-medium">Selling Price</td>
<td>₦${Number(p.selling_price).toLocaleString()}</td>
</tr>

<tr>
<td class="py-2 font-medium">Available Qty</td>
<td>${res.data.quantity}</td>
</tr>

<tr>
<td class="py-2 font-medium">Status</td>
<td>${p.status}</td>
</tr>

<tr>
<td class="py-2 font-medium">Description</td>
<td>${p.description ?? "-"}</td>
</tr>

</table>

</div>

<div>

<h4 class="font-semibold text-lg mb-4">
Store Information
</h4>

<table class="w-full text-sm">

<tr>
<td class="py-2 font-medium">Store</td>
<td>${s.name}</td>
</tr>

<tr>
<td class="py-2 font-medium">Address</td>
<td>${s.address ?? "-"}</td>
</tr>

<tr>
<td class="py-2 font-medium">Phone</td>
<td>${s.phone ?? "-"}</td>
</tr>

<tr>
<td class="py-2 font-medium">Email</td>
<td>${s.email ?? "-"}</td>
</tr>

<tr>
<td class="py-2 font-medium">Inventory ID</td>
<td>${res.data.inventory_id}</td>
</tr>

<tr>
<td class="py-2 font-medium">Created</td>
<td>${res.data.created_at}</td>
</tr>

<tr>
<td class="py-2 font-medium">Updated</td>
<td>${res.data.updated_at}</td>
</tr>

</table>

</div>

</div>

            `);

            $("#viewProductModal")
                .removeClass("hidden")
                .addClass("flex");

        },

        error:function(xhr){

            showMessage(
                "error",
                xhr.responseJSON?.message || "Unable to load product."
            );

        }

    });

}









/*
|--------------------------------------------------------------------------
| SAVE CART
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
| ADD PRODUCT TO CART
|--------------------------------------------------------------------------
*/

$(document).on(
    "click",
    ".addToCart",
    function () {

        const inventoryId = Number(
            $(this).data("id")
        );

        const product = products.find(function (item) {

            return Number(item.inventory_id) === inventoryId;

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
                "This product is out of stock."
            );

            return;

        }

        const existing = cart.find(function (item) {

            return Number(item.inventory_id) === inventoryId;

        });

        if (existing) {

            if (existing.quantity >= product.quantity) {

                showMessage(
                    "error",
                    "Maximum stock reached."
                );

                return;

            }

            existing.quantity++;

        } else {

            cart.push({

                inventory_id: product.inventory_id,

                product_id: product.product.id,

                name: product.product.name,

                barcode: product.product.barcode,

                sku: product.product.sku,

                category: product.product.category,

                store_id: product.store.id,

                store_name: product.store.name,

                selling_price: Number(product.product.selling_price),

                available_quantity: Number(product.quantity),

                quantity: 1

            });

        }

        saveCart();

        showMessage(
            "success",
            "Product added to cart."
        );

    }
);

/*
|--------------------------------------------------------------------------
| BARCODE AUTO SEARCH
|--------------------------------------------------------------------------
*/

barcodeSearch.on("keyup", function (e) {

    if (e.key !== "Enter") {

        return;

    }

    const code = $(this)
        .val()
        .trim();

    if (!code.length) {

        return;

    }

    const product = products.find(function (item) {

        return item.product.barcode === code;

    });

    if (!product) {

        showMessage(
            "error",
            "No product found."
        );

        return;

    }

    $(".addToCart[data-id='" +
        product.inventory_id +
        "']").click();

    $(this).val("");

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

    }, 500);

});

/*
|--------------------------------------------------------------------------
| PAGE SIZE
|--------------------------------------------------------------------------
*/

$("#rowsPerPage").change(function () {

    rowsPerPage = Number(
        $(this).val()
    );

    loadProducts(1);

});


$("#closeViewModal").click(function(){

    $("#viewProductModal")
        .removeClass("flex")
        .addClass("hidden");

});

$("#viewProductModal").click(function(e){

    if(e.target === this){

        $(this)
            .removeClass("flex")
            .addClass("hidden");

    }

});

/*
|--------------------------------------------------------------------------
| ENTER SEARCH
|--------------------------------------------------------------------------
*/

$("#productSearch").keypress(function (e) {

    if (e.which === 13) {

        loadProducts(1);

    }

});

/*
|--------------------------------------------------------------------------
| INITIAL LOAD
|--------------------------------------------------------------------------
*/

$(function () {

    loadProducts();

});






</script>