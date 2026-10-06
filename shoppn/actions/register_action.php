<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CustomerController.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
redirect('../views/register.php');
}

$fullName  = trim(strip_tags($_POST['full_name'] ?? ''));
$emailAddr = filter_var(trim($_POST['email_addr'] ?? ''), FILTER_VALIDATE_EMAIL);
$rawPass   = $_POST['user_pass'] ?? '';
$country   = trim(strip_tags($_POST['country'] ?? ''));
$city      = trim(strip_tags($_POST['city'] ?? ''));
$contactNo = trim(strip_tags($_POST['contact_no'] ?? ''));

$errors = [];
if (strlen($fullName) < 2)                   $errors[] = 'Name is too short.';
if (!$emailAddr || strlen($emailAddr) > 50)  $errors[] = 'Please enter a valid email.';
if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d).{8,}$/', $rawPass)) $errors[] = 'Password needs at least 8 characters, including a letter and a number.';
if (strlen($country) > 30)                   $errors[] = 'Country is too long.';
if (strlen($city) > 30)                      $errors[] = 'City is too long.';
if (strlen($contactNo) > 15)                 $errors[] = 'Contact number is too long.';

if (!empty($errors)) {
$_SESSION['error'] = implode(' ', $errors);
redirect('../views/register.php');
}

$controller = new CustomerController();
$outcome = $controller->register([
'name'    => $fullName,
'email'   => $emailAddr,
'pass'    => $rawPass,
'country' => $country,
'city'    => $city,
'contact' => $contactNo,
]);

if ($outcome['success']) {
$_SESSION['customer_id'] = $outcome['customer_id'];
$_SESSION['customer_name'] = $fullName;
$_SESSION['user_role'] = 2;
redirect('../views/account/my_account.php');
}

$_SESSION['error'] = $outcome['error'];
redirect('../views/register.php');