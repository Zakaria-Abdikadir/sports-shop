<?php

require_once "../../config/init.php";

$date = $_GET['date'] ?? date("Y-m-d");

/*
|--------------------------------------------------------------------------
| Sales List
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
SELECT
    s.id,
    s.invoice_no,
    s.sale_date,
    s.payment_method,
    s.total_amount,
    CONCAT(
        IFNULL(c.first_name,''),
        ' ',
        IFNULL(c.last_name,'')
    ) AS customer
FROM sales s
LEFT JOIN customers c
ON s.customer_id=c.id
WHERE DATE(s.sale_date)=?
ORDER BY s.sale_date DESC
");

$stmt->bind_param("s",$date);

$stmt->execute();

$sales = $stmt->get_result();

/*
|--------------------------------------------------------------------------
| Daily Total
|--------------------------------------------------------------------------
*/

$stmt2 = $conn->prepare("
SELECT
IFNULL(SUM(total_amount),0) total
FROM sales
WHERE DATE(sale_date)=?
");

$stmt2->bind_param("s",$date);

$stmt2->execute();

$total = $stmt2->get_result()->fetch_assoc()['total'];

include "../../templates/header.php";
include "../../templates/sidebar.php";

?>

<div class="main-content">

<?php include "../../templates/navbar.php"; ?>

<div class="table-box">

<div class="d-flex justify-content-between align-items-center mb-4">

<h2 class="fw-bold">

<i class="bi bi-calendar-day"></i>

Daily Sales Report

</h2>

<a href="index.php" class="btn btn-secondary">

<i class="bi bi-arrow-left"></i>

Back

</a>

</div>

<form method="GET" class="row mb-4">

<div class="col-md-3">

<input
type="date"
name="date"
value="<?= $date; ?>"
class="form-control">

</div>

<div class="col-md-2">

<button class="btn btn-primary">

Search

</button>

</div>

</form>

<div class="table-responsive">

<table class="table table-bordered table-striped">

<thead class="table-dark">

<tr>

<th>Invoice</th>

<th>Date</th>

<th>Customer</th>

<th>Payment</th>

<th>Total</th>

<th>Action</th>

</tr>

</thead>

<tbody>

<?php if($sales->num_rows): ?>

<?php while($row=$sales->fetch_assoc()): ?>

<tr>

<td><?= e($row['invoice_no']); ?></td>

<td><?= date("d M Y H:i",strtotime($row['sale_date'])); ?></td>

<td>

<?= trim($row['customer'])=="" ? "Walk-in Customer" : e($row['customer']); ?>

</td>

<td><?= e($row['payment_method']); ?></td>

<td>

KES <?= number_format($row['total_amount'],2); ?>

</td>

<td>

<a
href="../sales/invoice.php?id=<?= $row['id']; ?>"
class="btn btn-sm btn-primary">

View Invoice

</a>

</td>

</tr>

<?php endwhile; ?>

<?php else: ?>

<tr>

<td colspan="6" class="text-center">

No sales found.

</td>

</tr>

<?php endif; ?>

</tbody>

<tfoot>

<tr>

<th colspan="4" class="text-end">

Daily Total

</th>

<th>

KES <?= number_format($total,2); ?>

</th>

<th></th>

</tr>

</tfoot>

</table>

</div>

</div>

</div>

<?php include "../../templates/footer.php"; ?>