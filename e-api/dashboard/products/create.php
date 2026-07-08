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
header("Access-Control-Allow-Methods: POST, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
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
 * READ JSON INPUT
 * -------------------------
 */

$data = json_decode(file_get_contents("php://input"), true);

if (!is_array($data)) {
    http_response_code(400);
    echo json_encode([
        "status" => false,
        "message" => "Invalid JSON payload"
    ]);
    exit;
}

/**
 * -------------------------
 * GET INPUT
 * -------------------------
 */

$product_name   = trim($data["product_name"] ?? "");
$barcode        = trim($data["barcode"] ?? "");
$sku            = trim($data["sku"] ?? "");
$category       = trim($data["category"] ?? "");
$description    = trim($data["description"] ?? "");
$selling_price  = (float)($data["selling_price"] ?? 0);
$cost_price     = (float)($data["cost_price"] ?? 0);
$quantity       = (int)($data["quantity"] ?? 0);
$minimum_stock  = (int)($data["minimum_stock"] ?? 0);
$unit           = trim($data["unit"] ?? "pcs");
$status         = trim($data["status"] ?? "available");
$is_active      = isset($data["is_active"]) ? (int)$data["is_active"] : 1;

/**
 * -------------------------
 * VALIDATION
 * -------------------------
 */

if (
    empty($product_name) ||
    empty($barcode) ||
    $selling_price <= 0
) {
    http_response_code(400);

    echo json_encode([
        "status" => false,
        "message" => "Product Name, Barcode and Selling Price are required."
    ]);

    exit;
}

/**
 * -------------------------
 * CHECK BARCODE
 * -------------------------
 */

$check = $conn->prepare("
    SELECT id
    FROM products
    WHERE barcode = ?
    LIMIT 1
");

if (!$check) {

    http_response_code(500);

    echo json_encode([
        "status" => false,
        "message" => "Database prepare failed",
        "error" => $conn->error
    ]);

    exit;
}

$check->bind_param("s", $barcode);
$check->execute();

if ($check->get_result()->num_rows > 0) {

    $check->close();

    http_response_code(409);

    echo json_encode([
        "status" => false,
        "message" => "Barcode already exists."
    ]);

    exit;
}

$check->close();

/**
 * -------------------------
 * CHECK SKU
 * -------------------------
 */

if (!empty($sku)) {

    $skuCheck = $conn->prepare("
        SELECT id
        FROM products
        WHERE sku = ?
        LIMIT 1
    ");

    $skuCheck->bind_param("s", $sku);
    $skuCheck->execute();

    if ($skuCheck->get_result()->num_rows > 0) {

        $skuCheck->close();

        http_response_code(409);

        echo json_encode([
            "status" => false,
            "message" => "SKU already exists."
        ]);

        exit;
    }

    $skuCheck->close();
}

/**
 * -------------------------
 * CREATE PRODUCT
 * -------------------------
 */

$stmt = $conn->prepare("
    INSERT INTO products
    (
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
        is_active
    )
    VALUES
    (
        ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
    )
");

if (!$stmt) {

    http_response_code(500);

    echo json_encode([
        "status" => false,
        "message" => "Database prepare failed",
        "error" => $conn->error
    ]);

    exit;
}

$stmt->bind_param(
    "sssssddiissi",
    $product_name,
    $barcode,
    $sku,
    $category,
    $description,
    $selling_price,
    $cost_price,
    $quantity,
    $minimum_stock,
    $unit,
    $status,
    $is_active
);

if ($stmt->execute()) {

    echo json_encode([
        "status" => true,
        "message" => "Product created successfully.",
        "product_id" => $stmt->insert_id
    ]);

} else {

    http_response_code(500);

    echo json_encode([
        "status" => false,
        "message" => "Unable to create product."
    ]);
}

$stmt->close();
$conn->close();

exit;