<?php

require_once 'config.php';
$page_title = 'Create Account';

if (isLoggedIn()) {
    redirect('index.php');
}

$errors = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullName = cleanInput($_POST['full_name']);
    $email = cleanInput($_POST['email']);
    $phone = cleanInput($_POST['phone']);
    $location = cleanInput($_POST['location']);
    $userType = cleanInput($_POST['user_type']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    
    if (empty($fullName)) $errors[] = "Full name is required";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Please enter a valid email address";
    
    $emailCheck = $conn->query("SELECT email FROM users WHERE email = '$email'");
    if ($emailCheck->num_rows > 0) $errors[] = "Email address is already registered";
    
    if (empty($phone)) $errors[] = "Phone number is required";
    if (empty($location)) $errors[] = "Please select your location";
    if (strlen($password) < MIN_PASSWORD_LENGTH) $errors[] = "Password must be at least " . MIN_PASSWORD_LENGTH . " characters";
    if ($password !== $confirmPassword) $errors[] = "Passwords do not match";
    
    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (full_name, email, password, phone, location, user_type) 
                VALUES ('$fullName', '$email', '$hashedPassword', '$phone', '$location', '$userType')";
        
        if ($conn->query($sql)) {
            setSuccessMessage("Account created successfully! You can now login.");
            redirect('login.php');
        } else {
            $errors[] = "Database error: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en-ZA">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title . ' - ' . SITE_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>css/style.css">
</head>
<body>

<!-- Register Page Hero with Blur Background -->
<section class="register-hero">
    <div class="site-title">
        <a href="<?php echo SITE_URL; ?>index.php" class="text-decoration-none">
            <h2>ShopEasySA</h2>
        </a>
    </div>
    
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="register-form-container">
                    <div class="text-center mb-4">
                        <h2 class="register-heading display-5 fw-bold">Create Your Account</h2>
                        <p class="text-muted">Join South Africa's fastest growing marketplace</p>
                    </div>
                    
                    <?php if(!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <strong>Please fix the following errors:</strong>
                            <ul class="mb-0 mt-2">
                                <?php foreach($errors as $error): echo "<li>$error</li>"; endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Full Name</label>
                            <input type="text" name="full_name" class="form-control form-control-lg" required>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Email Address</label>
                                <input type="email" name="email" class="form-control form-control-lg" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Phone Number</label>
                                <input type="tel" name="phone" class="form-control form-control-lg" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Location</label>
                            <select name="location" class="form-select form-select-lg" required>
                                <option value="">Select your area</option>
                                <option>Mpumalanga</option><option>Gauteng</option>
                                <option>Free State</option><option>KwaZulu-Natal</option>
                                <option>North West</option><option>Eastern Cape</option>
                                <option>Western Cape</option><option>Northern Cape</option>
                                <option>Limpopo</option><option>Other</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">I want to</label>
                            <div class="d-flex gap-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="user_type" value="buyer" checked>
                                    <label class="form-check-label">Buy Items</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="user_type" value="seller">
                                    <label class="form-check-label">Sell Items</label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Password</label>
                                <input type="password" name="password" id="password" class="form-control form-control-lg" required>
                                <small class="text-muted">Minimum <?php echo MIN_PASSWORD_LENGTH; ?> characters</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Confirm Password</label>
                                <input type="password" name="confirm_password" id="confirmPassword" class="form-control form-control-lg" required>
                            </div>
                        </div>
                        
                        <div class="mb-4 form-check">
                            <input type="checkbox" class="form-check-input" id="termsCheck" required>
                            <label class="form-check-label">I agree to the Terms of Service and Privacy Policy</label>
                        </div>
                        
                        <button type="submit" class="btn-register-gradient w-100 py-3 fw-bold fs-5">Create Account</button>
                    </form>
                    
                    <p class="text-center mt-4 mb-0">
                        Already have an account? <a href="login.php" class="fw-bold">Login here</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
var password = document.getElementById('password');
var confirmPass = document.getElementById('confirmPassword');

function checkMatch() {
    if (confirmPass.value.length > 0 && password.value !== confirmPass.value) {
        confirmPass.setCustomValidity("Passwords do not match");
    } else {
        confirmPass.setCustomValidity("");
    }
}

password.addEventListener('input', checkMatch);
confirmPass.addEventListener('input', checkMatch);

document.querySelector('form').addEventListener('submit', function(e) {
    if (password.value !== confirmPass.value) {
        e.preventDefault();
        alert('Passwords do not match');
    }
    if (!document.getElementById('termsCheck').checked) {
        e.preventDefault();
        alert('You must agree to the Terms of Service');
    }
});
</script>

<?php include 'footer.php'; ?>