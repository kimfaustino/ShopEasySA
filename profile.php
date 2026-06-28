<?php
/*Displays user information and statistics*/

require_once 'config.php';
$page_title = 'My Profile';

if (!isLoggedIn()) {
    redirect('login.php');
}

$userId = $_SESSION['user_id'];
$user = $conn->query("SELECT * FROM users WHERE user_id = $userId")->fetch_assoc();

// Get statistics
$listings = $conn->query("SELECT COUNT(*) as count FROM products WHERE seller_id = $userId")->fetch_assoc();
$sales = $conn->query("SELECT COUNT(*) as count FROM orders WHERE seller_id = $userId AND status = 'completed'")->fetch_assoc();
$purchases = $conn->query("SELECT COUNT(*) as count FROM orders WHERE buyer_id = $userId")->fetch_assoc();

include 'header.php';
?>

<div class="container my-4">
    <div class="row g-4">
        
        <div class="col-12 col-md-4">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <div class="profile-avatar mx-auto mb-3">
                        <i class="fas fa-user fa-3x text-white"></i>
                    </div>
                    <h3><?php echo htmlspecialchars($user['full_name']); ?></h3>
                    <p class="text-muted"><i class="fas fa-map-marker-alt"></i> <?php echo $user['location']; ?></p>
                    
                    <hr>
                    <p><i class="fas fa-envelope"></i> <?php echo $user['email']; ?></p>
                    <p><i class="fas fa-phone"></i> <?php echo $user['phone']; ?></p>
                    <p><i class="fas fa-calendar-alt"></i> Member since <?php echo date('M Y', strtotime($user['created_at'])); ?></p>
                    
                    <div class="d-grid gap-2">
                        <a href="change_password.php" class="btn btn-outline-primary">Change Password</a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-md-8">
            <div class="row g-3 mb-4">
                <div class="col-4">
                    <div class="card text-center bg-primary text-white">
                        <div class="card-body">
                            <h2 class="mb-0"><?php echo $listings['count']; ?></h2>
                            <small>Listings</small>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="card text-center bg-success text-white">
                        <div class="card-body">
                            <h2 class="mb-0"><?php echo $sales['count']; ?></h2>
                            <small>Items Sold</small>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="card text-center bg-info text-white">
                        <div class="card-body">
                            <h2 class="mb-0"><?php echo $purchases['count']; ?></h2>
                            <small>Items Bought</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card shadow-sm">
                <div class="card-header bg-white fw-bold">Account Information</div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Account Type:</span>
                        <span class="badge bg-primary"><?php echo ucfirst($user['user_type']); ?></span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <span>Account Status:</span>
                        <?php if($user['is_active']): ?>
                            <span class="badge bg-success">Active</span>
                        <?php else: ?>
                            <span class="badge bg-danger">Disabled</span>
                        <?php endif; ?>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <span>Verification Status:</span>
                        <?php if($user['is_verified']): ?>
                            <span class="badge bg-success">Verified</span>
                        <?php else: ?>
                            <span class="badge bg-warning">Pending</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>