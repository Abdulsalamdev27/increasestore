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
header("Access-Control-Allow-Methods: POST, OPTIONS");

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(200);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {

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

$adminId = (int)$user["user_id"];

if (!$user) {

    http_response_code(401);

    echo json_encode([
        "status" => false,
        "message" => "Unauthorized."
    ]);

    exit;
}

$user = $GLOBALS["authUser"] ?? null;

if (!$user) {

    http_response_code(401);

    echo json_encode([
        "status" => false,
        "message" => "Unauthorized."
    ]);

    exit;
}

$adminId = (int)$user["admin_id"];
/**
 * ---------------------------------------
 * REQUEST BODY
 * ---------------------------------------
 */

$data = json_decode(file_get_contents("php://input"), true);

$id = isset($data["id"])
    ? (int)$data["id"]
    : 0;

/**
 * ---------------------------------------
 * VALIDATION
 * ---------------------------------------
 */

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
 * BEGIN TRANSACTION
 * ---------------------------------------
 */

$conn->begin_transaction();

try {

    /**
     * ---------------------------------------
     * LOAD TRANSFER
     * ---------------------------------------
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

    $stmt->bind_param("i", $id);

    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows === 0) {

        throw new Exception("Transfer not found.");

    }

    $transfer = $result->fetch_assoc();

    $stmt->close();

/**
 * ---------------------------------------
 * DELETE RULES
 * ---------------------------------------
 */

/*
|--------------------------------------------------------------------------
| Prevent deleting accepted transfers
|--------------------------------------------------------------------------
|
| If you prefer to allow deletion of accepted transfers,
| remove this block and adjust the inventory first.
|
*/

if ($transfer["status"] === "accepted") {

    throw new Exception(
        "Accepted transfers cannot be deleted. Reject or return the transfer instead."
    );

}

/**
 * ---------------------------------------
 * DELETE TRANSFER
 * ---------------------------------------
 */

$deleteSql = "

    DELETE FROM product_transfers

    WHERE id = ?

    LIMIT 1

";

$deleteStmt = $conn->prepare($deleteSql);

if (!$deleteStmt) {

    throw new Exception($conn->error);

}

$deleteStmt->bind_param(

    "i",

    $id

);

if (!$deleteStmt->execute()) {

    throw new Exception($deleteStmt->error);

}

$deleteStmt->close();

/**
 * ---------------------------------------
 * INVENTORY HANDLING
 * ---------------------------------------
 */

if ($transfer["status"] === "accepted") {

    /**
     * ---------------------------------------
     * CHECK STORE INVENTORY
     * ---------------------------------------
     */

    $inventorySql = "

        SELECT
            id,
            quantity

        FROM store_inventory

        WHERE
            store_id = ?
        AND
            product_id = ?

        LIMIT 1

    ";

    $inventoryStmt = $conn->prepare($inventorySql);

    if (!$inventoryStmt) {

        throw new Exception($conn->error);

    }

    $inventoryStmt->bind_param(

        "ii",

        $transfer["store_id"],

        $transfer["product_id"]

    );

    $inventoryStmt->execute();

    $inventoryResult = $inventoryStmt->get_result();

    if ($inventoryResult->num_rows === 0) {

        throw new Exception("Store inventory record not found.");

    }

    $inventory = $inventoryResult->fetch_assoc();

    $inventoryStmt->close();

    /**
     * ---------------------------------------
     * ENSURE SUFFICIENT STOCK
     * ---------------------------------------
     */

    if ($inventory["quantity"] < $transfer["quantity"]) {

        throw new Exception(
            "Cannot delete transfer because inventory quantity is insufficient."
        );

    }

    /**
     * ---------------------------------------
     * REMOVE INVENTORY
     * ---------------------------------------
     */

    $updateInventorySql = "

        UPDATE store_inventory

        SET
            quantity = quantity - ?

        WHERE
            store_id = ?
        AND
            product_id = ?

    ";

    $updateInventoryStmt = $conn->prepare($updateInventorySql);

    if (!$updateInventoryStmt) {

        throw new Exception($conn->error);

    }

    $updateInventoryStmt->bind_param(

        "iii",

        $transfer["quantity"],

        $transfer["store_id"],

        $transfer["product_id"]

    );

    if (!$updateInventoryStmt->execute()) {

        throw new Exception($updateInventoryStmt->error);

    }

    $updateInventoryStmt->close();

}

/**
 * ---------------------------------------
 * DELETE TRANSFER
 * ---------------------------------------
 */

$deleteSql = "

    DELETE FROM product_transfers

    WHERE id = ?

    LIMIT 1

";

$deleteStmt = $conn->prepare($deleteSql);

if (!$deleteStmt) {

    throw new Exception($conn->error);

}

$deleteStmt->bind_param(

    "i",

    $id

);

if (!$deleteStmt->execute()) {

    throw new Exception($deleteStmt->error);

}

$deleteStmt->close();

/**
 * ---------------------------------------
 * COMMIT TRANSACTION
 * ---------------------------------------
 */

$conn->commit();

/**
 * ---------------------------------------
 * SUCCESS RESPONSE
 * ---------------------------------------
 */

http_response_code(200);

echo json_encode([

    "status" => true,

    "message" => "Transfer deleted successfully.",

    "data" => [

        "id" => $id

    ]

]);

} catch (Exception $e) {

    /**
     * ---------------------------------------
     * ROLLBACK TRANSACTION
     * ---------------------------------------
     */

    if ($conn) {

        $conn->rollback();

    }

    http_response_code(500);

    echo json_encode([

        "status" => false,

        "message" => "Failed to delete transfer.",

        "error" => $e->getMessage()

    ]);

} finally {

    /**
     * ---------------------------------------
     * CLOSE DATABASE CONNECTION
     * ---------------------------------------
     */

    if ($conn) {

        $conn->close();

    }

}

exit;