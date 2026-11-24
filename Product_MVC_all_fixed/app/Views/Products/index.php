<?php
$page_Title = 'Products List';
$active = 'products';
require __DIR__ . '/../layouts/header.php';
$messages = [
    'created' => 'Product inserted successfully.',
    'updated' => 'Product updated successfully.',
    'deleted' => 'Product deleted successfully.',
];
$success = is_string($_GET['success'] ?? null) ? $_GET['success'] : '';
$errorKey = $_GET['error'] ?? '';
?>
<h2>Product List</h2>
<a href="index.php?action=create" class="btn btn-primary mb-3">Add Product</a>
<?php if (isset($messages[$success])): ?>
<div class="alert alert-success"><?= htmlspecialchars($messages[$success]) ?></div>
<?php endif; ?>
<?php if ($errorKey === 'delete_failed'): ?>
<div class="alert alert-danger">Could not delete the product.</div>
<?php endif; ?>
<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th><th>Product Code</th><th>Product Name</th>
                <th>Category</th><th>Price</th><th>Quantity</th><th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($products)): ?>
            <tr><td colspan="7" class="text-center">No products found.</td></tr>
            <?php else: foreach ($products as $product): ?>
            <tr>
                <td><?= (int)$product['id'] ?></td>
                <td><?= htmlspecialchars($product['product_code']) ?></td>
                <td><?= htmlspecialchars($product['product_name']) ?></td>
                <td><?= htmlspecialchars($product['category']) ?></td>
                <td><?= number_format((float)$product['price'], 2) ?></td>
                <td><?= (int)$product['quantity'] ?></td>
                <td>
                    <a href="index.php?action=edit&id=<?= (int)$product['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                    <form method="post" action="index.php?action=delete&id=<?= (int)$product['id'] ?>"
                          class="d-inline" onsubmit="return confirm('Delete this product?')">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; endif; ?>
        </tbody>
    </table>
</div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
