<?php
/*ShopEasySA - Submit Review Processor*/

require_once 'config.php';

if (!isLoggedIn()) {
    setErrorMessage("Please login to leave a review.");
    redirect('login.php');
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $productId = intval($_POST['product_id']);
    $userId = $_SESSION['user_id'];
    $rating = intval($_POST['rating']);
    $comment = cleanInput($_POST['comment']);
    
    // Validate rating
    if ($rating < 1 || $rating > 5) {
        setErrorMessage("Please select a rating between 1 and 5 stars.");
        redirect('product.php?id=' . $productId);
    }
    
    // Check if user already reviewed this product
    $check = $conn->query("SELECT review_id FROM reviews WHERE product_id = $productId AND user_id = $userId");
    if ($check->num_rows > 0) {
        setErrorMessage("You have already reviewed this product.");
        redirect('product.php?id=' . $productId);
    }
    
    // Insert review (pending approval)
    $sql = "INSERT INTO reviews (product_id, user_id, rating, comment, is_approved) 
            VALUES ($productId, $userId, $rating, '$comment', 0)";
    
    if ($conn->query($sql)) {
        setSuccessMessage("Thank you for your review! It will appear after admin approval.");
    } else {
        setErrorMessage("Error submitting review: " . $conn->error);
    }
    
    redirect('product.php?id=' . $productId);
}
?>