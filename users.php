<?php
require_once 'includes/auth.php';

requireAdminOrHR();

$action = $_GET['action'] ?? 'list';
$userId = $_GET['id'] ?? null;
$message = '';
$messageType = 'success';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create_user'])) {
        $result = createUser(
            $_POST['username'],
            $_POST['password'],
            $_POST['full_name'],
            $_POST['email'],
            $_POST['role'],
            $_POST['phone'] ?? null
        );
        $message = $result['message'];
        $messageType = $result['success'] ? 'success' : 'error';
        if ($result['success']) {
            header('Location: users.php?message=' . urlencode($message));
            exit;
        }
    } elseif (isset($_POST['update_user'])) {
        $result = updateUser(
            $_POST['user_id'],
            $_POST['full_name'],
            $_POST['email'],
            $_POST['role'],
            $_POST['phone'] ?? null,
            $_POST['status']
        );
        $message = $result['message'];
        $messageType = $result['success'] ? 'success' : 'error';
        if ($result['success']) {
            header('Location: users.php?message=' . urlencode($message));
            exit;
        }
    }
}

// Handle delete
if ($action === 'delete' && $userId) {
    $result = deleteUser($userId);
    header('Location: users.php?message=' . urlencode($result['message']));
    exit;
}

// Get data
$users = getAllUsers();

if ($action === 'edit' && $userId) {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $editUser = $stmt->fetch();
    
    if (!$editUser) {
        header('Location: users.php?message=User not found');
        exit;
    }
}

if (isset($_GET['message'])) {
    $message = $_GET['message'];
    $messageType = 'success';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - AutoDrive Pro Malaysia</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="main-wrapper">
        <?php include 'includes/sidebar.php'; ?>
        
        <div class="main-content">
            <?php if ($message): ?>
            <div class="alert alert-<?php echo $messageType; ?>" style="padding: 15px; margin-bottom: 20px; border-radius: 6px; background: <?php echo $messageType === 'success' ? '#e8f5e9' : '#ffebee'; ?>; color: <?php echo $messageType === 'success' ? '#388e3c' : '#d32f2f'; ?>;">
                <?php echo htmlspecialchars($message); ?>
            </div>
            <?php endif; ?>

            <?php if ($action === 'list'): ?>
            <div class="page-header">
                <h1>User Management</h1>
                <p>Manage system users, roles, and permissions</p>
            </div>

            <div class="content-section">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h2 style="margin: 0;">All Users</h2>
                    <a href="users.php?action=add" class="btn btn-primary">
                        <span>➕</span> Add New User
                    </a>
                </div>

                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Phone</th>
                                <th>Status</th>
                                <th>Last Login</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($user['username']); ?></strong></td>
                                <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td>
                                    <span class="status-badge status-<?php echo $user['role']; ?>" style="background: <?php 
                                        echo $user['role'] === 'admin' ? '#e3f2fd' : ($user['role'] === 'hr' ? '#fff3e0' : '#f3e5f5'); 
                                    ?>; color: <?php 
                                        echo $user['role'] === 'admin' ? '#1976d2' : ($user['role'] === 'hr' ? '#f57c00' : '#7b1fa2'); 
                                    ?>;">
                                        <?php echo ucfirst($user['role']); ?>
                                    </span>
                                </td>
                                <td><?php echo htmlspecialchars($user['phone'] ?? 'N/A'); ?></td>
                                <td>
                                    <span class="status-badge <?php echo $user['status'] === 'active' ? 'status-approved' : 'status-rejected'; ?>">
                                        <?php echo ucfirst($user['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo $user['last_login'] ? date('M d, Y H:i', strtotime($user['last_login'])) : 'Never'; ?></td>
                                <td>
                                    <div style="display: flex; gap: 5px;">
                                        <a href="users.php?action=edit&id=<?php echo $user['id']; ?>" class="btn btn-secondary" style="padding: 6px 12px; font-size: 12px;">✏️ Edit</a>
                                        <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                        <a href="users.php?action=delete&id=<?php echo $user['id']; ?>" onclick="return confirmDelete('Are you sure you want to delete this user?')" class="btn btn-danger" style="padding: 6px 12px; font-size: 12px;">🗑️</a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <?php elseif ($action === 'add' || $action === 'edit'): ?>
            <div class="page-header">
                <h1><?php echo $action === 'add' ? 'Add New User' : 'Edit User'; ?></h1>
                <p><?php echo $action === 'add' ? 'Create a new system user account' : 'Update user information'; ?></p>
            </div>

            <form method="POST" action="">
                <?php if ($action === 'edit'): ?>
                <input type="hidden" name="user_id" value="<?php echo $editUser['id']; ?>">
                <?php endif; ?>

                <div class="form-section">
                    <h3>Account Information</h3>
                    <div class="form-grid">
                        <?php if ($action === 'add'): ?>
                        <div class="form-group">
                            <label>Username *</label>
                            <input type="text" name="username" required>
                        </div>
                        <div class="form-group">
                            <label>Password *</label>
                            <input type="password" name="password" required minlength="6">
                        </div>
                        <?php endif; ?>
                        
                        <div class="form-group">
                            <label>Full Name *</label>
                            <input type="text" name="full_name" required value="<?php echo htmlspecialchars($editUser['full_name'] ?? ''); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label>Email *</label>
                            <input type="email" name="email" required value="<?php echo htmlspecialchars($editUser['email'] ?? ''); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label>Phone</label>
                            <input type="tel" name="phone" value="<?php echo htmlspecialchars($editUser['phone'] ?? ''); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label>Role *</label>
                            <select name="role" required>
                                <option value="employee" <?php echo ($editUser['role'] ?? '') === 'employee' ? 'selected' : ''; ?>>Employee</option>
                                <option value="hr" <?php echo ($editUser['role'] ?? '') === 'hr' ? 'selected' : ''; ?>>HR Manager</option>
                                <?php if (hasRole('admin')): ?>
                                <option value="admin" <?php echo ($editUser['role'] ?? '') === 'admin' ? 'selected' : ''; ?>>Administrator</option>
                                <?php endif; ?>
                            </select>
                        </div>
                        
                        <?php if ($action === 'edit'): ?>
                        <div class="form-group">
                            <label>Status *</label>
                            <select name="status" required>
                                <option value="active" <?php echo ($editUser['status'] ?? '') === 'active' ? 'selected' : ''; ?>>Active</option>
                                <option value="inactive" <?php echo ($editUser['status'] ?? '') === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                            </select>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div style="display: flex; gap: 10px;">
                    <button type="submit" name="<?php echo $action === 'add' ? 'create_user' : 'update_user'; ?>" class="btn btn-primary">
                        <?php echo $action === 'add' ? '➕ Create User' : '💾 Update User'; ?>
                    </button>
                    <a href="users.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
            <?php endif; ?>
        </div>
    </div>

    <script src="assets/js/main.js"></script>
</body>
</html>
