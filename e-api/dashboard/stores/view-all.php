<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

/**
 * -------------------------
 * HEADERS
 * -------------------------
 */

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
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

if (!$user || empty($user['user_id'])) {
    http_response_code(401);
    echo json_encode([
        "status" => false,
        "message" => "Token expired or invalid"
    ]);
    exit;
}

/**
 * -------------------------
 * FETCH STORES
 * -------------------------
 */

$sql = "
SELECT
    id,
    store_name,
    email,
    phone,
    address,
    created_at,
    updated_at
FROM stores
ORDER BY created_at DESC
";

$stmt = $conn->prepare($sql);
$stmt->execute();

$result = $stmt->get_result();

$stores = [];

while ($row = $result->fetch_assoc()) {
    $stores[] = $row;
}

$stmt->close();

/**
 * -------------------------
 * RESPONSE
 * -------------------------
 */

echo json_encode([
    "status" => true,
    "data" => $stores
]);

exit;