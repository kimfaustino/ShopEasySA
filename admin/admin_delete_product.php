<?php
session_start();
require_once '../config.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// Get product ID
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($product_id > 0) {

    // Get image path before deleting the product
    $stmt = $conn->prepare("SELECT image_path FROM products WHERE product_id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {

        $product = $result->fetch_assoc();
        $image_path = "../" . $product['image_path'];

        $stmt->close();

        // Delete the product
        $stmt = $conn->prepare("DELETE FROM products WHERE product_id = ?");
        $stmt->bind_param("i", $product_id);

        if ($stmt->execute()) {

            // Delete the image file if it exists
            if (!empty($product['image_path']) && file_exists($image_path)) {
                unlink($image_path);
            }

            $_SESSION['success_message'] = "Product deleted successfully.";
        } else {
            $_SESSION['error_message'] = "Failed to delete the product.";
        }

    } else {
        $_SESSION['error_message'] = "Product not found.";
    }

    $stmt->close();

} else {
    $_SESSION['error_message'] = "Invalid product ID.";
}

$conn->close();

// Redirect back to product management
header("Location: admin_products.php");
exit();
?>