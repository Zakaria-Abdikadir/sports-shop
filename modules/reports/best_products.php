<?php

require_once "../../config/init.php";

/*
|--------------------------------------------------------------------------
| Best Selling Products
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
SELECT
    p.item_no,
    p.product_name,
    p.selling_unit,
    SUM(si.quantity) AS qty,
    SUM(si.pieces) AS pieces,
    SUM(si.subtotal) AS revenue
FROM sale_items si
INNER JOIN products p
ON si.product_id = p.id
GROUP BY si.product_id
ORDER BY revenue DESC
");

$stmt->execute();

$products = $stmt->get_result();

include "../../templates/header.php";
include "../../templates/sidebar.php";

?>

<div class="main-content">

<?php include "../../templates/navbar.php"; ?>

<div class="table-box">

<div class="d-flex justify-content-between align-items-center mb-4">

<h2 class="fw-bold">

<i class="bi bi-bar-chart-line"></i>

Best Selling Products

</h2>

<a href="index.php" class="btn btn-secondary">

<i class="bi bi-arrow-left"></i>

Back

</a>

</div>

<div class="table-responsive">

<table class="table table-bordered table-striped">

<thead class="table-dark">

<tr>

<th>#</th>

<th>Item No</th>

<th>Product</th>

<th>Unit</th>

<th>Qty Sold</th>

<th>Pieces Sold</th>

<th>Total Revenue</th>

</tr>

</thead>

<tbody>

<?php

$no = 1;

while($row = $products->fetch_assoc()):

?>

<tr>

<td><?= $no++; ?></td>

<td><?= e($row['item_no']); ?></td>

<td><?= e($row['product_name']); ?></td>

<td><?= e($row['selling_unit']); ?></td>

<td><?= number_format($row['qty']); ?></td>

<td><?= number_format($row['pieces']); ?></td>

<td>

KES <?= number_format($row['revenue'],2); ?>

</td>

</tr>

<?php endwhile; ?>

</tbody>

</table>

</div>

</div>

</div>

<?php include "../../templates/footer.php"; ?>