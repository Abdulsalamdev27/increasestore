<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

/**
 * ---------------------------------
 * HEADERS
 * ---------------------------------
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
 * ---------------------------------
 * DATABASE
 * ---------------------------------
 */

require_once __DIR__ . "/../../../config/dbconn.php";
require_once __DIR__ . "/../../middleware/auth.php";

/**
 * ---------------------------------
 * AUTH USER
 * ---------------------------------
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

$adminId = (int)$user["id"];

/**
 * ---------------------------------
 * REQUEST BODY
 * ---------------------------------
 */

$data = json_decode(file_get_contents("php://input"), true);

$id = isset($data["id"])
    ? (int)$data["id"]
    : 0;

$quantity = isset($data["quantity"])
    ? (int)$data["quantity"]
    : 0;

$status = trim($data["status"] ?? "");

$remarks = trim($data["remarks"] ?? "");

/**
 * ---------------------------------
 * VALIDATION
 * ---------------------------------
 */

if ($id <= 0) {

    http_response_code(422);

    echo json_encode([
        "status" => false,
        "message" => "Invalid transfer ID."
    ]);

    exit;
}

if ($quantity <= 0) {

    http_response_code(422);

    echo json_encode([
        "status" => false,
        "message" => "Quantity must be greater than zero."
    ]);

    exit;
}

$allowedStatus = [
    "pending",
    "accepted",
    "rejected"
];

if (!in_array($status, $allowedStatus)) {

    http_response_code(422);

    echo json_encode([
        "status" => false,
        "message" => "Invalid transfer status."
    ]);

    exit;
}

/**
 * ---------------------------------
 * BEGIN TRANSACTION
 * ---------------------------------
 */

$conn->begin_transaction();

try {

    /**
     * ---------------------------------
     * LOAD EXISTING TRANSFER
     * ---------------------------------
     */

    $sql = "

        SELECT

            id,
            product_id,
            store_id,
            quantity,
            status,
            remarks

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
     * ---------------------------------
     * STORE OLD VALUES
     * ---------------------------------
     */

    $oldStatus = $transfer["status"];

    $oldQuantity = (int)$transfer["quantity"];

    $productId = (int)$transfer["product_id"];

    $storeId = (int)$transfer["store_id"];

    /**
     * ---------------------------------
     * UPDATE TRANSFER
     * ---------------------------------
     */

    $updateSql = "

        UPDATE product_transfers

        SET

            quantity = ?,

            status = ?,

            remarks = ?,

            reviewed_by = ?,

            reviewed_at = NOW()

        WHERE id = ?

    ";

    $updateStmt = $conn->prepare($updateSql);

    if (!$updateStmt) {

        throw new Exception($conn->error);

    }

    $updateStmt->bind_param(

        "issii",

        $quantity,

        $status,

        $remarks,

        $adminId,

        $id

    );

    if (!$updateStmt->execute()) {

        throw new Exception($updateStmt->error);

    }

    $updateStmt->close();

    /**
     * ---------------------------------
     * TRANSFER UPDATED
     * ---------------------------------
     *
     * Variables available for Part 3:
     *
     * $oldStatus
     * $status
     * $oldQuantity
     * $quantity
     * $storeId
     * $productId
     *
    /**
     * ---------------------------------
     * UPDATE STORE INVENTORY
     * ---------------------------------
     */

    if ($oldStatus !== "accepted" && $status === "accepted") {

        /*
        |------------------------------------------
        | First time accepting this transfer
        |------------------------------------------
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

            $storeId,

            $productId

        );

        $inventoryStmt->execute();

        $inventoryResult = $inventoryStmt->get_result();

        if ($inventoryResult->num_rows > 0) {

            $inventory = $inventoryResult->fetch_assoc();

            $inventoryStmt->close();

            $updateInventory = "

                UPDATE store_inventory

                SET

                    quantity = quantity + ?

                WHERE id = ?

            ";

            $inventoryStmt = $conn->prepare($updateInventory);

            if (!$inventoryStmt) {

                throw new Exception($conn->error);

            }

            $inventoryStmt->bind_param(

                "ii",

                $quantity,

                $inventory["id"]

            );

            $inventoryStmt->execute();

            $inventoryStmt->close();

        } else {

            $inventoryStmt->close();

            $insertInventory = "

                INSERT INTO store_inventory(

                    store_id,

                    product_id,

                    quantity

                )

                VALUES(

                    ?, ?, ?

                )

            ";

            $inventoryStmt = $conn->prepare($insertInventory);

            if (!$inventoryStmt) {

                throw new Exception($conn->error);

            }

            $inventoryStmt->bind_param(

                "iii",

                $storeId,

                $productId,

                $quantity

            );

            $inventoryStmt->execute();

            $inventoryStmt->close();

        }

    }

    /*
    |------------------------------------------
    | Quantity changed after acceptance
    |------------------------------------------
    */

    elseif ($oldStatus === "accepted" && $status === "accepted") {

        $difference = $quantity - $oldQuantity;

        if ($difference != 0) {

            $adjustInventory = "

                UPDATE store_inventory

                SET quantity = quantity + ?

                WHERE

                    store_id = ?

                AND

                    product_id = ?

            ";

            $adjustStmt = $conn->prepare($adjustInventory);

            if (!$adjustStmt) {

                throw new Exception($conn->error);

            }

            $adjustStmt->bind_param(

                "iii",

                $difference,

                $storeId,

                $productId

            );

            $adjustStmt->execute();

            $adjustStmt->close();

        }

    }

    /*
    |------------------------------------------
    | Accepted -> Rejected
    | Remove stock previously added
    |------------------------------------------
    */

    elseif ($oldStatus === "accepted" && $status === "rejected") {

        $removeInventory = "

            UPDATE store_inventory

            SET quantity = quantity - ?

            WHERE

                store_id = ?

            AND

                product_id = ?

        ";

        $removeStmt = $conn->prepare($removeInventory);

        if (!$removeStmt) {

            throw new Exception($conn->error);

        }

        $removeStmt->bind_param(

            "iii",

            $oldQuantity,

            $storeId,

            $productId

        );

        $removeStmt->execute();

        $removeStmt->close();

    }

    /**
     * ---------------------------------
     * NEXT:
     * Part 4
     * Commit Transaction
     * Return JSON
     * Rollback on Error
     * ---------------------------------
     */

    /**
     * ---------------------------------
     * COMMIT TRANSACTION
     * ---------------------------------
     */

    $conn->commit();

    /**
     * ---------------------------------
     * SUCCESS RESPONSE
     * ---------------------------------
     */

    echo json_encode([

        "status" => true,

        "message" => "Transfer updated successfully.",

        "data" => [

            "id" => $id,

            "status" => $status,

            "quantity" => $quantity

        ]

    ]);

} catch (Exception $e) {

    /**
     * ---------------------------------
     * ROLLBACK
     * ---------------------------------
     */

    $conn->rollback();

    http_response_code(500);

    echo json_encode([

        "status" => false,

        "message" => "Transfer update failed.",

        "error" => $e->getMessage()

    ]);

}

/**
 * ---------------------------------
 * CLOSE DATABASE
 * ---------------------------------
 */

$conn->close();

exit;