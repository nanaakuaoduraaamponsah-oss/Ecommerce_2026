<?php
require_once __DIR__ . '/../core/core.php';
require_login();

include __DIR__ . '/layout/header.php';
include __DIR__ . '/layout/sidebar.php';
?>

<h2>Payment failed</h2>

<?php if (!empty($_SESSION['error'])): ?>
    <?php include __DIR__ . '/layout/flash.php'; ?>
<?php else: ?>
    <div class="alert-error">Something went wrong with your payment.</div>
<?php endif; ?>

<p>Your order was not recorded and your cart is still saved.</p>

<div class="cart-buttons">
    <a class="btn-primary" href="<?= BASE_PATH ?>/views/cart.php">Back to Cart</a>
    <a class="btn-primary btn-secondary" href="<?= BASE_PATH ?>/views/payment.php">Try Again</a>
</div>

<?php include __DIR__ . '/layout/footer.php'; ?>

