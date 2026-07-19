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
| STORE FILTER (OPTIONAL)
|--------------------------------------------------------------------------
|
| Example:
| accepted-products.php?store_id=1
|
*/

$storeId = isset($_GET['store_id'])
    ? (int) $_GET['store_id']
    : 0;

/*
|--------------------------------------------------------------------------
| SQL
|--------------------------------------------------------------------------
*/

$sql = "

SELECT

    si.id AS inventory_id,

    si.store_id,

    s.store_name,

    si.product_id,

    si.quantity,

    p.product_name,

    p.barcode,

    p.sku,

    p.category,

    p.description,

    p.selling_price,

    p.cost_price,

    p.minimum_stock,

    p.unit,

    p.status,

    p.created_at,

    p.updated_at

FROM store_inventory si

INNER JOIN products p
ON p.id = si.product_id

INNER JOIN stores s
ON s.id = si.store_id

WHERE

    si.quantity > 0

    AND p.is_active = 1

";

if ($storeId > 0) {

    $sql .= " AND si.store_id = ? ";

}

$sql .= "

ORDER BY

p.product_name ASC

";

/*
|--------------------------------------------------------------------------
| PREPARE
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

if ($storeId > 0) {

    $stmt->bind_param("i", $storeId);

}

$stmt->execute();

$result = $stmt->get_result();

$data = [];

while ($row = $result->fetch_assoc()) {

    $data[] = [

        "inventory_id" => (int)$row["inventory_id"],

        "store_id" => (int)$row["store_id"],

        "store_name" => $row["store_name"],

        "product_id" => (int)$row["product_id"],

        "product_name" => $row["product_name"],

        "barcode" => $row["barcode"],

        "sku" => $row["sku"],

        "category" => $row["category"],

        "description" => $row["description"],

        "selling_price" => (float)$row["selling_price"],

        "cost_price" => (float)$row["cost_price"],

        "quantity" => (int)$row["quantity"],

        "minimum_stock" => (int)$row["minimum_stock"],

        "unit" => $row["unit"],

        "status" => $row["status"],

        "created_at" => $row["created_at"],

        "updated_at" => $row["updated_at"]

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

    "message" => "Accepted products fetched successfully.",

    "total" => count($data),

    "data" => $data

]);

exit;