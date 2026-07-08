<?php
require_once __DIR__ . "/../includes/header.php";
?>

<section class="w-full">

    <!-- Page Title -->
    <article class="mb-6">

        <h1 class="font-title font-bold text-2xl">
            Send Product
        </h1>

        <p class="text-gray-600 text-sm">
            Send products from the warehouse to a store.
        </p>

    </article>

    <!-- Response -->
    <div
        id="responseBox"
        class="hidden mb-6 px-4 py-3 rounded text-sm font-medium">
    </div>

    <!-- Card -->
    <article class="bg-white rounded-2xl border border-gray-200 shadow-lg backdrop-blur-md">

        <form
            id="transferForm"
            class="space-y-10 py-6">

            <!-- TRANSFER INFORMATION -->
            <section class="px-6">

                <h2 class="font-title font-semibold text-lg mb-4">
                    Transfer Information
                </h2>

                <div class="grid md:grid-cols-2 gap-5">

                    <!-- ========================= -->
                    <!-- PRODUCT -->
                    <!-- ========================= -->

                    <div class="md:col-span-2">

                        <label class="label">
                            Product
                        </label>

                        <!-- Search Product -->
                        <input
                            type="text"
                            id="productSearch"
                            class="input mb-2"
                            placeholder="Search product by name, barcode or SKU">

                        <!-- Product Dropdown -->
                        <select
                            id="product_id"
                            name="product_id"
                            class="input"
                            required>

                            <option value="">
                                Loading products...
                            </option>

                        </select>

                    </div>

                    <!-- ========================= -->
                    <!-- PRODUCT DETAILS -->
                    <!-- ========================= -->

                    <div>

                        <label class="label">
                            Barcode
                        </label>

                        <input
                            type="text"
                            id="barcode"
                            class="input bg-gray-100"
                            readonly>

                    </div>

                    <div>

                        <label class="label">
                            SKU
                        </label>

                        <input
                            type="text"
                            id="sku"
                            class="input bg-gray-100"
                            readonly>

                    </div>

                    <div>

                        <label class="label">
                            Category
                        </label>

                        <input
                            type="text"
                            id="category"
                            class="input bg-gray-100"
                            readonly>

                    </div>

                    <div>

                        <label class="label">
                            Available Quantity
                        </label>

                        <input
                            type="number"
                            id="available_qty"
                            class="input bg-gray-100"
                            readonly>

                    </div>

                    <!-- ========================= -->
                    <!-- STORE -->
                    <!-- ========================= -->

                    <div class="md:col-span-2">

                        <label class="label">
                            Destination Store
                        </label>

                        <!-- Search Store -->
                        <input
                            type="text"
                            id="storeSearch"
                            class="input mb-2"
                            placeholder="Search store">

                        <!-- Store Dropdown -->
                        <select
                            id="store_id"
                            name="store_id"
                            class="input"
                            required>

                            <option value="">
                                Loading stores...
                            </option>

                        </select>

                    </div>

                    <!-- ========================= -->
                    <!-- QUANTITY -->
                    <!-- ========================= -->

                    <div>

                        <label class="label">
                            Quantity To Send
                        </label>

                        <input
                            type="number"
                            id="quantity"
                            name="quantity"
                            class="input"
                            min="1"
                            placeholder="Enter quantity"
                            required>

                    </div>

                    <!-- ========================= -->
                    <!-- REFERENCE -->
                    <!-- ========================= -->

                    <div>

                        <label class="label">
                            Reference Number
                        </label>

                        <input
                            type="text"
                            id="reference_no"
                            name="reference_no"
                            class="input"
                            placeholder="Optional">

                    </div>

                    <!-- ========================= -->
                    <!-- REMARKS -->
                    <!-- ========================= -->

                    <div class="md:col-span-2">

                        <label class="label">
                            Remarks
                        </label>

                        <textarea
                            id="remarks"
                            name="remarks"
                            rows="4"
                            class="input"
                            placeholder="Optional remarks"></textarea>

                    </div>

                </div>

            </section>

            <!-- Submit -->
            <div class="px-6 pt-4">

                <button
                    type="submit"
                    id="sendBtn"
                    class="w-full py-3 rounded-xl bg-indigo-600 text-white font-semibold shadow-md hover:bg-indigo-700 transition">

                    Send Product

                </button>

            </div>

        </form>

    </article>

</section>

<?php
require_once __DIR__ . "/../includes/footer.php";
?>
<script>

const API_BASE_URL = "<?php echo API_BASE_URL; ?>";

let allProducts = [];
let allStores = [];

/* ==========================================
   LOAD PRODUCTS
========================================== */

function loadProducts() {

    $.ajax({

        url: API_BASE_URL + "/dashboard/products/view-all.php",

        method: "GET",

        headers: {
            Authorization: "Bearer " + localStorage.getItem("auth_token")
        },

        dataType: "json",

        success(res) {

            if (res.status) {

                allProducts = res.data || [];

                renderProducts(allProducts);

            } else {

                showMessage(res.message || "Unable to load products.");

            }

        },

        error(xhr) {

            showMessage(
                xhr.responseJSON?.message || "Server error."
            );

        }

    });

}

