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
<option value="">-- Select Country --</option>
<option value="Algeria">Algeria</option>
<option value="Angola">Angola</option>
<option value="Benin">Benin</option>
<option value="Botswana">Botswana</option>
<option value="Burkina Faso">Burkina Faso</option>
<option value="Burundi">Burundi</option>
<option value="Cabo Verde">Cabo Verde</option>
<option value="Cameroon">Cameroon</option>
<option value="Central African Republic">Central African Republic</option>
<option value="Chad">Chad</option>
<option value="Comoros">Comoros</option>
<option value="Congo (Brazzaville)">Congo (Brazzaville)</option>
<option value="Congo (DRC)">Congo (DRC)</option>
<option value="Djibouti">Djibouti</option>
<option value="Egypt">Egypt</option>
<option value="Equatorial Guinea">Equatorial Guinea</option>
<option value="Eritrea">Eritrea</option>
<option value="Eswatini">Eswatini</option>
<option value="Ethiopia">Ethiopia</option>
<option value="Gabon">Gabon</option>
<option value="Gambia">Gambia</option>
<option value="Ghana" selected>Ghana</option>
<option value="Guinea">Guinea</option>
<option value="Guinea-Bissau">Guinea-Bissau</option>
<option value="Ivory Coast">Ivory Coast</option>
<option value="Kenya">Kenya</option>
<option value="Lesotho">Lesotho</option>
<option value="Liberia">Liberia</option>
<option value="Libya">Libya</option>
<option value="Madagascar">Madagascar</option>
<option value="Malawi">Malawi</option>
<option value="Mali">Mali</option>
<option value="Mauritania">Mauritania</option>
<option value="Mauritius">Mauritius</option>
<option value="Morocco">Morocco</option>
<option value="Mozambique">Mozambique</option>
<option value="Namibia">Namibia</option>
<option value="Niger">Niger</option>
<option value="Nigeria">Nigeria</option>
<option value="Rwanda">Rwanda</option>
<option value="Sao Tome and Principe">Sao Tome and Principe</option>
<option value="Senegal">Senegal</option>
<option value="Seychelles">Seychelles</option>
<option value="Sierra Leone">Sierra Leone</option>
<option value="Somalia">Somalia</option>
<option value="South Africa">South Africa</option>
<option value="South Sudan">South Sudan</option>
<option value="Sudan">Sudan</option>
<option value="Tanzania">Tanzania</option>
<option value="Togo">Togo</option>
<option value="Tunisia">Tunisia</option>
<option value="Uganda">Uganda</option>
<option value="Zambia">Zambia</option>
<option value="Zimbabwe">Zimbabwe</option>
</select>
</label>

    <label>City<input type="text" name="city"></label>

    <label>Contact Number<input type="text" name="contact_no" id="signup-contact"></label>

    <button type="submit" id="signup-btn">Create Account</button>
</form>

<p>Already registered? <a href="login.php">Log in here</a></p>
</section>
<?php include __DIR__ . '/layout/footer.php'; ?>