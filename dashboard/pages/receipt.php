<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>

        Receipt #<?= htmlspecialchars($order['order_number']) ?>

    </title>

    <link
        rel="stylesheet"
        href="../../assets/css/dashboard.css">

    <style>

        body{

            font-family:Arial,Helvetica,sans-serif;

            background:#f4f6f9;

            margin:0;

            padding:30px;

            color:#333;

        }

        .receipt{

            width:850px;

            max-width:100%;

            margin:auto;

            background:#fff;

            padding:35px;

            border-radius:8px;

            box-shadow:0 3px 12px rgba(0,0,0,.08);

        }

        .header{

            display:flex;

            justify-content:space-between;

            align-items:flex-start;

            border-bottom:2px solid #e5e5e5;

            padding-bottom:20px;

            margin-bottom:25px;

        }

        .company h1{

            margin:0;

            font-size:28px;

        }

        .company p{

            margin:4px 0;

            font-size:14px;

            color:#666;

        }

        .receipt-title{

            text-align:right;

        }

        .receipt-title h2{

            margin:0;

            font-size:32px;

            color:#0d6efd;

        }

        .receipt-title p{

            margin:6px 0;

            font-size:15px;

        }

        .badge{

            display:inline-block;

            padding:8px 16px;

            border-radius:20px;

            background:#198754;

            color:#fff;

            font-size:13px;

            font-weight:bold;

        }

        @media print{

            body{

                background:#fff;

                padding:0;

            }

            .receipt{

                width:100%;

                box-shadow:none;

                border-radius:0;

            }

        }

    </style>

</head>

<body>

<div class="receipt">

    <!-- ===========================
         RECEIPT HEADER
    ============================ -->

    <div class="header">

        <div class="company">

            <h1>

                <?= htmlspecialchars($company["name"]) ?>

            </h1>

            <p>

                <?= htmlspecialchars($company["address"]) ?>

            </p>

            <p>

                Phone:
                <?= htmlspecialchars($company["phone"]) ?>

            </p>

            <p>

                Email:
                <?= htmlspecialchars($company["email"]) ?>

            </p>

            <p>

                <?= htmlspecialchars($company["website"]) ?>

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

                <?= htmlspecialchars($order["order_number"]) ?>

            </p>

            <p>

                <strong>

                    Date:

                </strong>

                <?= date(
                    "d M Y h:i A",
                    strtotime($order["created_at"])
                ) ?>

            </p>

            <span class="badge">

                <?= strtoupper($order["payment_status"]) ?>

            </span>

        </div>

    </div>

<!-- ===================================================
     CUSTOMER INFORMATION + ORDER DETAILS
=================================================== -->

<div
    style="
        display:flex;
        justify-content:space-between;
        gap:30px;
        margin-bottom:30px;
    ">

    <!-- Customer Information -->

    <div
        style="
            flex:1;
            border:1px solid #e5e5e5;
            border-radius:8px;
            padding:18px;
        ">

        <h3
            style="
                margin-top:0;
                margin-bottom:15px;
                color:#0d6efd;
                font-size:18px;
            ">

            Customer Information

        </h3>

        <table
            style="
                width:100%;
                border-collapse:collapse;
            ">

            <tr>

                <td
                    style="
                        width:130px;
                        font-weight:bold;
                        padding:6px 0;
                    ">

                    Name

                </td>

                <td>

                    <?= htmlspecialchars($order["customer_name"] ?: "Walk-in Customer") ?>

                </td>

            </tr>

            <tr>

                <td
                    style="
                        font-weight:bold;
                        padding:6px 0;
                    ">

                    Phone

                </td>

                <td>

                    <?= htmlspecialchars($order["customer_phone"] ?: "-") ?>

                </td>

            </tr>

            <tr>

                <td
                    style="
                        font-weight:bold;
                        padding:6px 0;
                    ">

                    Email

                </td>

                <td>

                    <?= htmlspecialchars($order["customer_email"] ?: "-") ?>

                </td>

            </tr>

        </table>

    </div>

    <!-- Order Information -->

    <div
        style="
            flex:1;
            border:1px solid #e5e5e5;
            border-radius:8px;
            padding:18px;
        ">

        <h3
            style="
                margin-top:0;
                margin-bottom:15px;
                color:#0d6efd;
                font-size:18px;
            ">

            Order Information

        </h3>

        <table
            style="
                width:100%;
                border-collapse:collapse;
            ">

            <tr>

                <td
                    style="
                        width:150px;
                        font-weight:bold;
                        padding:6px 0;
                    ">

                    Order No

                </td>

                <td>

                    <?= htmlspecialchars($order["order_number"]) ?>

                </td>

            </tr>

            <tr>

                <td
                    style="
                        font-weight:bold;
                        padding:6px 0;
                    ">

                    Store

                </td>

                <td>

                    <?= htmlspecialchars($order["store_name"]) ?>

                </td>

            </tr>

            <tr>

                <td
                    style="
                        font-weight:bold;
                        padding:6px 0;
                    ">

                    Cashier

                </td>

                <td>

                    <?= htmlspecialchars($order["cashier"]) ?>

                </td>

            </tr>

            <tr>

                <td
                    style="
                        font-weight:bold;
                        padding:6px 0;
                    ">

                    Payment

                </td>

                <td>

                    <?= strtoupper(htmlspecialchars($order["payment_method"])) ?>

                </td>

            </tr>

            <tr>

                <td
                    style="
                        font-weight:bold;
                        padding:6px 0;
                    ">

                    Status

                </td>

                <td>

                    <span
                        style="
                            background:
                            <?= $order["payment_status"] == "paid"
                                ? "#198754"
                                : "#dc3545"; ?>;
                            color:#fff;
                            padding:4px 12px;
                            border-radius:20px;
                            font-size:12px;
                            font-weight:bold;
                        ">

                        <?= strtoupper($order["payment_status"]) ?>

                    </span>

                </td>

            </tr>

            <tr>

                <td
                    style="
                        font-weight:bold;
                        padding:6px 0;
                    ">

                    Date

                </td>

                <td>

                    <?= date(
                        "d M Y h:i A",
                        strtotime($order["created_at"])
                    ) ?>

                </td>

            </tr>

        </table>

    </div>

