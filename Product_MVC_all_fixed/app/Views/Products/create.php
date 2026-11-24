<?php
$page_Title = 'Insert Product';
$active = 'create';
require __DIR__ . '/../layouts/header.php';
?>
<h2>Insert Product</h2>
<?php if (!empty($error)): ?>
<div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>
<form method="post" action="index.php?action=store">
    <?php require __DIR__ . '/_form_fields.php'; ?>
</form>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
