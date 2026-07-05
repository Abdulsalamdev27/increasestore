<?php
require_once __DIR__ . "/../includes/header.php";
?>

<div class="dashboard-container">

    <!-- RESPONSE -->
    <div id="responseBox" class="hidden mb-6 px-4 py-3 rounded text-sm font-medium"></div>

    <div class="dashboard-content">

        <div class="flex justify-between items-center mb-5">
            <h2 class="font-semibold text-lg">
                Stores
            </h2>

            <a href="create-store.php"
                class="px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">
                + Create Store
            </a>
        </div>

        <div class="overflow-x-auto bg-white rounded-xl shadow-sm">

            <table class="min-w-full text-sm">

                <thead class="bg-gray-50 text-gray-600 uppercase text-xs">

                    <tr>
                        <th class="px-6 py-3 text-left">Store</th>
                        <th class="px-6 py-3 text-left">Email</th>
                        <th class="px-6 py-3 text-left">Phone</th>
                        <th class="px-6 py-3 text-left">Created</th>
                        <th class="px-6 py-3 text-left">Action</th>
                    </tr>

                </thead>

                <tbody id="storesTable" class="divide-y">
                </tbody>

            </table>

        </div>

        <!-- Pagination -->
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

    </div>

</div>

<!-- ==========================================
EDIT STORE MODAL
=========================================== -->

<div
    id="editStoreModal"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">

    <div class="bg-white rounded-2xl shadow-xl w-full max-w-xl mx-4">

        <!-- Header -->
        <div class="flex items-center justify-between border-b px-6 py-4">

            <h2 class="text-lg font-semibold">
                Edit Store
            </h2>

            <button
                type="button"
                id="closeModal"
                class="text-2xl text-gray-500 hover:text-gray-700">

                &times;

            </button>

        </div>

        <!-- Form -->
        <form id="editStoreForm" class="p-6 space-y-5">

            <input
                type="hidden"
                id="editStoreId"
                name="id">

            <div>

                <label class="label">
                    Store Name
                </label>

                <input
                    type="text"
                    id="editStoreName"
                    name="store_name"
                    class="input"
                    required>

            </div>

            <div>

                <label class="label">
                    Email Address
                </label>

                <input
                    type="email"
                    id="editStoreEmail"
                    name="email"
                    class="input">

            </div>

            <div>

                <label class="label">
                    Phone Number
                </label>

                <input
                    type="text"
                    id="editStorePhone"
                    name="phone"
                    class="input">

            </div>

            <div>

                <label class="label">
                    Store Address
                </label>

                <textarea
                    id="editStoreAddress"
                    name="address"
                    rows="4"
                    class="input"></textarea>

            </div>

            <div class="flex justify-end gap-3 pt-2">

                <button
                    type="button"
                    id="cancelModal"
                    class="px-4 py-2 rounded-lg border hover:bg-gray-100">

                    Cancel

                </button>

                <button
                    type="submit"
                    id="updateStoreBtn"
                    class="px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">

                    Update Store

                </button>

            </div>

        </form>

    </div>

</div>


<?php include("../includes/footer.php"); ?>

<script>

const API_BASE_URL = "<?php echo API_BASE_URL; ?>";

let allStores = [];

let currentPage = 1;

const rowsPerPage = 7;

// ----------------------
// LOAD STORES
// ----------------------

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

                currentPage = 1;

                renderStores();

                updatePagination();

            } else {

                showMessage(res.message || "Unable to load stores");

            }

        },

        error(xhr) {

            showMessage(
                xhr.responseJSON?.message || "Server error."
            );

        }

    });

}

loadStores();

// ----------------------
// RENDER STORES
// ----------------------

function renderStores() {

    const table = $("#storesTable");

    table.html("");

    if (!allStores.length) {

        table.html(`
            <tr>
                <td colspan="5" class="px-6 py-6 text-center text-gray-500">
                    No stores found.
                </td>
            </tr>
        `);

        $("#pagination").addClass("hidden");

        return;

    }

    $("#pagination").removeClass("hidden");

    const start = (currentPage - 1) * rowsPerPage;

    const stores = allStores.slice(start, start + rowsPerPage);

    stores.forEach(store => {

        table.append(`

<tr class="hover:bg-gray-50">

    <td class="px-6 py-4">

        <div class="font-medium text-gray-800">
            ${escapeHtml(store.store_name)}
        </div>

        <div class="text-xs text-gray-500">
            ${escapeHtml(store.address)}
        </div>

    </td>

    <td class="px-6 py-4">
        ${escapeHtml(store.email || "-")}
    </td>

    <td class="px-6 py-4">
        ${escapeHtml(store.phone || "-")}
    </td>

    <td class="px-6 py-4">
        ${new Date(store.created_at).toLocaleDateString()}
    </td>

    <td class="px-6 py-4">

        <div class="flex gap-2">

            <button
                class="editBtn px-3 py-1.5 border rounded hover:bg-gray-100"
                data-id="${store.id}"
                data-name="${escapeHtml(store.store_name)}"
                data-email="${escapeHtml(store.email || "")}"
                data-phone="${escapeHtml(store.phone || "")}"
                data-address="${escapeHtml(store.address || "")}">

                Edit

            </button>

            <button
                class="deleteBtn px-3 py-1.5 rounded bg-red-600 text-white hover:bg-red-700"
                data-id="${store.id}">

                Delete

            </button>

        </div>

    </td>

</tr>

        `);

    });

}

