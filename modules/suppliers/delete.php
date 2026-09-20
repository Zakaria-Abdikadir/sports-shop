<?php

require_once "../../config/auth.php";
require_once "../../config/database.php";

if(!isset($_GET['id']) || !is_numeric($_GET['id'])){
    header("Location:index.php");
    exit();
}

$id = (int)$_GET['id'];

$stmt = $conn->prepare("DELETE FROM suppliers WHERE id=?");
$stmt->bind_param("i",$id);
$stmt->execute();

header("Location:index.php?success=deleted");
exit();