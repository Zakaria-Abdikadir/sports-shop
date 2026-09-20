<?php

require_once "../../config/auth.php";
require_once "../../config/database.php";
require_once "../../includes/functions.php";

$products = $conn->query("
SELECT
    p.id,
    p.item_no,
    p.product_name,
    p.selling_unit,
    p.selling_qty,

    IFNULL(i.current_stock,0) AS current_stock

FROM products p

LEFT JOIN inventory i
ON p.id=i.product_id

WHERE p.status='Active'

ORDER BY p.product_name ASC
");

include "../../templates/header.php";
include "../../templates/sidebar.php";
?>

<div class="main-content">

<?php include "../../templates/navbar.php"; ?>

<div class="table-box">

<div class="d-flex justify-content-between align-items-center mb-4">

<h3>Stock Out</h3>

<a href="index.php" class="btn btn-secondary">

<i class="bi bi-arrow-left"></i>

Back

</a>

</div>

<form action="save-stock-out.php" method="POST">

<div class="row">

<div class="col-md-6 mb-3">

<label class="form-label">

Product

</label>

<select
name="product_id"
id="product"
class="form-select"
required>

<option value="">Select Product</option>

<?php while($row=$products->fetch_assoc()): ?>

<option

value="<?= $row['id']; ?>"

data-item="<?= e($row['item_no']); ?>"

data-unit="<?= e($row['selling_unit']); ?>"

data-qty="<?= $row['selling_qty']; ?>"

data-stock="<?= $row['current_stock']; ?>"

>

<?= e($row['product_name']); ?>

</option>

<?php endwhile; ?>

</select>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Item No.

</label>

<input
type="text"
id="item_no"
class="form-control"
readonly>

</div>

<div class="col-md-4 mb-3">

<label class="form-label">

Selling Unit

</label>

<input
type="text"
id="selling_unit"
class="form-control"
readonly>

</div>

<div class="col-md-4 mb-3">

<label class="form-label">

Pieces per Unit

</label>

<input
type="text"
id="selling_qty"
class="form-control"
readonly>

</div>

<div class="col-md-4 mb-3">

<label class="form-label">

Current Stock

</label>

<input
type="text"
id="current_stock"
class="form-control"
readonly>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Quantity to Remove

</label>

<input
type="number"
name="quantity"
min="1"
class="form-control"
required>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Reference

</label>

<input
type="text"
name="reference"
class="form-control">

</div>

<div class="col-12 mb-3">

<label class="form-label">

Reason

</label>

<textarea
name="notes"
rows="3"
class="form-control"
placeholder="Damaged, Lost, Gift, etc."></textarea>

</div>

<button class="btn btn-danger">

<i class="bi bi-box-arrow-up"></i>

Save Stock Out

</button>

</form>

</div>

</div>

<script>

const product=document.getElementById("product");

product.addEventListener("change",function(){

const option=this.options[this.selectedIndex];

document.getElementById("item_no").value=option.dataset.item||"";
document.getElementById("selling_unit").value=option.dataset.unit||"";
document.getElementById("selling_qty").value=option.dataset.qty||"";
document.getElementById("current_stock").value=option.dataset.stock||"";

});

</script>

<?php include "../../templates/footer.php"; ?>