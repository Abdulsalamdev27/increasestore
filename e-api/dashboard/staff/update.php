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

$id         = (int)($data["id"] ?? 0);
$store_id   = (int)($data["store_id"] ?? 0);
$first_name = trim($data["first_name"] ?? "");
$last_name  = trim($data["last_name"] ?? "");
$email      = trim($data["email"] ?? "");
$phone      = trim($data["phone"] ?? "");
$position   = trim($data["position"] ?? "");
$is_active  = isset($data["is_active"]) ? (int)$data["is_active"] : 1;

if (
    $id <= 0 ||
    $store_id <= 0 ||
    empty($first_name) ||
    empty($last_name)
) {
    http_response_code(400);
    echo json_encode([
        "status" => false,
        "message" => "Staff ID, Store, First Name and Last Name are required."
    ]);
    exit;
}

/**
 * -------------------------
 * CHECK STAFF EXISTS
 * -------------------------
 */

$check = $conn->prepare("
    SELECT id
    FROM staffs
    WHERE id = ?
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

$check->bind_param("i", $id);
$check->execute();

if ($check->get_result()->num_rows === 0) {

    $check->close();

    http_response_code(404);
    echo json_encode([
        "status" => false,
        "message" => "Staff not found"
    ]);
    exit;
}

$check->close();

/**
 * -------------------------
 * CHECK STORE EXISTS
 * -------------------------
 */

$storeCheck = $conn->prepare("
    SELECT id
    FROM stores
    WHERE id = ?
    LIMIT 1
");

$storeCheck->bind_param("i", $store_id);
$storeCheck->execute();

if ($storeCheck->get_result()->num_rows === 0) {

    $storeCheck->close();

    http_response_code(404);
    echo json_encode([
        "status" => false,
        "message" => "Store not found"
    ]);
    exit;
}

$storeCheck->close();

/**
 * -------------------------
 * CHECK EMAIL DUPLICATE
 * -------------------------
 */

if (!empty($email)) {

    $emailCheck = $conn->prepare("
        SELECT id
        FROM staffs
        WHERE email = ?
        AND id != ?
        LIMIT 1
    ");

    $emailCheck->bind_param("si", $email, $id);
    $emailCheck->execute();

    if ($emailCheck->get_result()->num_rows > 0) {

        $emailCheck->close();

        http_response_code(409);
        echo json_encode([
            "status" => false,
            "message" => "Email already exists."
        ]);
        exit;
    }

    $emailCheck->close();
}

/**
 * -------------------------
 * UPDATE STAFF
 * -------------------------
 */

$stmt = $conn->prepare("
    UPDATE staffs
    SET
        store_id = ?,
        first_name = ?,
        last_name = ?,
        email = ?,
        phone = ?,
        position = ?,
        is_active = ?
    WHERE id = ?
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
    "isssssii",
    $store_id,
    $first_name,
    $last_name,
    $email,
    $phone,
    $position,
    $is_active,
    $id
);

if ($stmt->execute()) {

    echo json_encode([
        "status" => true,
        "message" => "Staff updated successfully."
    ]);

} else {

    http_response_code(500);

    echo json_encode([
        "status" => false,
        "message" => "Unable to update staff."
    ]);
}

$stmt->close();
$conn->close();

exit;