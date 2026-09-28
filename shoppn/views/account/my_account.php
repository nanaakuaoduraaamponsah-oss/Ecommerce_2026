<?php
require_once __DIR__ . '/../../core/core.php';
require_login();
echo "Logged in as: " . htmlspecialchars($_SESSION['customer_name']) . " (role: " . $_SESSION['user_role'] . ")";
