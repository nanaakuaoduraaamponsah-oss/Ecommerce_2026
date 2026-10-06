<?php
require_once __DIR__ . '/../../core/core.php';
require_login();
require_once __DIR__ . '/../../controllers/ProductController.php';
include __DIR__ . '/../layout/header.php';
include __DIR__ . '/../layout/sidebar.php';
?>

<h2>My Account</h2>

<p><strong>Name:</strong> <?= htmlspecialchars($_SESSION['customer_name']) ?></p>
<p><strong>Email:</strong> <?= htmlspecialchars($_SESSION['customer_email']) ?></p>
<p><strong>Account type:</strong> <?= $_SESSION['user_role'] == 1 ? 'Admin' : 'Customer' ?></p>

<?php include __DIR__ . '/../layout/footer.php'; ?>