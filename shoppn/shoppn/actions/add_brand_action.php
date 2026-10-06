<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/ProductController.php';

require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
redirect('../views/admin/brand.php');
}

$brandName = trim(strip_tags($_POST['brand_name'] ?? ''));

if (strlen($brandName) < 2 || strlen($brandName) > 100) {
$_SESSION['error'] = 'Brand name must be between 2 and 100 characters.';
redirect('../views/admin/brand.php');
}

$controller = new ProductController();
$ok = $controller->addBrand($brandName);

if ($ok) {
$_SESSION['success'] = 'Brand added.';
} else {
$_SESSION['error'] = 'Could not add brand. Try again.';
}

redirect('../views/admin/brand.php');