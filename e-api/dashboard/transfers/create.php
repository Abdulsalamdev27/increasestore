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

$admin_id = (int)$user["admin_id"];

/**
 * -------------------------
 * READ JSON
 * -------------------------
 */

$data = json_decode(file_get_contents("php://input"), true);

if (!is_array($data)) {

    http_response_code(400);

    echo json_encode([
        "status" => false,
        "message" => "Invalid JSON payload."
    ]);

    exit;

}

$product_id = (int)($data["product_id"] ?? 0);
$store_id = (int)($data["store_id"] ?? 0);
$quantity = (int)($data["quantity"] ?? 0);

$reference_no = trim($data["reference_no"] ?? "");
$remarks = trim($data["remarks"] ?? "");

/**
 * -------------------------
 * VALIDATION
 * -------------------------
 */

if ($product_id <= 0) {

    echo json_encode([
        "status" => false,
        "message" => "Please select a product."
    ]);

    exit;

}

if ($store_id <= 0) {

    echo json_encode([
        "status" => false,
        "message" => "Please select a store."
    ]);

    exit;

}

if ($quantity <= 0) {

    echo json_encode([
        "status" => false,
        "message" => "Quantity must be greater than zero."
    ]);

    exit;

}

/**
 * -------------------------
 * CHECK PRODUCT
 * -------------------------
 */

$stmt = $conn->prepare("
SELECT
    id,
    product_name,
    quantity
FROM products
WHERE id = ?
LIMIT 1
");

$stmt->bind_param("i", $product_id);
$stmt->execute();

$product = $stmt->get_result()->fetch_assoc();

$stmt->close();

if (!$product) {

    echo json_encode([
        "status" => false,
        "message" => "Product not found."
    ]);

    exit;

}

if ($product["quantity"] < $quantity) {

    echo json_encode([
        "status" => false,
        "message" => "Insufficient stock available."
    ]);

    exit;

}

/**
 * -------------------------
 * CHECK STORE
 * -------------------------
 */

$stmt = $conn->prepare("
SELECT id
FROM stores
WHERE id = ?
LIMIT 1
");

$stmt->bind_param("i", $store_id);
$stmt->execute();

$store = $stmt->get_result()->fetch_assoc();

$stmt->close();

if (!$store) {

    echo json_encode([
        "status" => false,
        "message" => "Store not found."
    ]);

    exit;

}

/**
 * -------------------------
 * GENERATE REFERENCE
 * -------------------------
 */

if ($reference_no == "") {

    $reference_no =
        "TRF-" .
        date("YmdHis") .
        "-" .
        rand(1000, 9999);

}

/**
 * -------------------------
 * CREATE TRANSFER
 * -------------------------
 */

$stmt = $conn->prepare("
INSERT INTO product_transfers
(
    product_id,
    store_id,
    quantity,
    movement_type,
    status,
    reference_no,
    remarks,
    sent_by
)
VALUES
(
    ?,
    ?,
    ?,
    'send',
    'pending',
    ?,
    ?,
    ?
)
");

$stmt->bind_param(
    "iiissi",
    $product_id,
    $store_id,
    $quantity,
    $reference_no,
    $remarks,
    $admin_id
);

if (!$stmt->execute()) {

    echo json_encode([
        "status" => false,
        "message" => "Unable to create transfer.",
        "error" => $stmt->error
    ]);

    exit;

}

$transfer_id = $stmt->insert_id;

$stmt->close();

/**
 * -------------------------
 * RESPONSE
 * -------------------------
 */

echo json_encode([
    "status" => true,
    "message" => "Product transfer created successfully. Waiting for store approval.",
    "transfer_id" => $transfer_id,
    "reference_no" => $reference_no
]);

$conn->close();

exit;