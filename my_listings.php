<?php
/*Displays all products the user has listed for sale*/

require_once 'config.php';
$page_title = 'My Listings';

if (!isLoggedIn()) {
    redirect('login.php');
}

$userId = $_SESSION['user_id'];

$products = $conn->query("
    SELECT p.*, c.category_name
    FROM products p 
    JOIN categories c ON p.category_id = c.category_id 
    WHERE p.seller_id = $userId 
    ORDER BY p.created_at DESC
");

include 'header.php';
?>

<div class="container my-4">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2 mb-0">My Listings</h1>
        <a href="add_listing.php" class="btn btn-primary-custom">New Listing</a>
    </div>
    
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5>Total Listings</h5>
                    <h2><?php echo number_format($products->num_rows); ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5>Active Listings</h5>
                    <h2><?php 
                        $active = $conn->query("SELECT COUNT(*) as count FROM products WHERE seller_id = $userId AND status = 'active'")->fetch_assoc();
                        echo number_format($active['count']);
                    ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    <h5>Pending Approval</h5>
                    <h2><?php 
                        $pending = $conn->query("SELECT COUNT(*) as count FROM products WHERE seller_id = $userId AND status = 'pending'")->fetch_assoc();
                        echo number_format($pending['count']);
                    ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5>Total Views</h5>
                    <h2><?php 
                        $views = $conn->query("SELECT SUM(views) as total FROM products WHERE seller_id = $userId")->fetch_assoc();
                        echo number_format($views['total'] ?? 0);
                    ?></h2>
                </div>
            </div>
        </div>
    </div>
    
    <?php if($products->num_rows == 0): ?>
        <div class="text-center py-5">
            <i class="fas fa-box-open fa-5x text-muted mb-4"></i>
            <h3>No listings yet</h3>
            <p class="text-muted">Start selling on ShopEasySA today</p>
            <a href="add_listing.php" class="btn btn-primary-custom btn-lg">Sell Something</a>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php while($product = $products->fetch_assoc()): ?>
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card shadow-sm h-100">
                        <img src="<?php echo $product['image_path']; ?>" 
                             class="card-img-top" 
                             style="height: 200px; object-fit: cover" 
                             alt="<?php echo htmlspecialchars($product['title']); ?>">
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="badge bg-secondary"><?php echo $product['category_name']; ?></span>
                                <?php
                                $statusClass = [
                                    'pending' => 'warning',
                                    'active' => 'success',
                                    'sold' => 'secondary'
                                ];
                                ?>
                                <span class="badge bg-<?php echo $statusClass[$product['status']] ?? 'secondary'; ?>">
                                    <?php echo ucfirst($product['status']); ?>
                                </span>
                            </div>
                            <h5 class="card-title"><?php echo htmlspecialchars($product['title']); ?></h5>
                            <p class="text-primary-custom fw-bold fs-4">R <?php echo number_format($product['price'], 2); ?></p>
                            <small class="text-muted">Views: <?php echo number_format($product['views']); ?></small>
                        </div>
                        <div class="card-footer bg-white">
                            <div class="row g-2">
                                <div class="col-6">
                                    <a href="product.php?id=<?php echo $product['product_id']; ?>" class="btn btn-sm btn-outline-primary w-100">View</a>
                                </div>
                                <div class="col-6">
                                    <a href="edit_listing.php?id=<?php echo $product['product_id']; ?>" class="btn btn-sm btn-outline-secondary w-100">Edit</a>
                                </div>
                                <div class="col-12">
                                    <a href="delete_listing.php?id=<?php echo $product['product_id']; ?>" class="btn btn-sm btn-outline-danger w-100" onclick="return confirm('Delete this listing?')">Delete</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>