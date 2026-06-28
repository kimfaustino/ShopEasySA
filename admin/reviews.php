<?php

require_once '../config.php';

if (!isAdmin()) {
    redirect('login.php');
}

$page_title = 'Manage Reviews';

// Approve review
if (isset($_GET['approve'])) {
    $reviewId = intval($_GET['approve']);
    $conn->query("UPDATE reviews SET is_approved = 1 WHERE review_id = $reviewId");
    setSuccessMessage("Review approved successfully.");
    redirect('admin/reviews.php');
}

// Delete review
if (isset($_GET['delete']) && isset($_GET['id'])) {
    $reviewId = intval($_GET['delete']);
    $conn->query("DELETE FROM reviews WHERE review_id = $reviewId");
    setSuccessMessage("Review deleted successfully.");
    redirect('admin/reviews.php');
}

// Get pending reviews
$pendingReviews = $conn->query("
    SELECT r.*, p.title as product_title, u.full_name as user_name
    FROM reviews r
    JOIN products p ON r.product_id = p.product_id
    JOIN users u ON r.user_id = u.user_id
    WHERE r.is_approved = 0
    ORDER BY r.created_at DESC
");

// Get approved reviews
$approvedReviews = $conn->query("
    SELECT r.*, p.title as product_title, u.full_name as user_name
    FROM reviews r
    JOIN products p ON r.product_id = p.product_id
    JOIN users u ON r.user_id = u.user_id
    WHERE r.is_approved = 1
    ORDER BY r.created_at DESC
");

include 'admin_header.php';
?>

<div class="container-fluid">
    <div class="row">
        <?php include 'admin_sidebar.php'; ?>
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Manage Reviews</h1>
            </div>
            
            <ul class="nav nav-tabs mb-4">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#pending">Pending Approval</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#approved">Approved Reviews</a>
                </li>
            </ul>
            
            <div class="tab-content">
                <!-- Pending Reviews Tab -->
                <div class="tab-pane fade show active" id="pending">
                    <div class="card shadow-sm">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Product</th>
                                            <th>User</th>
                                            <th>Rating</th>
                                            <th>Review</th>
                                            <th>Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while($review = $pendingReviews->fetch_assoc()): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($review['product_title']); ?></td>
                                            <td><?php echo htmlspecialchars($review['user_name']); ?></td>
                                            <td>
                                                <?php for($i = 1; $i <= 5; $i++): ?>
                                                    <?php if($i <= $review['rating']): ?>
                                                        <i class="fas fa-star text-warning"></i>
                                                    <?php else: ?>
                                                        <i class="far fa-star text-muted"></i>
                                                    <?php endif; ?>
                                                <?php endfor; ?>
                                            </td>
                                            <td><?php echo htmlspecialchars(substr($review['comment'], 0, 50)); ?>...</td>
                                            <td><?php echo date('M d, Y', strtotime($review['created_at'])); ?></td>
                                            <td>
                                                <a href="reviews.php?approve=<?php echo $review['review_id']; ?>" class="btn btn-sm btn-success">Approve</a>
                                                <a href="reviews.php?delete=<?php echo $review['review_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this review?')">Delete</a>
                                            </td>
                                        </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Approved Reviews Tab -->
                <div class="tab-pane fade" id="approved">
                    <div class="card shadow-sm">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Product</th>
                                            <th>User</th>
                                            <th>Rating</th>
                                            <th>Review</th>
                                            <th>Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while($review = $approvedReviews->fetch_assoc()): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($review['product_title']); ?></td>
                                            <td><?php echo htmlspecialchars($review['user_name']); ?></td>
                                            <td>
                                                <?php for($i = 1; $i <= 5; $i++): ?>
                                                    <?php if($i <= $review['rating']): ?>
                                                        <i class="fas fa-star text-warning"></i>
                                                    <?php else: ?>
                                                        <i class="far fa-star text-muted"></i>
                                                    <?php endif; ?>
                                                <?php endfor; ?>
                                            </td>
                                            <td><?php echo htmlspecialchars(substr($review['comment'], 0, 50)); ?>...</td>
                                            <td><?php echo date('M d, Y', strtotime($review['created_at'])); ?></td>
                                            <td>
                                                <a href="reviews.php?delete=<?php echo $review['review_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this review?')">Delete</a>
                                            </td>
                                        </tr>
                                        <?php endwhile; ?>
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