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

if ($id <= 0) {

    http_response_code(422);

    echo json_encode([
        "status" => false,
        "message" => "Invalid transfer ID."
    ]);

    exit;
}

/**
 * -------------------------
 * CHECK TRANSFER EXISTS
 * -------------------------
 */

$check = $conn->prepare("
SELECT id
FROM product_transfers
WHERE id = ?
LIMIT 1
");

if (!$check) {

    http_response_code(500);

    echo json_encode([
        "status" => false,
        "message" => "Database prepare failed.",
        "error" => $conn->error
    ]);

    exit;
}

$check->bind_param("i", $id);

$check->execute();

$result = $check->get_result();

if ($result->num_rows === 0) {

    $check->close();
    $conn->close();

    echo json_encode([
        "status" => false,
        "message" => "Transfer not found."
    ]);

    exit;
}

$check->close();

/**
 * -------------------------
 * DELETE TRANSFER
 * -------------------------
 */

$stmt = $conn->prepare("
DELETE FROM product_transfers
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

$stmt->bind_param("i", $id);

if (!$stmt->execute()) {

    http_response_code(500);

    echo json_encode([
        "status" => false,
        "message" => "Failed to delete transfer.",
        "error" => $stmt->error
    ]);

    $stmt->close();
    $conn->close();

    exit;
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
    "message" => "Transfer deleted successfully."
]);

exit;