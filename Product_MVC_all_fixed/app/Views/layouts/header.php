<?php
// Shared layout (sidebar). Set $page_Title and $active ('dashboard'|'products'|'create') before including.
$page_Title = $page_Title ?? 'Product Management';
$active = $active ?? '';
$navItems = [
    'dashboard' => ['index.php?action=dashboard', 'Dashboard'],
    'products'  => ['index.php?action=products', 'Products list'],
    'create'    => ['index.php?action=create', 'Insert Product'],
];
?>
<!doctype html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_Title) ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="asset/app.css">
</head>
<body>
    <div class="d-flex">
        <nav class="sidebar d-flex flex-column">
            <a href="index.php?action=dashboard" class="sidebar-brand">Welcome</a>
            <ul class="nav flex-column mb-auto">
                <?php foreach ($navItems as $key => [$href, $label]): ?>
                <li class="nav-item">
                    <a class="nav-link<?= $key === $active ? ' active' : '' ?>" href="<?= $href ?>"><?= $label ?></a>
                </li>
                <?php endforeach; ?>
                <li class="nav-item mt-3">
                    <a class="nav-link logout-link" href="index.php?action=logout">Logout</a>
                </li>
            </ul>
        </nav>
        <main class="main-content">
