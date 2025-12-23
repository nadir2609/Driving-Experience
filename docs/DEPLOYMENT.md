# Deployment Guide - Remote Web Server

## 🌐 Deployment Options

This guide covers deployment to:
1. Shared Hosting (cPanel, Plesk)
2. VPS/Cloud Server (AWS, DigitalOcean, Linode)
3. Free Hosting (InfinityFree, 000webhost)

---

## 📦 Pre-Deployment Checklist

### Files to Upload
- ✅ All `.php` files
- ✅ `assets/` folder (css, js, images)
- ✅ `includes/` folder
- ✅ `pages/` folder
- ✅ `.htaccess` file
- ✅ `database/` folder (SQL files)

### Files NOT to Upload
- ❌ `README.md` (optional, for documentation)
- ❌ `LICENSE` (optional)
- ❌ `docs/` folder (optional, documentation only)
- ❌ `.git/` folder (if using version control)
- ❌ `.gitignore` (optional)
- ❌ Local configuration backups

### Security Preparations
1. Update `includes/config.php` with production database credentials
2. Set `display_errors = Off` in PHP settings
3. Enable error logging instead
4. Review `.htaccess` security rules
5. Use strong database passwords

---

## 🏠 Shared Hosting Deployment (cPanel)

### Step 1: Upload Files

#### Via File Manager
1. Log into cPanel
2. Go to File Manager
3. Navigate to `public_html` (or `www`, `htdocs`)
4. Create folder: `driving-log` (optional, for subdirectory)
5. Upload all files:
   - Click "Upload"
   - Select all project files
   - Wait for upload to complete

#### Via FTP Client (FileZilla)
1. Download FileZilla: https://filezilla-project.org/
2. Connect with credentials:
   - Host: `ftp.yourdomain.com` or server IP
   - Username: Your cPanel username
   - Password: Your cPanel password
   - Port: 21 (or 22 for SFTP)
3. Navigate to `public_html`
4. Drag and drop all project files

### Step 2: Create Database

1. In cPanel, go to **MySQL Databases**
2. Create new database:
   - Name: `username_drivinglog`
   - Click "Create Database"
3. Create database user:
   - Username: `username_driver`
   - Password: [Generate strong password]
   - Click "Create User"
4. Add user to database:
   - Select user and database
   - Grant ALL PRIVILEGES
   - Click "Add"

### Step 3: Import Database

1. Go to **phpMyAdmin** in cPanel
2. Select your database (`username_drivinglog`)
3. Click **Import** tab
4. Choose file: `database.sql`
5. Click **Go**
6. Wait for success message

### Step 4: Configure Connection

1. Edit `includes/config.php` via File Manager or FTP
2. Update credentials:
```php
define('DB_HOST', 'localhost');  // Usually localhost
define('DB_USER', 'username_driver');  // Your database user
define('DB_PASS', 'your_strong_password');
define('DB_NAME', 'username_drivinglog');  // Your database name
```
3. Save the file

### Step 5: Test Installation

1. Open browser
2. Go to: `https://yourdomain.com/driving-log/`
3. Check homepage loads
4. Navigate to "Add Experience"
5. Submit a test entry
6. Verify in "Summary" page

---

## ☁️ VPS/Cloud Server Deployment

### Prerequisites
- Ubuntu 20.04+ or similar Linux distribution
- SSH access to server
- Root or sudo privileges

### Step 1: Install LAMP Stack

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install Apache
sudo apt install apache2 -y

# Install MySQL
sudo apt install mysql-server -y

# Install PHP and extensions
sudo apt install php libapache2-mod-php php-mysql php-cli php-curl php-json -y

# Enable Apache modules
sudo a2enmod rewrite
sudo systemctl restart apache2
```

### Step 2: Secure MySQL

```bash
sudo mysql_secure_installation

# Answer prompts:
# - Set root password: YES (choose strong password)
# - Remove anonymous users: YES
# - Disallow root login remotely: YES
# - Remove test database: YES
# - Reload privilege tables: YES
```

### Step 3: Create Database

```bash
# Login to MySQL
sudo mysql -u root -p

