<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

/**
 * ---------------------------------
 * HEADERS
 * ---------------------------------
 */

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: POST, OPTIONS");

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(200);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {

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
 * ---------------------------------
 * AUTH USER
 * ---------------------------------
 */

$user = $GLOBALS["authUser"] ?? null;

if (!$user) {

    http_response_code(401);

    echo json_encode([
        "status" => false,
        "message" => "Unauthorized"
    ]);

    exit;
}

$adminId = (int)$user["id"];

/**
 * ---------------------------------
 * REQUEST BODY
 * ---------------------------------
 */

$data = json_decode(file_get_contents("php://input"), true);

$id = (int)($data["id"] ?? 0);
$quantity = (int)($data["quantity"] ?? 0);
$status = trim($data["status"] ?? "");
$remarks = trim($data["remarks"] ?? "");

/**
 * ---------------------------------
 * VALIDATION
 * ---------------------------------
 */

if ($id <= 0) {

    http_response_code(422);

    echo json_encode([
        "status" => false,
        "message" => "Invalid transfer ID."
    ]);

    exit;
}

if ($quantity <= 0) {

    http_response_code(422);

    echo json_encode([
        "status" => false,
        "message" => "Quantity must be greater than zero."
    ]);

    exit;
}

$allowedStatus = [
    "pending",
    "accepted",
    "rejected"
];

if (!in_array($status, $allowedStatus)) {

    http_response_code(422);

    echo json_encode([
        "status" => false,
        "message" => "Invalid transfer status."
    ]);

    exit;
}

/**
 * ---------------------------------
 * CHECK TRANSFER EXISTS
 * ---------------------------------
 */

$check = $conn->prepare("
SELECT
    id,
    status
FROM product_transfers
WHERE id = ?
LIMIT 1
");

$check->bind_param("i", $id);

$check->execute();

$result = $check->get_result();

if ($result->num_rows === 0) {

    echo json_encode([
        "status" => false,
        "message" => "Transfer not found."
    ]);

    exit;
}

$transfer = $result->fetch_assoc();

$check->close();

/**
 * ---------------------------------
 * UPDATE TRANSFER
 * ---------------------------------
 */

$sql = "

UPDATE product_transfers

SET

    quantity = ?,

    status = ?,

    remarks = ?,

    reviewed_by = ?,

    reviewed_at = NOW()

WHERE id = ?

";

$stmt = $conn->prepare($sql);

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

    "issii",

    $quantity,

    $status,

    $remarks,

    $adminId,

    $id

);

if (!$stmt->execute()) {

    http_response_code(500);

    echo json_encode([
        "status" => false,
        "message" => "Failed to update transfer.",
        "error" => $stmt->error
    ]);

    $stmt->close();
    $conn->close();

    exit;
}

$stmt->close();

$conn->close();

/**
 * ---------------------------------
 * RESPONSE
 * ---------------------------------
 */

echo json_encode([

    "status" => true,

    "message" => "Transfer updated successfully."

]);

exit;