// ----------------------
// PAGINATION
// ----------------------

function updatePagination() {

    const totalPages = Math.ceil(allStores.length / rowsPerPage);

    $("#pageInfo").text(`Page ${currentPage} of ${totalPages}`);

    $("#prevPage").prop("disabled", currentPage === 1);

    $("#nextPage").prop("disabled", currentPage === totalPages);

}

$("#prevPage").click(function () {

    if (currentPage > 1) {

        currentPage--;

        renderStores();

        updatePagination();

    }

});

$("#nextPage").click(function () {

    const totalPages = Math.ceil(allStores.length / rowsPerPage);

    if (currentPage < totalPages) {

        currentPage++;

        renderStores();

        updatePagination();

    }

});

// ----------------------
// OPEN EDIT MODAL
// ----------------------

$(document).on("click", ".editBtn", function () {

    $("#editStoreId").val($(this).data("id"));
    $("#editStoreName").val($(this).data("name"));
    $("#editStoreEmail").val($(this).data("email"));
    $("#editStorePhone").val($(this).data("phone"));
    $("#editStoreAddress").val($(this).data("address"));

    $("#editStoreModal")
        .removeClass("hidden")
        .addClass("flex");

});

// ----------------------
// CLOSE MODAL
// ----------------------

$("#closeModal, #cancelModal").click(function () {

    $("#editStoreModal")
        .removeClass("flex")
        .addClass("hidden");

});

// Close when clicking outside the modal

$("#editStoreModal").click(function (e) {

    if (e.target === this) {

        $(this)
            .removeClass("flex")
            .addClass("hidden");

    }

});

// ----------------------
// MESSAGE
// ----------------------

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

}

// ----------------------
// XSS PROTECTION
// ----------------------

function escapeHtml(text) {

    return $("<div>").text(text ?? "").html();

}


// ----------------------
// UPDATE STORE
// ----------------------

$("#editStoreForm").on("submit", function (e) {

    e.preventDefault();

    const btn = $("#updateStoreBtn");

    btn
        .text("Updating...")
        .prop("disabled", true);

    const payload = {

        id: $("#editStoreId").val(),

        store_name: $("#editStoreName").val(),

        email: $("#editStoreEmail").val(),

        phone: $("#editStorePhone").val(),

        address: $("#editStoreAddress").val()

    };

    $.ajax({

        url: API_BASE_URL + "/dashboard/stores/update.php",

        method: "POST",

        contentType: "application/json",

        dataType: "json",

        headers: {
            Authorization: "Bearer " + localStorage.getItem("auth_token")
        },

        data: JSON.stringify(payload),

        success(res) {

            if (res.status) {

                $("#editStoreModal")
                    .removeClass("flex")
                    .addClass("hidden");

                showMessage(
                    res.message || "Store updated successfully.",
                    "success"
                );

                loadStores();

            } else {

                showMessage(
                    res.message || "Unable to update store."
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
                .text("Update Store")
                .prop("disabled", false);

        }

    });

});

// ----------------------
// DELETE STORE
// ----------------------

$(document).on("click", ".deleteBtn", function () {

    const id = $(this).data("id");

    if (!confirm("Are you sure you want to delete this store?")) {

        return;

    }

    $.ajax({

        url: API_BASE_URL + "/dashboard/stores/delete.php",

        method: "POST",

        contentType: "application/json",

        dataType: "json",

        headers: {
            Authorization: "Bearer " + localStorage.getItem("auth_token")
        },

        data: JSON.stringify({

            id: id

        }),

        success(res) {

            if (res.status) {

                showMessage(
                    res.message || "Store deleted successfully.",
                    "success"
                );

                loadStores();

            } else {

                showMessage(
                    res.message || "Unable to delete store."
                );

            }

        },

        error(xhr) {

            showMessage(
                xhr.responseJSON?.message || "Server error."
            );

        }

    });

});


</script>





