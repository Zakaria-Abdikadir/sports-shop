<?php

require_once "../../config/auth.php";
require_once "../../config/database.php";
require_once "../../includes/functions.php";

/*
|--------------------------------------------------------------------------
| Get Products
|--------------------------------------------------------------------------
*/

$products = $conn->query("
    SELECT
        p.id,
        p.item_no,
        p.product_name,
        p.receiving_unit,
        p.receiving_qty,

        IFNULL(i.current_stock,0) AS current_stock

    FROM products p

    LEFT JOIN inventory i
        ON p.id = i.product_id

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

<h3>Stock In</h3>

<a href="index.php" class="btn btn-secondary">

<i class="bi bi-arrow-left"></i>

Back

</a>

</div>

<form action="save-stock-in.php" method="POST">

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

<option value="">

Select Product

</option>

<?php while($row=$products->fetch_assoc()): ?>

<option

value="<?= $row['id']; ?>"

data-item="<?= e($row['item_no']); ?>"

data-unit="<?= e($row['receiving_unit']); ?>"

data-qty="<?= $row['receiving_qty']; ?>"

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

Receiving Unit

</label>

<input
type="text"
id="receiving_unit"
class="form-control"
readonly>

</div>

<div class="col-md-4 mb-3">

<label class="form-label">

Pieces per Unit

</label>

<input
type="text"
id="receiving_qty"
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

Received Quantity

</label>

<input
type="number"
name="received_quantity"
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
class="form-control"
placeholder="Invoice Number (Optional)">

</div>

<div class="col-12 mb-3">

<label class="form-label">

Notes

</label>

<textarea
name="notes"
rows="3"
class="form-control"></textarea>

</div>

<div class="mt-3">

<button class="btn btn-success">

<i class="bi bi-check-circle"></i>

Save Stock

</button>

</div>

</div>

</form>

</div>

</div>

<script>

const product=document.getElementById("product");

product.addEventListener("change",function(){

const option=this.options[this.selectedIndex];

document.getElementById("item_no").value=
option.dataset.item || "";

document.getElementById("receiving_unit").value=
option.dataset.unit || "";

document.getElementById("receiving_qty").value=
option.dataset.qty || "";

document.getElementById("current_stock").value=
option.dataset.stock || "";

});

</script>

<?php include "../../templates/footer.php"; ?>