<?php

require_once "../../config/auth.php";
require_once "../../config/database.php";

$product_id = (int)$_POST['product_id'];
$quantity   = (int)$_POST['quantity'];
$reference  = trim($_POST['reference']);
$notes      = trim($_POST['notes']);

$stmt = $conn->prepare("
SELECT selling_qty
FROM products
WHERE id=?
");

$stmt->bind_param("i",$product_id);
$stmt->execute();

$product=$stmt->get_result()->fetch_assoc();

if(!$product){
    die("Product not found.");
}

$totalPieces=$quantity*$product['selling_qty'];

$stmt=$conn->prepare("
SELECT current_stock
FROM inventory
WHERE product_id=?
");

$stmt->bind_param("i",$product_id);
$stmt->execute();

$inventory=$stmt->get_result()->fetch_assoc();

if(!$inventory){
    die("Inventory not found.");
}

if($inventory['current_stock']<$totalPieces){
    die("Not enough stock.");
}

$newStock=$inventory['current_stock']-$totalPieces;

$stmt=$conn->prepare("
UPDATE inventory
SET
current_stock=?,
updated_at=NOW()
WHERE product_id=?
");

$stmt->bind_param("ii",$newStock,$product_id);
$stmt->execute();

$type="Stock Out";

$stmt=$conn->prepare("
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

$stmt->bind_param(
"isiss",
$product_id,
$type,
$totalPieces,
$reference,
$notes
);

$stmt->execute();

header("Location:index.php?success=stockout");
exit();