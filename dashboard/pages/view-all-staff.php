<?php
require_once __DIR__ . "/../includes/header.php";
?>

<div class="dashboard-container">

    <!-- RESPONSE -->
    <div id="responseBox" class="hidden mb-6 px-4 py-3 rounded text-sm font-medium"></div>

    <div class="dashboard-content">

        <!-- Header -->
        <div class="flex justify-between items-center mb-5">

            <h2 class="font-semibold text-lg">
                Staff
            </h2>

            <a href="create-staff.php"
                class="px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">

                + Create Staff

            </a>

        </div>

        <!-- Staff Table -->
        <div class="overflow-x-auto bg-white rounded-xl shadow-sm">

            <table class="min-w-full text-sm">

                <thead class="bg-gray-50 text-gray-600 uppercase text-xs">

                    <tr>

                        <th class="px-6 py-3 text-left">
                            Staff
                        </th>

                        <th class="px-6 py-3 text-left">
                            Store
                        </th>

                        <th class="px-6 py-3 text-left">
                            Position
                        </th>

                        <th class="px-6 py-3 text-left">
                            Phone
                        </th>

                        <th class="px-6 py-3 text-left">
                            Status
                        </th>

                        <th class="px-6 py-3 text-left">
                            Created
                        </th>

                        <th class="px-6 py-3 text-left">
                            Action
                        </th>

                    </tr>

                </thead>

                <tbody id="staffTable" class="divide-y"></tbody>

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

<!-- ========================= -->
<!-- EDIT STAFF MODAL -->
<!-- ========================= -->

<div
    id="editStaffModal"
    class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">

    <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl">

        <div class="flex justify-between items-center px-6 py-4 border-b">

            <h2 class="font-semibold text-lg">
                Edit Staff
            </h2>

            <button
                id="closeModal"
                class="text-2xl text-gray-500 hover:text-black">

                &times;

            </button>

        </div>

        <form
            id="editStaffForm"
            class="p-6 space-y-5">

            <input
                type="hidden"
                id="editStaffId">

            <div class="grid md:grid-cols-2 gap-5">

                <div>

                    <label class="label">
                        First Name
                    </label>

                    <input
                        type="text"
                        id="editFirstName"
                        class="input"
                        required>

                </div>

                <div>

                    <label class="label">
                        Last Name
                    </label>

                    <input
                        type="text"
                        id="editLastName"
                        class="input"
                        required>

                </div>

                <div>

                    <label class="label">
                        Email
                    </label>

                    <input
                        type="email"
                        id="editEmail"
                        class="input">

                </div>

                <div>

                    <label class="label">
                        Phone
                    </label>

                    <input
                        type="text"
                        id="editPhone"
                        class="input">

                </div>

                <div>

                    <label class="label">
                        Position
                    </label>

                    <input
                        type="text"
                        id="editPosition"
                        class="input">

                </div>

                <div>

                    <label class="label">
                        Store
                    </label>

                    <select
                        id="editStore"
                        class="input"
                        required>

                        <option value="">
                            Loading stores...
                        </option>

                    </select>

                </div>

                <div class="md:col-span-2">

                    <label class="label">
                        Status
                    </label>

                    <select
                        id="editStatus"
                        class="input">

                        <option value="1">
                            Active
                        </option>

                        <option value="0">
                            Inactive
                        </option>

                    </select>

                </div>

            </div>

            <div class="flex justify-end gap-3 pt-3">

                <button
                    type="button"
                    id="cancelModal"
                    class="px-5 py-2 rounded border hover:bg-gray-100">

                    Cancel

                </button>

                <button
                    type="submit"
                    id="updateStaffBtn"
                    class="px-5 py-2 rounded bg-indigo-600 text-white hover:bg-indigo-700">

                    Update Staff

                </button>

            </div>

        </form>

    </div>

</div>

<?php include("../includes/footer.php"); ?>

<script>

const API_BASE_URL = "<?php echo API_BASE_URL; ?>";

