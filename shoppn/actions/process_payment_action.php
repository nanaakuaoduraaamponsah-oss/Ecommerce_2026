<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../core/paystack_config.php';
require_once __DIR__ . '/../controllers/CartController.php';

require_login();

$failUrl = BASE_PATH . '/views/payment_failed.php';

function fail_payment($message, $failUrl) {
    $_SESSION['error'] = $message;
    redirect($failUrl);
}

// ---- 1. read + sanity-check the reference ----
$reference = trim($_GET['ref'] ?? '');
if (!preg_match('/^[A-Za-z0-9._=-]{5,100}$/', $reference)) {
    fail_payment('Invalid payment reference.', $failUrl);
}

$cart       = new CartController();
$ip         = get_ip();
$customerId = (int) $_SESSION['customer_id'];

// ---- 2. page refreshed / reference reused? show the order we already made ----
$existing = $cart->getOrderIdByReference($reference, $customerId);
if ($existing) {
    redirect(BASE_PATH . '/views/payment_success.php?order_id=' . $existing);
}
if ($cart->referenceExists($reference)) {
    fail_payment('This payment reference has already been used.', $failUrl);
}

// ---- 3. what the cart should cost (from OUR database, never from the browser) ----
$total = $cart->getCartTotal($ip);
if ($total <= 0) {
    fail_payment('Your cart is empty, so there is nothing to pay for.', $failUrl);
}
$expectedPesewas = (int) round($total * 100);

// ---- 4. ask Paystack whether this transaction really succeeded ----
$ch = curl_init('https://api.paystack.co/transaction/verify/' . rawurlencode($reference));
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER     => ['Authorization: Bearer ' . PAYSTACK_SECRET_KEY],
    CURLOPT_TIMEOUT        => 20,
]);
$body    = curl_exec($ch);
$curlErr = curl_error($ch);
curl_close($ch);

if ($body === false) {
    error_log('Paystack verify cURL error: ' . $curlErr);
    fail_payment('We could not reach Paystack to confirm your payment. If you were charged, contact support with reference ' . $reference . '.', $failUrl);
}

$result = json_decode($body, true);
if (!is_array($result) || empty($result['status']) || empty($result['data'])) {
    fail_payment('Paystack could not verify this payment.', $failUrl);
}
$data = $result['data'];

// ---- 5. every one of these must be true before we mark anything as paid ----
$paidOk     = ($data['status'] ?? '') === 'success';
$amountOk   = (int) ($data['amount'] ?? 0) === $expectedPesewas;
$currencyOk = strtoupper($data['currency'] ?? '') === PAYSTACK_CURRENCY;
$emailOk    = strcasecmp($data['customer']['email'] ?? '', $_SESSION['customer_email']) === 0;

if (!$paidOk) {
    fail_payment('Payment was not successful (status: ' . ($data['status'] ?? 'unknown') . ').', $failUrl);
}
if (!$amountOk || !$currencyOk || !$emailOk) {
    error_log("Paystack mismatch ref=$reference paid=" . ($data['amount'] ?? '?') . " expected=$expectedPesewas");
    fail_payment('The amount paid does not match your cart total. Your order was not recorded.', $failUrl);
}

// ---- 6. verified: record the order ----
$orderId = $cart->processPayment($customerId, $ip, $reference, $total);
if (!$orderId) {
    fail_payment('Payment was received but we could not save your order. Please contact support with reference ' . $reference . '.', $failUrl);
}

// ---- 7. confirmation email (a mail() failure must never undo the order) ----
$rows = $cart->getOrderById($orderId, $customerId);
if ($rows) {
    $lines = [];
    $sum   = 0;
    foreach ($rows as $r) {
        $line    = $r['product_price'] * $r['qty'];
        $sum    += $line;
        $lines[] = sprintf('%s  x%d  GHS %s', $r['product_title'], $r['qty'], number_format($line, 2));
    }
    $message = "Hi " . $_SESSION['customer_name'] . ",\n\n"
            . "Thank you for your order. Your payment was received.\n\n"
            . "Invoice no: " . $rows[0]['invoice_no'] . "\n"
            . "Date: " . $rows[0]['order_date'] . "\n\n"
            . implode("\n", $lines) . "\n\n"
            . "Total paid: GHS " . number_format($sum, 2) . "\n\n"
            . "Shoppn";
    $host    = preg_replace('/[^A-Za-z0-9.-]/', '', $_SERVER['HTTP_HOST'] ?? 'localhost');
    $headers = "From: Shoppn <no-reply@{$host}>\r\nContent-Type: text/plain; charset=UTF-8";
    @mail($_SESSION['customer_email'], 'Your Shoppn order #' . $rows[0]['invoice_no'], $message, $headers);
}

redirect(BASE_PATH . '/views/payment_success.php?order_id=' . $orderId);