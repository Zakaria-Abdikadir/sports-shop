<?php

require_once "../../config/init.php";

$result = $conn->query("
SELECT

products.id,
products.product_name,
products.item_no,
products.receiving_unit,
products.receiving_qty,
products.selling_unit,
products.selling_qty,

categories.category_name,

IFNULL(inventory.current_stock,0) AS stock,
IFNULL(products.minimum_stock,5) AS minimum_stock

FROM products

LEFT JOIN categories
ON products.category_id = categories.id

LEFT JOIN inventory
ON products.id = inventory.product_id

ORDER BY products.product_name ASC
");

include "../../templates/header.php";
include "../../templates/sidebar.php";
?>

<div class="main-content">

<?php include "../../templates/navbar.php"; ?>

<div class="table-box">

<div class="d-flex justify-content-between align-items-center mb-4">

<div>

<h3>Inventory</h3>

<small class="text-muted">

Current Stock Overview

</small>

</div>

<div>

<a href="stock-in.php" class="btn btn-success">

<i class="bi bi-box-arrow-in-down"></i>

Stock In

</a>

<a href="stock-out.php" class="btn btn-danger">

<i class="bi bi-box-arrow-up"></i>

Stock Out

</a>

<a href="history.php" class="btn btn-dark">

<i class="bi bi-clock-history"></i>

History

</a>

</div>

</div>

<div class="table-responsive">

<table class="table table-bordered table-hover align-middle">

<thead class="table-dark">

<tr>

<th>Item No</th>

<th>Product</th>

<th>Category</th>

<th>Receiving</th>

<th>Selling</th>

<th>Current Stock</th>

<th>Status</th>

</tr>

</thead>

<tbody>

<?php if($result->num_rows>0): ?>

<?php while($row=$result->fetch_assoc()): ?>

<tr>

<td>

<strong>

<?= e($row['item_no']); ?>

</strong>

</td>

<td>

<?= e($row['product_name']); ?>

</td>

<td>

<?= e($row['category_name']); ?>

</td>

<td>

<?= e($row['receiving_unit']); ?>

(<?= $row['receiving_qty']; ?> pcs)

</td>

<td>

<?= e($row['selling_unit']); ?>

(<?= $row['selling_qty']; ?> pcs)

</td>

<td>

<strong>

<?= number_format($row['stock']); ?>

Pieces

</strong>

</td>

<td>

<?php

if($row['stock']==0){

echo '<span class="badge bg-danger">Out of Stock</span>';

}elseif($row['stock']<=$row['minimum_stock']){

echo '<span class="badge bg-warning text-dark">Low Stock</span>';

}else{

echo '<span class="badge bg-success">In Stock</span>';

}

?>

</td>

</tr>

<?php endwhile; ?>

<?php else: ?>

<tr>

<td colspan="7" class="text-center py-5">

No products found.

</td>

</tr>

<?php endif; ?>

</tbody>

</table>

</div>

</div>

</div>

<?php include "../../templates/footer.php"; ?>