<?php

require_once __DIR__ . "/../includes/header.php";

?>

<div class="dashboard-container">

    <div class="dashboard-content">

        <!-- ==========================================================
             PAGE HEADER
        =========================================================== -->

        <div class="mb-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

            <div>

                <h1 class="text-2xl font-bold text-gray-800">
                    Dashboard
                </h1>

                <p class="mt-1 text-sm text-gray-500">
                    Overview of your orders, inventory, sales and transfers.
                </p>

            </div>

            <button
                type="button"
                id="refreshDashboard"
                class="rounded-lg bg-gray-800 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-gray-700">

                <span class="inline-flex items-center gap-2"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h5M20 20v-5h-5M5.5 9A7 7 0 0 1 18 5.5L20 4M19 15a7 7 0 0 1-12.5 3.5L4 20"/></svg>Refresh</span>

            </button>

        </div>


        <!-- ==========================================================
             RESPONSE MESSAGE
        =========================================================== -->

        <div
            id="dashboardMessage"
            class="mb-6 hidden rounded-lg border px-4 py-3 text-sm font-medium">
        </div>


        <!-- ==========================================================
             LOADING
        =========================================================== -->

        <div
            id="dashboardLoading"
            class="mb-6 hidden rounded-xl bg-white p-8 text-center shadow-sm">

            <div
                class="mx-auto mb-3 h-8 w-8 animate-spin rounded-full border-4 border-gray-200 border-t-blue-600">
            </div>

            <p class="text-sm text-gray-500">
                Loading dashboard summary...
            </p>

        </div>


        <!-- ==========================================================
             SUMMARY CARDS
        =========================================================== -->

        <div
            id="summaryCards"
            class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">


            <!-- ======================================================
                 TOTAL ORDERS
            ======================================================= -->

            <div class="rounded-xl bg-white p-5 shadow-sm">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-sm font-medium text-gray-500">
                            Total Orders
                        </p>

                        <h2
                            id="totalOrders"
                            class="mt-2 text-3xl font-bold text-gray-800">

                            0

                        </h2>

                    </div>

                    <div
                        class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-100 text-2xl">

                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l2.4 11.2a2 2 0 0 0 2 1.6h7.8a2 2 0 0 0 1.9-1.4L21 7H6M10 20a1 1 0 1 1-2 0 1 1 0 0 1 2 0Zm8 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0Z"/></svg>

                    </div>

                </div>

                <p class="mt-3 text-xs text-gray-500">
                    All orders in the system
                </p>

            </div>


            <!-- ======================================================
                 TOTAL SALES
            ======================================================= -->

            <div class="rounded-xl bg-white p-5 shadow-sm">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-sm font-medium text-gray-500">
                            Total Sales
                        </p>

                        <h2
                            id="totalSales"
                            class="mt-2 text-3xl font-bold text-gray-800">

                            ₦0.00

                        </h2>

                    </div>

                    <div
                        class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-100 text-2xl">

                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="5" width="18" height="14" rx="2"/><path stroke-linecap="round" d="M3 9h18M12 12.5a2 2 0 1 0 0 4 2 2 0 0 0 0-4Z"/></svg>

                    </div>

                </div>

                <p class="mt-3 text-xs text-gray-500">
                    Total order value
                </p>

            </div>


            <!-- ======================================================
                 TOTAL PRODUCTS
            ======================================================= -->

            <div class="rounded-xl bg-white p-5 shadow-sm">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-sm font-medium text-gray-500">
                            Total Products
                        </p>

                        <h2
                            id="totalProducts"
                            class="mt-2 text-3xl font-bold text-gray-800">

                            0

                        </h2>

                    </div>

                    <div
                        class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-100 text-2xl">

                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m12 3 8 4.5v9L12 21l-8-4.5v-9L12 3Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 7.8 12 12l7.5-4.2M12 12v9"/></svg>

                    </div>

                </div>

                <p class="mt-3 text-xs text-gray-500">
                    Products in inventory
                </p>

            </div>


            <!-- ======================================================
                 LOW STOCK
            ======================================================= -->

            <div class="rounded-xl bg-white p-5 shadow-sm">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-sm font-medium text-gray-500">
                            Low Stock
                        </p>

                        <h2
                            id="lowStock"
                            class="mt-2 text-3xl font-bold text-yellow-600">

                            0

                        </h2>

                    </div>

                    <div
                        class="flex h-12 w-12 items-center justify-center rounded-xl bg-yellow-100 text-2xl">

                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.3 4.5 2.7 18a2 2 0 0 0 1.8 3h15a2 2 0 0 0 1.8-3L13.7 4.5a2 2 0 0 0-3.4 0Z"/><path stroke-linecap="round" d="M12 9v4M12 17h.01"/></svg>

                    </div>

                </div>

                <p class="mt-3 text-xs text-gray-500">
                    Products below minimum stock
                </p>

            </div>


            <!-- ======================================================
                 PENDING ORDERS
            ======================================================= -->

            <div class="rounded-xl bg-white p-5 shadow-sm">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-sm font-medium text-gray-500">
                            Pending Orders
                        </p>

                        <h2
                            id="pendingOrders"
                            class="mt-2 text-3xl font-bold text-orange-600">

                            0

                        </h2>

                    </div>

                    <div
                        class="flex h-12 w-12 items-center justify-center rounded-xl bg-orange-100 text-2xl">

                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M6 3h12M6 21h12M8 3c0 4 4 5 4 9s-4 5-4 9M16 3c0 4-4 5-4 9s4 5 4 9"/></svg>

                    </div>

                </div>

                <p class="mt-3 text-xs text-gray-500">
                    Orders awaiting processing
                </p>

            </div>


            <!-- ======================================================
                 STOCKED OUT
            ======================================================= -->

            <div class="rounded-xl bg-white p-5 shadow-sm">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-sm font-medium text-gray-500">
                            Stocked Out
                        </p>

                        <h2
                            id="stockedOut"
                            class="mt-2 text-3xl font-bold text-blue-600">

                            0

                        </h2>

                    </div>

                    <div
                        class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-100 text-2xl">

                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 6h11v11H3zM14 10h4l3 3v4h-7z"/><path stroke-linecap="round" d="M7 20a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm11 0a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z"/></svg>

                    </div>

                </div>

                <p class="mt-3 text-xs text-gray-500">
                    Orders already stocked out
                </p>

            </div>


            <!-- ======================================================
                 OUT OF STOCK
            ======================================================= -->

            <div class="rounded-xl bg-white p-5 shadow-sm">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-sm font-medium text-gray-500">
                            Out of Stock
                        </p>

                        <h2
                            id="outOfStock"
                            class="mt-2 text-3xl font-bold text-red-600">

                            0

                        </h2>

                    </div>

                    <div
                        class="flex h-12 w-12 items-center justify-center rounded-xl bg-red-100 text-2xl">

                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" d="m9 9 6 6m0-6-6 6"/></svg>

                    </div>

                </div>

                <p class="mt-3 text-xs text-gray-500">
                    Products currently unavailable
                </p>

            </div>


            <!-- ======================================================
                 PENDING TRANSFERS
            ======================================================= -->

            <div class="rounded-xl bg-white p-5 shadow-sm">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-sm font-medium text-gray-500">
                            Pending Transfers
                        </p>

                        <h2
                            id="pendingTransfers"
                            class="mt-2 text-3xl font-bold text-purple-600">

                            0

                        </h2>

                    </div>

                    <div
                        class="flex h-12 w-12 items-center justify-center rounded-xl bg-purple-100 text-2xl">

                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7v5h-5M4 17v-5h5M6 12a6 6 0 0 1 10-4l4 4M18 12a6 6 0 0 1-10 4l-4-4"/></svg>

                    </div>

                </div>

                <p class="mt-3 text-xs text-gray-500">
                    Transfers awaiting review
                </p>

            </div>

        </div>


        <!-- ==========================================================
             ADDITIONAL SUMMARY
        =========================================================== -->

        <div class="mt-6 grid grid-cols-1 gap-5 lg:grid-cols-3">


            <!-- PAID ORDERS -->

            <div class="rounded-xl bg-white p-5 shadow-sm">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-sm font-medium text-gray-500">
                            Paid Orders
                        </p>

                        <h2
                            id="paidOrders"
                            class="mt-2 text-2xl font-bold text-green-600">

                            0

                        </h2>

                    </div>

                    <span class="text-2xl">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" stroke-linejoin="round" d="m8.5 12 2.2 2.2 4.8-5"/></svg>
                    </span>

                </div>

            </div>


            <!-- PARTIAL ORDERS -->

            <div class="rounded-xl bg-white p-5 shadow-sm">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-sm font-medium text-gray-500">
                            Partial Orders
                        </p>

                        <h2
                            id="partialOrders"
                            class="mt-2 text-2xl font-bold text-blue-600">

                            0

                        </h2>

                    </div>

                    <span class="text-2xl">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 10h18"/></svg>
                    </span>

                </div>

            </div>


            <!-- REJECTED TRANSFERS -->

            <div class="rounded-xl bg-white p-5 shadow-sm">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-sm font-medium text-gray-500">
                            Rejected Transfers
                        </p>

                        <h2
                            id="rejectedTransfers"
                            class="mt-2 text-2xl font-bold text-red-600">

                            0

                        </h2>

                    </div>

                    <span class="text-2xl">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" d="m8.5 8.5 7 7"/></svg>
                    </span>

                </div>

            </div>

        </div>

    </div>

