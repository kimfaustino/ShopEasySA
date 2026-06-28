<?php


require_once 'config.php';
$page_title = 'Change Password';

if (!isLoggedIn()) {
    redirect('login.php');
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $userId = $_SESSION['user_id'];
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];
    
    $user = $conn->query("SELECT password FROM users WHERE user_id = $userId")->fetch_assoc();
    
    if(password_verify($currentPassword, $user['password'])) {
        if($newPassword == $confirmPassword && strlen($newPassword) >= MIN_PASSWORD_LENGTH) {
            $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
            $conn->query("UPDATE users SET password = '$newHash' WHERE user_id = $userId");
            setSuccessMessage("Password changed successfully.");
            redirect('profile.php');
        } else {
            $error = "New password must match and be at least " . MIN_PASSWORD_LENGTH . " characters";
        }
    } else {
        $error = "Current password is incorrect";
    }
}

include 'header.php';
?>

<div class="container my-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h4 class="mb-0">Change Password</h4>
                </div>
                <div class="card-body">
                    
                    <?php if($error): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Current Password</label>
                            <input type="password" name="current_password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">New Password</label>
                            <input type="password" name="new_password" id="newPassword" class="form-control" required>
                            <small class="text-muted">Minimum <?php echo MIN_PASSWORD_LENGTH; ?> characters</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Confirm New Password</label>
                            <input type="password" name="confirm_password" id="confirmPassword" class="form-control" required>
                            <div id="matchMessage" class="small mt-1"></div>
                        </div>
                        <button type="submit" class="btn btn-primary-custom">Update Password</button>
                        <a href="profile.php" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
var newPass = document.getElementById('newPassword');
var confirmPass = document.getElementById('confirmPassword');
var matchMsg = document.getElementById('matchMessage');

function checkMatch() {
    if (confirmPass.value.length > 0) {
        if (newPass.value === confirmPass.value) {
            matchMsg.innerHTML = '<i class="fas fa-check-circle text-success"></i> Passwords match';
            matchMsg.className = 'small text-success mt-1';
        } else {
            matchMsg.innerHTML = '<i class="fas fa-exclamation-circle text-danger"></i> Passwords do not match';
            matchMsg.className = 'small text-danger mt-1';
        }
    } else {
        matchMsg.innerHTML = '';
    }
}

newPass.addEventListener('input', checkMatch);
confirmPass.addEventListener('input', checkMatch);
</script>

<?php include 'footer.php'; ?>