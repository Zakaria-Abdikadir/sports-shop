<?php

require_once "../../config/init.php";

$products = $conn->query("
SELECT

p.id,
p.item_no,
p.product_name,

IFNULL(i.current_stock,0) AS current_stock

FROM products p

LEFT JOIN inventory i
ON p.id=i.product_id

WHERE p.status='Active'

ORDER BY p.product_name
");

include "../../templates/header.php";
include "../../templates/sidebar.php";

?>

<div class="main-content">

<?php include "../../templates/navbar.php"; ?>

<div class="table-box">

<div class="d-flex justify-content-between align-items-center mb-4">

<h3>Stock Adjustment</h3>

<a href="index.php" class="btn btn-secondary">

<i class="bi bi-arrow-left"></i>

Back

</a>

</div>

<form action="save-adjustment.php" method="POST">

<div class="row">

<div class="col-md-6 mb-3">

<label>Product</label>

<select
name="product_id"
id="product"
class="form-select"
required>

<option value="">Select Product</option>

<?php while($row=$products->fetch_assoc()): ?>

<option

value="<?= $row['id']; ?>"

data-stock="<?= $row['current_stock']; ?>"

>

<?= e($row['item_no']); ?>

-

<?= e($row['product_name']); ?>

</option>

<?php endwhile; ?>

</select>

</div>

<div class="col-md-6 mb-3">

<label>Current Stock</label>

<input
type="text"
id="current_stock"
class="form-control"
readonly>

</div>

<div class="col-md-6 mb-3">

<label>Physical Count</label>

<input
type="number"
name="physical_stock"
min="0"
class="form-control"
required>

</div>

<div class="col-md-6 mb-3">

<label>Reason</label>

<input
type="text"
name="notes"
class="form-control"
placeholder="Physical count">

</div>

<div class="mt-3">

<button class="btn btn-primary">

Save Adjustment

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

document.getElementById("current_stock").value=option.dataset.stock;

});

</script>

<?php include "../../templates/footer.php"; ?>