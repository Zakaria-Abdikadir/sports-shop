<?php

require_once "../../config/auth.php";
require_once "../../config/database.php";
require_once "../../includes/functions.php";

if(!isset($_GET['id']) || !is_numeric($_GET['id'])){
    header("Location:index.php");
    exit();
}

$id = (int)$_GET['id'];

$stmt = $conn->prepare("SELECT * FROM customers WHERE id=?");
$stmt->bind_param("i",$id);
$stmt->execute();

$customer = $stmt->get_result()->fetch_assoc();

if(!$customer){
    header("Location:index.php");
    exit();
}

include "../../templates/header.php";
include "../../templates/sidebar.php";
?>

<div class="main-content">

<?php include "../../templates/navbar.php"; ?>

<div class="table-box">

<div class="d-flex justify-content-between align-items-center mb-4">

<h3>Edit Customer</h3>

<a href="index.php" class="btn btn-secondary">
    <i class="bi bi-arrow-left"></i>
    Back
</a>

</div>

<form action="update.php" method="POST">

<input type="hidden" name="id" value="<?= $customer['id']; ?>">

<div class="row">

<div class="col-md-6 mb-3">
<label class="form-label">First Name</label>
<input type="text" name="first_name" class="form-control"
value="<?= e($customer['first_name']); ?>" required>
</div>

<div class="col-md-6 mb-3">
<label class="form-label">Last Name</label>
<input type="text" name="last_name" class="form-control"
value="<?= e($customer['last_name']); ?>" required>
</div>

<div class="col-md-6 mb-3">
<label class="form-label">Phone</label>
<input type="text" name="phone" class="form-control"
value="<?= e($customer['phone']); ?>">
</div>

<div class="col-md-6 mb-3">
<label class="form-label">Email</label>
<input type="email" name="email" class="form-control"
value="<?= e($customer['email']); ?>">
</div>

<div class="col-md-6 mb-3">
<label class="form-label">Gender</label>

<select name="gender" class="form-select">

<option value="Male" <?= $customer['gender']=="Male"?"selected":""; ?>>Male</option>

<option value="Female" <?= $customer['gender']=="Female"?"selected":""; ?>>Female</option>

<option value="Other" <?= $customer['gender']=="Other"?"selected":""; ?>>Other</option>

</select>

</div>

<div class="col-md-6 mb-3">

<label>Status</label>

<select name="status" class="form-select">

<option value="Active" <?= $customer['status']=="Active"?"selected":""; ?>>Active</option>

<option value="Inactive" <?= $customer['status']=="Inactive"?"selected":""; ?>>Inactive</option>

</select>

</div>

<div class="col-12 mb-3">

<label>Address</label>

<textarea name="address" rows="4" class="form-control"><?= e($customer['address']); ?></textarea>

</div>

</div>

<button class="btn btn-primary">

<i class="bi bi-check-circle"></i>

Update Customer

</button>

</form>

</div>

</div>

<?php include "../../templates/footer.php"; ?>