</div>

<!-- ===================================================
     PRODUCTS TABLE
=================================================== -->

<div
    style="
        margin-top:20px;
        margin-bottom:30px;
    ">

    <h3
        style="
            margin-bottom:15px;
            color:#0d6efd;
        ">

        Purchased Items

    </h3>

    <table
        style="
            width:100%;
            border-collapse:collapse;
            font-size:14px;
        ">

        <thead>

            <tr
                style="
                    background:#0d6efd;
                    color:#fff;
                ">

                <th
                    style="
                        padding:12px;
                        border:1px solid #ddd;
                        width:60px;
                    ">

                    #

                </th>

                <th
                    style="
                        padding:12px;
                        border:1px solid #ddd;
                        text-align:left;
                    ">

                    Product

                </th>

                <th
                    style="
                        padding:12px;
                        border:1px solid #ddd;
                    ">

                    Barcode

                </th>

                <th
                    style="
                        padding:12px;
                        border:1px solid #ddd;
                    ">

                    SKU

                </th>

                <th
                    style="
                        padding:12px;
                        border:1px solid #ddd;
                    ">

                    Qty

                </th>

                <th
                    style="
                        padding:12px;
                        border:1px solid #ddd;
                    ">

                    Unit Price

                </th>

                <th
                    style="
                        padding:12px;
                        border:1px solid #ddd;
                    ">

                    Subtotal

                </th>

            </tr>

        </thead>

        <tbody>

        <?php if(count($orderItems) > 0): ?>

            <?php foreach($orderItems as $index => $item): ?>

                <tr>

                    <td
                        style="
                            padding:10px;
                            border:1px solid #ddd;
                            text-align:center;
                        ">

                        <?= $index + 1 ?>

                    </td>

                    <td
                        style="
                            padding:10px;
                            border:1px solid #ddd;
                        ">

                        <strong>

                            <?= htmlspecialchars($item["product_name"]) ?>

                        </strong>

                        <br>

                        <small style="color:#777;">

                            <?= htmlspecialchars($item["category"]) ?>

                        </small>

                    </td>

                    <td
                        style="
                            padding:10px;
                            border:1px solid #ddd;
                            text-align:center;
                        ">

                        <?= htmlspecialchars($item["barcode"]) ?>

                    </td>

                    <td
                        style="
                            padding:10px;
                            border:1px solid #ddd;
                            text-align:center;
                        ">

                        <?= htmlspecialchars($item["sku"]) ?>

                    </td>

                    <td
                        style="
                            padding:10px;
                            border:1px solid #ddd;
                            text-align:center;
                        ">

                        <?= number_format($item["quantity"]) ?>

                        <?= htmlspecialchars($item["unit"]) ?>

                    </td>

                    <td
                        style="
                            padding:10px;
                            border:1px solid #ddd;
                            text-align:right;
                        ">

                        ₦<?= money($item["unit_price"]) ?>

                    </td>

                    <td
                        style="
                            padding:10px;
                            border:1px solid #ddd;
                            text-align:right;
                            font-weight:bold;
                        ">

                        ₦<?= money($item["subtotal"]) ?>

                    </td>

                </tr>

            <?php endforeach; ?>

        <?php else: ?>

            <tr>

                <td
                    colspan="7"
                    style="
                        padding:25px;
                        text-align:center;
                        border:1px solid #ddd;
                        color:#999;
                    ">

                    No products found.

                </td>

            </tr>

        <?php endif; ?>

        </tbody>

    </table>

