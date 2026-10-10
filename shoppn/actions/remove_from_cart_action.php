<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CartController.php';

$cartUrl = BASE_PATH . '/views/cart.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect($cartUrl);
}

// Accepts the remove[] checkboxes, or a single product_id
$ids = $_POST['remove'] ?? [];
if (!is_array($ids)) {
    $ids = [$ids];
}
if (isset($_POST['product_id'])) {
    $ids[] = $_POST['product_id'];
}

if (empty($ids)) {
    $_SESSION['error'] = 'Select at least one item to remove.';
    redirect($cartUrl);
}

$cartController = new CartController();
$ip = get_ip();
$removed = 0;

foreach ($ids as $rawId) {
    $productId = filter_var($rawId, FILTER_VALIDATE_INT);
    if ($productId && $productId > 0 && $cartController->removeFromCart($productId, $ip)) {
    $removed++;
    }
}

if ($removed > 0) {
    $_SESSION['success'] = $removed === 1 ? 'Item removed from your cart.' : 'Items removed from your cart.';
} else {
    $_SESSION['error'] = 'Nothing was removed. Please try again.';
}

redirect($cartUrl);