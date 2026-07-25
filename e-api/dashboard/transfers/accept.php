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
header("Access-Control-Allow-Methods: POST, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    http_response_code(405);

    echo json_encode([
        "status" => false,
        "message" => "Method Not Allowed"
    ]);

    exit;
}

/*
|--------------------------------------------------------------------------
| DATABASE
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/../../../config/dbconn.php";
require_once __DIR__ . "/../../middleware/auth.php";

/*
|--------------------------------------------------------------------------
| AUTHENTICATION
|--------------------------------------------------------------------------
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

$adminId = (int)$user["admin_id"];
/*
|--------------------------------------------------------------------------
| REQUEST DATA
|--------------------------------------------------------------------------
*/

$data = json_decode(file_get_contents("php://input"), true);

$transferId = isset($data["id"])
    ? (int)$data["id"]
    : 0;

if ($transferId <= 0) {

    echo json_encode([
        "status" => false,
        "message" => "Transfer ID is required."
    ]);

    exit;
}

try {

    /*
    |--------------------------------------------------------------------------
    | START TRANSACTION
    |--------------------------------------------------------------------------
    */

    $conn->begin_transaction();

    /*
    |--------------------------------------------------------------------------
    | GET TRANSFER
    |--------------------------------------------------------------------------
    */
$conn->begin_transaction();

/*
|--------------------------------------------------------------------------
| GET TRANSFER
|--------------------------------------------------------------------------
*/

$sql = "
SELECT
    id,
    product_id,
    store_id,
    quantity,
    status
FROM product_transfers
WHERE id = ?
LIMIT 1
";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    throw new Exception($conn->error);
}

$stmt->bind_param("i", $transferId);

if (!$stmt->execute()) {
    throw new Exception($stmt->error);
}

$result = $stmt->get_result();

if ($result->num_rows === 0) {
    throw new Exception("Transfer not found.");
}

$transfer = $result->fetch_assoc();

$stmt->close();

if ($transfer["status"] !== "pending") {
    throw new Exception("Transfer has already been processed.");
}

/*
|--------------------------------------------------------------------------
| CHECK INVENTORY
|--------------------------------------------------------------------------
*/

$check = "
SELECT id
FROM store_inventory
WHERE store_id = ?
AND product_id = ?
LIMIT 1
";

$stmt = $conn->prepare($check);

if (!$stmt) {
    throw new Exception($conn->error);
}

$stmt->bind_param(
    "ii",
    $transfer["store_id"],
    $transfer["product_id"]
);

if (!$stmt->execute()) {
    throw new Exception($stmt->error);
}

$inventoryResult = $stmt->get_result();

$stmt->close();

/*
|--------------------------------------------------------------------------
| UPDATE OR INSERT INVENTORY
|--------------------------------------------------------------------------
*/

if ($inventoryResult->num_rows > 0) {

    $inventory = $inventoryResult->fetch_assoc();

    $updateInventory = "
    UPDATE store_inventory
    SET quantity = quantity + ?
    WHERE id = ?
    ";

    $stmt = $conn->prepare($updateInventory);

    if (!$stmt) {
        throw new Exception($conn->error);
    }

    $stmt->bind_param(
        "ii",
        $transfer["quantity"],
        $inventory["id"]
    );

    if (!$stmt->execute()) {
        throw new Exception($stmt->error);
    }

    if ($stmt->affected_rows < 1) {
        throw new Exception("Inventory update failed.");
    }

    $stmt->close();

} else {

    $insertInventory = "
    INSERT INTO store_inventory
    (
        store_id,
        product_id,
        quantity
    )
    VALUES
    (
        ?, ?, ?
    )
    ";

    $stmt = $conn->prepare($insertInventory);

    if (!$stmt) {
        throw new Exception($conn->error);
    }

    $stmt->bind_param(
        "iii",
        $transfer["store_id"],
        $transfer["product_id"],
        $transfer["quantity"]
    );

    if (!$stmt->execute()) {
        throw new Exception($stmt->error);
    }

    if ($stmt->affected_rows < 1) {
        throw new Exception("Inventory insert failed.");
    }

    $stmt->close();
}

/*
|--------------------------------------------------------------------------
| MARK TRANSFER ACCEPTED
|--------------------------------------------------------------------------
*/

$updateTransfer = "
UPDATE product_transfers
SET
    status = 'accepted',
    reviewed_by = ?,
    reviewed_at = NOW()
WHERE id = ?
";

$stmt = $conn->prepare($updateTransfer);

if (!$stmt) {
    throw new Exception($conn->error);
}

$stmt->bind_param(
    "ii",
    $adminId,
    $transferId
);

if (!$stmt->execute()) {
    throw new Exception($stmt->error);
}

$stmt->close();

/*
|--------------------------------------------------------------------------
| COMMIT
|--------------------------------------------------------------------------
*/

$conn->commit();

echo json_encode([
    "status" => true,
    "message" => "Transfer accepted successfully.",
    "data" => [
        "transfer_id" => $transferId,
        "store_id" => $transfer["store_id"],
        "product_id" => $transfer["product_id"],
        "quantity" => $transfer["quantity"],
        "reviewed_by" => $adminId
    ]
]);

} catch (Exception $e) {

    $conn->rollback();

    http_response_code(500);

    echo json_encode([

        "status" => false,

        "message" => $e->getMessage()

    ]);

}

$conn->close();

exit;