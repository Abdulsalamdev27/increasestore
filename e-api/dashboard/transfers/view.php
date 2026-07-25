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
        "message" => "Method not allowed."
    ]);

    exit;
}

/**
 * ---------------------------------------
 * DATABASE
 * ---------------------------------------
 */

require_once __DIR__ . "/../../../config/dbconn.php";
require_once __DIR__ . "/../../middleware/auth.php";

/**
 * ---------------------------------------
 * AUTHENTICATION
 * ---------------------------------------
 */

$user = $GLOBALS["authUser"] ?? null;

if (!$user) {

    http_response_code(401);

    echo json_encode([
        "status" => false,
        "message" => "Unauthorized."
    ]);

    exit;
}

/**
 * ---------------------------------------
 * GET TRANSFER ID
 * ---------------------------------------
 */

$id = isset($_GET["id"])
    ? (int)$_GET["id"]
    : 0;

if ($id <= 0) {

    http_response_code(422);

    echo json_encode([
        "status" => false,
        "message" => "Invalid transfer ID."
    ]);

    exit;
}

/**
 * ---------------------------------------
 * SQL QUERY
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
    p.category,
    p.unit,
    p.selling_price,
    p.cost_price,

    s.store_name,
    s.address,

    CONCAT(sender.first_name, ' ', sender.last_name) AS sender_name,
    sender.email AS sender_email,
    sender.phone AS sender_phone,

    CASE
        WHEN reviewer.id IS NULL THEN NULL
        ELSE CONCAT(reviewer.first_name, ' ', reviewer.last_name)
    END AS reviewer_name,

    reviewer.email AS reviewer_email,
    reviewer.phone AS reviewer_phone

FROM product_transfers pt

INNER JOIN products p
    ON p.id = pt.product_id

INNER JOIN stores s
    ON s.id = pt.store_id

INNER JOIN admins sender
    ON sender.id = pt.sent_by

LEFT JOIN admins reviewer
    ON reviewer.id = pt.reviewed_by

WHERE pt.id = ?

LIMIT 1

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

    "i",

    $id

);

$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 0) {

    http_response_code(404);

    echo json_encode([

        "status" => false,

        "message" => "Transfer not found."

    ]);

    $stmt->close();

    $conn->close();

    exit;

}

$transfer = $result->fetch_assoc();

$stmt->close();

/**
 * ---------------------------------------
 * FORMAT RESPONSE
 * ---------------------------------------
 */

$response = [

    "id" => (int)$transfer["id"],

    "quantity" => (int)$transfer["quantity"],

    "movement_type" => $transfer["movement_type"],

    "status" => $transfer["status"],

    "reference_no" => $transfer["reference_no"],

    "remarks" => $transfer["remarks"],

    "reviewed_at" => $transfer["reviewed_at"],

    "created_at" => $transfer["created_at"],

    "updated_at" => $transfer["updated_at"],

    /**
     * ---------------------------------------
     * PRODUCT
     * ---------------------------------------
     */

    "product" => [

        "id" => (int)$transfer["product_id"],

        "name" => $transfer["product_name"],

        "barcode" => $transfer["barcode"],

        "sku" => $transfer["sku"],

        "category" => $transfer["category"],

        "unit" => $transfer["unit"],

        "cost_price" => (float)$transfer["cost_price"],

        "selling_price" => (float)$transfer["selling_price"]

    ],

    /**
     * ---------------------------------------
     * STORE
     * ---------------------------------------
     */

"store" => [

    "id" => (int)$transfer["store_id"],

    "name" => $transfer["store_name"],

    "address" => $transfer["address"]

],

    /**
     * ---------------------------------------
     * SENT BY
     * ---------------------------------------
     */

    "sender" => [

        "id" => (int)$transfer["sent_by"],

        "name" => $transfer["sender_name"],

        "email" => $transfer["sender_email"],

        "phone" => $transfer["sender_phone"]

    ],

    /**
     * ---------------------------------------
     * REVIEWED BY
     * ---------------------------------------
     */

    "reviewer" => [

        "id" => $transfer["reviewed_by"]
            ? (int)$transfer["reviewed_by"]
            : null,

        "name" => $transfer["reviewer_name"],

        "email" => $transfer["reviewer_email"],

        "phone" => $transfer["reviewer_phone"]

    ]

];

/**
 * ---------------------------------------
 * JSON RESPONSE
 * ---------------------------------------
 */

$conn->close();

http_response_code(200);

echo json_encode([

    "status" => true,

    "message" => "Transfer retrieved successfully.",

    "data" => $response

]);

exit;