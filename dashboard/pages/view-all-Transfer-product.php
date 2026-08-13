<?php
require_once __DIR__ . "/../includes/header.php";
?>

<div class="dashboard-container">

    <!-- ==========================================
         RESPONSE MESSAGE
    =========================================== -->
    <div
        id="responseBox"
        class="hidden mb-6 rounded-lg border px-4 py-3 text-sm font-medium">
    </div>

    <div class="dashboard-content">

        <!-- ==========================================
             PAGE HEADER
        =========================================== -->
        <div class="flex flex-col gap-4 mb-6 md:flex-row md:items-center md:justify-between">

            <div>

                <h1 class="text-2xl font-bold text-gray-800">
                    Product Transfers
                </h1>

                <p class="mt-1 text-sm text-gray-500">
                    View, monitor and manage product transfers between warehouse and stores.
                </p>

            </div>

            <div class="flex gap-3">

                <button
                    type="button"
                    id="refreshTransfers"
                    class="rounded-lg bg-gray-700 px-5 py-2.5 text-white transition hover:bg-gray-800">

                    Refresh

                </button>

                <a
                    href="create-transfer.php"
                    class="rounded-lg bg-indigo-600 px-5 py-2.5 text-white transition hover:bg-indigo-700">

                    + Send Product

                </a>

            </div>

        </div>

        <!-- ==========================================
             FILTER CARD
        =========================================== -->
        <div class="mb-6 rounded-xl bg-white shadow-sm">

            <div class="grid gap-5 p-6 md:grid-cols-4">

                <!-- Search -->
                <div>

                    <label
                        for="searchTransfer"
                        class="mb-2 block text-sm font-medium text-gray-700">

                        Search

                    </label>

                    <input
                        id="searchTransfer"
                        type="text"
                        autocomplete="off"
                        placeholder="Reference, Product, Barcode or Store..."
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200">

                </div>

                <!-- Status -->
                <div>

                    <label
                        for="statusFilter"
                        class="mb-2 block text-sm font-medium text-gray-700">

                        Status

                    </label>

                    <select
                        id="statusFilter"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200">

                        <option value="">
                            All Status
                        </option>

                        <option value="pending">
                            Pending
                        </option>

                        <option value="accepted">
                            Accepted
                        </option>

                        <option value="rejected">
                            Rejected
                        </option>

                    </select>

                </div>

                <!-- Movement -->
                <div>

                    <label
                        for="movementFilter"
                        class="mb-2 block text-sm font-medium text-gray-700">

                        Movement Type

                    </label>

                    <select
                        id="movementFilter"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200">

                        <option value="">
                            All Movements
                        </option>

                        <option value="send">
                            Send
                        </option>

                        <option value="return">
                            Return
                        </option>

                    </select>

                </div>

                <!-- Reset -->
                <div class="flex items-end">

                    <button
                        type="button"
                        id="resetFilters"
                        class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-gray-700 transition hover:bg-gray-100">

                        Reset Filters

                    </button>

                </div>

            </div>

        </div>

<!-- ==========================================================
     TRANSFER TABLE
=========================================================== -->

<div class="overflow-hidden rounded-xl bg-white shadow-sm">

    <div class="overflow-x-auto">

        <table class="min-w-full divide-y divide-gray-200 text-sm">

            <thead class="bg-gray-50">

                <tr class="text-left text-xs font-semibold uppercase tracking-wider text-gray-600">

                    <th class="px-6 py-4">
                        Reference
                    </th>

                    <th class="px-6 py-4">
                        Product
                    </th>

                    <th class="px-6 py-4">
                        Store
                    </th>

                    <th class="px-6 py-4">
                        Movement
                    </th>

                    <th class="px-6 py-4 text-center">
                        Qty
                    </th>

                    <th class="px-6 py-4">
                        Status
                    </th>

                    <th class="px-6 py-4">
                        Sent By
                    </th>

                    <th class="px-6 py-4">
                        Date
                    </th>

                    <th class="px-6 py-4 text-center">
                        Actions
                    </th>

                </tr>

            </thead>

            <tbody
                id="transferTable"
                class="divide-y divide-gray-100 bg-white">

                <tr>

                    <td
                        colspan="9"
                        class="px-6 py-12 text-center">

                        <div class="flex flex-col items-center justify-center">

                            <svg
                                class="mb-3 h-8 w-8 animate-spin text-indigo-600"
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
                                    d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z">
                                </path>

                            </svg>

                            <p class="text-gray-500">
                                Loading transfers...
                            </p>

                        </div>

                    </td>

                </tr>

            </tbody>

        </table>

    </div>

