<?php
require_once __DIR__ . '/../core/core.php';
include __DIR__ . '/layout/header.php';
?>
<section class="auth-box">
<h2>Create an Account</h2>

<?php if (!empty($_SESSION['error'])): ?>
    <div class="alert-error"><?= htmlspecialchars($_SESSION['error']) ?></div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<form id="signup-form" action="../actions/register_action.php" method="POST">
    <label>Full Name<input type="text" name="full_name" required></label>
    <label>Email<input type="email" name="email_addr" required></label>
    <label>Password<input type="password" name="user_pass" id="signup-pass" required></label>

    <label>Country
    <select name="country">
        <option value="Ghana">Ghana</option>
        <option value="Other">Other</option>
    </select>
    </label>

    <label>City<input type="text" name="city"></label>
    <label>Contact Number<input type="text" name="contact_no" id="signup-contact"></label>

    <button type="submit" id="signup-btn">Create Account</button>
</form>

<p>Already registered? <a href="login.php">Log in here</a></p>
</section>
<?php include __DIR__ . '/layout/footer.php'; ?>