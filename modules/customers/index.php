<?php

require_once "../../config/auth.php";
require_once "../../config/database.php";
require_once "../../includes/functions.php";

$search = "";

if(isset($_GET['search'])){
    $search = trim($_GET['search']);
}

if($search != ""){

    $stmt = $conn->prepare("
        SELECT *
        FROM customers
        WHERE first_name LIKE ?
           OR last_name LIKE ?
           OR phone LIKE ?
        ORDER BY id DESC
    ");

    $keyword = "%".$search."%";

    $stmt->bind_param("sss",$keyword,$keyword,$keyword);
    $stmt->execute();

    $result = $stmt->get_result();

}else{

    $result = $conn->query("
        SELECT *
        FROM customers
        ORDER BY id DESC
    ");

}

$totalCustomers = $result->num_rows;

include "../../templates/header.php";
include "../../templates/sidebar.php";
?>

<div class="main-content">

<?php include "../../templates/navbar.php"; ?>

<!-- Success Messages -->

<?php if(isset($_GET['success'])): ?>

    <?php if($_GET['success']=="added"): ?>

        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle-fill"></i>
            Customer added successfully.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>

    <?php elseif($_GET['success']=="updated"): ?>

        <div class="alert alert-warning alert-dismissible fade show">
            <i class="bi bi-pencil-square"></i>
            Customer updated successfully.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>

    <?php elseif($_GET['success']=="deleted"): ?>

        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-trash"></i>
            Customer deleted successfully.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>

    <?php endif; ?>

<?php endif; ?>

<div class="table-box">

<div class="d-flex justify-content-between align-items-center mb-4">

<div>

<h3>Customers</h3>

<small class="text-muted">
Total Customers:
<strong><?= $totalCustomers; ?></strong>
</small>

</div>

<div class="d-flex">

<form method="GET" class="me-2">

<input
type="text"
name="search"
class="form-control"
placeholder="Search customer..."
value="<?= e($search); ?>">

</form>

<a href="add.php" class="btn btn-primary">

<i class="bi bi-plus-circle"></i>

Add Customer

</a>

</div>

</div>

<div class="table-responsive">

<table class="table table-bordered table-hover align-middle">

<thead class="table-dark">

<tr>

<th>ID</th>
<th>Name</th>
<th>Phone</th>
<th>Email</th>
<th>Status</th>
<th width="120">Action</th>

</tr>

</thead>

<tbody>

<?php if($result->num_rows>0): ?>

<?php while($row=$result->fetch_assoc()): ?>

<tr>

<td><?= $row['id']; ?></td>

<td>

<strong>
<?= e($row['first_name']." ".$row['last_name']); ?>
</strong>

</td>

<td><?= e($row['phone']); ?></td>

<td><?= e($row['email']); ?></td>

<td>

<?php if($row['status']=="Active"): ?>

<span class="badge bg-success">

Active

</span>

<?php else: ?>

<span class="badge bg-danger">

Inactive

</span>

<?php endif; ?>

</td>

<td>

<a
href="edit.php?id=<?= $row['id']; ?>"
class="btn btn-warning btn-sm"
title="Edit">

<i class="bi bi-pencil"></i>

</a>

<a
href="delete.php?id=<?= $row['id']; ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Delete this customer?');"
title="Delete">

<i class="bi bi-trash"></i>

</a>

</td>

</tr>

<?php endwhile; ?>

<?php else: ?>

<tr>

<td colspan="6" class="text-center py-5">

<i class="bi bi-people fs-1 d-block mb-2"></i>

No customers found.

</td>

</tr>

<?php endif; ?>

</tbody>

</table>

</div>

</div>

</div>

<?php include "../../templates/footer.php"; ?>