</div>

<!-- ==========================================================
     PAGINATION
=========================================================== -->


        <div
            id="pagination"
            class="flex justify-between items-center mt-4 text-sm text-gray-600 hidden">

            <button
                id="prevPage"
                class="px-3 py-1 rounded bg-gray-100 hover:bg-gray-200">

                ← Previous

            </button>

            <span id="pageInfo"></span>

            <button
                id="nextPage"
                class="px-3 py-1 rounded bg-gray-100 hover:bg-gray-200">

                Next →

            </button>

        </div>

<!-- ==========================================================
     TRANSFER DETAILS MODAL
=========================================================== -->

<div
    id="transferModal"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm">

    <div class="w-full max-w-4xl rounded-2xl bg-white shadow-2xl">

        <!-- ==========================
             MODAL HEADER
        =========================== -->

        <div class="flex items-center justify-between border-b px-6 py-4">

            <div>

                <h2 class="text-xl font-semibold text-gray-800">
                    Transfer Details
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    View and manage this product transfer.
                </p>

            </div>

            <button
                id="closeModal"
                type="button"
                class="text-3xl leading-none text-gray-500 transition hover:text-red-600">

                &times;

            </button>

        </div>

        <!-- ==========================
             LOADING STATE
        =========================== -->

        <div
            id="modalLoader"
            class="hidden border-b bg-gray-50 px-6 py-8 text-center">

            <svg
                class="mx-auto mb-3 h-8 w-8 animate-spin text-indigo-600"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24">

                <circle
                    class="opacity-20"
                    cx="12"
                    cy="12"
                    r="10"
                    stroke="currentColor"
                    stroke-width="4">
                </circle>

                <path
                    class="opacity-80"
                    fill="currentColor"
                    d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z">
                </path>

            </svg>

            Loading transfer...

        </div>

        <!-- ==========================
             FORM
        =========================== -->

        <form id="transferForm">

            <input
                type="hidden"
                id="transfer_id">

            <div class="grid gap-6 p-6 md:grid-cols-2">

                <!-- Reference -->

                <div>

                    <label class="mb-2 block text-sm font-medium text-gray-700">

                        Reference No.

                    </label>

                    <input
                        id="reference_no"
                        type="text"
                        readonly
                        class="w-full rounded-lg border bg-gray-100 px-4 py-3">

                </div>

                <!-- Status -->

                <div>

                    <label class="mb-2 block text-sm font-medium text-gray-700">

                        Status

                    </label>

                    <select
                        id="status"
                        class="w-full rounded-lg border px-4 py-3">

                        <option value="pending">
                            Pending
                        </option>

                        <option value="accepted">
                            Accepted
                        </option>

                        <option value="rejected">
                            Rejected
                        </option>

                    </select>

                </div>

                <!-- Product -->

                <div>

                    <label class="mb-2 block text-sm font-medium">

                        Product

                    </label>

                    <input
                        id="product_name"
                        readonly
                        class="w-full rounded-lg border bg-gray-100 px-4 py-3">

                </div>

                <!-- Store -->

                <div>

                    <label class="mb-2 block text-sm font-medium">

                        Store

                    </label>

                    <input
                        id="store_name"
                        readonly
                        class="w-full rounded-lg border bg-gray-100 px-4 py-3">

                </div>

                <!-- Barcode -->

                <div>

                    <label class="mb-2 block text-sm font-medium">

                        Barcode

                    </label>

                    <input
                        id="barcode"
                        readonly
                        class="w-full rounded-lg border bg-gray-100 px-4 py-3">

                </div>

                <!-- SKU -->

                <div>

                    <label class="mb-2 block text-sm font-medium">

                        SKU

                    </label>

                    <input
                        id="sku"
                        readonly
                        class="w-full rounded-lg border bg-gray-100 px-4 py-3">

                </div>

                <!-- Quantity -->

                <div>

                    <label class="mb-2 block text-sm font-medium">

                        Quantity

                    </label>

                    <input
                        id="quantity"
                        type="number"
                        min="1"
                        class="w-full rounded-lg border px-4 py-3">

                </div>

                <!-- Movement -->

                <div>

                    <label class="mb-2 block text-sm font-medium">

                        Movement Type

                    </label>

                    <input
                        id="movement_type"
                        readonly
                        class="w-full rounded-lg border bg-gray-100 px-4 py-3">

                </div>

                <!-- Sent By -->

                <div>

                    <label class="mb-2 block text-sm font-medium">

                        Sent By

                    </label>

                    <input
                        id="sent_by"
                        readonly
                        class="w-full rounded-lg border bg-gray-100 px-4 py-3">

                </div>

                <!-- Reviewed By -->

                <div>

                    <label class="mb-2 block text-sm font-medium">

                        Reviewed By

                    </label>

                    <input
                        id="reviewed_by"
                        readonly
                        class="w-full rounded-lg border bg-gray-100 px-4 py-3">

                </div>

                <!-- Created -->

                <div>

                    <label class="mb-2 block text-sm font-medium">

                        Date Sent

                    </label>

                    <input
                        id="created_at"
                        readonly
                        class="w-full rounded-lg border bg-gray-100 px-4 py-3">

                </div>

                <!-- Reviewed -->

                <div>

                    <label class="mb-2 block text-sm font-medium">

                        Reviewed At

                    </label>

                    <input
                        id="reviewed_at"
                        readonly
                        class="w-full rounded-lg border bg-gray-100 px-4 py-3">

                </div>

                <!-- Remarks -->

                <div class="md:col-span-2">

                    <label class="mb-2 block text-sm font-medium">

                        Remarks

                    </label>

                    <textarea
                        id="remarks"
                        rows="4"
                        class="w-full rounded-lg border px-4 py-3 resize-none"></textarea>

                </div>

            </div>

            <!-- ==========================
                 FOOTER BUTTONS
            =========================== -->

            <div class="flex flex-wrap justify-end gap-3 border-t bg-gray-50 px-6 py-4">

                <button
                    type="button"
                    id="acceptTransfer"
                    class="rounded-lg bg-green-600 px-5 py-2.5 text-white hover:bg-green-700">

                    Accept

                </button>

                <button
                    type="button"
                    id="rejectTransfer"
                    class="rounded-lg bg-yellow-500 px-5 py-2.5 text-white hover:bg-yellow-600">

                    Reject

                </button>

                <button
                    type="button"
                    id="deleteTransfer"
                    class="rounded-lg bg-red-600 px-5 py-2.5 text-white hover:bg-red-700">

                    Delete

                </button>

                <button
                    type="button"
                    id="cancelModal"
                    class="rounded-lg border px-5 py-2.5 hover:bg-gray-100">

                    Close

                </button>

                <button
                    type="submit"
                    id="saveTransfer"
                    class="rounded-lg bg-indigo-600 px-6 py-2.5 text-white hover:bg-indigo-700">

                    Update Transfer

                </button>

            </div>

        </form>

    </div>

