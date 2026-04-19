# 🚀 Live Server Deployment Guide - AutoDrive Pro Malaysia

## 📋 Prerequisites

You'll need:
- ✅ cPanel access to your hosting account
- ✅ phpMyAdmin access
- ✅ FTP/File Manager access
- ✅ Database name: `u728571461_autodrive_pro`
- ✅ Database username and password from your hosting provider

---

## 🔧 Step-by-Step Deployment

### Step 1: Upload Files to Server

#### Option A: Using cPanel File Manager
1. Login to your **cPanel**
2. Open **File Manager**
3. Navigate to `public_html` directory (or your domain's root folder)
4. Click **Upload**
5. Upload the `autodrive-pro-system.tar.gz` file
6. After upload completes, right-click the file → **Extract**
7. Delete the `.tar.gz` file after extraction
8. You should now have an `autodrive-pro-system` folder

#### Option B: Using FTP (FileZilla)
1. Open **FileZilla** (or your FTP client)
2. Connect to your server using FTP credentials
3. Navigate to `public_html` directory
4. Extract `autodrive-pro-system.tar.gz` on your computer first
5. Upload the entire `autodrive-pro-system` folder
6. Wait for all files to upload

---

### Step 2: Create Database in phpMyAdmin

1. Login to **cPanel**
2. Open **phpMyAdmin**
3. Click **"New"** in the left sidebar
4. Database name: **u728571461_autodrive_pro** (should already exist)
   - If it doesn't exist, create it using cPanel MySQL Databases tool
5. Click **"Create"**
6. Select the database **u728571461_autodrive_pro**
7. Click **"Import"** tab
8. Click **"Choose File"**
9. Select: `autodrive-pro-system/sql/database.sql`
10. Scroll down and click **"Go"**
11. Wait for success message

✅ **Database created with:**
- 4 tables (users, leads, follow_up_logs, activity_log)
- 3 default users (admin, hr_manager, john_employee)

---

### Step 3: Configure Database Connection

1. In **File Manager**, navigate to:
   ```
   public_html/autodrive-pro-system/config/database.php
   ```

2. Right-click → **Edit**

3. Update these lines with your actual credentials:

```php
// Database Configuration
define('DB_HOST', 'localhost');  // Usually 'localhost'
define('DB_USER', 'u728571461_autodrive_usr');  // Your database username
define('DB_PASS', 'YOUR_DATABASE_PASSWORD');     // Your database password
define('DB_NAME', 'u728571461_autodrive_pro');   // Database name

// Application Settings
define('SITE_URL', 'https://yourdomain.com/autodrive-pro-system');  // Your actual URL
```

4. **Save Changes**

**Important:** Get your database password from:
- cPanel → MySQL Databases → Current Users section
- Or create a new database user and assign it to the database

---

### Step 4: Set File Permissions

In **File Manager**, set these permissions:

1. Select the `autodrive-pro-system` folder
2. Right-click → **Permissions**
3. Set to: **755** (or rwxr-xr-x)

For security, set these specific permissions:
```
autodrive-pro-system/          → 755
autodrive-pro-system/config/   → 755
autodrive-pro-system/includes/ → 755
config/database.php            → 644 (important for security)
```

---

### Step 5: Test the Installation

1. Open your browser
2. Navigate to: `https://yourdomain.com/autodrive-pro-system/login.php`
   
   Replace `yourdomain.com` with your actual domain

3. You should see the login page

4. Try logging in with:
   - **Username**: `admin`
   - **Password**: `admin123`

5. If successful, you'll see the dashboard! 🎉

---

### Step 6: Secure Your Installation

#### Change Default Passwords (CRITICAL!)
1. Login as `admin`
2. Go to **Profile** → **Change Password**
3. Set a strong password (at least 8 characters, mix of letters/numbers/symbols)
4. Logout and login again with new password

5. Repeat for other default users:
   - Login as `hr_manager` / `admin123`
   - Login as `john_employee` / `admin123`
   - Change their passwords too

#### Secure config/database.php
1. In File Manager, right-click `config/database.php`
2. **Permissions** → Set to **644**
3. This prevents direct web access to your database credentials

#### Disable Error Display (For Production)
Edit `config/database.php`:
```php
// Change this:
error_reporting(E_ALL);
ini_set('display_errors', 1);

// To this:
error_reporting(0);
ini_set('display_errors', 0);
```

---

### Step 7: Create Your First Employee

1. Login as **admin**
2. Go to **User Management**
3. Click **Add New User**
4. Fill in:
   - Username: Employee's desired username
   - Password: Temporary password (they'll change it)
   - Full Name: Employee's full name
   - Email: Employee's email
   - Role: **Employee**
   - Phone: Employee's phone number
5. Click **Create User**
6. Share credentials with the employee

---

## 🔍 Troubleshooting

### Issue: Can't connect to database
**Error**: "Connection failed: Access denied"

**Solution:**
1. Check database credentials in `config/database.php`
2. Verify database user has permissions:
   - cPanel → MySQL Databases
   - Add user to database with ALL PRIVILEGES
3. Check database name is exactly: `u728571461_autodrive_pro`

---

### Issue: Blank white page
**Possible causes:**
1. PHP syntax error
2. Database connection failed
3. Missing files

**Solution:**
1. Enable error display temporarily in `config/database.php`:
   ```php
   error_reporting(E_ALL);
   ini_set('display_errors', 1);
   ```
2. Check PHP error logs in cPanel → Error Log
3. Verify all files uploaded correctly

---

### Issue: 404 Not Found
**Solution:**
1. Check URL is correct: `https://yourdomain.com/autodrive-pro-system/login.php`
2. Verify folder name is exactly `autodrive-pro-system`
3. Check .htaccess file exists and is readable

---

### Issue: CSS/JavaScript not loading
**Symptoms:** Page looks broken, no styling

**Solution:**
1. Update `SITE_URL` in `config/database.php` to your actual URL
2. Clear browser cache (Ctrl+F5)
3. Check file permissions on `assets` folder (should be 755)

---

### Issue: Can't login with default credentials
**Solution:**
1. Check database was imported successfully
2. In phpMyAdmin, browse `users` table
3. Verify 3 default users exist
4. If not, re-import `sql/database.sql`

---

## 📊 Database Information

### Credentials
- **Database Name**: `u728571461_autodrive_pro`
- **Database User**: `u728571461_autodrive_usr` (update with actual)
- **Database Password**: (from your hosting provider)
- **Database Host**: `localhost`

### Tables Created
1. **users** - System users and authentication
2. **leads** - Customer leads and applications
3. **follow_up_logs** - Interaction notes and history
4. **activity_log** - System audit trail

### Default Users
| Username | Password | Role | Purpose |
|----------|----------|------|---------|
| admin | admin123 | Administrator | Full system access |
| hr_manager | admin123 | HR Manager | User & lead management |
| john_employee | admin123 | Employee | Sample employee |

**⚠️ CHANGE ALL PASSWORDS IMMEDIATELY AFTER FIRST LOGIN!**

---

## 🔒 Security Checklist

After deployment, ensure:
- [ ] All default passwords changed
- [ ] Error display disabled (`display_errors = 0`)
- [ ] `config/database.php` has 644 permissions
- [ ] SSL certificate installed (https://)
- [ ] Regular database backups scheduled
- [ ] Only necessary users have database access

---

## 📱 Accessing the System

### URLs
- **Login Page**: `https://yourdomain.com/autodrive-pro-system/login.php`
- **Dashboard**: `https://yourdomain.com/autodrive-pro-system/index.php`
- **Direct Access**: Set up redirect from root domain if needed

### Mobile Access
The system is fully responsive and works on:
- ✅ Smartphones (iOS, Android)
- ✅ Tablets
- ✅ Desktop computers

---

## 🔄 Regular Maintenance

### Daily
- Monitor employee activity
- Check new leads

### Weekly
- Review system logs in Activity Log
- Check high-risk leads

### Monthly
- **Backup database** via phpMyAdmin:
  1. Select database `u728571461_autodrive_pro`
  2. Click **Export**
  3. Click **Go**
  4. Save the SQL file safely

### Quarterly
- Update user passwords
- Review user access levels
- Clean up old rejected leads

---

## 📞 Support

If you encounter issues:
1. Check this deployment guide
2. Review error logs in cPanel
3. Check database connection settings
4. Verify file permissions

---

## ✅ Deployment Checklist

Use this checklist to ensure proper deployment:

- [ ] Files uploaded to server
- [ ] Database `u728571461_autodrive_pro` created
- [ ] SQL file imported successfully
- [ ] `config/database.php` updated with correct credentials
- [ ] File permissions set correctly (755 for folders, 644 for files)
- [ ] Login page accessible via browser
- [ ] Successful login with admin account
- [ ] All 3 default users can login
- [ ] Default passwords changed
- [ ] Error display disabled
- [ ] `SITE_URL` updated to actual domain
- [ ] First employee user created
- [ ] Test lead creation works
- [ ] Follow-up log system tested
- [ ] Database backup scheduled

---

**🎉 Congratulations! Your AutoDrive Pro system is now live!**

**Next Steps:**
1. Train your employees using the `EMPLOYEE_GUIDE.md`
2. Create employee accounts
3. Start adding leads from TikTok
4. Monitor the dashboard daily

**Good luck with your car sales! 🚗💼**
