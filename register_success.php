<?php
/* Shows confirmation message after successful registration*/

require_once 'config.php';
$page_title = 'Registration Successful';

include 'header.php';
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-5">
                    <i class="fas fa-check-circle fa-5x text-success mb-4"></i>
                    <h2 class="mb-3">Registration Successful!</h2>
                    <p class="text-muted mb-4">
                        Your account has been created successfully. You can now login to start buying and selling on ShopEasySA.
                    </p>
                    <div class="d-grid gap-3">
                        <a href="login.php" class="btn btn-primary-custom btn-lg">Login Now</a>
                        <a href="index.php" class="btn btn-outline-secondary">Return to Homepage</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>