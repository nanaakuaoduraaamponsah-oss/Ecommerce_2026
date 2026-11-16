<?php
require_once __DIR__ . '/../core/core.php';
require_login();
require_once __DIR__ . '/../core/paystack_config.php';
require_once __DIR__ . '/../controllers/CartController.php';

$cartController = new CartController();
$ip    = get_ip();
$items = $cartController->getCartItems($ip);
$total = $cartController->getCartTotal($ip);

if (empty($items)) {
    $_SESSION['error'] = 'Your cart is empty.';
    redirect(BASE_PATH . '/views/cart.php');
}

$amountPesewas = (int) round($total * 100);   // Paystack wants the smallest unit

include __DIR__ . '/layout/header.php';
include __DIR__ . '/layout/sidebar.php';
?>

<h2>Payment</h2>
<?php include __DIR__ . '/layout/flash.php'; ?>

<table class="cart-table">
    <tr>
    <th>Product</th>
    <th>Qty</th>
    <th>Unit Price</th>
    <th>Line Total</th>
    </tr>
    <?php foreach ($items as $item): ?>
    <tr>
        <td><?= htmlspecialchars($item['product_title']) ?></td>
        <td><?= (int) $item['qty'] ?></td>
        <td>GHS <?= number_format((float) $item['product_price'], 2) ?></td>
        <td>GHS <?= number_format((float) $item['product_price'] * (int) $item['qty'], 2) ?></td>
    </tr>
    <?php endforeach; ?>
    <tr class="cart-subtotal">
    <td colspan="3"><strong>Total</strong></td>
    <td><strong>GHS <?= number_format($total, 2) ?></strong></td>
    </tr>
</table>

<p>Paying as <strong><?= htmlspecialchars($_SESSION['customer_email']) ?></strong></p>

<div class="cart-buttons">
    <a class="btn-primary" href="<?= BASE_PATH ?>/views/cart.php">Back to Cart</a>
    <button type="button" class="btn-primary btn-secondary" id="pay-btn" onclick="payWithPaystack()">Pay GHS <?= number_format($total, 2) ?></button>
</div>

<script src="https://js.paystack.co/v1/inline.js"></script>
<script>
var redirected = false;

  // Paystack's inline v1 calls "callback"; newer versions call "onSuccess".
  // Both point here so it works whichever one your script version uses.
function paymentDone(response) {
    if (redirected) return;
    redirected = true;
    window.location = '<?= BASE_PATH ?>/actions/process_payment_action.php?ref=' + encodeURIComponent(response.reference);
}

function payWithPaystack() {
    var handler = PaystackPop.setup({
    key: '<?= PAYSTACK_PUBLIC_KEY ?>',
    email: '<?= htmlspecialchars($_SESSION["customer_email"], ENT_QUOTES) ?>',
    amount: <?= $amountPesewas ?>,
    currency: '<?= PAYSTACK_CURRENCY ?>',
    callback: paymentDone,
    onSuccess: paymentDone,
    onClose: function () { alert('Payment cancelled.'); },
    onCancel: function () { alert('Payment cancelled.'); }
    });
    handler.openIframe();
}
</script>

<?php include __DIR__ . '/layout/footer.php'; ?>