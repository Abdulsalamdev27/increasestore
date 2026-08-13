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

$user = $GLOBALS['authUser'] ?? null;

if (!$user) {

    http_response_code(401);

    echo json_encode([
        'status' => false,
        'message' => 'Unauthorized.'
    ]);

    exit;
}


/*
|--------------------------------------------------------------------------
| ADMIN ID
|--------------------------------------------------------------------------
*/

$adminId = (int)($user['admin_id'] ?? 0);

if ($adminId <= 0) {

    http_response_code(401);

    echo json_encode([
        'status' => false,
        'message' => 'Invalid admin session.'
    ]);

    exit;
}

error_log(
    "ADMIN ID SAVING TO CREATED_BY: " . $adminId
);


/*
|--------------------------------------------------------------------------
| READ JSON
|--------------------------------------------------------------------------
*/

$rawInput = file_get_contents('php://input');

$data = json_decode(
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

$customerName = trim(
    $data['customer_name'] ?? ''
);

$customerPhone = trim(
    $data['customer_phone'] ?? ''
);

$customerEmail = trim(
    $data['customer_email'] ?? ''
);

$customerCode = trim(
    $data['customer_code'] ?? ''
);


/*
|--------------------------------------------------------------------------
| PAYMENT
|--------------------------------------------------------------------------
*/

$paymentMethod = strtolower(
    trim(
        $data['payment_method'] ?? ''
    )
);

$paymentStatus = strtolower(
    trim(
        $data['payment_status'] ?? 'pending'
    )
);

$notes = trim(
    $data['notes'] ?? ''
);


/*
|--------------------------------------------------------------------------
| TOTALS
|--------------------------------------------------------------------------
*/

$subtotal = (float)(
    $data['subtotal'] ?? 0
);

$discount = (float)(
    $data['discount'] ?? 0
);

$tax = (float)(
    $data['tax'] ?? 0
);

$shipping = (float)(
    $data['shipping'] ?? 0
);

$totalAmount = (float)(
    $data['total_amount'] ?? 0
);

$amountPaid = (float)(
    $data['amount_paid'] ?? 0
);

$balance = (float)(
    $data['balance'] ?? 0
);


/*
|--------------------------------------------------------------------------
| CART
|--------------------------------------------------------------------------
*/

$items = $data['items'] ?? [];

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
| DEBUG CART
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
    | CREATE ORDER NUMBER
    |--------------------------------------------------------------------------
    */

    $orderNo =
        'ORD-' .
        date('YmdHis') .
        '-' .
        random_int(1000, 9999);


    /*
    |--------------------------------------------------------------------------
    | CREATE ORDER
    |--------------------------------------------------------------------------
    */

    $orderStmt = $conn->prepare("
        INSERT INTO orders
        (
            order_no,
            created_by,
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
        VALUES
        (
            ?, ?, ?, ?, ?, ?, ?, ?,
            ?, ?, ?, ?, ?, ?, ?, ?
        )
    ");

    if (!$orderStmt) {

        throw new Exception(
            "Unable to prepare order statement: " .
            $conn->error
        );

    }


    /*
    |--------------------------------------------------------------------------
    | BIND ORDER DATA
    |--------------------------------------------------------------------------
    |
    | 16 parameters:
    |
    | 1  order_no       = s
    | 2  created_by     = i
    | 3  customer_name  = s
    | 4  customer_phone = s
    | 5  customer_email = s
    | 6  customer_code  = s
    | 7  payment_method = s
    | 8  payment_status = s
    | 9  subtotal       = d
    | 10 discount       = d
    | 11 tax            = d
    | 12 shipping       = d
    | 13 total_amount   = d
    | 14 amount_paid    = d
    | 15 balance        = d
    | 16 notes          = s
    |
    */

    $orderStmt->bind_param(
        "sissssssddddddds",
        $orderNo,
        $adminId,
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


    /*
    |--------------------------------------------------------------------------
    | EXECUTE ORDER
    |--------------------------------------------------------------------------
    */

    if (!$orderStmt->execute()) {

        throw new Exception(
            "Unable to create order: " .
            $orderStmt->error
        );

    }


    /*
    |--------------------------------------------------------------------------
    | KEEP ORDER ID
    |--------------------------------------------------------------------------
    */

    $orderId = $orderStmt->insert_id;

    error_log(
        "CREATED ORDER ID: " . $orderId
    );


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
            "Unable to prepare order item statement: " .
            $conn->error
        );

    }


    /*
    |--------------------------------------------------------------------------
    | PREPARE INVENTORY CHECK
    |--------------------------------------------------------------------------
    |
    | IMPORTANT:
    |
    | store_id and product_id are retrieved from the database.
    | We do NOT depend on the cart values for these IDs.
    |
    */

    $stockStmt = $conn->prepare("
        SELECT
            id,
            store_id,
            product_id,
            quantity
        FROM store_inventory
        WHERE id = ?
        LIMIT 1
    ");

    if (!$stockStmt) {

        throw new Exception(
            "Unable to prepare stock check: " .
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
          AND quantity >= ?
    ");

    if (!$updateStockStmt) {

        throw new Exception(
            "Unable to prepare stock update: " .
            $conn->error
        );

    }


    /*
    |--------------------------------------------------------------------------
    | STATEMENTS ARE READY
    |--------------------------------------------------------------------------
    */

    error_log(
        "ORDER STATEMENTS PREPARED SUCCESSFULLY."
    );

/*
|--------------------------------------------------------------------------
| PROCESS ORDER ITEMS
|--------------------------------------------------------------------------
*/

foreach ($items as $index => $item) {

    /*
    |--------------------------------------------------------------------------
    | GET CART DATA
    |--------------------------------------------------------------------------
    */

    $storeInventoryId = (int)(
        $item['inventory_id'] ?? 0
    );

    $productName = trim(
        $item['product_name']
        ?? $item['name']
        ?? ''
    );

    $barcode = trim(
        $item['barcode'] ?? ''
    );

    $storeNameFromCart = trim(
        $item['store_name'] ?? ''
    );

    $quantity = (int)(
        $item['quantity'] ?? 0
    );

    $unitPrice = (float)(
        $item['selling_price'] ?? 0
    );


    /*
    |--------------------------------------------------------------------------
    | DEBUG CART ITEM
    |--------------------------------------------------------------------------
    */

    error_log(
        "ORDER ITEM " .
        ($index + 1) .
        ": " .
        json_encode($item)
    );


    /*
    |--------------------------------------------------------------------------
    | VALIDATE INVENTORY ID
    |--------------------------------------------------------------------------
    */

    if ($storeInventoryId <= 0) {

        throw new Exception(
            "Invalid inventory ID on cart item " .
            ($index + 1)
        );

    }


    /*
    |--------------------------------------------------------------------------
    | VALIDATE QUANTITY
    |--------------------------------------------------------------------------
    */

    if ($quantity <= 0) {

        throw new Exception(
            "Invalid quantity on cart item " .
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
            "Invalid selling price on cart item " .
            ($index + 1)
        );

    }


    /*
    |--------------------------------------------------------------------------
    | CHECK INVENTORY
    |--------------------------------------------------------------------------
    |
    | We use inventory_id to get:
    |
    | - store_id
    | - product_id
    | - available quantity
    |
    */

    $stockStmt->bind_param(
        "i",
        $storeInventoryId
    );


    if (!$stockStmt->execute()) {

        throw new Exception(
            "Unable to check inventory stock: " .
            $stockStmt->error
        );

    }


    /*
    |--------------------------------------------------------------------------
    | GET INVENTORY RESULT
    |--------------------------------------------------------------------------
    */

    $stockResult = $stockStmt->get_result();


    if ($stockResult->num_rows === 0) {

        throw new Exception(
            "Inventory item not found on cart item " .
            ($index + 1)
        );

    }


    /*
    |--------------------------------------------------------------------------
    | FETCH INVENTORY
    |--------------------------------------------------------------------------
    */

    $inventory = $stockResult->fetch_assoc();


    /*
    |--------------------------------------------------------------------------
    | GET DATABASE IDs
    |--------------------------------------------------------------------------
    */

    $storeId = (int)(
        $inventory['store_id'] ?? 0
    );

    $productId = (int)(
        $inventory['product_id'] ?? 0
    );

    $availableQty = (int)(
        $inventory['quantity'] ?? 0
    );


    /*
    |--------------------------------------------------------------------------
    | DEBUG DATABASE IDs
    |--------------------------------------------------------------------------
    */

    error_log(
        "INVENTORY ID: " .
        $storeInventoryId
    );

    error_log(
        "DATABASE STORE ID: " .
        $storeId
    );

    error_log(
        "DATABASE PRODUCT ID: " .
        $productId
    );

    error_log(
        "AVAILABLE STOCK: " .
        $availableQty
    );


    /*
    |--------------------------------------------------------------------------
    | VALIDATE DATABASE STORE ID
    |--------------------------------------------------------------------------
    */

    if ($storeId <= 0) {

        throw new Exception(
            "Inventory " .
            $storeInventoryId .
            " has an invalid store ID."
        );

    }


    /*
    |--------------------------------------------------------------------------
    | VALIDATE DATABASE PRODUCT ID
    |--------------------------------------------------------------------------
    */

    if ($productId <= 0) {

        throw new Exception(
            "Inventory " .
            $storeInventoryId .
            " has an invalid product ID."
        );

    }


    /*
    |--------------------------------------------------------------------------
    | VALIDATE STOCK
    |--------------------------------------------------------------------------
    */

    if ($quantity > $availableQty) {

        throw new Exception(

            $productName .
            " only has " .
            $availableQty .
            " item(s) remaining."

        );

    }


    /*
    |--------------------------------------------------------------------------
    | USE DATABASE STORE NAME WHEN AVAILABLE
    |--------------------------------------------------------------------------
    */

    $storeName = $storeNameFromCart;


    /*
    |--------------------------------------------------------------------------
    | LINE TOTAL
    |--------------------------------------------------------------------------
    */

    $lineTotal =
        $unitPrice * $quantity;


    /*
    |--------------------------------------------------------------------------
    | INSERT ORDER ITEM
    |--------------------------------------------------------------------------
    */

    $itemStmt->bind_param(

        "iiiisssdid",

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


    /*
    |--------------------------------------------------------------------------
    | EXECUTE ORDER ITEM
    |--------------------------------------------------------------------------
    */

    if (!$itemStmt->execute()) {

        throw new Exception(

            "Unable to save order item: " .
            $itemStmt->error

        );

    }


    /*
    |--------------------------------------------------------------------------
    | STOCK OUT
    |--------------------------------------------------------------------------
    */

    $updateStockStmt->bind_param(

        "iii",

        $quantity,

        $storeInventoryId,

        $quantity

    );


    /*
    |--------------------------------------------------------------------------
    | EXECUTE STOCK UPDATE
    |--------------------------------------------------------------------------
    */

    if (!$updateStockStmt->execute()) {

        throw new Exception(

            "Unable to update inventory: " .
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

            "Inventory was not updated for " .
            $productName

        );

    }


    /*
    |--------------------------------------------------------------------------
    | LOG SUCCESS
    |--------------------------------------------------------------------------
    */

    error_log(
        "ORDER ITEM SAVED: " .
        $productName .
        " | Inventory: " .
        $storeInventoryId .
        " | Store: " .
        $storeId .
        " | Product: " .
        $productId .
        " | Quantity: " .
        $quantity
    );

}

/*
|--------------------------------------------------------------------------
| COMMIT TRANSACTION
|--------------------------------------------------------------------------
*/

if (!$conn->commit()) {

    throw new Exception(
        "Unable to commit order transaction."
    );

}


/*
|--------------------------------------------------------------------------
| SUCCESS RESPONSE
|--------------------------------------------------------------------------
*/

http_response_code(200);

echo json_encode([

    'status' => true,

    'message' => 'Order created successfully.',

    'data' => [

        'order_id' => $orderId,

        'order_no' => $orderNo,

        'customer_name' => $customerName,

        'customer_code' => $customerCode,

        'total_amount' => $totalAmount,

        'amount_paid' => $amountPaid,

        'balance' => $balance,

        'items' => count($items)

    ]

]);

/*
|--------------------------------------------------------------------------
| SUCCESS RESPONSE SENT
|--------------------------------------------------------------------------
*/

exit;
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

        if ($conn->errno === 0 || $conn->errno > 0) {

            try {

                $conn->rollback();

            } catch (Throwable $rollbackError) {

                error_log(
                    "ROLLBACK ERROR: " .
                    $rollbackError->getMessage()
                );

            }

        }

    }


    /*
    |--------------------------------------------------------------------------
    | LOG ERROR
    |--------------------------------------------------------------------------
    */

    error_log(
        "CREATE ORDER ERROR: " .
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

        'message' => $e->getMessage()

    ]);

    exit;
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

        try {

            $orderStmt->close();

        } catch (Throwable $e) {

            error_log(
                "ORDER STATEMENT CLOSE ERROR: " .
                $e->getMessage()
            );

        }

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

        try {

            $itemStmt->close();

        } catch (Throwable $e) {

            error_log(
                "ORDER ITEM STATEMENT CLOSE ERROR: " .
                $e->getMessage()
            );

        }

    }


    /*
    |--------------------------------------------------------------------------
    | CLOSE STOCK CHECK STATEMENT
    |--------------------------------------------------------------------------
    */

    if (
        isset($stockStmt) &&
        $stockStmt instanceof mysqli_stmt
    ) {

        try {

            $stockStmt->close();

        } catch (Throwable $e) {

            error_log(
                "STOCK STATEMENT CLOSE ERROR: " .
                $e->getMessage()
            );

        }

    }


    /*
    |--------------------------------------------------------------------------
    | CLOSE STOCK UPDATE STATEMENT
    |--------------------------------------------------------------------------
    */

    if (
        isset($updateStockStmt) &&
        $updateStockStmt instanceof mysqli_stmt
    ) {

        try {

            $updateStockStmt->close();

        } catch (Throwable $e) {

            error_log(
                "UPDATE STOCK STATEMENT CLOSE ERROR: " .
                $e->getMessage()
            );

        }

    }


    /*
    |--------------------------------------------------------------------------
    | CLOSE DATABASE CONNECTION
    |--------------------------------------------------------------------------
    */

    if (
        isset($conn) &&
        $conn instanceof mysqli
    ) {

        try {

            $conn->close();

        } catch (Throwable $e) {

            error_log(
                "DATABASE CLOSE ERROR: " .
                $e->getMessage()
            );

        }

    }

}