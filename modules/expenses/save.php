<?php

require_once "../../config/database.php";

$stmt=$conn->prepare("
INSERT INTO expenses
(
expense_name,
category,
amount,
expense_date,
notes
)

VALUES

(?,?,?,?,?)
");

$stmt->bind_param(

"ssdss",

$_POST['expense_name'],
$_POST['category'],
$_POST['amount'],
$_POST['expense_date'],
$_POST['notes']

);

$stmt->execute();

header("Location:index.php?success=added");
exit();