</div>

<!-- ===================================================
     RECEIPT SUMMARY
=================================================== -->

<div
    style="
        display:flex;
        justify-content:flex-end;
        margin-bottom:30px;
    ">

    <table
        style="
            width:320px;
            border-collapse:collapse;
        ">

        <tr>

            <td
                style="
                    padding:8px;
                    font-weight:bold;
                ">

                Total Items

            </td>

            <td
                style="
                    text-align:right;
                    padding:8px;
                ">

                <?= $totalItems ?>

            </td>

        </tr>

        <tr>

            <td
                style="
                    padding:8px;
                    font-weight:bold;
                ">

                Total Quantity

            </td>

            <td
                style="
                    text-align:right;
                    padding:8px;
                ">

                <?= $totalQuantity ?>

            </td>

        </tr>

        <tr
            style="
                border-top:2px solid #ddd;
            ">

            <td
                style="
                    padding:12px;
                    font-size:18px;
                    font-weight:bold;
                ">

                Grand Total

            </td>

            <td
                style="
                    padding:12px;
                    text-align:right;
                    font-size:20px;
                    font-weight:bold;
                    color:#198754;
                ">

                ₦<?= money($order["total"]) ?>

            </td>

        </tr>

    </table>

</div>

<!-- ===================================================
     RECEIPT FOOTER
=================================================== -->

<hr style="margin:30px 0;border:none;border-top:2px dashed #ccc;">

<div
    style="
        text-align:center;
        margin-bottom:25px;
    ">

    <h3
        style="
            margin-bottom:10px;
            color:#198754;
        ">

        Thank You For Your Purchase!

    </h3>

    <p
        style="
            color:#666;
            margin:5px 0;
        ">

        We appreciate your business.

    </p>

    <p
        style="
            color:#666;
            margin:5px 0;
        ">

        Please keep this receipt for warranty and return purposes.

    </p>

</div>

<!-- ===================================================
     SIGNATURES
=================================================== -->

<div
    style="
        display:flex;
        justify-content:space-between;
        margin-top:50px;
        margin-bottom:40px;
    ">

    <div
        style="
            width:250px;
            text-align:center;
        ">

        <div
            style="
                border-top:1px solid #333;
                padding-top:8px;
            ">

            Cashier Signature

            <br>

            <strong>

                <?= htmlspecialchars($order["cashier"]) ?>

            </strong>

        </div>

    </div>

    <div
        style="
            width:250px;
            text-align:center;
        ">

        <div
            style="
                border-top:1px solid #333;
                padding-top:8px;
            ">

            Customer Signature

        </div>

    </div>

</div>

<!-- ===================================================
     COMPANY FOOTER
=================================================== -->

<div
    style="
        text-align:center;
        color:#777;
        font-size:13px;
        border-top:1px solid #ddd;
        padding-top:20px;
    ">

    <p>

        <?= htmlspecialchars($company["name"]) ?>

    </p>

    <p>

        <?= htmlspecialchars($company["address"]) ?>

    </p>

    <p>

        Phone:
        <?= htmlspecialchars($company["phone"]) ?>

        |

        Email:
        <?= htmlspecialchars($company["email"]) ?>

    </p>

    <p>

        Website:
        <?= htmlspecialchars($company["website"]) ?>

    </p>

</div>

<!-- ===================================================
     ACTION BUTTONS
=================================================== -->

<div
    class="no-print"
    style="
        margin-top:35px;
        text-align:center;
    ">

    <button

        onclick="window.print();"

        style="
            background:#0d6efd;
            color:#fff;
            border:none;
            padding:14px 35px;
            border-radius:6px;
            cursor:pointer;
            font-size:15px;
            margin-right:10px;
        ">

        🖨 Print Receipt

    </button>

    <button

        onclick="window.close();"

        style="
            background:#dc3545;
            color:#fff;
            border:none;
            padding:14px 35px;
            border-radius:6px;
            cursor:pointer;
            font-size:15px;
        ">

        ✕ Close

    </button>

</div>

<!-- ===================================================
     PRINT STYLES
=================================================== -->

<style>

@media print{

    body{

        background:#fff !important;

        margin:0;

        padding:0;

    }

    .receipt{

        width:100%;

        box-shadow:none;

        border:none;

        margin:0;

        padding:20px;

    }

    .no-print{

        display:none !important;

    }

    a{

        text-decoration:none;

        color:#000;

    }

}

</style>

<!-- ===================================================
     AUTO PRINT (OPTIONAL)
=================================================== -->

<script>

// Uncomment to print automatically when the page opens
// window.onload = function () {
//     window.print();
// };

</script>

</div>

</body>

</html>