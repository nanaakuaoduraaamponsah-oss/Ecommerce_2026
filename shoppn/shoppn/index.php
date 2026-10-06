<?php
require_once __DIR__ . '/core/core.php';

if (is_logged_in()) {
redirect(BASE_PATH . '/views/home.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Shoppn</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="landing">
    <a href="#" class="logo-cursive">shoppn</a>

    <h1 class="typewriter" id="typewriter"></h1>

    <div class="landing-buttons">
    <a href="views/register.php" class="btn-primary">Sign Up</a>
    <a href="views/login.php" class="btn-primary btn-secondary">Log In</a>
    </div>
</div>

<script>
    const fullText = "let's go <span class='brand-word'>shoppn</span>";
    const el = document.getElementById('typewriter');
    const plain = "let's go shoppn"; // used only to measure character count
    let i = 0;

    function type() {
    if (i <= plain.length) {
        const visiblePlain = plain.slice(0, i);
        if (visiblePlain.toLowerCase().includes('shoppn')) {
        const idx = visiblePlain.toLowerCase().indexOf('shoppn');
        el.innerHTML = visiblePlain.slice(0, idx) +
            "<span class='brand-word'>" + visiblePlain.slice(idx) + "</span>";
        } else {
        el.innerHTML = visiblePlain;
        }
        i++;
        setTimeout(type, 90);
    }
    }
    type();
</script>
</body>
</html>