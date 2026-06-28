<?php
session_start();
require_once '../config.php';

// Check if user is logged in and is admin
if(!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

$page_title = 'Manage Products';

$status = isset($_GET['status']) ? $_GET['status'] : 'all';
$where = $status == 'pending' ? "WHERE p.status = 'pending'" : 
         ($status == 'active' ? "WHERE p.status = 'active'" : "WHERE 1=1");

$products = $conn->query("
    SELECT p.*, u.full_name as seller_name, c.category_name 
    FROM products p 
    JOIN users u ON p.seller_id = u.user_id 
    JOIN categories c ON p.category_id = c.category_id 
    $where
    ORDER BY p.created_at DESC
");

include 'admin_header.php';
?>

<div class="container-fluid">
    <div class="row">
        <?php include 'admin_sidebar.php'; ?>
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Manage Products</h1>
            </div>
            
            <div class="mb-3">
                <a href="admin_products.php" class="btn btn-sm btn-outline-secondary">All</a>
                <a href="admin_products.php?status=pending" class="btn btn-sm btn-outline-warning">Pending</a>
                <a href="admin_products.php?status=active" class="btn btn-sm btn-outline-success">Active</a>
            </div>
            
            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th><th>Image</th><th>Title</th><th>Seller</th>
                                    <th>Category</th><th>Price</th><th>Status</th><th>Views</th><th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if($products && $products->num_rows > 0): ?>
                                    <?php while($product = $products->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo $product['product_id']; ?></td>
                                        <td>
                                            <?php if(!empty($product['image_path']) && file_exists('../' . $product['image_path'])): ?>
                                                <img src="../<?php echo $product['image_path']; ?>" width="50" height="50" style="object-fit:cover;">
                                            <?php else: ?>
                                                <div class="bg-light d-flex align-items-center justify-content-center" style="width:50px;height:50px;">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo htmlspecialchars(substr($product['title'], 0, 40)); ?></td>
                                        <td><?php echo $product['seller_name']; ?></td>
                                        <td><?php echo $product['category_name']; ?></td>
                                        <td>R <?php echo number_format($product['price'], 2); ?></td>
                                        <td><span class="badge bg-<?php echo $product['status'] == 'active' ? 'success' : 'warning'; ?>"><?php echo ucfirst($product['status']); ?></span></td>
                                        <td><?php echo number_format($product['views']); ?></td>
                                        <td>
                                            <?php if($product['status'] == 'pending'): ?>
                                                <a href="approve_product.php?id=<?php echo $product['product_id']; ?>" class="btn btn-sm btn-success">Approve</a>
                                            <?php endif; ?>
                                            <a href="delete_product.php?id=<?php echo $product['product_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this product?')">Delete</a>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="9" class="text-center">No products found</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<?php include 'admin_footer.php'; ?>