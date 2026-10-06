<?php
session_start();
date_default_timezone_set('Africa/Accra');
require_once __DIR__ . '/db_class.php';

if (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false) {
  define('BASE_PATH', '/shoppn');
} else {
  define('BASE_PATH', '/~akua.amponsah/ecommerce-class/shoppn');
}

function redirect($url) {
  header("Location: $url");
  exit;
}

function get_ip() {
  return $_SERVER['REMOTE_ADDR'];
}

function is_logged_in() {
  return isset($_SESSION['customer_id']);
}

function is_admin() {
  return is_logged_in() && $_SESSION['user_role'] == 1;
}

function require_login() {
  if (!is_logged_in()) redirect(BASE_PATH . '/views/login.php');
}

function require_admin() {
  if (!is_admin()) redirect(BASE_PATH . '/index.php');
}