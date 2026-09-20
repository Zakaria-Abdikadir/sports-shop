<?php

require_once "../../config/auth.php";
require_once "../../config/database.php";
require_once "../../includes/functions.php";

if(!isset($_GET['id']) || !is_numeric($_GET['id'])){
    header("Location:index.php");
    exit();
}

$id = (int)$_GET['id'];

$stmt = $conn->prepare("SELECT * FROM suppliers WHERE id=?");
$stmt->bind_param("i",$id);
$stmt->execute();

$supplier = $stmt->get_result()->fetch_assoc();

if(!$supplier){
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

<h3>Edit Supplier</h3>

<a href="index.php" class="btn btn-secondary">
    <i class="bi bi-arrow-left"></i> Back
</a>

</div>

<form action="update.php" method="POST">

<input type="hidden" name="id" value="<?= $supplier['id']; ?>">

<div class="row">

<div class="col-md-6 mb-3">
<label class="form-label">Supplier Name</label>
<input type="text" name="supplier_name" class="form-control"
value="<?= e($supplier['supplier_name']); ?>" required>
</div>

<div class="col-md-6 mb-3">
<label class="form-label">Contact Person</label>
<input type="text" name="contact_person" class="form-control"
value="<?= e($supplier['contact_person']); ?>">
</div>

<div class="col-md-6 mb-3">
<label class="form-label">Phone</label>
<input type="text" name="phone" class="form-control"
value="<?= e($supplier['phone']); ?>">
</div>

<div class="col-md-6 mb-3">
<label class="form-label">Email</label>
<input type="email" name="email" class="form-control"
value="<?= e($supplier['email']); ?>">
</div>

<div class="col-12 mb-3">
<label class="form-label">Address</label>
<textarea name="address" rows="4" class="form-control"><?= e($supplier['address']); ?></textarea>
</div>

<div class="col-md-6 mb-3">
<label class="form-label">Status</label>

<select name="status" class="form-select">

<option value="Active" <?= $supplier['status']=="Active"?"selected":""; ?>>Active</option>
<option value="Inactive" <?= $supplier['status']=="Inactive"?"selected":""; ?>>Inactive</option>

</select>

</div>

</div>

<button class="btn btn-primary">
<i class="bi bi-check-circle"></i>
Update Supplier
</button>

</form>

</div>

</div>

<?php include "../../templates/footer.php"; ?>