/* ==========================================
   LOAD STORES
========================================== */

function loadStores() {

    $.ajax({

        url: API_BASE_URL + "/dashboard/stores/view-all.php",

        method: "GET",

        headers: {
            Authorization: "Bearer " + localStorage.getItem("auth_token")
        },

        dataType: "json",

        success(res) {

            if (res.status) {

                allStores = res.data || [];

                renderStores(allStores);

            } else {

                showMessage(res.message || "Unable to load stores.");

            }

        },

        error(xhr) {

            showMessage(
                xhr.responseJSON?.message || "Server error."
            );

        }

    });

}

/* ==========================================
   PRODUCT DROPDOWN
========================================== */

function renderProducts(products) {

    const select = $("#product_id");

    select.html("");

    select.append(`
        <option value="">
            Select Product
        </option>
    `);

    products.forEach(product => {

        select.append(`

            <option value="${product.id}">

                ${escapeHtml(product.product_name)}
                (${escapeHtml(product.barcode)})

            </option>

        `);

    });

}

/* ==========================================
   STORE DROPDOWN
========================================== */

function renderStores(stores) {

    const select = $("#store_id");

    select.html("");

    select.append(`
        <option value="">
            Select Store
        </option>
    `);

    stores.forEach(store => {

        select.append(`

            <option value="${store.id}">

                ${escapeHtml(store.store_name)}

            </option>

        `);

    });

}

/* ==========================================
   SEARCH PRODUCTS
========================================== */

$("#productSearch").on("keyup", function () {

    const keyword = $(this)
        .val()
        .toLowerCase()
        .trim();

    const filtered = allProducts.filter(product =>

        (product.product_name || "")
            .toLowerCase()
            .includes(keyword)

        ||

        (product.barcode || "")
            .toLowerCase()
            .includes(keyword)

        ||

        (product.sku || "")
            .toLowerCase()
            .includes(keyword)

    );

    renderProducts(filtered);

});

/* ==========================================
   SEARCH STORES
========================================== */

$("#storeSearch").on("keyup", function () {

    const keyword = $(this)
        .val()
        .toLowerCase()
        .trim();

    const filtered = allStores.filter(store =>

        (store.store_name || "")
            .toLowerCase()
            .includes(keyword)

    );

    renderStores(filtered);

});

/* ==========================================
   PRODUCT CHANGE
========================================== */

$("#product_id").on("change", function () {

    const id = $(this).val();

    const product = allProducts.find(p => p.id == id);

    if (!product) {

        $("#barcode").val("");
        $("#sku").val("");
        $("#category").val("");
        $("#available_qty").val("");

        return;

    }

    $("#barcode").val(product.barcode);

    $("#sku").val(product.sku);

    $("#category").val(product.category);

    $("#available_qty").val(product.quantity);

});

/* ==========================================
   SUBMIT FORM
========================================== */

$("#transferForm").on("submit", function (e) {

    e.preventDefault();

    const btn = $("#sendBtn");

    btn
        .text("Sending...")
        .prop("disabled", true);

    const payload = {

        product_id: $("#product_id").val(),

        store_id: $("#store_id").val(),

        quantity: $("#quantity").val(),

        reference_no: $("#reference_no").val(),

        remarks: $("#remarks").val()

    };

    $.ajax({

        url: API_BASE_URL + "/dashboard/transfers/create.php",

        method: "POST",

        headers: {
            Authorization: "Bearer " + localStorage.getItem("auth_token")
        },

        contentType: "application/json",

        dataType: "json",

        data: JSON.stringify(payload),

        success(res) {

            if (res.status) {

                showMessage(
                    res.message,
                    "success"
                );

                $("#transferForm")[0].reset();

                $("#barcode").val("");
                $("#sku").val("");
                $("#category").val("");
                $("#available_qty").val("");

                renderProducts(allProducts);

                renderStores(allStores);

            } else {

                showMessage(
                    res.message || "Unable to send product."
                );

            }

        },

        error(xhr) {

            showMessage(
                xhr.responseJSON?.message || "Server error."
            );

        },

        complete() {

            btn
                .text("Send Product")
                .prop("disabled", false);

        }

    });

});

/* ==========================================
   RESPONSE
========================================== */

function showMessage(message, type = "error") {

    const box = $("#responseBox");

    box.removeClass(
        "hidden bg-green-100 bg-red-100 text-green-700 text-red-700"
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

    box.removeClass("hidden");

    setTimeout(function () {

        box.addClass("hidden");

    }, 3000);

}

/* ==========================================
   XSS PROTECTION
========================================== */

function escapeHtml(text) {

    return $("<div>")
        .text(text ?? "")
        .html();

}

/* ==========================================
   INITIALIZE
========================================== */

$(document).ready(function () {

    loadProducts();

    loadStores();

});

</script>