</div>

<?php include("../includes/footer.php"); ?>

<script>

const API_BASE_URL = "<?php echo API_BASE_URL; ?>";
const TOKEN = localStorage.getItem("auth_token");


/* ==========================================================
   GLOBAL VARIABLES
========================================================== */

let allTransfers = [];

let filteredTransfers = [];

let currentPage = 1;

const rowsPerPage = 10;

let selectedTransfer = null;

/* ==========================================================
   LOAD TRANSFERS
========================================================== */

function loadTransfers(page = 1) {

    currentPage = page;

    $.ajax({

        url: API_BASE_URL + "/dashboard/transfers/list.php",

        method: "GET",

        headers: {
            Authorization: "Bearer " + TOKEN
        },

        dataType: "json",

        beforeSend: function () {

            $("#transferTable").html(`

                <tr>

                    <td colspan="9"
                        class="px-6 py-10 text-center">

                        <div class="flex flex-col items-center">

                            <svg
                                class="h-8 w-8 animate-spin text-indigo-600 mb-3"
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
                                    d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z">
                                </path>

                            </svg>

                            <span class="text-gray-500">
                                Loading transfers...
                            </span>

                        </div>

                    </td>

                </tr>

            `);

        },

        success: function (response) {

            if (!response.status) {

                $("#transferTable").html(`

                    <tr>

                        <td colspan="9"
                            class="px-6 py-10 text-center text-red-500">

                            ${escapeHtml(
                                response.message || "Unable to load transfers."
                            )}

                        </td>

                    </tr>

                `);

                return;
            }

            allTransfers = response.data || [];

            filteredTransfers = [...allTransfers];

            currentPage = 1;

            renderTransfers();

            updatePagination();

        },

        error: function (xhr) {

            const message =

                xhr.responseJSON?.message ||

                "Server error while loading transfers.";

            $("#transferTable").html(`

                <tr>

                    <td colspan="9"
                        class="px-6 py-10 text-center text-red-500">

                        ${escapeHtml(message)}

                    </td>

                </tr>

            `);

        }

    });

}

