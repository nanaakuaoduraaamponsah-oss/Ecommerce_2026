<?php
require_once __DIR__ . '/../../core/core.php';
require_admin();
require_once __DIR__ . '/../../controllers/ProductController.php';

$controller = new ProductController();

$editProduct = null;
if (isset($_GET['edit_id'])) {
    $editId = filter_var($_GET['edit_id'], FILTER_VALIDATE_INT);
    if ($editId) {
    $editProduct = $controller->getProductById($editId);
    }
}

$allCategories = $controller->getAllCategories();
$allBrands     = $controller->getAllBrands();
$allProducts   = $controller->getAllProducts();

include __DIR__ . '/../layout/header.php';
include __DIR__ . '/../layout/sidebar.php';
?>

<h2><?= $editProduct ? 'Edit Product' : 'Add Product' ?></h2>

<?php include __DIR__ . '/../layout/flash.php'; ?>

<form id="product-form" class="form-grid"
    action="<?= BASE_PATH ?>/actions/<?= $editProduct ? 'update_product_action.php' : 'add_product_action.php' ?>"
    method="POST" enctype="multipart/form-data">

    <?php if ($editProduct): ?>
    <input type="hidden" name="product_id" value="<?= (int) $editProduct['product_id'] ?>">
    <?php endif; ?>

    <label class="full">Product Title
    <input type="text" name="product_title" maxlength="200" required
        value="<?= $editProduct ? htmlspecialchars($editProduct['product_title']) : '' ?>">
    </label>

    <label>Category
    <select name="product_cat" required>
        <option value="">-- Select Category --</option>
        <?php foreach ($allCategories as $cat): ?>
        <option value="<?= (int) $cat['cat_id'] ?>"
            <?= ($editProduct && $editProduct['product_cat'] == $cat['cat_id']) ? 'selected' : '' ?>>
            <?= htmlspecialchars($cat['cat_name']) ?>
        </option>
        <?php endforeach; ?>
    </select>
    </label>

    <label>Brand
    <select name="product_brand" required>
        <option value="">-- Select Brand --</option>
        <?php foreach ($allBrands as $brand): ?>
        <option value="<?= (int) $brand['brand_id'] ?>"
            <?= ($editProduct && $editProduct['product_brand'] == $brand['brand_id']) ? 'selected' : '' ?>>
            <?= htmlspecialchars($brand['brand_name']) ?>
        </option>
        <?php endforeach; ?>
    </select>
    </label>

    <label>Price (GHS)
    <input type="number" name="product_price" step="0.01" min="0.01" required
        value="<?= $editProduct ? htmlspecialchars($editProduct['product_price']) : '' ?>">
    </label>

    <label>Keywords
    <input type="text" name="product_keywords" maxlength="100"
        value="<?= $editProduct ? htmlspecialchars($editProduct['product_keywords'] ?? '') : '' ?>">
    </label>

    <label class="full">Description
    <textarea name="product_desc" rows="4" maxlength="500"><?= $editProduct ? htmlspecialchars($editProduct['product_desc'] ?? '') : '' ?></textarea>
    </label>

    <label class="full">Product Image (JPG, PNG, GIF or WEBP, max 2MB)
    <input type="file" name="product_image" accept="image/jpeg,image/png,image/gif,image/webp">
    </label>

    <?php if ($editProduct && !empty($editProduct['product_image'])): ?>
    <div class="full">
        <p>Current image:</p>
        <img class="admin-preview" src="<?= BASE_PATH ?>/images/products/<?= htmlspecialchars(rawurlencode($editProduct['product_image'])) ?>" alt="Current product image">
        <p><small>Leave the file field empty to keep this image.</small></p>
    </div>
    <?php endif; ?>

    <div class="full">
    <button type="submit" class="btn-primary"><?= $editProduct ? 'Update Product' : 'Add Product' ?></button>
    <?php if ($editProduct): ?>
        <a class="btn-primary btn-secondary" href="<?= BASE_PATH ?>/views/admin/product.php">Cancel</a>
    <?php endif; ?>
    </div>
</form>

<h3>All Products</h3>
<table border="1" cellpadding="6" class="admin-table">
    <tr><th>ID</th><th>Image</th><th>Title</th><th>Category</th><th>Brand</th><th>Price</th><th>Action</th></tr>
    <?php foreach ($allProducts as $product): ?>
    <tr>
        <td><?= (int) $product['product_id'] ?></td>
        <td>
        <?php if (!empty($product['product_image'])): ?>
            <img class="admin-thumb" src="<?= BASE_PATH ?>/images/products/<?= htmlspecialchars(rawurlencode($product['product_image'])) ?>" alt="">
        <?php endif; ?>
        </td>
        <td><?= htmlspecialchars($product['product_title']) ?></td>
        <td><?= htmlspecialchars($product['cat_name']) ?></td>
        <td><?= htmlspecialchars($product['brand_name']) ?></td>
        <td>GHS <?= number_format((float) $product['product_price'], 2) ?></td>
        <td><a href="product.php?edit_id=<?= (int) $product['product_id'] ?>">Edit</a></td>
    </tr>
    <?php endforeach; ?>
</table>

<?php include __DIR__ . '/../layout/footer.php'; ?>