# In MySQL prompt:
CREATE DATABASE driving_experience CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'drivingapp'@'localhost' IDENTIFIED BY 'strong_password_here';
GRANT ALL PRIVILEGES ON driving_experience.* TO 'drivingapp'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### Step 4: Upload Files

#### Via SCP (from local machine)
```bash
# From your local machine
scp -r "C:\Users\nadir\Desktop\Backend Project\*" user@your_server_ip:/var/www/html/driving-log/
```

#### Via SFTP (FileZilla)
1. Protocol: SFTP
2. Host: your_server_ip
3. Username: your_ssh_username
4. Password: your_ssh_password
5. Port: 22
6. Upload to: `/var/www/html/driving-log/`

#### Via Git (recommended)
```bash
# On server
cd /var/www/html/
git clone your_repository_url driving-log
cd driving-log
```

### Step 5: Import Database

```bash
# On server
cd /var/www/html/driving-log/
mysql -u drivingapp -p driving_experience < database.sql
```

### Step 6: Configure Application

```bash
# Edit config file
nano includes/config.php

# Update:
# define('DB_HOST', 'localhost');
# define('DB_USER', 'drivingapp');
# define('DB_PASS', 'strong_password_here');
# define('DB_NAME', 'driving_experience');

# Save: Ctrl+O, Enter, Ctrl+X
```

### Step 7: Set Permissions

```bash
# Set ownership
sudo chown -R www-data:www-data /var/www/html/driving-log/

# Set directory permissions
sudo find /var/www/html/driving-log/ -type d -exec chmod 755 {} \;

# Set file permissions
sudo find /var/www/html/driving-log/ -type f -exec chmod 644 {} \;
```

### Step 8: Configure Apache Virtual Host

```bash
# Create virtual host file
sudo nano /etc/apache2/sites-available/driving-log.conf

# Add configuration:
```

```apache
<VirtualHost *:80>
    ServerName yourdomain.com
    ServerAlias www.yourdomain.com
    DocumentRoot /var/www/html/driving-log
    
    <Directory /var/www/html/driving-log>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/driving-log-error.log
    CustomLog ${APACHE_LOG_DIR}/driving-log-access.log combined
</VirtualHost>
```

```bash
# Enable site
sudo a2ensite driving-log.conf

# Disable default site (optional)
sudo a2dissite 000-default.conf

# Test configuration
sudo apache2ctl configtest

# Restart Apache
sudo systemctl restart apache2
```

### Step 9: Enable HTTPS (Let's Encrypt)

```bash
# Install Certbot
sudo apt install certbot python3-certbot-apache -y

# Obtain certificate
sudo certbot --apache -d yourdomain.com -d www.yourdomain.com

# Follow prompts:
# - Enter email address
# - Agree to terms
# - Choose redirect (option 2)

# Test auto-renewal
sudo certbot renew --dry-run
```

### Step 10: Configure Firewall

```bash
# Allow HTTP and HTTPS
sudo ufw allow 'Apache Full'

# Enable firewall
sudo ufw enable

# Check status
sudo ufw status
```

---

## 🆓 Free Hosting Deployment

### Recommended Free Hosts
1. **InfinityFree** - https://infinityfree.net/
   - PHP 7.4+, MySQL
   - No ads
   - Free subdomain or custom domain
   
2. **000webhost** - https://www.000webhost.com/
   - PHP 7.4, MySQL
   - 300 MB storage
   - Free SSL

### General Steps (Similar to cPanel)
1. Sign up for account
2. Create website/application
3. Access File Manager or FTP
4. Upload files
5. Create MySQL database via control panel
6. Import database.sql
7. Configure config.php
8. Test application

### Limitations
- ⚠️ Limited bandwidth
- ⚠️ May have ads
- ⚠️ Limited PHP execution time
- ⚠️ Not suitable for production
- ✅ Good for testing/learning

---

## 🔒 Security Best Practices

### Production Configuration

#### 1. PHP Settings
```php
// In php.ini or .htaccess
display_errors = Off
log_errors = On
error_log = /path/to/error.log
```

#### 2. Database Security
- Use strong passwords (16+ characters)
- Limit user privileges (grant only necessary permissions)
- Use localhost for DB_HOST when possible
- Never expose database credentials in code comments

