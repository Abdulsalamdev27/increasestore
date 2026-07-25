<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

/*
|--------------------------------------------------------------------------
| HEADERS
|--------------------------------------------------------------------------
*/

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: GET, OPTIONS");

/*
|--------------------------------------------------------------------------
| HANDLE PREFLIGHT
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {

    http_response_code(200);
    exit;

}

/*
|--------------------------------------------------------------------------
| ONLY ALLOW GET
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] !== "GET") {

    http_response_code(405);

    echo json_encode([
        "status"  => false,
        "message" => "Method Not Allowed."
    ]);

    exit;

}

/*
|--------------------------------------------------------------------------
| LOAD DEPENDENCIES
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/../../../config/dbconn.php";
require_once __DIR__ . "/../../middleware/auth.php";

/*
|--------------------------------------------------------------------------
| VERIFY DATABASE CONNECTION
|--------------------------------------------------------------------------
*/

if (!$conn) {

    http_response_code(500);

    echo json_encode([
        "status"  => false,
        "message" => "Database connection failed."
    ]);

    exit;

}

/*
|--------------------------------------------------------------------------
| JWT AUTHENTICATION
|--------------------------------------------------------------------------
*/

$user = $GLOBALS["authUser"] ?? null;

if (!$user) {

    http_response_code(401);

    echo json_encode([
        "status"  => false,
        "message" => "Unauthorized."
    ]);

    exit;

}

$adminId = (int)($user["admin_id"] ?? $user["user_id"] ?? 0);

if ($adminId <= 0) {

    http_response_code(401);

    echo json_encode([
        "status"  => false,
        "message" => "Invalid authentication token."
    ]);

    exit;

}

/*
|--------------------------------------------------------------------------
| PAGINATION
|--------------------------------------------------------------------------
*/

$page = isset($_GET["page"])
    ? max(1, (int)$_GET["page"])
    : 1;

$limit = isset($_GET["limit"])
    ? max(1, (int)$_GET["limit"])
    : 10;

$offset = ($page - 1) * $limit;

/*
|--------------------------------------------------------------------------
| DEFAULT PAGINATION LIMITS
|--------------------------------------------------------------------------
*/

if ($limit > 100) {

    $limit = 100;

}

if ($page < 1) {

    $page = 1;

}

/*
|--------------------------------------------------------------------------
| DEFAULT RESPONSE VARIABLES
|--------------------------------------------------------------------------
*/

$totalRecords = 0;

$products = [];

$pagination = [];

$where = [
    "p.is_active = 1",
    "p.status = 'available'",
    "si.quantity > 0"
];

$params = [];

$types = "";

/*
|--------------------------------------------------------------------------
| FILTERS
|--------------------------------------------------------------------------
*/

$search = trim($_GET["search"] ?? "");

$storeId = isset($_GET["store_id"])
    ? (int)$_GET["store_id"]
    : 0;

$productId = isset($_GET["product_id"])
    ? (int)$_GET["product_id"]
    : 0;

$category = trim($_GET["category"] ?? "");

$stock = trim($_GET["stock"] ?? "");

/*
|--------------------------------------------------------------------------
| SORTING
|--------------------------------------------------------------------------
*/

$sortBy = trim($_GET["sort_by"] ?? "id");

$sortOrder = strtoupper(
    trim($_GET["sort_order"] ?? "DESC")
);

/*
|--------------------------------------------------------------------------
| SORT COLUMN MAP
|--------------------------------------------------------------------------
*/

$sortMap = [

    "id"        => "si.id",

    "name"      => "p.product_name",

    "price"     => "p.selling_price",

    "quantity"  => "si.quantity",

    "category"  => "p.category",

    "barcode"   => "p.barcode",

    "sku"       => "p.sku",

    "store"     => "s.store_name",

    "created"   => "si.created_at",

    "updated"   => "si.updated_at"

];

$sortBy = $sortMap[$sortBy] ?? "si.id";

/*
|--------------------------------------------------------------------------
| SORT DIRECTION
|--------------------------------------------------------------------------
*/

if (!in_array($sortOrder, ["ASC", "DESC"])) {

    $sortOrder = "DESC";

}

/*
|--------------------------------------------------------------------------
| SEARCH FILTER
|--------------------------------------------------------------------------
*/

if ($search !== "") {

    $where[] = "(
        p.product_name LIKE ?
        OR p.barcode LIKE ?
        OR p.sku LIKE ?
        OR s.store_name LIKE ?
    )";

    $keyword = "%" . $search . "%";

    $params[] = $keyword;
    $params[] = $keyword;
    $params[] = $keyword;
    $params[] = $keyword;

    $types .= "ssss";

}

/*
|--------------------------------------------------------------------------
| STORE FILTER
|--------------------------------------------------------------------------
*/

if ($storeId > 0) {

    $where[] = "si.store_id = ?";

    $params[] = $storeId;

    $types .= "i";

}

/*
|--------------------------------------------------------------------------
| PRODUCT FILTER
|--------------------------------------------------------------------------
*/

if ($productId > 0) {

    $where[] = "si.product_id = ?";

    $params[] = $productId;

    $types .= "i";

}

/*
|--------------------------------------------------------------------------
| CATEGORY FILTER
|--------------------------------------------------------------------------
*/

if ($category !== "") {

    $where[] = "p.category = ?";

    $params[] = $category;

    $types .= "s";

}

/*
|--------------------------------------------------------------------------
| STOCK FILTER
|--------------------------------------------------------------------------
*/

