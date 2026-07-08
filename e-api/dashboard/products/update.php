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
 * REQUEST DATA
 * -------------------------
 */

$data = json_decode(file_get_contents("php://input"), true);

$id = (int)($data["id"] ?? 0);

$product_name = trim($data["product_name"] ?? "");
$barcode = trim($data["barcode"] ?? "");
$sku = trim($data["sku"] ?? "");
$category = trim($data["category"] ?? "");
$description = trim($data["description"] ?? "");
$selling_price = (float)($data["selling_price"] ?? 0);
$cost_price = (float)($data["cost_price"] ?? 0);
$quantity = (int)($data["quantity"] ?? 0);
$minimum_stock = (int)($data["minimum_stock"] ?? 0);
$unit = trim($data["unit"] ?? "pcs");
$status = trim($data["status"] ?? "available");
$is_active = (int)($data["is_active"] ?? 1);

if ($id <= 0 || empty($product_name) || empty($barcode)) {

    echo json_encode([
        "status" => false,
        "message" => "Required fields are missing."
    ]);
    exit;
}

/**
 * -------------------------
 * CHECK BARCODE
 * -------------------------
 */

$stmt = $conn->prepare("
SELECT id
FROM products
WHERE barcode=?
AND id<>?
LIMIT 1
");

$stmt->bind_param("si", $barcode, $id);
$stmt->execute();

if ($stmt->get_result()->num_rows > 0) {

    echo json_encode([
        "status" => false,
        "message" => "Barcode already exists."
    ]);

    exit;
}

$stmt->close();

/**
 * -------------------------
 * UPDATE
 * -------------------------
 */

$stmt = $conn->prepare("
UPDATE products SET

product_name=?,
barcode=?,
sku=?,
category=?,
description=?,
selling_price=?,
cost_price=?,
quantity=?,
minimum_stock=?,
unit=?,
status=?,
is_active=?

WHERE id=?
");

$stmt->bind_param(

    "sssssddiissii",

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
    $is_active,
    $id

);

if ($stmt->execute()) {

    echo json_encode([
        "status" => true,
        "message" => "Product updated successfully."
    ]);

} else {

    echo json_encode([
        "status" => false,
        "message" => "Unable to update product."
    ]);

}

$stmt->close();
$conn->close();