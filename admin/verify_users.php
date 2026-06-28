<?php

require_once '../config.php';

if (!isAdmin()) {
    redirect('login.php');
}

$page_title = 'Verify Sellers';

if (isset($_GET['verify']) && isset($_GET['id'])) {
    $userId = intval($_GET['id']);
    $conn->query("UPDATE users SET is_verified = 1 WHERE user_id = $userId");
    setSuccessMessage("Seller verified successfully.");
    redirect('admin/verify_users.php');
}

$pendingSellers = $conn->query("SELECT * FROM users WHERE user_type = 'seller' AND is_verified = 0 ORDER BY created_at DESC");
$verifiedSellers = $conn->query("SELECT * FROM users WHERE user_type = 'seller' AND is_verified = 1 ORDER BY created_at DESC");

include 'admin_header.php';
?>

<div class="container-fluid">
    <div class="row">
        <?php include 'admin_sidebar.php'; ?>
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Verify Sellers</h1>
            </div>
            
            <ul class="nav nav-tabs mb-4">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#pending">Pending Verification</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#verified">Verified Sellers</a>
                </li>
            </ul>
            
            <div class="tab-content">
                <div class="tab-pane fade show active" id="pending">
                    <div class="card shadow-sm">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr><th>Name</th><th>Email</th><th>Phone</th><th>Location</th><th>Joined</th><th>Action</th> </tr>
                                    </thead>
                                    <tbody>
                                        <?php while($seller = $pendingSellers->fetch_assoc()): ?>
                                         <tr>
                                             <td><?php echo htmlspecialchars($seller['full_name']); ?></td>
                                             <td><?php echo $seller['email']; ?></td>
                                             <td><?php echo $seller['phone']; ?></td>
                                             <td><?php echo $seller['location']; ?></td>
                                             <td><?php echo date('M d, Y', strtotime($seller['created_at'])); ?></td>
                                             <td>
                                                 <a href="verify_users.php?verify=1&id=<?php echo $seller['user_id']; ?>" class="btn btn-sm btn-success">Verify</a>
                                             </td>
                                         </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="tab-pane fade" id="verified">
                    <div class="card shadow-sm">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr><th>Name</th><th>Email</th><th>Phone</th><th>Location</th><th>Verified On</th> </tr>
                                    </thead>
                                    <tbody>
                                        <?php while($seller = $verifiedSellers->fetch_assoc()): ?>
                                         <tr>
                                             <td><?php echo htmlspecialchars($seller['full_name']); ?></td>
                                             <td><?php echo $seller['email']; ?></td>
                                             <td><?php echo $seller['phone']; ?></td>
                                             <td><?php echo $seller['location']; ?></td>
                                             <td><?php echo date('M d, Y', strtotime($seller['created_at'])); ?></td>
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