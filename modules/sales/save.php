<?php

require_once "../../config/init.php";

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location:index.php");
    exit();
}

$conn->begin_transaction();

try {

    $invoice_no     = $_POST['invoice_no'];
    $customer_id    = !empty($_POST['customer_id']) ? (int)$_POST['customer_id'] : NULL;
    $total_amount   = (float)$_POST['total_amount'];
    $amount_paid    = (float)$_POST['amount_paid'];
    $balance        = (float)$_POST['balance'];
    $payment_method = $_POST['payment_method'];

    $created_by = $_SESSION['user_id'];

    /*
    |----------------------------------------------------------
    | Save Sale
    |----------------------------------------------------------
    */

    $stmt = $conn->prepare("
        INSERT INTO sales
        (
            invoice_no,
            customer_id,
            total_amount,
            amount_paid,
            balance,
            payment_method,
            created_by
        )
        VALUES
        (?,?,?,?,?,?,?)
    ");

    $stmt->bind_param(
        "sidddsi",
        $invoice_no,
        $customer_id,
        $total_amount,
        $amount_paid,
        $balance,
        $payment_method,
        $created_by
    );

    $stmt->execute();

    $sale_id = $conn->insert_id;

    /*
    |----------------------------------------------------------
    | Save Items
    |----------------------------------------------------------
    */

    $product_ids = $_POST['product_id'];
    $qtys        = $_POST['quantity'];
    $prices      = $_POST['price'];
    $pieces      = $_POST['pieces'];
    $units       = $_POST['unit'];

    for ($i = 0; $i < count($product_ids); $i++) {

        $product_id = (int)$product_ids[$i];

        $qty = (float)$qtys[$i];

        $price = (float)$prices[$i];

        $selling_qty = (int)$pieces[$i];

        $unit = $units[$i];

        $stock_out = $qty * $selling_qty;

// Total pieces × price per piece
$subtotal = $stock_out * $price;

        /*
        |------------------------------------------------------
        | Save Sale Item
        |------------------------------------------------------
        */

        $stmt = $conn->prepare("
            INSERT INTO sale_items
            (
                sale_id,
                product_id,
                quantity,
                unit,
                pieces,
                unit_price,
                subtotal
            )
            VALUES
            (?,?,?,?,?,?,?)
        ");

      $stmt->bind_param(
    "iiisidd",
    $sale_id,
    $product_id,
    $qty,
    $unit,
    $stock_out,
    $price,
    $subtotal
);

        $stmt->execute();

        /*
        |------------------------------------------------------
        | Update Inventory
        |------------------------------------------------------
        */

        $stmt = $conn->prepare("
            UPDATE inventory
            SET current_stock = current_stock - ?
            WHERE product_id = ?
        ");

        $stmt->bind_param(
            "ii",
            $stock_out,
            $product_id
        );

        $stmt->execute();

        /*
        |------------------------------------------------------
        | Stock Movement
        |------------------------------------------------------
        */

        $reference = $invoice_no;

        $notes = "Sale";

        $stmt = $conn->prepare("
            INSERT INTO stock_movements
            (
                product_id,
                movement_type,
                quantity,
                reference,
                notes
            )
            VALUES
            (?,?,?,?,?)
        ");

        $movement = "Stock Out";

        $stmt->bind_param(
            "isiss",
            $product_id,
            $movement,
            $stock_out,
            $reference,
            $notes
        );

        $stmt->execute();

    }

    $conn->commit();

    header("Location:invoice.php?id=".$sale_id);

} catch (Exception $e) {

    $conn->rollback();

    die($e->getMessage());

}