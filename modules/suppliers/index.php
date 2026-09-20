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
        FROM suppliers
        WHERE supplier_name LIKE ?
           OR contact_person LIKE ?
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
        FROM suppliers
        ORDER BY id DESC
    ");

}

$totalSuppliers = $result->num_rows;

include "../../templates/header.php";
include "../../templates/sidebar.php";
?>

<div class="main-content">

<?php include "../../templates/navbar.php"; ?>

<?php if(isset($_GET['success'])): ?>

<?php if($_GET['success']=="added"): ?>

<div class="alert alert-success alert-dismissible fade show">
Supplier added successfully.
<button class="btn-close" data-bs-dismiss="alert"></button>
</div>

<?php elseif($_GET['success']=="updated"): ?>

<div class="alert alert-warning alert-dismissible fade show">
Supplier updated successfully.
<button class="btn-close" data-bs-dismiss="alert"></button>
</div>

<?php elseif($_GET['success']=="deleted"): ?>

<div class="alert alert-danger alert-dismissible fade show">
Supplier deleted successfully.
<button class="btn-close" data-bs-dismiss="alert"></button>
</div>

<?php endif; ?>

<?php endif; ?>

<div class="table-box">

<div class="d-flex justify-content-between align-items-center mb-4">

<div>

<h3>Suppliers</h3>

<small class="text-muted">

Total Suppliers:

<strong><?= $totalSuppliers ?></strong>

</small>

</div>

<div class="d-flex">

<form method="GET" class="me-2">

<input
type="text"
name="search"
class="form-control"
placeholder="Search supplier..."
value="<?= e($search) ?>">

</form>

<a href="add.php" class="btn btn-primary">

<i class="bi bi-plus-circle"></i>

Add Supplier

</a>

</div>

</div>

<table class="table table-bordered table-hover">

<thead class="table-dark">

<tr>

<th>ID</th>
<th>Supplier</th>
<th>Contact Person</th>
<th>Phone</th>
<th>Status</th>
<th>Action</th>

</tr>

</thead>

<tbody>

<?php if($result->num_rows>0): ?>

<?php while($row=$result->fetch_assoc()): ?>

<tr>

<td><?= $row['id']; ?></td>

<td><?= e($row['supplier_name']); ?></td>

<td><?= e($row['contact_person']); ?></td>

<td><?= e($row['phone']); ?></td>

<td>

<?php if($row['status']=="Active"): ?>

<span class="badge bg-success">Active</span>

<?php else: ?>

<span class="badge bg-danger">Inactive</span>

<?php endif; ?>

</td>

<td>

<a href="edit.php?id=<?= $row['id']; ?>" class="btn btn-warning btn-sm">
<i class="bi bi-pencil"></i>
</a>

<a href="delete.php?id=<?= $row['id']; ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Delete supplier?')">

<i class="bi bi-trash"></i>

</a>

</td>

</tr>

<?php endwhile; ?>

<?php else: ?>

<tr>

<td colspan="6" class="text-center py-5">

No suppliers found.

</td>

</tr>

<?php endif; ?>

</tbody>

</table>

</div>

</div>

<?php include "../../templates/footer.php"; ?>