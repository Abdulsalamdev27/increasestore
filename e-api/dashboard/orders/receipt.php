<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);


/*
|--------------------------------------------------------------------------
| DATABASE
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/../../config/dbconn.php";
require_once __DIR__ . "/../middleware/auth.php";


/*
|--------------------------------------------------------------------------
| AUTHENTICATION
|--------------------------------------------------------------------------
*/

$user = $GLOBALS['authUser'] ?? null;

if (!$user) {

    die("Unauthorized");

}


/*
|--------------------------------------------------------------------------
| ORDER ID
|--------------------------------------------------------------------------
*/

$orderId = isset($_GET['id'])
    ? (int)$_GET['id']
    : 0;

if ($orderId <= 0) {

    die("Invalid Order ID.");

}


/*
|--------------------------------------------------------------------------
| FETCH ORDER
|--------------------------------------------------------------------------
|
| IMPORTANT:
| Do not use o.store_id here if your orders table does not
| contain store_id.
|
| The store information is stored on order_items.
|
*/

$sql = "

SELECT

    o.id,

    o.order_no,

    o.created_by,

    o.customer_name,

    o.customer_phone,

    o.customer_email,

    o.customer_code,

    o.payment_method,

    o.payment_status,

    o.subtotal,

    o.discount,

    o.tax,

    o.shipping,

    o.total_amount,

    o.amount_paid,

    o.balance,

    o.notes,

    o.created_at,

    CONCAT(
        a.firstName,
        ' ',
        a.lastName
    ) AS cashier

FROM orders o

LEFT JOIN admins a
    ON a.id = o.created_by

WHERE o.id = ?

LIMIT 1

";


$stmt = $conn->prepare($sql);

if (!$stmt) {

    die(
        "Database Error: " .
        $conn->error
    );

}


$stmt->bind_param(
    "i",
    $orderId
);


if (!$stmt->execute()) {

    die(
        "Unable to fetch order: " .
        $stmt->error
    );

}


$result = $stmt->get_result();


if ($result->num_rows === 0) {

    die("Order not found.");

}


$order = $result->fetch_assoc();


$stmt->close();

/*
|--------------------------------------------------------------------------
| FETCH ORDER ITEMS
|--------------------------------------------------------------------------
*/

$itemSql = "

SELECT

    oi.id,

    oi.store_inventory_id,

    oi.store_id,

    oi.product_id,

    oi.product_name,

    oi.barcode,

    oi.store_name,

    oi.unit_price,

    oi.quantity,

    oi.line_total

FROM order_items oi

WHERE oi.order_id = ?

ORDER BY oi.id ASC

";


$itemStmt = $conn->prepare($itemSql);

if (!$itemStmt) {

    die(
        "Database Error: " .
        $conn->error
    );

}


$itemStmt->bind_param(
    "i",
    $orderId
);


if (!$itemStmt->execute()) {

    die(
        "Unable to fetch order items: " .
        $itemStmt->error
    );

}


$itemResult = $itemStmt->get_result();


$orderItems = [];

$totalItems = 0;

$totalQuantity = 0;


while (
    $row = $itemResult->fetch_assoc()
) {

    $orderItems[] = $row;

    $totalItems++;

    $totalQuantity +=
        (int)$row["quantity"];

}


$itemStmt->close();

/*
|--------------------------------------------------------------------------
| HELPER
|--------------------------------------------------------------------------
*/

function money($amount)
{
    return number_format(
        (float)$amount,
        2
    );
}


/*
|--------------------------------------------------------------------------
| COMPANY INFORMATION
|--------------------------------------------------------------------------
*/

$company = [

    "name" =>
        "Your Company Name",

    "address" =>
        "Company Address",

    "phone" =>
        "+234 XXX XXX XXXX",

    "email" =>
        "info@company.com",

    "website" =>
        "www.company.com"

];


/*
|--------------------------------------------------------------------------
| RECEIPT VARIABLES
|--------------------------------------------------------------------------
*/

$receiptNumber =
    $order["order_no"] ?? "";


$cashier =
    trim(
        $order["cashier"] ?? ""
    );


$customerName =
    $order["customer_name"] ?? "";


$customerPhone =
    $order["customer_phone"] ?? "";


$customerEmail =
    $order["customer_email"] ?? "";


$paymentMethod =
    $order["payment_method"] ?? "";


$paymentStatus =
    $order["payment_status"] ?? "";


$totalAmount =
    (float)(
        $order["total_amount"] ?? 0
    );


$amountPaid =
    (float)(
        $order["amount_paid"] ?? 0
    );


$balance =
    (float)(
        $order["balance"] ?? 0
    );

/*
|--------------------------------------------------------------------------
| STORE INFORMATION
|--------------------------------------------------------------------------
*/

$storeNames = [];


foreach ($orderItems as $item) {

    $storeName =
        trim(
            $item["store_name"] ?? ""
        );

    if (
        $storeName !== "" &&
        !in_array(
            $storeName,
            $storeNames,
            true
        )
    ) {

        $storeNames[] =
            $storeName;

    }

}


$receiptStoreName =
    implode(
        ", ",
        $storeNames
    );