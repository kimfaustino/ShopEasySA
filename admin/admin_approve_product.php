<?php
// admin/approve_product.php
session_start();
require_once '../config.php';

// Check if user is logged in and is admin
if(!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// Get product ID from URL
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($product_id > 0) {
    // Update product status to 'active'
    $stmt = $conn->prepare("UPDATE products SET status = 'active' WHERE product_id = ?");
    $stmt->bind_param("i", $product_id);
    
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Product approved successfully! It is now live on the marketplace.";
    } else {
        $_SESSION['error_message'] = "Error approving product: " . $conn->error;
    }
    $stmt->close();
} else {
    $_SESSION['error_message'] = "Invalid product ID.";
}

// Redirect back to where they came from
$redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'admin_products.php';
header("Location: $redirect");
exit();
?>