<?php
/*Allows sellers to update their existing listings*/

require_once 'config.php';
$page_title = 'Edit Listing';

if (!isLoggedIn()) {
    redirect('login.php');
}

$productId = isset($_GET['id']) ? intval($_GET['id']) : 0;
$userId = $_SESSION['user_id'];

$product = $conn->query("SELECT * FROM products WHERE product_id = $productId AND seller_id = $userId")->fetch_assoc();

if(!$product) {
    setErrorMessage("Product not found or you don't have permission.");
    redirect('my_listings.php');
}

$categories = $conn->query("SELECT * FROM categories WHERE is_active = 1 ORDER BY category_name");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $title = cleanInput($_POST['title']);
    $description = cleanInput($_POST['description']);
    $price = floatval($_POST['price']);
    $categoryId = intval($_POST['category_id']);
    
    $sql = "UPDATE products SET title='$title', description='$description', price=$price, category_id=$categoryId 
            WHERE product_id=$productId";
    
    $conn->query($sql);
    setSuccessMessage("Listing updated successfully.");
    redirect('my_listings.php');
}

include 'header.php';
?>

<div class="container my-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h4 class="mb-0">Edit Listing: <?php echo htmlspecialchars($product['title']); ?></h4>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Title</label>
                            <input type="text" name="title" class="form-control" 
                                   value="<?php echo htmlspecialchars($product['title']); ?>" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Category</label>
                                <select name="category_id" class="form-select">
                                    <?php while($cat = $categories->fetch_assoc()): ?>
                                        <option value="<?php echo $cat['category_id']; ?>" 
                                                <?php echo $product['category_id'] == $cat['category_id'] ? 'selected' : ''; ?>>
                                            <?php echo $cat['category_name']; ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Price (R)</label>
                                <input type="number" step="0.01" name="price" class="form-control" 
                                       value="<?php echo $product['price']; ?>" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" rows="6" class="form-control" required><?php echo htmlspecialchars($product['description']); ?></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary-custom">Save Changes</button>
                        <a href="my_listings.php" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>