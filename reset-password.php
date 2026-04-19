<?php
/*
 * PASSWORD RESET UTILITY
 * 
 * This script resets all default user passwords to 'admin123'
 * Run this ONCE via browser, then delete this file for security
 * 
 * URL: http://yourdomain.com/autodrive-pro-system/reset_passwords.php
 */

// Include database configuration
require_once 'config/database.php';

// Security check - delete this file after running!
$already_run = false;

if ($already_run) {
    die('This script has already been run. Delete this file for security.');
}

echo "<h1>Password Reset Utility</h1>";
echo "<p>Resetting passwords for all default users...</p>";

try {
    $db = getDB();
    
    // The correct password hash for 'admin123'
    $password = 'admin123';
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    echo "<p><strong>New password hash generated:</strong> " . substr($hashed_password, 0, 30) . "...</p>";
    
    // Update admin password
    $stmt = $db->prepare("UPDATE users SET password = ? WHERE username = 'admin'");
    $stmt->execute([$hashed_password]);
    echo "<p>✅ Admin password reset</p>";
    
    // Update hr_manager password
    $stmt = $db->prepare("UPDATE users SET password = ? WHERE username = 'hr_manager'");
    $stmt->execute([$hashed_password]);
    echo "<p>✅ HR Manager password reset</p>";
    
    // Update john_employee password
    $stmt = $db->prepare("UPDATE users SET password = ? WHERE username = 'john_employee'");
    $stmt->execute([$hashed_password]);
    echo "<p>✅ Employee password reset</p>";
    
    echo "<hr>";
    echo "<h2>✅ SUCCESS! All passwords have been reset to: admin123</h2>";
    echo "<p><strong>You can now login with:</strong></p>";
    echo "<ul>";
    echo "<li>Username: <strong>admin</strong> / Password: <strong>admin123</strong></li>";
    echo "<li>Username: <strong>hr_manager</strong> / Password: <strong>admin123</strong></li>";
    echo "<li>Username: <strong>john_employee</strong> / Password: <strong>admin123</strong></li>";
    echo "</ul>";
    
    echo "<hr>";
    echo "<h3 style='color: red;'>⚠️ IMPORTANT SECURITY STEP:</h3>";
    echo "<p style='color: red; font-weight: bold;'>DELETE THIS FILE (reset_passwords.php) IMMEDIATELY FOR SECURITY!</p>";
    echo "<p>After testing login, delete this file from your server.</p>";
    
} catch(PDOException $e) {
    echo "<p style='color: red;'>❌ ERROR: " . $e->getMessage() . "</p>";
    echo "<p>Make sure your database credentials in config/database.php are correct.</p>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Password Reset</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        h1 { color: #2a5298; }
        ul { background: white; padding: 20px; border-radius: 5px; }
        li { margin: 10px 0; font-size: 16px; }
    </style>
</head>
<body>
    <hr>
    <p><a href="login.php" style="display: inline-block; background: #2a5298; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Go to Login Page</a></p>
</body>
</html>