let allStaff = [];

let currentPage = 1;

const rowsPerPage = 7;

// ----------------------
// LOAD STAFF
// ----------------------

loadStaff();

function loadStaff() {

    $.ajax({

        url: API_BASE_URL + "/dashboard/staff/get-stores.php",

        method: "GET",

        headers: {
            Authorization: "Bearer " + localStorage.getItem("auth_token")
        },

        dataType: "json",

        success(res) {

            if (res.status) {

                allStaff = res.data || [];

                currentPage = 1;

                renderStaff();

                updatePagination();

            } else {

                showMessage(res.message || "Unable to load staff.");

            }

        },

        error(xhr) {

            showMessage(
                xhr.responseJSON?.message || "Server error."
            );

        }

    });

}

// ----------------------
// LOAD STORES DROPDOWN
// ----------------------

loadStores();

function loadStores() {

    $.ajax({

        url: API_BASE_URL + "/dashboard/staff/get-stores.php",

        method: "GET",

        headers: {
            Authorization: "Bearer " + localStorage.getItem("auth_token")
        },

        dataType: "json",

        success(res) {

            if (!res.status) return;

            let options = "";

            res.data.forEach(function(store){

                options += `
                    <option value="${store.id}">
                        ${store.store_name}
                    </option>
                `;

            });

            $("#editStore").html(options);

        }

    });

}

// ----------------------
// RENDER STAFF
// ----------------------

