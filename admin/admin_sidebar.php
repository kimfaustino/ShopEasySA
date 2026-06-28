<nav class="col-md-3 col-lg-2 d-md-block bg-dark sidebar">
    <div class="position-sticky pt-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>" href="dashboard.php">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'users.php' ? 'active' : ''; ?>" href="admin_users.php">
                    <i class="fas fa-users"></i> Users
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'products.php' ? 'active' : ''; ?>" href="admin_products.php">
                    <i class="fas fa-box"></i> Products
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'transactions.php' ? 'active' : ''; ?>" href="transactions.php">
                    <i class="fas fa-exchange-alt"></i> Transactions
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'verify_users.php' ? 'active' : ''; ?>" href="verify_users.php">
                    <i class="fas fa-check-circle"></i> Verify Sellers
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'reviews.php' ? 'active' : ''; ?>" href="reviews.php">
                    <i class="fas fa-star"></i> Reviews
                </a>
            </li>
            <li class="nav-item mt-3">
                <hr class="border-secondary">
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../index.php">
                    <i class="fas fa-store"></i> Back to Site
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-danger" href="../logout.php">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </li>
        </ul>
    </div>
</nav>