</div>


<script>

/* ==============================================================
   API CONFIGURATION
============================================================== */

const API_BASE_URL =
    "<?php echo API_BASE_URL; ?>";


/* ==============================================================
   TOKEN
============================================================== */

function getToken() {

    return (
        localStorage.getItem("token") ||
        sessionStorage.getItem("token") ||
        localStorage.getItem("auth_token") ||
        sessionStorage.getItem("auth_token") ||
        ""
    );

}


/* ==============================================================
   MESSAGE
============================================================== */

function showDashboardMessage(
    message,
    type = "error"
) {

    const box =
        document.getElementById(
            "dashboardMessage"
        );

    if (!box) {
        console.log(message);
        return;
    }


    box.classList.remove(
        "hidden",
        "bg-red-100",
        "text-red-700",
        "border-red-300",
        "bg-green-100",
        "text-green-700",
        "border-green-300"
    );


    if (type === "success") {

        box.classList.add(
            "bg-green-100",
            "text-green-700",
            "border-green-300"
        );

    } else {

        box.classList.add(
            "bg-red-100",
            "text-red-700",
            "border-red-300"
        );

    }


    box.textContent =
        message;

}


/* ==============================================================
   FORMAT MONEY
============================================================== */

function formatMoney(
    amount
) {

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


/* ==============================================================
   UPDATE SUMMARY
============================================================== */

function updateSummary(
    data
) {

    document.getElementById(
        "totalOrders"
    ).textContent =
        Number(
            data.total_orders || 0
        ).toLocaleString();


    document.getElementById(
        "totalSales"
    ).textContent =
        "₦" +
        formatMoney(
            data.total_sales || 0
        );


    document.getElementById(
        "totalProducts"
    ).textContent =
        Number(
            data.total_products || 0
        ).toLocaleString();


    document.getElementById(
        "lowStock"
    ).textContent =
        Number(
            data.low_stock || 0
        ).toLocaleString();


    document.getElementById(
        "pendingOrders"
    ).textContent =
        Number(
            data.pending_orders || 0
        ).toLocaleString();


    document.getElementById(
        "stockedOut"
    ).textContent =
        Number(
            data.stocked_out || 0
        ).toLocaleString();


    document.getElementById(
        "outOfStock"
    ).textContent =
        Number(
            data.out_of_stock || 0
        ).toLocaleString();


    document.getElementById(
        "pendingTransfers"
    ).textContent =
        Number(
            data.pending_transfers || 0
        ).toLocaleString();


    document.getElementById(
        "paidOrders"
    ).textContent =
        Number(
            data.paid_orders || 0
        ).toLocaleString();


    document.getElementById(
        "partialOrders"
    ).textContent =
        Number(
            data.partial_orders || 0
        ).toLocaleString();


    document.getElementById(
        "rejectedTransfers"
    ).textContent =
        Number(
            data.rejected_transfers || 0
        ).toLocaleString();

}


/* ==============================================================
   LOAD DASHBOARD SUMMARY
============================================================== */

async function loadDashboardSummary() {

    const token =
        getToken();


    if (!token) {

        showDashboardMessage(
            "Authentication token not found. Please login again.",
            "error"
        );

        return;

    }


    const loading =
        document.getElementById(
            "dashboardLoading"
        );


    if (loading) {
        loading.classList.remove(
            "hidden"
        );
    }


    const apiUrl =
        `${API_BASE_URL}/dashboard/dashboard/summary.php`;


    console.log(
        "Dashboard Summary API:",
        apiUrl
    );


    try {

        const response =
            await fetch(
                apiUrl,
                {
                    method: "GET",

                    headers: {
                        "Authorization":
                            `Bearer ${token}`,

                        "Accept":
                            "application/json"
                    }
                }
            );


        console.log(
            "Dashboard Summary Status:",
            response.status
        );


        const rawResponse =
            await response.text();


        console.log(
            "Dashboard Summary Raw Response:",
            rawResponse
        );


        if (!rawResponse.trim()) {

            throw new Error(
                "Dashboard API returned an empty response."
            );

        }


        let result;


        try {

            result =
                JSON.parse(
                    rawResponse
                );

        } catch (error) {

            console.error(
                "Dashboard API returned invalid JSON:",
                rawResponse
            );

            throw new Error(
                "Dashboard API returned invalid JSON."
            );

        }


        console.log(
            "Dashboard Summary Response:",
            result
        );


        if (!response.ok) {

            throw new Error(
                result.message ||
                result.error ||
                `Dashboard request failed. HTTP ${response.status}`
            );

        }


        if (!result.status) {

            throw new Error(
                result.message ||
                "Unable to load dashboard summary."
            );

        }


        const summary =
            result.data ||
            result.summary ||
            result;


        updateSummary(
            summary
        );


        showDashboardMessage(
            "Dashboard summary updated successfully.",
            "success"
        );


    } catch (error) {

        console.error(
            "Dashboard summary error:",
            error
        );


        showDashboardMessage(
            error.message ||
            "Unable to load dashboard summary.",
            "error"
        );


    } finally {

        if (loading) {

            loading.classList.add(
                "hidden"
            );

        }

    }

}


/* ==============================================================
   REFRESH BUTTON
============================================================== */

document.addEventListener(
    "DOMContentLoaded",
    function () {

        const refreshButton =
            document.getElementById(
                "refreshDashboard"
            );


        if (refreshButton) {

            refreshButton.addEventListener(
                "click",
                loadDashboardSummary
            );

        }


        loadDashboardSummary();

    }
);

</script>