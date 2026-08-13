<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);


/*
|--------------------------------------------------------------------------
| DATABASE
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/../../config/dbconn.php";
require_once __DIR__ . "/../../middleware/authentication.php";


/*
|--------------------------------------------------------------------------
| AUTHENTICATION
|--------------------------------------------------------------------------
| Use the shared JWT page guard.
|--------------------------------------------------------------------------
*/

$user = jwtPageGuard();

if (!$user) {
    die("Unauthorized");
}


/*
|--------------------------------------------------------------------------
| ORDER ID
|--------------------------------------------------------------------------
*/

$orderId = isset($_GET['id'])
    ? (int) $_GET['id']
    : 0;

if ($orderId <= 0) {
    die("Invalid Order ID.");
}


/*
|--------------------------------------------------------------------------
| FETCH ORDER
|--------------------------------------------------------------------------
| Based on the actual orders table schema provided.
|--------------------------------------------------------------------------
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
    o.updated_at

FROM orders o

WHERE o.id = ?

LIMIT 1

";


$stmt = $conn->prepare($sql);

if (!$stmt) {

    die(
        "Unable to prepare order query: " .
        $conn->error
    );

}


$stmt->bind_param(
    "i",
    $orderId
);


if (!$stmt->execute()) {

    die(
        "Unable to fetch order: " .
        $stmt->error
    );

}


$result = $stmt->get_result();


if ($result->num_rows === 0) {

    $stmt->close();

    die("Order not found.");

}


$order = $result->fetch_assoc();


$stmt->close();


/*
|--------------------------------------------------------------------------
| FETCH ORDER ITEMS
|--------------------------------------------------------------------------
| Based on the actual order_items schema provided.
|--------------------------------------------------------------------------
*/

$itemSql = "

SELECT

    oi.id,
    oi.order_id,
    oi.store_inventory_id,
    oi.store_id,
    oi.product_id,
    oi.product_name,
    oi.barcode,
    oi.store_name,
    oi.unit_price,
    oi.quantity,
    oi.line_total,
    oi.created_at

FROM order_items oi

WHERE oi.order_id = ?

ORDER BY oi.id ASC

";


$itemStmt = $conn->prepare($itemSql);

if (!$itemStmt) {

    die(
        "Unable to prepare order items query: " .
        $conn->error
    );

}


$itemStmt->bind_param(
    "i",
    $orderId
);


if (!$itemStmt->execute()) {

    die(
        "Unable to fetch order items: " .
        $itemStmt->error
    );

}


$itemResult = $itemStmt->get_result();


$orderItems = [];

$totalItems = 0;
$totalQuantity = 0;


while (
    $row = $itemResult->fetch_assoc()
) {

    $orderItems[] = $row;

    $totalItems++;

    $totalQuantity +=
        (int) (
            $row["quantity"] ?? 0
        );

}


$itemStmt->close();


/*
|--------------------------------------------------------------------------
| HELPER FUNCTIONS
|--------------------------------------------------------------------------
*/

function money($amount)
{
    return number_format(
        (float) $amount,
        2
    );
}


function e($value)
{
    return htmlspecialchars(
        (string) ($value ?? ""),
        ENT_QUOTES,
        'UTF-8'
    );
}


/*
|--------------------------------------------------------------------------
| COMPANY INFORMATION
|--------------------------------------------------------------------------
| Replace these with your actual company details.
|--------------------------------------------------------------------------
*/

$company = [

    "name" =>
        "Increase Original Super Store",

    "address" =>
        "Lagos, Nigeria",

    "phone" =>
        "+234 XXX XXX XXXX",

    "email" =>
        "info@company.com",

    "website" =>
        "www.company.com"

];


/*
|--------------------------------------------------------------------------
| RECEIPT VARIABLES
|--------------------------------------------------------------------------
*/

$receiptNumber =
    trim(
        $order["order_no"] ?? ""
    );

if ($receiptNumber === "") {

    $receiptNumber = "-";

}


