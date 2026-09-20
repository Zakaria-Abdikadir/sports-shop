<?php

require_once "../../config/auth.php";
require_once "../../config/database.php";
require_once "../../includes/functions.php";

include "../../templates/header.php";
include "../../templates/sidebar.php";
?>

<div class="main-content">

<?php include "../../templates/navbar.php"; ?>

<div class="table-box">

<div class="d-flex justify-content-between align-items-center mb-4">

<h3>Add Supplier</h3>

<a href="index.php" class="btn btn-secondary">
    <i class="bi bi-arrow-left"></i>
    Back
</a>

</div>

<form action="save.php" method="POST">

<div class="row">

<div class="col-md-6 mb-3">
<label class="form-label">Supplier Name</label>
<input
type="text"
name="supplier_name"
class="form-control"
required>
</div>

<div class="col-md-6 mb-3">
<label class="form-label">Contact Person</label>
<input
type="text"
name="contact_person"
class="form-control">
</div>

<div class="col-md-6 mb-3">
<label class="form-label">Phone Number</label>
<input
type="text"
name="phone"
class="form-control"
placeholder="07XXXXXXXX">
</div>

<div class="col-md-6 mb-3">
<label class="form-label">Email</label>
<input
type="email"
name="email"
class="form-control">
</div>

<div class="col-12 mb-3">
<label class="form-label">Address</label>
<textarea
name="address"
rows="4"
class="form-control"></textarea>
</div>

<div class="col-md-6 mb-3">
<label class="form-label">Status</label>

<select
name="status"
class="form-select">

<option value="Active">Active</option>
<option value="Inactive">Inactive</option>

</select>

</div>

</div>

<button class="btn btn-primary">

<i class="bi bi-check-circle"></i>

Save Supplier

</button>

</form>

</div>

</div>

<?php include "../../templates/footer.php"; ?>