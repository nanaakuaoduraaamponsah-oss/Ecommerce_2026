<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Shoppn</title>
<link rel="stylesheet" href="<?= BASE_PATH ?>/css/style.css">
</head>
<body>

<header class="app-header">
<a href="<?= BASE_PATH ?>/views/home.php" class="logo-cursive">shoppn</a>

<ul class="nav-links">
    <?php if (is_admin()): ?>
    <li><a href="<?= BASE_PATH ?>/views/admin/brand.php">Brand</a></li>
    <li><a href="<?= BASE_PATH ?>/views/admin/category.php">Category</a></li>
    <li><a href="<?= BASE_PATH ?>/views/admin/product.php">Product</a></li>
    <?php endif; ?>
</ul>

<div class="header-icons">
    <a href="<?= BASE_PATH ?>/views/cart.php" class="icon-btn" title="Cart">
    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <circle cx="9" cy="21" r="1"></circle>
        <circle cx="20" cy="21" r="1"></circle>
        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
    </svg>
    </a>

    <?php if (is_logged_in()): ?>
    <a href="<?= BASE_PATH ?>/views/account/my_account.php" class="icon-btn" title="My Profile">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
        <circle cx="12" cy="7" r="4"></circle>
        </svg>
    </a>
    <a href="<?= BASE_PATH ?>/logout.php" class="icon-btn" title="Logout">Logout</a>
    <?php else: ?>
    <a href="<?= BASE_PATH ?>/views/login.php" class="icon-btn" title="Login">Login</a>
    <?php endif; ?>
</div>
</header>

<div class="app-body">