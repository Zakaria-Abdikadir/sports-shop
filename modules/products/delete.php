<?php

require_once "../../config/auth.php";
require_once "../../config/database.php";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location:index.php");
    exit();
}

$id = (int)$_GET['id'];

/*
|--------------------------------------------------------------------------
| Get Image
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
SELECT product_image
FROM products
WHERE id=?
");

$stmt->bind_param("i", $id);
$stmt->execute();

$product = $stmt->get_result()->fetch_assoc();

if (!$product) {
    header("Location:index.php");
    exit();
}

/*
|--------------------------------------------------------------------------
| Delete Image
|--------------------------------------------------------------------------
*/

if (!empty($product['product_image'])) {

    $image = "../../uploads/products/" . $product['product_image'];

    if (file_exists($image)) {
        unlink($image);
    }
}

/*
|--------------------------------------------------------------------------
| Delete Product
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
DELETE FROM products
WHERE id=?
");

$stmt->bind_param("i", $id);
$stmt->execute();

header("Location:index.php?success=deleted");
exit();