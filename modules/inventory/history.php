<?php

require_once "../../config/auth.php";
require_once "../../config/database.php";
require_once "../../includes/functions.php";

$result = $conn->query("
SELECT

sm.id,
sm.movement_type,
sm.quantity,
sm.reference,
sm.notes,
sm.created_at,

p.item_no,
p.product_name

FROM stock_movements sm

LEFT JOIN products p
ON sm.product_id = p.id

ORDER BY sm.created_at DESC
");

include "../../templates/header.php";
include "../../templates/sidebar.php";

?>

<div class="main-content">

<?php include "../../templates/navbar.php"; ?>

<div class="table-box">

<div class="d-flex justify-content-between align-items-center mb-4">

<div>

<h3>Stock History</h3>

<small class="text-muted">

All inventory movements

</small>

</div>

<a href="index.php" class="btn btn-secondary">

<i class="bi bi-arrow-left"></i>

Back

</a>

</div>

<div class="table-responsive">

<table class="table table-bordered table-hover align-middle">

<thead class="table-dark">

<tr>

<th>#</th>

<th>Date</th>

<th>Item No.</th>

<th>Product</th>

<th>Movement</th>

<th>Quantity (Pieces)</th>

<th>Reference</th>

<th>Notes</th>

</tr>

</thead>

<tbody>

<?php if($result->num_rows>0): ?>

<?php while($row=$result->fetch_assoc()): ?>

<tr>

<td><?= $row['id']; ?></td>

<td><?= date("d M Y H:i", strtotime($row['created_at'])); ?></td>

<td><?= e($row['item_no']); ?></td>

<td><?= e($row['product_name']); ?></td>

<td>

<?php if($row['movement_type']=="Stock In"): ?>

<span class="badge bg-success">

Stock In

</span>

<?php else: ?>

<span class="badge bg-danger">

Stock Out

</span>

<?php endif; ?>

</td>

<td>

<strong>

<?= number_format($row['quantity']); ?>

</strong>

</td>

<td><?= e($row['reference']); ?></td>

<td><?= e($row['notes']); ?></td>

</tr>

<?php endwhile; ?>

<?php else: ?>

<tr>

<td colspan="8" class="text-center py-5">

<i class="bi bi-clock-history fs-1 d-block mb-2"></i>

No stock history found.

</td>

</tr>

<?php endif; ?>

</tbody>

</table>

</div>

</div>

</div>

<?php include "../../templates/footer.php"; ?>