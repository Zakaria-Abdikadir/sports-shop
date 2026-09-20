<?php

require_once "config/init.php";

$productResult = $conn->query("SELECT COUNT(*) AS total FROM products");
$totalProducts = $productResult->fetch_assoc()['total'];

$customerResult = $conn->query("SELECT COUNT(*) AS total FROM customers");
$totalCustomers = $customerResult->fetch_assoc()['total'];

$supplierResult = $conn->query("SELECT COUNT(*) AS total FROM suppliers");
$totalSuppliers = $supplierResult->fetch_assoc()['total'];

$categoryResult = $conn->query("SELECT COUNT(*) AS total FROM categories");
$totalCategories = $categoryResult->fetch_assoc()['total'];

$expenseResult = $conn->query("SELECT COUNT(*) AS total FROM expenses");
$totalExpenses = $expenseResult->fetch_assoc()['total'];

$totalExpenseAmount = $conn->query("
    SELECT IFNULL(SUM(amount),0) AS total
    FROM expenses
")->fetch_assoc()['total'];

include "templates/header.php";
include "templates/sidebar.php";
?>

<div class="main-content">

<?php include "templates/navbar.php"; ?>

<div class="cards">

    <div class="card-box border-start border-primary border-5">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h6 class="text-muted">Products</h6>
                <h2><?= $totalProducts; ?></h2>
            </div>
            <i class="bi bi-box-seam fs-1 text-primary"></i>
        </div>
    </div>

    <div class="card-box border-start border-success border-5">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h6 class="text-muted">Customers</h6>
                <h2><?= $totalCustomers; ?></h2>
            </div>
            <i class="bi bi-people fs-1 text-success"></i>
        </div>
    </div>

    <div class="card-box border-start border-warning border-5">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h6 class="text-muted">Suppliers</h6>
                <h2><?= $totalSuppliers; ?></h2>
            </div>
            <i class="bi bi-truck fs-1 text-warning"></i>
        </div>
    </div>

    <div class="card-box border-start border-danger border-5">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h6 class="text-muted">Expenses</h6>
                <h2><?= money($totalExpenseAmount); ?></h2>
            </div>
            <i class="bi bi-cash-stack fs-1 text-danger"></i>
        </div>
    </div>

</div>

<div class="row mt-4">

    <div class="col-lg-8">

        <div class="table-box">

            <h4 class="mb-3">Business Summary</h4>

            <table class="table table-striped">

                <tbody>

                    <tr>
                        <th>Total Categories</th>
                        <td><?= $totalCategories; ?></td>
                    </tr>

                    <tr>
                        <th>Total Products</th>
                        <td><?= $totalProducts; ?></td>
                    </tr>

                    <tr>
                        <th>Total Customers</th>
                        <td><?= $totalCustomers; ?></td>
                    </tr>

                    <tr>
                        <th>Total Suppliers</th>
                        <td><?= $totalSuppliers; ?></td>
                    </tr>

                    <tr>
                        <th>Total Expense Records</th>
                        <td><?= $totalExpenses; ?></td>
                    </tr>

                    <tr>
                        <th>Total Expenses</th>
                        <td class="text-danger fw-bold">
                            <?= money($totalExpenseAmount); ?>
                        </td>
                    </tr>

                </tbody>

            </table>

        </div>

    </div>

    <div class="col-lg-4">

        <div class="table-box">

            <h4 class="mb-3">Quick Actions</h4>

            <div class="d-grid gap-3">

                <a href="modules/products/add.php" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i>
                    Add Product
                </a>

                <a href="modules/customers/add.php" class="btn btn-success">
                    <i class="bi bi-person-plus"></i>
                    Add Customer
                </a>

                <a href="modules/suppliers/add.php" class="btn btn-warning">
                    <i class="bi bi-truck"></i>
                    Add Supplier
                </a>

                <a href="modules/expenses/add.php" class="btn btn-danger">
                    <i class="bi bi-cash-stack"></i>
                    Add Expense
                </a>

            </div>

        </div>

    </div>

</div>

</div>

<?php include "templates/footer.php"; ?>