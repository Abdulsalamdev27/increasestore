<?php
require_once __DIR__ . "/../includes/header.php";
?>

<div class="dashboard-container">

    <!-- RESPONSE -->
    <div
        id="responseBox"
        class="hidden mb-6 px-4 py-3 rounded text-sm font-medium">
    </div>

    <div class="dashboard-content">

        <!-- Page Header -->
        <div class="flex justify-between items-center mb-5">

            <div>

                <h2 class="font-semibold text-lg">
                    Product Transfers
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    View, manage and monitor product transfers to stores.
                </p>

            </div>

            <a
                href="create-transfer.php"
                class="px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">

                + Send Product

            </a>

        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-sm border mb-5">

            <div class="grid md:grid-cols-4 gap-4 p-5">

                <!-- Search -->
                <div>

                    <label class="label">
                        Search
                    </label>

                    <input
                        type="text"
                        id="searchTransfer"
                        class="input"
                        placeholder="Reference, Product or Store">

                </div>

                <!-- Status -->
                <div>

                    <label class="label">
                        Status
                    </label>

                    <select
                        id="statusFilter"
                        class="input">

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

                    <label class="label">
                        Movement
                    </label>

                    <select
                        id="movementFilter"
                        class="input">

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

                <!-- Refresh -->
                <div class="flex items-end">

                    <button
                        id="refreshTransfers"
                        class="w-full px-4 py-3 rounded-lg bg-gray-700 text-white hover:bg-gray-800">

                        Refresh

                    </button>

                </div>

            </div>

        </div>

        <!-- Table -->
        <div class="overflow-x-auto bg-white rounded-xl shadow-sm">

            <table class="min-w-full text-sm">

                <thead
                    class="bg-gray-50 text-gray-600 uppercase text-xs">

                    <tr>

                        <th class="px-6 py-3 text-left">
                            Reference
                        </th>

                        <th class="px-6 py-3 text-left">
                            Product
                        </th>

                        <th class="px-6 py-3 text-left">
                            Store
                        </th>

                        <th class="px-6 py-3 text-left">
                            Movement
                        </th>

                        <th class="px-6 py-3 text-left">
                            Qty
                        </th>

                        <th class="px-6 py-3 text-left">
                            Status
                        </th>

                        <th class="px-6 py-3 text-left">
                            Sent By
                        </th>

                        <th class="px-6 py-3 text-left">
                            Date
                        </th>

                        <th class="px-6 py-3 text-left">
                            Action
                        </th>

                    </tr>

                </thead>

                <tbody
                    id="transferTable"
                    class="divide-y">

                    <tr>

                        <td
                            colspan="9"
                            class="px-6 py-6 text-center text-gray-500">

                            Loading transfers...

                        </td>

                    </tr>

                </tbody>

            </table>

        </div>

        <!-- Pagination -->
        <div
            id="pagination"
            class="hidden flex justify-between items-center mt-5 text-sm text-gray-600">

            <button
                id="prevPage"
                class="px-3 py-2 rounded bg-gray-100 hover:bg-gray-200">

                ← Previous

            </button>

            <span id="pageInfo">
                Page 1 of 1
            </span>

            <button
                id="nextPage"
                class="px-3 py-2 rounded bg-gray-100 hover:bg-gray-200">

                Next →

            </button>

        </div>

    </div>

</div>

<!-- ================================================= -->
<!-- VIEW / EDIT TRANSFER MODAL -->
<!-- ================================================= -->

