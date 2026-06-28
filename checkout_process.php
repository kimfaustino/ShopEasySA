<?php
/**Creates orders in the database*/

require_once 'config.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    redirect('cart.php');
}

$userId = $_SESSION['user_id'];
$cartId = intval($_POST['cart_id']);
$address = cleanInput($_POST['address']);
$fullName = cleanInput($_POST['full_name']);
$phone = cleanInput($_POST['phone']);
$location = cleanInput($_POST['location']);

// Get cart items
$cartItems = $conn->query("
    SELECT ci.*, p.title, p.price, p.seller_id
    FROM cart_items ci
    JOIN products p ON ci.product_id = p.product_id
    WHERE ci.cart_id = $cartId
");

if ($cartItems->num_rows == 0) {
    redirect('cart.php');
}

$conn->begin_transaction();

try {
    while($item = $cartItems->fetch_assoc()) {
        $itemTotal = $item['price_at_add'] * $item['quantity'];
        $escrowFee = $itemTotal * (ESCROW_FEE / 100);
        $grandTotal = $itemTotal + $escrowFee;
        
        $orderNumber = 'ORD-' . date('Ymd') . '-' . rand(1000, 9999);
        
        $sql = "INSERT INTO orders (order_number, buyer_id, seller_id, product_id, quantity, price, total_amount, escrow_fee, buyer_address, buyer_phone, status) 
                VALUES ('$orderNumber', $userId, {$item['seller_id']}, {$item['product_id']}, {$item['quantity']}, {$item['price_at_add']}, $itemTotal, $escrowFee, '$address', '$phone', 'pending')";
        
        $conn->query($sql);
        
        // Update product status
        $conn->query("UPDATE products SET status = 'sold' WHERE product_id = {$item['product_id']}");
    }
    
    // Clear cart
    $conn->query("DELETE FROM cart_items WHERE cart_id = $cartId");
    
    $conn->commit();
    
    setSuccessMessage("Order placed successfully! Thank you for shopping at ShopEasySA.");
    redirect('index.php');
    
} catch (Exception $e) {
    $conn->rollback();
    setErrorMessage("Error processing your order. Please try again.");
    redirect('cart.php');
}
?>