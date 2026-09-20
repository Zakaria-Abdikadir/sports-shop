<?php

require_once "../../config/init.php";

include "../../templates/header.php";
include "../../templates/sidebar.php";

/*
|--------------------------------------------------------------------------
| Today's Sales
|--------------------------------------------------------------------------
*/

$sql = "
SELECT IFNULL(SUM(total_amount),0) AS total
FROM sales
WHERE DATE(sale_date)=CURDATE()
";

$todaySales = $conn->query($sql)->fetch_assoc()['total'];

/*
|--------------------------------------------------------------------------
| Monthly Sales
|--------------------------------------------------------------------------
*/

$sql = "
SELECT IFNULL(SUM(total_amount),0) AS total
FROM sales
WHERE MONTH(sale_date)=MONTH(CURDATE())
AND YEAR(sale_date)=YEAR(CURDATE())
";

$monthSales = $conn->query($sql)->fetch_assoc()['total'];

/*
|--------------------------------------------------------------------------
| Products Sold This Month
|--------------------------------------------------------------------------
*/

$sql = "
SELECT IFNULL(SUM(si.pieces),0) AS total
FROM sale_items si
INNER JOIN sales s
    ON si.sale_id = s.id
WHERE MONTH(s.sale_date) = MONTH(CURDATE())
AND YEAR(s.sale_date) = YEAR(CURDATE())
";

$productsSold = $conn->query($sql)->fetch_assoc()['total'];

/*
|--------------------------------------------------------------------------
| Profit This Month
|--------------------------------------------------------------------------
*/

$profit = 0;

$result = $conn->query("SHOW COLUMNS FROM products LIKE 'buying_price'");

if($result->num_rows){

    $sql = "
    SELECT
        IFNULL(
            SUM(
                si.subtotal -
                (si.pieces * p.buying_price)
            ),
            0
        ) AS profit
    FROM sale_items si
    INNER JOIN sales s
        ON si.sale_id = s.id
    INNER JOIN products p
        ON si.product_id = p.id
    WHERE MONTH(s.sale_date) = MONTH(CURDATE())
    AND YEAR(s.sale_date) = YEAR(CURDATE())
    ";

    $profit = $conn->query($sql)->fetch_assoc()['profit'];
}

?>

<div class="main-content">

<?php include "../../templates/navbar.php"; ?>

<div class="table-box">

<div class="d-flex justify-content-between align-items-center mb-4">

<h2 class="fw-bold">

<i class="bi bi-bar-chart-line"></i>

Reports Dashboard

</h2>

</div>

<!-- Summary Cards -->

<div class="row">

<div class="col-md-3 mb-4">

<div class="card text-white bg-primary shadow">

<div class="card-body">

<h6>Today's Sales</h6>

<h4>

KES <?= number_format($todaySales,2); ?>

</h4>

</div>

</div>

</div>

<div class="col-md-3 mb-4">

<div class="card text-white bg-success shadow">

<div class="card-body">

<h6>Monthly Sales</h6>

<h4>

KES <?= number_format($monthSales,2); ?>

</h4>

</div>

</div>

</div>

<div class="col-md-3 mb-4">

<div class="card text-white bg-warning shadow">

<div class="card-body">

<h6>Products Sold</h6>

<h4>

<?= number_format($productsSold); ?>

</h4>

</div>

</div>

</div>

<div class="col-md-3 mb-4">

<div class="card text-white bg-dark shadow">

<div class="card-body">

<h6>Total Profit</h6>

<h4>

KES <?= number_format($profit,2); ?>

</h4>

</div>

</div>

</div>

</div>

<hr>

<!-- Report Buttons -->

<div class="row">

<div class="col-md-4 mb-4">

<a href="daily_sales.php" class="btn btn-primary w-100 p-4">

<i class="bi bi-calendar-day fs-2"></i>

<br><br>

Daily Sales Report

</a>

</div>

<div class="col-md-4 mb-4">

<a href="monthly_sales.php" class="btn btn-success w-100 p-4">

<i class="bi bi-calendar-month fs-2"></i>

<br><br>

Monthly Sales Report

</a>

</div>

<div class="col-md-4 mb-4">

<a href="best_products.php" class="btn btn-info w-100 p-4 text-white">

<i class="bi bi-bar-chart fs-2"></i>

<br><br>

Best Selling Products

</a>

</div>

<div class="col-md-6 mb-4">

<a href="low_stock.php" class="btn btn-warning w-100 p-4">

<i class="bi bi-exclamation-triangle fs-2"></i>

<br><br>

Low Stock Report

</a>

</div>

<div class="col-md-6 mb-4">

<a href="profit_report.php" class="btn btn-dark w-100 p-4">

<i class="bi bi-cash-stack fs-2"></i>

<br><br>

Profit Report

</a>

</div>

</div>

</div>

</div>

<?php include "../../templates/footer.php"; ?>