<?php

require_once __DIR__ . "/../includes/header.php";

?>

<div class="dashboard-container">

    <!-- ==========================================================
         RESPONSE MESSAGE
    =========================================================== -->

    <div
        id="responseBox"
        class="hidden mb-6 rounded-lg border px-4 py-3 text-sm font-medium">
    </div>


    <div class="dashboard-content">


        <!-- ==========================================================
             PAGE HEADER
        =========================================================== -->

        <div class="mb-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

            <div>

                <h1 class="text-2xl font-bold text-gray-800">
                    Order Receipts
                </h1>

                <p class="mt-1 text-sm text-gray-500">
                    View, monitor and print customer orders and receipts.
                </p>

            </div>


            <div class="flex gap-3">

                <button
                    type="button"
                    id="refreshOrders"
                    class="rounded-lg bg-gray-700 px-5 py-2.5 text-white transition hover:bg-gray-800">

                    Refresh

                </button>

            </div>

        </div>


        <!-- ==========================================================
             FILTER CARD
        =========================================================== -->

        <div class="mb-6 rounded-xl bg-white shadow-sm">

            <div class="grid gap-5 p-6 md:grid-cols-3">


                <!-- Search -->

                <div>

                    <label
                        for="searchOrder"
                        class="mb-2 block text-sm font-medium text-gray-700">

                        Search

                    </label>


                    <input
                        id="searchOrder"
                        type="text"
                        autocomplete="off"
                        placeholder="Order number, customer, phone or email..."
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200">

                </div>


                <!-- Payment Status -->

                <div>

                    <label
                        for="paymentStatusFilter"
                        class="mb-2 block text-sm font-medium text-gray-700">

                        Payment Status

                    </label>


                    <select
                        id="paymentStatusFilter"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200">

                        <option value="">
                            All Status
                        </option>

                        <option value="paid">
                            Paid
                        </option>

                        <option value="pending">
                            Pending
                        </option>

                        <option value="partial">
                            Partial
                        </option>

                        <option value="failed">
                            Failed
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
             ORDERS TABLE
        =========================================================== -->

        <div class="overflow-hidden rounded-xl bg-white shadow-sm">

            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-gray-200 text-sm">

                    <thead class="bg-gray-50">

                        <tr class="text-left text-xs font-semibold uppercase tracking-wider text-gray-600">

                            <th class="px-6 py-4">
                                #
                            </th>

                            <th class="px-6 py-4">
                                Order No.
                            </th>

                            <th class="px-6 py-4">
                                Customer
                            </th>

                            <th class="px-6 py-4">
                                Phone
                            </th>

                            <th class="px-6 py-4">
                                Payment
                            </th>

                            <th class="px-6 py-4">
                                Status
                            </th>

                            <th class="px-6 py-4">
                                Total
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
                        id="ordersTableBody"
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
                                        Loading orders...
                                    </p>

                                </div>

                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>


            <!-- ======================================================
                 PAGINATION
            ======================================================= -->

            <div
                id="pagination"
                class="flex flex-col gap-4 border-t bg-white p-4 md:flex-row md:items-center md:justify-between">

                <!-- Results -->

                <div class="text-sm text-gray-600">

                    Showing

                    <span
                        id="showingFrom"
                        class="font-semibold">

                        0

                    </span>

                    -

                    <span
                        id="showingTo"
                        class="font-semibold">

                        0

                    </span>

                    of

                    <span
                        id="totalRows"
                        class="font-semibold">

                        0

                    </span>

                    orders

                </div>


                <!-- Controls -->

                <div class="flex items-center gap-2">

                    <button
                        id="prevPage"
                        type="button"
                        class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm transition hover:bg-gray-100 disabled:cursor-not-allowed disabled:opacity-50">

                        ← Previous

                    </button>


                    <span
                        id="pageInfo"
                        class="rounded-lg bg-gray-100 px-4 py-2 text-sm font-medium">

                        Page 1 of 1

                    </span>


                    <button
                        id="nextPage"
                        type="button"
                        class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm transition hover:bg-gray-100 disabled:cursor-not-allowed disabled:opacity-50">

                        Next →

                    </button>

                </div>

            </div>

        </div>

    </div>

</div>


<?php include("../includes/footer.php"); ?>


<script>

/* ==========================================================
   API CONFIGURATION
========================================================== */

const API_BASE_URL = "<?php echo API_BASE_URL; ?>";

const TOKEN = localStorage.getItem("auth_token");


/* ==========================================================
   GLOBAL VARIABLES
========================================================== */

let allOrders = [];

let filteredOrders = [];

let currentPage = 1;

const rowsPerPage = 10;


/* ==========================================================
   LOAD ORDERS
========================================================== */

