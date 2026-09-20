<?php

require_once "../../config/auth.php";
require_once "../../config/database.php";

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = (int) $_GET['id'];

/*
|--------------------------------------------------------------------------
| Check if category exists
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("SELECT id FROM categories WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location: index.php");
    exit();
}

/*
|--------------------------------------------------------------------------
| Delete category
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("DELETE FROM categories WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: index.php");
exit();