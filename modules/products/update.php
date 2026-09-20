<?php

require_once "../../config/database.php";

$id              = (int)$_POST['id'];
$product_name    = trim($_POST['product_name']);
$category_id     = (int)$_POST['category_id'];
$item_no         = trim($_POST['item_no']);
$brand           = trim($_POST['brand']);

$buying_price    = (float)$_POST['buying_price'];
$selling_price   = (float)$_POST['selling_price'];

$receiving_unit  = $_POST['receiving_unit'];
$receiving_qty   = (int)$_POST['receiving_qty'];

$selling_unit    = $_POST['selling_unit'];
$selling_qty     = (int)$_POST['selling_qty'];

$description     = trim($_POST['description']);
$status          = $_POST['status'];

$product_image = $_POST['old_image'];

/*
|--------------------------------------------------------------------------
| Upload New Image
|--------------------------------------------------------------------------
*/

if(isset($_FILES['image']) && $_FILES['image']['error'] == 0){

    $extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

    $allowed = ['jpg','jpeg','png','webp'];

    if(in_array($extension,$allowed)){

        if(!empty($product_image) && file_exists("../../uploads/products/".$product_image)){
            unlink("../../uploads/products/".$product_image);
        }

        $product_image = time()."_".basename($_FILES['image']['name']);

        move_uploaded_file(
            $_FILES['image']['tmp_name'],
            "../../uploads/products/".$product_image
        );
    }
}

/*
|--------------------------------------------------------------------------
| Update Product
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
UPDATE products SET

category_id=?,
item_no=?,
product_name=?,
brand=?,
buying_price=?,
selling_price=?,
receiving_unit=?,
receiving_qty=?,
selling_unit=?,
selling_qty=?,
product_image=?,
description=?,
status=?

WHERE id=?
");

$stmt->bind_param(
    "isssddsisssssi",
    $category_id,
    $item_no,
    $product_name,
    $brand,
    $buying_price,
    $selling_price,
    $receiving_unit,
    $receiving_qty,
    $selling_unit,
    $selling_qty,
    $product_image,
    $description,
    $status,
    $id
);

$stmt->execute();

header("Location:index.php?success=updated");
exit();