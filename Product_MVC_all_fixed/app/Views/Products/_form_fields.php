<?php
$v = fn(string $k) => htmlspecialchars((string)($data[$k] ?? ''));
?>
<div class="mb-3">
    <label for="product_code" class="form-label">Product Code</label>
    <input type="text" name="product_code" id="product_code" class="form-control" maxlength="50" required value="<?= $v('product_code') ?>">
</div>
<div class="mb-3">
    <label for="product_name" class="form-label">Product Name</label>
    <input type="text" name="product_name" id="product_name" class="form-control" maxlength="150" required value="<?= $v('product_name') ?>">
</div>
<div class="mb-3">
    <label for="category" class="form-label">Category</label>
    <input type="text" name="category" id="category" class="form-control" maxlength="100" required value="<?= $v('category') ?>">
</div>
<div class="mb-3">
    <label for="price" class="form-label">Price</label>
    <input type="number" step="0.01" name="price" id="price" class="form-control" min="0" max="999999.99" required value="<?= $v('price') ?>">
</div>
<div class="mb-3">
    <label for="quantity" class="form-label">Quantity</label>
    <input type="number" name="quantity" id="quantity" class="form-control" min="0" step="1" required value="<?= $v('quantity') ?>">
</div>
<?= csrf_field() ?>
<button type="submit" class="btn btn-primary">Save</button>
<a href="index.php?action=products" class="btn btn-secondary">Cancel</a>
