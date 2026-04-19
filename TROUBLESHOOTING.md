# 🔧 Troubleshooting: Column 'loan_status' Error

## Error Message
```
#1072 - Key column 'loan_status' doesn't exist in table
```

## What Happened?
The database schema was updated to use `lead_status` instead of `loan_status` to better reflect the employee workflow. If you imported an older version of the database, you need to update it.

---

## ✅ Solution 1: Fresh Import (RECOMMENDED)

If you haven't added any real data yet:

1. **Delete the existing database:**
   - Open phpMyAdmin
   - Select database `u728571461_autodrive_pro`
   - Click "Drop" tab
   - Confirm deletion

2. **Re-import the correct schema:**
   - Click "New" to create database again
   - Name: `u728571461_autodrive_pro`
   - Select the database
   - Click "Import"
   - Choose: `sql/database.sql` (from the latest package)
   - Click "Go"

3. **Verify:**
   - Browse the `leads` table
   - Check that column is named `lead_status` (NOT loan_status)

✅ **This is the cleanest solution!**

---

## 🔄 Solution 2: Update Existing Database

If you already have data and can't delete:

1. **Open phpMyAdmin**
2. **Select database** `u728571461_autodrive_pro`
3. **Click SQL tab**
4. **Copy and paste this SQL:**

```sql
-- Drop old index
ALTER TABLE leads DROP INDEX IF EXISTS idx_status;

-- Rename column
ALTER TABLE leads 
CHANGE COLUMN loan_status lead_status 
ENUM('New', 'In Progress', 'Pending', 'Submitted', 'Completed', 'Rejected') 
DEFAULT 'New';

-- Add new index
ALTER TABLE leads ADD INDEX idx_status (lead_status);
```

5. **Click "Go"**
6. **Verify the change:**
   - Click on `leads` table
   - Click "Structure" tab
   - Confirm column is now named `lead_status`

---

## Alternative: Use Update Script

We've provided a ready-made update script:

1. In phpMyAdmin, select your database
2. Click "Import"
3. Choose file: `sql/update_column_name.sql`
4. Click "Go"

---

## Verification Steps

After applying the fix, verify everything works:

1. **Check Table Structure:**
   ```sql
   DESCRIBE leads;
   ```
   You should see `lead_status` in the output

2. **Test the Application:**
   - Login to the system
   - Go to "Lead Pipeline"
   - Try to create a new lead
   - Should work without errors

3. **Check Existing Data:**
   ```sql
   SELECT id, full_name, lead_status FROM leads LIMIT 10;
   ```
   Your data should still be there with status values

---

## Prevention

To avoid this in the future:

- ✅ Always use the latest `database.sql` file
- ✅ Delete old databases before fresh import
- ✅ Check database structure after import
- ✅ Test creating a lead before going live

---

## Still Having Issues?

### Error: Column still not found
**Check:**
1. Did you select the correct database?
2. Did the ALTER statement execute successfully?
3. Refresh phpMyAdmin (F5)

### Error: Unknown column in field list
**This means:**
- The column was renamed successfully
- But PHP code is still looking for old name
- Make sure you have the latest PHP files uploaded

### Error: Duplicate column name
**This means:**
- Column already exists with new name
- You can skip the rename step
- Just add the index if missing

---

## Quick Fix Command

If you just want to get it working quickly, run this in phpMyAdmin SQL tab:

```sql
USE u728571461_autodrive_pro;

-- This will work even if column is already renamed
ALTER TABLE leads MODIFY COLUMN lead_status 
ENUM('New', 'In Progress', 'Pending', 'Submitted', 'Completed', 'Rejected') 
DEFAULT 'New';

-- Make sure index exists
ALTER TABLE leads DROP INDEX IF EXISTS idx_status;
ALTER TABLE leads ADD INDEX idx_status (lead_status);
```

---

## Need Help?

1. Check the column name in phpMyAdmin:
   - Browse to leads table
   - Look at Structure tab
   - Take screenshot

2. Check error logs:
   - cPanel → Error Log
   - Note exact error message

3. Verify file versions:
   - Make sure using latest database.sql
   - Make sure using latest PHP files

---

**After Fix:** Clear browser cache and try again!
