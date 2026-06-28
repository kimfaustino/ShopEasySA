<?php
/*Allows users to send messages to the platform administrators*/

require_once 'config.php';
$page_title = 'Contact Us';

$success = false;
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $name = cleanInput($_POST['name']);
    $email = cleanInput($_POST['email']);
    $subject = cleanInput($_POST['subject']);
    $message = cleanInput($_POST['message']);
    
    if(empty($name) || empty($email) || empty($subject) || empty($message)) {
        $error = "Please fill in all fields";
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address";
    } else {
        $success = true;
    }
}

include 'header.php';
?>

<div class="container my-5">
    <div class="row">
        <div class="col-12 text-center mb-5">
            <h1 class="display-4 fw-bold">Contact Us</h1>
            <p class="lead">We'd love to hear from you</p>
        </div>
    </div>
    
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card text-center shadow-sm h-100">
                <div class="card-body">
                    <i class="fas fa-phone-alt fa-3x text-primary-custom mb-3"></i>
                    <h5>Call Us</h5>
                    <p class="mb-0">0800 123 456</p>
                    <small class="text-muted">Mon-Fri, 9am-5pm</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center shadow-sm h-100">
                <div class="card-body">
                    <i class="fas fa-envelope fa-3x text-primary-custom mb-3"></i>
                    <h5>Email Us</h5>
                    <p class="mb-0"><?php echo SITE_EMAIL; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center shadow-sm h-100">
                <div class="card-body">
                    <i class="fab fa-whatsapp fa-3x text-primary-custom mb-3"></i>
                    <h5>WhatsApp</h5>
                    <p class="mb-0">079 431 8829</p>
                    <small class="text-muted">Chat with us</small>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-8 mx-auto">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h4 class="mb-0">Send us a message</h4>
            </div>
            <div class="card-body">
                
                <?php if($success): ?>
                    <div class="alert alert-success">
                        Thank you for your message! We will respond within 24 hours.
                    </div>
                <?php endif; ?>
                
                <?php if($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <form method="POST">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Your Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Subject</label>
                        <input type="text" name="subject" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Message</label>
                        <textarea name="message" rows="5" class="form-control" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary-custom w-100">Send Message</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>