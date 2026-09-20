<?php

require_once "../../config/auth.php";
require_once "../../config/database.php";
require_once "../../includes/functions.php";

if(!isset($_GET['id']) || !is_numeric($_GET['id'])){
    header("Location:index.php");
    exit();
}

$id = (int)$_GET['id'];

$stmt = $conn->prepare("
SELECT *
FROM products
WHERE id=?
LIMIT 1
");

$stmt->bind_param("i",$id);
$stmt->execute();

$product = $stmt->get_result()->fetch_assoc();

if(!$product){
    header("Location:index.php");
    exit();
}

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

<h3>Edit Product</h3>

<a href="index.php" class="btn btn-secondary">
<i class="bi bi-arrow-left"></i>
Back
</a>

</div>

<form action="update.php" method="POST" enctype="multipart/form-data">

<input type="hidden" name="id" value="<?= $product['id']; ?>">

<input type="hidden" name="old_image" value="<?= $product['product_image']; ?>">

<div class="row">

<!-- Product Name -->

<div class="col-md-6 mb-3">

<label class="form-label">Product Name</label>

<input
type="text"
name="product_name"
class="form-control"
value="<?= e($product['product_name']); ?>"
required>

</div>

<!-- Category -->

<div class="col-md-6 mb-3">

<label class="form-label">Category</label>

<select
name="category_id"
class="form-select"
required>

<?php while($cat=$categories->fetch_assoc()): ?>

<option
value="<?= $cat['id']; ?>"
<?= $cat['id']==$product['category_id'] ? 'selected' : ''; ?>>

<?= e($cat['category_name']); ?>

</option>

<?php endwhile; ?>

</select>

</div>

<!-- Brand -->

<div class="col-md-6 mb-3">

<label class="form-label">Brand</label>

<select name="brand" class="form-select">

<?php

$brands = [
'Nike',
'Adidas',
'Puma',
'Umbro',
'Joma',
'Kelme',
'New Balance',
'Macron',
'Kappa',
'Other'
];

foreach($brands as $brand){

?>

<option value="<?= $brand; ?>" <?= $product['brand']==$brand ? 'selected' : ''; ?>>

<?= $brand; ?>

</option>

<?php } ?>

</select>

</div>

<!-- Item No -->

<div class="col-md-6 mb-3">

<label class="form-label">Item No.</label>

<input
type="text"
name="item_no"
class="form-control"
value="<?= e($product['item_no']); ?>"
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
value="<?= $product['buying_price']; ?>"
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
value="<?= $product['selling_price']; ?>"
required>

</div>

<!-- Receiving Unit -->
<div class="col-md-6 mb-3">
<label class="form-label">Receiving Unit</label>

<select name="receiving_unit" class="form-select">

<option value="Bag" <?= $product['receiving_unit']=="Bag"?"selected":""; ?>>Bag</option>

<option value="Carton" <?= $product['receiving_unit']=="Carton"?"selected":""; ?>>Carton</option>

<option value="Box" <?= $product['receiving_unit']=="Box"?"selected":""; ?>>Box</option>

<option value="Bale" <?= $product['receiving_unit']=="Bale"?"selected":""; ?>>Bale</option>

<option value="Pack" <?= $product['receiving_unit']=="Pack"?"selected":""; ?>>Pack</option>

</select>

</div>

<!-- Receiving Qty -->

<div class="col-md-6 mb-3">

<label class="form-label">

Quantity per Receiving Unit

</label>

<input
type="number"
name="receiving_qty"
class="form-control"
value="<?= $product['receiving_qty']; ?>"
required>

</div>

<!-- Selling Unit -->

<div class="col-md-6 mb-3">

<label class="form-label">

Selling Unit

</label>

<select name="selling_unit" class="form-select">

<option value="Piece" <?= $product['selling_unit']=="Piece"?"selected":""; ?>>Piece</option>

<option value="Dozen" <?= $product['selling_unit']=="Dozen"?"selected":""; ?>>Dozen</option>

<option value="Pair" <?= $product['selling_unit']=="Pair"?"selected":""; ?>>Pair</option>

<option value="Set" <?= $product['selling_unit']=="Set"?"selected":""; ?>>Set</option>

</select>

</div>

<!-- Selling Qty -->

<div class="col-md-6 mb-3">

<label class="form-label">

Quantity per Selling Unit

</label>

<input
type="number"
name="selling_qty"
class="form-control"
value="<?= $product['selling_qty']; ?>"
required>

</div>

<!-- Minimum Stock -->

<div class="col-md-6 mb-3">

<label class="form-label">

Minimum Stock

</label>

<input
type="number"
name="minimum_stock"
class="form-control"
value="<?= $product['minimum_stock']; ?>"
required>

</div>

<!-- Receiving Unit -->

<div class="col-md-6 mb-3">

<label class="form-label">Receiving Unit</label>

<select
name="receiving_unit"
class="form-select">

<?php

$receivingUnits = ['Bag','Carton','Box','Bale','Pack'];

foreach($receivingUnits as $unit){

?>

<option value="<?= $unit; ?>" <?= $product['receiving_unit']==$unit ? 'selected' : ''; ?>>

<?= $unit; ?>

</option>

<?php } ?>

</select>

</div>

<!-- Receiving Qty -->

<div class="col-md-6 mb-3">

<label class="form-label">Quantity per Receiving Unit</label>

<input
type="number"
name="receiving_qty"
class="form-control"
value="<?= $product['receiving_qty']; ?>"
required>

</div>

<!-- Selling Unit -->

<div class="col-md-6 mb-3">

<label class="form-label">Selling Unit</label>

<select
name="selling_unit"
class="form-select">

<?php

$sellingUnits = ['Piece','Dozen','Pair','Set'];

foreach($sellingUnits as $unit){

?>

<option value="<?= $unit; ?>" <?= $product['selling_unit']==$unit ? 'selected' : ''; ?>>

<?= $unit; ?>

</option>

<?php } ?>

</select>

</div>

<!-- Selling Qty -->

<div class="col-md-6 mb-3">

<label class="form-label">Quantity per Selling Unit</label>

<input
type="number"
name="selling_qty"
class="form-control"
value="<?= $product['selling_qty']; ?>"
required>

</div>

<!-- Image -->

<div class="col-md-6 mb-3">

<label class="form-label">Replace Image</label>

<input
type="file"
name="image"
class="form-control">

</div>

<!-- Status -->

<div class="col-md-6 mb-3">

<label class="form-label">Status</label>

<select
name="status"
class="form-select">

<option value="Active" <?= $product['status']=="Active" ? "selected" : ""; ?>>
Active
</option>

<option value="Inactive" <?= $product['status']=="Inactive" ? "selected" : ""; ?>>
Inactive
</option>

</select>

</div>

<!-- Description -->

<div class="col-12">

<label class="form-label">Description</label>

<textarea
name="description"
rows="4"
class="form-control"><?= e($product['description']); ?></textarea>

</div>

<div class="mt-4">

<button class="btn btn-primary">

<i class="bi bi-check-circle"></i>

Update Product

</button>

</div>

</div>

</form>

</div>

</div>

<?php include "../../templates/footer.php"; ?>