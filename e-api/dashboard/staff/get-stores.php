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
header("Access-Control-Allow-Methods: GET, OPTIONS");

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
 * FETCH STAFF
 * -------------------------
 */

$sql = "
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
    s.created_at,
    s.updated_at
FROM staffs s
INNER JOIN stores st
    ON s.store_id = st.id
ORDER BY s.created_at DESC
";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    http_response_code(500);
    echo json_encode([
        "status" => false,
        "message" => "Database prepare failed",
        "error" => $conn->error
    ]);
    exit;
}

if (!$stmt->execute()) {
    http_response_code(500);
    echo json_encode([
        "status" => false,
        "message" => "Failed to fetch staff.",
        "error" => $stmt->error
    ]);
    $stmt->close();
    $conn->close();
    exit;
}

$result = $stmt->get_result();

$staffs = [];

while ($row = $result->fetch_assoc()) {

    $staffs[] = [

        "id" => (int)$row["id"],

        "store_id" => (int)$row["store_id"],

        "store_name" => $row["store_name"],

        "first_name" => $row["first_name"],

        "last_name" => $row["last_name"],

        "full_name" => trim(
            $row["first_name"] . " " . $row["last_name"]
        ),

        "email" => $row["email"],

        "phone" => $row["phone"],

        "position" => $row["position"],

        "is_active" => (int)$row["is_active"],

        "created_at" => $row["created_at"],

        "updated_at" => $row["updated_at"]

    ];

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

    "message" => "Staff fetched successfully.",

    "total" => count($staffs),

    "data" => $staffs

]);

exit;