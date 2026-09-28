<?php
require_once __DIR__ . '/../core/core.php';
include __DIR__ . '/layout/header.php';
?>
<section class="auth-box">
<h2>Log In</h2>

<?php if (!empty($_SESSION['error'])): ?>
    <div class="alert-error"><?= htmlspecialchars($_SESSION['error']) ?></div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<form id="login-form" action="../actions/login_action.php" method="POST">
    <label>Email<input type="email" name="email_addr" required></label>
    <label>Password<input type="password" name="user_pass" required></label>
    <button type="submit">Log In</button>
</form>

<p>Don't have an account? <a href="register.php">Register here</a></p>
</section>
<?php include __DIR__ . '/layout/footer.php'; ?>