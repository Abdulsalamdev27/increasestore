<?php
require_once __DIR__ . "/../includes/header.php";
?>

<div class="dashboard-container">

    <div id="responseBox" class="hidden mb-6 px-4 py-3 rounded text-sm font-medium"></div>

    <div class="dashboard-content">

        <div class="flex justify-between items-center mb-5">

            <h2 class="font-semibold text-lg">
                Products
            </h2>

            <a href="create-product.php"
                class="px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">

                + Add Product

            </a>

        </div>

        <div class="overflow-x-auto bg-white rounded-xl shadow-sm">

            <table class="min-w-full text-sm">

                <thead class="bg-gray-50 text-gray-600 uppercase text-xs">

                    <tr>

                        <th class="px-6 py-3 text-left">Product</th>
                        <th class="px-6 py-3 text-left">Barcode</th>
                        <th class="px-6 py-3 text-left">Category</th>
                        <th class="px-6 py-3 text-left">Selling Price</th>
                        <th class="px-6 py-3 text-left">Qty</th>
                        <th class="px-6 py-3 text-left">Status</th>
                        <th class="px-6 py-3 text-left">Created</th>
                        <th class="px-6 py-3 text-left">Action</th>

                    </tr>

                </thead>

                <tbody id="productsTable" class="divide-y"></tbody>

            </table>

        </div>

        <!-- Pagination -->

        <div id="pagination"
            class="flex justify-between items-center mt-4 text-sm text-gray-600 hidden">

            <button id="prevPage"
                class="px-3 py-1 rounded bg-gray-100 hover:bg-gray-200">

                ← Previous

            </button>

            <span id="pageInfo"></span>

            <button id="nextPage"
                class="px-3 py-1 rounded bg-gray-100 hover:bg-gray-200">

                Next →

            </button>

        </div>

    </div>

</div>


<!-- ========================= -->
<!-- EDIT PRODUCT MODAL -->
<!-- ========================= -->

<div
    id="editModal"
    class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">

    <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl">

        <div class="flex justify-between items-center border-b px-6 py-4">

            <h3 class="text-lg font-semibold">

                Edit Product

            </h3>

            <button
                id="closeModal"
                class="text-2xl leading-none">

                &times;

            </button>

        </div>

        <form id="editForm" class="p-6 space-y-5">

            <input type="hidden" id="edit_id">

            <div class="grid md:grid-cols-2 gap-5">

                <div>

                    <label class="label">
                        Product Name
                    </label>

                    <input
                        type="text"
                        id="edit_product_name"
                        class="input"
                        required>

                </div>

                <div>

                    <label class="label">
                        Barcode
                    </label>

                    <input
                        type="text"
                        id="edit_barcode"
                        class="input"
                        required>

                </div>

                <div>

                    <label class="label">
                        SKU
                    </label>

                    <input
                        type="text"
                        id="edit_sku"
                        class="input">

                </div>

                <div>

                    <label class="label">
                        Category
                    </label>

                    <input
                        type="text"
                        id="edit_category"
                        class="input">

                </div>

                <div>

                    <label class="label">
                        Selling Price
                    </label>

                    <input
                        type="number"
                        step="0.01"
                        id="edit_selling_price"
                        class="input">

                </div>

                <div>

                    <label class="label">
                        Cost Price
                    </label>

                    <input
                        type="number"
                        step="0.01"
                        id="edit_cost_price"
                        class="input">

                </div>

                <div>

                    <label class="label">
                        Quantity
                    </label>

                    <input
                        type="number"
                        id="edit_quantity"
                        class="input">

                </div>

                <div>

                    <label class="label">
                        Minimum Stock
                    </label>

                    <input
                        type="number"
                        id="edit_minimum_stock"
                        class="input">

                </div>

                <div>

                    <label class="label">
                        Unit
                    </label>

                    <input
                        type="text"
                        id="edit_unit"
                        class="input">

                </div>

                <div>

                    <label class="label">
                        Status
                    </label>

                    <select
                        id="edit_status"
                        class="input">

                        <option value="available">Available</option>
                        <option value="out_of_stock">Out of Stock</option>
                        <option value="discontinued">Discontinued</option>

                    </select>

                </div>

                <div class="md:col-span-2">

                    <label class="label">
                        Description
                    </label>

                    <textarea
                        id="edit_description"
                        rows="4"
                        class="input"></textarea>

                </div>

            </div>

            <div class="flex justify-end gap-3 pt-3">

                <button
                    type="button"
                    id="cancelModal"
                    class="px-5 py-2 border rounded-lg">

                    Cancel

                </button>

                <button
                    id="saveBtn"
                    class="px-5 py-2 rounded-lg bg-indigo-600 text-white">

                    Update Product

                </button>

            </div>

        </form>

    </div>

