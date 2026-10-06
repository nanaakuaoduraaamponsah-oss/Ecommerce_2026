<?php
require_once __DIR__ . '/../../controllers/ProductController.php';
$sidebarController = new ProductController();
$sidebarCategories = $sidebarController->getAllCategories();
$sidebarBrands = $sidebarController->getAllBrands();
?>
<aside class="sidebar">
<nav class="sidebar-nav">

    <div class="nav-group">
    <div class="nav-group-title">
        Categories
        <span class="nav-arrow">&#9662;</span>
    </div>
    <ul class="nav-group-items">
        <?php if (empty($sidebarCategories)): ?>
        <li class="nav-empty">No categories yet</li>
        <?php else: ?>
        <?php foreach ($sidebarCategories as $cat): ?>
            <li><a href="<?= BASE_PATH ?>/views/home.php?cat=<?= $cat['cat_id'] ?>"><?= htmlspecialchars($cat['cat_name']) ?></a></li>
        <?php endforeach; ?>
        <?php endif; ?>
    </ul>
    </div>

    <div class="nav-group">
    <div class="nav-group-title">
        Brands
        <span class="nav-arrow">&#9662;</span>
    </div>
    <ul class="nav-group-items">
        <?php if (empty($sidebarBrands)): ?>
        <li class="nav-empty">No brands yet</li>
        <?php else: ?>
        <?php foreach ($sidebarBrands as $brand): ?>
            <li><a href="<?= BASE_PATH ?>/views/home.php?brand=<?= $brand['brand_id'] ?>"><?= htmlspecialchars($brand['brand_name']) ?></a></li>
        <?php endforeach; ?>
        <?php endif; ?>
    </ul>
    </div>

    <?php if (is_admin()): ?>
    <div class="nav-group">
    <div class="nav-group-title">
        Products
        <span class="nav-arrow">&#9662;</span>
    </div>
    <ul class="nav-group-items">
        <li><a href="<?= BASE_PATH ?>/views/admin/product.php">Add Product</a></li>
        <li><a href="<?= BASE_PATH ?>/views/admin/product.php">Manage Products</a></li>
    </ul>
    </div>
    <?php endif; ?>

</nav>
</aside>

<main class="main-content">