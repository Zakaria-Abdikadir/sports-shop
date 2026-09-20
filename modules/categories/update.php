<?php

require_once "../../config/auth.php";
require_once "../../config/database.php";

$id = (int) $_POST['id'];
$category = trim($_POST['category_name']);
$description = trim($_POST['description']);
$status = $_POST['status'];

$stmt = $conn->prepare("
UPDATE categories
SET
category_name=?,
description=?,
status=?
WHERE id=?
");

$stmt->bind_param(
"sssi",
$category,
$description,
$status,
$id
);

$stmt->execute();

header("Location: index.php");
exit();