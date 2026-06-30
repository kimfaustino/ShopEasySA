<?php
/* Displays all available products with search and filter options*/

require_once 'config.php';
$page_title = 'Marketplace';

// Get filter parameters
$search = isset($_GET['search']) ? cleanInput($_GET['search']) : '';
$category = isset($_GET['category']) ? intval($_GET['category']) : 0;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = ITEMS_PER_PAGE;
$offset = ($page - 1) * $limit;


$where = "WHERE p.status = 'active'";

if (!empty($search)) {
    $where .= " AND (p.title LIKE '%$search%' OR p.description LIKE '%$search%')";
}

if ($category > 0) {
    $where .= " AND p.category_id = $category";
}

// Get total count for pagination
$countQuery = "SELECT COUNT(*) as total FROM products p $where";
$countResult = $conn->query($countQuery);
$totalProducts = $countResult->fetch_assoc()['total'];
$totalPages = ceil($totalProducts / $limit);

// Get products for current page
$products = $conn->query("
    SELECT p.*, u.location, c.category_name
    FROM products p
    JOIN users u ON p.seller_id = u.user_id
    JOIN categories c ON p.category_id = c.category_id
    $where
    ORDER BY p.created_at DESC
    LIMIT $offset, $limit
");

// Get categories for sidebar
$categories = $conn->query("SELECT * FROM categories WHERE is_active = 1 ORDER BY display_order");

include 'header.php';
?>

<div class="container my-4">
    <div class="row">
        
        <!-- Sidebar Filters (Desktop) -->
        <div class="col-lg-3 d-none d-lg-block">
            
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white fw-bold">Categories</div>
                <div class="list-group list-group-flush">
                    <a href="marketplace.php" class="list-group-item list-group-item-action <?php echo !$category ? 'active' : ''; ?>">
                        All Categories
                        <span class="badge bg-secondary float-end"><?php echo $totalProducts; ?></span>
                    </a>
                    <?php while($cat = $categories->fetch_assoc()): 
                        $catCount = $conn->query("SELECT COUNT(*) as count FROM products WHERE category_id = {$cat['category_id']} AND status = 'active'")->fetch_assoc();
                    ?>
                    <a href="marketplace.php?category=<?php echo $cat['category_id']; ?>" 
                       class="list-group-item list-group-item-action <?php echo $category == $cat['category_id'] ? 'active' : ''; ?>">
                        <?php echo $cat['category_name']; ?>
                        <span class="badge bg-secondary float-end"><?php echo $catCount['count']; ?></span>
                    </a>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="col-lg-9">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h2 mb-0">Marketplace</h1>
                <?php if(!empty($search)): ?>
                    <p class="text-muted mt-2">
                        Showing results for: <strong>"<?php echo htmlspecialchars($search); ?>"</strong>
                        <a href="marketplace.php" class="text-danger ms-2">Clear</a>
                    </p>
                <?php endif; ?>
            </div>
            
            <div class="alert alert-light border mb-4">
                Found <strong><?php echo $totalProducts; ?></strong> items
                <span class="float-end">Page <?php echo $page; ?> of <?php echo $totalPages; ?></span>
            </div>
            
            <?php if($products->num_rows == 0): ?>
                <div class="text-center py-5">
                    <i class="fas fa-box-open fa-5x text-muted mb-4"></i>
                    <h3>No items found</h3>
                    <p class="text-muted">Try adjusting your search or filter criteria</p>
                    <a href="marketplace.php" class="btn btn-primary-custom">Clear All Filters</a>
                </div>
            <?php else: ?>
                <div class="row g-4">
                    <?php while($product = $products->fetch_assoc()): ?>
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="card h-100 shadow-sm product-card">
                                    <img src="<?php echo htmlspecialchars(str_replace('uploads/', '', $product['image_path'])); ?>" 
                                     class="card-img-top" 
                                     style="height: 200px; object-fit: cover" 
                                     alt="<?php echo htmlspecialchars($product['title']); ?>">
                                <div class="card-body">
                                    <span class="badge bg-secondary mb-2"><?php echo $product['category_name']; ?></span>
                                    <h5 class="card-title"><?php echo htmlspecialchars($product['title']); ?></h5>
                                    <p class="card-text small text-muted"><?php echo htmlspecialchars(substr($product['description'], 0, 80)); ?>...</p>
                                    <h4 class="text-primary-custom fw-bold">R <?php echo number_format($product['price'], 2); ?></h4>
                                    <small class="text-muted"><i class="fas fa-map-marker-alt"></i> <?php echo $product['location']; ?></small>
                                </div>
                                <div class="card-footer bg-white border-0 pb-3">
                                    <a href="product.php?id=<?php echo $product['product_id']; ?>" class="btn btn-primary-custom w-100">View Details</a>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
                
                
                <?php if($totalPages > 1): ?>
                <nav class="mt-5">
                    <ul class="pagination justify-content-center">
                        <?php if($page > 1): ?>
                            <li class="page-item"><a class="page-link" href="?page=<?php echo $page-1; ?>&search=<?php echo urlencode($search); ?>&category=<?php echo $category; ?>">Previous</a></li>
                        <?php else: ?>
                            <li class="page-item disabled"><span class="page-link">Previous</span></li>
                        <?php endif; ?>
                        
                        <?php for($i = 1; $i <= $totalPages; $i++): ?>
                            <?php if($i >= $page-2 && $i <= $page+2): ?>
                                <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&category=<?php echo $category; ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endif; ?>
                        <?php endfor; ?>
                        
                        <?php if($page < $totalPages): ?>
                            <li class="page-item"><a class="page-link" href="?page=<?php echo $page+1; ?>&search=<?php echo urlencode($search); ?>&category=<?php echo $category; ?>">Next</a></li>
                        <?php else: ?>
                            <li class="page-item disabled"><span class="page-link">Next</span></li>
                        <?php endif; ?>
                    </ul>
                </nav>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>