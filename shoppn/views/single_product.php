<?php
require_once __DIR__ . '/../core/core.php';
require_login();
require_once __DIR__ . '/../controllers/ProductController.php';

$productId = filter_var($_GET['pro_id'] ?? '', FILTER_VALIDATE_INT);
if (!$productId || $productId < 1) {
    redirect(BASE_PATH . '/views/home.php');
}

$productController = new ProductController();
$product = $productController->getProductById($productId);
if (!$product) {
    redirect(BASE_PATH . '/views/home.php');
}

include __DIR__ . '/layout/header.php';
include __DIR__ . '/layout/sidebar.php';
?>

<?php include __DIR__ . '/layout/flash.php'; ?>

<div class="product-detail">
    <div class="product-detail-img">
    <?php if (!empty($product['product_image'])): ?>
        <img src="<?= BASE_PATH ?>/images/products/<?= htmlspecialchars(rawurlencode($product['product_image'])) ?>" alt="<?= htmlspecialchars($product['product_title']) ?>">
    <?php else: ?>
        <div class="product-img-placeholder">No image</div>
    <?php endif; ?>
    </div>

    <div class="product-detail-info">
    <h2><?= htmlspecialchars($product['product_title']) ?></h2>
    <p class="product-price">GHS <?= number_format((float) $product['product_price'], 2) ?></p>
    <p><strong>Category:</strong> <?= htmlspecialchars($product['cat_name']) ?></p>
    <p><strong>Brand:</strong> <?= htmlspecialchars($product['brand_name']) ?></p>
    <p><?= nl2br(htmlspecialchars($product['product_desc'] ?? '')) ?></p>
    <?php if (!empty($product['product_keywords'])): ?>
        <p><strong>Keywords:</strong> <?= htmlspecialchars($product['product_keywords']) ?></p>
    <?php endif; ?>
    <a class="btn-primary btn-secondary" href="<?= BASE_PATH ?>/actions/add_to_cart_action.php?add_cart=<?= (int) $product['product_id'] ?>">Add to Cart</a>
    <a class="btn-primary" href="<?= BASE_PATH ?>/views/home.php">Back to shop</a>
    </div>
</div>

<?php include __DIR__ . '/layout/footer.php'; ?>