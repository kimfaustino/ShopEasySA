<?php
/*Collects shipping information and processes order*/

require_once 'config.php';
$page_title = 'Checkout';

if (!isLoggedIn()) {
    setErrorMessage("Please login to checkout.");
    redirect('login.php');
}

$userId = $_SESSION['user_id'];

// Get user's cart
$cartResult = $conn->query("SELECT cart_id FROM shopping_cart WHERE user_id = $userId");
if ($cartResult->num_rows == 0) {
    redirect('cart.php');
}
$cart = $cartResult->fetch_assoc();
$cartId = $cart['cart_id'];

// Get cart items
$cartItems = $conn->query("
    SELECT ci.*, p.title, p.price, p.seller_id, u.full_name as seller_name
    FROM cart_items ci
    JOIN products p ON ci.product_id = p.product_id
    JOIN users u ON p.seller_id = u.user_id
    WHERE ci.cart_id = $cartId
");

if ($cartItems->num_rows == 0) {
    redirect('cart.php');
}

// Calculate totals
$subtotal = 0;
while($item = $cartItems->fetch_assoc()) {
    $subtotal += $item['price_at_add'] * $item['quantity'];
}

$escrowFee = $subtotal * (ESCROW_FEE / 100);
$grandTotal = $subtotal + $escrowFee;

// Get user info for pre-filling
$user = $conn->query("SELECT * FROM users WHERE user_id = $userId")->fetch_assoc();

include 'header.php';
?>

<div class="container my-4">
    <h1 class="h2 mb-4">Checkout</h1>
    
    <div class="row g-4">
        
        <div class="col-12 col-lg-7">
            <form method="POST" action="checkout_process.php" id="checkoutForm">
                
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Shipping Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Full Name</label>
                                <input type="text" name="full_name" class="form-control" 
                                       value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone Number</label>
                                <input type="tel" name="phone" class="form-control" 
                                       value="<?php echo htmlspecialchars($user['phone']); ?>" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Street Address</label>
                            <textarea name="address" class="form-control" rows="2" required 
                                      placeholder="House number, street name, landmark"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Township / City</label>
                                <input type="text" name="location" class="form-control" 
                                       value="<?php echo htmlspecialchars($user['location']); ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Postal Code</label>
                                <input type="text" name="postal_code" class="form-control" placeholder="e.g., 2000">
                            </div>
                        </div>
                    </div>
                </div>
                
                <input type="hidden" name="cart_id" value="<?php echo $cartId; ?>">
                <input type="hidden" name="total" value="<?php echo $grandTotal; ?>">
                
                <button type="submit" class="btn btn-primary-custom btn-lg w-100 py-3 fw-bold">
                    Place Order (R <?php echo number_format($grandTotal, 2); ?>)
                </button>
                
                <p class="text-center text-muted small mt-3">
                    By placing your order, you agree to ShopEasySA's Terms of Service
                </p>
            </form>
        </div>
        
        <div class="col-12 col-lg-5">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Order Summary</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span>R <?php echo number_format($subtotal, 2); ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Escrow Fee (<?php echo ESCROW_FEE; ?>%):</span>
                        <span>R <?php echo number_format($escrowFee, 2); ?></span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="fw-bold">Total:</span>
                        <span class="fw-bold text-primary-custom fs-4">R <?php echo number_format($grandTotal, 2); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('checkoutForm').addEventListener('submit', function(e) {
    var address = document.querySelector('textarea[name="address"]').value;
    if(address.trim().length < 10) {
        e.preventDefault();
        alert('Please provide a complete shipping address');
    }
});
</script>

<?php include 'footer.php'; ?>