<div
    id="transferModal"
    class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">

    <div class="bg-white rounded-2xl shadow-xl w-full max-w-3xl">

        <!-- Header -->
        <div class="flex justify-between items-center border-b px-6 py-4">

            <h3 class="text-lg font-semibold">
                Transfer Details
            </h3>

            <button
                id="closeModal"
                class="text-2xl leading-none hover:text-red-600">

                &times;

            </button>

        </div>

        <!-- Body -->
        <form id="transferForm" class="p-6">

            <input
                type="hidden"
                id="transfer_id">

            <div class="grid md:grid-cols-2 gap-5">

                <!-- Reference -->
                <div>

                    <label class="label">
                        Reference No.
                    </label>

                    <input
                        type="text"
                        id="reference_no"
                        class="input bg-gray-100"
                        readonly>

                </div>

                <!-- Status -->
                <div>

                    <label class="label">
                        Status
                    </label>

                    <select
                        id="status"
                        class="input">

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

                    <label class="label">
                        Product
                    </label>

                    <input
                        type="text"
                        id="product_name"
                        class="input bg-gray-100"
                        readonly>

                </div>

                <!-- Store -->
                <div>

                    <label class="label">
                        Store
                    </label>

                    <input
                        type="text"
                        id="store_name"
                        class="input bg-gray-100"
                        readonly>

                </div>

                <!-- Movement -->
                <div>

                    <label class="label">
                        Movement
                    </label>

                    <input
                        type="text"
                        id="movement_type"
                        class="input bg-gray-100"
                        readonly>

                </div>

                <!-- Quantity -->
                <div>

                    <label class="label">
                        Quantity
                    </label>

                    <input
                        type="number"
                        id="quantity"
                        class="input">

                </div>

                <!-- Sent By -->
                <div>

                    <label class="label">
                        Sent By
                    </label>

                    <input
                        type="text"
                        id="sent_by"
                        class="input bg-gray-100"
                        readonly>

                </div>

                <!-- Created -->
                <div>

                    <label class="label">
                        Date Sent
                    </label>

                    <input
                        type="text"
                        id="created_at"
                        class="input bg-gray-100"
                        readonly>

                </div>

                <!-- Reviewed By -->
                <div>

                    <label class="label">
                        Reviewed By
                    </label>

                    <input
                        type="text"
                        id="reviewed_by"
                        class="input bg-gray-100"
                        readonly>

                </div>

                <!-- Reviewed Date -->
                <div>

                    <label class="label">
                        Reviewed Date
                    </label>

                    <input
                        type="text"
                        id="reviewed_at"
                        class="input bg-gray-100"
                        readonly>

                </div>

                <!-- Remarks -->
                <div class="md:col-span-2">

                    <label class="label">
                        Remarks
                    </label>

                    <textarea
                        id="remarks"
                        rows="4"
                        class="input"></textarea>

                </div>

            </div>

            <!-- Footer Buttons -->
            <div class="flex justify-end gap-3 mt-8">

                <button
                    type="button"
                    id="deleteTransfer"
                    class="px-5 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700">

                    Delete

                </button>

                <button
                    type="button"
                    id="cancelModal"
                    class="px-5 py-2 border rounded-lg hover:bg-gray-100">

                    Close

                </button>

                <button
                    type="submit"
                    id="saveTransfer"
                    class="px-5 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">

                    Update Transfer

                </button>

            </div>

        </form>

    </div>

</div>

<?php
include("../includes/footer.php");
?>

<?php
include("../includes/footer.php");
?>

<script>

const API_BASE_URL = "<?php echo API_BASE_URL; ?>";

/* ==========================================
   VARIABLES
========================================== */

let allTransfers = [];

let filteredTransfers = [];

let currentPage = 1;

const rowsPerPage = 10;

/* ==========================================
   LOAD TRANSFERS
========================================== */

function loadTransfers() {

    $.ajax({

        url: API_BASE_URL + "/dashboard/transfers/view-all.php",

        method: "GET",

        headers: {
            Authorization:
                "Bearer " + localStorage.getItem("auth_token")
        },

        dataType: "json",

        beforeSend() {

            $("#transferTable").html(`

                <tr>

                    <td colspan="9"
                        class="px-6 py-8 text-center text-gray-500">

                        Loading transfers...

                    </td>

                </tr>

            `);

        },

        success(res) {

            if (res.status) {

                allTransfers = res.data || [];

                filteredTransfers = [...allTransfers];

                currentPage = 1;

                renderTransfers();

                updatePagination();

            } else {

                $("#transferTable").html(`

                    <tr>

                        <td colspan="9"
                            class="px-6 py-8 text-center text-red-500">

                            ${escapeHtml(res.message)}

                        </td>

                    </tr>

                `);

            }

        },

        error(xhr) {

            $("#transferTable").html(`

                <tr>

                    <td colspan="9"
                        class="px-6 py-8 text-center text-red-500">

                        ${escapeHtml(
                            xhr.responseJSON?.message || "Server error."
                        )}

                    </td>

                </tr>

            `);

        }

    });

}

/* ==========================================
   RENDER TABLE
========================================== */

