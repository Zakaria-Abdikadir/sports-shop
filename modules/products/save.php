<?php

require_once "../../config/database.php";

$product_name     = trim($_POST['product_name']);
$category_id      = (int)$_POST['category_id'];
$item_no          = trim($_POST['item_no']);
$brand            = trim($_POST['brand']);
$buying_price     = (float)$_POST['buying_price'];
$selling_price    = (float)$_POST['selling_price'];

$receiving_unit   = $_POST['receiving_unit'];
$receiving_qty    = (int)$_POST['receiving_qty'];

$selling_unit     = $_POST['selling_unit'];
$selling_qty      = (int)$_POST['selling_qty'];

$description      = trim($_POST['description']);
$status           = $_POST['status'];

$product_image = "";

/*
|--------------------------------------------------------------------------
| Upload Image
|--------------------------------------------------------------------------
*/

if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {

    $extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

    $allowed = ['jpg', 'jpeg', 'png', 'webp'];

    if (in_array($extension, $allowed)) {

        $product_image = time() . "_" . basename($_FILES['image']['name']);

        move_uploaded_file(
            $_FILES['image']['tmp_name'],
            "../../uploads/products/" . $product_image
        );
    }
}

/*
|--------------------------------------------------------------------------
| Save Product
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
INSERT INTO products
(
category_id,
item_no,
product_name,
brand,
buying_price,
selling_price,
receiving_unit,
receiving_qty,
selling_unit,
selling_qty,
product_image,
description,
status
)

VALUES

(?,?,?,?,?,?,?,?,?,?,?,?,?)
");

$stmt->bind_param(
    "isssddsisssss",
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
    $status
);

$stmt->execute();

header("Location:index.php?success=added");
exit();