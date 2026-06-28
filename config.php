<?php

$db_host = 'sql305.infinityfree.com'; 
$db_port = 3306;
$db_user = 'if0_42182747';
$db_password = 'Shopeasysa';          
$db_name = 'if0_42182747_shopeasysa_db';

// CREATE DATABASE CONNECTION

$conn = new mysqli($db_host, $db_user, $db_password, $db_name, $db_port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set character encoding
$conn->set_charset("utf8mb4");


// START SESSION

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// WEBSITE SETTINGS
define('SITE_NAME', 'ShopEasySA');
define('SITE_URL', 'http://ShopEasySA.infinityfree.io/');
define('SITE_EMAIL', 'info@shopeasysa.co.za');
define('ESCROW_FEE', 5);
define('MAX_IMAGE_SIZE', 5242880);
define('ITEMS_PER_PAGE', 12);
define('MIN_PASSWORD_LENGTH', 6);

// HELPER FUNCTIONS
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] == 'admin';
}

function redirect($page) {
    header("Location: " . SITE_URL . $page);
    exit();
}

function cleanInput($data) {
    global $conn;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $conn->real_escape_string($data);
}

function setSuccessMessage($message) {
    $_SESSION['success_message'] = $message;
}

function setErrorMessage($message) {
    $_SESSION['error_message'] = $message;
}

function getCartCount() {
    if (!isset($_SESSION['user_id'])) return 0;
    global $conn;
    $userId = $_SESSION['user_id'];
    $result = $conn->query("SELECT cart_id FROM shopping_cart WHERE user_id = $userId");
    if ($result->num_rows == 0) return 0;
    $cart = $result->fetch_assoc();
    $cartId = $cart['cart_id'];
    $items = $conn->query("SELECT SUM(quantity) as total FROM cart_items WHERE cart_id = $cartId");
    $count = $items->fetch_assoc();
    return $count['total'] ?? 0;
}

function formatMoney($amount) {
    return 'R ' . number_format($amount, 2);
}
?>