/*
|--------------------------------------------------------------------------
| CASHIER
|--------------------------------------------------------------------------
| We only know created_by from the supplied orders schema.
|--------------------------------------------------------------------------
*/

$createdBy =
    $order["created_by"] ?? null;


$cashier = "-";


if (
    $createdBy !== null &&
    $createdBy !== ""
) {

    $cashier =
        "User #" .
        (int) $createdBy;

}


/*
|--------------------------------------------------------------------------
| CUSTOMER
|--------------------------------------------------------------------------
*/

$customerName =
    trim(
        $order["customer_name"] ?? ""
    );


if ($customerName === "") {

    $customerName =
        "Walk-in Customer";

}


$customerPhone =
    trim(
        $order["customer_phone"] ?? ""
    );


if ($customerPhone === "") {

    $customerPhone = "-";

}


$customerEmail =
    trim(
        $order["customer_email"] ?? ""
    );


if ($customerEmail === "") {

    $customerEmail = "-";

}


$customerCode =
    trim(
        $order["customer_code"] ?? ""
    );


if ($customerCode === "") {

    $customerCode = "-";

}


/*
|--------------------------------------------------------------------------
| PAYMENT
|--------------------------------------------------------------------------
*/

$paymentMethod =
    trim(
        $order["payment_method"] ?? ""
    );


if ($paymentMethod === "") {

    $paymentMethod = "-";

}


$paymentStatus =
    trim(
        $order["payment_status"] ?? ""
    );


if ($paymentStatus === "") {

    $paymentStatus = "pending";

}


$paymentStatusDisplay =
    strtoupper(
        $paymentStatus
    );


/*
|--------------------------------------------------------------------------
| FINANCIAL INFORMATION
|--------------------------------------------------------------------------
*/

$subtotal =
    (float) (
        $order["subtotal"] ?? 0
    );


$discount =
    (float) (
        $order["discount"] ?? 0
    );


$tax =
    (float) (
        $order["tax"] ?? 0
    );


$shipping =
    (float) (
        $order["shipping"] ?? 0
    );


$totalAmount =
    (float) (
        $order["total_amount"] ?? 0
    );


$amountPaid =
    (float) (
        $order["amount_paid"] ?? 0
    );


$balance =
    (float) (
        $order["balance"] ?? 0
    );


/*
|--------------------------------------------------------------------------
| CREATED DATE
|--------------------------------------------------------------------------
*/

$createdAt = "-";


if (!empty($order["created_at"])) {

    $timestamp =
        strtotime(
            $order["created_at"]
        );

    if ($timestamp !== false) {

        $createdAt =
            date(
                "d M Y h:i A",
                $timestamp
            );

    }

}


/*
|--------------------------------------------------------------------------
| STORE INFORMATION
|--------------------------------------------------------------------------
| Store comes from order_items.store_name.
|--------------------------------------------------------------------------
*/

$storeNames = [];


foreach (
    $orderItems
    as $item
) {

    $storeName =
        trim(
            $item["store_name"] ?? ""
        );


    if (
        $storeName !== "" &&
        !in_array(
            $storeName,
            $storeNames,
            true
        )
    ) {

        $storeNames[] =
            $storeName;

    }

}


$receiptStoreName =
    !empty($storeNames)
        ? implode(
            ", ",
            $storeNames
        )
        : "-";


/*
|--------------------------------------------------------------------------
| PAYMENT STATUS CSS
|--------------------------------------------------------------------------
*/

$statusClass = "pending";


if (
    strtolower($paymentStatus)
    === "paid"
) {

    $statusClass = "paid";

} elseif (
    strtolower($paymentStatus)
    === "cancelled"
) {

    $statusClass = "cancelled";

} elseif (
    strtolower($paymentStatus)
    === "failed"
) {

    $statusClass = "failed";

}


/*
|--------------------------------------------------------------------------
| DEBUG INFORMATION
|--------------------------------------------------------------------------
| Open browser console to see the data loaded by PHP.
|--------------------------------------------------------------------------
*/

