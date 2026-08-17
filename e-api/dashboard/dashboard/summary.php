<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: GET, OPTIONS");


/**
 * ==========================================================
 * OPTIONS
 * ==========================================================
 */

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {

    http_response_code(200);

    exit;

}


/**
 * ==========================================================
 * METHOD
 * ==========================================================
 */

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {

    http_response_code(405);

    echo json_encode([
        "status" => false,
        "message" => "Method not allowed"
    ]);

    exit;

}


/**
 * ==========================================================
 * DATABASE + AUTH
 * ==========================================================
 */

require_once __DIR__ . "/../../../config/dbconn.php";
require_once __DIR__ . "/../../middleware/auth.php";


/**
 * ==========================================================
 * AUTHENTICATION
 * ==========================================================
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
 * ==========================================================
 * DATABASE CHECK
 * ==========================================================
 */

if (!$conn) {

    http_response_code(500);

    echo json_encode([
        "status" => false,
        "message" => "Database connection failed."
    ]);

    exit;

}


try {


    /**
     * ======================================================
     * TOTAL ORDERS
     * ======================================================
     */

    $sql = "
        SELECT COUNT(*) AS total_orders
        FROM orders
    ";

    $result = $conn->query($sql);

    if (!$result) {
        throw new Exception(
            "Unable to calculate total orders: " .
            $conn->error
        );
    }

    $row = $result->fetch_assoc();

    $totalOrders = (int)(
        $row['total_orders'] ?? 0
    );


    /**
     * ======================================================
     * TOTAL SALES
     * ======================================================
     *
     * Based on your schema:
     *
     * orders.total_amount
     *
     */

    $sql = "
        SELECT
            COALESCE(
                SUM(total_amount),
                0
            ) AS total_sales
        FROM orders
    ";

    $result = $conn->query($sql);

    if (!$result) {
        throw new Exception(
            "Unable to calculate total sales: " .
            $conn->error
        );
    }

    $row = $result->fetch_assoc();

    $totalSales = (float)(
        $row['total_sales'] ?? 0
    );


    /**
     * ======================================================
     * TOTAL PRODUCTS
     * ======================================================
     */

    $sql = "
        SELECT COUNT(*) AS total_products
        FROM products
    ";

    $result = $conn->query($sql);

    if (!$result) {
        throw new Exception(
            "Unable to calculate total products: " .
            $conn->error
        );
    }

    $row = $result->fetch_assoc();

    $totalProducts = (int)(
        $row['total_products'] ?? 0
    );


    /**
     * ======================================================
     * LOW STOCK
     * ======================================================
     *
     * quantity > 0
     * AND quantity <= minimum_stock
     *
     */

    $sql = "
        SELECT COUNT(*) AS low_stock
        FROM products
        WHERE
            quantity > 0
            AND quantity <= minimum_stock
    ";

    $result = $conn->query($sql);

    if (!$result) {
        throw new Exception(
            "Unable to calculate low stock: " .
            $conn->error
        );
    }

    $row = $result->fetch_assoc();

    $lowStock = (int)(
        $row['low_stock'] ?? 0
    );


    /**
     * ======================================================
     * OUT OF STOCK
     * ======================================================
     */

    $sql = "
        SELECT COUNT(*) AS out_of_stock
        FROM products
        WHERE
            quantity <= 0
            OR status = 'out_of_stock'
    ";

    $result = $conn->query($sql);

    if (!$result) {
        throw new Exception(
            "Unable to calculate out of stock: " .
            $conn->error
        );
    }

    $row = $result->fetch_assoc();

    $outOfStock = (int)(
        $row['out_of_stock'] ?? 0
    );


    /**
     * ======================================================
     * PENDING ORDERS
     * ======================================================
     */

    $sql = "
        SELECT COUNT(*) AS pending_orders
        FROM orders
        WHERE payment_status = 'pending'
    ";

    $result = $conn->query($sql);

    if (!$result) {
        throw new Exception(
            "Unable to calculate pending orders: " .
            $conn->error
        );
    }

    $row = $result->fetch_assoc();

    $pendingOrders = (int)(
        $row['pending_orders'] ?? 0
    );


    /**
     * ======================================================
     * PAID ORDERS
     * ======================================================
     */

    $sql = "
        SELECT COUNT(*) AS paid_orders
        FROM orders
        WHERE payment_status = 'paid'
    ";

    $result = $conn->query($sql);

    if (!$result) {
        throw new Exception(
            "Unable to calculate paid orders: " .
            $conn->error
        );
    }

    $row = $result->fetch_assoc();

    $paidOrders = (int)(
        $row['paid_orders'] ?? 0
    );


    /**
     * ======================================================
     * PARTIAL ORDERS
     * ======================================================
     */

    $sql = "
        SELECT COUNT(*) AS partial_orders
        FROM orders
        WHERE payment_status = 'partial'
    ";

    $result = $conn->query($sql);

    if (!$result) {
        throw new Exception(
            "Unable to calculate partial orders: " .
            $conn->error
        );
    }

    $row = $result->fetch_assoc();

    $partialOrders = (int)(
        $row['partial_orders'] ?? 0
    );


    /**
     * ======================================================
     * CANCELLED ORDERS
     * ======================================================
     */

    $sql = "
        SELECT COUNT(*) AS cancelled_orders
        FROM orders
        WHERE payment_status = 'cancelled'
    ";

    $result = $conn->query($sql);

    if (!$result) {
        throw new Exception(
            "Unable to calculate cancelled orders: " .
            $conn->error
        );
    }

    $row = $result->fetch_assoc();

    $cancelledOrders = (int)(
        $row['cancelled_orders'] ?? 0
    );


    /**
     * ======================================================
     * STOCKED OUT ORDERS
     * ======================================================
     *
     * Your stock-out endpoint adds:
     *
     * STOCK_OUT_COMPLETED
     *
     * into orders.notes.
     *
     */

    $sql = "
        SELECT COUNT(*) AS stocked_out
        FROM orders
        WHERE notes LIKE '%STOCK_OUT_COMPLETED%'
    ";

    $result = $conn->query($sql);

    if (!$result) {
        throw new Exception(
            "Unable to calculate stocked out orders: " .
            $conn->error
        );
    }

    $row = $result->fetch_assoc();

    $stockedOut = (int)(
        $row['stocked_out'] ?? 0
    );


    /**
     * ======================================================
     * PENDING TRANSFERS
     * ======================================================
     */

    $pendingTransfers = 0;
    $rejectedTransfers = 0;


    /**
     * Check whether transfers table exists.
     *
     * This prevents the whole dashboard from returning
     * HTTP 500 if the transfers table has not been created.
     *
     */

    $tableCheckSql = "
        SHOW TABLES LIKE 'transfers'
    ";

    $tableCheckResult =
        $conn->query($tableCheckSql);


    if (
        $tableCheckResult &&
        $tableCheckResult->num_rows > 0
    ) {


        /**
         * ==================================================
         * PENDING TRANSFERS
         * ==================================================
         */

        $sql = "
            SELECT COUNT(*) AS pending_transfers
            FROM transfers
            WHERE status = 'pending'
        ";

        $result = $conn->query($sql);

        if (!$result) {
            throw new Exception(
                "Unable to calculate pending transfers: " .
                $conn->error
            );
        }

        $row = $result->fetch_assoc();

        $pendingTransfers = (int)(
            $row['pending_transfers'] ?? 0
        );


        /**
         * ==================================================
         * REJECTED TRANSFERS
         * ==================================================
         */

        $sql = "
            SELECT COUNT(*) AS rejected_transfers
            FROM transfers
            WHERE status = 'rejected'
        ";

        $result = $conn->query($sql);

        if (!$result) {
            throw new Exception(
                "Unable to calculate rejected transfers: " .
                $conn->error
            );
        }

        $row = $result->fetch_assoc();

        $rejectedTransfers = (int)(
            $row['rejected_transfers'] ?? 0
        );

    }


    /**
     * ======================================================
     * BUILD RESPONSE
     * ======================================================
     */

    $summary = [

        "total_orders" =>
            $totalOrders,

        "total_sales" =>
            $totalSales,

        "total_products" =>
            $totalProducts,

        "low_stock" =>
            $lowStock,

        "pending_orders" =>
            $pendingOrders,

        "stocked_out" =>
            $stockedOut,

        "out_of_stock" =>
            $outOfStock,

        "pending_transfers" =>
            $pendingTransfers,

        "paid_orders" =>
            $paidOrders,

        "partial_orders" =>
            $partialOrders,

        "cancelled_orders" =>
            $cancelledOrders,

        "rejected_transfers" =>
            $rejectedTransfers

    ];


    /**
     * ======================================================
     * DEBUG LOG
     * ======================================================
     */

    error_log(
        "Dashboard Summary: " .
        json_encode($summary)
    );


    /**
     * ======================================================
     * SUCCESS
     * ======================================================
     */

    http_response_code(200);

    echo json_encode([

        "status" => true,

        "message" =>
            "Dashboard summary loaded successfully.",

        "data" =>
            $summary

    ]);

    exit;


} catch (Throwable $e) {


    /**
     * ======================================================
     * ERROR LOG
     * ======================================================
     */

    error_log(
        "Dashboard Summary Error: " .
        $e->getMessage()
    );


    /**
     * ======================================================
     * ERROR RESPONSE
     * ======================================================
     */

    http_response_code(500);

    echo json_encode([

        "status" => false,

        "message" =>
            "Unable to load dashboard summary.",

        "error" =>
            $e->getMessage()

    ]);

    exit;

}

?>