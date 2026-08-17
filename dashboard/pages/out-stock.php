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
                    Stock Out Orders 
                </h1> 

                <p class="mt-1 text-sm text-gray-500"> 
                    Remove ordered products from inventory and process stock-out orders. 
                </p> 

            </div> 

        </div> 


        <!-- ==========================================================
             SEARCH / FILTER
        =========================================================== --> 

        <div class="mb-6 rounded-xl bg-white p-5 shadow-sm"> 

            <div class="grid grid-cols-1 gap-4 md:grid-cols-3"> 

                <!-- Search --> 

                <div> 

                    <label 
                        for="searchInput" 
                        class="mb-1 block text-sm font-medium text-gray-700"> 

                        Search Order 

                    </label> 

                    <input 
                        type="text" 
                        id="searchInput" 
                        placeholder="Order number, customer, phone..." 
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"> 

                </div> 


                <!-- Payment Status --> 

                <div> 

                    <label 
                        for="paymentStatusFilter" 
                        class="mb-1 block text-sm font-medium text-gray-700"> 

                        Payment Status 

                    </label> 

                    <select 
                        id="paymentStatusFilter" 
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm outline-none focus:border-blue-500"> 

                        <option value=""> 
                            All Payment Status 
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

                    </select> 

                </div> 


                <!-- Refresh --> 

                <div class="flex items-end"> 

                    <button 
                        type="button" 
                        id="refreshButton" 
                        class="w-full rounded-lg bg-gray-800 px-4 py-2.5 text-sm font-semibold text-white hover:bg-gray-700"> 

                        ↻ Refresh Orders 

                    </button> 

                </div> 

            </div> 

        </div> 


        <!-- ==========================================================
             LOADING
        =========================================================== --> 

        <div 
            id="loadingBox" 
            class="hidden mb-6 rounded-xl bg-white p-8 text-center shadow-sm"> 

            <div class="mx-auto mb-3 h-8 w-8 animate-spin rounded-full border-4 border-gray-200 border-t-blue-600"> 
            </div> 

            <p class="text-sm text-gray-500"> 
                Loading orders... 
            </p> 

        </div> 


        <!-- ==========================================================
             ORDERS TABLE
        =========================================================== --> 

        <div class="overflow-hidden rounded-xl bg-white shadow-sm"> 

            <div class="overflow-x-auto"> 

                <table class="w-full min-w-[1000px] text-left text-sm"> 

                    <thead class="bg-gray-50 text-xs uppercase text-gray-500"> 

                        <tr> 

                            <th class="px-5 py-4"> 
                                # 
                            </th> 

                            <th class="px-5 py-4"> 
                                Order 
                            </th> 

                            <th class="px-5 py-4"> 
                                Customer 
                            </th> 

                            <th class="px-5 py-4"> 
                                Store 
                            </th> 

                            <th class="px-5 py-4"> 
                                Total 
                            </th> 

                            <th class="px-5 py-4"> 
                                Payment 
                            </th> 

                            <th class="px-5 py-4"> 
                                Status 
                            </th> 

                            <th class="px-5 py-4"> 
                                Date 
                            </th> 

                            <th class="px-5 py-4 text-center"> 
                                Action 
                            </th> 

                        </tr> 

                    </thead> 


                    <tbody 
                        id="ordersTableBody" 
                        class="divide-y divide-gray-100"> 

                    </tbody> 

                </table> 

            </div> 


            <!-- ======================================================
                 EMPTY STATE
            ======================================================= --> 

            <div 
                id="emptyState" 
                class="hidden px-6 py-12 text-center"> 

                <div class="mb-3 text-4xl"> 
                    📦 
                </div> 

                <h3 class="text-lg font-semibold text-gray-700"> 
                    No orders found 
                </h3> 

                <p class="mt-1 text-sm text-gray-500"> 
                    There are no orders matching your search. 
                </p> 

            </div> 


            <!-- ======================================================
                 PAGINATION
            ======================================================= --> 

            <div 
                id="paginationContainer" 
                class="flex flex-col gap-3 border-t px-5 py-4 md:flex-row md:items-center md:justify-between"> 

            </div> 

        </div> 

    </div> 

</div> 


