<?php
/* header with logo left, user right, nav centered*/

if (!isset($conn)) {
    require_once 'config.php';
}

$cartItemCount = getCartCount();
?>
<!DOCTYPE html>
<html lang="en-ZA">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="ShopEasySA - South African Township C2C Marketplace">
    
    <title><?php echo isset($page_title) ? $page_title . ' - ' . SITE_NAME : SITE_NAME; ?></title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>css/style.css">
</head>
<body>

<!-- Flash Messages -->
<?php if(isset($_SESSION['success_message'])): ?>
    <div class="alert alert-success alert-dismissible fade show text-center mb-0 rounded-0" role="alert">
        <i class="fas fa-check-circle"></i> <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if(isset($_SESSION['error_message'])): ?>
    <div class="alert alert-danger alert-dismissible fade show text-center mb-0 rounded-0" role="alert">
        <i class="fas fa-exclamation-circle"></i> <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Header -->
<header class="main-header">
    <div class="container">
        <!-- Row 1: Logo Left, User Actions Right -->
        <div class="header-top">
            <div class="logo-section">
                <a href="<?php echo SITE_URL; ?>index.php" class="logo-link">
                    <span class="logo-icon"></span>
                    <span class="logo-text">ShopEasy<span class="logo-highlight">SA</span></span>
                </a>
            </div>
            
            <div class="user-section">
                <?php if(isLoggedIn()): ?>
                    <div class="profile-dropdown">
                        <button class="profile-btn">
                            <div class="profile-avatar-sm">
                                <i class="fas fa-user"></i>
                            </div>
                            <span class="profile-name"><?php echo $_SESSION['user_name']; ?></span>
                            <i class="fas fa-chevron-down dropdown-arrow"></i>
                        </button>
                        <div class="dropdown-menu-custom">
                            <div class="dropdown-header">
                                <strong><?php echo $_SESSION['user_name']; ?></strong>
                                <small><?php echo $_SESSION['user_type']; ?></small>
                            </div>
                            <div class="dropdown-divider"></div>
                            <a href="<?php echo SITE_URL; ?>profile.php"><i class="fas fa-id-card"></i> My Profile</a>
                            <a href="<?php echo SITE_URL; ?>my_listings.php"><i class="fas fa-list"></i> My Listings</a>
                            <a href="<?php echo SITE_URL; ?>change_password.php"><i class="fas fa-key"></i> Change Password</a>
                            <?php if(isAdmin()): ?>
                                <a href="<?php echo SITE_URL; ?>admin/dashboard.php"><i class="fas fa-tachometer-alt"></i> Admin Dashboard</a>
                            <?php endif; ?>
                            <div class="dropdown-divider"></div>
                            <a href="<?php echo SITE_URL; ?>logout.php" class="logout-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="<?php echo SITE_URL; ?>login.php" class="login-link">Login</a>
                    <a href="<?php echo SITE_URL; ?>register.php" class="register-btn-header">Register/Sign In</a>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Row 2: Navigation Menu - Centered -->
        <nav class="nav-menu">
            <ul class="nav-list">
                <li><a href="<?php echo SITE_URL; ?>index.php">Home</a></li>
                <li><a href="<?php echo SITE_URL; ?>marketplace.php">Marketplace</a></li>
                <li><a href="<?php echo SITE_URL; ?>about.php">About Us</a></li>
                <li><a href="<?php echo SITE_URL; ?>contact.php">Contact</a></li>
            </ul>
        </nav>
    </div>
</header>

<main>