<?php
/*Authenticates users and creates session*/

require_once 'config.php';
$page_title = 'Login';

// If user is already logged in, redirect to appropriate page
if (isLoggedIn()) {
    if (isAdmin()) {
        redirect('admin/dashboard.php');
    } else {
        redirect('index.php');
    }
}

include 'header.php';
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                <div class="card-header bg-primary-custom text-white text-center py-4">
                    <h3 class="mb-0">Welcome Back</h3>
                    <small>Login to your ShopEasySA account</small>
                </div>
                <div class="card-body p-4">
                    
                    <?php if(isset($_GET['error'])): ?>
                        <div class="alert alert-danger text-center">
                            <i class="fas fa-exclamation-triangle"></i> 
                            Invalid email or password. Please try again.
                        </div>
                    <?php endif; ?>
                    
                    <?php if(isset($_GET['registered'])): ?>
                        <div class="alert alert-success text-center">
                            <i class="fas fa-check-circle"></i> 
                            Account created successfully! Please login below.
                        </div>
                    <?php endif; ?>
                    
                    <form action="login_process.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Email Address</label>
                            <input type="email" name="email" class="form-control" required autofocus>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Password</label>
                            <input type="password" name="password" id="password" class="form-control" required>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="remember">
                                <label class="form-check-label small">Remember me</label>
                            </div>
                            <a href="#" class="small text-primary-custom">Forgot password?</a>
                        </div>
                        
                        <button type="submit" class="btn btn-primary-custom w-100 py-3 fw-bold">Login</button>
                    </form>
                    
                    <p class="text-center mt-4 mb-0">
                        Don't have an account? <a href="register.php" class="fw-bold text-primary-custom">Sign up here</a>
                    </p>
                    
                    <hr class="my-4">
                    
                    <div class="text-center">
                        <small class="text-muted">Demo Accounts for Testing:</small>
                        <div class="row mt-2 small">
                            <div class="col-4">
                                <button class="btn btn-sm btn-outline-secondary w-100 mb-1" 
                                        onclick="fillDemo('admin@shopeasysa.co.za', 'admin123')">
                                    Admin
                                </button>
                            </div>
                            <div class="col-4">
                                <button class="btn btn-sm btn-outline-secondary w-100 mb-1" 
                                        onclick="fillDemo('seller@shopeasysa.co.za', 'admin123')">
                                    Seller
                                </button>
                            </div>
                            <div class="col-4">
                                <button class="btn btn-sm btn-outline-secondary w-100 mb-1" 
                                        onclick="fillDemo('buyer@shopeasysa.co.za', 'admin123')">
                                    Buyer
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="alert alert-info mt-3 small text-center">
                <i class="fas fa-shield-alt"></i> Your login is secure.
            </div>
        </div>
    </div>
</div>

<script>
function fillDemo(email, password) {
    document.querySelector('input[name="email"]').value = email;
    document.querySelector('input[name="password"]').value = password;
}
</script>

<?php include 'footer.php'; ?>