# AutoDrive Pro Malaysia - Quick Start Guide

## 🚀 Getting Started in 5 Minutes

### Step 1: Install XAMPP (if you don't have it)
1. Download XAMPP from https://www.apachefriends.org/
2. Install and start Apache and MySQL

### Step 2: Setup Database
1. Open phpMyAdmin: http://localhost/phpmyadmin
2. Click "New" to create database
3. Name: `autodrive_pro`
4. Click "Import" tab
5. Choose file: `sql/database.sql`
6. Click "Go"

### Step 3: Configure Database
1. Open: `config/database.php`
2. Update if needed:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   ```

### Step 4: Deploy Application
1. Copy `autodrive-pro-system` folder to:
   - Windows: `C:\xampp\htdocs\`
   - Mac: `/Applications/XAMPP/htdocs/`
   - Linux: `/opt/lampp/htdocs/`

### Step 5: Login
1. Open browser: http://localhost/autodrive-pro-system/login.php
2. Login with:
   - Username: `admin`
   - Password: `admin123`

## ✅ What's Next?

### First Time Setup
1. **Change Password**: Go to Profile → Change Password
2. **Add Employees**: Go to User Management → Add New User
3. **Test Lead Creation**: Go to Lead Pipeline → Add New Lead

### Daily Workflow (For Employees)
1. **Employee creates lead** from TikTok inquiry → Status: "New"
2. **Contact customer** and gather documents → Status: "In Progress"
3. **Customer action needed** (waiting for docs) → Status: "Pending"
4. **Submit application** to bank/manager → Status: "Submitted"
5. **Deal closes successfully** → Status: "Completed" ✅
6. **Throughout process**: Add follow-up notes at each step

### Lead Status Flow
```
New → In Progress → Pending → Submitted → Completed
                                        ↘ Rejected
```

**Employee adds notes at each step:**
- "Customer interested in Honda Civic 2020"
- "Sent document checklist to customer"
- "Received IC and payslip, pending bank statement"
- "All documents received, submitting to Maybank"
- "Loan approved! Deal completed"

## 🎯 Key Features

### Automated DSR Calculation
- Input: Salary + Allowances + Bonus + Car Price
- Output: DSR % + Risk Level (High/Medium/Low)
- **High Risk** = DSR > 30% (flagged in red)

### Follow-up Logs
- Click "Log" button on any lead
- Add timestamped notes
- Track all interactions

### Role-Based Access
- **Admin**: Full access
- **HR**: Manage users + view all leads
- **Employee**: Only see assigned leads

## 📊 Understanding the Dashboard

### Statistics Cards
- **Total Leads**: All leads in system
- **Approved Loans**: Successfully approved
- **Pending Review**: Awaiting bank decision
- **High Risk DSR**: Above 30% threshold

### Lead Pipeline Stages
1. **New**: Just created, fresh inquiry from TikTok
2. **In Progress**: Actively working, gathering documents
3. **Pending**: Waiting for customer action or documents
4. **Submitted**: Application sent to bank/manager
5. **Completed**: ✅ Deal successfully closed
6. **Rejected**: ❌ Application declined or cancelled

## 🔐 Security Tips

1. ✅ Change all default passwords
2. ✅ Use strong passwords (8+ characters)
3. ✅ Don't share login credentials
4. ✅ Logout when done
5. ✅ Regular database backups

## 🆘 Common Issues

### Can't login?
- Check username/password
- Clear browser cache
- Verify database is running

### DSR not calculating?
- Ensure all salary fields filled
- Check listing price entered
- Refresh the page

### Leads not showing?
- Employees only see assigned leads
- Admin/HR see all leads
- Check filter settings

## 📱 Mobile Access

The system is mobile-responsive!
- Access from phone/tablet
- Same URL: http://localhost/autodrive-pro-system/
- All features work on mobile

## 🎓 Training Resources

### For Employees
1. How to add a new lead
2. How to update lead status
3. How to add follow-up logs

### For HR/Admin
1. How to manage users
2. How to view reports
3. How to export data

## 📞 Support

If you encounter issues:
1. Check README.md for detailed docs
2. Review error messages
3. Check browser console (F12)
4. Verify database connection

---

**Happy Selling! 🚗💼**
