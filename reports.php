<?php
require_once 'includes/auth.php';
require_once 'includes/leads.php';

requireLogin();

$stats = getDashboardStats();
$leads = getAllLeads();

// Calculate additional stats
$totalRevenue = 0;
$approvedLeads = array_filter($leads, fn($l) => $l['lead_status'] === 'Completed');
foreach ($approvedLeads as $lead) {
    $totalRevenue += $lead['listing_price'];
}

$statusCounts = [
    'New' => 0,
    'In Progress' => 0,
    'Pending' => 0,
    'Submitted' => 0,
    'Completed' => 0,
    'Rejected' => 0
];

foreach ($leads as $lead) {
    $statusCounts[$lead['lead_status']]++;
}

$riskCounts = [
    'Low' => 0,
    'Medium' => 0,
    'High' => 0
];

foreach ($leads as $lead) {
    $riskCounts[$lead['risk_level']]++;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - AutoDrive Pro Malaysia</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="main-wrapper">
        <?php include 'includes/sidebar.php'; ?>
        
        <div class="main-content">
            <div class="page-header">
                <h1>Reports & Analytics</h1>
                <p>Overview of your lead pipeline performance</p>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon" style="background: #4CAF50;">💰</div>
                    <div class="stat-info">
                        <h3>RM <?php echo number_format($totalRevenue, 0); ?></h3>
                        <p>Total Completed Value</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon" style="background: #2196F3;">📊</div>
                    <div class="stat-info">
                        <h3><?php echo count($approvedLeads); ?>/<?php echo count($leads); ?></h3>
                        <p>Approval Rate</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon" style="background: #FF9800;">⚠️</div>
                    <div class="stat-info">
                        <h3><?php echo $riskCounts['High']; ?></h3>
                        <p>High Risk Applications</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon" style="background: #9C27B0;">📈</div>
                    <div class="stat-info">
                        <h3><?php echo count($leads) > 0 ? round((count($approvedLeads) / count($leads)) * 100, 1) : 0; ?>%</h3>
                        <p>Completion Rate</p>
                    </div>
                </div>
            </div>

            <div class="content-section">
                <h2>Status Breakdown</h2>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Status</th>
                                <th>Count</th>
                                <th>Percentage</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($statusCounts as $status => $count): ?>
                            <tr>
                                <td><span class="status-badge status-<?php echo strtolower(str_replace(' ', '', $status)); ?>"><?php echo $status; ?></span></td>
                                <td><strong><?php echo $count; ?></strong></td>
                                <td><?php echo count($leads) > 0 ? round(($count / count($leads)) * 100, 1) : 0; ?>%</td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="content-section">
                <h2>Risk Level Distribution</h2>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Risk Level</th>
                                <th>Count</th>
                                <th>Percentage</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($riskCounts as $risk => $count): ?>
                            <tr class="risk-<?php echo strtolower($risk); ?>">
                                <td><strong><?php echo $risk; ?> Risk</strong></td>
                                <td><strong><?php echo $count; ?></strong></td>
                                <td><?php echo count($leads) > 0 ? round(($count / count($leads)) * 100, 1) : 0; ?>%</td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <?php if (isAdminOrHR()): ?>
            <div class="content-section">
                <h2>Export Options</h2>
                <div class="action-buttons">
                    <button onclick="window.print()" class="btn btn-primary">🖨️ Print Report</button>
                    <button onclick="alert('CSV export feature coming soon!')" class="btn btn-secondary">📥 Export to CSV</button>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="assets/js/main.js"></script>
</body>
</html>
