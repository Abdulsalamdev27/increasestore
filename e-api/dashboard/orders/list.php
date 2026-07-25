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
header("Access-Control-Allow-Methods: GET, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {

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
| FILTERS
|--------------------------------------------------------------------------
*/

$search = trim($_GET['search'] ?? '');

$storeId = isset($_GET['store_id']) ? (int)$_GET['store_id'] : 0;

$paymentMethod = trim($_GET['payment_method'] ?? '');

$paymentStatus = trim($_GET['payment_status'] ?? '');

/*
|--------------------------------------------------------------------------
| SQL
|--------------------------------------------------------------------------
*/

$sql = "

SELECT

    o.id,

    o.store_id,

    s.store_name,

    o.order_number,

    o.customer_name,

    o.customer_phone,

    o.customer_email,

    o.total,

    o.payment_method,

    o.payment_status,

    o.created_by,

    CONCAT(a.firstName,' ',a.lastName) AS created_by_name,

    o.created_at

FROM orders o

LEFT JOIN stores s
ON o.store_id = s.id

LEFT JOIN admins a
ON o.created_by = a.id

WHERE 1=1

";

$params = [];
$types = "";

/*
|--------------------------------------------------------------------------
| Search
|--------------------------------------------------------------------------
*/

if ($search !== '') {

    $sql .= "

    AND (

        o.order_number LIKE ?

        OR o.customer_name LIKE ?

        OR o.customer_phone LIKE ?

        OR o.customer_email LIKE ?

    )

    ";

    $keyword = "%{$search}%";

    $params[] = $keyword;
    $params[] = $keyword;
    $params[] = $keyword;
    $params[] = $keyword;

    $types .= "ssss";

}

/*
|--------------------------------------------------------------------------
| Store Filter
|--------------------------------------------------------------------------
*/

if ($storeId > 0) {

    $sql .= " AND o.store_id = ? ";

    $params[] = $storeId;

    $types .= "i";

}

/*
|--------------------------------------------------------------------------
| Payment Method
|--------------------------------------------------------------------------
*/

if ($paymentMethod !== '') {

    $sql .= " AND o.payment_method = ? ";

    $params[] = $paymentMethod;

    $types .= "s";

}

/*
|--------------------------------------------------------------------------
| Payment Status
|--------------------------------------------------------------------------
*/

if ($paymentStatus !== '') {

    $sql .= " AND o.payment_status = ? ";

    $params[] = $paymentStatus;

    $types .= "s";

}

/*
|--------------------------------------------------------------------------
| Order
|--------------------------------------------------------------------------
*/

$sql .= "

ORDER BY

o.created_at DESC

";

/*
|--------------------------------------------------------------------------
| Prepare
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare($sql);

if (!$stmt) {

    http_response_code(500);

    echo json_encode([

        "status" => false,

        "message" => "Database prepare failed",

        "error" => $conn->error

    ]);

    exit;

}

if (!empty($params)) {

    $stmt->bind_param($types, ...$params);

}

$stmt->execute();

$result = $stmt->get_result();

$orders = [];

while ($row = $result->fetch_assoc()) {

    $orders[] = [

        "id" => (int)$row["id"],

        "store_id" => (int)$row["store_id"],

        "store_name" => $row["store_name"],

        "order_number" => $row["order_number"],

        "customer_name" => $row["customer_name"],

        "customer_phone" => $row["customer_phone"],

        "customer_email" => $row["customer_email"],

        "total" => (float)$row["total"],

        "payment_method" => $row["payment_method"],

        "payment_status" => $row["payment_status"],

        "created_by" => (int)$row["created_by"],

        "created_by_name" => $row["created_by_name"],

        "created_at" => $row["created_at"]

    ];

}

$stmt->close();

$conn->close();

/*
|--------------------------------------------------------------------------
| RESPONSE
|--------------------------------------------------------------------------
*/

echo json_encode([

    "status" => true,

    "message" => "Orders fetched successfully.",

    "total" => count($orders),

    "data" => $orders

]);

exit;