<!-- ==============================================================
     STOCK OUT CONFIRMATION MODAL
============================================================== --> 

<div 
    id="stockOutModal" 
    class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4"> 

    <div class="w-full max-w-md rounded-xl bg-white shadow-xl"> 

        <!-- Header --> 

        <div class="flex items-center justify-between border-b px-6 py-4"> 

            <h2 class="text-lg font-bold text-gray-800"> 
                Confirm Stock Out 
            </h2> 

            <button 
                type="button" 
                id="closeStockOutModal" 
                class="text-xl text-gray-400 hover:text-gray-700"> 

                ✕ 

            </button> 

        </div> 


        <!-- Body --> 

        <div class="px-6 py-6"> 

            <div class="mb-4 rounded-lg bg-yellow-50 p-4"> 

                <p class="text-sm text-yellow-800"> 

                    This action will remove the ordered quantities from 
                    inventory. 

                </p> 

            </div> 


            <div class="space-y-3 text-sm"> 

                <div class="flex justify-between"> 

                    <span class="font-medium text-gray-500"> 
                        Order: 
                    </span> 

                    <span 
                        id="modalOrderNumber" 
                        class="font-semibold text-gray-800"> 
                        - 
                    </span> 

                </div> 


                <div class="flex justify-between"> 

                    <span class="font-medium text-gray-500"> 
                        Customer: 
                    </span> 

                    <span 
                        id="modalCustomerName" 
                        class="font-semibold text-gray-800"> 
                        - 
                    </span> 

                </div> 


                <div class="flex justify-between"> 

                    <span class="font-medium text-gray-500"> 
                        Total: 
                    </span> 

                    <span 
                        id="modalOrderTotal" 
                        class="font-semibold text-gray-800"> 
                        ₦0.00 
                    </span> 

                </div> 

            </div> 

            <p class="mt-5 text-sm text-gray-600"> 

                Are you sure you want to stock out this order? 

            </p> 

        </div> 


        <!-- Footer --> 

        <div class="flex justify-end gap-3 border-t px-6 py-4"> 

            <button 
                type="button" 
                id="cancelStockOut" 
                class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50"> 

                Cancel 

            </button> 


            <button 
                type="button" 
                id="confirmStockOut" 
                class="rounded-lg bg-red-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-red-700"> 

                Stock Out 

            </button> 

        </div> 

    </div> 

</div> 


<script> 

/* ============================================================== 
   API CONFIGURATION 
============================================================== */ 

const API_BASE_URL = "<?php echo API_BASE_URL; ?>"; 


/* ============================================================== 
   AUTH TOKEN 
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
   GLOBAL VARIABLES 
============================================================== */ 

let allOrders = []; 

let filteredOrders = []; 

let currentPage = 1; 

const rowsPerPage = 10; 


/* ============================================================== 
   RESPONSE MESSAGE 
============================================================== */ 

function showMessage(message, type = "error") { 

    const box = document.getElementById("responseBox"); 

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


    box.textContent = message; 


    setTimeout(function () { 

        box.classList.add("hidden"); 

    }, 5000); 

} 


/* ============================================================== 
   CLEAR MESSAGE 
============================================================== */ 

function clearMessage() { 

    const box = 
        document.getElementById("responseBox"); 

    if (!box) { 

        return; 

    } 

    box.classList.add("hidden"); 

    box.textContent = ""; 

} 


/* ============================================================== 
   CHECK IF ORDER HAS BEEN STOCKED OUT
============================================================== */ 

function isOrderStockedOut(order) { 

    if (!order) { 

        return false; 

    } 


    /* ==========================================================
       DIRECT STATUS FIELDS
    =========================================================== */ 

    const stockOutStatus = String( 
        order.stock_out_status || 
        order.stockout_status || 
        order.stock_out || 
        "" 
    ).toLowerCase(); 


    if ( 
        stockOutStatus === "completed" || 
        stockOutStatus === "stocked_out" || 
        stockOutStatus === "stocked out" || 
        stockOutStatus === "out_of_stock" || 
        stockOutStatus === "out of stock" || 
        stockOutStatus === "true" || 
        stockOutStatus === "1" 
    ) { 

        return true; 

    } 


    /* ==========================================================
       NOTES CHECK
       
       Your stock-out API adds:
       
       STOCK_OUT_COMPLETED
       
       to orders.notes.
    =========================================================== */ 

    const notes = String( 
        order.notes || 
        "" 
    ); 


    if ( 
        notes.toUpperCase().includes( 
            "STOCK_OUT_COMPLETED" 
        ) 
    ) { 

        return true; 

    } 


    return false; 

} 


