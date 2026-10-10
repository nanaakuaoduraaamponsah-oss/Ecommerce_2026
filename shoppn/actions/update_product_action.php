<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../core/upload_helper.php';
require_once __DIR__ . '/../controllers/ProductController.php';

require_admin();

$backUrl = BASE_PATH . '/views/admin/product.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect($backUrl);
}

$controller = new ProductController();

$productId = filter_var($_POST['product_id'] ?? '', FILTER_VALIDATE_INT);
$existing  = ($productId && $productId > 0) ? $controller->getProductById($productId) : false;

if (!$existing) {
    $_SESSION['error'] = 'Product not found.';
    redirect($backUrl);
}

$editUrl = $backUrl . '?edit_id=' . $productId;

$catId    = filter_var($_POST['product_cat'] ?? '', FILTER_VALIDATE_INT);
$brandId  = filter_var($_POST['product_brand'] ?? '', FILTER_VALIDATE_INT);
$title    = trim(strip_tags($_POST['product_title'] ?? ''));
$price    = filter_var($_POST['product_price'] ?? '', FILTER_VALIDATE_FLOAT);
$desc     = trim(strip_tags($_POST['product_desc'] ?? ''));
$keywords = trim(strip_tags($_POST['product_keywords'] ?? ''));

$errors = [];
if (!$catId || !$controller->getCategoryById($catId))   $errors[] = 'Choose a valid category.';
if (!$brandId || !$controller->getBrandById($brandId))  $errors[] = 'Choose a valid brand.';
if (strlen($title) < 2 || strlen($title) > 200)        $errors[] = 'Title must be 2 to 200 characters.';
if ($price === false || $price <= 0 || $price > 1000000) $errors[] = 'Enter a valid price greater than 0.';
if (strlen($desc) > 500)                               $errors[] = 'Description must be 500 characters or fewer.';
if (strlen($keywords) > 100)                           $errors[] = 'Keywords must be 100 characters or fewer.';

if (!empty($errors)) {
    $_SESSION['error'] = implode(' ', $errors);
    redirect($editUrl);
}

// Keep the current image unless a new one is uploaded
$imageName = $existing['product_image'];

$upload = save_product_image($_FILES['product_image'] ?? null);
if ($upload['status'] === 'error') {
    $_SESSION['error'] = $upload['error'];
    redirect($editUrl);
}
if ($upload['status'] === 'ok') {
    $imageName = $upload['filename'];
}

$ok = $controller->updateProduct($productId, $catId, $brandId, $title, $price, $desc, $imageName, $keywords);

if ($ok) {
    // Remove the old file once the new one is safely saved
    if ($upload['status'] === 'ok' && !empty($existing['product_image'])) {
    $oldFile = __DIR__ . '/../images/products/' . basename($existing['product_image']);
    if (is_file($oldFile)) {
        @unlink($oldFile);
    }
    }
    $_SESSION['success'] = 'Product updated.';
} else {
    if ($upload['status'] === 'ok') {
    @unlink(__DIR__ . '/../images/products/' . $upload['filename']);
    }
    $_SESSION['error'] = 'Could not update product. Try again.';
}

redirect($backUrl);