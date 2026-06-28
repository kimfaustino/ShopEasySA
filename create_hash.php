<?php
// Simple script to create a working hash for admin123
$password = 'admin123';
$hash = password_hash($password, PASSWORD_DEFAULT);

echo "Password: " . $password . "<br>";
echo "New Hash: " . $hash . "<br><br>";
echo "Copy this SQL and run it in MySQL:<br>";
echo "UPDATE users SET password = '$hash' WHERE email = 'admin@shopeasysa.co.za';";
?>