function renderTransfers() {

    const table = $("#transferTable");

    table.html("");

    if (!filteredTransfers.length) {

        table.html(`

            <tr>

                <td colspan="9"
                    class="px-6 py-8 text-center text-gray-500">

                    No transfers found.

                </td>

            </tr>

        `);

        $("#pagination").addClass("hidden");

        return;

    }

    $("#pagination").removeClass("hidden");

    const start = (currentPage - 1) * rowsPerPage;

    const transfers = filteredTransfers.slice(
        start,
        start + rowsPerPage
    );

    transfers.forEach(item => {

        let badge = "";

        switch (item.status) {

            case "pending":

                badge = `
                    <span
                        class="px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">
                        Pending
                    </span>
                `;

                break;

            case "accepted":

                badge = `
                    <span
                        class="px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                        Accepted
                    </span>
                `;

                break;

            case "rejected":

                badge = `
                    <span
                        class="px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">
                        Rejected
                    </span>
                `;

                break;

        }

        table.append(`

<tr class="hover:bg-gray-50">

    <td class="px-6 py-4">

        <div class="font-medium">

            ${escapeHtml(item.reference_no)}

        </div>

    </td>

    <td class="px-6 py-4">

        <div class="font-medium">

            ${escapeHtml(item.product_name)}

        </div>

        <div class="text-xs text-gray-500">

            ${escapeHtml(item.barcode || "")}

        </div>

    </td>

    <td class="px-6 py-4">

        ${escapeHtml(item.store_name)}

    </td>

    <td class="px-6 py-4">

        <span
            class="capitalize">

            ${escapeHtml(item.movement_type)}

        </span>

    </td>

    <td class="px-6 py-4">

        ${item.quantity}

    </td>

    <td class="px-6 py-4">

        ${badge}

    </td>

    <td class="px-6 py-4">

        ${escapeHtml(item.sent_by_name || "-")}

    </td>

    <td class="px-6 py-4">

        ${new Date(item.created_at).toLocaleDateString()}

    </td>

    <td class="px-6 py-4">

        <button

            class="viewTransfer px-3 py-1.5 border rounded hover:bg-gray-100"

            data-id="${item.id}"

            data-reference="${escapeHtml(item.reference_no)}"

            data-product="${escapeHtml(item.product_name)}"

            data-store="${escapeHtml(item.store_name)}"

            data-movement="${escapeHtml(item.movement_type)}"

            data-quantity="${item.quantity}"

            data-status="${item.status}"

            data-remarks="${escapeHtml(item.remarks || "")}"

            data-created="${item.created_at}"

            data-sent="${escapeHtml(item.sent_by_name || "")}"

            data-reviewed="${escapeHtml(item.reviewed_by_name || "")}"

            data-reviewedat="${item.reviewed_at || ""}">

            View

        </button>

    </td>

</tr>

        `);

    });

}

/* ==========================================
   REFRESH BUTTON
========================================== */

$("#refreshTransfers").click(function () {

    loadTransfers();

});

/* ===========================================================
   SEARCH + FILTERS + PAGINATION
   =========================================================== */

/* -------------------------
   SEARCH
-------------------------- */

$("#searchTransfer").on("keyup", function () {

    applyFilters();

});

/* -------------------------
   STATUS FILTER
-------------------------- */

$("#statusFilter").on("change", function () {

    applyFilters();

});

/* -------------------------
   MOVEMENT FILTER
-------------------------- */

$("#movementFilter").on("change", function () {

    applyFilters();

});

/* -------------------------
   APPLY FILTERS
-------------------------- */

function applyFilters() {

    const keyword = $("#searchTransfer")
        .val()
        .toLowerCase()
        .trim();

    const status = $("#statusFilter").val();

    const movement = $("#movementFilter").val();

    filteredTransfers = allTransfers.filter(function (item) {

        const matchesSearch =

            (item.reference_no || "")
                .toLowerCase()
                .includes(keyword)

            ||

            (item.product_name || "")
                .toLowerCase()
                .includes(keyword)

            ||

            (item.store_name || "")
                .toLowerCase()
                .includes(keyword)

            ||

            (item.barcode || "")
                .toLowerCase()
                .includes(keyword);

        const matchesStatus =

            status === ""

            ||

            item.status === status;

        const matchesMovement =

            movement === ""

            ||

            item.movement_type === movement;

        return (

            matchesSearch

            &&

            matchesStatus

            &&

            matchesMovement

        );

    });

    currentPage = 1;

    renderTransfers();

    updatePagination();

}

/* -------------------------
   PAGINATION
-------------------------- */

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

/* -------------------------
   PREVIOUS PAGE
-------------------------- */

$("#prevPage").click(function () {

    if (currentPage > 1) {

        currentPage--;

        renderTransfers();

        updatePagination();

    }

});

/* -------------------------
   NEXT PAGE
-------------------------- */

$("#nextPage").click(function () {

    const totalPages = Math.max(

        1,

        Math.ceil(filteredTransfers.length / rowsPerPage)

    );

    if (currentPage < totalPages) {

        currentPage++;

        renderTransfers();

        updatePagination();

    }

});

/* -------------------------
   RESET FILTERS (Optional)
-------------------------- */