$debugData = [

    "orderId" =>
        $orderId,

    "order" =>
        $order,

    "orderItems" =>
        $orderItems,

    "company" =>
        $company,

    "receiptNumber" =>
        $receiptNumber,

    "cashier" =>
        $cashier,

    "customerName" =>
        $customerName,

    "customerPhone" =>
        $customerPhone,

    "customerEmail" =>
        $customerEmail,

    "paymentMethod" =>
        $paymentMethod,

    "paymentStatus" =>
        $paymentStatus,

    "subtotal" =>
        $subtotal,

    "discount" =>
        $discount,

    "tax" =>
        $tax,

    "shipping" =>
        $shipping,

    "totalAmount" =>
        $totalAmount,

    "amountPaid" =>
        $amountPaid,

    "balance" =>
        $balance,

    "createdAt" =>
        $createdAt,

    "receiptStoreName" =>
        $receiptStoreName,

    "totalItems" =>
        $totalItems,

    "totalQuantity" =>
        $totalQuantity

];

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Receipt #<?= e($receiptNumber) ?>
    </title>


    <link
        rel="stylesheet"
        href="../../assets/css/dashboard.css"
    >


    <style>

        * {
            box-sizing: border-box;
        }


        body {

            font-family:
                Arial,
                Helvetica,
                sans-serif;

            background:
                #f4f6f9;

            margin: 0;

            padding: 30px;

            color: #333;

        }


        .receipt {

            width: 850px;

            max-width: 100%;

            margin: auto;

            background: #fff;

            padding: 35px;

            border-radius: 8px;

            box-shadow:
                0 3px 12px
                rgba(0,0,0,.08);

        }


        .header {

            display: flex;

            justify-content:
                space-between;

            align-items:
                flex-start;

            border-bottom:
                2px solid #e5e5e5;

            padding-bottom: 20px;

            margin-bottom: 25px;

            gap: 30px;

        }


        .company {

            flex: 1;

        }


        .company h1 {

            margin: 0;

            font-size: 28px;

            color: #222;

        }


        .company p {

            margin: 5px 0;

            font-size: 14px;

            color: #666;

        }


        .receipt-title {

            text-align: right;

        }


        .receipt-title h2 {

            margin: 0;

            font-size: 28px;

            color: #0d6efd;

        }


        .receipt-title p {

            margin: 7px 0;

            font-size: 14px;

        }


        .badge {

            display: inline-block;

            padding:
                7px 15px;

            border-radius:
                20px;

            color: #fff;

            font-size: 12px;

            font-weight: bold;

        }


        .badge.paid {

            background: #198754;

        }


        .badge.pending {

            background: #ffc107;

            color: #222;

        }


        .badge.cancelled,
        .badge.failed {

            background: #dc3545;

        }


        .info-wrapper {

            display: flex;

            justify-content:
                space-between;

            gap: 30px;

            margin-bottom: 30px;

        }


        .info-box {

            flex: 1;

            border:
                1px solid #e5e5e5;

            border-radius: 8px;

            padding: 18px;

        }


        .info-box h3 {

            margin-top: 0;

            margin-bottom: 15px;

            color: #0d6efd;

            font-size: 18px;

        }


        .info-table {

            width: 100%;

            border-collapse:
                collapse;

        }


        .info-table td {

            padding: 7px 0;

            vertical-align:
                top;

        }


        .info-table td:first-child {

            width: 130px;

            font-weight: bold;

        }


        .items-section {

            margin-top: 20px;

            margin-bottom: 30px;

        }


        .items-section h3 {

            margin-bottom: 15px;

            color: #0d6efd;

        }


        .items-table {

            width: 100%;

            border-collapse:
                collapse;

            font-size: 14px;

        }


        .items-table th {

            background: #0d6efd;

            color: #fff;

            padding: 11px 8px;

            border:
                1px solid #ddd;

        }


        .items-table td {

            padding: 10px 8px;

            border:
                1px solid #ddd;

        }


        .text-center {

            text-align: center;

        }


        .text-right {

            text-align: right;

        }


        .summary-wrapper {

            display: flex;

            justify-content:
                flex-end;

            margin-bottom: 30px;

        }


        .summary-table {

            width: 350px;

            border-collapse:
                collapse;

        }


        .summary-table td {

            padding: 8px;

        }


        .summary-table td:last-child {

            text-align: right;

        }


        .summary-table .grand-total {

            border-top:
                2px solid #ddd;

        }


        .summary-table .grand-total td {

            padding: 13px 8px;

            font-size: 19px;

            font-weight: bold;

        }


        .summary-table .grand-total td:last-child {

            color: #198754;

        }


        .footer-message {

            text-align: center;

            margin-bottom: 30px;

        }


        .footer-message h3 {

            margin-bottom: 10px;

            color: #198754;

        }


        .footer-message p {

            color: #666;

            margin: 5px 0;

        }


        .signatures {

            display: flex;

            justify-content:
                space-between;

            gap: 50px;

            margin-top: 50px;

            margin-bottom: 40px;

        }


        .signature {

            flex: 1;

            text-align: center;

        }


        .signature-line {

            border-top:
                1px solid #333;

            padding-top: 8px;

        }


        .company-footer {

            text-align: center;

            color: #777;

            font-size: 13px;

            border-top:
                1px solid #ddd;

            padding-top: 20px;

        }


        .company-footer p {

            margin: 5px 0;

        }


        .actions {

            margin-top: 35px;

            text-align: center;

        }


        .btn {

            border: none;

            padding:
                13px 30px;

            border-radius: 6px;

            cursor: pointer;

            font-size: 15px;

            margin: 5px;

        }


        .btn-print {

            background: #0d6efd;

            color: #fff;

        }


        .btn-close {

            background: #dc3545;

            color: #fff;

        }


        @media (max-width: 700px) {

            body {

                padding: 10px;

            }

            .receipt {

                padding: 20px;

            }

            .header {

                flex-direction: column;

            }

            .receipt-title {

                text-align: left;

            }

            .info-wrapper {

                flex-direction: column;

            }

            .items-table {

                font-size: 12px;

            }

            .items-table th,
            .items-table td {

                padding: 7px 5px;

            }

        }


        @media print {

            body {

                background: #fff !important;

                margin: 0;

                padding: 0;

            }


            .receipt {

                width: 100%;

                max-width: none;

                box-shadow: none;

                border: none;

                margin: 0;

                padding: 20px;

            }


            .no-print {

                display: none !important;

            }


            a {

                text-decoration: none;

                color: #000;

            }

        }

    </style>

