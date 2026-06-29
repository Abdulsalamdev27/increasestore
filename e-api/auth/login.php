<?php
require_once __DIR__ . "/../../config/dbconn.php";
require "../helpers/jwt.php";

/**
 * HEADERS
 */
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: POST, OPTIONS");

/**
 * PREFLIGHT
 */
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
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
        "message" => "Invalid JSON payload"
    ]);
    exit;
}

$email = trim($data['email'] ?? '');
$password = $data['password'] ?? '';

if (empty($email) || empty($password)) {
    http_response_code(400);
    echo json_encode([
        "status" => false,
        "message" => "Email and password are required"
    ]);
    exit;
}

/**
 * FETCH ADMIN
 */
$stmt = $conn->prepare("
    SELECT
        id,
        first_name,
        last_name,
        email,
        phone,
        password
    FROM admins
    WHERE email = ?
    LIMIT 1
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

$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 0) {
    http_response_code(401);
    echo json_encode([
        "status" => false,
        "message" => "Invalid email or password"
    ]);
    exit;
}

$admin = $result->fetch_assoc();

/**
 * VERIFY PASSWORD
 */
if (!password_verify($password, $admin['password'])) {
    http_response_code(401);
    echo json_encode([
        "status" => false,
        "message" => "Invalid email or password"
    ]);
    exit;
}

/**
 * JWT PAYLOAD
 */
$payload = [
    "admin_id"   => $admin['id'],
    "first_name" => $admin['first_name'],
    "last_name"  => $admin['last_name'],
    "email"      => $admin['email'],
    "phone"      => $admin['phone'],
    "iat"        => time(),
    "exp"        => time() + (60 * 60 * 24) // 24 hours
];

$token = generateJWT($payload, "SUPER_SECRET_KEY");

/**
 * RESPONSE
 */
echo json_encode([
    "status" => true,
    "message" => "Login successful",
    "token" => $token,
    "admin" => [
        "id" => $admin['id'],
        "first_name" => $admin['first_name'],
        "last_name" => $admin['last_name'],
        "email" => $admin['email'],
        "phone" => $admin['phone']
    ]
]);

$stmt->close();
$conn->close();