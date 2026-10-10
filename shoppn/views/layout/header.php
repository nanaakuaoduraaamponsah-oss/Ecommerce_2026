<?php
$authPage = $authPage ?? false;

if (!$authPage) {
    require_once __DIR__ . '/../../controllers/CartController.php';
    $headerCart      = new CartController();
    $headerCartCount = $headerCart->getCartCount(get_ip());
    $headerCartTotal = $headerCart->getCartTotal(get_ip());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Shoppn</title>
<link rel="stylesheet" href="<?= BASE_PATH ?>/css/style.css">
</head>
<body>

<header class="app-header<?= $authPage ? ' auth-header' : '' ?>">
<a href="<?= BASE_PATH ?><?= $authPage ? '/index.php' : '/views/home.php' ?>" class="logo-cursive">shoppn</a>

<?php if (!$authPage): ?>
<form class="header-search" action="<?= BASE_PATH ?>/views/search_results.php" method="GET">
    <input type="text" name="user_query" placeholder="Search products..." maxlength="100"
    value="<?= htmlspecialchars($_GET['user_query'] ?? '') ?>">
    <button type="submit" class="btn-primary btn-small">Search</button>
</form>

<ul class="nav-links">
    <?php if (is_admin()): ?>
    <li><a href="<?= BASE_PATH ?>/views/admin/brand.php">Brand</a></li>
    <li><a href="<?= BASE_PATH ?>/views/admin/category.php">Category</a></li>
    <li><a href="<?= BASE_PATH ?>/views/admin/product.php">Product</a></li>
    <?php endif; ?>
</ul>

<div class="header-icons">
    <a href="<?= BASE_PATH ?>/views/cart.php" class="icon-btn"
    title="Cart: <?= $headerCartCount ?> item(s) | GHS <?= number_format($headerCartTotal, 2) ?>">
    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <circle cx="9" cy="21" r="1"></circle>
        <circle cx="20" cy="21" r="1"></circle>
        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
    </svg>
    <?php if ($headerCartCount > 0): ?>
        <span class="cart-count"><?= $headerCartCount ?></span>
    <?php endif; ?>
    </a>
    <span class="cart-total">GHS <?= number_format($headerCartTotal, 2) ?></span>

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
<?php endif; ?>
</header>

<?php if (!$authPage): ?>
<div class="app-body">
<?php endif; ?>