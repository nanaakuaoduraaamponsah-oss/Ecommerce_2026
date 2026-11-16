<?php
require_once __DIR__ . '/../core/core.php';
require_login();
require_once __DIR__ . '/../controllers/CartController.php';

$cart = new CartController();
if ($cart->getCartCount(get_ip()) === 0) {
    $_SESSION['error'] = 'Your cart is empty.';
    redirect(BASE_PATH . '/views/cart.php');
}
redirect(BASE_PATH . '/views/payment.php');