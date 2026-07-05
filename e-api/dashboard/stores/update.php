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
 * READ JSON
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
 * VALIDATE INPUT
 * -------------------------
 */

$id = isset($data["id"]) ? (int)$data["id"] : 0;

$store_name = trim($data["store_name"] ?? "");
$email      = trim($data["email"] ?? "");
$phone      = trim($data["phone"] ?? "");
$address    = trim($data["address"] ?? "");

if ($id <= 0) {
    http_response_code(400);
    echo json_encode([
        "status" => false,
        "message" => "Store ID is required."
    ]);
    exit;
}

if ($store_name === "") {
    http_response_code(400);
    echo json_encode([
        "status" => false,
        "message" => "Store name is required."
    ]);
    exit;
}

/**
 * -------------------------
 * CHECK STORE EXISTS
 * -------------------------
 */

$check = $conn->prepare("
    SELECT id
    FROM stores
    WHERE id = ?
    LIMIT 1
");

$check->bind_param("i", $id);
$check->execute();

$result = $check->get_result();

if ($result->num_rows === 0) {

    http_response_code(404);

    echo json_encode([
        "status" => false,
        "message" => "Store not found."
    ]);

    $check->close();
    exit;
}

$check->close();

/**
 * -------------------------
 * UPDATE STORE
 * -------------------------
 */

$stmt = $conn->prepare("
    UPDATE stores
    SET
        store_name = ?,
        email = ?,
        phone = ?,
        address = ?,
        updated_at = CURRENT_TIMESTAMP
    WHERE id = ?
");

if (!$stmt) {

    http_response_code(500);

    echo json_encode([
        "status" => false,
        "message" => "Database prepare failed.",
        "error" => $conn->error
    ]);

    exit;
}

$stmt->bind_param(
    "ssssi",
    $store_name,
    $email,
    $phone,
    $address,
    $id
);

if ($stmt->execute()) {

    echo json_encode([
        "status" => true,
        "message" => "Store updated successfully."
    ]);

} else {

    http_response_code(500);

    echo json_encode([
        "status" => false,
        "message" => "Failed to update store."
    ]);
}

$stmt->close();
$conn->close();
exit;