<?php

ini_set('display_errors', 0);
error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Methods: POST, OPTIONS');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {

    http_response_code(200);

    exit;

}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    http_response_code(405);

    echo json_encode([
        'status' => false,
        'message' => 'Method not allowed.'
    ]);

    exit;

}


/*
|--------------------------------------------------------------------------
| DATABASE
|--------------------------------------------------------------------------
*/

require_once __DIR__ . '/../../../config/dbconn.php';

require_once __DIR__ . '/../../middleware/auth.php';


/*
|--------------------------------------------------------------------------
| AUTH USER
|--------------------------------------------------------------------------
*/

$user =
    $GLOBALS['authUser'] ?? null;

if (!$user) {

    http_response_code(401);

    echo json_encode([
        'status' => false,
        'message' => 'Unauthorized.'
    ]);

    exit;

}


$adminId =
    (int)($user['admin_id'] ?? 0);


if ($adminId <= 0) {

    http_response_code(401);

    echo json_encode([
        'status' => false,
        'message' => 'Invalid admin session.'
    ]);

    exit;

}


/*
|--------------------------------------------------------------------------
| READ JSON
|--------------------------------------------------------------------------
*/

$rawInput =
    file_get_contents('php://input');


$data =
    json_decode(
        $rawInput,
        true
    );


if (
    !is_array($data) ||
    json_last_error() !== JSON_ERROR_NONE
) {

    http_response_code(400);

    echo json_encode([
        'status' => false,
        'message' => 'Invalid JSON payload.'
    ]);

    exit;

}


/*
|--------------------------------------------------------------------------
| CUSTOMER
|--------------------------------------------------------------------------
*/

$customerName =
    trim(
        $data['customer_name'] ?? ''
    );

$customerPhone =
    trim(
        $data['customer_phone'] ?? ''
    );

$customerEmail =
    trim(
        $data['customer_email'] ?? ''
    );

$customerCode =
    trim(
        $data['customer_code'] ?? ''
    );


/*
|--------------------------------------------------------------------------
| PAYMENT
|--------------------------------------------------------------------------
*/

$paymentMethod =
    strtolower(
        trim(
            $data['payment_method'] ?? ''
        )
    );

$paymentStatus =
    strtolower(
        trim(
            $data['payment_status'] ?? 'pending'
        )
    );


$notes =
    trim(
        $data['notes'] ?? ''
    );


/*
|--------------------------------------------------------------------------
| TOTALS
|--------------------------------------------------------------------------
*/

$subtotal =
    (float)(
        $data['subtotal'] ?? 0
    );

$discount =
    (float)(
        $data['discount'] ?? 0
    );

$tax =
    (float)(
        $data['tax'] ?? 0
    );

$shipping =
    (float)(
        $data['shipping'] ?? 0
    );

$totalAmount =
    (float)(
        $data['total_amount'] ?? 0
    );

$amountPaid =
    (float)(
        $data['amount_paid'] ?? 0
    );

$balance =
    (float)(
        $data['balance'] ?? 0
    );


/*
|--------------------------------------------------------------------------
| CART
|--------------------------------------------------------------------------
*/

$items =
    $data['items'] ?? [];


if (!is_array($items)) {

    http_response_code(400);

    echo json_encode([
        'status' => false,
        'message' => 'Invalid cart data.'
    ]);

    exit;

}

/*
|--------------------------------------------------------------------------
| VALIDATE CUSTOMER
|--------------------------------------------------------------------------
*/

if ($customerName === '') {

    http_response_code(400);

    echo json_encode([
        'status' => false,
        'message' => 'Customer name is required.'
    ]);

    exit;

}


if ($customerPhone === '') {

    http_response_code(400);

    echo json_encode([
        'status' => false,
        'message' => 'Customer phone is required.'
    ]);

    exit;

}


/*
|--------------------------------------------------------------------------
| VALIDATE CART
|--------------------------------------------------------------------------
*/

if (count($items) === 0) {

    http_response_code(400);

    echo json_encode([
        'status' => false,
        'message' => 'Cart is empty.'
    ]);

    exit;

}


/*
|--------------------------------------------------------------------------
| DEBUG COMPLETE CART
|--------------------------------------------------------------------------
*/

