<?php
/*
 * LOGIN DIAGNOSTIC TOOL
 * This script helps diagnose login issues
 * Access: https://yourdomain.com/autodrive-pro-system/login_diagnostic.php
 * DELETE THIS FILE AFTER FIXING THE ISSUE!
 */

require_once 'config/database.php';

echo "<h1>🔍 Login Diagnostic Tool</h1>";
echo "<style>body{font-family:Arial;max-width:900px;margin:50px auto;padding:20px;} .success{color:green;} .error{color:red;} .info{background:#e3f2fd;padding:15px;border-radius:5px;margin:10px 0;} code{background:#f5f5f5;padding:2px 5px;border-radius:3px;}</style>";

// Test 1: Database Connection
echo "<h2>Test 1: Database Connection</h2>";
try {
    $db = getDB();
    echo "<p class='success'>✅ Database connected successfully!</p>";
} catch(Exception $e) {
    echo "<p class='error'>❌ Database connection failed: " . $e->getMessage() . "</p>";
    echo "<p>Check your config/database.php settings.</p>";
    exit;
}

// Test 2: Check if users table exists and has data
echo "<h2>Test 2: Users Table</h2>";
try {
    $stmt = $db->query("SELECT COUNT(*) as count FROM users");
    $result = $stmt->fetch();
    echo "<p class='success'>✅ Users table exists. Total users: " . $result['count'] . "</p>";
    
    if ($result['count'] == 0) {
        echo "<p class='error'>⚠️ No users found! You need to import the database.</p>";
        exit;
    }
} catch(Exception $e) {
    echo "<p class='error'>❌ Cannot access users table: " . $e->getMessage() . "</p>";
    exit;
}

// Test 3: Check admin user exists
echo "<h2>Test 3: Admin User Check</h2>";
try {
    $stmt = $db->prepare("SELECT * FROM users WHERE username = 'admin'");
    $stmt->execute();
    $admin = $stmt->fetch();
    
    if ($admin) {
        echo "<p class='success'>✅ Admin user exists</p>";
        echo "<div class='info'>";
        echo "<strong>Admin Details:</strong><br>";
        echo "Username: <code>" . htmlspecialchars($admin['username']) . "</code><br>";
        echo "Full Name: <code>" . htmlspecialchars($admin['full_name']) . "</code><br>";
        echo "Email: <code>" . htmlspecialchars($admin['email']) . "</code><br>";
        echo "Role: <code>" . htmlspecialchars($admin['role']) . "</code><br>";
        echo "Status: <code>" . htmlspecialchars($admin['status']) . "</code><br>";
        echo "Password Hash: <code>" . substr($admin['password'], 0, 30) . "...</code><br>";
        echo "</div>";
    } else {
        echo "<p class='error'>❌ Admin user not found!</p>";
        exit;
    }
} catch(Exception $e) {
    echo "<p class='error'>❌ Error checking admin: " . $e->getMessage() . "</p>";
    exit;
}

// Test 4: Password Hashing Functions
echo "<h2>Test 4: PHP Password Functions</h2>";
if (function_exists('password_hash') && function_exists('password_verify')) {
    echo "<p class='success'>✅ Password functions available</p>";
    echo "<p>PHP Version: <code>" . phpversion() . "</code></p>";
    
    // Generate a test hash
    $test_password = 'admin123';
    $test_hash = password_hash($test_password, PASSWORD_DEFAULT);
    echo "<p>Generated test hash for 'admin123': <code>" . substr($test_hash, 0, 30) . "...</code></p>";
} else {
    echo "<p class='error'>❌ Password functions not available!</p>";
    echo "<p>Your PHP version might be too old. Need PHP 5.5+</p>";
    exit;
}

// Test 5: Password Verification Test
echo "<h2>Test 5: Password Verification</h2>";
$test_password = 'admin123';
$current_hash = $admin['password'];

echo "<p>Testing password: <code>admin123</code></p>";
echo "<p>Against stored hash: <code>" . substr($current_hash, 0, 30) . "...</code></p>";

if (password_verify($test_password, $current_hash)) {
    echo "<p class='success'>✅ PASSWORD VERIFICATION SUCCESSFUL!</p>";
    echo "<p><strong>Good news!</strong> The password 'admin123' works with the stored hash.</p>";
    echo "<p class='error'>If you still can't login, the issue might be with sessions or the login form.</p>";
} else {
    echo "<p class='error'>❌ PASSWORD VERIFICATION FAILED!</p>";
    echo "<p><strong>This is the problem!</strong> The stored password hash doesn't match 'admin123'.</p>";
    
    echo "<hr>";
    echo "<h3>🔧 Fix This Now:</h3>";
    echo "<p>Run this SQL query in phpMyAdmin to reset the password:</p>";
    
    $new_hash = password_hash('admin123', PASSWORD_DEFAULT);
    echo "<div class='info'>";
    echo "<code style='display:block;padding:10px;background:white;'>UPDATE users SET password = '" . $new_hash . "' WHERE username = 'admin';</code>";
    echo "</div>";
    
    echo "<p><strong>OR</strong> Use the automated fix button below:</p>";
    
    // Auto-fix button
    if (isset($_GET['autofix'])) {
        try {
            $stmt = $db->prepare("UPDATE users SET password = ? WHERE username = 'admin'");
            $stmt->execute([$new_hash]);
            
            echo "<p class='success'>✅ PASSWORD UPDATED! Try logging in now with admin / admin123</p>";
            echo "<p><a href='login.php' style='display:inline-block;background:#2a5298;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;'>Go to Login Page</a></p>";
        } catch(Exception $e) {
            echo "<p class='error'>❌ Auto-fix failed: " . $e->getMessage() . "</p>";
        }
    } else {
        echo "<p><a href='?autofix=1' style='display:inline-block;background:#4caf50;color:white;padding:15px 30px;text-decoration:none;border-radius:5px;font-weight:bold;'>🔧 AUTO-FIX PASSWORD NOW</a></p>";
    }
}

// Test 6: Session Test
echo "<h2>Test 6: Session Functionality</h2>";
session_start();
$_SESSION['test'] = 'working';
if (isset($_SESSION['test']) && $_SESSION['test'] === 'working') {
    echo "<p class='success'>✅ Sessions are working correctly</p>";
    unset($_SESSION['test']);
} else {
    echo "<p class='error'>❌ Sessions not working!</p>";
}

echo "<hr>";
echo "<h2>Summary</h2>";
echo "<p>✅ If all tests passed and password verified, try logging in again.</p>";
echo "<p>⚠️ If password verification failed, use the auto-fix button above.</p>";
echo "<p class='error'><strong>IMPORTANT:</strong> Delete this file (login_diagnostic.php) after fixing!</p>";

echo "<hr>";
echo "<p><a href='login.php' style='display:inline-block;background:#2a5298;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;margin:5px;'>Go to Login</a>";
echo "<a href='reset_passwords.php' style='display:inline-block;background:#f57c00;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;margin:5px;'>Reset All Passwords</a></p>";
?>