function resetFilters() {

    $("#searchTransfer").val("");

    $("#statusFilter").val("");

    $("#movementFilter").val("");

    filteredTransfers = [...allTransfers];

    currentPage = 1;

    renderTransfers();

    updatePagination();

}

/* -------------------------
   INITIAL LOAD
-------------------------- */

$(document).ready(function () {

    loadTransfers();

});


/* ==========================================================
   PART 4B-3
   OPEN/CLOSE MODAL + POPULATE FORM + HELPERS
========================================================== */

/* --------------------------
   OPEN MODAL
-------------------------- */

$(document).on("click", ".viewTransfer", function () {

    $("#transfer_id").val($(this).data("id"));

    $("#reference_no").val($(this).data("reference"));

    $("#product_name").val($(this).data("product"));

    $("#store_name").val($(this).data("store"));

    $("#movement_type").val($(this).data("movement"));

    $("#quantity").val($(this).data("quantity"));

    $("#status").val($(this).data("status"));

    $("#remarks").val($(this).data("remarks"));

    $("#created_at").val(
        formatDateTime($(this).data("created"))
    );

    $("#sent_by").val($(this).data("sent"));

    $("#reviewed_by").val(
        $(this).data("reviewed") || "-"
    );

    $("#reviewed_at").val(

        $(this).data("reviewedat")
            ? formatDateTime($(this).data("reviewedat"))
            : "-"

    );

    /* --------------------------
       Only pending transfers
       can be edited
    -------------------------- */

    if ($(this).data("status") === "pending") {

        $("#quantity").prop("readonly", false);

        $("#remarks").prop("readonly", false);

        $("#status").prop("disabled", false);

        $("#saveTransfer").show();

        $("#deleteTransfer").show();

    } else {

        $("#quantity").prop("readonly", true);

        $("#remarks").prop("readonly", true);

        $("#status").prop("disabled", true);

        $("#saveTransfer").hide();

        $("#deleteTransfer").hide();

    }

    $("#transferModal")
        .removeClass("hidden")
        .addClass("flex");

});

/* --------------------------
   CLOSE MODAL
-------------------------- */

$("#closeModal, #cancelModal").on("click", function () {

    closeTransferModal();

});

/* --------------------------
   CLOSE ON BACKDROP
-------------------------- */

$("#transferModal").on("click", function (e) {

    if (e.target === this) {

        closeTransferModal();

    }

});

/* --------------------------
   ESC KEY
-------------------------- */

$(document).on("keydown", function (e) {

    if (e.key === "Escape") {

        closeTransferModal();

    }

});

/* --------------------------
   CLOSE FUNCTION
-------------------------- */

function closeTransferModal() {

    $("#transferForm")[0].reset();

    $("#transferModal")
        .removeClass("flex")
        .addClass("hidden");

}

/* ==========================================================
   RESPONSE MESSAGE
========================================================== */

function showMessage(message, type = "error") {

    const box = $("#responseBox");

    box.removeClass(
        "hidden bg-red-100 bg-green-100 text-red-700 text-green-700"
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

    $("html, body").animate({

        scrollTop: 0

    }, 200);

    setTimeout(function () {

        box.addClass("hidden");

    }, 3000);

}

/* ==========================================================
   FORMAT DATE
========================================================== */

function formatDateTime(dateString) {

    if (!dateString) return "";

    const d = new Date(dateString);

    if (isNaN(d.getTime())) {

        return dateString;

    }

    return d.toLocaleString();

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
   RESET MODAL
========================================================== */

function resetTransferModal() {

    $("#transfer_id").val("");

    $("#reference_no").val("");

    $("#product_name").val("");

    $("#store_name").val("");

    $("#movement_type").val("");

    $("#quantity").val("");

    $("#status").val("pending");

    $("#remarks").val("");

    $("#created_at").val("");

    $("#sent_by").val("");

    $("#reviewed_by").val("");

    $("#reviewed_at").val("");

}

/* ==========================================================
   OPTIONAL REFRESH
========================================================== */

function refreshTransferTable() {

    closeTransferModal();

    loadTransfers();

}

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

        id: transferId,

        quantity: quantity,

        status: $("#status").val(),

        remarks: $("#remarks").val().trim()

    };

    const btn = $("#saveTransfer");

    btn
        .prop("disabled", true)
        .text("Updating...");

    $.ajax({

        url: API_BASE_URL + "/dashboard/transfers/update.php",

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
                    res.message || "Transfer updated successfully.",
                    "success"
                );

                refreshTransferTable();

            } else {

                showMessage(
                    res.message || "Unable to update transfer."
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
                .prop("disabled", false)
                .text("Update Transfer");

        }

    });

});

