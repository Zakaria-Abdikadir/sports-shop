<?php

require_once "../../config/auth.php";
require_once "../../config/database.php";
require_once "../../includes/functions.php";

/*
|--------------------------------------------------------------------------
| Search
|--------------------------------------------------------------------------
*/

$search = "";

if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
}

if ($search != "") {

    $stmt = $conn->prepare("
        SELECT
            p.*,
            c.category_name
        FROM products p
        LEFT JOIN categories c
        ON p.category_id = c.id

        WHERE
            p.product_name LIKE ?
            OR p.item_no LIKE ?
            OR p.brand LIKE ?
            OR c.category_name LIKE ?

        ORDER BY p.id DESC
    ");

    $keyword = "%".$search."%";

    $stmt->bind_param(
        "ssss",
        $keyword,
        $keyword,
        $keyword,
        $keyword
    );

    $stmt->execute();

    $result = $stmt->get_result();

} else {

    $result = $conn->query("
        SELECT
            p.*,
            c.category_name
        FROM products p
        LEFT JOIN categories c
        ON p.category_id = c.id
        ORDER BY p.id DESC
    ");

}

$totalProducts = $result->num_rows;

include "../../templates/header.php";
include "../../templates/sidebar.php";

?>

<div class="main-content">

<?php include "../../templates/navbar.php"; ?>

<?php if(isset($_GET['success'])): ?>

    <?php if($_GET['success']=="added"): ?>

        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle-fill"></i>
            Product added successfully.
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>

    <?php elseif($_GET['success']=="updated"): ?>

        <div class="alert alert-warning alert-dismissible fade show">
            <i class="bi bi-pencil-square"></i>
            Product updated successfully.
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>

    <?php elseif($_GET['success']=="deleted"): ?>

        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-trash"></i>
            Product deleted successfully.
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>

    <?php endif; ?>

<?php endif; ?>

<div class="table-box">

<div class="d-flex justify-content-between align-items-center mb-4">

<div>

<h3 class="mb-1">Products</h3>

<small class="text-muted">

Total Products:

<strong><?= $totalProducts; ?></strong>

</small>

</div>

<div class="d-flex">

<form method="GET" class="me-2">

<input
type="text"
name="search"
class="form-control"
placeholder="Search Item No., Product, Brand..."
value="<?= e($search); ?>">

</form>

<a href="add.php" class="btn btn-primary">

<i class="bi bi-plus-circle"></i>

Add Product

</a>

</div>

</div>

<div class="table-responsive">

<table class="table table-bordered table-hover align-middle">

<thead class="table-dark">

<tr>

<th>Image</th>

<th>Item No.</th>

<th>Product</th>

<th>Receiving</th>

<th>Selling</th>

<th>Buying Price</th>

<th>Selling Price</th>

<th>Status</th>

<th width="120">Action</th>

</tr>

</thead>

<tbody>

<?php if($result->num_rows>0): ?>

<?php while($row=$result->fetch_assoc()): ?>

<tr>

<td>

<?php if(!empty($row['product_image']) && file_exists("../../uploads/products/".$row['product_image'])): ?>

<img
src="../../uploads/products/<?= e($row['product_image']); ?>"
width="60"
height="60"
class="rounded shadow-sm border"
style="object-fit:cover;">

<?php else: ?>

<div
class="bg-light border rounded d-flex justify-content-center align-items-center"
style="width:60px;height:60px;">

<i class="bi bi-image text-secondary fs-4"></i>

</div>

<?php endif; ?>

</td>

<td>

<strong>

<?= e($row['item_no']); ?>

</strong>

</td>

<td>

<div class="fw-bold">

<?= e($row['product_name']); ?>

</div>

<small class="text-muted">

Brand:
<strong><?= e($row['brand']); ?></strong>

<br>

Category:
<strong><?= e($row['category_name']); ?></strong>

</small>

</td>

<td>

<strong>

<?= e($row['receiving_unit']); ?>

</strong>

<br>

<small class="text-muted">

<?= $row['receiving_qty']; ?> Pieces

</small>

</td>

<td>

<strong>

<?= e($row['selling_unit']); ?>

</strong>

<br>

<small class="text-muted">

<?= $row['selling_qty']; ?> Pieces

</small>

</td>

<td>

<?= money($row['buying_price']); ?>

</td>

<td>

<?= money($row['selling_price']); ?>

</td>

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
title="Edit Product">

<i class="bi bi-pencil-square"></i>

</a>

<a
href="delete.php?id=<?= $row['id']; ?>"
class="btn btn-danger btn-sm"
title="Delete Product"
onclick="return confirm('Delete this product?')">

<i class="bi bi-trash"></i>

</a>

</td>

</tr>

<?php endwhile; ?>

<?php else: ?>

<tr>

<td colspan="9" class="text-center py-5">

<i class="bi bi-box-seam fs-1 d-block mb-2"></i>

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