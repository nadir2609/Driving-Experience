# AlwaysData Deployment Guide

## 🌐 Deploy to AlwaysData (Free Hosting)

AlwaysData provides free PHP/MySQL hosting perfect for student projects.

---

## Step 1: Create AlwaysData Account

1. Go to https://www.alwaysdata.com/
2. Click **"Sign Up"** or **"Free Trial"**
3. Create your account (100MB free, no credit card required)
4. Choose a subdomain: `yourname.alwaysdata.net`
5. Verify your email

---

## Step 2: Create MySQL Database

1. Log into AlwaysData admin panel
2. Go to **"Databases" → "MySQL"**
3. Click **"Add a database"**
4. Fill in:
   - **Name**: `yourname_driving` (or any name you prefer)
   - **User**: Will be created automatically
   - **Password**: Set a strong password (save it!)
5. Click **"Submit"**

**Note:** AlwaysData will show you:
- Database name: `yourname_driving`
- Username: Usually same as your account name
- Password: The one you just set
- Host: `mysql-yourname.alwaysdata.net`

---

## Step 3: Import Database

### Option A: Via phpMyAdmin (Easier)
1. In AlwaysData admin, go to **"Databases" → "MySQL"**
2. Click on your database name
3. Click **"phpMyAdmin"** button
4. Login with your credentials
5. Click **"Import"** tab
6. Choose `database/database.sql` file from your project
7. Click **"Go"

### Option B: Via SSH (Advanced)
```bash
# Upload file via SFTP first, then:
mysql -h mysql-yourname.alwaysdata.net -u yourname -p yourname_driving < database/database.sql
```

---

## Step 4: Upload Project Files

### Option A: Via Web Interface (File Manager)
1. In AlwaysData admin, go to **"Remote Access" → "WebDAV"**
2. Or use **SFTP** with FileZilla:
   - Host: `ssh-yourname.alwaysdata.net`
   - Port: 22
   - Protocol: SFTP
   - Username: Your AlwaysData account name
   - Password: Your AlwaysData password

### Option B: Via FTP Client (Recommended)
1. Download FileZilla: https://filezilla-project.org/
2. Connect with:
   - **Host**: `ftp-yourname.alwaysdata.net`
   - **Username**: Your AlwaysData username
   - **Password**: Your AlwaysData password
   - **Port**: 21
3. Navigate to `/www/` folder
4. Upload all project files to `/www/`

**Files to upload:**
- All `.php` files (root level)
- `assets/` folder (css, js, images)
- `includes/` folder (config, functions, classes)
- `pages/` folder (all page files)
- `.htaccess` file
- Database files from `database/` folder (for import)
- `includes/` folder
- `pages/` folder
- `.htaccess` file
- Do NOT upload `config.php` yet

---

## Step 5: Configure Database Connection

### Method 1: Edit via Web Interface
1. Go to **"Remote Access" → "SSH"**
2. Or use AlwaysData's file editor
3. Navigate to `includes/config.php`
4. Update with your AlwaysData credentials:

```php
<?php
// AlwaysData Database Configuration
define('DB_HOST', 'mysql-yourname.alwaysdata.net');  // Your MySQL host
define('DB_USER', 'yourname');                        // Your database username
define('DB_PASS', 'your_database_password');          // Password you set
define('DB_NAME', 'yourname_driving');                // Your database name

function getDBConnection() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $conn->set_charset("utf8mb4");
    return $conn;
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('Europe/Paris');  // AlwaysData is in France
?>
```

### Method 2: Upload Configured File
1. Edit `includes/config.php` on your local computer
2. Update with AlwaysData credentials (see above)
3. Upload via FTP to overwrite the file

---

## Step 6: Set Permissions (Usually automatic on AlwaysData)

AlwaysData typically sets correct permissions automatically, but if needed:
- Directories: 755
- PHP files: 644

---

## Step 7: Test Your Application

1. Open browser
2. Go to: `https://yourname.alwaysdata.net`
3. Your application should load!

**Test checklist:**
- ✅ Homepage loads without errors
- ✅ Navigate to "Add Experience"
- ✅ Submit a test driving experience
- ✅ Check "Summary" page shows your entry
- ✅ View "Statistics" page (charts should load)

