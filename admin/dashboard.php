<?php
session_start();

// Check if user is logged in and is admin
if(!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

require_once '../config.php';

$page_title = 'Admin Dashboard';

// Get statistics
$stats = $conn->query("
    SELECT 
        (SELECT COUNT(*) FROM users WHERE user_type != 'admin') as total_users,
        (SELECT COUNT(*) FROM products) as total_products,
        (SELECT COUNT(*) FROM products WHERE status = 'pending') as pending_products,
        (SELECT COUNT(*) FROM orders) as total_orders,
        (SELECT COALESCE(SUM(total_amount), 0) FROM orders WHERE status = 'completed') as total_revenue
")->fetch_assoc();

// Get recent orders
$recentOrders = $conn->query("
    SELECT o.*, u.full_name as buyer_name 
    FROM orders o 
    JOIN users u ON o.buyer_id = u.user_id 
    ORDER BY o.created_at DESC 
    LIMIT 5
");

// Get pending products
$pendingProducts = $conn->query("
    SELECT p.*, u.full_name as seller_name 
    FROM products p 
    JOIN users u ON p.seller_id = u.user_id 
    WHERE p.status = 'pending' 
    LIMIT 5
");

include 'admin_header.php';
?>

<div class="container-fluid">
    <div class="row">
        <?php include 'admin_sidebar.php'; ?>
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Dashboard</h1>
                <small>Welcome back, <?php echo $_SESSION['user_name']; ?>!</small>
            </div>
            
            <!-- Statistics Cards -->
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="card text-white" style="background: linear-gradient(135deg, #e91e63, #ff9800);">
                        <div class="card-body">
                            <h6 class="card-title">Total Users</h6>
                            <h2 class="mb-0"><?php echo number_format($stats['total_users']); ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white" style="background: linear-gradient(135deg, #2196f3, #4caf50);">
                        <div class="card-body">
                            <h6 class="card-title">Total Products</h6>
                            <h2 class="mb-0"><?php echo number_format($stats['total_products']); ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-dark" style="background: linear-gradient(135deg, #ffc107, #ff9800);">
                        <div class="card-body">
                            <h6 class="card-title">Pending Approval</h6>
                            <h2 class="mb-0"><?php echo number_format($stats['pending_products']); ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white" style="background: linear-gradient(135deg, #4caf50, #2196f3);">
                        <div class="card-body">
                            <h6 class="card-title">Total Revenue</h6>
                            <h2 class="mb-0">R <?php echo number_format($stats['total_revenue'], 0); ?></h2>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <!-- Recent Orders -->
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">Recent Orders</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr><th>Order #</th><th>Buyer</th><th>Total</th><th>Status</th></tr>
                                    </thead>
                                    <tbody>
                                        <?php if($recentOrders && $recentOrders->num_rows > 0): ?>
                                            <?php while($order = $recentOrders->fetch_assoc()): ?>
                                            <tr>
                                                <td><?php echo $order['order_number']; ?></td>
                                                <td><?php echo $order['buyer_name']; ?></td>
                                                <td>R <?php echo number_format($order['total_amount'], 2); ?></td>
                                                <td><span class="badge bg-<?php echo $order['status'] == 'pending' ? 'warning' : 'success'; ?>"><?php echo ucfirst($order['status']); ?></span></td>
                                            </tr>
                                            <?php endwhile; ?>
                                        <?php else: ?>
                                            <tr><td colspan="4" class="text-center">No orders yet</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Pending Products -->
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white d-flex justify-content-between">
                            <h5 class="mb-0">Pending Product Approvals</h5>
                            <a href="admin_products.php?status=pending" class="btn btn-sm btn-primary">View All</a>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr><th>Product</th><th>Seller</th><th>Price</th><th>Action</th></tr>
                                    </thead>
                                    <tbody>
                                        <?php if($pendingProducts && $pendingProducts->num_rows > 0): ?>
                                            <?php while($product = $pendingProducts->fetch_assoc()): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars(substr($product['title'], 0, 30)); ?></td>
                                                <td><?php echo $product['seller_name']; ?></td>
                                                <td>R <?php echo number_format($product['price'], 2); ?></td>
                                                <td>
                                                    <a href="admin_approve_product.php?id=<?php echo $product['product_id']; ?>" class="btn btn-sm btn-success">Approve</a>
                                                    <a href="admin_delete_product.php?id=<?php echo $product['product_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this product?')">Delete</a>
                                                </td>
                                            </tr>
                                            <?php endwhile; ?>
                                        <?php else: ?>
                                            <tr><td colspan="4" class="text-center">No pending products</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<?php include 'admin_footer.php'; ?>