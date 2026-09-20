<?php

require_once "../../config/init.php";

$limit = $_GET['limit'] ?? 20;

/*
|--------------------------------------------------------------------------
| Low Stock Products
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
SELECT
    p.item_no,
    p.product_name,
    p.selling_unit,
    i.current_stock,
    i.minimum_stock
FROM products p
INNER JOIN inventory i
ON p.id = i.product_id
WHERE i.current_stock <= i.minimum_stock
ORDER BY i.current_stock ASC
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

<i class="bi bi-exclamation-triangle text-warning"></i>

Low Stock Report

</h2>

<a href="index.php" class="btn btn-secondary">

<i class="bi bi-arrow-left"></i>

Back

</a>

</div>

<form method="GET" class="row mb-4">

<div class="col-md-3">

<label class="form-label">

Stock Limit

</label>

<input
type="number"
name="limit"
value="<?= $limit; ?>"
class="form-control">

</div>

<div class="col-md-2 d-flex align-items-end">

<button class="btn btn-warning w-100">

Search

</button>

</div>

</form>

<div class="table-responsive">

<table class="table table-bordered table-striped">

<?php

$no = 1;

if($products->num_rows):

while($row = $products->fetch_assoc()):

?>

<tr>

<td><?= $no++; ?></td>

<td><?= e($row['item_no']); ?></td>

<td><?= e($row['product_name']); ?></td>

<td><?= e($row['selling_unit']); ?></td>

<td><?= number_format($row['current_stock']); ?></td>

<td><?= number_format($row['minimum_stock']); ?></td>

<td>

<?php if($row['current_stock'] <= 0): ?>

<span class="badge bg-danger">Out of Stock</span>

<?php else: ?>

<span class="badge bg-warning text-dark">Low Stock</span>

<?php endif; ?>

</td>

</tr>

<?php endwhile; ?>

<?php else: ?>

<tr>

<td colspan="7" class="text-center">

No low stock products found.

</td>

</tr>

<?php endif; ?>
<tbody>

<?php

$no = 1;

if($products->num_rows):

while($row = $products->fetch_assoc()):

?>

<tr>

<td><?= $no++; ?></td>

<td><?= e($row['item_no']); ?></td>

<td><?= e($row['product_name']); ?></td>

<td><?= e($row['selling_unit']); ?></td>

<td><?= number_format($row['selling_qty']); ?></td>

<td>

<?= number_format($row['current_stock']); ?>

</td>

<td>

<span class="badge bg-danger">

Low Stock

</span>

</td>

</tr>

<?php endwhile; ?>

<?php else: ?>

<tr>

<td colspan="7" class="text-center">

No low stock products found.

</td>

</tr>

<?php endif; ?>

</tbody>

</table>

</div>

</div>

</div>

<?php include "../../templates/footer.php"; ?>