</head>


<body>


<div class="receipt">


    <!--
    |--------------------------------------------------------------------------
    | RECEIPT HEADER
    |--------------------------------------------------------------------------
    -->


    <div class="header">


        <div class="company">

            <h1>
                <?= e($company["name"]) ?>
            </h1>


            <p>
                <?= e($company["address"]) ?>
            </p>


            <p>
                Phone:
                <?= e($company["phone"]) ?>
            </p>


            <p>
                Email:
                <?= e($company["email"]) ?>
            </p>


            <p>
                <?= e($company["website"]) ?>
            </p>

        </div>


        <div class="receipt-title">


            <h2>
                SALES RECEIPT
            </h2>


            <p>

                <strong>
                    Receipt No:
                </strong>

                <?= e($receiptNumber) ?>

            </p>


            <p>

                <strong>
                    Date:
                </strong>

                <?= e($createdAt) ?>

            </p>


            <span
                class="badge <?= e($statusClass) ?>"
            >

                <?= e($paymentStatusDisplay) ?>

            </span>


        </div>


    </div>



    <!--
    |--------------------------------------------------------------------------
    | CUSTOMER + ORDER INFORMATION
    |--------------------------------------------------------------------------
    -->


    <div class="info-wrapper">


        <!-- CUSTOMER -->


        <div class="info-box">


            <h3>
                Customer Information
            </h3>


            <table class="info-table">


                <tr>

                    <td>
                        Name
                    </td>

                    <td>
                        <?= e($customerName) ?>
                    </td>

                </tr>


                <tr>

                    <td>
                        Phone
                    </td>

                    <td>
                        <?= e($customerPhone) ?>
                    </td>

                </tr>


                <tr>

                    <td>
                        Email
                    </td>

                    <td>
                        <?= e($customerEmail) ?>
                    </td>

                </tr>


                <tr>

                    <td>
                        Customer Code
                    </td>

                    <td>
                        <?= e($customerCode) ?>
                    </td>

                </tr>


            </table>


        </div>



        <!-- ORDER INFORMATION -->


        <div class="info-box">


            <h3>
                Order Information
            </h3>


            <table class="info-table">


                <tr>

                    <td>
                        Order No
                    </td>

                    <td>
                        <?= e($receiptNumber) ?>
                    </td>

                </tr>


                <tr>

                    <td>
                        Store
                    </td>

                    <td>
                        <?= e($receiptStoreName) ?>
                    </td>

                </tr>


                <tr>

                    <td>
                        Cashier
                    </td>

                    <td>
                        <?= e($cashier) ?>
                    </td>

                </tr>


                <tr>

                    <td>
                        Payment
                    </td>

                    <td>
                        <?= e(strtoupper($paymentMethod)) ?>
                    </td>

                </tr>


                <tr>

                    <td>
                        Status
                    </td>

                    <td>

                        <span
                            class="badge <?= e($statusClass) ?>"
                        >

                            <?= e($paymentStatusDisplay) ?>

                        </span>

                    </td>

                </tr>


                <tr>

                    <td>
                        Date
                    </td>

                    <td>
                        <?= e($createdAt) ?>
                    </td>

                </tr>


            </table>


        </div>


    </div>



    <!--
    |--------------------------------------------------------------------------
    | PURCHASED ITEMS
    |--------------------------------------------------------------------------
    -->


    <div class="items-section">


        <h3>
            Purchased Items
        </h3>


        <div
            style="
                overflow-x:auto;
            "
        >


            <table class="items-table">


                <thead>

                    <tr>

                        <th
                            style="width:50px;"
                        >
                            #
                        </th>

                        <th
                            style="text-align:left;"
                        >
                            Product
                        </th>

                        <th>
                            Barcode
                        </th>

                        <th>
                            Qty
                        </th>

                        <th>
                            Unit Price
                        </th>

                        <th>
                            Subtotal
                        </th>

                    </tr>

                </thead>


                <tbody>


                <?php if (!empty($orderItems)): ?>


                    <?php foreach (
                        $orderItems
                        as $index => $item
                    ): ?>


                        <tr>


                            <td
                                class="text-center"
                            >

                                <?= $index + 1 ?>

                            </td>


                            <td>

                                <strong>

                                    <?= e(
                                        $item["product_name"]
                                        ?? "-"
                                    ) ?>

                                </strong>


                                <?php
                                $itemStore =
                                    trim(
                                        $item["store_name"]
                                        ?? ""
                                    );
                                ?>


                                <?php if (
                                    $itemStore !== ""
                                ): ?>

                                    <br>

                                    <small
                                        style="
                                            color:#777;
                                        "
                                    >

                                        Store:
                                        <?= e($itemStore) ?>

                                    </small>

                                <?php endif; ?>


                            </td>


                            <td
                                class="text-center"
                            >

                                <?= e(
                                    $item["barcode"]
                                    ?? "-"
                                ) ?>

                            </td>


                            <td
                                class="text-center"
                            >

                                <?= number_format(
                                    (int) (
                                        $item["quantity"]
                                        ?? 0
                                    )
                                ) ?>

                            </td>


                            <td
                                class="text-right"
                            >

                                ₦<?= money(
                                    $item["unit_price"]
                                    ?? 0
                                ) ?>

                            </td>


                            <td
                                class="text-right"
                            >

                                <strong>

                                    ₦<?= money(
                                        $item["line_total"]
                                        ?? 0
                                    ) ?>

                                </strong>

                            </td>


                        </tr>


                    <?php endforeach; ?>


                <?php else: ?>


                    <tr>

                        <td
                            colspan="6"
                            style="
                                padding:25px;
                                text-align:center;
                                color:#999;
                            "
                        >

                            No products found.

                        </td>

                    </tr>


                <?php endif; ?>


                </tbody>


            </table>


        </div>


    </div>



    <!--
    |--------------------------------------------------------------------------
    | RECEIPT SUMMARY
    |--------------------------------------------------------------------------
    -->


    <div class="summary-wrapper">


        <table class="summary-table">


            <tr>

                <td>
                    Total Items
                </td>

                <td>
                    <?= number_format($totalItems) ?>
                </td>

            </tr>


            <tr>

                <td>
                    Total Quantity
                </td>

                <td>
                    <?= number_format($totalQuantity) ?>
                </td>

            </tr>


            <tr>

                <td>
                    Subtotal
                </td>

                <td>
                    ₦<?= money($subtotal) ?>
                </td>

            </tr>


            <?php if ($discount > 0): ?>

                <tr>

                    <td>
                        Discount
                    </td>

                    <td>
                        - ₦<?= money($discount) ?>
                    </td>

                </tr>

            <?php endif; ?>


            <?php if ($tax > 0): ?>

                <tr>

                    <td>
                        Tax
                    </td>

                    <td>
                        ₦<?= money($tax) ?>
                    </td>

                </tr>

            <?php endif; ?>


            <?php if ($shipping > 0): ?>

                <tr>

                    <td>
                        Shipping
                    </td>

                    <td>
                        ₦<?= money($shipping) ?>
                    </td>

                </tr>

            <?php endif; ?>


            <tr class="grand-total">

                <td>
                    Grand Total
                </td>

                <td>
                    ₦<?= money($totalAmount) ?>
                </td>

            </tr>


            <tr>

                <td>
                    Amount Paid
                </td>

                <td>
                    ₦<?= money($amountPaid) ?>
                </td>

            </tr>


            <tr>

                <td>
                    Balance
                </td>

                <td
                    style="
                        color:
                        <?= $balance > 0
                            ? '#dc3545'
                            : '#198754';
                        ?>;
                        font-weight:bold;
                    "
                >

                    ₦<?= money($balance) ?>

                </td>

            </tr>


        </table>


    </div>



    <!--
    |--------------------------------------------------------------------------
    | NOTES
    |--------------------------------------------------------------------------
    -->


    <?php if (
        !empty(
            trim(
                $order["notes"] ?? ""
            )
        )
    ): ?>


        <div
            style="
                border:
                    1px solid #e5e5e5;
                border-radius:8px;
                padding:15px;
                margin-bottom:30px;
            "
        >

            <strong>
                Notes
            </strong>

            <p
                style="
                    margin-bottom:0;
                    color:#666;
                "
            >

                <?= nl2br(
                    e(
                        $order["notes"]
                    )
                ) ?>

            </p>

        </div>


    <?php endif; ?>



    <!--
    |--------------------------------------------------------------------------
    | THANK YOU
    |--------------------------------------------------------------------------
    -->


    <hr
        style="
            margin:30px 0;
            border:none;
            border-top:
                2px dashed #ccc;
        "
    >


    <!-- <div class="footer-message">


        <h3>
            Thank You For Your Purchase!
        </h3>


        <p>
            We appreciate your business.
        </p>


        <p>
            Please keep this receipt for
            warranty and return purposes.
        </p>


    </div> -->



    <!--
    |--------------------------------------------------------------------------
    | SIGNATURES
    |--------------------------------------------------------------------------
    -->


    <!-- <div class="signatures">


        <div class="signature">


            <div class="signature-line">

                Cashier Signature

                <br>

                <strong>
                    <?= e($cashier) ?>
                </strong>

            </div>


        </div>


        <div class="signature">


            <div class="signature-line">

                Customer Signature

                <br>

                <strong>
                    <?= e($customerName) ?>
                </strong>

            </div>


        </div>


    </div> -->



    <!--
    |--------------------------------------------------------------------------
    | COMPANY FOOTER
    |--------------------------------------------------------------------------
    -->


    <!-- <div class="company-footer">


        <p>
            <strong>
                <?= e($company["name"]) ?>
            </strong>
        </p>


        <p>
            <?= e($company["address"]) ?>
        </p>


        <p>

            Phone:
            <?= e($company["phone"]) ?>

            &nbsp; | &nbsp;

            Email:
            <?= e($company["email"]) ?>

        </p>


        <p>
            <?= e($company["website"]) ?>
        </p>


    </div> -->



    <!--
    |--------------------------------------------------------------------------
    | ACTION BUTTONS
    |--------------------------------------------------------------------------
    -->


    <div
        class="actions no-print"
    >


        <button
            type="button"
            class="btn btn-print"
            onclick="window.print();"
        >

            🖨 Print Receipt

        </button>

        <button
            type="button"
            class="btn btn-close"
            onclick="window.close();"
        >
            ✕ Close
        </button>


    </div>


