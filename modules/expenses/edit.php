<?php

require_once "../../config/init.php";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location:index.php");
    exit();
}

$id = (int) $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM expenses WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();

$expense = $stmt->get_result()->fetch_assoc();

if (!$expense) {
    header("Location:index.php");
    exit();
}

include "../../templates/header.php";
include "../../templates/sidebar.php";
?>

<div class="main-content">

<?php include "../../templates/navbar.php"; ?>

<div class="table-box">

<div class="d-flex justify-content-between align-items-center mb-4">

<h3>Edit Expense</h3>

<a href="index.php" class="btn btn-secondary">
<i class="bi bi-arrow-left"></i> Back
</a>

</div>

<form action="update.php" method="POST">

<input type="hidden" name="id" value="<?= $expense['id']; ?>">

<div class="row">

<div class="col-md-6 mb-3">
<label class="form-label">Expense Name</label>
<input type="text"
name="expense_name"
class="form-control"
value="<?= e($expense['expense_name']); ?>"
required>
</div>

<div class="col-md-6 mb-3">
<label class="form-label">Category</label>

<select name="category" class="form-select">

<?php

$categories = [
"Rent",
"Transport",
"Salary",
"Utilities",
"Internet",
"Maintenance",
"Other"
];

foreach($categories as $cat){

?>

<option value="<?= $cat; ?>"
<?= $expense['category']==$cat ? 'selected':''; ?>>

<?= $cat; ?>

</option>

<?php } ?>

</select>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">Amount</label>

<input
type="number"
step="0.01"
name="amount"
class="form-control"
value="<?= $expense['amount']; ?>">

</div>

<div class="col-md-6 mb-3">

<label class="form-label">Expense Date</label>

<input
type="date"
name="expense_date"
class="form-control"
value="<?= $expense['expense_date']; ?>">

</div>

<div class="col-12 mb-3">

<label class="form-label">Notes</label>

<textarea
name="notes"
rows="4"
class="form-control"><?= e($expense['notes']); ?></textarea>

</div>

</div>

<button class="btn btn-primary">

<i class="bi bi-check-circle"></i>

Update Expense

</button>

</form>

</div>

</div>

<?php include "../../templates/footer.php"; ?>