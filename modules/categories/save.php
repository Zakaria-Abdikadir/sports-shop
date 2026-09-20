<?php

require_once "../../config/auth.php";
require_once "../../config/database.php";

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: index.php");
    exit();
}

$category = trim($_POST['category_name']);
$description = trim($_POST['description']);
$status = $_POST['status'];

if ($category == "") {
    die("Category name is required.");
}

/*
|--------------------------------------------------------------------------
| Check duplicate category
|--------------------------------------------------------------------------
*/

$check = $conn->prepare("SELECT id FROM categories WHERE category_name=?");
$check->bind_param("s", $category);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {

    echo "<script>
        alert('Category already exists.');
        window.history.back();
    </script>";

    exit();
}

/*
|--------------------------------------------------------------------------
| Insert
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
INSERT INTO categories
(category_name,description,status)
VALUES (?,?,?)
");

$stmt->bind_param(
    "sss",
    $category,
    $description,
    $status
);

$stmt->execute();

header("Location: index.php");

exit();