/* ==========================================================
   REFRESH BUTTON
========================================================== */

$("#refreshTransfers").on("click", function () {

    loadTransfers();

});

/* ==========================================================
   INITIAL LOAD
========================================================== */

$(document).ready(function () {

    loadTransfers();

});


/* ==========================================================
   RENDER TRANSFER TABLE
========================================================== */

function renderTransfers() {

    const table = $("#transferTable");

    table.empty();

    if (filteredTransfers.length === 0) {

        table.html(`

            <tr>

                <td colspan="9" class="px-6 py-10 text-center text-gray-500">

                    No transfers found.

                </td>

            </tr>

        `);

        $("#pagination").addClass("hidden");

        return;

    }

    $("#pagination").removeClass("hidden");

    const start = (currentPage - 1) * rowsPerPage;

    const end = start + rowsPerPage;

    const transfers = filteredTransfers.slice(start, end);

    transfers.forEach(function (item) {

        table.append(`

<tr class="border-b hover:bg-gray-50 transition">

    <td class="px-6 py-4">

        <div class="font-semibold text-gray-800">

            ${escapeHtml(item.reference_no)}

        </div>

    </td>

    <td class="px-6 py-4">

        <div class="font-medium">

            ${escapeHtml(item.product_name)}

        </div>

        <div class="text-xs text-gray-500">

            ${escapeHtml(item.barcode || "-")}

        </div>

    </td>

    <td class="px-6 py-4">

        ${escapeHtml(item.store_name)}

    </td>

    <td class="px-6 py-4">

        ${getMovementBadge(item.movement_type)}

    </td>

    <td class="px-6 py-4 text-center font-semibold">

        ${Number(item.quantity).toLocaleString()}

    </td>

    <td class="px-6 py-4">

        ${getStatusBadge(item.status)}

    </td>

    <td class="px-6 py-4">

        ${escapeHtml(item.sender_name || "-")}


    </td>

    <td class="px-6 py-4">

        ${formatDate(item.created_at)}

    </td>

    <td class="px-6 py-4">

        <div class="flex gap-2">

            <button

                class="viewTransfer rounded bg-indigo-600 px-3 py-1 text-white hover:bg-indigo-700"

                data-id="${item.id}">

                View

            </button>

        </div>

    </td>

</tr>

        `);

    });

    updatePaginationInfo();

}

/* ==========================================================
   UPDATE PAGINATION INFO
========================================================== */

function updatePaginationInfo() {

    const total = filteredTransfers.length;

    const totalPages = Math.max(1, Math.ceil(total / rowsPerPage));

    const from = total === 0 ? 0 : ((currentPage - 1) * rowsPerPage) + 1;

    const to = Math.min(currentPage * rowsPerPage, total);

    $("#showingFrom").text(from);

    $("#showingTo").text(to);

    $("#totalRows").text(total);

    $("#pageInfo").text(

        `Page ${currentPage} of ${totalPages}`

    );

    $("#prevPage").prop(

        "disabled",

        currentPage === 1

    );

    $("#nextPage").prop(

        "disabled",

        currentPage >= totalPages

    );

}

