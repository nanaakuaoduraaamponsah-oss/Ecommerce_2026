<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/ProductController.php';
require_once __DIR__ . '/../controllers/CartController.php';

// Return to the page the shopper came from (same site only)
$backUrl = BASE_PATH . '/views/home.php';
$referer = $_SERVER['HTTP_REFERER'] ?? '';
if ($referer !== '') {
    $refHost  = parse_url($referer, PHP_URL_HOST);
    $siteHost = parse_url('http://' . $_SERVER['HTTP_HOST'], PHP_URL_HOST);
    if ($refHost && $refHost === $siteHost) {
    $backUrl = $referer;
    }
}

$productId = filter_var($_GET['add_cart'] ?? '', FILTER_VALIDATE_INT);

if (!$productId || $productId < 1) {
    $_SESSION['error'] = 'Invalid product.';
    redirect($backUrl);
}

$productController = new ProductController();
if (!$productController->getProductById($productId)) {
    $_SESSION['error'] = 'That product no longer exists.';
    redirect($backUrl);
}

$cartController = new CartController();
$ip = get_ip();

if ($cartController->isInCart($productId, $ip)) {
    $_SESSION['error'] = 'This item is already in your cart. Go to cart to update quantity.';
    redirect($backUrl);
}

$customerId = $_SESSION['customer_id'] ?? null;

if ($cartController->addToCart($productId, 1, $ip, $customerId)) {
    $_SESSION['success'] = 'Item added to your cart.';
} else {
    $_SESSION['error'] = 'Could not add the item. Try again.';
}

redirect($backUrl);