switch ($stock) {

    case "available":

        $where[] = "si.quantity > 0";

        break;

    case "low":

        $where[] = "si.quantity <= p.minimum_stock";

        break;

    case "out":

        $where[] = "si.quantity <= 0";

        break;

}

/*
|--------------------------------------------------------------------------
| BUILD WHERE SQL
|--------------------------------------------------------------------------
*/

$whereSql = "";

if (!empty($where)) {

    $whereSql = " WHERE " . implode(" AND ", $where);

}


/*
|--------------------------------------------------------------------------
| COUNT QUERY
|--------------------------------------------------------------------------
*/

$countSql = "

SELECT
    COUNT(*) AS total

FROM store_inventory si

INNER JOIN products p
    ON p.id = si.product_id

INNER JOIN stores s
    ON s.id = si.store_id

{$whereSql}

";

$countStmt = $conn->prepare($countSql);

if (!$countStmt) {

    http_response_code(500);

    echo json_encode([
        "status"  => false,
        "message" => "Failed to prepare count query.",
        "error"   => $conn->error
    ]);

    exit;

}

if (!empty($params)) {

    $countStmt->bind_param($types, ...$params);

}

$countStmt->execute();

$countResult = $countStmt->get_result();

$totalRecords = (int)$countResult->fetch_assoc()["total"];

$countStmt->close();

/*
|--------------------------------------------------------------------------
| MAIN PRODUCTS QUERY
|--------------------------------------------------------------------------
*/

$sql = "

SELECT

    si.id AS inventory_id,

    si.store_id,

    si.product_id,

    si.quantity,

    si.created_at,

    si.updated_at,

    p.product_name,

    p.barcode,

    p.sku,

    p.category,

    p.description,

    p.unit,

    p.cost_price,

    p.selling_price,

    p.minimum_stock,

    p.status,

    p.is_active,

    p.created_at AS product_created_at,

    p.updated_at AS product_updated_at,

    s.store_name,

    s.address,

    s.phone,

    s.email

FROM store_inventory si

INNER JOIN products p
    ON p.id = si.product_id

INNER JOIN stores s
    ON s.id = si.store_id

{$whereSql}

ORDER BY {$sortBy} {$sortOrder}

LIMIT ?

OFFSET ?

";

/*
|--------------------------------------------------------------------------
| PREPARE MAIN QUERY
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare($sql);

if (!$stmt) {

    http_response_code(500);

    echo json_encode([
        "status"  => false,
        "message" => "Failed to prepare products query.",
        "error"   => $conn->error
    ]);

    exit;

}

/*
|--------------------------------------------------------------------------
| BIND PARAMETERS
|--------------------------------------------------------------------------
*/

$queryParams = $params;

$queryTypes = $types;

$queryParams[] = $limit;

$queryParams[] = $offset;

$queryTypes .= "ii";

$stmt->bind_param(
    $queryTypes,
    ...$queryParams
);

/*
|--------------------------------------------------------------------------
| EXECUTE QUERY
|--------------------------------------------------------------------------
*/

$stmt->execute();

$result = $stmt->get_result();

if (!$result) {

    http_response_code(500);

    echo json_encode([
        "status"  => false,
        "message" => "Failed to fetch products.",
        "error"   => $stmt->error
    ]);

    exit;

}

/*
|--------------------------------------------------------------------------
| FORMAT PRODUCTS
|--------------------------------------------------------------------------
*/

$products = [];

while ($row = $result->fetch_assoc()) {

    $products[] = [

        "inventory_id" => (int)$row["inventory_id"],

        "quantity" => (int)$row["quantity"],

        "created_at" => $row["created_at"],

        "updated_at" => $row["updated_at"],

        "product" => [

            "id" => (int)$row["product_id"],

            "name" => $row["product_name"],

            "barcode" => $row["barcode"],

            "sku" => $row["sku"],

            "category" => $row["category"],

            "description" => $row["description"],

            "unit" => $row["unit"],

            "cost_price" => (float)$row["cost_price"],

            "selling_price" => (float)$row["selling_price"],

            "minimum_stock" => (int)$row["minimum_stock"],

            "status" => $row["status"],

            "is_active" => (int)$row["is_active"],

            "created_at" => $row["product_created_at"],

            "updated_at" => $row["product_updated_at"]

        ],

        "store" => [

            "id" => (int)$row["store_id"],

            "name" => $row["store_name"],

            "address" => $row["address"],

            "phone" => $row["phone"],

            "email" => $row["email"]

        ]

    ];

}

/*
|--------------------------------------------------------------------------
| CLOSE STATEMENT
|--------------------------------------------------------------------------
*/

$stmt->close();

/*
|--------------------------------------------------------------------------
| PAGINATION
|--------------------------------------------------------------------------
*/

$pagination = [

    "page" => (int)$page,

    "limit" => (int)$limit,

    "total" => (int)$totalRecords,

    "total_pages" => max(
        1,
        (int)ceil($totalRecords / $limit)
    ),

    "has_previous" => $page > 1,

    "has_next" => ($page * $limit) < $totalRecords

];

/*
|--------------------------------------------------------------------------
| CLOSE DATABASE
|--------------------------------------------------------------------------
*/

$conn->close();

/*
|--------------------------------------------------------------------------
| SUCCESS RESPONSE
|--------------------------------------------------------------------------
*/

http_response_code(200);

echo json_encode([

    "status" => true,

    "message" => "Accepted products retrieved successfully.",

    "pagination" => $pagination,

    "data" => $products

], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

exit;