function loadOrders(page = 1) {

    currentPage = page;


    /*
     * Check authentication token
     */

    if (!TOKEN) {

        showMessage(
            "Authentication token not found. Please login again."
        );

        renderOrders();

        return;

    }


    /*
     * Loading state
     */

    $("#ordersTableBody").html(`

        <tr>

            <td
                colspan="9"
                class="px-6 py-10 text-center"
            >

                <div class="flex flex-col items-center justify-center">

                    <svg
                        class="mb-3 h-8 w-8 animate-spin text-indigo-600"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                    >

                        <circle
                            class="opacity-25"
                            cx="12"
                            cy="12"
                            r="10"
                            stroke="currentColor"
                            stroke-width="4"
                        >
                        </circle>

                        <path
                            class="opacity-75"
                            fill="currentColor"
                            d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"
                        >
                        </path>

                    </svg>

                    <p class="text-gray-500">
                        Loading orders...
                    </p>

                </div>

            </td>

        </tr>

    `);


    /*
     * API Request
     */

    $.ajax({

        url:
            API_BASE_URL +
            "/dashboard/orders/list.php",

        method: "GET",

        headers: {

            Authorization:
                "Bearer " + TOKEN

        },

        dataType: "json",


        success: function (response) {


            /*
             * Check API status
             */

            if (!response.status) {

                showMessage(
                    response.message ||
                    "Unable to load orders."
                );


                allOrders = [];

                filteredOrders = [];

                renderOrders();

                return;

            }


            /*
             * Support different API response structures.
             */

            allOrders =
                response.data ||
                response.orders ||
                response.results ||
                [];


            /*
             * Make sure the result is an array.
             */

            if (!Array.isArray(allOrders)) {

                allOrders = [];

            }


            /*
             * Copy all orders to filtered orders.
             */

            filteredOrders =
                [...allOrders];


            /*
             * Reset page.
             */

            currentPage = 1;


            /*
             * Render.
             */

            renderOrders();

        },


        error: function (xhr) {

            console.error(
                "Orders API Error:",
                xhr.responseText
            );


            const message =
                xhr.responseJSON?.message ||
                "Server error while loading orders.";


            showMessage(message);


            allOrders = [];

            filteredOrders = [];


            renderOrders();

        }

    });

}


/* ==========================================================
   RENDER ORDERS
========================================================== */

function renderOrders() {

    const tbody =
        $("#ordersTableBody");


    tbody.empty();


    /*
     * Total number of filtered orders.
     */

    const total =
        filteredOrders.length;


    /*
     * Calculate total pages.
     */

    const totalPages =
        Math.max(
            1,
            Math.ceil(
                total / rowsPerPage
            )
        );


    /*
     * Keep current page valid.
     */

    if (currentPage > totalPages) {

        currentPage = totalPages;

    }


    /*
     * Calculate pagination range.
     */

    const start =
        (currentPage - 1) *
        rowsPerPage;


    const end =
        start +
        rowsPerPage;


    /*
     * Orders for current page.
     */

    const orders =
        filteredOrders.slice(
            start,
            end
        );


    /*
     * No orders.
     */

    if (orders.length === 0) {

        tbody.html(`

            <tr>

                <td
                    colspan="9"
                    class="px-6 py-10 text-center text-gray-500"
                >

                    No orders found.

                </td>

            </tr>

        `);


        updatePaginationInfo();

        return;

    }


    /*
     * Render each order.
     */

    orders.forEach(
        function (order, index) {


            const rowNumber =
                start + index + 1;


            const orderId =
                Number(
                    order.id || 0
                );


            const orderNo =
                escapeHtml(
                    order.order_no ||
                    "-"
                );


            const customer =
                escapeHtml(
                    order.customer_name ||
                    "Walk-in Customer"
                );


            const phone =
                escapeHtml(
                    order.customer_phone ||
                    "-"
                );


            const paymentMethod =
                escapeHtml(
                    order.payment_method ||
                    "-"
                );


            const paymentStatus =
                String(
                    order.payment_status ||
                    "pending"
                ).toLowerCase();


            const totalAmount =
                Number(
                    order.total_amount ||
                    0
                );


            const createdAt =
                formatDateTime(
                    order.created_at
                );


            tbody.append(`

                <tr
                    class="border-b transition hover:bg-gray-50"
                >

                    <!-- NUMBER -->

                    <td class="px-6 py-4">

                        <span class="text-gray-500">

                            ${rowNumber}

                        </span>

                    </td>


                    <!-- ORDER NUMBER -->

                    <td class="px-6 py-4">

                        <div class="font-semibold text-gray-800">

                            ${orderNo}

                        </div>

                    </td>


                    <!-- CUSTOMER -->

                    <td class="px-6 py-4">

                        <div class="font-medium text-gray-800">

                            ${customer}

                        </div>

                    </td>


                    <!-- PHONE -->

                    <td class="px-6 py-4">

                        ${phone}

                    </td>


                    <!-- PAYMENT METHOD -->

                    <td class="px-6 py-4">

                        <span class="capitalize">

                            ${paymentMethod}

                        </span>

                    </td>


                    <!-- PAYMENT STATUS -->

                    <td class="px-6 py-4">

                        ${getPaymentStatusBadge(paymentStatus)}

                    </td>


                    <!-- TOTAL -->

                    <td class="px-6 py-4">

                        <span class="font-semibold text-gray-800">

                            ₦${money(totalAmount)}

                        </span>

                    </td>


                    <!-- DATE -->

                    <td class="px-6 py-4 text-gray-500">

                        ${createdAt}

                    </td>


                    <!-- ACTIONS -->

                    <td class="px-6 py-4">

                        <div class="flex justify-center gap-2">


                            <!-- VIEW -->

                            <button
                                type="button"
                                class="viewOrder rounded-lg bg-indigo-600 px-3 py-2 text-xs font-medium text-white transition hover:bg-indigo-700"
                                data-id="${orderId}"
                            >

                                View

                            </button>


                            <!-- PRINT -->

                            <button
                                type="button"
                                class="printOrder rounded-lg bg-green-600 px-3 py-2 text-xs font-medium text-white transition hover:bg-green-700"
                                data-id="${orderId}"
                            >

                                Print

                            </button>


                        </div>

                    </td>

                </tr>

            `);

        }
    );


    /*
     * Update pagination.
     */

    updatePaginationInfo();

}


