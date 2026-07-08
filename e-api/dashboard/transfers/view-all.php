<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

/**
 * ---------------------------------------
 * HEADERS
 * ---------------------------------------
 */

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: GET, OPTIONS");

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(200);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "GET") {

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
 * ---------------------------------------
 * AUTH USER
 * ---------------------------------------
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

/**
 * ---------------------------------------
 * FETCH TRANSFERS
 * ---------------------------------------
 */

$sql = "

SELECT

    pt.id,
    pt.product_id,
    pt.store_id,
    pt.quantity,
    pt.movement_type,
    pt.status,
    pt.reference_no,
    pt.remarks,
    pt.sent_by,
    pt.reviewed_by,
    pt.reviewed_at,
    pt.created_at,
    pt.updated_at,

    p.product_name,
    p.barcode,
    p.sku,

    s.store_name,

    CONCAT(a1.first_name,' ',a1.last_name) AS sent_by_name,

    CASE

        WHEN a2.id IS NULL THEN NULL

        ELSE CONCAT(a2.first_name,' ',a2.last_name)

    END AS reviewed_by_name

FROM product_transfers pt

LEFT JOIN products p
ON p.id = pt.product_id

LEFT JOIN stores s
ON s.id = pt.store_id

LEFT JOIN admins a1
ON a1.id = pt.sent_by

LEFT JOIN admins a2
ON a2.id = pt.reviewed_by

ORDER BY pt.created_at DESC

";

$stmt = $conn->prepare($sql);

if (!$stmt) {

    http_response_code(500);

    echo json_encode([

        "status" => false,

        "message" => "Database prepare failed",

        "mysql_error" => $conn->error

    ]);

    exit;
}

$stmt->execute();

$result = $stmt->get_result();

$transfers = [];

while ($row = $result->fetch_assoc()) {

    $transfers[] = [

        "id" => (int)$row["id"],

        "product_id" => (int)$row["product_id"],

        "store_id" => (int)$row["store_id"],

        "product_name" => $row["product_name"],

        "barcode" => $row["barcode"],

        "sku" => $row["sku"],

        "store_name" => $row["store_name"],

        "quantity" => (int)$row["quantity"],

        "movement_type" => $row["movement_type"],

        "status" => $row["status"],

        "reference_no" => $row["reference_no"],

        "remarks" => $row["remarks"],

        "sent_by" => (int)$row["sent_by"],

        "sent_by_name" => $row["sent_by_name"],

        "reviewed_by" => $row["reviewed_by"] !== null
            ? (int)$row["reviewed_by"]
            : null,

        "reviewed_by_name" => $row["reviewed_by_name"],

        "reviewed_at" => $row["reviewed_at"],

        "created_at" => $row["created_at"],

        "updated_at" => $row["updated_at"]

    ];

}

$stmt->close();

$conn->close();

/**
 * ---------------------------------------
 * RESPONSE
 * ---------------------------------------
 */

echo json_encode([

    "status" => true,

    "message" => "Transfers fetched successfully.",

    "total" => count($transfers),

    "data" => $transfers

]);

exit;