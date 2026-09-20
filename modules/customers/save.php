<?php

require_once "../../config/database.php";

$first_name = trim($_POST['first_name']);
$last_name  = trim($_POST['last_name']);
$phone      = trim($_POST['phone']);
$email      = trim($_POST['email']);
$gender     = $_POST['gender'];
$address    = trim($_POST['address']);
$status     = $_POST['status'];

$stmt = $conn->prepare("
INSERT INTO customers
(
first_name,
last_name,
phone,
email,
gender,
address,
status
)

VALUES

(?,?,?,?,?,?,?)
");

$stmt->bind_param(
"sssssss",
$first_name,
$last_name,
$phone,
$email,
$gender,
$address,
$status
);

$stmt->execute();

header("Location:index.php?success=added");
exit();