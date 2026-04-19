<?php
require_once 'includes/auth.php';

requireLogin();

$db = getDB();

// Get activity logs
$sql = "
    SELECT 
        a.*,
        u.full_name as user_name,
        u.username
    FROM activity_log a
    LEFT JOIN users u ON a.user_id = u.id
    ORDER BY a.created_at DESC
    LIMIT 100
";

$stmt = $db->query($sql);
$activities = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Log - AutoDrive Pro Malaysia</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="main-wrapper">
        <?php include 'includes/sidebar.php'; ?>
        
        <div class="main-content">
            <div class="page-header">
                <h1>Activity Log</h1>
                <p>System-wide audit trail of user actions</p>
            </div>

            <div class="content-section">
                <h2>Recent Activities</h2>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Date & Time</th>
                                <th>User</th>
                                <th>Action</th>
                                <th>Description</th>
                                <th>IP Address</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($activities)): ?>
                            <tr>
                                <td colspan="5" style="text-align: center; color: #666;">No activity logs found</td>
                            </tr>
                            <?php else: ?>
                                <?php foreach ($activities as $activity): ?>
                                <tr>
                                    <td><?php echo date('M d, Y H:i:s', strtotime($activity['created_at'])); ?></td>
                                    <td><strong><?php echo htmlspecialchars($activity['user_name'] ?? 'System'); ?></strong></td>
                                    <td><span class="status-badge status-new"><?php echo htmlspecialchars($activity['action']); ?></span></td>
                                    <td><?php echo htmlspecialchars($activity['description'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($activity['ip_address'] ?? 'N/A'); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/js/main.js"></script>
</body>
</html>
