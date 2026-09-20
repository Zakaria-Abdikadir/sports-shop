<?php

require_once "../../config/init.php";

$customers = $conn->query("
SELECT
id,
first_name,
last_name
FROM customers
WHERE status='Active'
ORDER BY first_name ASC
");

$products = $conn->query("
SELECT

p.id,
p.item_no,
p.product_name,
p.selling_price,
p.selling_unit,
p.selling_qty,

IFNULL(i.current_stock,0) current_stock

FROM products p

LEFT JOIN inventory i
ON p.id=i.product_id

WHERE p.status='Active'

ORDER BY p.product_name
");

$invoice = "INV".date("YmdHis");

include "../../templates/header.php";
include "../../templates/sidebar.php";

?>

<div class="main-content">

<?php include "../../templates/navbar.php"; ?>

<div class="table-box">

<div class="d-flex justify-content-between align-items-center mb-4">

<h3>New Sale</h3>

<a href="index.php" class="btn btn-secondary">

<i class="bi bi-arrow-left"></i>

Back

</a>

</div>

<form action="save.php" method="POST" id="saleForm">

<input
type="hidden"
name="invoice_no"
value="<?= $invoice ?>">

<div class="row">

<div class="col-md-4">

<label class="form-label">
    Invoice
</label>

<input
    type="text"
    class="form-control"
    value="<?= $invoice ?>"
    readonly>

</div>

<div class="col-md-4">

<label class="form-label">

Date

</label>

<input
class="form-control"
value="<?= date("d M Y H:i"); ?>"
readonly>

</div>

<div class="col-md-4">

<label class="form-label">

Customer

</label>

<select
name="customer_id"
class="form-select">

<option value="">Walk-in Customer</option>

<?php while($c=$customers->fetch_assoc()): ?>

<option value="<?= $c['id']; ?>">

<?= e($c['first_name']." ".$c['last_name']); ?>

</option>

<?php endwhile; ?>

</select>

</div>

</div>

<hr class="my-4">

<h5>Add Product</h5>

<div class="row">

<div class="col-md-6">

<label class="form-label">

Product

</label>

<select
id="product"
class="form-select">

<option value="">Select Product</option>

<?php while($p=$products->fetch_assoc()): ?>

<option

value="<?= $p['id']; ?>"

data-name="<?= e($p['product_name']); ?>"

data-item="<?= e($p['item_no']); ?>"

data-price="<?= $p['selling_price']; ?>"

data-unit="<?= e($p['selling_unit']); ?>"

data-stock="<?= $p['current_stock']; ?>"

data-piece="<?= $p['selling_qty']; ?>"

>

<?= e($p['item_no']); ?>

-

<?= e($p['product_name']); ?>

</option>

<?php endwhile; ?>

</select>

</div>

<div class="col-md-2">

<label>

Price

</label>

<input
    type="number"
    id="price"
    class="form-control"
    step="0.01"
    min="0"
    placeholder="Enter price">

</div>

<div class="col-md-2">

<label>

Unit

</label>

<input
id="unit"
class="form-control"
readonly>

</div>

<div class="col-md-2">

<label>

Stock

</label>

<input
id="stock"
class="form-control"
readonly>

</div>

</div>

<div class="row mt-3">

<div class="col-md-3">

<label>

Quantity

</label>

<input
type="number"
id="qty"
class="form-control"
value="1"
min="1">

</div>

<div class="col-md-3 d-grid">

<label>&nbsp;</label>

<button
type="button"
class="btn btn-primary"
id="addCart">

<i class="bi bi-cart-plus"></i>

Add To Cart

</button>

</div>

</div>

<hr>
<!-- ===========================
     Shopping Cart
=========================== -->

<div class="table-responsive">

<table class="table table-bordered table-hover align-middle" id="cartTable">

<thead class="table-dark">

<tr>

<th width="10%">Item No</th>

<th width="28%">Product</th>

<th width="10%">Unit</th>

<th width="10%">Qty</th>

<th width="12%">Price</th>

<th width="15%">Subtotal</th>

<th width="10%">Action</th>

</tr>

</thead>

<tbody>

<tr id="emptyRow">

<td colspan="7" class="text-center text-muted py-4">

No products added.

</td>

</tr>

</tbody>

</table>

</div>

<!-- Hidden Cart Data -->

<div id="cartInputs"></div>

<hr>

<div class="row">

<div class="col-md-4 offset-md-8">

<table class="table table-bordered">

<tr>

<th>

Grand Total

</th>

<td>

<strong id="grandTotal">

KES 0.00

</strong>

<input
type="hidden"
name="total_amount"
id="totalAmount"
value="0">

</td>

</tr>

<tr>

<th>

Amount Paid

</th>

<td>

<input
type="number"
step="0.01"
class="form-control"
name="amount_paid"
id="amountPaid"
value="0">

</td>

</tr>

<tr>

<th>

Balance

</th>

<td>

<strong id="balance">

KES 0.00

</strong>

<input
type="hidden"
name="balance"
id="balanceInput"
value="0">

</td>

</tr>

<tr>

<th>

Payment

</th>

<td>

<select
name="payment_method"
class="form-select">

<option value="Cash">

Cash

</option>

<option value="Mpesa">

Mpesa

</option>

<option value="Bank">

Bank

</option>

</select>

</td>

</tr>

</table>

</div>

</div>

<div class="mt-4 text-end">

<button
type="submit"
class="btn btn-success btn-lg">

<i class="bi bi-check-circle"></i>

Complete Sale

</button>

</div>

</form>

</div>

</div>
<script>

let cart=[];

const product=document.getElementById("product");
const qty=document.getElementById("qty");

const price=document.getElementById("price");
const unit=document.getElementById("unit");
const stock=document.getElementById("stock");

product.onchange=function(){

let option=this.options[this.selectedIndex];

price.value=option.dataset.price || "";
unit.value=option.dataset.unit || "";
stock.value=option.dataset.stock || "";

};

document.getElementById("addCart").onclick=function(){

if(product.value==""){
     

alert("Please select a product.");

return;

}
let option = product.options[product.selectedIndex];

let qtyValue = parseFloat(qty.value);

let piecePerUnit = parseInt(option.dataset.piece);

let piecePrice = parseFloat(price.value);

let subtotal = qtyValue * piecePerUnit * piecePrice;

let item = {

    id: product.value,

    item: option.dataset.item,

    name: option.dataset.name,

    unit: option.dataset.unit,

    price: piecePrice,

    qty: qtyValue,

    pieces: piecePerUnit,

    subtotal: subtotal

};

cart.push(item);

renderCart();

};

function renderCart(){

let tbody=document.querySelector("#cartTable tbody");

tbody.innerHTML="";

let total=0;

let hidden="";

cart.forEach(function(item,index){

total+=item.subtotal;

tbody.innerHTML+=`

<tr>

<td>${item.item}</td>

<td>${item.name}</td>

<td>${item.unit}</td>

<td>${item.qty}</td>

<td>${item.price.toFixed(2)}</td>

<td>${item.subtotal.toFixed(2)}</td>

<td>

<button
type="button"
class="btn btn-danger btn-sm"
onclick="removeItem(${index})">

<i class="bi bi-trash"></i>

</button>

</td>

</tr>

`;

hidden+=`

<input type="hidden" name="product_id[]" value="${item.id}">
<input type="hidden" name="quantity[]" value="${item.qty}">
<input type="hidden" name="price[]" value="${item.price}">
<input type="hidden" name="pieces[]" value="${item.pieces}">
<input type="hidden" name="unit[]" value="${item.unit}">

`;

});

if(cart.length==0){

tbody.innerHTML=`

<tr>

<td colspan="7" class="text-center">

No products added.

</td>

</tr>

`;

}

document.getElementById("cartInputs").innerHTML=hidden;

document.getElementById("grandTotal").innerHTML="KES "+total.toFixed(2);

document.getElementById("totalAmount").value=total;

calculateBalance();

}

function removeItem(index){

cart.splice(index,1);

renderCart();

}

document.getElementById("amountPaid").addEventListener("input",calculateBalance);

function calculateBalance(){

let total=parseFloat(document.getElementById("totalAmount").value)||0;

let paid=parseFloat(document.getElementById("amountPaid").value)||0;

let balance = total - paid;

document.getElementById("balance").innerHTML="KES "+balance.toFixed(2);

document.getElementById("balanceInput").value=balance;

}

</script>

<?php include "../../templates/footer.php"; ?>