/* ==========================================================
   VIEW ORDER
========================================================== */

$(document).on(
    "click",
    ".viewOrder",
    function () {


        const orderId =
            Number(
                $(this).data("id")
            );


        if (!orderId) {

            showMessage(
                "Invalid order selected."
            );

            return;

        }


        /*
         * Open receipt page.
         */

        const url =
            "order-receipt.php?id=" +
            encodeURIComponent(
                orderId
            );


        window.open(
            url,
            "_blank"
        );

    }
);


/* ==========================================================
   PRINT ORDER
========================================================== */

$(document).on(
    "click",
    ".printOrder",
    function () {


        const orderId =
            Number(
                $(this).data("id")
            );


        if (!orderId) {

            showMessage(
                "Invalid order selected."
            );

            return;

        }


        /*
         * Open receipt with print parameter.
         */

        const url =
            "order-receipt.php?id=" +
            encodeURIComponent(
                orderId
            ) +
            "&print=1";


        const printWindow =
            window.open(
                url,
                "_blank"
            );


        /*
         * Popup blocker.
         */

        if (!printWindow) {

            showMessage(
                "Please allow pop-ups to print the receipt."
            );

        }

    }
);


/* ==========================================================
   SEARCH + FILTERS
========================================================== */

function applyFilters() {


    const keyword =
        $("#searchOrder")
            .val()
            .toLowerCase()
            .trim();


    const paymentStatus =
        $("#paymentStatusFilter")
            .val()
            .toLowerCase();


    filteredOrders =
        allOrders.filter(
            function (order) {


                /*
                 * Order number
                 */

                const orderNo =
                    String(
                        order.order_no ||
                        ""
                    ).toLowerCase();


                /*
                 * Customer
                 */

                const customer =
                    String(
                        order.customer_name ||
                        ""
                    ).toLowerCase();


                /*
                 * Phone
                 */

                const phone =
                    String(
                        order.customer_phone ||
                        ""
                    ).toLowerCase();


                /*
                 * Email
                 */

                const email =
                    String(
                        order.customer_email ||
                        ""
                    ).toLowerCase();


                /*
                 * Search matching.
                 */

                const matchesKeyword =
                    keyword === "" ||
                    orderNo.includes(keyword) ||
                    customer.includes(keyword) ||
                    phone.includes(keyword) ||
                    email.includes(keyword);


                /*
                 * Payment status.
                 */

                const currentPaymentStatus =
                    String(
                        order.payment_status ||
                        ""
                    ).toLowerCase();


                const matchesPaymentStatus =
                    paymentStatus === "" ||
                    currentPaymentStatus ===
                    paymentStatus;


                return (
                    matchesKeyword &&
                    matchesPaymentStatus
                );

            }
        );


    /*
     * Return to page one after filtering.
     */

    currentPage = 1;


    renderOrders();

}


/* ==========================================================
   SEARCH EVENT
========================================================== */

$("#searchOrder").on(
    "keyup",
    function () {

        applyFilters();

    }
);


/* ==========================================================
   SEARCH ENTER KEY
========================================================== */

$("#searchOrder").on(
    "keypress",
    function (e) {

        if (e.which === 13) {

            e.preventDefault();

            applyFilters();

        }

    }
);


