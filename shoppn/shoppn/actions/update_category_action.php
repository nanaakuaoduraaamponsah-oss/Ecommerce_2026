<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/ProductController.php';

require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  redirect('../views/admin/category.php');
}

$catId   = filter_var($_POST['cat_id'] ?? '', FILTER_VALIDATE_INT);
$catName = trim(strip_tags($_POST['cat_name'] ?? ''));

if (!$catId || $catId <= 0) {
$_SESSION['error'] = 'Invalid category.';
redirect('../views/admin/category.php');
}

if (strlen($catName) < 2 || strlen($catName) > 100) {
$_SESSION['error'] = 'Category name must be between 2 and 100 characters.';
redirect('../views/admin/category.php');
}

$controller = new ProductController();
$ok = $controller->updateCategory($catId, $catName);

if ($ok) {
$_SESSION['success'] = 'Category updated.';
} else {
$_SESSION['error'] = 'Could not update category. Try again.';
}

redirect('../views/admin/category.php');