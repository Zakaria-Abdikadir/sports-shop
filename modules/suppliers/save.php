<?php

require_once "../../config/database.php";

$supplier_name = trim($_POST['supplier_name']);
$contact_person = trim($_POST['contact_person']);
$phone = trim($_POST['phone']);
$email = trim($_POST['email']);
$address = trim($_POST['address']);
$status = $_POST['status'];

$stmt = $conn->prepare("
INSERT INTO suppliers
(
supplier_name,
contact_person,
phone,
email,
address,
status
)

VALUES

(?,?,?,?,?,?)
");

$stmt->bind_param(
"ssssss",
$supplier_name,
$contact_person,
$phone,
$email,
$address,
$status
);

$stmt->execute();

header("Location:index.php?success=added");
exit();