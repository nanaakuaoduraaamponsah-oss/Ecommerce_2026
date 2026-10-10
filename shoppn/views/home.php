<?php
require_once __DIR__ . '/../core/core.php';
require_login();
require_once __DIR__ . '/../controllers/ProductController.php';

$productController = new ProductController();

$heading  = 'Featured Products';
$products = [];

if (isset($_GET['cat'])) {
    $catId = filter_var($_GET['cat'], FILTER_VALIDATE_INT);
    $category = $catId ? $productController->getCategoryById($catId) : false;
    $products = $category ? $productController->getProductsByCategory($catId) : [];
    $heading  = $category ? $category['cat_name'] : 'Category not found';
} elseif (isset($_GET['brand'])) {
    $brandId = filter_var($_GET['brand'], FILTER_VALIDATE_INT);
    $brand = $brandId ? $productController->getBrandById($brandId) : false;
    $products = $brand ? $productController->getProductsByBrand($brandId) : [];
    $heading  = $brand ? $brand['brand_name'] : 'Brand not found';
} else {
    $products = $productController->getFeaturedProducts(12);
}

include __DIR__ . '/layout/header.php';
include __DIR__ . '/layout/sidebar.php';
?>

<h2>Welcome back, <?= htmlspecialchars($_SESSION['customer_name']) ?>!</h2>

<?php include __DIR__ . '/layout/flash.php'; ?>

<h3><?= htmlspecialchars($heading) ?></h3>

<?php if (empty($products)): ?>
    <p>No products found.</p>
<?php else: ?>
    <div class="product-grid">
    <?php foreach ($products as $product): ?>
        <?php include __DIR__ . '/layout/product_card.php'; ?>
    <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php include __DIR__ . '/layout/footer.php'; ?>