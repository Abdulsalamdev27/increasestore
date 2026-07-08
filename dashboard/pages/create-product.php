<?php
require_once __DIR__ . "/../includes/header.php";
?>

<section class="w-full">

    <!-- Page Title -->
    <article class="mb-6">

        <h1 class="font-title font-bold text-2xl">
            Create Product
        </h1>

        <p class="text-gray-600 text-sm">
            Fill in the details below to create a new product.
        </p>

    </article>

    <!-- Response -->
    <div
        id="responseBox"
        class="hidden mb-6 px-4 py-3 rounded text-sm font-medium">
    </div>

    <!-- Form Card -->
    <article class="bg-white rounded-2xl border border-gray-200 shadow-lg backdrop-blur-md">

        <form
            id="productForm"
            class="space-y-10 py-6">

            <!-- PRODUCT INFORMATION -->
            <section class="px-6">

                <h2 class="font-title font-semibold text-lg mb-4">
                    Product Information
                </h2>

                <div class="grid md:grid-cols-2 gap-5">

                    <!-- Product Name -->
                    <div>

                        <label class="label">
                            Product Name
                        </label>

                        <input
                            type="text"
                            name="product_name"
                            class="input"
                            placeholder="Enter product name"
                            required>

                    </div>

                    <!-- Barcode -->
                    <div>

                        <label class="label">
                            Barcode
                        </label>

                        <input
                            type="text"
                            name="barcode"
                            id="barcode"
                            class="input"
                            placeholder="Scan or enter barcode"
                            autocomplete="off"
                            required>

                        <p class="text-xs text-gray-500 mt-1">
                            Click inside this field and scan using a barcode laser scanner.
                        </p>

                    </div>

                    <!-- SKU -->
                    <div>

                        <label class="label">
                            SKU
                        </label>

                        <input
                            type="text"
                            name="sku"
                            class="input"
                            placeholder="Stock Keeping Unit">

                    </div>

                    <!-- Category -->
                    <div>

                        <label class="label">
                            Category
                        </label>

                        <input
                            type="text"
                            name="category"
                            class="input"
                            placeholder="Electronics, Drinks, Grocery...">

                    </div>

                    <!-- Selling Price -->
                    <div>

                        <label class="label">
                            Selling Price (₦)
                        </label>

                        <input
                            type="number"
                            name="selling_price"
                            class="input"
                            min="0"
                            step="0.01"
                            placeholder="0.00"
                            required>

                    </div>

                    <!-- Cost Price -->
                    <div>

                        <label class="label">
                            Cost Price (₦)
                        </label>

                        <input
                            type="number"
                            name="cost_price"
                            class="input"
                            min="0"
                            step="0.01"
                            placeholder="0.00">

                    </div>

                    <!-- Quantity -->
                    <div>

                        <label class="label">
                            Quantity
                        </label>

                        <input
                            type="number"
                            name="quantity"
                            class="input"
                            min="0"
                            value="0"
                            required>

                    </div>

                    <!-- Minimum Stock -->
                    <div>

                        <label class="label">
                            Minimum Stock
                        </label>

                        <input
                            type="number"
                            name="minimum_stock"
                            class="input"
                            min="0"
                            value="0">

                    </div>

                    <!-- Unit -->
                    <div>

                        <label class="label">
                            Unit
                        </label>

                        <select
                            name="unit"
                            class="input">

                            <option value="pcs">Pieces (pcs)</option>
                            <option value="box">Box</option>
                            <option value="carton">Carton</option>
                            <option value="pack">Pack</option>
                            <option value="dozen">Dozen</option>
                            <option value="bottle">Bottle</option>
                            <option value="can">Can</option>
                            <option value="kg">Kilogram (kg)</option>
                            <option value="g">Gram (g)</option>
                            <option value="litre">Litre</option>
                            <option value="ml">Millilitre (ml)</option>

                        </select>

                    </div>

                    <!-- Status -->
                    <div>

                        <label class="label">
                            Product Status
                        </label>

                        <select
                            name="status"
                            class="input">

                            <option value="available">
                                Available
                            </option>

                            <option value="out_of_stock">
                                Out of Stock
                            </option>

                            <option value="discontinued">
                                Discontinued
                            </option>

                        </select>

                    </div>

                    <!-- Active -->
                    <div>

                        <label class="label">
                            Active Status
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

                    <!-- Description -->
                    <div class="md:col-span-2">

                        <label class="label">
                            Description
                        </label>

                        <textarea
                            name="description"
                            rows="5"
                            class="input"
                            placeholder="Enter product description"></textarea>

                    </div>

                </div>

            </section>

            <!-- Submit -->
            <div class="px-6 pt-4">

                <button
                    type="submit"
                    id="createProductBtn"
                    class="w-full py-3 rounded-xl bg-indigo-600 text-white font-semibold shadow-md hover:bg-indigo-700 transition">

                    Create Product

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

// ----------------------
// BARCODE SCANNER SUPPORT
// ----------------------

// Focus barcode field when page loads
$(document).ready(function () {

    $("#barcode").focus();

});

// When a laser scanner scans, it behaves like a keyboard
$("#barcode").on("keypress", function (e) {

    if (e.which === 13) {

        e.preventDefault();

        $("input[name='sku']").focus();

    }

});

// ----------------------
// CREATE PRODUCT
// ----------------------

$("#productForm").on("submit", function (e) {

    e.preventDefault();

    const btn = $("#createProductBtn");

    btn
        .text("Creating...")
        .prop("disabled", true);

    const form = this;

    const fd = new FormData(form);

    const payload = Object.fromEntries(fd.entries());

    // Convert numeric fields
    payload.selling_price = Number(payload.selling_price);
    payload.cost_price = Number(payload.cost_price || 0);
    payload.quantity = Number(payload.quantity);
    payload.minimum_stock = Number(payload.minimum_stock);
    payload.is_active = Number(payload.is_active);

    $.ajax({

        url: API_BASE_URL + "/dashboard/products/create.php",

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
                    res.message || "Product created successfully.",
                    "success"
                );

                form.reset();

                // Ready for next barcode scan
                $("#barcode").focus();

                setTimeout(function(){

                    window.location.href = "products.php";

                },1000);

            }else{

                showMessage(
                    res.message || "Unable to create product."
                );

            }

        },

        error(xhr){

            showMessage(

                xhr.responseJSON?.message ||

                "Server error. Please try again."

            );

        },

        complete(){

            btn
                .text("Create Product")
                .prop("disabled", false);

        }

    });

});

// ----------------------
// RESPONSE MESSAGE
// ----------------------

function showMessage(message, type="error"){

    const box = $("#responseBox");

    box.removeClass(
        "hidden bg-green-100 bg-red-100 text-green-700 text-red-700"
    );

    if(type==="success"){

        box.addClass(
            "bg-green-100 text-green-700"
        );

    }else{

        box.addClass(
            "bg-red-100 text-red-700"
        );

    }

    box.text(message);

    box.removeClass("hidden");

    setTimeout(function(){

        box.addClass("hidden");

    },3000);

}

// ----------------------
// XSS PROTECTION
// ----------------------

function escapeHtml(text){

    return $("<div>").text(text ?? "").html();

}

</script>