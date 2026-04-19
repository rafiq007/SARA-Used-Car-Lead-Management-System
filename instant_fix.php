<?php
/*
 * INSTANT PASSWORD FIX
 * One-click solution to fix login issues
 * Access: https://yourdomain.com/autodrive-pro-system/instant_fix.php
 * DELETE AFTER USE!
 */

require_once 'config/database.php';

echo "<!DOCTYPE html><html><head><title>Instant Fix</title>";
echo "<style>body{font-family:Arial;max-width:600px;margin:100px auto;padding:30px;background:#f5f5f5;} .box{background:white;padding:30px;border-radius:10px;box-shadow:0 2px 10px rgba(0,0,0,0.1);} h1{color:#2a5298;} .success{color:#4caf50;font-size:20px;} .error{color:#f44336;} .btn{display:inline-block;background:#4caf50;color:white;padding:15px 30px;text-decoration:none;border-radius:5px;margin:20px 0;font-weight:bold;}</style>";
echo "</head><body><div class='box'>";

echo "<h1>🔧 Instant Login Fix</h1>";

try {
    $db = getDB();
    
    // Generate fresh password hashes
    $password = 'admin123';
    $hash = password_hash($password, PASSWORD_DEFAULT);
    
    // Update all default users
    $users = ['admin', 'hr_manager', 'john_employee'];
    $updated = 0;
    
    foreach ($users as $username) {
        $stmt = $db->prepare("UPDATE users SET password = ? WHERE username = ?");
        if ($stmt->execute([$hash, $username])) {
            $updated++;
        }
    }
    
    if ($updated > 0) {
        echo "<p class='success'>✅ SUCCESS!</p>";
        echo "<p><strong>$updated user password(s) have been reset.</strong></p>";
        echo "<hr>";
        echo "<h3>Login Credentials:</h3>";
        echo "<p>Username: <strong>admin</strong><br>Password: <strong>admin123</strong></p>";
        echo "<p>Username: <strong>hr_manager</strong><br>Password: <strong>admin123</strong></p>";
        echo "<p>Username: <strong>john_employee</strong><br>Password: <strong>admin123</strong></p>";
        echo "<hr>";
        echo "<a href='login.php' class='btn'>🚀 Go to Login Page</a>";
        echo "<p style='color:#f44336;margin-top:30px;'><strong>⚠️ IMPORTANT:</strong> Delete this file (instant_fix.php) immediately for security!</p>";
    } else {
        echo "<p class='error'>❌ No users were updated. Please check database connection.</p>";
    }
    
} catch(Exception $e) {
    echo "<p class='error'>❌ Error: " . $e->getMessage() . "</p>";
    echo "<p>Please check your database configuration in config/database.php</p>";
}

echo "</div></body></html>";
?>
