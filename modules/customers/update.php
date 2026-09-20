<?php

require_once "../../config/database.php";

$id = (int)$_POST['id'];

$stmt = $conn->prepare("
UPDATE customers SET

first_name=?,
last_name=?,
phone=?,
email=?,
gender=?,
address=?,
status=?

WHERE id=?
");

$stmt->bind_param(
"sssssssi",

$_POST['first_name'],
$_POST['last_name'],
$_POST['phone'],
$_POST['email'],
$_POST['gender'],
$_POST['address'],
$_POST['status'],
$id

);

$stmt->execute();

header("Location:index.php?success=updated");
exit();