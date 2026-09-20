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
        FROM expenses
        WHERE expense_name LIKE ?
           OR category LIKE ?
        ORDER BY expense_date DESC
    ");

    $keyword = "%".$search."%";
    $stmt->bind_param("ss",$keyword,$keyword);
    $stmt->execute();
    $result = $stmt->get_result();

}else{

    $result = $conn->query("
        SELECT *
        FROM expenses
        ORDER BY expense_date DESC
    ");

}

$totalExpenses = $result->num_rows;

include "../../templates/header.php";
include "../../templates/sidebar.php";
?>

<div class="main-content">

<?php include "../../templates/navbar.php"; ?>

<?php if(isset($_GET['success'])): ?>

<div class="alert alert-success alert-dismissible fade show">

<?php

switch($_GET['success']){

case "added":
echo "Expense added successfully.";
break;

case "updated":
echo "Expense updated successfully.";
break;

case "deleted":
echo "Expense deleted successfully.";
break;

}

?>

<button class="btn-close" data-bs-dismiss="alert"></button>

</div>

<?php endif; ?>

<div class="table-box">

<div class="d-flex justify-content-between align-items-center mb-4">

<div>

<h3>Expenses</h3>

<small class="text-muted">

Total Records:
<strong><?= $totalExpenses ?></strong>

</small>

</div>

<div class="d-flex">

<form method="GET" class="me-2">

<input
type="text"
name="search"
class="form-control"
placeholder="Search expense..."
value="<?= e($search) ?>">

</form>

<a href="add.php" class="btn btn-primary">

<i class="bi bi-plus-circle"></i>

Add Expense

</a>

</div>

</div>

<table class="table table-bordered table-hover">

<thead class="table-dark">

<tr>

<th>ID</th>
<th>Expense</th>
<th>Category</th>
<th>Amount</th>
<th>Date</th>
<th>Action</th>

</tr>

</thead>

<tbody>

<?php if($result->num_rows>0): ?>

<?php while($row=$result->fetch_assoc()): ?>

<tr>

<td><?= $row['id']; ?></td>

<td><?= e($row['expense_name']); ?></td>

<td><?= e($row['category']); ?></td>

<td>KES <?= number_format($row['amount'],2); ?></td>

<td><?= $row['expense_date']; ?></td>

<td>

<a href="edit.php?id=<?= $row['id']; ?>" class="btn btn-warning btn-sm">
<i class="bi bi-pencil"></i>
</a>

<a href="delete.php?id=<?= $row['id']; ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Delete expense?')">

<i class="bi bi-trash"></i>

</a>

</td>

</tr>

<?php endwhile; ?>

<?php else: ?>

<tr>

<td colspan="6" class="text-center py-5">

No expenses found.

</td>

</tr>

<?php endif; ?>

</tbody>

</table>

</div>

</div>

<?php include "../../templates/footer.php"; ?>