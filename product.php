<?php
/*Displays detailed information about a specific product*/

require_once 'config.php';
$page_title = 'Product Details';

$productId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Get product details
$result = $conn->query("
    SELECT p.*, u.full_name as seller_name, u.email as seller_email, u.phone as seller_phone, u.location,
           c.category_name
    FROM products p
    JOIN users u ON p.seller_id = u.user_id
    JOIN categories c ON p.category_id = c.category_id
    WHERE p.product_id = $productId
");

$product = $result->fetch_assoc();

if(!$product) {
    setErrorMessage("Product not found.");
    redirect('marketplace.php');
}


$conn->query("UPDATE products SET views = views + 1 WHERE product_id = $productId");

// Get related products (same category)
$relatedProducts = $conn->query("
    SELECT p.*, u.location
    FROM products p
    JOIN users u ON p.seller_id = u.user_id
    WHERE p.category_id = {$product['category_id']} 
    AND p.product_id != $productId 
    AND p.status = 'active'
    LIMIT 4
");

include 'header.php';
?>

<div class="container my-4">
    <div class="row g-4">
        
        <!-- Product Image -->
        <div class="col-12 col-md-6">
            <img src="<?php echo $product['image_path']; ?>" 
                 class="img-fluid rounded-4 shadow-sm w-100" 
                 style="height: 400px; object-fit: cover"
                 alt="<?php echo htmlspecialchars($product['title']); ?>">
        </div>
        
        <!-- Product Information -->
        <div class="col-12 col-md-6">
            
            <span class="badge bg-secondary mb-2"><?php echo $product['category_name']; ?></span>
            <h1 class="display-6 fw-bold mb-3"><?php echo htmlspecialchars($product['title']); ?></h1>
            
            <div class="bg-light p-3 rounded-3 mb-3">
                <div class="d-flex align-items-baseline">
                    <span class="text-muted me-2">Price:</span>
                    <span class="display-5 text-primary-custom fw-bold">R <?php echo number_format($product['price'], 2); ?></span>
                </div>
            </div>
            
            <div class="mb-4">
                <h5>Description</h5>
                <p class="text-muted"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
            </div>
            
            <div class="row g-2 mb-4">
                <div class="col-6">
                    <div class="border rounded-3 p-2 text-center">
                        <small class="text-muted">Condition</small>
                        <div class="fw-bold">Good</div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="border rounded-3 p-2 text-center">
                        <small class="text-muted">Views</small>
                        <div class="fw-bold"><?php echo number_format($product['views']); ?></div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="border rounded-3 p-2 text-center">
                        <small class="text-muted">Listed On</small>
                        <div class="fw-bold"><?php echo date('M d, Y', strtotime($product['created_at'])); ?></div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="border rounded-3 p-2 text-center">
                        <small class="text-muted">Location</small>
                        <div class="fw-bold"><?php echo $product['location']; ?></div>
                    </div>
                </div>
            </div>
            
            <!-- Seller Information -->
            <div class="alert alert-light border">
                <h6>Seller Information</h6>
                <p class="mb-1"><strong><?php echo $product['seller_name']; ?></strong></p>
                <p class="mb-1 small">Location: <?php echo $product['location']; ?></p>
                <p class="mb-1 small">Phone: <?php echo $product['seller_phone']; ?></p>
            </div>
            
            <!-- Action Buttons -->
            <?php if(isLoggedIn() && $_SESSION['user_id'] == $product['seller_id']): ?>
                <div class="alert alert-info">This is your listing. <a href="edit_listing.php?id=<?php echo $productId; ?>" class="alert-link">Edit it here</a></div>
                <div class="d-grid gap-2">
                    <a href="edit_listing.php?id=<?php echo $productId; ?>" class="btn btn-outline-primary">Edit Listing</a>
                    <a href="delete_listing.php?id=<?php echo $productId; ?>" class="btn btn-outline-danger" onclick="return confirm('Delete this listing permanently?')">Delete Listing</a>
                </div>
                
            <?php elseif($product['status'] == 'active'): ?>
                <div class="d-grid gap-2">
                    <a href="cart.php?add=<?php echo $productId; ?>" class="btn btn-primary-custom btn-lg">Add to Cart</a>
                    <a href="checkout.php?buy_now=<?php echo $productId; ?>" class="btn btn-success">Buy Now</a>
                </div>
                <div class="alert alert-success small mt-3">
                    Your payment is protected by ShopEasySA Escrow. Buy with confidence.
                </div>
                
            <?php elseif($product['status'] == 'sold'): ?>
                <div class="alert alert-secondary text-center">This item has been sold.</div>
            <?php else: ?>
                <div class="alert alert-secondary text-center">This item is pending approval.</div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Related Products -->
    <?php if($relatedProducts->num_rows > 0): ?>
    <div class="row mt-5">
        <div class="col-12">
            <h4 class="mb-3">You May Also Like</h4>
            <div class="row g-4">
                <?php while($related = $relatedProducts->fetch_assoc()): ?>
                    <div class="col-6 col-md-3">
                        <div class="card h-100 shadow-sm">
                            <img src="<?php echo $related['image_path']; ?>" 
                                 class="card-img-top" 
                                 style="height: 150px; object-fit: cover"
                                 alt="<?php echo htmlspecialchars($related['title']); ?>">
                            <div class="card-body">
                                <h6 class="card-title"><?php echo htmlspecialchars(substr($related['title'], 0, 30)); ?></h6>
                                <p class="text-primary-custom fw-bold">R <?php echo number_format($related['price'], 2); ?></p>
                                <small class="text-muted"><?php echo $related['location']; ?></small>
                            </div>
                            <div class="card-footer bg-white">
                                <a href="product.php?id=<?php echo $related['product_id']; ?>" class="btn btn-sm btn-primary-custom w-100">View</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- REVIEWS SECTION - Add to product.php -->
<div class="row mt-5">
    <div class="col-12">
        <h4>Customer Reviews</h4>
        
        <?php
        // Get reviews for this product
        $reviewQuery = $conn->query("
            SELECT r.*, u.full_name 
            FROM reviews r
            JOIN users u ON r.user_id = u.user_id
            WHERE r.product_id = $productId AND r.is_approved = 1
            ORDER BY r.created_at DESC
        ");
        ?>
        
        <!-- Display existing reviews -->
        <?php if($reviewQuery->num_rows > 0): ?>
            <?php while($review = $reviewQuery->fetch_assoc()): ?>
                <div class="border-bottom pb-3 mb-3">
                    <div class="d-flex justify-content-between">
                        <strong><?php echo htmlspecialchars($review['full_name']); ?></strong>
                        <small class="text-muted"><?php echo date('M d, Y', strtotime($review['created_at'])); ?></small>
                    </div>
                    <div class="mb-2">
                        <?php for($i = 1; $i <= 5; $i++): ?>
                            <?php if($i <= $review['rating']): ?>
                                <i class="fas fa-star text-warning"></i>
                            <?php else: ?>
                                <i class="far fa-star text-muted"></i>
                            <?php endif; ?>
                        <?php endfor; ?>
                    </div>
                    <p class="mb-0"><?php echo nl2br(htmlspecialchars($review['comment'])); ?></p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-muted">No reviews yet. Be the first to review this product!</p>
        <?php endif; ?>
        
        <!-- Review Form (only for logged in users who are not the seller) -->
        <?php if(isLoggedIn() && $_SESSION['user_id'] != $product['seller_id']): ?>
            <div class="card mt-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Write a Review</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="submit_review.php">
                        <input type="hidden" name="product_id" value="<?php echo $productId; ?>">
                        
                        <div class="mb-3">
                            <label class="form-label">Your Rating</label>
                            <div class="rating-input">
                                <i class="far fa-star fa-lg" data-rating="1"></i>
                                <i class="far fa-star fa-lg" data-rating="2"></i>
                                <i class="far fa-star fa-lg" data-rating="3"></i>
                                <i class="far fa-star fa-lg" data-rating="4"></i>
                                <i class="far fa-star fa-lg" data-rating="5"></i>
                                <input type="hidden" name="rating" id="rating_value" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Your Review</label>
                            <textarea name="comment" class="form-control" rows="4" required></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary-custom">Submit Review</button>
                    </form>
                </div>
            </div>
            
            <script>
            // Rating star functionality
            document.querySelectorAll('.rating-input i').forEach(function(star) {
                star.addEventListener('click', function() {
                    var rating = this.dataset.rating;
                    document.getElementById('rating_value').value = rating;
                    
                    // Highlight stars
                    document.querySelectorAll('.rating-input i').forEach(function(s, index) {
                        if(index < rating) {
                            s.className = 'fas fa-star fa-lg text-warning';
                        } else {
                            s.className = 'far fa-star fa-lg text-muted';
                        }
                    });
                });
            });
            </script>
        <?php endif; ?>
    </div>
</div>


<?php include 'footer.php'; ?>