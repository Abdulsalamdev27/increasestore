<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

/*
|--------------------------------------------------------------------------
| HEADERS
|--------------------------------------------------------------------------
*/

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: POST, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {

    http_response_code(200);
    exit;

}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    http_response_code(405);

    echo json_encode([
        "status" => false,
        "message" => "Method Not Allowed"
    ]);

    exit;

}

/*
|--------------------------------------------------------------------------
| DATABASE
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/../../../config/dbconn.php";
require_once __DIR__ . "/../../middleware/auth.php";

/*
|--------------------------------------------------------------------------
| AUTHENTICATION
|--------------------------------------------------------------------------
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

/*
|--------------------------------------------------------------------------
| CURRENT ADMIN
|--------------------------------------------------------------------------
*/

$adminId = (int)$user['admin_id'];
/*
|--------------------------------------------------------------------------
| READ JSON
|--------------------------------------------------------------------------
*/

$payload = json_decode(file_get_contents("php://input"), true);

if (!$payload) {

    http_response_code(400);

    echo json_encode([
        "status" => false,
        "message" => "Invalid JSON payload."
    ]);

    exit;

}

/*
|--------------------------------------------------------------------------
| CUSTOMER INFORMATION
|--------------------------------------------------------------------------
*/

$customerName = trim($payload['customer_name'] ?? '');

$customerPhone = trim($payload['customer_phone'] ?? '');

$customerEmail = trim($payload['customer_email'] ?? '');

$paymentMethod = trim($payload['payment_method'] ?? '');

$notes = trim($payload['notes'] ?? '');

/*
|--------------------------------------------------------------------------
| ORDER TOTALS
|--------------------------------------------------------------------------
*/

$subtotal = (float)($payload['subtotal'] ?? 0);

$discount = (float)($payload['discount'] ?? 0);

$tax = (float)($payload['tax'] ?? 0);

$shipping = (float)($payload['shipping'] ?? 0);

$totalAmount = (float)($payload['total_amount'] ?? 0);

$amountPaid = (float)($payload['amount_paid'] ?? 0);

$balance = (float)($payload['balance'] ?? 0);

/*
|--------------------------------------------------------------------------
| ORDER ITEMS
|--------------------------------------------------------------------------
*/

$items = $payload['items'] ?? [];

/*
|--------------------------------------------------------------------------
| VALIDATION
|--------------------------------------------------------------------------
*/

if ($customerName === '') {

    echo json_encode([
        "status" => false,
        "message" => "Customer name is required."
    ]);

    exit;

}

if ($customerPhone === '') {

    echo json_encode([
        "status" => false,
        "message" => "Customer phone is required."
    ]);

    exit;

}

if ($paymentMethod === '') {

    echo json_encode([
        "status" => false,
        "message" => "Payment method is required."
    ]);

    exit;

}

if (!is_array($items) || count($items) === 0) {

    echo json_encode([
        "status" => false,
        "message" => "Order cart is empty."
    ]);

    exit;

}

if ($subtotal <= 0) {

    echo json_encode([
        "status" => false,
        "message" => "Invalid subtotal."
    ]);

    exit;

}

if ($totalAmount <= 0) {

    echo json_encode([
        "status" => false,
        "message" => "Invalid total amount."
    ]);

    exit;

}

/*
|--------------------------------------------------------------------------
| VALIDATE ITEMS
|--------------------------------------------------------------------------
*/

foreach ($items as $index => $item) {

    if (!isset($item['product_id'])) {

        echo json_encode([
            "status" => false,
            "message" => "Missing product ID on item " . ($index + 1)
        ]);

        exit;

    }

    if (!isset($item['quantity'])) {

        echo json_encode([
            "status" => false,
            "message" => "Missing quantity on item " . ($index + 1)
        ]);

        exit;

    }

    if (!isset($item['selling_price'])) {

        echo json_encode([
            "status" => false,
            "message" => "Missing selling price on item " . ($index + 1)
        ]);

        exit;

    }

    $productId = (int)$item['product_id'];

    $quantity = (int)$item['quantity'];

    $price = (float)$item['selling_price'];

    if ($productId <= 0) {

        echo json_encode([
            "status" => false,
            "message" => "Invalid product ID."
        ]);

        exit;

    }

    if ($quantity <= 0) {

        echo json_encode([
            "status" => false,
            "message" => "Invalid quantity."
        ]);

        exit;

    }

    if ($price < 0) {

        echo json_encode([
            "status" => false,
            "message" => "Invalid selling price."
        ]);

        exit;

    }

}

/*
|--------------------------------------------------------------------------
| BEGIN DATABASE TRANSACTION
|--------------------------------------------------------------------------
*/

$conn->begin_transaction();

try {

    /*
    |--------------------------------------------------------------------------
    | Generate Order Number
    |--------------------------------------------------------------------------
    */

    $orderNo = "ORD-" . date("YmdHis") . "-" . mt_rand(1000, 9999);

    /*
    |--------------------------------------------------------------------------
    | Create Order
    |--------------------------------------------------------------------------
    */

    $orderSql = "
        INSERT INTO orders
        (
            order_no,
            customer_name,
            customer_phone,
            customer_email,
            payment_method,
            subtotal,
            discount,
            tax,
            shipping,
            total_amount,
            amount_paid,
            balance,
            notes,
            created_by
        )
        VALUES
        (
            ?,?,?,?,?,?,?,?,?,?,?,?,?,?
        )
    ";

    $orderStmt = $conn->prepare($orderSql);

    if (!$orderStmt) {

        throw new Exception(
            "Unable to prepare order statement: " .
            $conn->error
        );

    }

    $orderStmt->bind_param(

        "sssssdddddddsi",

        $orderNo,

        $customerName,

        $customerPhone,

        $customerEmail,

        $paymentMethod,

        $subtotal,

        $discount,

        $tax,

        $shipping,

        $totalAmount,

        $amountPaid,

        $balance,

        $notes,

        $adminId

    );

    if (!$orderStmt->execute()) {

        throw new Exception(
            "Unable to create order: " .
            $orderStmt->error
        );

    }

    /*
    |--------------------------------------------------------------------------
    | New Order ID
    |--------------------------------------------------------------------------
    */

    $orderId = $conn->insert_id;

    if (!$orderId) {

        throw new Exception("Failed to obtain Order ID.");

    }

    $orderStmt->close();

    }
catch (Exception $e) {

    $conn->rollback();

    http_response_code(500);

    echo json_encode([
        "status" => false,
        "message" => $e->getMessage()
    ]);

    exit;

}