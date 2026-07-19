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

try {

    /**
     * ---------------------------------------
     * QUERY PARAMETERS
     * ---------------------------------------
     */

    $page = max(1, (int)($_GET["page"] ?? 1));

    $limit = max(1, (int)($_GET["limit"] ?? 20));

    $offset = ($page - 1) * $limit;

    $search = trim($_GET["search"] ?? "");

    $status = trim($_GET["status"] ?? "");

    $movement = trim($_GET["movement_type"] ?? "");

    $storeId = (int)($_GET["store_id"] ?? 0);

    /**
     * ---------------------------------------
     * VALIDATION
     * ---------------------------------------
     */

    $allowedStatus = [
        "pending",
        "accepted",
        "rejected"
    ];

    if ($status !== "" && !in_array($status, $allowedStatus, true)) {

        http_response_code(422);

        echo json_encode([
            "status" => false,
            "message" => "Invalid transfer status."
        ]);

        exit;
    }

    $allowedMovement = [
        "send",
        "return"
    ];

    if ($movement !== "" && !in_array($movement, $allowedMovement, true)) {

        http_response_code(422);

        echo json_encode([
            "status" => false,
            "message" => "Invalid movement type."
        ]);

        exit;
    }

    /**
     * ---------------------------------------
     * PREPARE SQL VARIABLES
     * ---------------------------------------
     */

    $params = [];

    $types = "";


    /**
     * ---------------------------------------
     * MAIN SQL QUERY
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

        s.store_name,

        CONCAT(sender.first_name, ' ', sender.last_name) AS sender_name,

        CASE
            WHEN reviewer.id IS NULL
            THEN NULL
            ELSE CONCAT(reviewer.first_name, ' ', reviewer.last_name)
        END AS reviewer_name

    FROM product_transfers pt

    INNER JOIN products p
        ON p.id = pt.product_id

    INNER JOIN stores s
        ON s.id = pt.store_id

    INNER JOIN admins sender
        ON sender.id = pt.sent_by

    LEFT JOIN admins reviewer
        ON reviewer.id = pt.reviewed_by

    WHERE 1 = 1

    ";

    /**
     * ---------------------------------------
     * SEARCH FILTER
     * ---------------------------------------
     */

    if ($search !== "") {

        $sql .= "

        AND (

            p.product_name LIKE ?

            OR p.barcode LIKE ?

            OR p.sku LIKE ?

            OR s.store_name LIKE ?

            OR pt.reference_no LIKE ?

        )

        ";

        $keyword = "%{$search}%";

        for ($i = 0; $i < 5; $i++) {

            $params[] = $keyword;

            $types .= "s";

        }

    }

    /**
     * ---------------------------------------
     * STATUS FILTER
     * ---------------------------------------
     */

    if ($status !== "") {

        $sql .= " AND pt.status = ? ";

        $params[] = $status;

        $types .= "s";

    }

    /**
     * ---------------------------------------
     * MOVEMENT FILTER
     * ---------------------------------------
     */

    if ($movement !== "") {

        $sql .= " AND pt.movement_type = ? ";

        $params[] = $movement;

        $types .= "s";

    }

    /**
     * ---------------------------------------
     * STORE FILTER
     * ---------------------------------------
     */

    if ($storeId > 0) {

        $sql .= " AND pt.store_id = ? ";

        $params[] = $storeId;

        $types .= "i";

    }

    /**
     * ---------------------------------------
     * ORDERING + PAGINATION
     * ---------------------------------------
     */

    $sql .= "

    ORDER BY pt.created_at DESC

    LIMIT ?, ?

    ";

    $params[] = $offset;
    $params[] = $limit;

    $types .= "ii";

    /**
     * ---------------------------------------
     * PREPARE STATEMENT
     * ---------------------------------------
     */

    $stmt = $conn->prepare($sql);

    if (!$stmt) {

        throw new Exception($conn->error);

    }

    /**
     * ---------------------------------------
     * BIND PARAMETERS
     * ---------------------------------------
     */

    if (!empty($params)) {

        $stmt->bind_param(

            $types,

            ...$params

        );

    }

        /**
     * ---------------------------------------
     * EXECUTE QUERY
     * ---------------------------------------
     */

    if (!$stmt->execute()) {

        throw new Exception($stmt->error);

    }

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

            "category" => $row["category"],

            "unit" => $row["unit"],

            "store_name" => $row["store_name"],

            "quantity" => (int)$row["quantity"],

            "movement_type" => $row["movement_type"],

            "status" => $row["status"],

            "reference_no" => $row["reference_no"],

            "remarks" => $row["remarks"],

            "sent_by" => (int)$row["sent_by"],

            "sent_by_name" => $row["sender_name"],

            "reviewed_by" => $row["reviewed_by"]
                ? (int)$row["reviewed_by"]
                : null,

            "reviewed_by_name" => $row["reviewer_name"],

            "reviewed_at" => $row["reviewed_at"],

            "created_at" => $row["created_at"],

            "updated_at" => $row["updated_at"]

        ];

    }

    $stmt->close();

    /**
     * ---------------------------------------
     * TOTAL RECORDS
     * ---------------------------------------
     */

    $countSql = "

    SELECT COUNT(*) AS total

    FROM product_transfers pt

    INNER JOIN products p
        ON p.id = pt.product_id

    INNER JOIN stores s
        ON s.id = pt.store_id

    WHERE 1=1

    ";

    $countParams = [];

    $countTypes = "";

    if ($search !== "") {

        $countSql .= "

        AND (

            p.product_name LIKE ?

            OR p.barcode LIKE ?

            OR p.sku LIKE ?

            OR s.store_name LIKE ?

            OR pt.reference_no LIKE ?

        )

        ";

        $keyword = "%{$search}%";

        for ($i = 0; $i < 5; $i++) {

            $countParams[] = $keyword;

            $countTypes .= "s";

        }

    }

    if ($status !== "") {

        $countSql .= " AND pt.status = ? ";

        $countParams[] = $status;

        $countTypes .= "s";

    }

    if ($movement !== "") {

        $countSql .= " AND pt.movement_type = ? ";

        $countParams[] = $movement;

        $countTypes .= "s";

    }

    if ($storeId > 0) {

        $countSql .= " AND pt.store_id = ? ";

        $countParams[] = $storeId;

        $countTypes .= "i";

    }

    $countStmt = $conn->prepare($countSql);

    if (!$countStmt) {

        throw new Exception($conn->error);

    }

    if (!empty($countParams)) {

        $countStmt->bind_param(

            $countTypes,

            ...$countParams

        );

    }

    $countStmt->execute();

    $countResult = $countStmt->get_result();

    $total = (int)$countResult->fetch_assoc()["total"];

    $countStmt->close();

    /**
     * ---------------------------------------
     * PAGINATION
     * ---------------------------------------
     */

    $totalPages = max(1, ceil($total / $limit));

        /**
     * ---------------------------------------
     * CLOSE DATABASE
     * ---------------------------------------
     */

    $conn->close();

    /**
     * ---------------------------------------
     * SUCCESS RESPONSE
     * ---------------------------------------
     */

    http_response_code(200);

    echo json_encode([

        "status" => true,

        "message" => "Transfers fetched successfully.",

        "pagination" => [

            "page" => $page,

            "limit" => $limit,

            "total" => $total,

            "total_pages" => $totalPages,

            "has_previous" => ($page > 1),

            "has_next" => ($page < $totalPages)

        ],

        "filters" => [

            "search" => $search,

            "status" => $status,

            "movement_type" => $movement,

            "store_id" => $storeId

        ],

        "data" => $transfers

    ]);

    exit;

/**
 * ---------------------------------------
 * ERROR HANDLER
 * ---------------------------------------
 */

} catch (Exception $e) {

    if (isset($stmt) && $stmt instanceof mysqli_stmt) {
        $stmt->close();
    }

    if (isset($countStmt) && $countStmt instanceof mysqli_stmt) {
        $countStmt->close();
    }

    if (isset($conn) && $conn instanceof mysqli && !$conn->connect_errno) {
        $conn->close();
    }

    http_response_code(500);

    echo json_encode([

        "status" => false,

        "message" => "Failed to fetch transfers.",

        "error" => $e->getMessage()

    ]);

    exit;

}