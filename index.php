<?php
/*Landing page showing featured products and information about the platform*/

require_once 'config.php';
$page_title = 'Home';

// Get featured products for display
$featuredProducts = $conn->query("
    SELECT p.*, u.location, c.category_name
    FROM products p
    JOIN users u ON p.seller_id = u.user_id
    JOIN categories c ON p.category_id = c.category_id
    WHERE p.status = 'active'
    ORDER BY p.created_at DESC
    LIMIT 8
");

// Get statistics for the homepage
$totalProducts = $conn->query("SELECT COUNT(*) as count FROM products WHERE status = 'active'")->fetch_assoc();
$totalSellers = $conn->query("SELECT COUNT(*) as count FROM users WHERE user_type = 'seller' AND is_active = 1")->fetch_assoc();
$totalSales = $conn->query("SELECT COUNT(*) as count FROM orders WHERE status = 'completed'")->fetch_assoc();

include 'header.php';
?>


<section class="search-section-home">
    <div class="container">
        <div class="search-wrapper-home">
            <form action="<?php echo SITE_URL; ?>marketplace.php" method="GET" class="search-form-home">
                <div class="search-flex">
                    <select name="category" class="category-select-home">
                        <option value="">All Categories</option>
                        <?php
                        $categories = $conn->query("SELECT * FROM categories WHERE is_active = 1 ORDER BY display_order");
                        while($cat = $categories->fetch_assoc()):
                        ?>
                        <option value="<?php echo $cat['category_id']; ?>"><?php echo $cat['category_name']; ?></option>
                        <?php endwhile; ?>
                    </select>
                    <input type="text" name="search" class="search-input-home" 
                           placeholder="What are you looking for today?">
                    <button type="submit" class="search-btn-home">
                        <i class="fas fa-search"></i> Search
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Hero Banner Section -->
<section class="hero-section text-white text-center">
    <div class="container py-5">
        <h1 class="display-4 fw-bold mb-3">Welcome to ShopEasySA</h1>
        <p class="lead mb-4">
            South Africa's Township C2C Marketplace - Buy and Sell with Confidence
        </p>
        <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
            <a href="register.php" class="btn-sell-gradient btn-lg px-5 fw-bold">
                <i class="fas fa-rocket"></i> Start Selling Today
            </a>
            <a href="marketplace.php" class="btn-browse-gradient btn-lg px-5">
                <i class="fas fa-search"></i> Browse Items
            </a>
        </div>
    </div>
</section>

<!-- Features Section -->
<div class="container my-5">
    <div class="text-center mb-5">
        <h2 class="display-6 fw-bold">Why Choose ShopEasySA?</h2>
        <p class="text-muted">The safe and easy way to trade in your community</p>
    </div>
    
    <div class="row g-4">
        <!-- Feature 1: Safe Escrow -->
        <div class="col-md-4">
            <div class="card h-100 text-center p-4 shadow-sm">
                <div class="icon-circle mx-auto mb-3">
                    <i class="fas fa-shield-alt fa-2x text-white"></i>
                </div>
                <h4>Safe Escrow Protection</h4>
                <p class="text-muted">Your money is held safely until you receive your item.</p>
            </div>
        </div>
        
        <!-- Feature 2: No Listing Fees -->
        <div class="col-md-4">
            <div class="card h-100 text-center p-4 shadow-sm">
                <div class="icon-circle mx-auto mb-3">
                    <i class="fas fa-hand-holding-usd fa-2x text-white"></i>
                </div>
                <h4>No Listing Fees</h4>
                <p class="text-muted">Post items for free. Pay only a small fee when you sell.</p>
            </div>
        </div>
        
        <!-- Feature 3: Local Community -->
        <div class="col-md-4">
            <div class="card h-100 text-center p-4 shadow-sm">
                <div class="icon-circle mx-auto mb-3">
                    <i class="fas fa-users fa-2x text-white"></i>
                </div>
                <h4>Local Community</h4>
                <p class="text-muted">Connect with buyers and sellers in your township.</p>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Section -->
<section class="bg-light py-5">
    <div class="container">
        <div class="row text-center g-4">
            <div class="col-md-4">
                <div class="bg-white rounded-3 p-3 shadow-sm">
                    <h2 class="display-5 fw-bold text-primary-custom"><?php echo number_format($totalProducts['count']); ?>+</h2>
                    <p class="text-muted mb-0">Items for Sale</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bg-white rounded-3 p-3 shadow-sm">
                    <h2 class="display-5 fw-bold text-primary-custom"><?php echo number_format($totalSellers['count']); ?>+</h2>
                    <p class="text-muted mb-0">Active Sellers</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bg-white rounded-3 p-3 shadow-sm">
                    <h2 class="display-5 fw-bold text-primary-custom"><?php echo number_format($totalSales['count']); ?>+</h2>
                    <p class="text-muted mb-0">Successful Sales</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<!-- Categories Section -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-6 fw-bold">Shop by Category</h2>
            <p class="text-muted">Find exactly what you are looking for</p>
        </div>
        
        <div class="row g-3">
            <?php
            $allCategories = $conn->query("SELECT * FROM categories WHERE is_active = 1 ORDER BY display_order");
            $modernIcons = [
                'fa-tshirt',           // Clothing
                'fa-mobile-alt',       // Electronics
                'fa-couch',            // Home & Living
                'fa-paintbrush',       // Handmade Crafts
                'fa-book-open',        // Books & Media
                'fa-futbol',           // Sports & Outdoors
                'fa-car',              // Vehicles
                'fa-gem',              // Jewelry
                'fa-laptop',           // Computer
                'fa-baby-carriage',    // Baby 
                'fa-paw',              // Pets 
                'fa-tools',            // Tools
                'fa-camera',           // Photography
                'fa-headphones',       // Audio
                'fa-rings-wedding',    // Wedding 
                'fa-apple-alt'         // Food
            ];
            $iconIndex = 0;
            while($cat = $allCategories->fetch_assoc()):
                $icon = !empty($cat['category_icon']) ? $cat['category_icon'] : $modernIcons[$iconIndex % count($modernIcons)];
            ?>
            <div class="col-6 col-md-4 col-lg-2">
                <a href="marketplace.php?category=<?php echo $cat['category_id']; ?>" class="text-decoration-none">
                    <div class="category-card text-center p-3 shadow-sm">
                        <i class="fas <?php echo $icon; ?> fa-2x mb-2"></i>
                        <p class="mb-0 mt-2 fw-bold small"><?php echo $cat['category_name']; ?></p>
                    </div>
                </a>
            </div>
            <?php 
            $iconIndex++;
            endwhile; 
            ?>
        </div>
    </div>
</section>

<!-- Featured Products Section -->
<?php if($featuredProducts->num_rows > 0): ?>
<section class="bg-light py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-6 fw-bold">Featured Items</h2>
            <p class="text-muted">Popular items from our community</p>
        </div>
        
        <div class="row g-4">
            <?php while($product = $featuredProducts->fetch_assoc()): ?>
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card h-100 shadow-sm product-card">
                        <img src="<?php echo $product['image_path']; ?>" 
                             class="card-img-top" 
                             style="height: 180px; object-fit: cover" 
                             alt="<?php echo htmlspecialchars($product['title']); ?>">
                        <div class="card-body">
                            <span class="badge bg-secondary mb-2"><?php echo $product['category_name']; ?></span>
                            <h6 class="card-title"><?php echo htmlspecialchars($product['title']); ?></h6>
                            <p class="fw-bold text-primary-custom">R <?php echo number_format($product['price'], 2); ?></p>
                            <small class="text-muted"><i class="fas fa-map-marker-alt"></i> <?php echo $product['location']; ?></small>
                        </div>
                        <div class="card-footer bg-white">
                            <a href="product.php?id=<?php echo $product['product_id']; ?>" class="btn btn-primary-custom w-100">View Details</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
        
        <div class="text-center mt-4">
            <a href="marketplace.php" class="btn btn-outline-primary-custom">Browse All Items</a>
        </div>
    </div>
</section>
<?php endif; ?>


<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-6 fw-bold">How ShopEasySA Works</h2>
            <p class="text-muted">Get started in four simple steps</p>
        </div>
        
        <div class="row g-4">
            <div class="col-md-3 text-center">
                <div class="step-circle mx-auto mb-3">
                    <span class="fs-3 fw-bold text-white">1</span>
                </div>
                <h5>Create Account</h5>
                <p class="small text-muted">Sign up for free in minutes</p>
            </div>
            <div class="col-md-3 text-center">
                <div class="step-circle mx-auto mb-3">
                    <span class="fs-3 fw-bold text-white">2</span>
                </div>
                <h5>List Your Item</h5>
                <p class="small text-muted">Add photos and set your price</p>
            </div>
            <div class="col-md-3 text-center">
                <div class="step-circle mx-auto mb-3">
                    <span class="fs-3 fw-bold text-white">3</span>
                </div>
                <h5>Connect with Buyers</h5>
                <p class="small text-muted">Buyers find and purchase your items</p>
            </div>
            <div class="col-md-3 text-center">
                <div class="step-circle mx-auto mb-3">
                    <span class="fs-3 fw-bold text-white">4</span>
                </div>
                <h5>Get Paid Safely</h5>
                <p class="small text-muted">Escrow protects both parties</p>
            </div>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>