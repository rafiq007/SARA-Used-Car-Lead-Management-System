<?php
require_once 'includes/auth.php';
require_once 'includes/leads.php';

requireLogin();

$currentUser = getCurrentUser();
$stats = getDashboardStats();
$recentActivity = getRecentActivity(5);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - AutoDrive Pro Malaysia</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="main-wrapper">
        <?php include 'includes/sidebar.php'; ?>
        
        <div class="main-content">
            <div class="page-header">
                <h1>Dashboard</h1>
                <p>Welcome back, <?php echo htmlspecialchars($currentUser['full_name']); ?>!</p>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon" style="background: #4CAF50;">📊</div>
                    <div class="stat-info">
                        <h3><?php echo $stats['total_leads']; ?></h3>
                        <p>Total Leads</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon" style="background: #2196F3;">✅</div>
                    <div class="stat-info">
                        <h3><?php echo $stats['approved_loans']; ?></h3>
                        <p>Completed Leads</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon" style="background: #FF9800;">⏳</div>
                    <div class="stat-info">
                        <h3><?php echo $stats['pending_leads']; ?></h3>
                        <p>In Progress</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon" style="background: #F44336;">⚠️</div>
                    <div class="stat-info">
                        <h3><?php echo $stats['high_risk_leads']; ?></h3>
                        <p>High Risk DSR</p>
                    </div>
                </div>
            </div>

            <div class="content-section">
                <h2>Recent Activity</h2>
                <div class="activity-list">
                    <?php if (empty($recentActivity)): ?>
                        <p class="text-muted">No recent activity. Add your first lead to get started!</p>
                    <?php else: ?>
                        <?php foreach ($recentActivity as $activity): ?>
                        <div class="activity-item">
                            <div class="activity-icon">
                                <?php
                                $icon = '📋';
                                if ($activity['lead_status'] === 'Completed') $icon = '✅';
                                elseif ($activity['lead_status'] === 'Rejected') $icon = '❌';
                                elseif ($activity['lead_status'] === 'Submitted') $icon = '📤';
                                elseif ($activity['lead_status'] === 'In Progress') $icon = '🔄';
                                elseif ($activity['lead_status'] === 'Pending') $icon = '⏳';
                                elseif ($activity['risk_level'] === 'High') $icon = '⚠️';
                                echo $icon;
                                ?>
                            </div>
                            <div class="activity-details">
                                <h4><?php echo htmlspecialchars($activity['full_name']); ?></h4>
                                <p><?php echo htmlspecialchars($activity['vehicle_model']); ?> (<?php echo $activity['vehicle_year']; ?>) - 
                                   <span class="status-badge status-<?php echo strtolower(str_replace(' ', '', $activity['lead_status'])); ?>">
                                       <?php echo $activity['lead_status']; ?>
                                   </span>
                                </p>
                            </div>
                            <div class="activity-time">
                                <?php echo date('M d, Y', strtotime($activity['created_at'])); ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (hasRole('admin') || hasRole('hr')): ?>
            <div class="content-section">
                <h2>Quick Actions</h2>
                <div class="action-buttons">
                    <a href="leads.php?action=add" class="btn btn-primary">
                        <span>➕</span> Add New Lead
                    </a>
                    <a href="users.php" class="btn btn-secondary">
                        <span>👥</span> Manage Users
                    </a>
                    <a href="reports.php" class="btn btn-secondary">
                        <span>📊</span> View Reports
                    </a>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="assets/js/main.js"></script>
</body>
</html>
