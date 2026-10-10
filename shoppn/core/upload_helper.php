<?php
// Validates and stores a product image. Returns:
// ['status' => 'none' | 'ok' | 'error', 'filename' => string|null, 'error' => string|null]
function save_product_image($file) {
if (!is_array($file) || ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
    return ['status' => 'none', 'filename' => null, 'error' => null];
}

if ($file['error'] !== UPLOAD_ERR_OK) {
    return ['status' => 'error', 'filename' => null, 'error' => 'Image upload failed. Please try again.'];
}

  $maxBytes = 2 * 1024 * 1024; // 2MB
if ($file['size'] > $maxBytes) {
    return ['status' => 'error', 'filename' => null, 'error' => 'Image must be 2MB or smaller.'];
}

  // Check the real file contents, not the browser-supplied type or extension
$info = @getimagesize($file['tmp_name']);
$allowed = [
    'image/jpeg' => 'jpg',
    'image/png'  => 'png',
    'image/gif'  => 'gif',
    'image/webp' => 'webp',
];
if (!$info || !isset($allowed[$info['mime']])) {
    return ['status' => 'error', 'filename' => null, 'error' => 'Only JPG, PNG, GIF or WEBP images are allowed.'];
}

$dir = __DIR__ . '/../images/products/';
if (!is_dir($dir) && !mkdir($dir, 0755, true)) {
    return ['status' => 'error', 'filename' => null, 'error' => 'Could not create the upload folder.'];
}

$filename = 'prod_' . bin2hex(random_bytes(8)) . '.' . $allowed[$info['mime']];
if (!move_uploaded_file($file['tmp_name'], $dir . $filename)) {
    return ['status' => 'error', 'filename' => null, 'error' => 'Could not save the image.'];
}

return ['status' => 'ok', 'filename' => $filename, 'error' => null];
}