#### 3. File Permissions
```bash
# Directories: 755
# PHP files: 644
# config.php: 640 (more restrictive)
chmod 640 includes/config.php
```

#### 4. .htaccess Security
Already included in project, but verify:
- Directory browsing disabled
- Config files protected
- Security headers set

#### 5. Regular Maintenance
- Keep PHP updated
- Update MySQL regularly
- Monitor error logs
- Backup database weekly
- Review access logs

---

## 📊 Performance Optimization

### Enable Caching
```apache
# In .htaccess (already included)
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType text/css "access plus 1 month"
    ExpiresByType text/javascript "access plus 1 month"
</IfModule>
```

### Enable Compression
```apache
# In .htaccess (already included)
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/html text/css text/javascript
</IfModule>
```

### Database Optimization
```sql
-- Run periodically
OPTIMIZE TABLE driving_experiences;
OPTIMIZE TABLE weather_conditions;
OPTIMIZE TABLE traffic_density;
OPTIMIZE TABLE road_types;
OPTIMIZE TABLE road_surface_quality;
```

---

## 🐛 Troubleshooting

### Problem: White Screen (WSOD)

**Solution 1: Enable Error Display**
```php
// Add to top of index.php temporarily
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

**Solution 2: Check Error Logs**
```bash
# On Linux
tail -f /var/log/apache2/error.log

# On cPanel
Check Error Log in cPanel
```

### Problem: Database Connection Error

**Check:**
1. Database exists: `SHOW DATABASES;`
2. User has privileges: `SHOW GRANTS FOR 'user'@'localhost';`
3. Credentials in config.php are correct
4. MySQL service is running: `sudo systemctl status mysql`

### Problem: 500 Internal Server Error

**Common Causes:**
1. Syntax error in .htaccess
2. Incorrect file permissions
3. PHP version incompatibility
4. Missing PHP extensions

**Solution:**
```bash
# Check Apache error log
sudo tail -f /var/log/apache2/error.log

# Test .htaccess
mv .htaccess .htaccess.bak
# If site works, fix .htaccess syntax
```

### Problem: Styles Not Loading

**Check:**
1. File paths are correct (case-sensitive on Linux)
2. Files were uploaded
3. Browser cache (Ctrl+Shift+R to hard refresh)
4. .htaccess rules not blocking CSS

---

## 📋 Post-Deployment Checklist

- [ ] All pages load without errors
- [ ] Database connection works
- [ ] Form submission successful
- [ ] Data displays in summary
- [ ] Charts load on statistics page
- [ ] Mobile responsive (test on phone)
- [ ] HTTPS enabled (if applicable)
- [ ] Error logging enabled
- [ ] File permissions correct
- [ ] Backup schedule set
- [ ] Domain DNS configured (if using custom domain)

---

## 🔄 Backup Strategy

### Manual Backup

#### Files
```bash
# On server
cd /var/www/html
tar -czf driving-log-backup-$(date +%Y%m%d).tar.gz driving-log/
```

#### Database
```bash
mysqldump -u username -p driving_experience > backup-$(date +%Y%m%d).sql
```

### Automated Backup (Cron Job)

```bash
# Edit crontab
crontab -e

# Add daily backup at 2 AM
0 2 * * * mysqldump -u username -p'password' driving_experience > /backups/db-$(date +\%Y\%m\%d).sql
0 2 * * * tar -czf /backups/files-$(date +\%Y\%m\%d).tar.gz /var/www/html/driving-log/
```

---

## 📞 Support Resources

### Documentation
- Apache: https://httpd.apache.org/docs/
- MySQL: https://dev.mysql.com/doc/
- PHP: https://www.php.net/docs.php
- Let's Encrypt: https://letsencrypt.org/docs/

### Community Help
- Stack Overflow: https://stackoverflow.com/
- Server Fault: https://serverfault.com/
- PHP Forums: https://www.phpfreaks.com/

---

**Deployment Guide Version**: 1.0
**Last Updated**: December 23, 2025
**Tested On**: Ubuntu 20.04, cPanel, InfinityFree
