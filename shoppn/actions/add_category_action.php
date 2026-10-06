<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/ProductController.php';

require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
redirect('../views/admin/category.php');
}

$catName = trim(strip_tags($_POST['cat_name'] ?? ''));

if (strlen($catName) < 2 || strlen($catName) > 100) {
$_SESSION['error'] = 'Category name must be between 2 and 100 characters.';
redirect('../views/admin/category.php');
}

$controller = new ProductController();
$ok = $controller->addCategory($catName);

if ($ok) {
$_SESSION['success'] = 'Category added.';
} else {
$_SESSION['error'] = 'Could not add category. Try again.';
}

redirect('../views/admin/category.php');