/* ==========================================================
   PAYMENT STATUS EVENT
========================================================== */

$("#paymentStatusFilter").on(
    "change",
    function () {

        applyFilters();

    }
);


/* ==========================================================
   RESET FILTERS
========================================================== */

$("#resetFilters").on(
    "click",
    function () {


        $("#searchOrder")
            .val("");


        $("#paymentStatusFilter")
            .val("");


        filteredOrders =
            [...allOrders];


        currentPage = 1;


        renderOrders();

    }
);


/* ==========================================================
   REFRESH ORDERS
========================================================== */

$("#refreshOrders").on(
    "click",
    function () {

        loadOrders(
            currentPage
        );

    }
);


/* ==========================================================
   PREVIOUS PAGE
========================================================== */

$("#prevPage").on(
    "click",
    function () {


        if (
            currentPage <= 1
        ) {

            return;

        }


        currentPage--;


        renderOrders();

    }
);


/* ==========================================================
   NEXT PAGE
========================================================== */

$("#nextPage").on(
    "click",
    function () {


        const totalPages =
            Math.max(
                1,
                Math.ceil(
                    filteredOrders.length /
                    rowsPerPage
                )
            );


        if (
            currentPage >=
            totalPages
        ) {

            return;

        }


        currentPage++;


        renderOrders();

    }
);


/* ==========================================================
   PAGINATION INFORMATION
========================================================== */

function updatePaginationInfo() {


    const total =
        filteredOrders.length;


    const totalPages =
        Math.max(
            1,
            Math.ceil(
                total / rowsPerPage
            )
        );


    const from =
        total === 0
            ? 0
            : (
                (currentPage - 1) *
                rowsPerPage
            ) + 1;


    const to =
        Math.min(
            currentPage *
            rowsPerPage,
            total
        );


    $("#showingFrom")
        .text(from);


    $("#showingTo")
        .text(to);


    $("#totalRows")
        .text(total);


    $("#pageInfo")
        .text(
            `Page ${currentPage} of ${totalPages}`
        );


    $("#prevPage")
        .prop(
            "disabled",
            currentPage === 1
        );


    $("#nextPage")
        .prop(
            "disabled",
            currentPage >= totalPages
        );

}


/* ==========================================================
   PAYMENT STATUS BADGE
========================================================== */

function getPaymentStatusBadge(
    status
) {


    status =
        String(
            status ||
            "pending"
        ).toLowerCase();


    switch (status) {


        case "paid":

            return `

                <span
                    class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700"
                >

                    Paid

                </span>

            `;


        case "partial":

            return `

                <span
                    class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-700"
                >

                    Partial

                </span>

            `;


        case "failed":

            return `

                <span
                    class="inline-flex rounded-full bg-red-100 px-3 py-1 text-xs font-medium text-red-700"
                >

                    Failed

                </span>

            `;


        default:

            return `

                <span
                    class="inline-flex rounded-full bg-yellow-100 px-3 py-1 text-xs font-medium text-yellow-700"
                >

                    Pending

                </span>

            `;

    }

}


/* ==========================================================
   MONEY FORMAT
========================================================== */

function money(amount) {


    return Number(
        amount || 0
    ).toLocaleString(
        "en-NG",
        {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }
    );

}


/* ==========================================================
   DATE FORMAT
========================================================== */

function formatDateTime(date) {


    if (!date) {

        return "-";

    }


    const d =
        new Date(date);


    if (
        isNaN(
            d.getTime()
        )
    ) {

        return date;

    }


    return d.toLocaleString(
        "en-NG",
        {
            year: "numeric",
            month: "short",
            day: "2-digit",
            hour: "2-digit",
            minute: "2-digit"
        }
    );

}


/* ==========================================================
   ESCAPE HTML
========================================================== */

function escapeHtml(text) {


    return $("<div>")
        .text(
            text ?? ""
        )
        .html();

}


/* ==========================================================
   SHOW RESPONSE MESSAGE
========================================================== */

function showMessage(
    message,
    type = "error"
) {


    const box =
        $("#responseBox");


    box.removeClass(
        "hidden bg-green-100 bg-red-100 text-green-700 text-red-700"
    );


    if (
        type === "success"
    ) {

        box.addClass(
            "bg-green-100 text-green-700"
        );

    } else {

        box.addClass(
            "bg-red-100 text-red-700"
        );

    }


    box.text(message);


    $("html, body").animate(
        {
            scrollTop: 0
        },
        200
    );


    setTimeout(
        function () {

            box.addClass(
                "hidden"
            );

        },
        4000
    );

}


/* ==========================================================
   INITIAL LOAD
========================================================== */

$(document).ready(
    function () {

        loadOrders(1);

    }
);

</script>