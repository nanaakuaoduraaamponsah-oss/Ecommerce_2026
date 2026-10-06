<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/ProductController.php';

require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
redirect('../views/admin/brand.php');
}

$brandId   = filter_var($_POST['brand_id'] ?? '', FILTER_VALIDATE_INT);
$brandName = trim(strip_tags($_POST['brand_name'] ?? ''));

if (!$brandId || $brandId <= 0) {
$_SESSION['error'] = 'Invalid brand.';
redirect('../views/admin/brand.php');
}

if (strlen($brandName) < 2 || strlen($brandName) > 100) {
$_SESSION['error'] = 'Brand name must be between 2 and 100 characters.';
redirect('../views/admin/brand.php');
}

$controller = new ProductController();
$ok = $controller->updateBrand($brandId, $brandName);

if ($ok) {
$_SESSION['success'] = 'Brand updated.';
} else {
$_SESSION['error'] = 'Could not update brand. Try again.';
}

redirect('../views/admin/brand.php');