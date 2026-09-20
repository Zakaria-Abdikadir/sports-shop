<?php

require_once "../../config/init.php";
require_once "../../includes/settings.php";

include "../../templates/header.php";
include "../../templates/sidebar.php";
?>

<div class="main-content">

<?php include "../../templates/navbar.php"; ?>

<div class="table-box">

<h2 class="mb-4">
<i class="bi bi-gear"></i>
System Settings
</h2>

<form action="save.php" method="POST" enctype="multipart/form-data">
    <div class="row">

<div class="col-md-6 mb-3">
<label class="form-label">Shop Name</label>
<input type="text"
name="shop_name"
class="form-control"
value="<?= e($settings['shop_name']); ?>">
</div>

<div class="col-md-6 mb-3">
<label class="form-label">Tagline</label>
<input type="text"
name="tagline"
class="form-control"
value="<?= e($settings['tagline']); ?>">
</div>

<div class="col-md-6 mb-3">
<label class="form-label">Phone</label>
<input type="text"
name="phone"
class="form-control"
value="<?= e($settings['phone']); ?>">
</div>

<div class="col-md-6 mb-3">
<label class="form-label">Email</label>
<input type="email"
name="email"
class="form-control"
value="<?= e($settings['email']); ?>">
</div>

<div class="col-md-12 mb-3">
<label class="form-label">Address</label>
<textarea
name="address"
class="form-control"
rows="3"><?= e($settings['address']); ?></textarea>
</div>

<div class="col-md-6 mb-3">
<label class="form-label">Currency</label>
<input type="text"
name="currency"
class="form-control"
value="<?= e($settings['currency']); ?>">
</div>

<div class="col-md-6 mb-3">
<label class="form-label">Logo</label>
<input type="file"
name="logo"
class="form-control">
</div>

<div class="col-md-12 mb-3">
<label class="form-label">Receipt Footer</label>
<textarea
name="receipt_footer"
class="form-control"
rows="4"><?= e($settings['receipt_footer']); ?></textarea>
</div>

<div class="col-md-12">
<button
type="submit"
class="btn btn-success">

<i class="bi bi-save"></i>

Save Settings

</button>
</div>

</div>

</form>

</div>

</div>

<?php include "../../templates/footer.php"; ?>