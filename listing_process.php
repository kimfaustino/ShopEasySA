<?php
/*Saves new product listing to the database*/

require_once 'config.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $sellerId = $_SESSION['user_id'];
    $categoryId = intval($_POST['category_id']);
    $title = cleanInput($_POST['title']);
    $description = cleanInput($_POST['description']);
    $price = floatval($_POST['price']);
    
    $uploadDir = "uploads/product_images/";
    
    if(!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    $fileName = time() . "_" . basename($_FILES["product_image"]["name"]);
    $targetFile = $uploadDir . $fileName;
    
    $check = getimagesize($_FILES["product_image"]["tmp_name"]);
    
    if($check !== false) {
        if(move_uploaded_file($_FILES["product_image"]["tmp_name"], $targetFile)) {
            
            $sql = "INSERT INTO products (seller_id, category_id, title, description, price, image_path, status) 
                    VALUES ($sellerId, $categoryId, '$title', '$description', $price, '$targetFile', 'pending')";
            
            if($conn->query($sql)) {
                setSuccessMessage("Your item has been listed! Pending admin approval.");
                redirect('my_listings.php');
            } else {
                setErrorMessage("Database error: " . $conn->error);
            }
        } else {
            setErrorMessage("Error uploading file. Please check folder permissions.");
        }
    } else {
        setErrorMessage("File is not a valid image.");
    }
}

redirect('add_listing.php');
?>