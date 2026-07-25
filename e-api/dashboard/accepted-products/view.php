<?php

ini_set('display_errors',1);
error_reporting(E_ALL);

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: GET, OPTIONS");

if($_SERVER["REQUEST_METHOD"]==="OPTIONS"){
    http_response_code(200);
    exit;
}

if($_SERVER["REQUEST_METHOD"]!=="GET"){
    http_response_code(405);
    echo json_encode([
        "status"=>false,
        "message"=>"Method not allowed."
    ]);
    exit;
}

require_once __DIR__ . "/../../../config/dbconn.php";
require_once __DIR__ . "/../../middleware/auth.php";

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

$user = $GLOBALS["authUser"] ?? null;

if(!$user){
    http_response_code(401);
    echo json_encode([
        "status"=>false,
        "message"=>"Unauthorized."
    ]);
    exit;
}

/*
|--------------------------------------------------------------------------
| INVENTORY ID
|--------------------------------------------------------------------------
*/

$inventoryId = isset($_GET["id"])
    ? (int)$_GET["id"]
    : 0;

if($inventoryId <= 0){

    http_response_code(400);

    echo json_encode([
        "status"=>false,
        "message"=>"Inventory ID is required."
    ]);

    exit;
}

/*
|--------------------------------------------------------------------------
| QUERY
|--------------------------------------------------------------------------
*/

$sql = "

SELECT

    si.id AS inventory_id,
    si.quantity,
    si.created_at,
    si.updated_at,

    p.id AS product_id,
    p.product_name,
    p.barcode,
    p.sku,
    p.category,
    p.description,
    p.unit,
    p.cost_price,
    p.selling_price,
    p.quantity AS total_stock,
    p.minimum_stock,
    p.status,

    s.id AS store_id,
    s.store_name,
    s.address,
    s.phone,
    s.email

FROM store_inventory si

INNER JOIN products p
    ON p.id = si.product_id

INNER JOIN stores s
    ON s.id = si.store_id

WHERE si.id = ?

LIMIT 1

";

$stmt = $conn->prepare($sql);

if (!$stmt) {

    http_response_code(500);

    echo json_encode([
        "status" => false,
        "message" => "Failed to prepare query.",
        "error" => $conn->error
    ]);

    exit;
}

$stmt->bind_param("i",$inventoryId);

$stmt->execute();

$result = $stmt->get_result();

if($result->num_rows===0){

    http_response_code(404);

    echo json_encode([
        "status"=>false,
        "message"=>"Accepted product not found."
    ]);

    exit;
}

$row = $result->fetch_assoc();

$response = [

    "inventory_id"=>(int)$row["inventory_id"],

    "quantity"=>(int)$row["quantity"],

    "created_at"=>$row["created_at"],

    "updated_at"=>$row["updated_at"],

    "product"=>[

        "id"=>(int)$row["product_id"],

        "name"=>$row["product_name"],

        "barcode"=>$row["barcode"],

        "sku"=>$row["sku"],

        "category"=>$row["category"],

        "description"=>$row["description"],

        "unit"=>$row["unit"],

        "cost_price"=>(float)$row["cost_price"],

        "selling_price"=>(float)$row["selling_price"],

        "quantity"=>(int)$row["total_stock"],

        "minimum_stock"=>(int)$row["minimum_stock"],

        "status"=>$row["status"],


    ],

    "store"=>[

        "id"=>(int)$row["store_id"],

        "name"=>$row["store_name"],

        "address"=>$row["address"],

        "phone"=>$row["phone"],

        "email"=>$row["email"]

    ]

];

$stmt->close();
$conn->close();

echo json_encode([

    "status"=>true,

    "message"=>"Accepted product retrieved successfully.",

    "data"=>$response

],JSON_UNESCAPED_SLASHES);

exit;