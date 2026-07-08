<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

/**
 * -------------------------
 * HEADERS
 * -------------------------
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
        "message" => "Method not allowed"
    ]);
    exit;
}

require_once __DIR__ . "/../../../config/dbconn.php";
require_once __DIR__ . "/../../middleware/auth.php";

/**
 * -------------------------
 * AUTH USER
 * -------------------------
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
 * -------------------------
 * FETCH PRODUCTS
 * -------------------------
 */

$sql = "
SELECT
    id,
    product_name,
    barcode,
    sku,
    category,
    description,
    selling_price,
    cost_price,
    quantity,
    minimum_stock,
    unit,
    status,
    is_active,
    created_at,
    updated_at
FROM products
ORDER BY created_at DESC
";

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

$stmt->execute();

$result = $stmt->get_result();

$products = [];

while ($row = $result->fetch_assoc()) {

    $products[] = [
        "id" => (int)$row["id"],
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
        "is_active" => (int)$row["is_active"],
        "created_at" => $row["created_at"],
        "updated_at" => $row["updated_at"]
    ];
}

$stmt->close();
$conn->close();

/**
 * -------------------------
 * RESPONSE
 * -------------------------
 */

echo json_encode([
    "status" => true,
    "data" => $products
]);

exit;