<?php

require_once "../../config/auth.php";
require_once "../../config/database.php";
require_once "../../includes/functions.php";

include "../../templates/header.php";
include "../../templates/sidebar.php";
?>

<div class="main-content">

    <?php include "../../templates/navbar.php"; ?>

    <div class="table-box">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <h3>Add Category</h3>

            <a href="index.php" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i>
                Back
            </a>

        </div>

        <form action="save.php" method="POST">

            <div class="row">

                <div class="col-md-6">

                    <label class="form-label">
                        Category Name
                    </label>

                    <input
                        type="text"
                        name="category_name"
                        class="form-control"
                        placeholder="Example: Jerseys"
                        required>

                </div>

                <div class="col-md-6">

                    <label class="form-label">
                        Status
                    </label>

                    <select
                        name="status"
                        class="form-select">

                        <option value="Active">
                            Active
                        </option>

                        <option value="Inactive">
                            Inactive
                        </option>

                    </select>

                </div>

            </div>

            <div class="mt-3">

                <label class="form-label">
                    Description
                </label>

                <textarea
                    name="description"
                    class="form-control"
                    rows="4"
                    placeholder="Write category description..."></textarea>

            </div>

            <div class="mt-4">

                <button class="btn btn-primary">

                    <i class="bi bi-check-circle"></i>

                    Save Category

                </button>

            </div>

        </form>

    </div>

</div>

<?php include "../../templates/footer.php"; ?>