---

## 🔧 AlwaysData Specific Settings

### PHP Version
AlwaysData supports multiple PHP versions. Ensure you're using PHP 7.4 or higher:
1. Go to **"Web" → "Sites"**
2. Click on your site
3. Check **"PHP version"** is 7.4+ or 8.x

### HTTPS
AlwaysData provides free SSL certificate:
- Your site is automatically accessible via `https://yourname.alwaysdata.net`
- Certificate is automatically renewed

### File Manager
Access files directly in browser:
1. Go to **"Remote Access" → "Web interface"**
2. Navigate to `/www/`
3. Edit files directly in browser

---

## 📋 Quick Reference

### Your AlwaysData URLs
- **Website**: `https://yourname.alwaysdata.net`
- **Admin Panel**: `https://admin.alwaysdata.com`
- **phpMyAdmin**: Via admin panel → Databases → MySQL → phpMyAdmin
- **FTP**: `ftp-yourname.alwaysdata.net`
- **SSH/SFTP**: `ssh-yourname.alwaysdata.net`

### Your Database Info
```
Host: mysql-yourname.alwaysdata.net
User: yourname (or your account name)
Pass: [password you set]
Name: yourname_driving (your database name)
```

---

## 🐛 Troubleshooting

### Problem: 500 Internal Server Error
**Check:**
1. .htaccess syntax (may need to adjust for AlwaysData)
2. PHP version (should be 7.4+)
3. File permissions

**Solution:** Check error logs in AlwaysData admin:
- Go to **"Logs" → "HTTP"**
- View recent errors

### Problem: Database Connection Error
**Check:**
1. Database credentials in `config.php` are correct
2. Database exists (check in admin panel)
3. User has privileges

**Solution:**
```php
// Add at top of index.php temporarily to see error:
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

### Problem: Blank Page
**Check:**
1. PHP errors (enable display_errors)
2. Check logs in AlwaysData admin

### Problem: CSS/JS Not Loading
**Check:**
1. File paths are case-sensitive on Linux
2. Files were uploaded to correct location
3. Browser cache (Ctrl+Shift+R to refresh)

---

## 🔒 Security Notes

### After Deployment:
1. ✅ Disable error display in production
2. ✅ Use strong database password
3. ✅ Keep AlwaysData account password secure
4. ✅ Don't share database credentials
5. ✅ Regular backups via phpMyAdmin export

### Backup Database:
1. Go to phpMyAdmin
2. Select your database
3. Click **"Export"**
4. Choose **"Quick"** method
5. Click **"Go"**
6. Save the .sql file

---

## 📊 Free Account Limits

AlwaysData Free Account includes:
- ✅ 100 MB disk space
- ✅ 1 MySQL database
- ✅ PHP 7.4, 8.x support
- ✅ Free SSL certificate
- ✅ Unlimited bandwidth (fair use)
- ✅ SSH/SFTP access
- ✅ phpMyAdmin
- ⚠️ Subdomain only (yourname.alwaysdata.net)
- ⚠️ Limited resources (sufficient for student projects)

**Upgrade:** If needed, AlwaysData offers paid plans starting at €10/month for custom domains and more resources.

---

## ✅ Deployment Checklist

Before submitting to your teacher:
- [ ] Database imported successfully
- [ ] All files uploaded to `/www/`
- [ ] `config.php` updated with AlwaysData credentials
- [ ] Tested adding a driving experience
- [ ] Verified summary page shows data
- [ ] Checked statistics page displays charts
- [ ] Tested on mobile device/browser
- [ ] Removed sample data (if any)
- [ ] Application works at `https://yourname.alwaysdata.net`

---

## 🎓 Submit to Teacher

Share with your teacher:
- **URL**: `https://yourname.alwaysdata.net`
- **Admin credentials** (if needed):
  - AlwaysData account: yourname
  - Password: [your password]
- **Database info** (if requested):
  - Database: yourname_driving
  - phpMyAdmin access via AlwaysData admin

---

**Deployment Time**: ~15-20 minutes  
**Cost**: FREE (100MB account)  
**Support**: https://help.alwaysdata.com/

**Your application is now live and accessible worldwide!** 🚀
