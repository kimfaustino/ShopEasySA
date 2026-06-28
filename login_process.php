<?php
/* Authenticates user credentials and creates session*/

require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    redirect('login.php');
}

$email = cleanInput($_POST['email']);
$password = $_POST['password'];

// Find user by email (must be active)
$result = $conn->query("SELECT * FROM users WHERE email = '$email' AND is_active = 1");

if ($result->num_rows == 1) {
    $user = $result->fetch_assoc();
    
    if (password_verify($password, $user['password'])) {
        
        // Create session variables
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['user_name'] = $user['full_name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_type'] = $user['user_type'];
        $_SESSION['role'] = $user['user_type'];
        
        // Redirect based on user type
        if ($user['user_type'] == 'admin') {
            redirect('admin/dashboard.php');
        } else {
            redirect('index.php');
        }
    }
}

// Login failed
redirect('login.php?error=1');
?>