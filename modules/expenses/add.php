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

<h3 class="mb-4">Add Expense</h3>

<form action="save.php" method="POST">

<div class="row">

<div class="col-md-6 mb-3">

<label>Expense Name</label>

<input
type="text"
name="expense_name"
class="form-control"
required>

</div>

<div class="col-md-6 mb-3">

<label>Category</label>

<select
name="category"
class="form-select">

<option>Rent</option>
<option>Transport</option>
<option>Salary</option>
<option>Utilities</option>
<option>Internet</option>
<option>Maintenance</option>
<option>Other</option>

</select>

</div>

<div class="col-md-6 mb-3">

<label>Amount</label>

<input
type="number"
step="0.01"
name="amount"
class="form-control"
required>

</div>

<div class="col-md-6 mb-3">

<label>Date</label>

<input
type="date"
name="expense_date"
class="form-control"
value="<?= date('Y-m-d'); ?>">

</div>

<div class="col-12 mb-3">

<label>Notes</label>

<textarea
name="notes"
rows="4"
class="form-control"></textarea>

</div>

</div>

<button class="btn btn-primary">

<i class="bi bi-check-circle"></i>

Save Expense

</button>

</form>

</div>

</div>

<?php include "../../templates/footer.php"; ?>