<nav class="navbar navbar-expand-lg bg-white shadow-sm rounded mb-4 px-4">

    <div class="container-fluid">

        <div>
            <h4 class="mb-0 fw-bold text-primary">
                <?= APP_NAME ?>
            </h4>
            <small class="text-muted">
                Sports Shop Management System
            </small>
        </div>

        <div class="d-flex align-items-center">

            <span class="me-4 text-secondary">
                Welcome,
                <strong><?= e(currentUser()) ?></strong>
            </span>

            <span id="liveClock" class="me-4 fw-bold text-primary"></span>

            <a href="<?= BASE_URL ?>logout.php" class="btn btn-danger btn-sm">
                <i class="bi bi-box-arrow-right"></i>
                Logout
            </a>

        </div>

    </div>

</nav>