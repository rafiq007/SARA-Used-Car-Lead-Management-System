# ✅ Live Server Setup Checklist

## Pre-Deployment
- [ ] Have cPanel login credentials
- [ ] Have database name: `u728571461_autodrive_pro`
- [ ] Have database username: `u728571461_autodrive_usr`
- [ ] Have database password from hosting provider
- [ ] Know your domain URL

---

## Deployment Steps

### 1. Upload Files
- [ ] Upload `autodrive-pro-system.tar.gz` to cPanel File Manager
- [ ] Extract files in `public_html` directory
- [ ] Verify all files extracted successfully
- [ ] Delete the `.tar.gz` file

### 2. Setup Database
- [ ] Open phpMyAdmin
- [ ] Verify database `u728571461_autodrive_pro` exists
- [ ] Import `sql/database.sql` file
- [ ] Check for "Import successful" message
- [ ] Verify 4 tables created (users, leads, follow_up_logs, activity_log)
- [ ] Verify 3 default users exist in users table

### 3. Configure Settings
- [ ] Edit `config/database.php`
- [ ] Update `DB_USER` with actual username
- [ ] Update `DB_PASS` with actual password
- [ ] Update `SITE_URL` with your domain (e.g., https://yourdomain.com/autodrive-pro-system)
- [ ] Save changes

### 4. Set Permissions
- [ ] Set folder permissions to 755
- [ ] Set file permissions to 644
- [ ] Set `config/database.php` to 644 (important!)

### 5. Test Installation
- [ ] Open browser and go to: `https://yourdomain.com/autodrive-pro-system/login.php`
- [ ] Login page loads successfully
- [ ] Login with: `admin` / `admin123`
- [ ] Dashboard loads successfully
- [ ] No error messages appear

---

## Post-Deployment Security

### Immediate Actions (DO FIRST!)
- [ ] Login as `admin`
- [ ] Go to Profile → Change Password
- [ ] Set strong password (8+ characters, mix of letters/numbers/symbols)
- [ ] Logout and login again
- [ ] Repeat for `hr_manager` account
- [ ] Repeat for `john_employee` account

### Security Hardening
- [ ] Verify error display is OFF in production
- [ ] Verify `config/database.php` has 644 permissions
- [ ] Delete or rename `database.config-template.php`
- [ ] Setup regular database backups

---

## Create Your Staff

### Add First Employee
- [ ] Login as admin
- [ ] Go to User Management
- [ ] Click "Add New User"
- [ ] Create employee account
- [ ] Share credentials with employee
- [ ] Employee logs in successfully
- [ ] Employee changes their password

### Test Employee Workflow
- [ ] Employee creates a test lead
- [ ] Employee edits the lead
- [ ] Employee adds follow-up note
- [ ] Employee updates status
- [ ] Verify employee only sees their own leads
- [ ] Admin/HR can see all leads

---

## Functional Testing

### Core Features
- [ ] Create new lead
- [ ] DSR calculates automatically
- [ ] High risk (>30%) shows in red
- [ ] Edit existing lead
- [ ] Delete lead
- [ ] Add follow-up log note
- [ ] View follow-up history
- [ ] Update lead status
- [ ] Dashboard statistics update
- [ ] Recent activity shows correctly

### User Management (Admin/HR)
- [ ] Create new user
- [ ] Edit user details
- [ ] Change user role
- [ ] Deactivate user
- [ ] Delete user
- [ ] View activity log

### Reports
- [ ] View reports page
- [ ] Statistics calculate correctly
- [ ] Status breakdown accurate
- [ ] Risk distribution correct

---

## Going Live

### Final Checks
- [ ] All default passwords changed
- [ ] All test data deleted
- [ ] Error reporting disabled
- [ ] HTTPS/SSL working
- [ ] Mobile view tested
- [ ] All employees trained

### Employee Training
- [ ] Share `EMPLOYEE_GUIDE.md` with staff
- [ ] Demo how to create lead
- [ ] Show how to add notes
- [ ] Explain status flow
- [ ] Practice DSR risk levels

### Backup Plan
- [ ] Database backup taken
- [ ] Files backup taken
- [ ] Backup stored safely offsite

---

## Maintenance Schedule

### Daily
- [ ] Check dashboard for new leads
- [ ] Monitor high-risk applications
- [ ] Review employee activity

### Weekly
- [ ] Review all pending/submitted leads
- [ ] Check activity logs
- [ ] Clear old notifications

### Monthly
- [ ] **Backup database** (CRITICAL!)
- [ ] Review user access
- [ ] Check system performance
- [ ] Clean up rejected leads

### Quarterly
- [ ] Force password changes
- [ ] Review and update documentation
- [ ] Train new employees
- [ ] System health check

---

## Common Issues & Solutions

### Issue: Can't login
**Check:**
- [ ] Database imported correctly
- [ ] Using correct username/password
- [ ] Browser cache cleared
- [ ] Cookies enabled

### Issue: Blank page
**Check:**
- [ ] Database connection settings
- [ ] File permissions
- [ ] PHP version (need 7.4+)
- [ ] Error logs in cPanel

### Issue: CSS not loading
**Check:**
- [ ] `SITE_URL` correct in config
- [ ] File permissions on assets folder
- [ ] Browser cache cleared

---

## Support Contacts

**Hosting Provider**: ________________
**Database Support**: ________________
**System Administrator**: ________________
**Training Contact**: ________________

---

## Notes

Date Deployed: ________________

Live URL: ________________

Database Credentials Stored: ________________

First Backup Taken: ________________

Staff Trained: ________________

---

**Status: [ ] Development  [ ] Testing  [ ] Production Live**

Last Updated: ________________
Updated By: ________________
