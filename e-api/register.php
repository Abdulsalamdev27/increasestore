<?php
require_once __DIR__ . "/../config/dbconn.php";

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
    echo json_encode(["status" => true]);
    exit;
}

/**
 * DB CONNECTION CHECK
 */
if (!$conn) {
    echo json_encode([
        "status" => false,
        "message" => "Database connection failed",
        "error" => mysqli_connect_error()
    ]);
    exit;
}

/**
 * READ JSON
 */
$input = json_decode(file_get_contents("php://input"), true);

if (!is_array($input)) {
    echo json_encode([
        "status" => false,
        "message" => "Invalid JSON payload"
    ]);
    exit;
}

/**
 * SANITIZE INPUTS
 */
$firstName = trim($input['firstName'] ?? '');
$lastName  = trim($input['lastName'] ?? '');
$email     = trim($input['email'] ?? '');
$phone     = trim($input['pnumber'] ?? '');
$password  = $input['pword'] ?? '';
$confirm   = $input['re_pword'] ?? '';


/**
 * VALIDATION
 */
if ($firstName === '' || $lastName === '' || $email === '' || $password === '' || $confirm === '') {
    echo json_encode([
        "status" => false,
        "message" => "All required fields must be filled"
    ]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        "status" => false,
        "message" => "Invalid email address"
    ]);
    exit;
}

if ($password !== $confirm) {
    echo json_encode([
        "status" => false,
        "message" => "Passwords do not match"
    ]);
    exit;
}

if (strlen($password) < 8) {
    echo json_encode([
        "status" => false,
        "message" => "Password must be at least 8 characters"
    ]);
    exit;
}

/**
 * CHECK EMAIL EXISTS
 */
$stmt = $conn->prepare("SELECT id FROM admins WHERE email = ?");
if (!$stmt) {
    echo json_encode([
        "status" => false,
        "message" => "Database prepare failed (email check)",
        "error" => $conn->error
    ]);
    exit;
}

$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo json_encode([
        "status" => false,
        "message" => "Email already registered"
    ]);
    $stmt->close();
    exit;
}
$stmt->close();

// Hash the password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Insert the admin
$stmt = $conn->prepare("
    INSERT INTO admins (first_name, last_name, email, phone, password)
    VALUES (?, ?, ?, ?, ?)
");

if (!$stmt) {
    echo json_encode([
        "status" => false,
        "message" => "Database prepare failed (insert)",
        "error" => $conn->error
    ]);
    exit;
}

$stmt->bind_param(
    "sssss",
    $firstName,
    $lastName,
    $email,
    $phone,
    $hashedPassword
);

if ($stmt->execute()) {
    echo json_encode([
        "status" => true,
        "message" => "Admin registered successfully",
        "id" => $stmt->insert_id
    ]);
} else {
    echo json_encode([
        "status" => false,
        "message" => "Registration failed",
        "error" => $stmt->error
    ]);
}

$stmt->close();
$conn->close();