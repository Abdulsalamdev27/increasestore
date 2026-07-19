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
*/

$sql = "

SELECT

    o.*,

    s.store_name,

    CONCAT(a.firstName,' ',a.lastName) AS cashier

FROM orders o

LEFT JOIN stores s
ON s.id = o.store_id

LEFT JOIN admins a
ON a.id = o.created_by

WHERE o.id = ?

LIMIT 1

";

$stmt = $conn->prepare($sql);

if (!$stmt) {

    die("Database Error: " . $conn->error);

}

$stmt->bind_param("i", $orderId);

$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows == 0) {

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

    oi.product_id,

    oi.quantity,

    oi.unit_price,

    oi.subtotal,

    p.product_name,

    p.barcode,

    p.sku,

    p.unit,

    p.category

FROM order_items oi

INNER JOIN products p

ON p.id = oi.product_id

WHERE oi.order_id = ?

ORDER BY oi.id ASC

";

$itemStmt = $conn->prepare($itemSql);

if (!$itemStmt) {

    die("Database Error: " . $conn->error);

}

$itemStmt->bind_param("i", $orderId);

$itemStmt->execute();

$itemResult = $itemStmt->get_result();

$orderItems = [];

$totalItems = 0;

$totalQuantity = 0;

while ($row = $itemResult->fetch_assoc()) {

    $orderItems[] = $row;

    $totalItems++;

    $totalQuantity += (int)$row["quantity"];

}

$itemStmt->close();

/*
|--------------------------------------------------------------------------
| HELPER
|--------------------------------------------------------------------------
*/

function money($amount)
{
    return number_format((float)$amount, 2);
}

/*
|--------------------------------------------------------------------------
| COMPANY INFORMATION
|--------------------------------------------------------------------------
|
| Change these to your business details
|
*/

$company = [

    "name" => "Your Company Name",

    "address" => "Company Address",

    "phone" => "+234 XXX XXX XXXX",

    "email" => "info@company.com",

    "website" => "www.company.com"

];

/*
|--------------------------------------------------------------------------
| READY FOR HTML
|--------------------------------------------------------------------------
|
| Variables available:
|
| $order
| $orderItems
| $company
| $totalItems
| $totalQuantity
|
*/
?>