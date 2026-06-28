<?php
/*Allows sellers to post new items for sale*/

require_once 'config.php';
$page_title = 'Sell an Item';

if (!isLoggedIn()) {
    setErrorMessage("Please login to sell items.");
    redirect('login.php');
}

if ($_SESSION['user_type'] != 'seller' && !isAdmin()) {
    setErrorMessage("You need to be a seller to list items.");
    redirect('index.php');
}

$categories = $conn->query("SELECT * FROM categories WHERE is_active = 1 ORDER BY category_name");

include 'header.php';
?>

<div class="container my-4">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-7">
            
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                <div class="card-header bg-primary-custom text-white text-center py-4">
                    <h3 class="mb-0">Sell Your Item</h3>
                    <small>Reach thousands of buyers in your community</small>
                </div>
                <div class="card-body p-4">
                    
                    <form action="listing_process.php" method="POST" enctype="multipart/form-data" id="sellForm">
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Item Title</label>
                            <input type="text" name="title" class="form-control" 
                                   placeholder="e.g., Nike Running Shoes - Size 42" required>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Category</label>
                                <select name="category_id" class="form-select" required>
                                    <option value="">Select category</option>
                                    <?php while($cat = $categories->fetch_assoc()): ?>
                                        <option value="<?php echo $cat['category_id']; ?>"><?php echo $cat['category_name']; ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Price (R)</label>
                                <input type="number" step="0.01" name="price" class="form-control" 
                                       placeholder="0.00" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Description</label>
                            <textarea name="description" rows="6" class="form-control" 
                                      placeholder="Describe your item in detail: condition, size, colour, reason for selling..." required></textarea>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">Product Photo</label>
                            <input type="file" name="product_image" class="form-control" accept="image/*" required>
                            <small class="text-muted">Upload a clear photo (JPG, PNG, max 5MB)</small>
                        </div>
                        
                        <div class="alert alert-info small">
                            Selling on ShopEasySA is FREE! You only pay a 5% fee when your item sells.
                        </div>
                        
                        <button type="submit" class="btn btn-primary-custom w-100 py-3 fw-bold">Post Listing</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('sellForm').addEventListener('submit', function(e) {
    var title = document.querySelector('input[name="title"]').value;
    var price = document.querySelector('input[name="price"]').value;
    
    if(title.length < 5) {
        e.preventDefault();
        alert('Please enter a title with at least 5 characters');
    }
    if(price <= 0) {
        e.preventDefault();
        alert('Please enter a valid price');
    }
});
</script>

<?php include 'footer.php'; ?>