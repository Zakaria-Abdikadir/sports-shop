<?php

require_once "../../config/auth.php";
require_once "../../config/database.php";
require_once "../../includes/functions.php";

$categories = $conn->query("
    SELECT *
    FROM categories
    WHERE status='Active'
    ORDER BY category_name ASC
");

include "../../templates/header.php";
include "../../templates/sidebar.php";
?>

<div class="main-content">

<?php include "../../templates/navbar.php"; ?>

<div class="table-box">

<div class="d-flex justify-content-between align-items-center mb-4">

<h3>Add Product</h3>

<a href="index.php" class="btn btn-secondary">
    <i class="bi bi-arrow-left"></i>
    Back
</a>

</div>

<form action="save.php" method="POST" enctype="multipart/form-data">

<div class="row">

<!-- Product Name -->

<div class="col-md-6 mb-3">

<label class="form-label">

Product Name

</label>

<input
type="text"
name="product_name"
class="form-control"
placeholder="Example: Arsenal Home Jersey 25/26"
required>

<small class="text-muted">

Use a clear product name.

</small>

</div>

<!-- Category -->

<div class="col-md-6 mb-3">

<label class="form-label">Category</label>

<select
name="category_id"
class="form-select"
required>

<option value="">Select Category</option>

<?php while($cat = $categories->fetch_assoc()): ?>

<option value="<?= $cat['id']; ?>">
<?= e($cat['category_name']); ?>
</option>

<?php endwhile; ?>

</select>

</div>

<!-- Brand -->

<div class="col-md-6 mb-3">

<label class="form-label">Brand</label>

<input
type="text"
name="brand"
class="form-control"
placeholder="Example: Adidas, Nike, Puma">

<small class="text-muted">
Enter any brand supplied with this product.
</small>

</div>

</div>


<!-- Minimum Stock -->

<div class="col-md-6 mb-3">

<label class="form-label">

Minimum Stock Alert

</label>

<input
type="number"
name="minimum_stock"
class="form-control"
value="5"
min="0"
required>

<small class="text-muted">

System will show Low Stock when inventory reaches this quantity.

</small>

</div>

<!-- Item Number -->

<div class="col-md-6 mb-3">

<label class="form-label">Item No.</label>

<input
type="text"
name="item_no"
class="form-control"
placeholder="Supplier Item Number"
required>

</div>

<!-- Buying Price -->

<div class="col-md-6 mb-3">

<label class="form-label">Buying Price</label>

<input
type="number"
step="0.01"
name="buying_price"
class="form-control"
required>

</div>

<!-- Selling Price -->

<div class="col-md-6 mb-3">

<label class="form-label">Selling Price</label>

<input
type="number"
step="0.01"
name="selling_price"
class="form-control"
required>

</div>

<!-- Receiving Unit -->

<div class="col-md-6 mb-3">

<label class="form-label">Receiving Unit</label>

<select
name="receiving_unit"
class="form-select"
required>

<option value="Bag">Bag</option>
<option value="Carton" selected>Carton</option>
<option value="Box">Box</option>
<option value="Bale">Bale</option>
<option value="Pack">Pack</option>

</select>

</div>

<!-- Quantity per Receiving Unit -->

<div class="col-md-6 mb-3">

<label class="form-label">Quantity per Receiving Unit</label>

<input
type="number"
name="receiving_qty"
class="form-control"
value="1"
min="1"
required>

<small class="text-muted">
Example: 600 pieces per bag, 24 footballs per carton.
</small>

</div>

<!-- Selling Unit -->

<div class="col-md-6 mb-3">

<label class="form-label">Selling Unit</label>

<select
name="selling_unit"
class="form-select"
required>

<option value="Piece">Piece</option>
<option value="Dozen">Dozen</option>
<option value="Pair">Pair</option>
<option value="Set">Set</option>

</select>

</div>

<!-- Quantity per Selling Unit -->

<div class="col-md-6 mb-3">

<label class="form-label">Quantity per Selling Unit</label>

<input
type="number"
name="selling_qty"
class="form-control"
value="1"
min="1"
required>

<small class="text-muted">
Examples:
<ul class="mb-0">
<li>Piece = 1</li>
<li>Dozen = 12</li>
<li>Pair = 2 (if inventory is counted as individual pieces)</li>
<li>Set = 1</li>
</ul>
</small>

</div>

<!-- Product Image -->

<div class="col-md-6 mb-3">

<label class="form-label">

Product Image (Optional)

</label>

<input
type="file"
name="image"
class="form-control"
accept="image/*">

</div>

<!-- Status -->

<div class="col-md-6 mb-3">

<label class="form-label">Status</label>

<select
name="status"
class="form-select">

<option value="Active">Active</option>
<option value="Inactive">Inactive</option>

</select>

</div>

<!-- Description -->

<div class="col-12">

<label class="form-label">

Description

</label>

<small class="text-muted d-block mb-2">

Optional notes about the product.

</small>

<textarea
name="description"
rows="4"
class="form-control"></textarea>

</div>

<div class="mt-4">

<button class="btn btn-primary">

<i class="bi bi-check-circle"></i>

Save Product

</button>

</div>

</div>

</form>

</div>

</div>

<?php include "../../templates/footer.php"; ?>