function renderStaff(){

    const table = $("#staffTable");

    table.html("");

    if(!allStaff.length){

        table.html(`
            <tr>

                <td colspan="7"
                    class="px-6 py-6 text-center text-gray-500">

                    No staff found.

                </td>

            </tr>
        `);

        $("#pagination").addClass("hidden");

        return;

    }

    $("#pagination").removeClass("hidden");

    const start = (currentPage - 1) * rowsPerPage;

    const staff = allStaff.slice(start, start + rowsPerPage);

    staff.forEach(item => {

        table.append(`

        <tr class="hover:bg-gray-50">

            <td class="px-6 py-4">

                <div class="font-medium text-gray-800">

                    ${escapeHtml(item.first_name)}
                    ${escapeHtml(item.last_name)}

                </div>

                <div class="text-xs text-gray-500">

                    ${escapeHtml(item.email || "-")}

                </div>

            </td>

            <td class="px-6 py-4">

                ${escapeHtml(item.store_name)}

            </td>

            <td class="px-6 py-4">

                ${escapeHtml(item.position || "-")}

            </td>

            <td class="px-6 py-4">

                ${escapeHtml(item.phone || "-")}

            </td>

            <td class="px-6 py-4">

                <span class="px-2 py-1 rounded-full text-xs
                ${item.is_active == 1
                    ? 'bg-green-100 text-green-700'
                    : 'bg-red-100 text-red-700'}">

                    ${item.is_active == 1 ? "Active" : "Inactive"}

                </span>

            </td>

            <td class="px-6 py-4">

                ${new Date(item.created_at).toLocaleDateString()}

            </td>

            <td class="px-6 py-4">

                <div class="flex gap-2">

                    <button

                        class="editBtn px-3 py-1.5 border rounded hover:bg-gray-100"

                        data-id="${item.id}"

                        data-store="${item.store_id}"

                        data-first="${escapeHtml(item.first_name)}"

                        data-last="${escapeHtml(item.last_name)}"

                        data-email="${escapeHtml(item.email || "")}"

                        data-phone="${escapeHtml(item.phone || "")}"

                        data-position="${escapeHtml(item.position || "")}"

                        data-status="${item.is_active}">

                        Edit

                    </button>

                    <button

                        class="deleteBtn px-3 py-1.5 rounded bg-red-600 text-white hover:bg-red-700"

                        data-id="${item.id}">

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

function updatePagination(){

    const totalPages = Math.ceil(allStaff.length / rowsPerPage);

    $("#pageInfo").text(`Page ${currentPage} of ${totalPages}`);

    $("#prevPage").prop("disabled", currentPage === 1);

    $("#nextPage").prop("disabled", currentPage === totalPages);

}

$("#prevPage").click(function(){

    if(currentPage > 1){

        currentPage--;

        renderStaff();

        updatePagination();

    }

});

$("#nextPage").click(function(){

    const totalPages = Math.ceil(allStaff.length / rowsPerPage);

    if(currentPage < totalPages){

        currentPage++;

        renderStaff();

        updatePagination();

    }

});

// ----------------------
// OPEN EDIT MODAL
// ----------------------

$(document).on("click", ".editBtn", function(){

    $("#editStaffId").val($(this).data("id"));

    $("#editStore").val($(this).data("store"));

    $("#editFirstName").val($(this).data("first"));

    $("#editLastName").val($(this).data("last"));

    $("#editEmail").val($(this).data("email"));

    $("#editPhone").val($(this).data("phone"));

    $("#editPosition").val($(this).data("position"));

    $("#editStatus").val($(this).data("status"));

    $("#editStaffModal")
        .removeClass("hidden")
        .addClass("flex");

});

// ----------------------
// CLOSE MODAL
// ----------------------

$("#closeModal, #cancelModal").click(function(){

    $("#editStaffModal")
        .removeClass("flex")
        .addClass("hidden");

});


// ----------------------
// UPDATE STAFF
// ----------------------

$("#editStaffForm").on("submit", function (e) {

    e.preventDefault();

    const btn = $("#updateStaffBtn");

    btn
        .text("Updating...")
        .prop("disabled", true);

    const payload = {

        id: $("#editStaffId").val(),

        store_id: $("#editStore").val(),

        first_name: $("#editFirstName").val(),

        last_name: $("#editLastName").val(),

        email: $("#editEmail").val(),

        phone: $("#editPhone").val(),

        position: $("#editPosition").val(),

        is_active: $("#editStatus").val()

    };

    $.ajax({

        url: API_BASE_URL + "/dashboard/staff/update.php",

        method: "POST",

        contentType: "application/json",

        dataType: "json",

        headers: {
            Authorization: "Bearer " + localStorage.getItem("auth_token")
        },

        data: JSON.stringify(payload),

        success(res){

            if(res.status){

                $("#editStaffModal")
                    .removeClass("flex")
                    .addClass("hidden");

                showMessage(
                    res.message || "Staff updated successfully.",
                    "success"
                );

                loadStaff();

            }else{

                showMessage(
                    res.message || "Unable to update staff."
                );

            }

        },

        error(xhr){

            showMessage(
                xhr.responseJSON?.message || "Server error."
            );

        },

        complete(){

            btn
                .text("Update Staff")
                .prop("disabled", false);

        }

    });

});

// ----------------------
// DELETE STAFF
// ----------------------

$(document).on("click", ".deleteBtn", function(){

    const id = $(this).data("id");

    if(!confirm("Are you sure you want to delete this staff?")){

        return;

    }

    $.ajax({

        url: API_BASE_URL + "/dashboard/staff/delete.php",

        method: "POST",

        contentType: "application/json",

        dataType: "json",

        headers:{
            Authorization: "Bearer " + localStorage.getItem("auth_token")
        },

        data: JSON.stringify({

            id: id

        }),

        success(res){

            if(res.status){

                showMessage(
                    res.message || "Staff deleted successfully.",
                    "success"
                );

                loadStaff();

            }else{

                showMessage(
                    res.message || "Unable to delete staff."
                );

            }

        },

        error(xhr){

            showMessage(
                xhr.responseJSON?.message || "Server error."
            );

        }

    });

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

    box.removeClass("hidden");

    setTimeout(function () {

        box.addClass("hidden");

    }, 3000);

}

// ----------------------
// XSS PROTECTION
// ----------------------

function escapeHtml(text){

    return $("<div>").text(text ?? "").html();

}

</script>