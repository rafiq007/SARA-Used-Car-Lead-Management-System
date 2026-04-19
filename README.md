# AutoDrive Pro Malaysia - Installation Guide
LIVE URL::: ✅👍👌https://sara.ourwisdom.xyz/login.php
## System Requirements
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server
- phpMyAdmin (for database management)

## Installation Steps

### 1. Database Setup

#### Option A: Using phpMyAdmin
1. Open phpMyAdmin in your browser (usually http://localhost/phpmyadmin)
2. Click on "New" to create a new database
3. Name it: `autodrive_pro`
4. Click "Create"
5. Select the `autodrive_pro` database
6. Click on "Import" tab
7. Choose the file: `sql/database.sql`
8. Click "Go" to import

#### Option B: Using MySQL Command Line
```bash
mysql -u root -p
CREATE DATABASE autodrive_pro;
USE autodrive_pro;
SOURCE /path/to/sql/database.sql;
EXIT;
```

### 2. Configure Database Connection

Edit the file: `config/database.php`

Update these lines with your MySQL credentials:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');      // Your MySQL username
define('DB_PASS', '');          // Your MySQL password
define('DB_NAME', 'autodrive_pro');
```

### 3. File Permissions

Make sure the following directories are writable:
```bash
chmod 755 assets/
chmod 755 includes/
```

### 4. Apache Configuration (if using Apache)

Make sure mod_rewrite is enabled and .htaccess files are allowed.

Create a `.htaccess` file in the root directory:
```apache
RewriteEngine On
RewriteBase /autodrive-pro-system/

# Prevent directory listing
Options -Indexes

# Protect sensitive files
<FilesMatch "^(config|includes)/.*\.php$">
    Order Deny,Allow
    Deny from all
</FilesMatch>
```

### 5. Access the Application

1. Copy the entire `autodrive-pro-system` folder to your web server directory:
   - XAMPP: `C:\xampp\htdocs\`
   - WAMP: `C:\wamp\www\`
   - Linux: `/var/www/html/`

2. Open your browser and navigate to:
   ```
   http://localhost/autodrive-pro-system/login.php
   ```

### 6. Default Login Credentials

**Administrator Account:**
- Username: `admin`
- Password: `admin123`

**HR Manager Account:**
- Username: `hr_manager`
- Password: `admin123`

**Employee Account:**
- Username: `john_employee`
- Password: `admin123`

**⚠️ IMPORTANT:** Change these default passwords immediately after first login!

## Features Overview

### User Roles & Permissions

1. **Administrator**
   - Full system access
   - Manage all users
   - View all leads across all employees
   - Generate reports
   - Access activity logs

2. **HR Manager**
   - Manage users (except admins)
   - View all leads across all employees
   - Generate reports
   - Access activity logs

3. **Employee**
   - Create new leads from TikTok inquiries
   - View and edit ONLY assigned leads
   - Update lead status throughout the process
   - Add detailed follow-up notes and progress updates
   - Submit leads for completion

### Key Features

1. **Employee-Focused Lead Management**
   - Employees create leads from TikTok customer inquiries
   - Each employee sees only their assigned leads
   - Full edit access to update customer information
   - Progressive status updates (New → In Progress → Pending → Submitted → Completed)
   - Detailed note-taking system for every interaction

2. **Lead Status Workflow**
   - **New**: Fresh lead from TikTok, not yet contacted
   - **In Progress**: Actively working, gathering documents
   - **Pending**: Waiting for customer action or documents
   - **Submitted**: Application submitted to bank/manager
   - **Completed**: Successfully closed deal ✅
   - **Rejected**: Application declined or customer cancelled ❌

3. **Follow-up Notes System**
   - Add timestamped notes at every step (e.g., "Sent payslip to Maybank")
   - Track all customer interactions
   - Complete history visible to employee and managers
   - Required for proper lead management
   - Examples:
     * "Customer requested quote for Honda Civic"
     * "Sent document checklist via WhatsApp"
     * "Received IC copy, pending payslip"
     * "All documents received, submitting to bank"

4. **Loan Qualification**
   - Track leads from TikTok sources
   - Automated DSR (Debt Service Ratio) calculation
   - Risk assessment (Low/Medium/High)
   - Follow-up logging system
   - Lead assignment to employees

2. **Loan Qualification**
   - Financial profile tracking
   - Monthly income calculation
   - Estimated installment calculation
   - DSR percentage with risk flagging
   - Status tracking (New → Pending → Submitted → Approved/Rejected)

3. **User Management**
   - Role-based access control
   - User activity tracking
   - Account status management

4. **Dashboard**
   - Real-time statistics
   - Recent activity feed
   - Quick actions
   - Performance metrics

## Database Structure

### Tables

1. **users** - System users and authentication
2. **leads** - Customer leads and loan applications
3. **follow_up_logs** - Interaction history for each lead
4. **activity_log** - System-wide audit trail

## Security Features

- Password hashing using bcrypt
- Session management
- Role-based access control
- SQL injection prevention (PDO prepared statements)
- XSS protection (input sanitization)
- CSRF protection ready

## Troubleshooting

### Can't connect to database
- Check MySQL is running
- Verify database credentials in `config/database.php`
- Ensure database `autodrive_pro` exists

### Page shows blank
- Check PHP error logs
- Enable error reporting in `config/database.php`
- Verify PHP version (7.4+)

### Login not working
- Clear browser cache/cookies
- Check session directory is writable
- Verify users table has default users

### DSR calculation not showing
- Check JavaScript console for errors
- Ensure all financial fields have values
- Verify assets/js/main.js is loaded

## Support

For issues or questions:
1. Check the error logs: `php_error.log`
2. Review database logs in phpMyAdmin
3. Check browser console for JavaScript errors

## Next Steps

After installation:
1. ✅ Change all default passwords
2. ✅ Add your company employees
3. ✅ Configure site URL in config
4. ✅ Test lead creation workflow
5. ✅ Review and customize permissions
6. ✅ Set up regular database backups


