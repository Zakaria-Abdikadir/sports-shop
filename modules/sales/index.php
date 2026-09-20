<?php

require_once "../../config/init.php";

$search = "";

if(isset($_GET['search'])){
    $search = trim($_GET['search']);
}

if($search != ""){

    $stmt = $conn->prepare("
    SELECT

    sales.*,

    customers.first_name,
    customers.last_name

    FROM sales

    LEFT JOIN customers
    ON sales.customer_id=customers.id

    WHERE

    invoice_no LIKE ?

    ORDER BY sale_date DESC
    ");

    $keyword="%".$search."%";

    $stmt->bind_param("s",$keyword);

    $stmt->execute();

    $result=$stmt->get_result();

}else{

$result=$conn->query("

SELECT

sales.*,

customers.first_name,
customers.last_name

FROM sales

LEFT JOIN customers
ON sales.customer_id=customers.id

ORDER BY sale_date DESC

");

}

include "../../templates/header.php";
include "../../templates/sidebar.php";

?>

<div class="main-content">

<?php include "../../templates/navbar.php"; ?>

<div class="table-box">

<div class="d-flex justify-content-between align-items-center mb-4">

<div>

<h3>Sales</h3>

<small class="text-muted">

Sales History

</small>

</div>

<div class="d-flex">

<form method="GET" class="me-2">

<input
type="text"
name="search"
class="form-control"
placeholder="Search Invoice..."
value="<?= e($search); ?>">

</form>

<a href="create.php" class="btn btn-primary">

<i class="bi bi-plus-circle"></i>

New Sale

</a>

</div>

</div>

<table class="table table-bordered table-hover align-middle">

<thead class="table-dark">

<tr>

<th>Invoice</th>

<th>Date</th>

<th>Customer</th>

<th>Total</th>

<th>Paid</th>

<th>Balance</th>

<th>Action</th>

</tr>

</thead>

<tbody>

<?php if($result->num_rows>0): ?>

<?php while($row=$result->fetch_assoc()): ?>

<tr>

<td>

<strong>

<?= e($row['invoice_no']); ?>

</strong>

</td>

<td>

<?= date("d M Y",strtotime($row['sale_date'])); ?>

</td>

<td>

<?php

if($row['customer_id']){

echo e($row['first_name']." ".$row['last_name']);

}else{

echo "Walk-in Customer";

}

?>

</td>

<td>

<?= money($row['total_amount']); ?>

</td>

<td>

<?= money($row['amount_paid']); ?>

</td>

<td>

<?= money($row['balance']); ?>

</td>

<td>

<a
href="invoice.php?id=<?= $row['id']; ?>"
class="btn btn-primary btn-sm">

<i class="bi bi-eye"></i>

</a>

<a
href="receipt.php?id=<?= $row['id']; ?>"
class="btn btn-success btn-sm">

<i class="bi bi-printer"></i>

</a>

</td>

</tr>

<?php endwhile; ?>

<?php else: ?>

<tr>

<td colspan="7" class="text-center py-5">

No sales found.

</td>

</tr>

<?php endif; ?>

</tbody>

</table>

</div>

</div>

<?php include "../../templates/footer.php"; ?>