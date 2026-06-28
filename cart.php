<?php
// Displays items in user's cart and allows quantity updates

require_once 'config.php';
$page_title = 'Shopping Cart';

if (!isLoggedIn()) {
    setErrorMessage("Please login to view your cart.");
    redirect('login.php');
}

$userId = $_SESSION['user_id'];

// Get or create user's cart
$cartResult = $conn->query("SELECT cart_id FROM shopping_cart WHERE user_id = $userId");
if ($cartResult->num_rows == 0) {
    $conn->query("INSERT INTO shopping_cart (user_id) VALUES ($userId)");
    $cartId = $conn->insert_id;
} else {
    $cart = $cartResult->fetch_assoc();
    $cartId = $cart['cart_id'];
}

// Handle add to cart
if (isset($_GET['add'])) {
    $productId = intval($_GET['add']);
    $product = $conn->query("SELECT price, seller_id FROM products WHERE product_id = $productId AND status = 'active'")->fetch_assoc();
    
    if($product && $product['seller_id'] != $userId) {
        $check = $conn->query("SELECT * FROM cart_items WHERE cart_id = $cartId AND product_id = $productId");
        if($check->num_rows == 0) {
            $conn->query("INSERT INTO cart_items (cart_id, product_id, quantity, price_at_add) VALUES ($cartId, $productId, 1, {$product['price']})");
            setSuccessMessage("Item added to cart!");
        } else {
            $conn->query("UPDATE cart_items SET quantity = quantity + 1 WHERE cart_id = $cartId AND product_id = $productId");
            setSuccessMessage("Item quantity updated in cart!");
        }
    }
    redirect('cart.php');
}

// Handle remove from cart
if (isset($_GET['remove'])) {
    $itemId = intval($_GET['remove']);
    $conn->query("DELETE FROM cart_items WHERE cart_item_id = $itemId AND cart_id = $cartId");
    setSuccessMessage("Item removed from cart.");
    redirect('cart.php');
}

// Handle update quantities
if (isset($_POST['update_cart'])) {
    foreach ($_POST['quantity'] as $itemId => $quantity) {
        $quantity = max(1, intval($quantity));
        $conn->query("UPDATE cart_items SET quantity = $quantity WHERE cart_item_id = $itemId AND cart_id = $cartId");
    }
    setSuccessMessage("Cart updated successfully.");
    redirect('cart.php');
}

// Get cart items
$cartItems = $conn->query("
    SELECT ci.*, p.title, p.price, p.image_path, p.seller_id, u.full_name as seller_name
    FROM cart_items ci
    JOIN products p ON ci.product_id = p.product_id
    JOIN users u ON p.seller_id = u.user_id
    WHERE ci.cart_id = $cartId
");

// Calculate totals
$subtotal = 0;
$cartItemsArray = array();

while($item = $cartItems->fetch_assoc()) {
    $itemTotal = $item['price_at_add'] * $item['quantity'];
    $subtotal += $itemTotal;
    $item['item_total'] = $itemTotal;
    $cartItemsArray[] = $item;
}

$escrowFee = $subtotal * (ESCROW_FEE / 100);
$grandTotal = $subtotal + $escrowFee;

include 'header.php';
?>

<div class="container my-4">
    <h1 class="h2 mb-4">Shopping Cart</h1>
    
    <?php if(empty($cartItemsArray)): ?>
        <div class="text-center py-5">
            <i class="fas fa-shopping-cart fa-5x text-muted mb-4"></i>
            <h3>Your cart is empty</h3>
            <p class="text-muted">Looks like you haven't added any items yet</p>
            <a href="marketplace.php" class="btn btn-primary-custom btn-lg">Start Shopping</a>
        </div>
    <?php else: ?>
        <div class="row g-4">
            
            <div class="col-12 col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Cart Items (<?php echo count($cartItemsArray); ?>)</h5>
                    </div>
                    <div class="card-body p-0">
                        <form method="POST" id="cartForm">
                            <?php foreach($cartItemsArray as $item): ?>
                                <div class="border-bottom p-3">
                                    <div class="row align-items-center">
                                        <div class="col-3 col-md-2">
                                            <img src="<?php echo $item['image_path']; ?>" 
                                                 class="img-fluid rounded" 
                                                 style="max-height: 80px;">
                                        </div>
                                        <div class="col-9 col-md-5">
                                            <h6 class="mb-1"><?php echo htmlspecialchars($item['title']); ?></h6>
                                            <small class="text-muted">Seller: <?php echo $item['seller_name']; ?></small>
                                            <div><small class="text-primary-custom">R <?php echo number_format($item['price_at_add'], 2); ?></small></div>
                                        </div>
                                        <div class="col-5 col-md-3 mt-2 mt-md-0">
                                            <input type="number" name="quantity[<?php echo $item['cart_item_id']; ?>]" 
                                                   value="<?php echo $item['quantity']; ?>" 
                                                   class="form-control form-control-sm" 
                                                   style="width: 80px;" min="1" max="99">
                                        </div>
                                        <div class="col-4 col-md-1 text-end">
                                            <strong>R <?php echo number_format($item['item_total'], 2); ?></strong>
                                        </div>
                                        <div class="col-3 col-md-1 text-end">
                                            <a href="cart.php?remove=<?php echo $item['cart_item_id']; ?>" 
                                               class="text-danger" onclick="return confirm('Remove this item?')">Remove</a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            
                            <div class="p-3 bg-light">
                                <button type="submit" name="update_cart" class="btn btn-secondary">Update Cart</button>
                                <a href="marketplace.php" class="btn btn-link">Continue Shopping</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-12 col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Order Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <strong>R <?php echo number_format($subtotal, 2); ?></strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Escrow Fee (<?php echo ESCROW_FEE; ?>%):</span>
                            <strong>R <?php echo number_format($escrowFee, 2); ?></strong>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="fw-bold">Total:</span>
                            <span class="fw-bold text-primary-custom fs-4">R <?php echo number_format($grandTotal, 2); ?></span>
                        </div>
                        <div class="alert alert-info small">
                            Your payment is held safely in escrow until you receive your item.
                        </div>
                        <a href="checkout.php" class="btn btn-primary-custom w-100 py-2 fw-bold">Proceed to Checkout</a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>