/* ==========================================================
   PAGINATION
========================================================== */

$("#prevPage").on("click", function () {

    if (currentPage <= 1) {

        return;

    }

    currentPage--;

    renderTransfers();

});

$("#nextPage").on("click", function () {

    const totalPages = Math.ceil(

        filteredTransfers.length / rowsPerPage

    );

    if (currentPage >= totalPages) {

        return;

    }

    currentPage++;

    renderTransfers();

});


/* ==========================================================
   SEARCH + FILTERS
========================================================== */

/**
 * Apply all filters
 */
function applyFilters() {

    const keyword = $("#searchTransfer")
        .val()
        .toLowerCase()
        .trim();

    const status = $("#statusFilter").val();

    const movement = $("#movementFilter").val();

    filteredTransfers = allTransfers.filter(function (item) {

        const reference = (item.reference_no || "").toLowerCase();

        const product = (item.product_name || "").toLowerCase();

        const barcode = (item.barcode || "").toLowerCase();

        const store = (item.store_name || "").toLowerCase();

        const sentBy = (item.sent_by_name || "").toLowerCase();

        const matchesKeyword =

            keyword === ""

            ||

            reference.includes(keyword)

            ||

            product.includes(keyword)

            ||

            barcode.includes(keyword)

            ||

            store.includes(keyword)

            ||

            sentBy.includes(keyword);

        const matchesStatus =

            status === ""

            ||

            item.status === status;

        const matchesMovement =

            movement === ""

            ||

            item.movement_type === movement;

        return (

            matchesKeyword

            &&

            matchesStatus

            &&

            matchesMovement

        );

    });

    currentPage = 1;

    renderTransfers();

}

/* ==========================================================
   SEARCH EVENTS
========================================================== */

$("#searchTransfer").on("keyup", function () {

    applyFilters();

});

$("#statusFilter").on("change", function () {

    applyFilters();

});

$("#movementFilter").on("change", function () {

    applyFilters();

});

/* ==========================================================
   RESET FILTERS
========================================================== */

$("#resetFilters").on("click", function () {

    $("#searchTransfer").val("");

    $("#statusFilter").val("");

    $("#movementFilter").val("");

    filteredTransfers = [...allTransfers];

    currentPage = 1;

    renderTransfers();

});

/* ==========================================================
   OPTIONAL LIVE SEARCH ON ENTER
========================================================== */

$("#searchTransfer").on("keypress", function (e) {

    if (e.which === 13) {

        e.preventDefault();

        applyFilters();

    }

});

/* ==========================================================
   VIEW TRANSFER
========================================================== */

$(document).on("click", ".viewTransfer", function () {

    const transferId = $(this).data("id");

    if (!transferId) {

        showMessage("Invalid transfer selected.");

        return;

    }

    selectedTransfer = transferId;

    $("#transferModal")
        .removeClass("hidden")
        .addClass("flex");

    $("#modalLoader").removeClass("hidden");

    $("#transferForm").hide();

    $.ajax({

        url: API_BASE_URL + "/dashboard/transfers/view.php",

        method: "GET",

        headers: {
            Authorization: "Bearer " + TOKEN
        },

        data: {
            id: transferId
        },

        dataType: "json",

        success: function (response) {

            $("#modalLoader").addClass("hidden");

            if (!response.status) {

                showMessage(
                    response.message || "Unable to load transfer."
                );

                closeTransferModal();

                return;

            }

            populateTransferForm(response.data);

            $("#transferForm").fadeIn(200);

        },

        error: function (xhr) {

            $("#modalLoader").addClass("hidden");

            closeTransferModal();

            showMessage(

                xhr.responseJSON?.message ||

                "Unable to load transfer."

            );

        }

    });

});


/* ==========================================================
   POPULATE FORM
========================================================== */

