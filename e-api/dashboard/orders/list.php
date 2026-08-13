<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

/**
 * ==========================================
 * HEADERS
 * ==========================================
 */

header("Content-Type: application/json; charset=UTF-8");
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
 * ==========================================
 * DATABASE
 * ==========================================
 */

require_once __DIR__ . "/../../../config/dbconn.php";
require_once __DIR__ . "/../../middleware/auth.php";


/**
 * ==========================================
 * AUTHENTICATION
 * ==========================================
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
     * ==========================================
     * QUERY PARAMETERS
     * ==========================================
     *
     * Supported:
     *
     * ?page=1
     * ?limit=20
     * ?search=customer
     * ?payment_method=cash
     * ?payment_status=paid
     * ?created_by=1
     *
     */

    $page = max(
        1,
        (int)($_GET["page"] ?? 1)
    );

    $limit = max(
        1,
        (int)($_GET["limit"] ?? 20)
    );

    /**
     * Prevent excessively large requests
     */

    if ($limit > 200) {
        $limit = 200;
    }

    $offset = ($page - 1) * $limit;


    $search = trim(
        $_GET["search"] ?? ""
    );

    $paymentMethod = trim(
        $_GET["payment_method"] ?? ""
    );

    $paymentStatus = trim(
        $_GET["payment_status"] ?? ""
    );

    $createdBy = (int)(
        $_GET["created_by"] ?? 0
    );


    /**
     * ==========================================
     * PREPARE SQL VARIABLES
     * ==========================================
     */

    $params = [];

    $types = "";


    /**
     * ==========================================
     * MAIN SQL QUERY
     * ==========================================
     */

    $sql = "

    SELECT

        o.id,
        o.order_no,

        o.customer_name,
        o.customer_phone,
        o.customer_email,
        o.customer_code,

        o.payment_method,
        o.payment_status,

        o.subtotal,
        o.discount,
        o.tax,
        o.shipping,
        o.total_amount,
        o.amount_paid,
        o.balance,

        o.notes,

        o.created_by,
        o.created_at,
        o.updated_at,

        CONCAT(
            COALESCE(a.first_name, ''),
            ' ',
            COALESCE(a.last_name, '')
        ) AS created_by_name,

        (
            SELECT GROUP_CONCAT(
                DISTINCT oi.store_name
                ORDER BY oi.store_name ASC
                SEPARATOR ', '
            )

            FROM order_items oi

            WHERE oi.order_id = o.id

        ) AS store_names,

        (
            SELECT COUNT(*)

            FROM order_items oi

            WHERE oi.order_id = o.id

        ) AS total_items,

        (
            SELECT COALESCE(
                SUM(oi.quantity),
                0
            )

            FROM order_items oi

            WHERE oi.order_id = o.id

        ) AS total_quantity

    FROM orders o

    LEFT JOIN admins a
        ON a.id = o.created_by

    WHERE 1 = 1

    ";


    /**
     * ==========================================
     * SEARCH FILTER
     * ==========================================
     */

    if ($search !== "") {

        $sql .= "

        AND (

            o.order_no LIKE ?

            OR o.customer_name LIKE ?

            OR o.customer_phone LIKE ?

            OR o.customer_email LIKE ?

            OR o.customer_code LIKE ?

        )

        ";

        $keyword = "%{$search}%";

        for ($i = 0; $i < 5; $i++) {

            $params[] = $keyword;

            $types .= "s";
        }
    }


    /**
     * ==========================================
     * PAYMENT METHOD FILTER
     * ==========================================
     */

    if ($paymentMethod !== "") {

        $sql .= "

        AND o.payment_method = ?

        ";

        $params[] = $paymentMethod;

        $types .= "s";
    }


    /**
     * ==========================================
     * PAYMENT STATUS FILTER
     * ==========================================
     */

    if ($paymentStatus !== "") {

        $sql .= "

        AND o.payment_status = ?

        ";

        $params[] = $paymentStatus;

        $types .= "s";
    }


    /**
     * ==========================================
     * CREATED BY FILTER
     * ==========================================
     */

    if ($createdBy > 0) {

        $sql .= "

        AND o.created_by = ?

        ";

        $params[] = $createdBy;

        $types .= "i";
    }


    /**
     * ==========================================
     * ORDERING + PAGINATION
     * ==========================================
     */

    $sql .= "

    ORDER BY o.created_at DESC

    LIMIT ?, ?

    ";

    $params[] = $offset;
    $params[] = $limit;

    $types .= "ii";


    /**
     * ==========================================
     * PREPARE STATEMENT
     * ==========================================
     */

    $stmt = $conn->prepare($sql);

    if (!$stmt) {

        throw new Exception(
            "Database prepare failed: " . $conn->error
        );
    }


    /**
     * ==========================================
     * BIND PARAMETERS
     * ==========================================
     */

    if (!empty($params)) {

        $stmt->bind_param(
            $types,
            ...$params
        );
    }


    /**
     * ==========================================
     * EXECUTE QUERY
     * ==========================================
     */

    if (!$stmt->execute()) {

        throw new Exception(
            "Unable to fetch orders: " . $stmt->error
        );
    }


    /**
     * ==========================================
     * GET RESULT
     * ==========================================
     */

    $result = $stmt->get_result();

    $orders = [];


    /**
     * ==========================================
     * BUILD ORDERS
     * ==========================================
     */

    while ($row = $result->fetch_assoc()) {

        $orders[] = [

            /**
             * ----------------------------------
             * BASIC ORDER INFORMATION
             * ----------------------------------
             */

            "id" => (int)$row["id"],

            "order_no" =>
                $row["order_no"] ?? "",


            /**
             * ----------------------------------
             * CUSTOMER
             * ----------------------------------
             */

            "customer_name" =>
                $row["customer_name"] ?? "",

            "customer_phone" =>
                $row["customer_phone"] ?? "",

            "customer_email" =>
                $row["customer_email"] ?? "",

            "customer_code" =>
                $row["customer_code"] ?? "",


            /**
             * ----------------------------------
             * PAYMENT
             * ----------------------------------
             */

            "payment_method" =>
                $row["payment_method"] ?? "",

            "payment_status" =>
                $row["payment_status"] ?? "",


            /**
             * ----------------------------------
             * FINANCIAL
             * ----------------------------------
             */

            "subtotal" =>
                (float)($row["subtotal"] ?? 0),

            "discount" =>
                (float)($row["discount"] ?? 0),

            "tax" =>
                (float)($row["tax"] ?? 0),

            "shipping" =>
                (float)($row["shipping"] ?? 0),

            "total_amount" =>
                (float)($row["total_amount"] ?? 0),

            "amount_paid" =>
                (float)($row["amount_paid"] ?? 0),

            "balance" =>
                (float)($row["balance"] ?? 0),


            /**
             * ----------------------------------
             * OTHER
             * ----------------------------------
             */

            "notes" =>
                $row["notes"] ?? "",


            /**
             * ----------------------------------
             * CASHIER / CREATED BY
             * ----------------------------------
             */

            "created_by" =>
                (int)($row["created_by"] ?? 0),

            "created_by_name" =>
                trim(
                    $row["created_by_name"] ?? ""
                ),


            /**
             * ----------------------------------
             * STORE
             * ----------------------------------
             */

            "store_name" =>
                $row["store_names"] ?? "-",


            /**
             * ----------------------------------
             * ITEM SUMMARY
             * ----------------------------------
             */

            "total_items" =>
                (int)($row["total_items"] ?? 0),

            "total_quantity" =>
                (int)($row["total_quantity"] ?? 0),


            /**
             * ----------------------------------
             * DATES
             * ----------------------------------
             */

            "created_at" =>
                $row["created_at"] ?? null,

            "updated_at" =>
                $row["updated_at"] ?? null,


            /**
             * ----------------------------------
             * RECEIPT URLS
             * ----------------------------------
             */

            "view_url" =>
                "../pages/order-receipt.php?id=" .
                (int)$row["id"],

            "print_url" =>
                "../pages/order-receipt.php?id=" .
                (int)$row["id"] .
                "&print=1"

        ];
    }


    /**
     * ==========================================
     * CLOSE MAIN STATEMENT
     * ==========================================
     */

    $stmt->close();


    /**
     * ==========================================
     * TOTAL RECORDS
     * ==========================================
     */

    $countSql = "

    SELECT COUNT(*) AS total

    FROM orders o

    WHERE 1 = 1

    ";


    $countParams = [];

    $countTypes = "";


    /**
     * ==========================================
     * COUNT SEARCH FILTER
     * ==========================================
     */

    if ($search !== "") {

        $countSql .= "

        AND (

            o.order_no LIKE ?

            OR o.customer_name LIKE ?

            OR o.customer_phone LIKE ?

            OR o.customer_email LIKE ?

            OR o.customer_code LIKE ?

        )

        ";

        $keyword = "%{$search}%";

        for ($i = 0; $i < 5; $i++) {

            $countParams[] = $keyword;

            $countTypes .= "s";
        }
    }


    /**
     * ==========================================
     * COUNT PAYMENT METHOD
     * ==========================================
     */

    if ($paymentMethod !== "") {

        $countSql .= "

        AND o.payment_method = ?

        ";

        $countParams[] = $paymentMethod;

        $countTypes .= "s";
    }


    /**
     * ==========================================
     * COUNT PAYMENT STATUS
     * ==========================================
     */

    if ($paymentStatus !== "") {

        $countSql .= "

        AND o.payment_status = ?

        ";

        $countParams[] = $paymentStatus;

        $countTypes .= "s";
    }


    /**
     * ==========================================
     * COUNT CREATED BY
     * ==========================================
     */

    if ($createdBy > 0) {

        $countSql .= "

        AND o.created_by = ?

        ";

        $countParams[] = $createdBy;

        $countTypes .= "i";
    }


    /**
     * ==========================================
     * PREPARE COUNT QUERY
     * ==========================================
     */

    $countStmt = $conn->prepare($countSql);

    if (!$countStmt) {

        throw new Exception(
            "Count query prepare failed: " .
            $conn->error
        );
    }


    /**
     * ==========================================
     * BIND COUNT PARAMETERS
     * ==========================================
     */

    if (!empty($countParams)) {

        $countStmt->bind_param(
            $countTypes,
            ...$countParams
        );
    }


    /**
     * ==========================================
     * EXECUTE COUNT
     * ==========================================
     */

    if (!$countStmt->execute()) {

        throw new Exception(
            "Count query failed: " .
            $countStmt->error
        );
    }


    /**
     * ==========================================
     * GET TOTAL
     * ==========================================
     */

    $countResult =
        $countStmt->get_result();

    $totalRow =
        $countResult->fetch_assoc();

    $total =
        (int)($totalRow["total"] ?? 0);


    $countStmt->close();


    /**
     * ==========================================
     * PAGINATION
     * ==========================================
     */

    $totalPages = max(
        1,
        (int)ceil($total / $limit)
    );


    /**
     * ==========================================
     * CLOSE DATABASE
     * ==========================================
     */

    $conn->close();


    /**
     * ==========================================
     * SUCCESS RESPONSE
     * ==========================================
     */

    http_response_code(200);

    echo json_encode([

        "status" => true,

        "message" =>
            "Orders fetched successfully.",

        "pagination" => [

            "page" =>
                $page,

            "limit" =>
                $limit,

            "total" =>
                $total,

            "total_pages" =>
                $totalPages,

            "has_previous" =>
                ($page > 1),

            "has_next" =>
                ($page < $totalPages)

        ],

        "filters" => [

            "search" =>
                $search,

            "payment_method" =>
                $paymentMethod,

            "payment_status" =>
                $paymentStatus,

            "created_by" =>
                $createdBy

        ],

        "data" =>
            $orders

    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

    exit;


/**
 * ==========================================
 * ERROR HANDLER
 * ==========================================
 */

} catch (Exception $e) {

    /**
     * Close main statement if it exists
     */

    if (
        isset($stmt) &&
        $stmt instanceof mysqli_stmt
    ) {

        $stmt->close();
    }


    /**
     * Close count statement if it exists
     */

    if (
        isset($countStmt) &&
        $countStmt instanceof mysqli_stmt
    ) {

        $countStmt->close();
    }


    /**
     * Close database connection
     */

    if (
        isset($conn) &&
        $conn instanceof mysqli &&
        !$conn->connect_errno
    ) {

        $conn->close();
    }


    /**
     * Error response
     */

    http_response_code(500);

    echo json_encode([

        "status" => false,

        "message" =>
            "Failed to fetch orders.",

        "error" =>
            $e->getMessage()

    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

    exit;
}