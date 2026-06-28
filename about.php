<?php
require_once 'config.php';
$page_title = 'About Us';

include 'header.php';
?>

<div class="container my-5">
    <div class="row">
        <div class="col-12 text-center mb-5">
            <h1 class="display-4 fw-bold">About ShopEasySA</h1>
            <p class="lead">Empowering South African informal entrepreneurs</p>
        </div>
    </div>
    
    <div class="row g-5 mb-5">
        <div class="col-md-6">
            <h2 class="h3 text-primary-custom">Our Mission</h2>
            <p>
                ShopEasySA was created to connect local sellers and buyers in South African townships. 
                We believe that everyone should have access to a safe, easy-to-use platform to buy 
                and sell goods within their community.
            </p>
            <p>
                Our platform removes barriers to entry for small entrepreneurs and provides a secure 
                environment for transactions through our escrow protection system.
            </p>
        </div>
        <div class="col-md-6">
            <div class="bg-light p-4 rounded-4">
                <h3 class="h4 text-primary-custom text-center">Our Impact</h3>
                <div class="row text-center mt-4">
                    <div class="col-4">
                        <div class="display-4 text-primary-custom fw-bold">500+</div>
                        <small>Active Sellers</small>
                    </div>
                    <div class="col-4">
                        <div class="display-4 text-primary-custom fw-bold">1,000+</div>
                        <small>Items Sold</small>
                    </div>
                    <div class="col-4">
                        <div class="display-4 text-primary-custom fw-bold">R2M+</div>
                        <small>In Sales</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="mb-5">
        <h2 class="text-center mb-4">Why Choose ShopEasySA</h2>
        <div class="row g-4">
            <div class="col-md-4 text-center">
                <div class="icon-circle mx-auto mb-3">
                    <i class="fas fa-shield-alt fa-2x text-white"></i>
                </div>
                <h5>Safe Escrow System</h5>
                <p class="small">Your money is protected until you receive your item</p>
            </div>
            <div class="col-md-4 text-center">
                <div class="icon-circle mx-auto mb-3">
                    <i class="fas fa-hand-holding-usd fa-2x text-white"></i>
                </div>
                <h5>Zero Listing Fees</h5>
                <p class="small">Post items for free, pay only when you sell</p>
            </div>
            <div class="col-md-4 text-center">
                <div class="icon-circle mx-auto mb-3">
                    <i class="fas fa-users fa-2x text-white"></i>
                </div>
                <h5>Local Community</h5>
                <p class="small">Connect with buyers and sellers in your area</p>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>