</div>

<?php include("../includes/footer.php"); ?>
<script>

const API_BASE_URL = "<?php echo API_BASE_URL; ?>";

let allProducts = [];
let currentPage = 1;
const rowsPerPage = 7;

// ----------------------
// LOAD PRODUCTS
// ----------------------

function loadProducts(){

    $.ajax({

        url: API_BASE_URL + "/dashboard/products/view-all.php",

        method: "GET",

        headers:{
            Authorization:"Bearer " + localStorage.getItem("auth_token")
        },

        dataType:"json",

        success:function(res){

            if(res.status){

                allProducts = res.data || [];

                currentPage = 1;

                renderProducts();

                updatePagination();

            }else{

                showMessage(res.message || "Unable to load products.");

            }

        },

        error:function(xhr){

            showMessage(
                xhr.responseJSON?.message || "Server error."
            );

        }

    });

}

// ----------------------
// RENDER TABLE
// ----------------------

function renderProducts(){

    const table=$("#productsTable");

    table.html("");

    if(!allProducts.length){

        table.html(`

            <tr>

                <td colspan="8"
                    class="px-6 py-6 text-center text-gray-500">

                    No products found.

                </td>

            </tr>

        `);

        $("#pagination").addClass("hidden");

        return;

    }

    $("#pagination").removeClass("hidden");

    const start=(currentPage-1)*rowsPerPage;

    const products=allProducts.slice(start,start+rowsPerPage);

    products.forEach(product=>{

        table.append(`

<tr class="hover:bg-gray-50">

<td class="px-6 py-4">

<div class="font-medium text-gray-800">

${escapeHtml(product.product_name)}

</div>

<div class="text-xs text-gray-500">

${escapeHtml(product.sku || "-")}

</div>

</td>

<td class="px-6 py-4">

${escapeHtml(product.barcode)}

</td>

<td class="px-6 py-4">

${escapeHtml(product.category || "-")}

</td>

<td class="px-6 py-4">

₦${Number(product.selling_price).toLocaleString()}

</td>

<td class="px-6 py-4">

${product.quantity}

</td>

<td class="px-6 py-4">

<span class="px-2 py-1 rounded text-xs
${product.status=="available"
?"bg-green-100 text-green-700"
:product.status=="out_of_stock"
?"bg-yellow-100 text-yellow-700"
:"bg-red-100 text-red-700"}">

${product.status.replaceAll("_"," ")}

</span>

</td>

<td class="px-6 py-4">

${new Date(product.created_at).toLocaleDateString()}

</td>

<td class="px-6 py-4">

<div class="flex gap-2">

<button

class="editBtn px-3 py-1.5 border rounded hover:bg-gray-100"

data-id="${product.id}"

>

Edit

</button>

<button

class="deleteBtn px-3 py-1.5 rounded bg-red-600 text-white hover:bg-red-700"

data-id="${product.id}"

>

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

    const totalPages=Math.ceil(allProducts.length/rowsPerPage);

    $("#pageInfo").text(

        `Page ${currentPage} of ${totalPages}`

    );

    $("#prevPage").prop(

        "disabled",

        currentPage===1

    );

    $("#nextPage").prop(

        "disabled",

        currentPage===totalPages

    );

}

$("#prevPage").click(function(){

    if(currentPage>1){

        currentPage--;

        renderProducts();

        updatePagination();

    }

});

$("#nextPage").click(function(){

    const totalPages=Math.ceil(allProducts.length/rowsPerPage);

    if(currentPage<totalPages){

        currentPage++;

        renderProducts();

        updatePagination();

    }

});

// ----------------------
// OPEN EDIT MODAL
// ----------------------

$(document).on("click",".editBtn",function(){

    const id=$(this).data("id");

    const product=allProducts.find(

        p=>p.id==id

    );

    if(!product)return;

    $("#edit_id").val(product.id);

    $("#edit_product_name").val(product.product_name);

    $("#edit_barcode").val(product.barcode);

    $("#edit_sku").val(product.sku);

    $("#edit_category").val(product.category);

    $("#edit_description").val(product.description);

    $("#edit_selling_price").val(product.selling_price);

    $("#edit_cost_price").val(product.cost_price);

    $("#edit_quantity").val(product.quantity);

    $("#edit_minimum_stock").val(product.minimum_stock);

    $("#edit_unit").val(product.unit);

    $("#edit_status").val(product.status);

    $("#editModal")

    .removeClass("hidden")

    .addClass("flex");

});

// ----------------------
// CLOSE MODAL
// ----------------------

$("#closeModal,#cancelModal").click(function(){

    $("#editModal")

    .removeClass("flex")

    .addClass("hidden");

});

// ----------------------
// UPDATE PRODUCT
// ----------------------

$("#editForm").submit(function(e){

    e.preventDefault();

    const btn=$("#saveBtn");

    btn.prop("disabled",true).text("Updating...");

    $.ajax({

        url:API_BASE_URL+"/dashboard/products/update.php",

        method:"POST",

        headers:{
            Authorization:"Bearer "+localStorage.getItem("auth_token")
        },

        contentType:"application/json",

        data:JSON.stringify({

            id:$("#edit_id").val(),

            product_name:$("#edit_product_name").val(),

            barcode:$("#edit_barcode").val(),

            sku:$("#edit_sku").val(),

            category:$("#edit_category").val(),

            description:$("#edit_description").val(),

            selling_price:$("#edit_selling_price").val(),

            cost_price:$("#edit_cost_price").val(),

            quantity:$("#edit_quantity").val(),

            minimum_stock:$("#edit_minimum_stock").val(),

            unit:$("#edit_unit").val(),

            status:$("#edit_status").val(),

            is_active:1

        }),

        dataType:"json",

        success:function(res){

            if(res.status){

                showMessage(res.message,"success");

                $("#editModal")

                .removeClass("flex")

                .addClass("hidden");

                loadProducts();

            }else{

                showMessage(res.message);

            }

        },

        error:function(xhr){

            showMessage(

                xhr.responseJSON?.message ||

                "Server error."

            );

        },

        complete:function(){

            btn

            .prop("disabled",false)

            .text("Update Product");

        }

    });

});

// ----------------------
// DELETE PRODUCT
// ----------------------

$(document).on("click",".deleteBtn",function(){

    if(!confirm("Delete this product?")){

        return;

    }

    $.ajax({

        url:API_BASE_URL+"/dashboard/products/delete.php",

        method:"POST",

        headers:{
            Authorization:"Bearer "+localStorage.getItem("auth_token")
        },

        contentType:"application/json",

        data:JSON.stringify({

            id:$(this).data("id")

        }),

        dataType:"json",

        success:function(res){

            if(res.status){

                showMessage(res.message,"success");

                loadProducts();

            }else{

                showMessage(res.message);

            }

        },

        error:function(xhr){

            showMessage(

                xhr.responseJSON?.message ||

                "Server error."

            );

        }

    });

});

// ----------------------
// MESSAGE
// ----------------------

function showMessage(message,type="error"){

    const box=$("#responseBox");

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

}

// ----------------------
// ESCAPE HTML
// ----------------------

function escapeHtml(text){

    return $("<div>").text(text ?? "").html();

}

// ----------------------
// INIT
// ----------------------

$(document).ready(function(){

    loadProducts();

});

</script>