error_log(
    'FINAL ORDER ITEMS RECEIVED: ' .
    json_encode($items)
    
);

/*
|--------------------------------------------------------------------------
| TRANSACTION
|--------------------------------------------------------------------------
*/

$conn->begin_transaction();


$orderStmt = null;

$itemStmt = null;

$stockStmt = null;

$updateStockStmt = null;


try {


    /*
    |--------------------------------------------------------------------------
    | CREATE ORDER
    |--------------------------------------------------------------------------
    */

$orderNo = 'ORD-' . date('YmdHis') . '-' . random_int(1000, 9999);

$orderStmt = $conn->prepare("
    INSERT INTO orders
    (
        order_no,
        customer_name,
        customer_phone,
        customer_email,
        customer_code,
        payment_method,
        payment_status,
        subtotal,
        discount,
        tax,
        shipping,
        total_amount,
        amount_paid,
        balance,
        notes
    )
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
");


    if (!$orderStmt) {

        throw new Exception(
            'Unable to prepare order statement: ' .
            $conn->error
        );

    }


$orderStmt->bind_param(
    "sssssssddddddds",
    $orderNo,
    $customerName,
    $customerPhone,
    $customerEmail,
    $customerCode,
    $paymentMethod,
    $paymentStatus,
    $subtotal,
    $discount,
    $tax,
    $shipping,
    $totalAmount,
    $amountPaid,
    $balance,
    $notes
);

    if (!$orderStmt->execute()) {

        throw new Exception(
            'Unable to create order: ' .
            $orderStmt->error
        );

    }


    /*
    |--------------------------------------------------------------------------
    | KEEP ORDER ID
    |--------------------------------------------------------------------------
    */

    $orderId =
        $orderStmt->insert_id;


    /*
    |--------------------------------------------------------------------------
    | DO NOT CLOSE HERE
    |--------------------------------------------------------------------------
    |
    | We close statements once in finally.
    |
    */

        /*
    |--------------------------------------------------------------------------
    | PREPARE ORDER ITEM
    |--------------------------------------------------------------------------
    */

    $itemStmt = $conn->prepare("

        INSERT INTO order_items
        (
            order_id,
            store_inventory_id,
            store_id,
            product_id,
            product_name,
            barcode,
            store_name,
            unit_price,
            quantity,
            line_total
        )

        VALUES
        (
            ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
        )

    ");


    if (!$itemStmt) {

        throw new Exception(
            'Unable to prepare order item statement: ' .
            $conn->error
        );

    }


    /*
    |--------------------------------------------------------------------------
    | PREPARE STOCK CHECK
    |--------------------------------------------------------------------------
    */

    $stockStmt = $conn->prepare("

        SELECT
            id,
            quantity

        FROM store_inventory

        WHERE id = ?

        LIMIT 1

    ");


    if (!$stockStmt) {

        throw new Exception(
            'Unable to prepare stock check: ' .
            $conn->error
        );

    }


    /*
    |--------------------------------------------------------------------------
    | PREPARE STOCK UPDATE
    |--------------------------------------------------------------------------
    */

    $updateStockStmt = $conn->prepare("

        UPDATE store_inventory

        SET quantity = quantity - ?

        WHERE id = ?

    ");


    if (!$updateStockStmt) {

        throw new Exception(
            'Unable to prepare stock update: ' .
            $conn->error
        );

    }

        /*
    |--------------------------------------------------------------------------
    | PROCESS CART ITEMS
    |--------------------------------------------------------------------------
    */

    foreach (
        $items as $index => $item
    ) {


        /*
        |--------------------------------------------------------------------------
        | MAKE SURE ITEM IS AN ARRAY
        |--------------------------------------------------------------------------
        */

        if (!is_array($item)) {

            throw new Exception(
                'Invalid cart item ' .
                ($index + 1)
            );

        }


        /*
        |--------------------------------------------------------------------------
        | GET INVENTORY ID
        |--------------------------------------------------------------------------
        */

        $storeInventoryId =
            (int)(
                $item['inventory_id'] ?? 0
            );


        /*
        |--------------------------------------------------------------------------
        | GET STORE ID
        |--------------------------------------------------------------------------
        */

        $storeId =
            (int)(
                $item['store_id'] ?? 0
            );


        /*
        |--------------------------------------------------------------------------
        | GET PRODUCT ID
        |--------------------------------------------------------------------------
        */

        $productId =
            (int)(
                $item['product_id'] ?? 0
            );


        /*
        |--------------------------------------------------------------------------
        | PRODUCT INFORMATION
        |--------------------------------------------------------------------------
        */

        $productName =
            trim(
                $item['product_name'] ??
                $item['name'] ??
                ''
            );


        $barcode =
            trim(
                $item['barcode'] ?? ''
            );


        $storeName =
            trim(
                $item['store_name'] ?? ''
            );


        /*
        |--------------------------------------------------------------------------
        | QUANTITY
        |--------------------------------------------------------------------------
        */

        $quantity =
            (int)(
                $item['quantity'] ?? 0
            );


        /*
        |--------------------------------------------------------------------------
        | PRICE
        |--------------------------------------------------------------------------
        */

        $unitPrice =
            (float)(
                $item['selling_price'] ?? 0
            );


        $lineTotal =
            $unitPrice *
            $quantity;


        /*
        |--------------------------------------------------------------------------
        | DEBUG
        |--------------------------------------------------------------------------
        */

        error_log(
            'ORDER ITEM ' .
            ($index + 1) .
            ': ' .
            json_encode($item)
        );

        error_log(
            'STORE ID: ' .
            $storeId
        );

        error_log(
            'PRODUCT ID: ' .
            $productId
        );


        /*
        |--------------------------------------------------------------------------
        | VALIDATE INVENTORY ID
        |--------------------------------------------------------------------------
        */

        if ($storeInventoryId <= 0) {

            throw new Exception(
                'Invalid inventory ID on cart item ' .
                ($index + 1)
            );

        }


        /*
        |--------------------------------------------------------------------------
        | VALIDATE STORE ID
        |--------------------------------------------------------------------------
        */

        if ($storeId <= 0) {

            throw new Exception(
                'Invalid store ID on cart item ' .
                ($index + 1) .
                '. Received item: ' .
                json_encode($item)
            );

        }


        /*
        |--------------------------------------------------------------------------
        | VALIDATE PRODUCT ID
        |--------------------------------------------------------------------------
        */

        if ($productId <= 0) {

            throw new Exception(
                'Invalid product ID on cart item ' .
                ($index + 1) .
                '. Received item: ' .
                json_encode($item)
            );

        }


        /*
        |--------------------------------------------------------------------------
        | VALIDATE QUANTITY
        |--------------------------------------------------------------------------
        */

        if ($quantity <= 0) {

            throw new Exception(
                'Invalid quantity on cart item ' .
                ($index + 1)
            );

        }


        /*
        |--------------------------------------------------------------------------
        | VALIDATE PRICE
        |--------------------------------------------------------------------------
        */

        if ($unitPrice < 0) {

            throw new Exception(
                'Invalid selling price on cart item ' .
                ($index + 1)
            );

        }


        /*
        |--------------------------------------------------------------------------
        | CHECK INVENTORY
        |--------------------------------------------------------------------------
        */

        $stockStmt->bind_param(
            'i',
            $storeInventoryId
        );


        if (!$stockStmt->execute()) {

            throw new Exception(
                'Unable to check inventory stock: ' .
                $stockStmt->error
            );

        }


        $stockResult =
            $stockStmt->get_result();


        if (
            !$stockResult ||
            $stockResult->num_rows === 0
        ) {

            throw new Exception(
                'Inventory item ' .
                $storeInventoryId .
                ' was not found.'
            );

        }


        $inventory =
            $stockResult->fetch_assoc();


        $availableQty =
            (int)(
                $inventory['quantity'] ?? 0
            );


        /*
        |--------------------------------------------------------------------------
        | CHECK AVAILABLE STOCK
        |--------------------------------------------------------------------------
        */

        if (
            $quantity >
            $availableQty
        ) {

            throw new Exception(

                $productName .
                ' only has ' .
                $availableQty .
                ' item(s) remaining.'

            );

        }


        /*
        |--------------------------------------------------------------------------
        | INSERT ORDER ITEM
        |--------------------------------------------------------------------------
        */

        $itemStmt->bind_param(

            'iiiisssdid',

            $orderId,

            $storeInventoryId,

            $storeId,

            $productId,

            $productName,

            $barcode,

            $storeName,

            $unitPrice,

            $quantity,

            $lineTotal

        );


        if (!$itemStmt->execute()) {

            throw new Exception(

                'Unable to save order item: ' .
                $itemStmt->error

            );

        }


        /*
        |--------------------------------------------------------------------------
        | REDUCE STOCK
        |--------------------------------------------------------------------------
        */

        $updateStockStmt->bind_param(

            'ii',

            $quantity,

            $storeInventoryId

        );


        if (!$updateStockStmt->execute()) {

            throw new Exception(

                'Unable to update inventory: ' .
                $updateStockStmt->error

            );

        }


        /*
        |--------------------------------------------------------------------------
        | CONFIRM STOCK UPDATE
        |--------------------------------------------------------------------------
        */

        if (
            $updateStockStmt->affected_rows === 0
        ) {

            throw new Exception(

                'Inventory was not updated for ' .
                $productName

            );

        }

    }

        /*
    |--------------------------------------------------------------------------
    | COMMIT
    |--------------------------------------------------------------------------
    */

    $conn->commit();


    /*
    |--------------------------------------------------------------------------
    | SUCCESS RESPONSE
    |--------------------------------------------------------------------------
    */

    http_response_code(200);

    echo json_encode([

        'status' => true,

        'message' =>
            'Order created successfully.',

        'data' => [

            'order_id' =>
                $orderId,

            'customer_name' =>
                $customerName,

            'customer_code' =>
                $customerCode,

            'total_amount' =>
                $totalAmount,

            'amount_paid' =>
                $amountPaid,

            'balance' =>
                $balance,

            'items' =>
                count($items)

        ]

    ]);


    } catch (Throwable $e) {


    /*
    |--------------------------------------------------------------------------
    | ROLLBACK
    |--------------------------------------------------------------------------
    */

    if (
        isset($conn) &&
        $conn instanceof mysqli
    ) {

        try {

            $conn->rollback();

        } catch (Throwable $rollbackError) {

            error_log(
                'Rollback error: ' .
                $rollbackError->getMessage()
            );

        }

    }


    /*
    |--------------------------------------------------------------------------
    | LOG ERROR
    |--------------------------------------------------------------------------
    */

    error_log(
        'CREATE ORDER ERROR: ' .
        $e->getMessage()
    );


    /*
    |--------------------------------------------------------------------------
    | JSON ERROR RESPONSE
    |--------------------------------------------------------------------------
    */

    http_response_code(400);

    echo json_encode([

        'status' => false,

        'message' =>
            $e->getMessage()

    ]);

}

finally {


    /*
    |--------------------------------------------------------------------------
    | CLOSE ORDER STATEMENT
    |--------------------------------------------------------------------------
    */

    if (
        isset($orderStmt) &&
        $orderStmt instanceof mysqli_stmt
    ) {

        $orderStmt->close();

    }


    /*
    |--------------------------------------------------------------------------
    | CLOSE ORDER ITEM STATEMENT
    |--------------------------------------------------------------------------
    */

    if (
        isset($itemStmt) &&
        $itemStmt instanceof mysqli_stmt
    ) {

        $itemStmt->close();

    }


    /*
    |--------------------------------------------------------------------------
    | CLOSE STOCK CHECK
    |--------------------------------------------------------------------------
    */

    if (
        isset($stockStmt) &&
        $stockStmt instanceof mysqli_stmt
    ) {

        $stockStmt->close();

    }


    /*
    |--------------------------------------------------------------------------
    | CLOSE STOCK UPDATE
    |--------------------------------------------------------------------------
    */

    if (
        isset($updateStockStmt) &&
        $updateStockStmt instanceof mysqli_stmt
    ) {

        $updateStockStmt->close();

    }


    /*
    |--------------------------------------------------------------------------
    | CLOSE DATABASE
    |--------------------------------------------------------------------------
    */

    if (
        isset($conn) &&
        $conn instanceof mysqli
    ) {

        $conn->close();

    }

}

exit;