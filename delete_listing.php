<?php
/* Removes a product listing from the database*/

require_once 'config.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

$productId = isset($_GET['id']) ? intval($_GET['id']) : 0;
$userId = $_SESSION['user_id'];

$conn->query("DELETE FROM products WHERE product_id = $productId AND seller_id = $userId");

setSuccessMessage("Listing deleted successfully.");
redirect('my_listings.php');
?>