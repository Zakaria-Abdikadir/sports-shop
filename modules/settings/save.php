<?php

require_once "../../config/init.php";

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location:index.php");
    exit();
}

$shop_name      = trim($_POST['shop_name']);
$tagline        = trim($_POST['tagline']);
$phone          = trim($_POST['phone']);
$email          = trim($_POST['email']);
$address        = trim($_POST['address']);
$currency       = trim($_POST['currency']);
$receipt_footer = trim($_POST['receipt_footer']);

/*
|--------------------------------------------------------------------------
| Upload Logo
|--------------------------------------------------------------------------
*/

$logo = "";

$stmt = $conn->query("SELECT logo FROM settings LIMIT 1");
$current = $stmt->fetch_assoc();

if ($current) {
    $logo = $current['logo'];
}

if (
    isset($_FILES['logo']) &&
    $_FILES['logo']['error'] == 0
) {

    $ext = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));

    $filename = "logo_" . time() . "." . $ext;

    $destination = "../../uploads/" . $filename;

    move_uploaded_file($_FILES['logo']['tmp_name'], $destination);

    $logo = $filename;
}

/*
|--------------------------------------------------------------------------
| Update Settings
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
UPDATE settings SET
shop_name=?,
tagline=?,
phone=?,
email=?,
address=?,
currency=?,
receipt_footer=?,
logo=?
WHERE id=1
");

$stmt->bind_param(
    "ssssssss",
    $shop_name,
    $tagline,
    $phone,
    $email,
    $address,
    $currency,
    $receipt_footer,
    $logo
);

$stmt->execute();

$_SESSION['success'] = "Settings updated successfully.";

header("Location:index.php");

exit();