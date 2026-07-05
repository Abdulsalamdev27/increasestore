<?php
require_once __DIR__ . "/../includes/header.php";
?>

<section class="w-full">

  <!-- Page Title -->
  <article class="mb-6">
    <h1 class="font-title font-bold text-2xl">
      Create Store
    </h1>
    <p class="text-gray-600 text-sm">
      Fill in the details below to create a new store.
    </p>
  </article>

  <!-- Response Message -->
  <div id="responseBox" class="hidden mb-6 px-4 py-3 rounded text-sm font-medium"></div>

  <!-- Form Card -->
  <article class="bg-white rounded-2xl border border-gray-200 shadow-lg backdrop-blur-md">

    <form id="storeForm" class="space-y-10 py-6">

      <!-- STORE INFORMATION -->
      <section class="px-6">
        <h2 class="font-title font-semibold text-lg mb-4">
          Store Information
        </h2>

        <div class="grid md:grid-cols-2 gap-5">

          <div>
            <label class="label">Store Name</label>
            <input
              type="text"
              name="store_name"
              class="input"
              placeholder="Enter store name"
              required
            />
          </div>

          <div>
            <label class="label">Email Address</label>
            <input
              type="email"
              name="email"
              class="input"
              placeholder="example@email.com"
            />
          </div>

          <div>
            <label class="label">Phone Number</label>
            <input
              type="text"
              name="phone"
              class="input"
              placeholder="08012345678"
            />
          </div>

          <div class="md:col-span-2">
            <label class="label">Store Address</label>
            <textarea
              name="address"
              rows="4"
              class="input"
              placeholder="Enter store address"
              required
            ></textarea>
          </div>

        </div>
      </section>

      <!-- SUBMIT -->
      <div class="px-6 pt-4">
        <button
          type="submit"
          id="createStoreBtn"
          class="w-full py-3 rounded-xl bg-indigo-600 text-white font-semibold shadow-md hover:bg-indigo-700 transition">
          Create Store
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

$("#storeForm").on("submit", function (e) {

    e.preventDefault();

    const btn = $("#createStoreBtn");

    btn.text("Creating...").prop("disabled", true);

    const form = this;

    const fd = new FormData(form);

    const data = Object.fromEntries(fd.entries());

    $.ajax({

        url: API_BASE_URL + "/dashboard/stores/create.php",

        method: "POST",

        contentType: "application/json",

        dataType: "json",

        headers: {
            Authorization: "Bearer " + localStorage.getItem("auth_token")
        },

        data: JSON.stringify(data),

        success: function (res) {

            if (res.status === true) {

                showMessage(res.message, "success");

                setTimeout(function () {
                    window.location.href = "stores.php";
                }, 1000);

            } else {

                showMessage(res.message || "Store creation failed.", "error");

            }

        },

        error: function () {

            showMessage("Server error. Please try again.", "error");

        },

        complete: function () {

            btn.text("Create Store").prop("disabled", false);

        }

    });

});

function showMessage(message, type) {

    const box = $("#responseBox");

    box.removeClass("hidden bg-green-100 bg-red-100 text-green-700 text-red-700");

    if (type === "success") {

        box.addClass("bg-green-100 text-green-700");

    } else {

        box.addClass("bg-red-100 text-red-700");

    }

    box.text(message);

}
</script>
