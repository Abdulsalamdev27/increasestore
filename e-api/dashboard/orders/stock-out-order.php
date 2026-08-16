<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

/**
 * ---------------------------------------------------------
 * HEADERS
 * ---------------------------------------------------------
 */

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: POST, OPTIONS");


/**
 * ---------------------------------------------------------
 * OPTIONS
 * ---------------------------------------------------------
 */

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {

    http_response_code(200);

    exit;
}


/**
 * ---------------------------------------------------------
 * METHOD
 * ---------------------------------------------------------
 */

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    http_response_code(405);

    echo json_encode([
        "status" => false,
        "message" => "Method not allowed"
    ]);

    exit;
}


/**
 * ---------------------------------------------------------
 * DATABASE
 * ---------------------------------------------------------
 */

require_once __DIR__ . "/../../../config/dbconn.php";
require_once __DIR__ . "/../../middleware/auth.php";


/**
 * ---------------------------------------------------------
 * AUTHENTICATION
 * ---------------------------------------------------------
 */

$user = $GLOBALS['authUser'] ?? null;

if (!$user) {

    http_response_code(401);

    echo json_encode([
        "status" => false,
        "message" => "Unauthorized"
    ]);

    exit;
}


/**
 * ---------------------------------------------------------
 * READ JSON BODY
 * ---------------------------------------------------------
 */

$input = json_decode(
    file_get_contents("php://input"),
    true
);


if (!is_array($input)) {

    http_response_code(400);

    echo json_encode([
        "status" => false,
        "message" => "Invalid JSON request body"
    ]);

    exit;
}


/**
 * ---------------------------------------------------------
 * ORDER ID
 * ---------------------------------------------------------
 */

$orderId = isset($input['order_id'])
    ? (int)$input['order_id']
    : 0;


if ($orderId <= 0) {

    http_response_code(400);

    echo json_encode([
        "status" => false,
        "message" => "A valid order_id is required"
    ]);

    exit;
}


/**
 * ---------------------------------------------------------
 * START TRANSACTION
 * ---------------------------------------------------------
 */

$conn->begin_transaction();


