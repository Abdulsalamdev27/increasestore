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
 * INPUTS
 * -------------------------
 */

$store_id   = (int)($data["store_id"] ?? 0);
$first_name = trim($data["first_name"] ?? "");
$last_name  = trim($data["last_name"] ?? "");
$email      = trim($data["email"] ?? "");
$phone      = trim($data["phone"] ?? "");
$position   = trim($data["position"] ?? "");
$is_active  = isset($data["is_active"]) ? (int)$data["is_active"] : 1;

/**
 * -------------------------
 * VALIDATION
 * -------------------------
 */

if ($store_id <= 0) {
    http_response_code(400);
    echo json_encode([
        "status" => false,
        "message" => "Please select a store."
    ]);
    exit;
}

if ($first_name === "") {
    http_response_code(400);
    echo json_encode([
        "status" => false,
        "message" => "First name is required."
    ]);
    exit;
}

if ($last_name === "") {
    http_response_code(400);
    echo json_encode([
        "status" => false,
        "message" => "Last name is required."
    ]);
    exit;
}

/**
 * -------------------------
 * CHECK STORE EXISTS
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
$result = $stmt->get_result();

if ($result->num_rows === 0) {

    $stmt->close();

    http_response_code(404);

    echo json_encode([
        "status" => false,
        "message" => "Selected store does not exist."
    ]);

    exit;
}

$stmt->close();

/**
 * -------------------------
 * CHECK EMAIL
 * -------------------------
 */

if (!empty($email)) {

    $stmt = $conn->prepare("
    SELECT id
    FROM staffs
    WHERE email = ?
    LIMIT 1
    ");

    $stmt->bind_param("s", $email);
    $stmt->execute();

    if ($stmt->get_result()->num_rows > 0) {

        $stmt->close();

        http_response_code(409);

        echo json_encode([
            "status" => false,
            "message" => "Email already exists."
        ]);

        exit;
    }

    $stmt->close();
}

/**
 * -------------------------
 * INSERT STAFF
 * -------------------------
 */

$stmt = $conn->prepare("
INSERT INTO staffs
(
    store_id,
    first_name,
    last_name,
    email,
    phone,
    position,
    is_active
)
VALUES
(
    ?, ?, ?, ?, ?, ?, ?
)
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
    "isssssi",
    $store_id,
    $first_name,
    $last_name,
    $email,
    $phone,
    $position,
    $is_active
);

if (!$stmt->execute()) {

    http_response_code(500);

    echo json_encode([
        "status" => false,
        "message" => "Failed to create staff.",
        "error" => $stmt->error
    ]);

    $stmt->close();
    $conn->close();

    exit;
}

$staff_id = $stmt->insert_id;

$stmt->close();

/**
 * -------------------------
 * FETCH CREATED STAFF
 * -------------------------
 */

$stmt = $conn->prepare("
SELECT
    s.id,
    s.store_id,
    st.store_name,
    s.first_name,
    s.last_name,
    s.email,
    s.phone,
    s.position,
    s.is_active,
    s.created_at
FROM staffs s
INNER JOIN stores st
ON st.id = s.store_id
WHERE s.id = ?
LIMIT 1
");

$stmt->bind_param("i", $staff_id);
$stmt->execute();

$staff = $stmt->get_result()->fetch_assoc();

$stmt->close();
$conn->close();

/**
 * -------------------------
 * RESPONSE
 * -------------------------
 */

echo json_encode([
    "status" => true,
    "message" => "Staff created successfully.",
    "data" => $staff
]);

exit;