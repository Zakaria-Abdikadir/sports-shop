<?php

require_once "../../config/auth.php";
require_once "../../config/database.php";
require_once "../../includes/functions.php";

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = (int) $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM categories WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Category not found.");
}

$category = $result->fetch_assoc();

include "../../templates/header.php";
include "../../templates/sidebar.php";
?>

<div class="main-content">

<?php include "../../templates/navbar.php"; ?>

<div class="table-box">

<div class="d-flex justify-content-between align-items-center mb-4">

<h3>Edit Category</h3>

<a href="index.php" class="btn btn-secondary">
    <i class="bi bi-arrow-left"></i>
    Back
</a>

</div>

<form action="update.php" method="POST">

<input type="hidden" name="id" value="<?= $category['id']; ?>">

<div class="row">

<div class="col-md-6">

<label class="form-label">Category Name</label>

<input
type="text"
name="category_name"
class="form-control"
value="<?= e($category['category_name']); ?>"
required>

</div>

<div class="col-md-6">

<label class="form-label">Status</label>

<select name="status" class="form-select">

<option value="Active"
<?= $category['status']=="Active" ? "selected" : ""; ?>>
Active
</option>

<option value="Inactive"
<?= $category['status']=="Inactive" ? "selected" : ""; ?>>
Inactive
</option>

</select>

</div>

</div>

<div class="mt-3">

<label class="form-label">Description</label>

<textarea
name="description"
rows="4"
class="form-control"><?= e($category['description']); ?></textarea>

</div>

<div class="mt-4">

<button class="btn btn-warning">

<i class="bi bi-pencil-square"></i>

Update Category

</button>

</div>

</form>

</div>

</div>

<?php include "../../templates/footer.php"; ?>