function populateTransferForm(item) {

    $("#transfer_id").val(item.id);

    $("#reference_no").val(item.reference_no || "");

    $("#status").val(item.status || "");

    $("#quantity").val(item.quantity || "");

    $("#movement_type").val(item.movement_type || "");

    $("#remarks").val(item.remarks || "");

    // Product
    $("#product_name").val(item.product?.name || "");
    $("#barcode").val(item.product?.barcode || "");
    $("#sku").val(item.product?.sku || "");

    // Store
    $("#store_name").val(item.store?.name || "");

    // Sender
    $("#sent_by").val(item.sender?.name || "-");

    // Reviewer
    $("#reviewed_by").val(item.reviewer?.name || "-");

    // Dates
    $("#created_at").val(
        item.created_at
            ? formatDateTime(item.created_at)
            : "-"
    );

    $("#reviewed_at").val(
        item.reviewed_at
            ? formatDateTime(item.reviewed_at)
            : "-"
    );

    const editable = item.status === "pending";

    $("#quantity").prop("readonly", !editable);

    $("#remarks").prop("readonly", !editable);

    $("#status").prop("disabled", !editable);

    $("#saveTransfer").toggle(editable);

    $("#deleteTransfer").toggle(editable);

    $("#acceptTransfer").toggle(editable);

    $("#rejectTransfer").toggle(editable);
}


/* ==========================================================
   CLOSE MODAL
========================================================== */

function closeTransferModal() {

    $("#transferForm")[0].reset();

    $("#transferForm").hide();

    $("#modalLoader").addClass("hidden");

    $("#transferModal")
        .removeClass("flex")
        .addClass("hidden");

}


/* ==========================================================
   CLOSE EVENTS
========================================================== */

$("#closeModal, #cancelModal").on("click", function () {

    closeTransferModal();

});

$("#transferModal").on("click", function (e) {

    if (e.target === this) {

        closeTransferModal();

    }

});

$(document).on("keydown", function (e) {

    if (e.key === "Escape") {

        closeTransferModal();

    }

});

/* ==========================================================
   UPDATE TRANSFER
========================================================== */

$("#transferForm").on("submit", function (e) {

    e.preventDefault();

    const transferId = $("#transfer_id").val();

    if (!transferId) {

        showMessage("Invalid transfer selected.");

        return;

    }

    const quantity = parseInt($("#quantity").val());

    if (isNaN(quantity) || quantity <= 0) {

        showMessage("Quantity must be greater than zero.");

        $("#quantity").focus();

        return;

    }

    const payload = {

        id: Number(transferId),

        quantity: quantity,

        status: $("#status").val(),

        remarks: $("#remarks").val().trim()

    };

    const btn = $("#saveTransfer");

    const originalText = btn.text();

    btn
        .prop("disabled", true)
        .text("Updating...");

    $.ajax({

        url: API_BASE_URL + "/dashboard/transfers/update.php",

        type: "POST",

        headers: {
            Authorization: "Bearer " + TOKEN
        },

        contentType: "application/json",

        dataType: "json",

        data: JSON.stringify(payload),

        success: function (response) {

            if (!response.status) {

                showMessage(

                    response.message ||

                    "Failed to update transfer."

                );

                return;

            }

            showMessage(

                response.message ||

                "Transfer updated successfully.",

                "success"

            );

            closeTransferModal();

            loadTransfers(currentPage);

        },

        error: function (xhr) {

            showMessage(

                xhr.responseJSON?.message ||

                "Unable to update transfer."

            );

        },

        complete: function () {

            btn
                .prop("disabled", false)
                .text(originalText);

        }

    });

});


/* ==========================================================
   ENABLE/DISABLE FORM
========================================================== */

function setTransferFormState(disabled = false) {

    $("#quantity").prop("disabled", disabled);

    $("#status").prop("disabled", disabled);

    $("#remarks").prop("disabled", disabled);

    $("#saveTransfer").prop("disabled", disabled);

}


/* ==========================================================
   VALIDATE TRANSFER
========================================================== */

function validateTransferForm() {

    const quantity = Number($("#quantity").val());

    if (!quantity || quantity <= 0) {

        showMessage("Quantity must be greater than zero.");

        $("#quantity").focus();

        return false;

    }

    const status = $("#status").val();

    if (!["pending", "accepted", "rejected"].includes(status)) {

        showMessage("Invalid transfer status.");

        return false;

    }

    return true;

}

