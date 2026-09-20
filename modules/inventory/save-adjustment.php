<?php

require_once "../../config/init.php";

$product_id=(int)$_POST['product_id'];

$physical_stock=(int)$_POST['physical_stock'];

$notes=trim($_POST['notes']);

$stmt=$conn->prepare("
SELECT current_stock
FROM inventory
WHERE product_id=?
");

$stmt->bind_param("i",$product_id);

$stmt->execute();

$inventory=$stmt->get_result()->fetch_assoc();

if(!$inventory){

die("Inventory record not found.");

}

$current=$inventory['current_stock'];

$stmt=$conn->prepare("
UPDATE inventory
SET current_stock=?,
updated_at=NOW()
WHERE product_id=?
");

$stmt->bind_param(
"ii",
$physical_stock,
$product_id
);

$stmt->execute();

$type="Adjustment";

$difference=$physical_stock-$current;

$stmt=$conn->prepare("
INSERT INTO stock_movements
(
product_id,
movement_type,
quantity,
notes
)
VALUES
(?,?,?,?)
");

$adjustmentNotes=$notes." (Difference: ".$difference.")";

$stmt->bind_param(
"isis",
$product_id,
$type,
$difference,
$adjustmentNotes
);

$stmt->execute();

header("Location:history.php");

exit();