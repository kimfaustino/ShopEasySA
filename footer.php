</main>

<footer class="bg-dark text-white-50 pt-5 pb-3 mt-5">
    <div class="container">
        <div class="row g-4">
            
            <!-- About Column -->
            <div class="col-12 col-md-4">
                <h5 class="text-white mb-3">About ShopEasySA</h5>
                <p class="small">
                    ShopEasySA is a student project C2C marketplace connecting township entrepreneurs 
                    with local buyers. The platform allows users to buy and sell items safely.
                </p>
                <div class="mt-3">
                    <a href="#" class="text-white-50 me-3"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-white-50 me-3"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-white-50 me-3"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
            
            <!-- Quick Links Column -->
            <div class="col-6 col-md-2">
                <h5 class="text-white mb-3">Quick Links</h5>
                <ul class="list-unstyled small">
                    <li class="mb-2"><a href="<?php echo SITE_URL; ?>index.php" class="text-white-50 text-decoration-none">Home</a></li>
                    <li class="mb-2"><a href="<?php echo SITE_URL; ?>marketplace.php" class="text-white-50 text-decoration-none">Marketplace</a></li>
                    <li class="mb-2"><a href="<?php echo SITE_URL; ?>about.php" class="text-white-50 text-decoration-none">About Us</a></li>
                    <li class="mb-2"><a href="<?php echo SITE_URL; ?>contact.php" class="text-white-50 text-decoration-none">Contact</a></li>
                </ul>
            </div>
            
            <!-- Support Column -->
            <div class="col-6 col-md-2">
                <h5 class="text-white mb-3">Support</h5>
                <ul class="list-unstyled small">
                    <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Help Center</a></li>
                    <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Safety Tips</a></li>
                    <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Report Issue</a></li>
                </ul>
            </div>
            
            <!-- Contact Column -->
            <div class="col-12 col-md-4">
                <h5 class="text-white mb-3">Contact Info</h5>
                <ul class="list-unstyled small">
                    <li class="mb-2">
                        <i class="fas fa-envelope me-2 text-primary-custom"></i>
                        <a href="mailto:<?php echo SITE_EMAIL; ?>" class="text-white-50 text-decoration-none">
                            <?php echo SITE_EMAIL; ?>
                        </a>
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-phone me-2 text-primary-custom"></i>
                        0800 123 456
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-map-marker-alt me-2 text-primary-custom"></i>
                        Mbombela, South Africa
                    </li>
                </ul>
            </div>
        </div>
        
        <hr class="mt-4">
        
        <!-- Copyright -->
        <div class="text-center small">
            &copy; <?php echo date('Y'); ?> ShopEasySA. All rights reserved.
        </div>
    </div>
</footer>


<button onclick="scrollToTop()" id="backToTopBtn" class="btn btn-primary-custom rounded-circle position-fixed" 
        style="bottom: 20px; right: 20px; display: none; width: 45px; height: 45px; z-index: 99;">
    ↑
</button>

<script>
// Back to top button functionality
window.onscroll = function() {
    var button = document.getElementById("backToTopBtn");
    if (document.body.scrollTop > 200 || document.documentElement.scrollTop > 200) {
        button.style.display = "block";
    } else {
        button.style.display = "none";
    }
};

function scrollToTop() {
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
}
</script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Custom JavaScript -->
<script src="<?php echo SITE_URL; ?>js/script.js"></script>

</body>
</html>