<?php

require_once "../../config/database.php";

$id = (int)$_POST['id'];

$stmt = $conn->prepare("
UPDATE suppliers SET

supplier_name=?,
contact_person=?,
phone=?,
email=?,
address=?,
status=?

WHERE id=?
");

$stmt->bind_param(
"ssssssi",

$_POST['supplier_name'],
$_POST['contact_person'],
$_POST['phone'],
$_POST['email'],
$_POST['address'],
$_POST['status'],
$id

);

$stmt->execute();

header("Location:index.php?success=updated");
exit();