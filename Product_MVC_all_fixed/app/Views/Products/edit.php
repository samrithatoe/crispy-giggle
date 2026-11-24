<?php
$page_Title = 'Edit Product';
$active = 'products';
require __DIR__ . '/../layouts/header.php';
?>
<h2>Edit Product</h2>
<?php if (!empty($error)): ?>
<div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>
<form method="post" action="index.php?action=update&id=<?= (int)($product['id'] ?? 0) ?>">
    <?php require __DIR__ . '/_form_fields.php'; ?>
</form>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
