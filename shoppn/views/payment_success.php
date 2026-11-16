<?php
require_once __DIR__ . '/../core/core.php';
require_login();
require_once __DIR__ . '/../controllers/CartController.php';

$orderId = filter_var($_GET['order_id'] ?? '', FILTER_VALIDATE_INT);
$rows = [];
if ($orderId && $orderId > 0) {
    $cartController = new CartController();
    // customer_id is passed so nobody can open someone else's invoice by changing the number in the URL
    $rows = $cartController->getOrderById($orderId, (int) $_SESSION['customer_id']);
}

if (empty($rows)) {
    $_SESSION['error'] = 'Order not found.';
    redirect(BASE_PATH . '/views/home.php');
}

$order = $rows[0];
$total = 0;

include __DIR__ . '/layout/header.php';
include __DIR__ . '/layout/sidebar.php';
?>

<h2>Payment successful &#10003;</h2>
<p>Thank you, <?= htmlspecialchars($_SESSION['customer_name']) ?>. A confirmation email is on its way to <?= htmlspecialchars($_SESSION['customer_email']) ?>.</p>

<h3>Invoice</h3>
<p>
    <strong>Order number:</strong> #<?= (int) $order['order_id'] ?><br>
    <strong>Invoice no:</strong> <?= (int) $order['invoice_no'] ?><br>
    <strong>Date:</strong> <?= htmlspecialchars($order['order_date']) ?><br>
    <strong>Status:</strong> <?= htmlspecialchars(ucfirst($order['order_status'])) ?>
</p>

<table class="cart-table">
    <tr>
    <th>Product</th>
    <th>Qty</th>
    <th>Unit Price</th>
    <th>Line Total</th>
    </tr>
    <?php foreach ($rows as $r): ?>
    <?php $line = (float) $r['product_price'] * (int) $r['qty']; $total += $line; ?>
    <tr>
        <td><?= htmlspecialchars($r['product_title']) ?></td>
        <td><?= (int) $r['qty'] ?></td>
        <td>GHS <?= number_format((float) $r['product_price'], 2) ?></td>
        <td>GHS <?= number_format($line, 2) ?></td>
    </tr>
    <?php endforeach; ?>
    <tr class="cart-subtotal">
    <td colspan="3"><strong>Total paid</strong></td>
    <td><strong>GHS <?= number_format($total, 2) ?></strong></td>
    </tr>
</table>

<div class="cart-buttons">
    <a class="btn-primary" href="<?= BASE_PATH ?>/views/home.php">Continue Shopping</a>
    <button type="button" class="btn-primary btn-secondary" onclick="window.print()">Print Invoice</button>
</div>

<?php include __DIR__ . '/layout/footer.php'; ?>