/* ============================================================== 
   LOAD ORDERS 
============================================================== */ 

async function loadOrders(page = 1) { 

    currentPage = page; 

    clearMessage(); 


    /* ==========================================================
       GET AUTHENTICATION TOKEN
    ========================================================== */ 

    const token = getToken(); 


    if (!token) { 

        showMessage( 
            "Authentication token not found. Please login again.", 
            "error" 
        ); 

        allOrders = []; 

        filteredOrders = []; 

        renderOrders(); 

        return; 

    } 


    /* ==========================================================
       API URL
    ========================================================== */ 

    const apiUrl = 
        `${API_BASE_URL}/dashboard/orders/list.php`; 


    console.log( 
        "Loading orders from:", 
        apiUrl 
    ); 


    try { 

        /* ======================================================
           REQUEST
        ====================================================== */ 

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
            "Load orders status:", 
            response.status 
        ); 


        /* ======================================================
           READ RAW RESPONSE
        ====================================================== */ 

        const rawResponse = 
            await response.text(); 


        console.log( 
            "Orders API response:", 
            rawResponse 
        ); 


        /* ======================================================
           HANDLE 404
        ====================================================== */ 

        if (response.status === 404) { 

            console.error( 
                "Orders API was not found:", 
                apiUrl 
            ); 

            throw new Error( 
                `Orders API not found (404). Check this URL: ${apiUrl}` 
            ); 

        } 


        /* ======================================================
           HANDLE OTHER HTTP ERRORS
        ====================================================== */ 

        if (!response.ok) { 

            const trimmed = 
                rawResponse 
                    .trim() 
                    .toLowerCase(); 


            if ( 
                trimmed.startsWith("<!doctype") || 
                trimmed.startsWith("<html") || 
                trimmed.startsWith("<!doctype html") 
            ) { 

                throw new Error( 
                    `Server returned HTML instead of JSON (${response.status}). Check the API URL: ${apiUrl}` 
                ); 

            } 


            throw new Error( 
                `Server returned status ${response.status}` 
            ); 

        } 


        /* ======================================================
           EMPTY RESPONSE
        ====================================================== */ 

        if (!rawResponse.trim()) { 

            throw new Error( 
                "Server returned an empty response." 
            ); 

        } 


        /* ======================================================
           PARSE JSON
        ====================================================== */ 

        let data; 

        try { 

            data = 
                JSON.parse(rawResponse); 

        } catch (error) { 

            console.error( 
                "Expected JSON but received:", 
                rawResponse 
            ); 

            throw new Error( 
                "Server returned invalid JSON. Check the API URL and PHP errors." 
            ); 

        } 


        /* ======================================================
           API STATUS
        ====================================================== */ 

        if (!data.status) { 

            throw new Error( 
                data.message || 
                "Unable to load orders." 
            ); 

        } 


        /* ======================================================
           GET ORDERS
        ====================================================== */ 

        if (Array.isArray(data.data)) { 

            allOrders = 
                data.data; 

        } else if (Array.isArray(data.orders)) { 

            allOrders = 
                data.orders; 

        } else { 

            allOrders = []; 

        } 


        /* ======================================================
           FILTERED ORDERS
        ====================================================== */ 

        filteredOrders = 
            [...allOrders]; 


        console.log( 
            "Orders loaded:", 
            allOrders 
        ); 


        /* ======================================================
           LOG STOCK-OUT ORDERS
        ====================================================== */ 

        const stockedOutOrders = 
            allOrders.filter( 
                function (order) { 
                    return isOrderStockedOut(order); 
                } 
            ); 


        console.log( 
            "Orders already stocked out:", 
            stockedOutOrders 
        ); 


        /* ======================================================
           RENDER
        ====================================================== */ 

        renderOrders(); 


    } catch (error) { 

        console.error( 
            "Load orders error:", 
            error 
        ); 


        allOrders = []; 

        filteredOrders = []; 


        renderOrders(); 


        showMessage( 
            error.message || 
            "Unable to load orders.", 
            "error" 
        ); 

    } 

} 