try {

    /**
     * -----------------------------------------------------
     * FETCH ORDER
     * -----------------------------------------------------
     */

    $orderSql = "
        SELECT
            id,
            order_no,
            payment_status
        FROM orders
        WHERE id = ?
        LIMIT 1
        FOR UPDATE
    ";


    $orderStmt = $conn->prepare($orderSql);


    if (!$orderStmt) {

        throw new Exception(
            "Unable to prepare order query: " .
            $conn->error
        );

    }


    $orderStmt->bind_param(
        "i",
        $orderId
    );


    if (!$orderStmt->execute()) {

        throw new Exception(
            "Unable to fetch order: " .
            $orderStmt->error
        );

    }


    $orderResult = $orderStmt->get_result();


    if ($orderResult->num_rows === 0) {

        $orderStmt->close();

        throw new Exception(
            "Order not found"
        );

    }


    $order = $orderResult->fetch_assoc();


    $orderStmt->close();


    /**
     * -----------------------------------------------------
     * CHECK IF ORDER WAS ALREADY STOCKED OUT
     * -----------------------------------------------------
     *
     * We use the order's notes field to record the
     * stock-out action.
     *
     * If you have a dedicated stock_out_status column,
     * use that instead.
     *
     * -----------------------------------------------------
     */

    $checkSql = "
        SELECT notes
        FROM orders
        WHERE id = ?
        LIMIT 1
    ";


    $checkStmt = $conn->prepare($checkSql);


    if (!$checkStmt) {

        throw new Exception(
            "Unable to check order status: " .
            $conn->error
        );

    }


    $checkStmt->bind_param(
        "i",
        $orderId
    );


    $checkStmt->execute();


    $checkResult = $checkStmt->get_result();


    $checkOrder = $checkResult->fetch_assoc();


    $checkStmt->close();


    $existingNotes = $checkOrder['notes'] ?? '';


    if (
        stripos(
            $existingNotes,
            'STOCK_OUT_COMPLETED'
        ) !== false
    ) {

        $conn->rollback();


        echo json_encode([

            "status" => false,

            "message" =>
                "This order has already been stocked out.",

            "order_id" =>
                $orderId

        ]);

        exit;

    }


    /**
     * -----------------------------------------------------
     * FETCH ORDER ITEMS
     * -----------------------------------------------------
     */

    $itemsSql = "
        SELECT
            id,
            order_id,
            store_inventory_id,
            store_id,
            product_id,
            product_name,
            barcode,
            quantity
        FROM order_items
        WHERE order_id = ?
        ORDER BY id ASC
        FOR UPDATE
    ";


    $itemsStmt = $conn->prepare($itemsSql);


    if (!$itemsStmt) {

        throw new Exception(
            "Unable to prepare order items query: " .
            $conn->error
        );

    }


    $itemsStmt->bind_param(
        "i",
        $orderId
    );


    if (!$itemsStmt->execute()) {

        throw new Exception(
            "Unable to fetch order items: " .
            $itemsStmt->error
        );

    }


    $itemsResult = $itemsStmt->get_result();


    if ($itemsResult->num_rows === 0) {

        $itemsStmt->close();

        throw new Exception(
            "This order does not contain any products."
        );

    }


    $orderItems = [];


    while (
        $item = $itemsResult->fetch_assoc()
    ) {

        $orderItems[] = $item;

    }


    $itemsStmt->close();


    /**
     * -----------------------------------------------------
     * PREPARE INVENTORY QUERY
     * -----------------------------------------------------
     *
     * IMPORTANT:
     *
     * Change "store_inventory" below if your actual
     * inventory table has another name.
     *
     * The code assumes:
     *
     * store_inventory.id
     * store_inventory.quantity
     *
     * -----------------------------------------------------
     */

    $inventorySelectSql = "
        SELECT
            id,
            quantity
        FROM store_inventory
        WHERE id = ?
        LIMIT 1
        FOR UPDATE
    ";


    $inventorySelectStmt =
        $conn->prepare(
            $inventorySelectSql
        );


    if (!$inventorySelectStmt) {

        throw new Exception(
            "Unable to prepare inventory query: " .
            $conn->error
        );

    }


    /**
     * -----------------------------------------------------
     * PREPARE INVENTORY UPDATE
     * -----------------------------------------------------
     */

    $inventoryUpdateSql = "
        UPDATE store_inventory
        SET quantity = quantity - ?
        WHERE id = ?
        AND quantity >= ?
    ";


    $inventoryUpdateStmt =
        $conn->prepare(
            $inventoryUpdateSql
        );


    if (!$inventoryUpdateStmt) {

        throw new Exception(
            "Unable to prepare inventory update: " .
            $conn->error
        );

    }


    /**
     * -----------------------------------------------------
     * STOCK OUT EACH PRODUCT
     * -----------------------------------------------------
     */

    $stockedOutItems = [];


    foreach ($orderItems as $item) {

        $inventoryId =
            (int)(
                $item['store_inventory_id']
                ?? 0
            );


        $quantity =
            (int)(
                $item['quantity']
                ?? 0
            );


        $productName =
            $item['product_name']
            ?? 'Unknown Product';


        /**
         * Invalid inventory ID
         */

        if ($inventoryId <= 0) {

            throw new Exception(
                "No store inventory record found for " .
                $productName
            );

        }


        /**
         * Invalid quantity
         */

        if ($quantity <= 0) {

            throw new Exception(
                "Invalid quantity for " .
                $productName
            );

        }


        /**
         * -------------------------------------------------
         * GET CURRENT STOCK
         * -------------------------------------------------
         */

        $inventorySelectStmt->bind_param(
            "i",
            $inventoryId
        );


        if (
            !$inventorySelectStmt->execute()
        ) {

            throw new Exception(
                "Unable to fetch inventory for " .
                $productName
            );

        }


        $inventoryResult =
            $inventorySelectStmt->get_result();


        if (
            $inventoryResult->num_rows === 0
        ) {

            throw new Exception(
                "Inventory record not found for " .
                $productName
            );

        }


        $inventory =
            $inventoryResult->fetch_assoc();


        $currentStock =
            (int)(
                $inventory['quantity']
                ?? 0
            );


        /**
         * -------------------------------------------------
         * CHECK AVAILABLE STOCK
         * -------------------------------------------------
         */

        if ($currentStock < $quantity) {

            throw new Exception(

                "Insufficient stock for " .
                $productName .
                ". Available: " .
                $currentStock .
                ", Required: " .
                $quantity

            );

        }


        /**
         * -------------------------------------------------
         * UPDATE STOCK
         * -------------------------------------------------
         */

        $inventoryUpdateStmt->bind_param(
            "iii",
            $quantity,
            $inventoryId,
            $quantity
        );


        if (
            !$inventoryUpdateStmt->execute()
        ) {

            throw new Exception(

                "Unable to stock out " .
                $productName .
                ": " .
                $inventoryUpdateStmt->error

            );

        }


        if (
            $inventoryUpdateStmt->affected_rows !== 1
        ) {

            throw new Exception(

                "Stock update failed for " .
                $productName

            );

        }


        $newStock =
            $currentStock - $quantity;


        $stockedOutItems[] = [

            "product_id" =>
                (int)(
                    $item['product_id']
                    ?? 0
                ),

            "product_name" =>
                $productName,

            "barcode" =>
                $item['barcode'] ?? null,

            "store_inventory_id" =>
                $inventoryId,

            "quantity_removed" =>
                $quantity,

            "previous_stock" =>
                $currentStock,

            "remaining_stock" =>
                $newStock

        ];

    }


    $inventorySelectStmt->close();
    $inventoryUpdateStmt->close();


    /**
     * -----------------------------------------------------
     * UPDATE ORDER NOTES
     * -----------------------------------------------------
     *
     * This records that stock-out has already happened.
     *
     * -----------------------------------------------------
     */

    $stockOutTime =
        date("Y-m-d H:i:s");


    $stockOutUser =
        isset($user['id'])
            ? (int)$user['id']
            : 0;


    $newNotes =
        trim($existingNotes);


    if ($newNotes !== '') {

        $newNotes .= "\n\n";

    }


    $newNotes .=
        "STOCK_OUT_COMPLETED | " .
        "Date: " .
        $stockOutTime .
        " | User ID: " .
        $stockOutUser;


    $updateOrderSql = "
        UPDATE orders
        SET notes = ?
        WHERE id = ?
    ";


    $updateOrderStmt =
        $conn->prepare(
            $updateOrderSql
        );


    if (!$updateOrderStmt) {

        throw new Exception(
            "Unable to prepare order update: " .
            $conn->error
        );

    }


    $updateOrderStmt->bind_param(
        "si",
        $newNotes,
        $orderId
    );


    if (
        !$updateOrderStmt->execute()
    ) {

        throw new Exception(
            "Unable to update order: " .
            $updateOrderStmt->error
        );

    }


    $updateOrderStmt->close();


    /**
     * -----------------------------------------------------
     * COMMIT
     * -----------------------------------------------------
     */

    $conn->commit();


    /**
     * -----------------------------------------------------
     * RESPONSE
     * -----------------------------------------------------
     */

    http_response_code(200);


    echo json_encode([

        "status" => true,

        "message" =>
            "Order stocked out successfully.",

        "data" => [

            "order_id" =>
                $orderId,

            "order_no" =>
                $order['order_no'],

            "payment_status" =>
                $order['payment_status'],

            "stocked_out_at" =>
                $stockOutTime,

            "stocked_out_by" =>
                $stockOutUser,

            "items" =>
                $stockedOutItems

        ]

    ]);

    exit;


} catch (Throwable $e) {


    /**
     * -----------------------------------------------------
     * ROLLBACK
     * -----------------------------------------------------
     */

    $conn->rollback();


    http_response_code(500);


    echo json_encode([

        "status" => false,

        "message" =>
            "Stock out failed.",

        "error" =>
            $e->getMessage(),

        "order_id" =>
            $orderId

    ]);


    exit;

}

?>