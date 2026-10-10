<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CartController.php';

$cartUrl = BASE_PATH . '/views/cart.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect($cartUrl);
}

$quantities = $_POST['qty'] ?? [];
$productIds = $_POST['update'] ?? [];

if (!is_array($quantities) || !is_array($productIds) || empty($productIds)) {
    $_SESSION['error'] = 'Nothing to update.';
    redirect($cartUrl);
}

$quantities = array_values($quantities);
$productIds = array_values($productIds);

$cartController = new CartController();
$ip = get_ip();
$updated = 0;
$rejected = 0;

for ($i = 0; $i < count($productIds); $i++) {
    $productId = filter_var($productIds[$i], FILTER_VALIDATE_INT);
    $qty       = filter_var($quantities[$i] ?? '', FILTER_VALIDATE_INT);

    if (!$productId || $productId < 1 || !$qty || $qty < 1 || $qty > 99) {
    $rejected++;
    continue;
    }

    if ($cartController->updateQty($productId, $qty, $ip)) {
    $updated++;
    }
}

if ($rejected > 0) {
    $_SESSION['error'] = 'Quantity must be a whole number between 1 and 99.';
} elseif ($updated > 0) {
    $_SESSION['success'] = 'Cart updated.';
}

redirect($cartUrl);