/* ==========================================================
   ACCEPT TRANSFER
========================================================== */

$("#acceptTransfer").on("click", function () {

    const transferId = $("#transfer_id").val();

    if (!transferId) {

        showMessage("Invalid transfer selected.");

        return;

    }

    if (!confirm("Are you sure you want to accept this transfer?")) {

        return;

    }

    const btn = $(this);

    const originalText = btn.text();

    btn
        .prop("disabled", true)
        .text("Accepting...");

    $.ajax({

        url: API_BASE_URL + "/dashboard/transfers/accept.php",

        type: "POST",

        headers: {
            Authorization: "Bearer " + TOKEN
        },

        contentType: "application/json",

        dataType: "json",

        data: JSON.stringify({

            id: Number(transferId),

            remarks: $("#remarks").val().trim()

        }),

        success: function (response) {

            if (!response.status) {

                showMessage(

                    response.message ||

                    "Unable to accept transfer."

                );

                return;

            }

            showMessage(

                response.message ||

                "Transfer accepted successfully.",

                "success"

            );

            closeTransferModal();

            loadTransfers(currentPage);

        },

        error: function (xhr) {

            showMessage(

                xhr.responseJSON?.message ||

                "Server error while accepting transfer."

            );

        },

        complete: function () {

            btn
                .prop("disabled", false)
                .text(originalText);

        }

    });

});

/* ==========================================================
   REJECT TRANSFER
========================================================== */

$("#rejectTransfer").on("click", function () {

    const transferId = $("#transfer_id").val();

    if (!transferId) {

        showMessage("Invalid transfer selected.");

        return;

    }

    const remarks = $("#remarks").val().trim();

    if (remarks === "") {

        showMessage("Please enter a reason for rejecting this transfer.");

        $("#remarks").focus();

        return;

    }

    if (!confirm("Are you sure you want to reject this transfer?")) {

        return;

    }

    const btn = $(this);

    const originalText = btn.text();

    btn
        .prop("disabled", true)
        .text("Rejecting...");

    $.ajax({

        url: API_BASE_URL + "/dashboard/transfers/reject.php",

        type: "POST",

        headers: {
            Authorization: "Bearer " + TOKEN
        },

        contentType: "application/json",

        dataType: "json",

        data: JSON.stringify({

            id: Number(transferId),

            remarks: remarks

        }),

        success: function (response) {

            if (!response.status) {

                showMessage(

                    response.message ||

                    "Unable to reject transfer."

                );

                return;

            }

            showMessage(

                response.message ||

                "Transfer rejected successfully.",

                "success"

            );

            closeTransferModal();

            loadTransfers(currentPage);

        },

        error: function (xhr) {

            showMessage(

                xhr.responseJSON?.message ||

                "Server error while rejecting transfer."

            );

        },

        complete: function () {

            btn
                .prop("disabled", false)
                .text(originalText);

        }

    });

});


/* ==========================================================
   DELETE TRANSFER
========================================================== */

$("#deleteTransfer").on("click", function () {

    const transferId = $("#transfer_id").val();

    if (!transferId) {

        showMessage("Invalid transfer selected.");

        return;

    }

    if (!confirm(
        "Are you sure you want to delete this transfer?\n\nThis action cannot be undone."
    )) {

        return;

    }

    const btn = $(this);

    const originalText = btn.text();

    btn
        .prop("disabled", true)
        .text("Deleting...");

    $.ajax({

        url: API_BASE_URL + "/dashboard/transfers/delete.php",

        type: "POST",

        headers: {
            Authorization: "Bearer " + TOKEN
        },

        contentType: "application/json",

        dataType: "json",

        data: JSON.stringify({

            id: Number(transferId)

        }),

        success: function (response) {

            if (!response.status) {

                showMessage(

                    response.message ||

                    "Unable to delete transfer."

                );

                return;

            }

            showMessage(

                response.message ||

                "Transfer deleted successfully.",

                "success"

            );

            closeTransferModal();

            loadTransfers(currentPage);

        },

        error: function (xhr) {

            showMessage(

                xhr.responseJSON?.message ||

                "Server error while deleting transfer."

            );

        },

        complete: function () {

            btn
                .prop("disabled", false)
                .text(originalText);

        }

    });

});

