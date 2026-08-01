<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

/**
 * -------------------------
 * HEADERS
 * -------------------------
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Methods: POST, OPTIONS');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'status' => false,
        'message' => 'Method not allowed'
    ]);
    exit;
}

/**
 * -------------------------
 * DATABASE & AUTH
 * -------------------------
 */

require_once __DIR__ . '/../../../config/dbconn.php';
require_once __DIR__ . '/../../middleware/auth.php';

/**
 * -------------------------
 * AUTH USER
 * -------------------------
 */

$user = $GLOBALS['authUser'] ?? null;

if (!$user) {
    http_response_code(401);
    echo json_encode([
        'status' => false,
        'message' => 'Unauthorized'
    ]);
    exit;
}

$adminId = (int)($user['admin_id'] ?? 0);

if ($adminId <= 0) {
    http_response_code(401);
    echo json_encode([
        'status' => false,
        'message' => 'Invalid admin session'
    ]);
    exit;
}

/**
 * -------------------------
 * READ JSON INPUT
 * -------------------------
 */

$data = json_decode(file_get_contents('php://input'), true);

if (!is_array($data)) {
    http_response_code(400);
    echo json_encode([
        'status' => false,
        'message' => 'Invalid JSON payload'
    ]);
    exit;
}

/**
 * -------------------------
 * GET INPUT
 * -------------------------
 */

$customerName  = trim($data['customer_name'] ?? '');
$customerPhone = trim($data['customer_phone'] ?? '');
$customerEmail = trim($data['customer_email'] ?? '');
$paymentMethod = trim($data['payment_method'] ?? '');
$paymentStatus = trim($data['payment_status'] ?? 'pending');
$notes         = trim($data['notes'] ?? '');

$subtotal    = (float)($data['subtotal'] ?? 0);
$discount    = (float)($data['discount'] ?? 0);
$tax         = (float)($data['tax'] ?? 0);
$shipping    = (float)($data['shipping'] ?? 0);
$totalAmount = (float)($data['total_amount'] ?? 0);
$amountPaid  = (float)($data['amount_paid'] ?? 0);
$balance     = (float)($data['balance'] ?? 0);

$items = $data['items'] ?? [];

/**
 * -------------------------
 * VALIDATION
 * -------------------------
 */

if ($customerName === '') {
    http_response_code(400);
    echo json_encode([
        'status' => false,
        'message' => 'Customer name is required.'
    ]);
    exit;
}

if ($customerPhone === '') {
    http_response_code(400);
    echo json_encode([
        'status' => false,
        'message' => 'Customer phone is required.'
    ]);
    exit;
}

if ($paymentMethod === '') {
    http_response_code(400);
    echo json_encode([
        'status' => false,
        'message' => 'Payment method is required.'
    ]);
    exit;
}

if (!is_array($items) || count($items) === 0) {
    http_response_code(400);
    echo json_encode([
        'status' => false,
        'message' => 'Order cart is empty.'
    ]);
    exit;
}

if ($totalAmount <= 0) {
    http_response_code(400);
    echo json_encode([
        'status' => false,
        'message' => 'Invalid total amount.'
    ]);
    exit;
}

/**
 * -------------------------
 * VALIDATE ITEMS
 * -------------------------
 */

foreach ($items as $index => $item) {

    $productId = (int)($item['product_id'] ?? 0);
    $quantity  = (int)($item['quantity'] ?? 0);
    $price     = (float)($item['selling_price'] ?? 0);

    if ($productId <= 0) {
        http_response_code(400);
        echo json_encode([
            'status' => false,
            'message' => 'Invalid product ID on item ' . ($index + 1)
        ]);
        exit;
    }

    if ($quantity <= 0) {
        http_response_code(400);
        echo json_encode([
            'status' => false,
            'message' => 'Invalid quantity on item ' . ($index + 1)
        ]);
        exit;
    }

    if ($price < 0) {
        http_response_code(400);
        echo json_encode([
            'status' => false,
            'message' => 'Invalid selling price on item ' . ($index + 1)
        ]);
        exit;
    }
}

/**
 * -------------------------
 * BEGIN TRANSACTION
 * -------------------------
 */

$conn->begin_transaction();