/* ==========================================================
   REFRESH TABLE
========================================================== */

function refreshTransferTable() {

    closeTransferModal();

    loadTransfers();

}

/* ==========================================================
   CLEAR FORM
========================================================== */

function clearTransferForm() {

    $("#transfer_id").val("");

    $("#reference_no").val("");

    $("#product_name").val("");

    $("#store_name").val("");

    $("#movement_type").val("");

    $("#quantity").val("");

    $("#status").val("pending");

    $("#remarks").val("");

    $("#created_at").val("");

    $("#sent_by").val("");

    $("#reviewed_by").val("");

    $("#reviewed_at").val("");

}

/* ==========================================================
   FORM VALIDATION
========================================================== */

function validateTransferForm() {

    const quantity = parseInt($("#quantity").val());

    if (isNaN(quantity) || quantity <= 0) {

        showMessage("Please enter a valid quantity.");

        $("#quantity").focus();

        return false;

    }

    const status = $("#status").val();

    const validStatus = [

        "pending",

        "accepted",

        "rejected"

    ];

    if (!validStatus.includes(status)) {

        showMessage("Invalid transfer status.");

        return false;

    }

    return true;

}


/* ==========================================================
   PART 4C-2
   DELETE TRANSFER + CONFIRMATION + HELPERS
========================================================== */

/* ----------------------------------------
   DELETE TRANSFER
----------------------------------------- */

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

    btn
        .prop("disabled", true)
        .text("Deleting...");

    $.ajax({

        url: API_BASE_URL + "/dashboard/transfers/delete.php",

        method: "POST",

        headers: {
            Authorization:
                "Bearer " + localStorage.getItem("auth_token")
        },

        contentType: "application/json",

        dataType: "json",

        data: JSON.stringify({

            id: transferId

        }),

        success(res) {

            if (res.status) {

                showMessage(

                    res.message ||
                    "Transfer deleted successfully.",

                    "success"

                );

                refreshTransferTable();

            } else {

                showMessage(

                    res.message ||
                    "Unable to delete transfer."

                );

            }

        },

        error(xhr) {

            showMessage(

                xhr.responseJSON?.message ||
                "Server error."

            );

        },

        complete() {

            btn
                .prop("disabled", false)
                .text("Delete");

        }

    });

});

/* ----------------------------------------
   CLOSE MODAL
----------------------------------------- */

function closeTransferModal() {

    clearTransferForm();

    $("#transferModal")
        .removeClass("flex")
        .addClass("hidden");

}

/* ----------------------------------------
   REFRESH TABLE
----------------------------------------- */

function refreshTransferTable() {

    closeTransferModal();

    loadTransfers();

}

/* ----------------------------------------
   RESET FILTERS
----------------------------------------- */

function resetFilters() {

    $("#searchTransfer").val("");

    $("#statusFilter").val("");

    $("#movementFilter").val("");

    filteredTransfers = [...allTransfers];

    currentPage = 1;

    renderTransfers();

    updatePagination();

}

/* ----------------------------------------
   BADGE HELPER
----------------------------------------- */

function getStatusBadge(status) {

    switch (status) {

        case "accepted":

            return `
                <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                    Accepted
                </span>
            `;

        case "rejected":

            return `
                <span class="px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">
                    Rejected
                </span>
            `;

        default:

            return `
                <span class="px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">
                    Pending
                </span>
            `;

    }

}

/* ----------------------------------------
   MOVEMENT HELPER
----------------------------------------- */

function getMovementBadge(type) {

    if (type === "return") {

        return `
            <span class="px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                Return
            </span>
        `;

    }

    return `
        <span class="px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-700">
            Send
        </span>
    `;

}

/* ----------------------------------------
   DATE FORMAT
----------------------------------------- */

function formatDate(date) {

    if (!date) {

        return "-";

    }

    return new Date(date).toLocaleString();

}

/* ----------------------------------------
   XSS PROTECTION
----------------------------------------- */

function escapeHtml(text) {

    return $("<div>")
        .text(text ?? "")
        .html();

}

/* ----------------------------------------
   RESPONSE MESSAGE
----------------------------------------- */

function showMessage(message, type = "error") {

    const box = $("#responseBox");

    box.removeClass(
        "hidden bg-red-100 bg-green-100 text-red-700 text-green-700"
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

    $("html, body").animate({

        scrollTop: 0

    }, 200);

    setTimeout(function () {

        box.addClass("hidden");

    }, 3000);

}

/* ----------------------------------------
   INITIALIZE PAGE
----------------------------------------- */

$(document).ready(function () {

    loadTransfers();

});

</script>