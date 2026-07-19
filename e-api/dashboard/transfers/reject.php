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

$remarks = trim($data["remarks"] ?? "");

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
     * DO NOT REJECT TWICE
     * ---------------------------------------
     */

    if ($transfer["status"] === "rejected") {

        throw new Exception("Transfer has already been rejected.");

    }

    /**
     * ---------------------------------------
     * UPDATE TRANSFER TO REJECTED
     * ---------------------------------------
     */

    $updateSql = "

        UPDATE product_transfers

        SET

            status = 'rejected',

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

        "sii",

        $remarks,

        $adminId,

        $id

    );

    if (!$updateStmt->execute()) {

        throw new Exception($updateStmt->error);

    }

    $updateStmt->close();

    /**
     * ---------------------------------------
     * IF TRANSFER WAS ALREADY ACCEPTED
     * REMOVE THE QUANTITY FROM STORE INVENTORY
     * ---------------------------------------
     */

    if ($transfer["status"] === "accepted") {

        $inventorySql = "

            UPDATE store_inventory

            SET quantity = quantity - ?

            WHERE

                store_id = ?

            AND

                product_id = ?

        ";

        $inventoryStmt = $conn->prepare($inventorySql);

        if (!$inventoryStmt) {

            throw new Exception($conn->error);

        }

        $inventoryStmt->bind_param(

            "iii",

            $transfer["quantity"],

            $transfer["store_id"],

            $transfer["product_id"]

        );

        if (!$inventoryStmt->execute()) {

            throw new Exception($inventoryStmt->error);

        }

        $inventoryStmt->close();

    }

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

    echo json_encode([

        "status" => true,

        "message" => "Transfer rejected successfully.",

        "data" => [

            "id" => $id,

            "status" => "rejected",

            "reviewed_by" => $adminId,

            "reviewed_at" => date("Y-m-d H:i:s")

        ]

    ]);

} catch (Exception $e) {

    /**
     * ---------------------------------------
     * ROLLBACK
     * ---------------------------------------
     */

    $conn->rollback();

    http_response_code(500);

    echo json_encode([

        "status" => false,

        "message" => "Failed to reject transfer.",

        "error" => $e->getMessage()

    ]);

}

/**
 * ---------------------------------------
 * CLOSE CONNECTION
 * ---------------------------------------
 */

$conn->close();

exit;