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
        SELECT *
        FROM categories
        WHERE category_name LIKE ?
        OR description LIKE ?
        ORDER BY id DESC
    ");

    $keyword = "%".$search."%";

    $stmt->bind_param("ss", $keyword, $keyword);
    $stmt->execute();

    $result = $stmt->get_result();

} else {

   /*
|--------------------------------------------------------------------------
| Search + Pagination
|--------------------------------------------------------------------------
*/

$limit = 10;

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

if ($page < 1) {
    $page = 1;
}

$offset = ($page - 1) * $limit;

$search = "";

if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
}

if ($search != "") {

    $keyword = "%".$search."%";

    // Count total records
    $count = $conn->prepare("
        SELECT COUNT(*) AS total
        FROM categories
        WHERE category_name LIKE ?
        OR description LIKE ?
    ");

    $count->bind_param("ss", $keyword, $keyword);
    $count->execute();

    $totalRecords = $count->get_result()->fetch_assoc()['total'];

    // Get paginated records
    $stmt = $conn->prepare("
        SELECT *
        FROM categories
        WHERE category_name LIKE ?
        OR description LIKE ?
        ORDER BY id DESC
        LIMIT ?, ?
    ");

    $stmt->bind_param("ssii", $keyword, $keyword, $offset, $limit);
    $stmt->execute();

    $result = $stmt->get_result();

} else {

    $count = $conn->query("SELECT COUNT(*) AS total FROM categories");
    $totalRecords = $count->fetch_assoc()['total'];

    $result = $conn->query("
        SELECT *
        FROM categories
        ORDER BY id DESC
        LIMIT $offset, $limit
    ");
}

$totalPages = ceil($totalRecords / $limit);

}

$totalCategories = $result->num_rows;

include "../../templates/header.php";
include "../../templates/sidebar.php";
?>

<div class="main-content">

    <?php include "../../templates/navbar.php"; ?>

    <?php if(isset($_GET['success'])): ?>

        <?php if($_GET['success']=="added"): ?>

            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle-fill"></i>
                Category added successfully.
                <button class="btn-close" data-bs-dismiss="alert"></button>
            </div>

        <?php elseif($_GET['success']=="updated"): ?>

            <div class="alert alert-warning alert-dismissible fade show">
                <i class="bi bi-pencil-square"></i>
                Category updated successfully.
                <button class="btn-close" data-bs-dismiss="alert"></button>
            </div>

        <?php elseif($_GET['success']=="deleted"): ?>

            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-trash"></i>
                Category deleted successfully.
                <button class="btn-close" data-bs-dismiss="alert"></button>
            </div>

        <?php endif; ?>

    <?php endif; ?>

    <div class="table-box">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>
                <h3 class="mb-1">Categories</h3>
                <small class="text-muted">
                    Total Categories:
<strong><?= $totalRecords; ?></strong>
                </small>
            </div>

            <div class="d-flex">

                <form method="GET" class="me-2">

                    <input
                        type="text"
                        name="search"
                        class="form-control"
                        placeholder="Search category..."
                        value="<?= e($search); ?>">

                </form>

                <a href="add.php" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i>
                    Add Category
                </a>

            </div>

        </div>

        <div class="table-responsive">

            <table class="table table-bordered table-hover align-middle">

                <thead class="table-dark">

                    <tr>
                        <th width="70">ID</th>
                        <th>Category</th>
                        <th>Description</th>
                        <th width="120">Status</th>
                        <th width="180">Action</th>
                    </tr>

                </thead>

                <tbody>

                <?php if($result->num_rows > 0): ?>

                    <?php while($row = $result->fetch_assoc()): ?>

                    <tr>

                        <td><?= $row['id']; ?></td>

                        <td>
                            <strong><?= e($row['category_name']); ?></strong>
                        </td>

                        <td><?= e($row['description']); ?></td>

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

                            <a href="edit.php?id=<?= $row['id']; ?>"
                               class="btn btn-warning btn-sm">

                                <i class="bi bi-pencil-square"></i>
                                Edit

                            </a>

                            <a href="delete.php?id=<?= $row['id']; ?>"
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Are you sure you want to delete this category?');">

                                <i class="bi bi-trash"></i>
                                Delete

                            </a>

                        </td>

                    </tr>

                    <?php endwhile; ?>

                <?php else: ?>

                    <tr>

                        <td colspan="5" class="text-center py-5 text-muted">

                            <i class="bi bi-folder-x fs-1 d-block mb-2"></i>

                            No categories found.

                        </td>

                    </tr>

                <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<?php if($totalPages > 1): ?>

<nav class="mt-4">

    <ul class="pagination justify-content-center">

        <?php for($i = 1; $i <= $totalPages; $i++): ?>

            <li class="page-item <?= ($page == $i) ? 'active' : ''; ?>">

                <a class="page-link"
                   href="?page=<?= $i; ?>&search=<?= urlencode($search); ?>">

                    <?= $i; ?>

                </a>

            </li>

        <?php endfor; ?>

    </ul>

</nav>

<?php endif; ?>

<?php include "../../templates/footer.php"; ?>