<?php
require_once 'includes/auth.php';
require_once 'includes/leads.php';

requireLogin();

$action = $_GET['action'] ?? 'list';
$leadId = $_GET['id'] ?? null;
$message = '';
$messageType = 'success';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_lead'])) {
        $result = createLead($_POST);
        $message = $result['message'];
        $messageType = $result['success'] ? 'success' : 'error';
        if ($result['success']) {
            header('Location: leads.php?message=' . urlencode($message));
            exit;
        }
    } elseif (isset($_POST['update_lead'])) {
        $result = updateLead($_POST['lead_id'], $_POST);
        $message = $result['message'];
        $messageType = $result['success'] ? 'success' : 'error';
        if ($result['success']) {
            header('Location: leads.php?message=' . urlencode($message));
            exit;
        }
    } elseif (isset($_POST['add_log'])) {
        $result = addFollowUpLog($_POST['lead_id'], $_POST['log_entry']);
        echo json_encode($result);
        exit;
    }
}

// Handle delete
if ($action === 'delete' && $leadId) {
    $result = deleteLead($leadId);
    header('Location: leads.php?message=' . urlencode($result['message']));
    exit;
}

// Get data based on action
if ($action === 'edit' && $leadId) {
    $lead = getLead($leadId);
    if (!$lead) {
        header('Location: leads.php?message=Lead not found');
        exit;
    }
}

