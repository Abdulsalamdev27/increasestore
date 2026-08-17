<?php
require_once __DIR__ . "/../includes/header.php";
?>

<section class="w-full">

    <!-- Page Title -->
    <article class="mb-6">

        <h1 class="font-title font-bold text-2xl">
            Create Staff
        </h1>

        <p class="text-gray-600 text-sm">
            Fill in the details below to create a new staff member.
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
            id="staffForm"
            class="space-y-10 py-6">

            <!-- STAFF INFORMATION -->
            <section class="px-6">

                <h2 class="font-title font-semibold text-lg mb-4">
                    Staff Information
                </h2>

                <div class="grid md:grid-cols-2 gap-5">

                    <!-- Store -->
                    <div class="md:col-span-2">

                        <label class="label">
                            Store
                        </label>

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

                    <!-- First Name -->
                    <div>

                        <label class="label">
                            First Name
                        </label>

                        <input
                            type="text"
                            name="first_name"
                            class="input"
                            placeholder="Enter first name"
                            required>

                    </div>

                    <!-- Last Name -->
                    <div>

                        <label class="label">
                            Last Name
                        </label>

                        <input
                            type="text"
                            name="last_name"
                            class="input"
                            placeholder="Enter last name"
                            required>

                    </div>

                    <!-- Email -->
                    <div>

                        <label class="label">
                            Email Address
                        </label>

                        <input
                            type="email"
                            name="email"
                            class="input"
                            placeholder="example@email.com">

                    </div>

                    <!-- Phone -->
                    <div>

                        <label class="label">
                            Phone Number
                        </label>

                        <input
                            type="text"
                            name="phone"
                            class="input"
                            placeholder="08012345678">

                    </div>

                    <!-- Position -->
                    <div>

                        <label class="label">
                            Position
                        </label>

                        <input
                            type="text"
                            name="position"
                            class="input"
                            placeholder="Manager, Cashier, Sales Rep">

                    </div>

                    <!-- Status -->
                    <div>

                        <label class="label">
                            Status
                        </label>

                        <select
                            name="is_active"
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

            </section>

            <!-- Submit -->
            <div class="px-6 pt-4">

                <button
                    type="submit"
                    id="createStaffBtn"
                    class="w-full py-3 rounded-xl bg-indigo-600 text-white font-semibold shadow-md hover:bg-indigo-700 transition">

                    Create Staff

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

let allStores = [];

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

                renderStores();

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

// ----------------------
// RENDER STORES
// ----------------------

function renderStores() {

    const select = $("#store_id");

    select.html("");

    select.append(`
        <option value="">
            Select Store
        </option>
    `);

    allStores.forEach(function(store){

        select.append(`

            <option value="${store.id}">
                ${escapeHtml(store.store_name)}
            </option>

        `);

    });

}

// ----------------------
// CREATE STAFF
// ----------------------

$("#staffForm").on("submit", function(e){

    e.preventDefault();

    const btn = $("#createStaffBtn");

    btn.text("Creating...").prop("disabled", true);

    const form = this;

    const fd = new FormData(form);

    const payload = Object.fromEntries(fd.entries());

    $.ajax({

        url: API_BASE_URL + "/dashboard/staff/create.php",

        method: "POST",

        contentType: "application/json",

        dataType: "json",

        headers: {
            Authorization: "Bearer " + localStorage.getItem("auth_token")
        },

        data: JSON.stringify(payload),

        success(res){

            if(res.status){

                showMessage(
                    res.message || "Staff created successfully.",
                    "success"
                );

                form.reset();

                renderStores();

                setTimeout(function(){

                    window.location.href = "view-all-staff.php";

                },1000);

            }else{

                showMessage(
                    res.message || "Unable to create staff."
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
                .text("Create Staff")
                .prop("disabled", false);

        }

    });

});

// ----------------------
// MESSAGE
// ----------------------

function showMessage(message, type="error"){

    const box = $("#responseBox");

    box.removeClass(
        "hidden bg-green-100 bg-red-100 text-green-700 text-red-700"
    );

    if(type==="success"){

        box.addClass("bg-green-100 text-green-700");

    }else{

        box.addClass("bg-red-100 text-red-700");

    }

    box.text(message);

    box.removeClass("hidden");

    setTimeout(function(){

        box.addClass("hidden");

    },3000);

}

// ----------------------
// XSS
// ----------------------

function escapeHtml(text){

    return $("<div>").text(text ?? "").html();

}

// ----------------------
// INIT
// ----------------------

$(function(){

    loadStores();

});

</script>