/* ============================================================== 
   RENDER ORDERS 
============================================================== */ 

function renderOrders() { 

    const tableBody = 
        document.getElementById( 
            "ordersTableBody" 
        ); 


    if (!tableBody) { 

        console.warn( 
            "ordersTableBody element was not found." 
        ); 

        return; 

    } 


    tableBody.innerHTML = ""; 


    /* ==========================================================
       EMPTY STATE
    =========================================================== */ 

    if ( 
        !filteredOrders || 
        filteredOrders.length === 0 
    ) { 

        tableBody.innerHTML = ` 

            <tr> 

                <td 
                    colspan="9" 
                    class="px-6 py-10 text-center text-gray-500" 
                > 

                    No orders found. 

                </td> 

            </tr> 

        `; 


        updatePagination(); 

        return; 

    } 


    /* ==========================================================
       PAGINATION
    =========================================================== */ 

    const startIndex = 
        (currentPage - 1) * 
        rowsPerPage; 


    const endIndex = 
        startIndex + 
        rowsPerPage; 


    const pageOrders = 
        filteredOrders.slice( 
            startIndex, 
            endIndex 
        ); 


    /* ==========================================================
       RENDER ROWS
    =========================================================== */ 

    pageOrders.forEach( 
        function (order, index) { 

            const rowNumber = 
                startIndex + index + 1; 


            const orderId = 
                Number( 
                    order.id || 0 
                ); 


            const orderNumber = 
                escapeHtml( 
                    order.order_number || 
                    order.order_no || 
                    "-" 
                ); 


            const customerName = 
                escapeHtml( 
                    order.customer_name || 
                    "Walk-in Customer" 
                ); 


            const customerPhone = 
                escapeHtml( 
                    order.customer_phone || 
                    "-" 
                ); 


            const storeName = 
                escapeHtml( 
                    order.store_name || 
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


            const total = 
                Number( 
                    order.total || 
                    order.total_amount || 
                    0 
                ); 


            const createdAt = 
                formatDate( 
                    order.created_at 
                ); 


            /* ==================================================
               CHECK STOCK OUT STATUS
            =================================================== */ 

            const stockedOut = 
                isOrderStockedOut(order); 


            let statusClass = 
                "bg-yellow-100 text-yellow-700"; 


            if ( 
                paymentStatus === "paid" 
            ) { 

                statusClass = 
                    "bg-green-100 text-green-700"; 

            } else if ( 
                paymentStatus === "failed" || 
                paymentStatus === "cancelled" 
            ) { 

                statusClass = 
                    "bg-red-100 text-red-700"; 

            } else if ( 
                paymentStatus === "partial" 
            ) { 

                statusClass = 
                    "bg-blue-100 text-blue-700"; 

            } 


            /* ==================================================
               STOCK OUT BADGE
            =================================================== */ 

            const stockOutBadge = stockedOut 

                ? ` 
                    <span 
                        class=" 
                            mt-1 
                            inline-flex 
                            items-center 
                            rounded-full 
                            bg-red-100 
                            px-3 
                            py-1 
                            text-xs 
                            font-bold 
                            text-red-700 
                            border 
                            border-red-200 
                        " 
                    > 
                        ✓ STOCKED OUT 
                    </span> 
                ` 

                : ""; 


            /* ==================================================
               STOCK OUT BUTTON
            =================================================== */ 

            const stockOutButton = stockedOut 

                ? ` 
                    <button 
                        type="button" 
                        disabled 
                        class=" 
                            cursor-not-allowed 
                            rounded-lg 
                            bg-gray-300 
                            px-3 
                            py-2 
                            text-xs 
                            font-medium 
                            text-gray-500 
                        " 
                        title="This order has already been stocked out" 
                    > 

                        ✓ Stocked Out 

                    </button> 
                ` 

                : ` 
                    <button 
                        type="button" 
                        onclick="stockOutOrder(${orderId})" 
                        class=" 
                            rounded-lg 
                            bg-red-600 
                            px-3 
                            py-2 
                            text-xs 
                            font-medium 
                            text-white 
                            hover:bg-red-700 
                        " 
                    > 

                        Stock Out 

                    </button> 
                `; 


            tableBody.innerHTML += ` 

                <tr 
                    class="border-b hover:bg-gray-50" 
                > 

                    <!-- NUMBER --> 

                    <td 
                        class="px-4 py-4 text-sm text-gray-600" 
                    > 

                        ${rowNumber} 

                    </td> 


                    <!-- ORDER NUMBER --> 

                    <td 
                        class="px-4 py-4" 
                    > 

                        <div 
                            class="font-semibold text-gray-800" 
                        > 

                            ${orderNumber} 

                        </div> 

                    </td> 


                    <!-- CUSTOMER --> 

                    <td 
                        class="px-4 py-4" 
                    > 

                        <div 
                            class="font-medium text-gray-800" 
                        > 

                            ${customerName} 

                        </div> 

                        <div 
                            class="text-xs text-gray-500" 
                        > 

                            ${customerPhone} 

                        </div> 

                    </td> 


                    <!-- STORE --> 

                    <td 
                        class="px-4 py-4 text-sm text-gray-600" 
                    > 

                        ${storeName} 

                    </td> 


                    <!-- TOTAL --> 

                    <td 
                        class="px-4 py-4 text-right font-semibold" 
                    > 

                        ₦${formatMoney(total)} 

                    </td> 


                    <!-- PAYMENT METHOD --> 

                    <td 
                        class="px-4 py-4 text-sm" 
                    > 

                        ${paymentMethod} 

                    </td> 


                    <!-- PAYMENT STATUS --> 

                    <td 
                        class="px-4 py-4" 
                    > 

                        <div class="flex flex-col items-start gap-1"> 

                            <span 
                                class=" 
                                    inline-flex 
                                    rounded-full 
                                    px-3 
                                    py-1 
                                    text-xs 
                                    font-semibold 
                                    ${statusClass} 
                                " 
                            > 

                                ${escapeHtml( 
                                    paymentStatus.toUpperCase() 
                                )} 

                            </span> 


                            ${stockOutBadge} 

                        </div> 

                    </td> 


                    <!-- DATE --> 

                    <td 
                        class="px-4 py-4 text-sm text-gray-600" 
                    > 

                        ${createdAt} 

                    </td> 


                    <!-- ACTIONS --> 

                    <td 
                        class="px-4 py-4" 
                    > 

                        <div 
                            class=" 
                                flex 
                                gap-2 
                                whitespace-nowrap 
                            " 
                        > 

                            <!-- VIEW --> 

                            <button 
                                type="button" 
                                onclick="viewOrder(${orderId})" 
                                class=" 
                                    rounded-lg 
                                    bg-blue-600 
                                    px-3 
                                    py-2 
                                    text-xs 
                                    font-medium 
                                    text-white 
                                    hover:bg-blue-700 
                                " 
                            > 

                                View 

                            </button> 


                            <!-- PRINT --> 

                            <button 
                                type="button" 
                                onclick="printOrder(${orderId})" 
                                class=" 
                                    rounded-lg 
                                    bg-gray-700 
                                    px-3 
                                    py-2 
                                    text-xs 
                                    font-medium 
                                    text-white 
                                    hover:bg-gray-800 
                                " 
                            > 

                                Print 

                            </button> 


                            <!-- STOCK OUT --> 

                            ${stockOutButton} 

                        </div> 

                    </td> 

                </tr> 

            `; 

        } 
    ); 


    updatePagination(); 

} 


/* ============================================================== 
   VIEW ORDER 
============================================================== */ 

function viewOrder(orderId) { 

    if (!orderId) { 

        showMessage( 
            "Invalid order ID.", 
            "error" 
        ); 

        return; 

    } 


    window.open( 
        `view-order-receipt.php?id=${encodeURIComponent(orderId)}`, 
        "_blank" 
    ); 

} 


/* ============================================================== 
   PRINT ORDER 
============================================================== */ 

function printOrder(orderId) { 

    if (!orderId) { 

        showMessage( 
            "Invalid order ID.", 
            "error" 
        ); 

        return; 

    } 


    const printWindow = 
        window.open( 
            `view-order-receipt.php?id=${encodeURIComponent(orderId)}&print=1`, 
            "_blank" 
        ); 


    if (!printWindow) { 

        showMessage( 
            "Unable to open receipt. Please allow pop-ups.", 
            "error" 
        ); 

        return; 

    } 


    printWindow.onload = 
        function () { 

            printWindow.print(); 

        }; 

} 


/* ============================================================== 
   STOCK OUT ORDER 
============================================================== */ 

async function stockOutOrder(orderId) { 

    if (!orderId) { 

        showMessage( 
            "Invalid order ID.", 
            "error" 
        ); 

        return; 

    } 


    const token = getToken(); 


    if (!token) { 

        showMessage( 
            "Authentication token not found. Please login again.", 
            "error" 
        ); 

        return; 

    } 


    /* ==========================================================
       CHECK CURRENT ORDER STATUS BEFORE PROCESSING
    =========================================================== */ 

    const existingOrder = 
        allOrders.find( 
            function (order) { 

                return Number(order.id) === 
                    Number(orderId); 

            } 
        ); 


    if (existingOrder && isOrderStockedOut(existingOrder)) { 

        console.warn( 
            "Stock-out prevented. Order is already stocked out:", 
            existingOrder 
        ); 


        showMessage( 
            "This order has already been stocked out.", 
            "error" 
        ); 

        return; 

    } 


    const confirmed = confirm( 
        "Are you sure you want to stock out the products for this order?" 
    ); 


    if (!confirmed) { 

        return; 

    } 


    const apiUrl = 
        `${API_BASE_URL}/dashboard/orders/stock-out-order.php`; 


    console.log( 
        "Stock Out API:", 
        apiUrl 
    ); 


    console.log( 
        "Stock Out Order ID:", 
        orderId 
    ); 


    try { 

        /* ======================================================
           PROCESSING MESSAGE
        ====================================================== */ 

        showMessage( 
            "Processing stock out...", 
            "success" 
        ); 


        console.log( 
            "Starting stock out for order:", 
            orderId 
        ); 


        const response = await fetch( 
            apiUrl, 
            { 

                method: "POST", 


                headers: { 

                    "Content-Type": 
                        "application/json", 

                    "Authorization": 
                        `Bearer ${token}` 

                }, 


                body: JSON.stringify({ 

                    order_id: 
                        Number(orderId) 

                }) 

            } 
        ); 


        console.log( 
            "Stock Out Status:", 
            response.status 
        ); 


        const rawResponse = 
            await response.text(); 


        console.log( 
            "Stock Out Raw Response:", 
            rawResponse 
        ); 


        if (!rawResponse.trim()) { 

            throw new Error( 

                `Stock-out API returned an empty response. HTTP ${response.status}` 

            ); 

        } 


        let data; 


        try { 

            data = 
                JSON.parse(rawResponse); 

        } catch (jsonError) { 

            console.error( 
                "Stock-out API returned non-JSON:", 
                rawResponse 
            ); 


            throw new Error( 

                `Stock-out API returned invalid JSON. HTTP ${response.status}` 

            ); 

        } 


        /* ======================================================
           LOG PARSED RESPONSE
        ====================================================== */ 

        console.log( 
            "Stock Out Parsed Response:", 
            data 
        ); 


        /* ======================================================
           HTTP ERROR
        ====================================================== */ 

        if (!response.ok) { 

            console.error( 
                "Stock Out HTTP Error:", 
                data 
            ); 


            throw new Error( 

                data.message || 
                data.error || 
                `Stock out failed. HTTP ${response.status}` 

            ); 

        } 


        /* ======================================================
           API ERROR
        ====================================================== */ 

        if (!data.status) { 

            console.error( 
                "Stock Out API Error:", 
                data 
            ); 


            throw new Error( 

                data.message || 
                data.error || 
                "Stock out failed." 

            ); 

        } 


        /* ======================================================
           SUCCESS LOG
        ====================================================== */ 

        console.log( 
            "====================================" 
        ); 


        console.log( 
            "STOCK OUT SUCCESSFUL" 
        ); 


        console.log( 
            "Order ID:", 
            orderId 
        ); 


        console.log( 
            "Order Number:", 
            data.data?.order_no || 
            existingOrder?.order_number || 
            existingOrder?.order_no || 
            "-" 
        ); 


        console.log( 
            "Stocked Out Items:", 
            data.data?.items || 
            [] 
        ); 


        console.log( 
            "Stocked Out At:", 
            data.data?.stocked_out_at || 
            "-" 
        ); 


        console.log( 
            "Stocked Out By:", 
            data.data?.stocked_out_by || 
            "-" 
        ); 


        console.log( 
            "Full Stock Out Success Response:", 
            data 
        ); 


        console.log( 
            "====================================" 
        ); 


        /* ======================================================
           SUCCESS MESSAGE
        ====================================================== */ 

        showMessage( 

            data.message || 
            "Order successfully stocked out.", 

            "success" 

        ); 


        /* ======================================================
           RELOAD ORDERS
           
           This will make the STOCKED OUT badge appear
           and disable the Stock Out button.
        ====================================================== */ 

        await loadOrders(currentPage); 


    } catch (error) { 

        console.error( 
            "Stock out error:", 
            error 
        ); 


        console.error( 
            "Stock out error message:", 
            error.message 
        ); 


        showMessage( 

            error.message || 
            "Unable to stock out order.", 

            "error" 

        ); 

    } 

} 


/* ============================================================== 
   SEARCH ORDERS 
============================================================== */ 

function searchOrders() { 

    const input = 
        document.getElementById( 
            "searchInput" 
        ); 


    const search = 
        input 
            ? input.value 
                .trim() 
                .toLowerCase() 
            : ""; 


    if (!search) { 

        filteredOrders = 
            [...allOrders]; 

        currentPage = 1; 

        renderOrders(); 

        return; 

    } 


    filteredOrders = 
        allOrders.filter( 
            function (order) { 

                const orderNumber = 
                    String( 
                        order.order_number || 
                        order.order_no || 
                        "" 
                    ).toLowerCase(); 


                const customerName = 
                    String( 
                        order.customer_name || 
                        "" 
                    ).toLowerCase(); 


                const phone = 
                    String( 
                        order.customer_phone || 
                        "" 
                    ).toLowerCase(); 


                const email = 
                    String( 
                        order.customer_email || 
                        "" 
                    ).toLowerCase(); 


                return ( 

                    orderNumber.includes(search) || 
                    customerName.includes(search) || 
                    phone.includes(search) || 
                    email.includes(search) 

                ); 

            } 
        ); 


    currentPage = 1; 

    renderOrders(); 

} 


/* ============================================================== 
   FILTER PAYMENT STATUS 
============================================================== */ 

function filterPaymentStatus() { 

    const select = 
        document.getElementById( 
            "paymentStatusFilter" 
        ); 


    const value = 
        select 
            ? select.value 
            : ""; 


    if (!value) { 

        filteredOrders = 
            [...allOrders]; 

    } else { 

        filteredOrders = 
            allOrders.filter( 
                function (order) { 

                    return ( 

                        String( 
                            order.payment_status || 
                            "" 
                        ).toLowerCase() 

                        === 

                        value.toLowerCase() 

                    ); 

                } 
            ); 

    } 


    currentPage = 1; 

    renderOrders(); 

} 


/* ============================================================== 
   PAGINATION 
============================================================== */ 

function updatePagination() { 

    const totalPages = 
        Math.max( 
            1, 
            Math.ceil( 
                filteredOrders.length / 
                rowsPerPage 
            ) 
        ); 


    const pagination = 
        document.getElementById( 
            "paginationContainer" 
        ); 


    if (!pagination) { 

        return; 

    } 


    pagination.innerHTML = ` 

        <div 
            class=" 
                flex 
                items-center 
                justify-between 
                gap-4 
            " 
        > 

            <span 
                class="text-sm text-gray-500" 
            > 

                Showing 

                ${filteredOrders.length === 0 
                    ? 0 
                    : ((currentPage - 1) * rowsPerPage) + 1} 

                - 

                ${Math.min( 
                    currentPage * rowsPerPage, 
                    filteredOrders.length 
                )} 

                of 

                ${filteredOrders.length} 

            </span> 


            <div 
                class="flex gap-2" 
            > 

                <button 
                    type="button" 
                    onclick="previousPage()" 
                    ${currentPage <= 1 ? "disabled" : ""} 
                    class=" 
                        rounded-lg 
                        border 
                        px-4 
                        py-2 
                        text-sm 
                        disabled:cursor-not-allowed 
                        disabled:opacity-50 
                    " 
                > 

                    Previous 

                </button> 


                <span 
                    class=" 
                        rounded-lg 
                        bg-gray-100 
                        px-4 
                        py-2 
                        text-sm 
                    " 
                > 

                    Page ${currentPage} 
                    of ${totalPages} 

                </span> 


                <button 
                    type="button" 
                    onclick="nextPage()" 
                    ${currentPage >= totalPages ? "disabled" : ""} 
                    class=" 
                        rounded-lg 
                        border 
                        px-4 
                        py-2 
                        text-sm 
                        disabled:cursor-not-allowed 
                        disabled:opacity-50 
                    " 
                > 

                    Next 

                </button> 

            </div> 

        </div> 

    `; 

} 


/* ============================================================== 
   PREVIOUS PAGE 
============================================================== */ 

function previousPage() { 

    if (currentPage <= 1) { 

        return; 

    } 


    currentPage--; 

    renderOrders(); 

} 


/* ============================================================== 
   NEXT PAGE 
============================================================== */ 

function nextPage() { 

    const totalPages = 
        Math.max( 
            1, 
            Math.ceil( 
                filteredOrders.length / 
                rowsPerPage 
            ) 
        ); 


    if ( 
        currentPage >= totalPages 
    ) { 

        return; 

    } 


    currentPage++; 

    renderOrders(); 

} 


/* ============================================================== 
   FORMAT MONEY 
============================================================== */ 

function formatMoney(amount) { 

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
   FORMAT DATE 
============================================================== */ 

function formatDate(dateValue) { 

    if (!dateValue) { 

        return "-"; 

    } 


    const date = 
        new Date(dateValue); 


    if ( 
        Number.isNaN( 
            date.getTime() 
        ) 
    ) { 

        return "-"; 

    } 


    return date.toLocaleString( 
        "en-NG", 
        { 
            day: "2-digit", 
            month: "short", 
            year: "numeric", 
            hour: "2-digit", 
            minute: "2-digit" 
        } 
    ); 

} 


/* ============================================================== 
   HTML ESCAPE 
============================================================== */ 

function escapeHtml(value) { 

    return String( 
        value ?? "" 
    ) 

    .replace( 
        /&/g, 
        "&amp;" 
    ) 

    .replace( 
        /</g, 
        "&lt;" 
    ) 

    .replace( 
        />/g, 
        "&gt;" 
    ) 

    .replace( 
        /"/g, 
        "&quot;" 
    ) 

    .replace( 
        /'/g, 
        "&#039;" 
    ); 

} 


/* ============================================================== 
   SEARCH / FILTER / REFRESH EVENTS
============================================================== */ 

document.addEventListener( 
    "DOMContentLoaded", 
    function () { 


        /* ======================================================
           SEARCH
        ====================================================== */ 

        const searchInput = 
            document.getElementById( 
                "searchInput" 
            ); 


        if (searchInput) { 

            searchInput.addEventListener( 
                "input", 
                searchOrders 
            ); 

        } 


        /* ======================================================
           PAYMENT STATUS
        ====================================================== */ 

        const paymentStatus = 
            document.getElementById( 
                "paymentStatusFilter" 
            ); 


        if (paymentStatus) { 

            paymentStatus.addEventListener( 
                "change", 
                filterPaymentStatus 
            ); 

        } 


        /* ======================================================
           REFRESH BUTTON
        ====================================================== */ 

        const refreshButton = 
            document.getElementById( 
                "refreshButton" 
            ); 


        if (refreshButton) { 

            refreshButton.addEventListener( 

                "click", 

                function () { 

                    console.log( 
                        "Refreshing stock-out orders..." 
                    ); 


                    loadOrders(currentPage); 

                } 

            ); 

        } 


        /* ======================================================
           LOAD ORDERS
        ====================================================== */ 

        loadOrders(1); 

    } 
); 

</script>