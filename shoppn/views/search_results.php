<?php
require_once __DIR__ . '/../core/core.php';
require_login();
require_once __DIR__ . '/../controllers/ProductController.php';

$searchTerm = trim(strip_tags($_GET['user_query'] ?? ''));
$searchTerm = substr($searchTerm, 0, 100);

if ($searchTerm === '') {
    redirect(BASE_PATH . '/views/home.php');
}

$productController = new ProductController();
$products = $productController->searchProducts($searchTerm);

include __DIR__ . '/layout/header.php';
include __DIR__ . '/layout/sidebar.php';
?>

<h2>Search results for "<?= htmlspecialchars($searchTerm) ?>"</h2>

<?php include __DIR__ . '/layout/flash.php'; ?>

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