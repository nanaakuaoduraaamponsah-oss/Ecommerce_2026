<?php
require_once __DIR__ . '/../../core/core.php';
require_admin();
require_once __DIR__ . '/../../controllers/ProductController.php';

$controller = new ProductController();

$editCat = null;
if (isset($_GET['edit_id'])) {
$editId = filter_var($_GET['edit_id'], FILTER_VALIDATE_INT);
if ($editId) {
    $editCat = $controller->getCategoryById($editId);
}
}

$allCats = $controller->getAllCategories();

include __DIR__ . '/../layout/header.php';
include __DIR__ . '/../layout/sidebar.php';
?>

<h2><?= $editCat ? 'Edit Category' : 'Add Category' ?></h2>

<?php if (!empty($_SESSION['success'])): ?>
<div class="alert-success"><?= htmlspecialchars($_SESSION['success']) ?></div>
<?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (!empty($_SESSION['error'])): ?>
<div class="alert-error"><?= htmlspecialchars($_SESSION['error']) ?></div>
<?php unset($_SESSION['error']); ?>
<?php endif; ?>

<form action="../../actions/<?= $editCat ? 'update_category_action.php' : 'add_category_action.php' ?>" method="POST">
<?php if ($editCat): ?>
    <input type="hidden" name="cat_id" value="<?= htmlspecialchars($editCat['cat_id']) ?>">
<?php endif; ?>
<label>Category Name
    <input type="text" name="cat_name" value="<?= $editCat ? htmlspecialchars($editCat['cat_name']) : '' ?>" required>
</label>
<button type="submit"><?= $editCat ? 'Update Category' : 'Add Category' ?></button>
</form>

<h3>All Categories</h3>
<table border="1" cellpadding="6">
<tr><th>ID</th><th>Name</th><th>Action</th></tr>
<?php foreach ($allCats as $cat): ?>
    <tr>
    <td><?= htmlspecialchars($cat['cat_id']) ?></td>
    <td><?= htmlspecialchars($cat['cat_name']) ?></td>
    <td><a href="category.php?edit_id=<?= $cat['cat_id'] ?>">Edit</a></td>
    </tr>
<?php endforeach; ?>
</table>

<?php include __DIR__ . '/../layout/footer.php'; ?>