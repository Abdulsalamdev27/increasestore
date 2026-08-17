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
 * DATABASE + AUTH
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
            payment_status,
            notes
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
            "Order not found."
        );
    }

    $order = $orderResult->fetch_assoc();

    $orderStmt->close();


    /**
     * -----------------------------------------------------
     * CHECK ALREADY STOCKED OUT
     * -----------------------------------------------------
     */

    $existingNotes = $order['notes'] ?? '';

    if (
        stripos(
            $existingNotes,
            'STOCK_OUT_COMPLETED'
        ) !== false
    ) {

        $conn->rollback();

        http_response_code(409);

        echo json_encode([
            "status" => false,
            "message" => "This order has already been stocked out.",
            "order_id" => $orderId
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

    while ($item = $itemsResult->fetch_assoc()) {
        $orderItems[] = $item;
    }

    $itemsStmt->close();


    /**
     * -----------------------------------------------------
     * PREPARE PRODUCT SELECT
     * -----------------------------------------------------
     *
     * Stock source:
     * products.quantity
     *
     * -----------------------------------------------------
     */

    $productSelectSql = "
        SELECT
            id,
            product_name,
            barcode,
            sku,
            quantity,
            minimum_stock,
            status,
            is_active
        FROM products
        WHERE id = ?
        LIMIT 1
        FOR UPDATE
    ";

    $productSelectStmt =
        $conn->prepare($productSelectSql);

    if (!$productSelectStmt) {
        throw new Exception(
            "Unable to prepare product query: " .
            $conn->error
        );
    }


    /**
     * -----------------------------------------------------
     * PREPARE PRODUCT UPDATE
     * -----------------------------------------------------
     */

    $productUpdateSql = "
        UPDATE products
        SET quantity = quantity - ?
        WHERE id = ?
        AND quantity >= ?
    ";

    $productUpdateStmt =
        $conn->prepare($productUpdateSql);

    if (!$productUpdateStmt) {
        throw new Exception(
            "Unable to prepare product update: " .
            $conn->error
        );
    }


    /**
     * -----------------------------------------------------
     * STOCK OUT PRODUCTS
     * -----------------------------------------------------
     */

    $stockedOutItems = [];

    foreach ($orderItems as $item) {

        $productId = (int)(
            $item['product_id'] ?? 0
        );

        $quantity = (int)(
            $item['quantity'] ?? 0
        );

        $productName = trim(
            $item['product_name'] ??
            'Unknown Product'
        );


        /**
         * Validate product ID
         */

        if ($productId <= 0) {
            throw new Exception(
                "Invalid product ID for " .
                $productName
            );
        }


        /**
         * Validate quantity
         */

        if ($quantity <= 0) {
            throw new Exception(
                "Invalid quantity for " .
                $productName
            );
        }


        /**
         * -------------------------------------------------
         * GET CURRENT PRODUCT
         * -------------------------------------------------
         */

        $productSelectStmt->bind_param(
            "i",
            $productId
        );

        if (!$productSelectStmt->execute()) {
            throw new Exception(
                "Unable to fetch product " .
                $productName .
                ": " .
                $productSelectStmt->error
            );
        }

        $productResult =
            $productSelectStmt->get_result();

        if ($productResult->num_rows === 0) {
            throw new Exception(
                "Product not found: " .
                $productName
            );
        }

        $product =
            $productResult->fetch_assoc();


        /**
         * -------------------------------------------------
         * CURRENT STOCK
         * -------------------------------------------------
         */

        $currentStock = (int)(
            $product['quantity'] ?? 0
        );


        /**
         * -------------------------------------------------
         * CHECK STOCK
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
         * DEDUCT PRODUCT STOCK
         * -------------------------------------------------
         */

        $productUpdateStmt->bind_param(
            "iii",
            $quantity,
            $productId,
            $quantity
        );

        if (!$productUpdateStmt->execute()) {

            throw new Exception(
                "Unable to stock out " .
                $productName .
                ": " .
                $productUpdateStmt->error
            );
        }


        /**
         * Verify update
         */

        if ($productUpdateStmt->affected_rows !== 1) {

            throw new Exception(
                "Stock update failed for " .
                $productName
            );
        }


        /**
         * -------------------------------------------------
         * CALCULATE NEW STOCK
         * -------------------------------------------------
         */

        $newStock =
            $currentStock - $quantity;


        /**
         * -------------------------------------------------
         * UPDATE PRODUCT STATUS
         * -------------------------------------------------
         */

        if ($newStock <= 0) {

            $statusSql = "
                UPDATE products
                SET status = 'out_of_stock'
                WHERE id = ?
            ";

        } elseif ((int)$product['is_active'] === 1) {

            $statusSql = "
                UPDATE products
                SET status = 'available'
                WHERE id = ?
            ";

        } else {

            $statusSql = null;
        }


        if ($statusSql !== null) {

            $statusStmt =
                $conn->prepare($statusSql);

            if (!$statusStmt) {
                throw new Exception(
                    "Unable to prepare product status update: " .
                    $conn->error
                );
            }

            $statusStmt->bind_param(
                "i",
                $productId
            );

            if (!$statusStmt->execute()) {

                $error =
                    $statusStmt->error;

                $statusStmt->close();

                throw new Exception(
                    "Unable to update product status for " .
                    $productName .
                    ": " .
                    $error
                );
            }

            $statusStmt->close();
        }


        /**
         * -------------------------------------------------
         * STORE RESULT
         * -------------------------------------------------
         */

        $stockedOutItems[] = [

            "product_id" =>
                $productId,

            "product_name" =>
                $product['product_name'],

            "barcode" =>
                $product['barcode'],

            "sku" =>
                $product['sku'],

            "quantity_removed" =>
                $quantity,

            "previous_stock" =>
                $currentStock,

            "remaining_stock" =>
                $newStock,

            "status" =>
                $newStock <= 0
                    ? "out_of_stock"
                    : "available"

        ];
    }


    /**
     * -----------------------------------------------------
     * CLOSE PRODUCT STATEMENTS
     * -----------------------------------------------------
     */

    $productSelectStmt->close();
    $productUpdateStmt->close();


    /**
     * -----------------------------------------------------
     * UPDATE ORDER NOTES
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


    /**
     * -----------------------------------------------------
     * UPDATE ORDER
     * -----------------------------------------------------
     */

    $updateOrderSql = "
        UPDATE orders
        SET notes = ?
        WHERE id = ?
    ";

    $updateOrderStmt =
        $conn->prepare($updateOrderSql);

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

    if (!$updateOrderStmt->execute()) {

        $error =
            $updateOrderStmt->error;

        $updateOrderStmt->close();

        throw new Exception(
            "Unable to update order: " .
            $error
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
     * SUCCESS RESPONSE
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