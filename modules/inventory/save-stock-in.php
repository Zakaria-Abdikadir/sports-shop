<?php

require_once "../../config/auth.php";
require_once "../../config/database.php";
require_once "../../includes/functions.php";

/*
|--------------------------------------------------------------------------
| Validate Request
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] != "POST") {
    header("Location: stock-in.php");
    exit();
}

/*
|--------------------------------------------------------------------------
| Get Form Data
|--------------------------------------------------------------------------
*/

$product_id        = (int)$_POST['product_id'];
$received_quantity = (int)$_POST['received_quantity'];
$reference         = trim($_POST['reference']);
$notes             = trim($_POST['notes']);

if ($product_id <= 0 || $received_quantity <= 0) {
    header("Location: stock-in.php");
    exit();
}

/*
|--------------------------------------------------------------------------
| Get Product Information
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT
        receiving_qty,
        minimum_stock
    FROM products
    WHERE id=?
");

$stmt->bind_param("i", $product_id);
$stmt->execute();

$product = $stmt->get_result()->fetch_assoc();

if (!$product) {
    die("Product not found.");
}

/*
|--------------------------------------------------------------------------
| Convert to Pieces
|--------------------------------------------------------------------------
*/

$totalPieces = $received_quantity * $product['receiving_qty'];

/*
|--------------------------------------------------------------------------
| Check Inventory Record
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT id,current_stock
    FROM inventory
    WHERE product_id=?
");

$stmt->bind_param("i", $product_id);
$stmt->execute();

$inventory = $stmt->get_result()->fetch_assoc();

/*
|--------------------------------------------------------------------------
| Update Inventory
|--------------------------------------------------------------------------
*/

if ($inventory) {

    $newStock = $inventory['current_stock'] + $totalPieces;

    $stmt = $conn->prepare("
        UPDATE inventory
        SET
            current_stock=?,
            updated_at=NOW()
        WHERE product_id=?
    ");

    $stmt->bind_param(
        "ii",
        $newStock,
        $product_id
    );

    $stmt->execute();

} else {

    $stmt = $conn->prepare("
        INSERT INTO inventory
        (
            product_id,
            current_stock,
            minimum_stock
        )
        VALUES
        (?,?,?)
    ");

    $stmt->bind_param(
        "iii",
        $product_id,
        $totalPieces,
        $product['minimum_stock']
    );

    $stmt->execute();

}

/*
|--------------------------------------------------------------------------
| Save Stock Movement
|--------------------------------------------------------------------------
*/

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

$type = "Stock In";

$stmt->bind_param(
    "isiss",
    $product_id,
    $type,
    $totalPieces,
    $reference,
    $notes
);

$stmt->execute();

/*
|--------------------------------------------------------------------------
| Redirect
|--------------------------------------------------------------------------
*/

header("Location: index.php?success=stockin");
exit();