try {

    /**
     * -------------------------
     * GENERATE ORDER NUMBER
     * -------------------------
     */

    $orderNo = 'ORD-' . date('YmdHis') . '-' . mt_rand(1000, 9999);

    /**
     * -------------------------
     * CREATE ORDER
     * -------------------------
     */

    $orderSql = '
        INSERT INTO orders
        (
            order_no,
            customer_name,
            customer_phone,
            customer_email,
            payment_method,
            payment_status,
            subtotal,
            discount,
            tax,
            shipping,
            total_amount,
            amount_paid,
            balance,
            notes,
            created_by
        )
        VALUES
        (
            ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
        )
    ';

    $orderStmt = $conn->prepare($orderSql);

    if (!$orderStmt) {
        throw new Exception('Database prepare failed: ' . $conn->error);
    }

    $orderStmt->bind_param(
        'ssssssdddddddsi',
        $orderNo,
        $customerName,
        $customerPhone,
        $customerEmail,
        $paymentMethod,
        $paymentStatus,
        $subtotal,
        $discount,
        $tax,
        $shipping,
        $totalAmount,
        $amountPaid,
        $balance,
        $notes,
        $adminId
    );

    if (!$orderStmt->execute()) {
        throw new Exception('Unable to create order: ' . $orderStmt->error);
    }

    $orderId = $orderStmt->insert_id;

    $orderStmt->close();

    /**
     * -------------------------
     * PREPARE ORDER ITEM INSERT
     * -------------------------
     */

    $itemStmt = $conn->prepare('
        INSERT INTO order_items
        (
            order_id,
            product_id,
            quantity,
            selling_price,
            total
        )
        VALUES
        (
            ?, ?, ?, ?, ?
        )
    ');

    if (!$itemStmt) {
        throw new Exception('Unable to prepare order item statement: ' . $conn->error);
    }

    /**
     * -------------------------
     * PREPARE STOCK UPDATE
     * -------------------------
     */

    $updateStock = $conn->prepare('
        UPDATE products
        SET quantity = quantity - ?
        WHERE id = ?
    ');

    if (!$updateStock) {
        throw new Exception('Unable to prepare stock update statement: ' . $conn->error);
    }

    /**
     * -------------------------
     * PROCESS ITEMS
     * -------------------------
     */

    foreach ($items as $item) {

        $productId = (int)$item['product_id'];
        $quantity  = (int)$item['quantity'];
        $price     = (float)$item['selling_price'];
        $lineTotal = (float)($item['total'] ?? ($quantity * $price));

        /**
         * CHECK PRODUCT STOCK
         */

        $stockCheck = $conn->prepare('
            SELECT quantity
            FROM products
            WHERE id = ?
            LIMIT 1
        ');

        if (!$stockCheck) {
            throw new Exception('Unable to prepare stock check statement: ' . $conn->error);
        }

        $stockCheck->bind_param('i', $productId);
        $stockCheck->execute();

        $result = $stockCheck->get_result();

        if ($result->num_rows === 0) {
            $stockCheck->close();
            throw new Exception('Product not found.');
        }

        $product = $result->fetch_assoc();

        $stockCheck->close();

        if ($quantity > (int)$product['quantity']) {
            throw new Exception('Insufficient stock for product ID ' . $productId);
        }

        /**
         * INSERT ORDER ITEM
         */

        $itemStmt->bind_param(
            'iiidd',
            $orderId,
            $productId,
            $quantity,
            $price,
            $lineTotal
        );

        if (!$itemStmt->execute()) {
            throw new Exception('Unable to save order item: ' . $itemStmt->error);
        }

        /**
         * UPDATE STOCK
         */

        $updateStock->bind_param('ii', $quantity, $productId);

        if (!$updateStock->execute()) {
            throw new Exception('Unable to update stock: ' . $updateStock->error);
        }
    }

    $itemStmt->close();
    $updateStock->close();

    /**
     * -------------------------
     * COMMIT
     * -------------------------
     */

    $conn->commit();

    echo json_encode([
        'status' => true,
        'message' => 'Order created successfully.',
        'order_id' => $orderId,
        'order_no' => $orderNo
    ]);

} catch (Exception $e) {

    $conn->rollback();

    http_response_code(500);

    echo json_encode([
        'status' => false,
        'message' => $e->getMessage()
    ]);
}

$conn->close();
exit;