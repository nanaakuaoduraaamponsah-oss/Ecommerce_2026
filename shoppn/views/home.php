<?php
require_once __DIR__ . '/../core/core.php';
require_login();
include __DIR__ . '/layout/header.php';
include __DIR__ . '/layout/sidebar.php';
?>

<h1>Welcome back, <?= htmlspecialchars($_SESSION['customer_name']) ?>!</h1>
<p>Products will appear here .</p>

<?php include __DIR__ . '/layout/footer.php'; ?>