$employees = getEmployees();
$leads = getAllLeads();

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
    <title>Lead Pipeline - AutoDrive Pro Malaysia</title>
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
                <h1>Lead Pipeline</h1>
                <p>Manage all your TikTok leads - Track progress and add follow-up notes</p>
            </div>

            <?php if (hasRole('employee')): ?>
            <div style="background: #e3f2fd; padding: 15px; border-radius: 6px; margin-bottom: 20px; border-left: 4px solid #1976d2;">
                <strong>💡 Employee Guide:</strong> You can create, edit, and update your assigned leads. All new leads are automatically assigned to you. Use the "Log" button to add notes after each customer interaction. Update the status as you progress through the process.
            </div>
            <?php endif; ?>

            <div class="content-section">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h2 style="margin: 0;">All Leads</h2>
                    <a href="leads.php?action=add" class="btn btn-primary">
                        <span>➕</span> Add New Lead
                    </a>
                </div>

                <div style="margin-bottom: 20px;">
                    <input type="text" id="searchInput" placeholder="Search leads..." onkeyup="filterTable('searchInput', 'leadsTable')" style="padding: 10px; width: 100%; max-width: 400px; border: 2px solid #ddd; border-radius: 6px;">
                </div>

                <div class="table-container">
                    <table id="leadsTable">
                        <thead>
                            <tr>
                                <th>Lead Name</th>
                                <th>Phone</th>
                                <th>Vehicle</th>
                                <th>Price</th>
                                <th>Assigned To</th>
                                <th>DSR</th>
                                <th>Risk</th>
                                <th>Lead Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($leads)): ?>
                            <tr>
                                <td colspan="9" style="text-align: center; color: #666;">No leads found. Add your first lead!</td>
                            </tr>
                            <?php else: ?>
                                <?php foreach ($leads as $lead): ?>
                                <tr class="risk-<?php echo strtolower($lead['risk_level']); ?>">
                                    <td><strong><?php echo htmlspecialchars($lead['full_name']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($lead['phone']); ?></td>
                                    <td><?php echo htmlspecialchars($lead['vehicle_model']); ?> (<?php echo $lead['vehicle_year']; ?>)</td>
                                    <td>RM <?php echo number_format($lead['listing_price'], 2); ?></td>
                                    <td><?php echo htmlspecialchars($lead['assigned_employee_name'] ?? 'Unassigned'); ?></td>
                                    <td><strong><?php echo $lead['dsr_percentage']; ?>%</strong></td>
                                    <td><span class="status-badge status-<?php echo strtolower($lead['risk_level']); ?>"><?php echo $lead['risk_level']; ?> Risk</span></td>
                                    <td><span class="status-badge status-<?php echo strtolower(str_replace(' ', '', $lead['lead_status'])); ?>"><?php echo $lead['lead_status']; ?></span></td>
                                    <td>
                                        <div style="display: flex; gap: 5px;">
                                            <a href="leads.php?action=edit&id=<?php echo $lead['id']; ?>" class="btn btn-secondary" style="padding: 6px 12px; font-size: 12px;">✏️ Edit</a>
                                            <button onclick="openLogModal(<?php echo $lead['id']; ?>)" class="btn btn-success" style="padding: 6px 12px; font-size: 12px;">📝 Log</button>
                                            <a href="leads.php?action=delete&id=<?php echo $lead['id']; ?>" onclick="return confirmDelete('Are you sure you want to delete this lead?')" class="btn btn-danger" style="padding: 6px 12px; font-size: 12px;">🗑️</a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <?php elseif ($action === 'add' || $action === 'edit'): ?>
            <div class="page-header">
                <h1><?php echo $action === 'add' ? 'Add New Lead' : 'Edit Lead'; ?></h1>
                <p><?php echo $action === 'add' ? 'Create a new customer lead from TikTok' : 'Update lead information'; ?></p>
            </div>

            <form method="POST" action="">
                <?php if ($action === 'edit'): ?>
                <input type="hidden" name="lead_id" value="<?php echo $lead['id']; ?>">
                <?php endif; ?>

                <div class="form-section">
                    <h3>Client Personal Information</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Full Name *</label>
                            <input type="text" name="full_name" required value="<?php echo htmlspecialchars($lead['full_name'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label>Phone Number *</label>
                            <input type="tel" name="phone" required value="<?php echo htmlspecialchars($lead['phone'] ?? ''); ?>">
                        </div>
                        <div class="form-group" style="grid-column: 1 / -1;">
                            <label>Home Address *</label>
                            <input type="text" name="address" required value="<?php echo htmlspecialchars($lead['address'] ?? ''); ?>">
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h3>Lead Source<?php echo isAdminOrHR() ? ' & Assignment' : ''; ?></h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label>TikTok Video URL/ID *</label>
                            <input type="text" name="tiktok_url" required value="<?php echo htmlspecialchars($lead['tiktok_url'] ?? ''); ?>" placeholder="https://www.tiktok.com/@username/video/...">
                        </div>
                        
                        <?php if (isAdminOrHR()): ?>
                        <!-- Admin/HR can assign to any employee -->
                        <div class="form-group">
                            <label>Assign to Employee *</label>
                            <select name="assigned_employee_id" required>
                                <option value="">Select Employee</option>
                                <?php foreach ($employees as $emp): ?>
                                <option value="<?php echo $emp['id']; ?>" <?php echo ($lead['assigned_employee_id'] ?? '') == $emp['id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($emp['full_name']); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <?php else: ?>
                        <!-- Employees: auto-assign to themselves -->
                        <input type="hidden" name="assigned_employee_id" value="<?php echo $_SESSION['user_id']; ?>">
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-section">
                    <h3>Vehicle Information</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Vehicle Model *</label>
                            <input type="text" name="vehicle_model" id="vehicle_model" required value="<?php echo htmlspecialchars($lead['vehicle_model'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label>Year *</label>
                            <input type="number" name="vehicle_year" id="vehicle_year" required min="2000" max="2025" value="<?php echo htmlspecialchars($lead['vehicle_year'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label>Listing Price (RM) *</label>
                            <input type="number" name="listing_price" id="listing_price" required step="0.01" value="<?php echo htmlspecialchars($lead['listing_price'] ?? ''); ?>">
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h3>Financial Profile</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Monthly Basic Salary (RM) *</label>
                            <input type="number" name="basic_salary" id="basic_salary" required step="0.01" value="<?php echo htmlspecialchars($lead['basic_salary'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label>Fixed Allowances (RM)</label>
                            <input type="number" name="fixed_allowances" id="fixed_allowances" step="0.01" value="<?php echo htmlspecialchars($lead['fixed_allowances'] ?? '0'); ?>">
                        </div>
                        <div class="form-group">
                            <label>Yearly Bonus Average (RM)</label>
                            <input type="number" name="yearly_bonus" id="yearly_bonus" step="0.01" value="<?php echo htmlspecialchars($lead['yearly_bonus'] ?? '0'); ?>">
                        </div>
                    </div>
                    <div id="dsr-display" style="margin-top: 15px;"></div>
                </div>

                <div class="form-section">
                    <h3>Lead Status & Notes</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Lead Status *</label>
                            <select name="loan_status" required>
                                <option value="New" <?php echo ($lead['lead_status'] ?? '') === 'New' ? 'selected' : ''; ?>>New</option>
                                <option value="In Progress" <?php echo ($lead['lead_status'] ?? '') === 'In Progress' ? 'selected' : ''; ?>>In Progress</option>
                                <option value="Pending" <?php echo ($lead['lead_status'] ?? '') === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                <option value="Submitted" <?php echo ($lead['lead_status'] ?? '') === 'Submitted' ? 'selected' : ''; ?>>Submitted</option>
                                <option value="Completed" <?php echo ($lead['lead_status'] ?? '') === 'Completed' ? 'selected' : ''; ?>>Completed</option>
                                <option value="Rejected" <?php echo ($lead['lead_status'] ?? '') === 'Rejected' ? 'selected' : ''; ?>>Rejected</option>
                            </select>
                        </div>
                        <?php if ($action === 'add'): ?>
                        <div class="form-group">
                            <label>Initial Notes</label>
                            <textarea name="initial_notes"></textarea>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div style="display: flex; gap: 10px;">
                    <button type="submit" name="<?php echo $action === 'add' ? 'add_lead' : 'update_lead'; ?>" class="btn btn-primary">
                        <?php echo $action === 'add' ? '➕ Add Lead' : '💾 Update Lead'; ?>
                    </button>
                    <a href="leads.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
            <?php endif; ?>
        </div>
    </div>

    <!-- Follow-up Log Modal -->
    <div id="logModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; justify-content: center; align-items: center;">
        <div style="background: white; padding: 30px; border-radius: 10px; max-width: 600px; width: 90%; max-height: 80vh; overflow-y: auto;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3 style="color: #2a5298; margin: 0;">Follow-up Log</h3>
                <button onclick="closeLogModal()" style="background: none; border: none; font-size: 24px; cursor: pointer;">&times;</button>
            </div>
            <div>
                <div class="form-group">
                    <label>Add New Log Entry</label>
                    <textarea id="newLogEntry" placeholder="e.g., Sent payslip to Maybank"></textarea>
                </div>
                <button onclick="addLogEntry()" class="btn btn-primary">Add Entry</button>
                <hr style="margin: 20px 0;">
                <h4 style="margin-bottom: 15px;">Previous Entries</h4>
                <div id="logHistory"></div>
            </div>
        </div>
    </div>

    <script src="assets/js/main.js"></script>
    <script>
        let currentLogLeadId = null;

        function openLogModal(leadId) {
            currentLogLeadId = leadId;
            document.getElementById('logModal').style.display = 'flex';
            loadLogHistory(leadId);
        }

        function closeLogModal() {
            document.getElementById('logModal').style.display = 'none';
            document.getElementById('newLogEntry').value = '';
        }

        function loadLogHistory(leadId) {
            fetch(`api/get-logs.php?lead_id=${leadId}`)
                .then(response => response.json())
                .then(logs => {
                    const historyDiv = document.getElementById('logHistory');
                    if (logs.length === 0) {
                        historyDiv.innerHTML = '<p style="color: #666;">No log entries yet.</p>';
                    } else {
                        historyDiv.innerHTML = logs.map(log => `
                            <div style="background: #f8f9fa; padding: 12px; border-left: 4px solid #2a5298; margin-bottom: 10px; border-radius: 4px;">
                                <div style="font-size: 12px; color: #666; margin-bottom: 5px;">
                                    ${new Date(log.created_at).toLocaleString()} - ${log.user_name}
                                </div>
                                <div>${log.log_entry}</div>
                            </div>
                        `).join('');
                    }
                });
        }

        function addLogEntry() {
            const entry = document.getElementById('newLogEntry').value.trim();
            if (!entry) {
                alert('Please enter a log entry');
                return;
            }

            const formData = new FormData();
            formData.append('add_log', '1');
            formData.append('lead_id', currentLogLeadId);
            formData.append('log_entry', entry);

            fetch('leads.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    document.getElementById('newLogEntry').value = '';
                    loadLogHistory(currentLogLeadId);
                    showMessage('Log entry added successfully');
                } else {
                    alert('Error: ' + result.message);
                }
            });
        }
    </script>
</body>
</html>