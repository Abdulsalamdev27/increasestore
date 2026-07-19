<?php

ini_set('display_errors',1);
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

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {

    http_response_code(405);

    echo json_encode([
        "status"=>false,
        "message"=>"Method Not Allowed"
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
| AUTH
|--------------------------------------------------------------------------
*/

$user = $GLOBALS['authUser'] ?? null;

if(!$user){

    http_response_code(401);

    echo json_encode([
        "status"=>false,
        "message"=>"Unauthorized"
    ]);

    exit;
}

/*
|--------------------------------------------------------------------------
| ORDER ID
|--------------------------------------------------------------------------
*/

$orderId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if($orderId <=0){

    echo json_encode([
        "status"=>false,
        "message"=>"Invalid order id."
    ]);

    exit;
}

/*
|--------------------------------------------------------------------------
| FETCH ORDER
|--------------------------------------------------------------------------
*/

$sql="

SELECT

o.*,

s.store_name,

CONCAT(a.firstName,' ',a.lastName) AS cashier

FROM orders o

LEFT JOIN stores s
ON s.id=o.store_id

LEFT JOIN admins a
ON a.id=o.created_by

WHERE o.id=?

LIMIT 1

";

$stmt=$conn->prepare($sql);

if(!$stmt){

    echo json_encode([
        "status"=>false,
        "message"=>"Prepare failed.",
        "error"=>$conn->error
    ]);

    exit;
}

$stmt->bind_param("i",$orderId);

$stmt->execute();

$orderResult=$stmt->get_result();

if($orderResult->num_rows==0){

    echo json_encode([
        "status"=>false,
        "message"=>"Order not found."
    ]);

    exit;
}

$order=$orderResult->fetch_assoc();

$stmt->close();

/*
|--------------------------------------------------------------------------
| FETCH ITEMS
|--------------------------------------------------------------------------
*/

$itemSql="

SELECT

oi.id,

oi.product_id,

oi.quantity,

oi.unit_price,

oi.subtotal,

p.product_name,

p.barcode,

p.sku,

p.category

FROM order_items oi

INNER JOIN products p
ON p.id=oi.product_id

WHERE oi.order_id=?

ORDER BY oi.id ASC

";

$itemStmt=$conn->prepare($itemSql);

$itemStmt->bind_param("i",$orderId);

$itemStmt->execute();

$itemResult=$itemStmt->get_result();

$items=[];

while($row=$itemResult->fetch_assoc()){

    $items[]=[

        "id"=>(int)$row["id"],

        "product_id"=>(int)$row["product_id"],

        "product_name"=>$row["product_name"],

        "barcode"=>$row["barcode"],

        "sku"=>$row["sku"],

        "category"=>$row["category"],

        "quantity"=>(int)$row["quantity"],

        "unit_price"=>(float)$row["unit_price"],

        "subtotal"=>(float)$row["subtotal"]

    ];

}

$itemStmt->close();

$conn->close();

/*
|--------------------------------------------------------------------------
| RESPONSE
|--------------------------------------------------------------------------
*/

echo json_encode([

    "status"=>true,

    "message"=>"Order retrieved successfully.",

    "data"=>[

        "order"=>[

            "id"=>(int)$order["id"],

            "order_number"=>$order["order_number"],

            "store_id"=>(int)$order["store_id"],

            "store_name"=>$order["store_name"],

            "customer_name"=>$order["customer_name"],

            "customer_phone"=>$order["customer_phone"],

            "customer_email"=>$order["customer_email"],

            "payment_method"=>$order["payment_method"],

            "payment_status"=>$order["payment_status"],

            "total"=>(float)$order["total"],

            "cashier"=>$order["cashier"],

            "created_at"=>$order["created_at"]

        ],

        "items"=>$items,

        "total_items"=>count($items)

    ]

]);

exit;