<?php
$page_Title = 'Dashboard';
$active = 'dashboard';
require __DIR__ . '/../layouts/header.php';
?>
<h2 class="fw-bold mb-4 text-dark">Dashboard</h2>
<p>សូមស្វាគមន៍ <?= htmlspecialchars($_SESSION['username'] ?? '') ?></p>
<div class="row">
    <div class="col-md-6 mb-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">ចំនួនផលិតផលសរុប</h5>
                <h2><?= (int)($summary['total_products'] ?? 0) ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">សរុបស្តុក</h5>
                <h2><?= (int)($summary['total_stock'] ?? 0) ?></h2>
            </div>
        </div>
    </div>
</div>
<a href="index.php?action=products" class="btn btn-primary">មើលបញ្ជីផលិតផល</a>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
