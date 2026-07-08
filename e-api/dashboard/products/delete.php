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
 * REQUEST
 * -------------------------
 */

$data = json_decode(file_get_contents("php://input"), true);

$id = (int)($data["id"] ?? 0);

if ($id <= 0) {

    echo json_encode([
        "status" => false,
        "message" => "Invalid product ID."
    ]);

    exit;
}

/**
 * -------------------------
 * CHECK PRODUCT
 * -------------------------
 */

$stmt = $conn->prepare("
SELECT id
FROM products
WHERE id=?
LIMIT 1
");

$stmt->bind_param("i", $id);
$stmt->execute();

if ($stmt->get_result()->num_rows == 0) {

    echo json_encode([
        "status" => false,
        "message" => "Product not found."
    ]);

    exit;
}

$stmt->close();

/**
 * -------------------------
 * DELETE
 * -------------------------
 */

$stmt = $conn->prepare("
DELETE FROM products
WHERE id=?
");

$stmt->bind_param("i", $id);

if ($stmt->execute()) {

    echo json_encode([
        "status" => true,
        "message" => "Product deleted successfully."
    ]);

} else {

    echo json_encode([
        "status" => false,
        "message" => "Unable to delete product."
    ]);

}

$stmt->close();
$conn->close();