</div>



<!--
|--------------------------------------------------------------------------
| DEBUG DATA
|--------------------------------------------------------------------------
-->

<script>

    /*
     * PHP → JavaScript debug data
     */

    const receiptDebugData =
        <?= json_encode(
            $debugData,
            JSON_UNESCAPED_UNICODE |
            JSON_UNESCAPED_SLASHES |
            JSON_PRETTY_PRINT
        ) ?>;


    console.group(
        "🧾 ORDER RECEIPT DEBUG"
    );


    console.log(
        "Order ID:",
        receiptDebugData.orderId
    );


    console.log(
        "Order:",
        receiptDebugData.order
    );


    console.log(
        "Order Items:",
        receiptDebugData.orderItems
    );


    console.log(
        "Company:",
        receiptDebugData.company
    );


    console.log(
        "Receipt Number:",
        receiptDebugData.receiptNumber
    );


    console.log(
        "Cashier:",
        receiptDebugData.cashier
    );


    console.log(
        "Customer:",
        {
            name:
                receiptDebugData.customerName,

            phone:
                receiptDebugData.customerPhone,

            email:
                receiptDebugData.customerEmail,

            code:
                receiptDebugData.customerCode
        }
    );


    console.log(
        "Payment:",
        {
            method:
                receiptDebugData.paymentMethod,

            status:
                receiptDebugData.paymentStatus
        }
    );


    console.log(
        "Financial:",
        {
            subtotal:
                receiptDebugData.subtotal,

            discount:
                receiptDebugData.discount,

            tax:
                receiptDebugData.tax,

            shipping:
                receiptDebugData.shipping,

            totalAmount:
                receiptDebugData.totalAmount,

            amountPaid:
                receiptDebugData.amountPaid,

            balance:
                receiptDebugData.balance
        }
    );


    console.log(
        "Store:",
        receiptDebugData.receiptStoreName
    );


    console.log(
        "Total Items:",
        receiptDebugData.totalItems
    );


    console.log(
        "Total Quantity:",
        receiptDebugData.totalQuantity
    );


    console.groupEnd();


    /*
     * Simple validation
     */

    if (
        !receiptDebugData.order ||
        Object.keys(
            receiptDebugData.order
        ).length === 0
    ) {

        console.error(
            "❌ Order data is empty."
        );

    } else {

        console.log(
            "✅ Order data loaded successfully."
        );

    }


    if (
        !Array.isArray(
            receiptDebugData.orderItems
        )
    ) {

        console.error(
            "❌ orderItems is not an array."
        );

    } else {

        console.log(
            "✅ Order items loaded:",
            receiptDebugData.orderItems.length
        );

    }

</script>


</body>

</html>