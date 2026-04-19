<?php
require_once 'includes/auth.php';
requireLogin();

$currentUser = getCurrentUser();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $result = updateUser(
        $_SESSION['user_id'],
        $_POST['full_name'],
        $_POST['email'],
        $currentUser['role'], // Keep same role
        $_POST['phone'],
        'active' // Keep active
    );
    $message = $result['message'];
    if ($result['success']) {
        $_SESSION['full_name'] = $_POST['full_name'];
        $_SESSION['email'] = $_POST['email'];
        $currentUser = getCurrentUser();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - AutoDrive Pro Malaysia</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="main-wrapper">
        <?php include 'includes/sidebar.php'; ?>
        
        <div class="main-content">
            <?php if ($message): ?>
            <div class="alert alert-success" style="padding: 15px; margin-bottom: 20px; border-radius: 6px; background: #e8f5e9; color: #388e3c;">
                <?php echo htmlspecialchars($message); ?>
            </div>
            <?php endif; ?>

            <div class="page-header">
                <h1>My Profile</h1>
                <p>Manage your account information</p>
            </div>

            <div class="content-section">
                <form method="POST" action="">
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Username</label>
                            <input type="text" value="<?php echo htmlspecialchars($currentUser['username']); ?>" disabled style="background: #f5f5f5;">
                        </div>
                        
                        <div class="form-group">
                            <label>Role</label>
                            <input type="text" value="<?php echo ucfirst($currentUser['role']); ?>" disabled style="background: #f5f5f5;">
                        </div>
                        
                        <div class="form-group">
                            <label>Full Name *</label>
                            <input type="text" name="full_name" value="<?php echo htmlspecialchars($currentUser['full_name']); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Email *</label>
                            <input type="email" name="email" value="<?php echo htmlspecialchars($currentUser['email']); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Phone</label>
                            <input type="tel" name="phone" value="<?php echo htmlspecialchars($currentUser['phone'] ?? ''); ?>">
                        </div>
                    </div>
                    
                    <div style="margin-top: 20px;">
                        <button type="submit" name="update_profile" class="btn btn-primary">💾 Update Profile</button>
                        <a href="change-password.php" class="btn btn-secondary">🔑 Change Password</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="assets/js/main.js"></script>
</body>
</html>
