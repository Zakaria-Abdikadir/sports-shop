<?php

require_once "../../config/init.php";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location:index.php");
    exit();
}

$id = (int)$_GET['id'];

/*-------------------------------------------------------
| Invoice Header
-------------------------------------------------------*/

$stmt = $conn->prepare("
SELECT
    s.*,
    CONCAT(IFNULL(c.first_name,''),' ',IFNULL(c.last_name,'')) AS customer_name
FROM sales s
LEFT JOIN customers c
    ON s.customer_id = c.id
WHERE s.id=?
LIMIT 1
");

$stmt->bind_param("i",$id);
$stmt->execute();

$sale = $stmt->get_result()->fetch_assoc();

if(!$sale){
    die("Invoice not found.");
}

/*-------------------------------------------------------
| Invoice Items
-------------------------------------------------------*/

$stmt = $conn->prepare("
SELECT
    si.*,
    p.item_no,
    p.product_name
FROM sale_items si
INNER JOIN products p
ON si.product_id=p.id
WHERE si.sale_id=?
");

$stmt->bind_param("i",$id);
$stmt->execute();

$items = $stmt->get_result();

include "../../templates/header.php";
include "../../templates/sidebar.php";
?>

<div class="main-content">

<?php include "../../templates/navbar.php"; ?>

<div class="table-box">

<div class="d-flex justify-content-between align-items-center mb-4">

<h2 class="fw-bold mb-0">

<i class="bi bi-receipt-cutoff"></i>

Sales Invoice

</h2>

<div>

<button
onclick="window.print();"
class="btn btn-primary">

<i class="bi bi-printer"></i>

Print Invoice

</button>

<a
href="receipt.php?id=<?= $sale['id']; ?>"
class="btn btn-success">

<i class="bi bi-receipt"></i>

Print Receipt

</a>

<a
href="index.php"
class="btn btn-secondary">

<i class="bi bi-arrow-left"></i>

Back

</a>

</div>

</div>


<div class="card shadow-sm border-0">

<div class="card-body p-5">

<div class="row mb-5">

<div class="col-md-6">

<h1
class="text-primary fw-bold">

AZAL SPORT HOUSE

</h1>

<p class="mb-1">

Sports Shop Management System

</p>

<p class="mb-1">

Eastleigh, Nairobi

</p>

<p class="mb-1">

Phone:
+254 XXX XXX XXX

</p>

<p>

Email:
info@azalsporthouse.com

</p>

</div>

<div class="col-md-6 text-end">

<h3 class="fw-bold">

INVOICE

</h3>

<table class="table table-borderless">

<tr>

<th class="text-end">

Invoice #

</th>

<td>

<?= e($sale['invoice_no']); ?>

</td>

</tr>

<tr>

<th class="text-end">

Date

</th>

<td>

<?= date("d M Y H:i",strtotime($sale['sale_date'])); ?>

</td>

</tr>

<tr>

<th class="text-end">

Customer

</th>

<td>

<?= trim($sale['customer_name']) != "" ? e($sale['customer_name']) : "Walk-in Customer"; ?>

</td>

</tr>

<tr>

<th class="text-end">

Payment

</th>

<td>

<?= e($sale['payment_method']); ?>

</td>

</tr>

<tr>

<th class="text-end">

Cashier

</th>

<td>

<?= e($_SESSION['username']); ?>

</td>

</tr>

</table>

</div>

</div>


<div class="table-responsive">

<table class="table table-bordered align-middle">

<thead class="table-dark">

<tr>

<th width="10%">Item No</th>

<th width="35%">Product</th>

<th width="10%">Qty</th>

<th width="10%">Unit</th>

<th width="15%">Unit Price</th>

<th width="20%">Subtotal</th>

</tr>

</thead>

<tbody>

<?php while($row=$items->fetch_assoc()): ?>

<tr>

<td>

<?= e($row['item_no']); ?>

</td>

<td>

<?= e($row['product_name']); ?>

</td>

<td>

<?= number_format($row['quantity']); ?>

</td>

<td>

<?= e($row['unit']); ?>

</td>

<td>

KES <?= number_format($row['unit_price'],2); ?>

</td>

<td>

KES <?= number_format($row['subtotal'],2); ?>

</td>

</tr>

<?php endwhile; ?>

</tbody>

</table>

</div>


<div class="row mt-5">

<div class="col-md-7">

<h5>

Thank you for shopping with us!

</h5>

<p class="text-muted">

Goods once sold cannot be returned unless they are defective.

Please keep this invoice as proof of purchase.

</p>

</div>

<div class="col-md-5">

<table class="table table-bordered">

<tr>

<th>

Grand Total

</th>

<td class="fw-bold">

KES <?= number_format($sale['total_amount'],2); ?>

</td>

</tr>

<tr>

<th>

Amount Paid

</th>

<td>

KES <?= number_format($sale['amount_paid'],2); ?>

</td>

</tr>

<tr>

<th>

Balance

</th>

<td class="<?= $sale['balance']>0 ? 'text-danger' : 'text-success'; ?> fw-bold">

KES <?= number_format($sale['balance'],2); ?>

</td>

</tr>

</table>

</div>

</div>

</div>

</div>

</div>

</div>

<style>

@page{
    size:80mm auto;
    margin:3mm;
}

body{
    width:74mm;
    margin:0 auto;
    font-family:Arial, Helvetica, sans-serif;
    font-size:12px;
    color:#000;
}

@media print{

.sidebar,
.navbar,
.btn,
footer,
header{
    display:none !important;
}

.main-content,
.table-box,
.card{
    margin:0 !important;
    padding:0 !important;
    border:none !important;
    box-shadow:none !important;
    width:100% !important;
}

body{
    width:74mm;
    margin:0 auto;
}

table{
    width:100%;
    border-collapse:collapse;
}

th,
td{
    padding:3px;
    font-size:11px;
}

}
</style>