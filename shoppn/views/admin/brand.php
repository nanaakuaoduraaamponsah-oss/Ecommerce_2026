<?php
require_once __DIR__ . '/../../core/core.php';
require_admin();
require_once __DIR__ . '/../../controllers/ProductController.php';

$controller = new ProductController();

$editBrand = null;
if (isset($_GET['edit_id'])) {
$editId = filter_var($_GET['edit_id'], FILTER_VALIDATE_INT);
if ($editId) {
    $editBrand = $controller->getBrandById($editId);
}
}

$allBrands = $controller->getAllBrands();

include __DIR__ . '/../layout/header.php';
include __DIR__ . '/../layout/sidebar.php';
?>

<h2><?= $editBrand ? 'Edit Brand' : 'Add Brand' ?></h2>


<?php if (!empty($_SESSION['success'])): ?>
<div class="alert-success"><?= htmlspecialchars($_SESSION['success']) ?></div>
<?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (!empty($_SESSION['error'])): ?>
<div class="alert-error"><?= htmlspecialchars($_SESSION['error']) ?></div>
<?php unset($_SESSION['error']); ?>
<?php endif; ?>

<form action="../../actions/<?= $editBrand ? 'update_brand_action.php' : 'add_brand_action.php' ?>" method="POST">
<?php if ($editBrand): ?>
    <input type="hidden" name="brand_id" value="<?= htmlspecialchars($editBrand['brand_id']) ?>">
<?php endif; ?>
<label>Brand Name
    <input type="text" name="brand_name" value="<?= $editBrand ? htmlspecialchars($editBrand['brand_name']) : '' ?>" required>
</label>
<button type="submit"><?= $editBrand ? 'Update Brand' : 'Add Brand' ?></button>
</form>

<h3>All Brands</h3>
<table border="1" cellpadding="6">
<tr><th>ID</th><th>Name</th><th>Action</th></tr>
<?php foreach ($allBrands as $brand): ?>
    <tr>
    <td><?= htmlspecialchars($brand['brand_id']) ?></td>
    <td><?= htmlspecialchars($brand['brand_name']) ?></td>
    <td><a href="brand.php?edit_id=<?= $brand['brand_id'] ?>">Edit</a></td>
    </tr>
<?php endforeach; ?>
</table>

<?php include __DIR__ . '/../layout/footer.php'; ?>