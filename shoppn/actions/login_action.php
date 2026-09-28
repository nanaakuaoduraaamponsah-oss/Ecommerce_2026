<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CustomerController.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
redirect('../views/login.php');
}

$emailAddr = filter_var(trim($_POST['email_addr'] ?? ''), FILTER_VALIDATE_EMAIL);
$rawPass   = $_POST['user_pass'] ?? '';

$controller = new CustomerController();
$outcome = $controller->login($emailAddr, $rawPass);

if ($outcome['success']) {
$account = $outcome['customer'];
$_SESSION['customer_id']    = $account['customer_id'];
$_SESSION['customer_name']  = $account['customer_name'];
$_SESSION['customer_email'] = $account['customer_email'];
$_SESSION['user_role']      = $account['user_role'];
redirect('../index.php');
}

$_SESSION['error'] = $outcome['error'];
redirect('../views/login.php');