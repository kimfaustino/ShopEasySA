<?php
session_start();
require_once '../config.php';

// Check if user is logged in and is admin
if(!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

$page_title = 'Manage Users';

// Handle toggle status
if (isset($_GET['toggle']) && isset($_GET['id'])) {
    $userId = intval($_GET['id']);
    $user = $conn->query("SELECT is_active FROM users WHERE user_id = $userId")->fetch_assoc();
    $newStatus = $user['is_active'] ? 0 : 1;
    $conn->query("UPDATE users SET is_active = $newStatus WHERE user_id = $userId");
    header("Location: admin_users.php");
    exit();
}

$users = $conn->query("SELECT * FROM users ORDER BY created_at DESC");

include 'admin_header.php';
?>

<div class="container-fluid">
    <div class="row">
        <?php include 'admin_sidebar.php'; ?>
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Manage Users</h1>
            </div>
            
            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th><th>Name</th><th>Email</th><th>Phone</th>
                                    <th>Location</th><th>Type</th><th>Status</th><th>Verified</th><th>Joined</th><th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($user = $users->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $user['user_id']; ?></td>
                                    <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                                    <td><?php echo $user['email']; ?></td>
                                    <td><?php echo $user['phone']; ?></td>
                                    <td><?php echo $user['location']; ?></td>
                                    <td><span class="badge bg-<?php echo $user['user_type'] == 'admin' ? 'danger' : ($user['user_type'] == 'seller' ? 'primary' : 'info'); ?>"><?php echo ucfirst($user['user_type']); ?></span></td>
                                    <td><?php echo $user['is_active'] ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Disabled</span>'; ?></td>
                                    <td><?php echo $user['is_verified'] ? '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-warning">No</span>'; ?></td>
                                    <td><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                                    <td>
                                        <a href="admin_users.php?toggle=1&id=<?php echo $user['user_id']; ?>" class="btn btn-sm btn-outline-warning">
                                            <?php echo $user['is_active'] ? 'Disable' : 'Enable'; ?>
                                        </a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<?php include 'admin_footer.php'; ?>