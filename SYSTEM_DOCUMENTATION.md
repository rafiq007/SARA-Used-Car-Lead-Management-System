# 🚗 AutoDrive Pro Malaysia - Complete System Documentation

## 📋 Table of Contents
1. [System Overview](#system-overview)
2. [Features](#features)
3. [Installation Guide](#installation-guide)
4. [User Roles & Permissions](#user-roles--permissions)
5. [Database Structure](#database-structure)
6. [Page Descriptions](#page-descriptions)
7. [Technical Specifications](#technical-specifications)

---

## System Overview

**AutoDrive Pro Malaysia** is a comprehensive Used Car Lead & Loan Management System designed for managing customer leads from TikTok and tracking them through the entire loan approval process.

### Key Capabilities
✅ Multi-user system with role-based access (Admin, HR, Employee)
✅ Automated Debt Service Ratio (DSR) calculation
✅ Risk assessment (High/Medium/Low)
✅ Follow-up logging system
✅ Real-time dashboard analytics
✅ MySQL database with phpMyAdmin support
✅ Mobile-responsive design

---

## Features

### 1. User Management
- **Login/Logout System**: Secure authentication with password hashing
- **Role-Based Access Control**: Admin, HR Manager, Employee roles
- **User CRUD Operations**: Create, Read, Update, Delete users
- **Profile Management**: Users can update their own profiles
- **Password Change**: Secure password update functionality
- **Activity Logging**: Track all user actions

### 2. Lead Management
- **Add New Leads**: Capture leads from TikTok sources
- **Lead Assignment**: Assign leads to specific employees
- **Status Tracking**: 5 stages (New → Pending Docs → Submitted → Approved/Rejected)
- **Edit/Delete Leads**: Full CRUD operations
- **Search & Filter**: Find leads quickly
- **Follow-up Logs**: Timestamped interaction history

### 3. Automated Loan Qualification
- **Financial Profile Tracking**:
  - Monthly Basic Salary
  - Fixed Allowances
  - Yearly Bonus Average
- **Automatic DSR Calculation**:
  - Monthly Income = Salary + Allowances + (Bonus/12)
  - Loan Amount = 90% of listing price
  - Interest Rate = 3.5% per annum
  - Loan Period = 7 years (84 months)
  - DSR = (Monthly Installment / Monthly Income) × 100
- **Risk Assessment**:
  - 🟢 Low Risk: DSR < 20%
  - 🟡 Medium Risk: DSR 20-30%
  - 🔴 High Risk: DSR > 30%

### 4. Dashboard & Reports
- **Real-time Statistics**:
  - Total Leads
  - Approved Loans
  - Pending Reviews
  - High Risk Count
- **Recent Activity Feed**
- **Status Breakdown**
- **Risk Distribution**
- **Conversion Rate Analytics**

### 5. Security Features
- Password hashing (bcrypt)
- SQL injection prevention (PDO prepared statements)
- XSS protection
- Session management
- Role-based authorization
- Activity audit trail

---

## Installation Guide

### Prerequisites
- **PHP**: Version 7.4 or higher
- **MySQL**: Version 5.7 or higher
- **Web Server**: Apache or Nginx
- **phpMyAdmin**: For database management

### Installation Steps

#### 1. Setup Web Server

**Option A: XAMPP (Recommended for Windows)**
```
1. Download XAMPP from https://www.apachefriends.org/
2. Install XAMPP
3. Start Apache and MySQL from XAMPP Control Panel
```

**Option B: WAMP (Windows Alternative)**
```
1. Download WAMP from http://www.wampserver.com/
2. Install and start services
```

**Option C: Linux (LAMP Stack)**
```bash
sudo apt update
sudo apt install apache2 mysql-server php libapache2-mod-php php-mysql
sudo systemctl start apache2
sudo systemctl start mysql
```

#### 2. Create Database

**Using phpMyAdmin:**
```
1. Open http://localhost/phpmyadmin
2. Click "New" in left sidebar
3. Database name: autodrive_pro
4. Collation: utf8mb4_general_ci
5. Click "Create"
6. Select the database
7. Click "Import" tab
8. Choose file: sql/database.sql
9. Click "Go"
```

**Using MySQL Command Line:**
```bash
mysql -u root -p
CREATE DATABASE autodrive_pro CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE autodrive_pro;
SOURCE /path/to/autodrive-pro-system/sql/database.sql;
EXIT;
```

#### 3. Configure Application

Edit `config/database.php`:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');          // Your MySQL username
define('DB_PASS', '');              // Your MySQL password
define('DB_NAME', 'autodrive_pro');
define('SITE_URL', 'http://localhost/autodrive-pro-system');
```

#### 4. Deploy Files

Copy the entire `autodrive-pro-system` folder to your web root:
- **XAMPP Windows**: `C:\xampp\htdocs\`
- **XAMPP Mac**: `/Applications/XAMPP/htdocs/`
- **WAMP**: `C:\wamp\www\`
- **Linux**: `/var/www/html/`

#### 5. Set Permissions (Linux/Mac only)

```bash
cd /var/www/html/autodrive-pro-system
chmod 755 -R .
chmod 644 config/database.php
```

#### 6. Access the System

Open your browser and navigate to:
```
http://localhost/autodrive-pro-system/login.php
```

---

## User Roles & Permissions

### Administrator
**Full System Access**
- ✅ Manage all users (create, edit, delete)
- ✅ View all leads (regardless of assignment)
- ✅ Create and edit all leads
- ✅ View all reports and analytics
- ✅ Access activity logs
- ✅ Export data

### HR Manager
**User & Lead Management**
- ✅ Manage users (except administrators)
- ✅ View all leads
- ✅ Create and edit leads
- ✅ Assign leads to employees
- ✅ View reports and analytics
- ✅ Access activity logs
- ❌ Cannot manage admin users

### Employee
**Limited Access**
- ✅ View assigned leads only
- ✅ Create new leads
- ✅ Update status of assigned leads
- ✅ Add follow-up logs
- ✅ View personal dashboard
- ❌ Cannot view other employees' leads
- ❌ Cannot manage users
- ❌ Limited reporting access

### Default Accounts

| Username | Password | Role | Purpose |
|----------|----------|------|---------|
| admin | admin123 | Administrator | Full system access |
| hr_manager | admin123 | HR Manager | User & lead management |
| john_employee | admin123 | Employee | Lead handling |

⚠️ **IMPORTANT**: Change all default passwords immediately after first login!

---

## Database Structure

### Tables Overview

#### 1. `users` Table
Stores all system users and authentication data.

**Columns:**
- `id` (INT, PRIMARY KEY, AUTO_INCREMENT)
- `username` (VARCHAR(50), UNIQUE, NOT NULL)
- `password` (VARCHAR(255), NOT NULL) - Bcrypt hashed
- `full_name` (VARCHAR(100), NOT NULL)
- `email` (VARCHAR(100), UNIQUE, NOT NULL)
- `role` (ENUM: 'admin', 'hr', 'employee')
- `phone` (VARCHAR(20))
- `status` (ENUM: 'active', 'inactive')
- `created_at` (TIMESTAMP)
- `last_login` (TIMESTAMP)

#### 2. `leads` Table
Stores customer lead information and loan details.

**Columns:**
- `id` (INT, PRIMARY KEY, AUTO_INCREMENT)
- `full_name` (VARCHAR(100), NOT NULL)
- `phone` (VARCHAR(20), NOT NULL)
- `address` (TEXT, NOT NULL)
- `tiktok_url` (VARCHAR(255))
- `assigned_employee_id` (INT, FOREIGN KEY → users.id)
- `vehicle_model` (VARCHAR(100), NOT NULL)
- `vehicle_year` (INT, NOT NULL)
- `listing_price` (DECIMAL(12,2), NOT NULL)
- `basic_salary` (DECIMAL(10,2), NOT NULL)
- `fixed_allowances` (DECIMAL(10,2))
- `yearly_bonus` (DECIMAL(10,2))
- `loan_status` (ENUM: 'New', 'Pending Docs', 'Submitted to Bank', 'Approved', 'Rejected')
- `dsr_percentage` (DECIMAL(5,2))
- `monthly_income` (DECIMAL(10,2))
- `monthly_installment` (DECIMAL(10,2))
- `risk_level` (ENUM: 'Low', 'Medium', 'High')
- `created_by` (INT, FOREIGN KEY → users.id)
- `created_at` (TIMESTAMP)
- `updated_at` (TIMESTAMP)

#### 3. `follow_up_logs` Table
Tracks all interactions and notes for each lead.

**Columns:**
- `id` (INT, PRIMARY KEY, AUTO_INCREMENT)
- `lead_id` (INT, FOREIGN KEY → leads.id, CASCADE DELETE)
- `user_id` (INT, FOREIGN KEY → users.id)
- `log_entry` (TEXT, NOT NULL)
- `created_at` (TIMESTAMP)

#### 4. `activity_log` Table
System-wide audit trail of user actions.

**Columns:**
- `id` (INT, PRIMARY KEY, AUTO_INCREMENT)
- `user_id` (INT, FOREIGN KEY → users.id)
- `action` (VARCHAR(100), NOT NULL)
- `description` (TEXT)
- `ip_address` (VARCHAR(45))
- `created_at` (TIMESTAMP)

---

## Page Descriptions

### Public Pages

#### `login.php`
- User authentication
- Displays demo credentials
- Session initialization
- Redirects to dashboard on success

### Dashboard Pages

#### `index.php` - Main Dashboard
- Overview statistics (4 stat cards)
- Recent activity feed
- Quick actions (for admin/HR)
- Role-based content display

#### `leads.php` - Lead Management
**List View:**
- Table of all leads with filters
- Color-coded risk levels
- Status badges
- Action buttons (Edit, Log, Delete)

**Add/Edit Form:**
- Client personal information
- Lead source (TikTok URL)
- Vehicle details
- Financial profile
- Real-time DSR calculation
- Loan status selection

#### `users.php` - User Management (Admin/HR only)
**List View:**
- All system users
- Role indicators
- Status badges
- Last login tracking

**Add/Edit Form:**
- Username (add only)
- Password (add only)
- Full name and contact info
- Role assignment
- Status toggle

### Utility Pages

#### `profile.php`
- View/edit personal information
- Cannot change role or username
- Link to password change

#### `change-password.php`
- Current password verification
- New password (min 6 characters)
- Confirmation field
- Security validation

#### `reports.php`
- Total approved value
- Approval rate statistics
- Status breakdown table
- Risk distribution
- Export options

#### `activity-log.php`
- Recent 100 activities
- User action tracking
- Timestamp and IP logging
- Audit trail

#### `logout.php`
- Session cleanup
- Activity logging
- Redirect to login

---

## Technical Specifications

### Frontend Technologies
- **HTML5**: Semantic markup
- **CSS3**: Modern styling with gradients, flexbox, grid
- **JavaScript (Vanilla)**: No frameworks needed
  - Real-time DSR calculation
  - Form validation
  - Modal dialogs
  - AJAX for follow-up logs

### Backend Technologies
- **PHP 7.4+**: Server-side logic
- **MySQL 5.7+**: Relational database
- **PDO**: Database abstraction layer
- **Bcrypt**: Password hashing

### Design Patterns
- **MVC-inspired**: Separation of concerns
- **Singleton**: Database connection
- **Factory**: User creation
- **Repository**: Data access layer

### Security Measures
1. **Authentication**: Session-based with secure cookies
2. **Authorization**: Role-based access control
3. **Password Security**: Bcrypt hashing (cost factor 10)
4. **SQL Injection**: PDO prepared statements
5. **XSS Protection**: htmlspecialchars() on all output
6. **CSRF Ready**: Token system can be implemented
7. **Session Security**: 
   - HTTP-only cookies
   - Regeneration on login
   - Timeout after inactivity

### File Structure
```
autodrive-pro-system/
├── api/
│   └── get-logs.php          # AJAX endpoint for logs
├── assets/
│   ├── css/
│   │   └── style.css         # Main stylesheet
│   └── js/
│       └── main.js           # Client-side scripts
├── config/
│   └── database.php          # DB configuration
├── includes/
│   ├── auth.php              # Authentication functions
│   ├── leads.php             # Lead management functions
│   ├── header.php            # Header template
│   └── sidebar.php           # Navigation template
├── sql/
│   └── database.sql          # Database schema + seed data
├── index.php                 # Dashboard
├── login.php                 # Login page
├── logout.php                # Logout handler
├── leads.php                 # Lead management
├── users.php                 # User management
├── profile.php               # User profile
├── change-password.php       # Password change
├── reports.php               # Analytics
├── activity-log.php          # Audit trail
├── README.md                 # Full documentation
└── QUICKSTART.md             # Quick start guide
```

### Browser Compatibility
- ✅ Chrome 90+
- ✅ Firefox 88+
- ✅ Safari 14+
- ✅ Edge 90+
- ✅ Mobile browsers (responsive design)

### Performance Optimizations
- Indexed database columns
- Prepared statement caching
- Minimal JavaScript dependencies
- Optimized CSS delivery
- Lazy loading where applicable

---

## Support & Maintenance

### Regular Maintenance Tasks
1. **Daily**: Monitor activity logs
2. **Weekly**: Review high-risk leads
3. **Monthly**: Backup database
4. **Quarterly**: Update passwords
5. **Yearly**: System audit

### Backup Procedure
```bash
# Database backup
mysqldump -u root -p autodrive_pro > backup_$(date +%Y%m%d).sql

# File backup
tar -czf backup_files_$(date +%Y%m%d).tar.gz /path/to/autodrive-pro-system
```

### Troubleshooting Common Issues

**Issue**: Cannot login
- Check database connection
- Verify user exists in database
- Check password hash
- Clear browser cache

**Issue**: DSR not calculating
- Check JavaScript console
- Verify all form fields have values
- Ensure main.js is loaded

**Issue**: Blank page
- Enable PHP error reporting
- Check PHP error logs
- Verify file permissions
- Check database connection

---

## Future Enhancements (Roadmap)

### Phase 2
- [ ] Email notifications
- [ ] SMS integration
- [ ] Document upload system
- [ ] Advanced filtering
- [ ] Export to PDF/Excel

### Phase 3
- [ ] API integration with banks
- [ ] WhatsApp notifications
- [ ] Mobile app
- [ ] Advanced analytics dashboard
- [ ] Multi-language support

---

**Version**: 1.0.0
**Last Updated**: December 2024
**Developed For**: AutoDrive Pro Malaysia