/* ==========================================================
   SHOW RESPONSE MESSAGE
========================================================== */

function showMessage(message, type = "error") {

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

    $("html, body").animate({
        scrollTop: 0
    }, 200);

    setTimeout(function () {

        box.addClass("hidden");

    }, 3000);

}


/* ==========================================================
   ESCAPE HTML
========================================================== */

function escapeHtml(text) {

    return $("<div>")
        .text(text ?? "")
        .html();

}


/* ==========================================================
   FORMAT DATE
========================================================== */

function formatDate(date) {

    if (!date) {

        return "-";

    }

    const d = new Date(date);

    if (isNaN(d.getTime())) {

        return date;

    }

    return d.toLocaleDateString();

}


/* ==========================================================
   FORMAT DATE & TIME
========================================================== */

function formatDateTime(date) {

    if (!date) {

        return "-";

    }

    const d = new Date(date);

    if (isNaN(d.getTime())) {

        return date;

    }

    return d.toLocaleString();

}


/* ==========================================================
   STATUS BADGE
========================================================== */

function getStatusBadge(status) {

    switch (status) {

        case "accepted":

            return `
                <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700">
                    Accepted
                </span>
            `;

        case "rejected":

            return `
                <span class="inline-flex rounded-full bg-red-100 px-3 py-1 text-xs font-medium text-red-700">
                    Rejected
                </span>
            `;

        default:

            return `
                <span class="inline-flex rounded-full bg-yellow-100 px-3 py-1 text-xs font-medium text-yellow-700">
                    Pending
                </span>
            `;
    }

}


/* ==========================================================
   MOVEMENT BADGE
========================================================== */

function getMovementBadge(type) {

    switch (type) {

        case "return":

            return `
                <span class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-700">
                    Return
                </span>
            `;

        default:

            return `
                <span class="inline-flex rounded-full bg-indigo-100 px-3 py-1 text-xs font-medium text-indigo-700">
                    Send
                </span>
            `;
    }

}


/* ==========================================================
   CLEAR MODAL
========================================================== */

function clearTransferForm() {

    $("#transferForm")[0].reset();

    $("#transfer_id").val("");

    selectedTransfer = null;

}


/* ==========================================================
   CLOSE MODAL
========================================================== */

function closeTransferModal() {

    clearTransferForm();

    $("#modalLoader").addClass("hidden");

    $("#transferForm").show();

    $("#transferModal")
        .removeClass("flex")
        .addClass("hidden");

}


/* ==========================================================
   REFRESH TABLE
========================================================== */

function refreshTransferTable() {

    closeTransferModal();

    loadTransfers(currentPage);

}


/* ==========================================================
   RESET FILTERS
========================================================== */

function resetFilters() {

    $("#searchTransfer").val("");

    $("#statusFilter").val("");

    $("#movementFilter").val("");

    filteredTransfers = [...allTransfers];

    currentPage = 1;

    renderTransfers();

}


/* ==========================================================
   PAGINATION INFO
========================================================== */

function updatePagination() {

    const totalPages = Math.max(
        1,
        Math.ceil(filteredTransfers.length / rowsPerPage)
    );

    $("#pageInfo").text(

        `Page ${currentPage} of ${totalPages}`

    );

    $("#prevPage").prop(
        "disabled",
        currentPage === 1
    );

    $("#nextPage").prop(
        "disabled",
        currentPage >= totalPages
    );

}


/* ==========================================================
   ESC KEY CLOSES MODAL
========================================================== */

$(document).on("keydown", function (e) {

    if (e.key === "Escape") {

        closeTransferModal();

    }

});


/* ==========================================================
   CLICK OUTSIDE MODAL
========================================================== */

$("#transferModal").on("click", function (e) {

    if (e.target === this) {

        closeTransferModal();

    }

});


/* ==========================================================
   CLOSE BUTTONS
========================================================== */

$("#closeModal, #cancelModal").on("click", function () {

    closeTransferModal();

});

</script>


