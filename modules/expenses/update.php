<?php

require_once "../../config/database.php";

$id = (int)$_POST['id'];

$stmt = $conn->prepare("
UPDATE expenses SET

expense_name=?,
category=?,
amount=?,
expense_date=?,
notes=?

WHERE id=?
");

$stmt->bind_param(

"ssdssi",

$_POST['expense_name'],
$_POST['category'],
$_POST['amount'],
$_POST['expense_date'],
$_POST['notes'],
$id

);

$stmt->execute();

header("Location:index.php?success=updated");
exit();