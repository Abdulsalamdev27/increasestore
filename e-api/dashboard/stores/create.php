<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . "/../../../config/dbconn.php";

/**
 * METHOD CHECK
 */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        "status" => false,
        "message" => "Method not allowed"
    ]);
    exit;
}

/**
 * READ JSON
 */
$data = json_decode(file_get_contents("php://input"), true);

if (!is_array($data)) {
    http_response_code(400);
    echo json_encode([
        "status" => false,
        "message" => "Invalid JSON body"
    ]);
    exit;
}

/**
 * SANITIZE
 */
function clean($value)
{
    return trim(htmlspecialchars($value ?? ""));
}

$store_name = clean($data['store_name'] ?? '');
$email      = clean($data['email'] ?? '');
$phone      = clean($data['phone'] ?? '');
$address    = clean($data['address'] ?? '');

/**
 * VALIDATION
 */
if (
    empty($store_name) ||
    empty($email) ||
    empty($phone) ||
    empty($address)
) {
    http_response_code(422);
    echo json_encode([
        "status" => false,
        "message" => "All fields are required."
    ]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        "status" => false,
        "message" => "Invalid email address."
    ]);
    exit;
}

/**
 * CHECK EMAIL
 */
$stmt = $conn->prepare("
    SELECT id
    FROM stores
    WHERE email = ?
");

$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {

    echo json_encode([
        "status" => false,
        "message" => "Store email already exists."
    ]);

    $stmt->close();
    exit;
}

$stmt->close();

/**
 * INSERT STORE
 */
$stmt = $conn->prepare("
    INSERT INTO stores
    (
        store_name,
        email,
        phone,
        address
    )
    VALUES
    (?, ?, ?, ?)
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
    "ssss",
    $store_name,
    $email,
    $phone,
    $address
);

if ($stmt->execute()) {

    echo json_encode([
        "status" => true,
        "message" => "Store created successfully.",
        "store_id" => $stmt->insert_id
    ]);

} else {

    http_response_code(500);

    echo json_encode([
        "status" => false,
        "message" => "Failed to create store.",
        "error" => $stmt->error
    ]);

}

$stmt->close();
$conn->close();
