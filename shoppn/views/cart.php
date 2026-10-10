<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CartController.php';

$cartController = new CartController();
$ip    = get_ip();
$items = $cartController->getCartItems($ip);
$total = $cartController->getCartTotal($ip);

include __DIR__ . '/layout/header.php';
include __DIR__ . '/layout/sidebar.php';
?>

<h2>Shopping Cart</h2>

<?php include __DIR__ . '/layout/flash.php'; ?>

<?php if (empty($items)): ?>
    <p>Your cart is empty.</p>
    <a class="btn-primary" href="<?= BASE_PATH ?>/views/home.php">Continue Shopping</a>
<?php else: ?>

    <form id="remove-form" class="hidden-form" action="<?= BASE_PATH ?>/actions/remove_from_cart_action.php" method="POST"></form>
    <form id="update-form" class="hidden-form" action="<?= BASE_PATH ?>/actions/update_qty_action.php" method="POST"></form>

    <table class="cart-table">
    <tr>
        <th>Remove</th>
        <th>Product</th>
        <th>Qty</th>
        <th>Unit Price</th>
        <th>Line Total</th>
    </tr>
    <?php foreach ($items as $item): ?>
        <tr>
        <td><input type="checkbox" name="remove[]" value="<?= (int) $item['p_id'] ?>" form="remove-form"></td>
        <td>
            <div class="cart-product">
            <?php if (!empty($item['product_image'])): ?>
                <img class="cart-thumb" src="<?= BASE_PATH ?>/images/products/<?= htmlspecialchars(rawurlencode($item['product_image'])) ?>" alt="">
            <?php endif; ?>
            <span><?= htmlspecialchars($item['product_title']) ?></span>
            </div>
        </td>
        <td>
            <input class="qty-input" type="number" min="1" max="99" name="qty[]" value="<?= (int) $item['qty'] ?>" form="update-form">
            <input type="hidden" name="update[]" value="<?= (int) $item['p_id'] ?>" form="update-form">
        </td>
        <td>GHS <?= number_format((float) $item['product_price'], 2) ?></td>
        <td>GHS <?= number_format((float) $item['product_price'] * (int) $item['qty'], 2) ?></td>
        </tr>
    <?php endforeach; ?>
    <tr class="cart-subtotal">
        <td colspan="4"><strong>Subtotal</strong></td>
        <td><strong>GHS <?= number_format($total, 2) ?></strong></td>
    </tr>
    </table>

    <div class="cart-buttons">
    <button type="submit" class="btn-primary btn-danger" form="remove-form">Remove from Cart</button>
    <button type="submit" class="btn-primary" form="update-form">Update Quantity</button>
    <a class="btn-primary" href="<?= BASE_PATH ?>/index.php">Continue Shopping</a>
    <a class="btn-primary btn-secondary" href="<?= BASE_PATH ?>/views/checkout.php">Checkout</a>
    </div>

<?php endif; ?>

<?php include __DIR__ . '/layout/footer.php'; ?>