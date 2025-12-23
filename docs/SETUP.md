# Quick Setup Guide

## 🚀 Fast Setup Instructions

### 1. Database Setup (Choose One Method)

#### Method A: Using phpMyAdmin
1. Open phpMyAdmin (http://localhost/phpmyadmin)
2. Click "New" to create database
3. Click "Import" tab
4. Choose file: `database/database.sql`
5. Click "Go"

#### Method B: MySQL Command Line
```bash
mysql -u root -p
CREATE DATABASE driving_experience;
USE driving_experience;
SOURCE database/database.sql;
EXIT;
```

### 2. Configure Database Connection

Edit `includes/config.php`:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');           // Your MySQL username
define('DB_PASS', '');               // Your MySQL password
define('DB_NAME', 'driving_experience');
```

### 3. Run the Application

#### For XAMPP/WAMP:
1. Copy project to `C:\xampp\htdocs\` (or `C:\wamp64\www\`)
2. Start Apache and MySQL from XAMPP/WAMP control panel
3. Open browser: `http://localhost/Backend%20Project/`

#### For PHP Built-in Server:
```bash
cd "C:\Users\nadir\Desktop\Backend Project"
php -S localhost:8000
```
Then open: `http://localhost:8000`

## ✅ Verify Installation

1. Homepage should load without errors
2. Navigate to "Add Experience"
3. Fill and submit the form
4. Check "Summary" to see your entry
5. View "Statistics" for charts

## 🔧 Common Issues

**Problem**: Database connection error
**Solution**: 
- Check MySQL is running
- Verify username/password in config.php
- Ensure database was imported successfully

**Problem**: Page shows white screen
**Solution**: 
- Enable PHP error reporting by adding to index.php:
  ```php
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  ```

**Problem**: Charts not showing
**Solution**: 
- Check internet connection (Chart.js loads from CDN)
- Add data first (go to Add Experience)

## 📋 Test Data

The `database.sql` file includes sample data. To add more test data:
1. Go to "Add Experience"
2. Use today's date
3. Enter various conditions
4. Check Statistics page for updated charts

## 🎯 Next Steps

After setup:
1. ✅ Add your first driving experience
2. ✅ Explore the summary page
3. ✅ View statistics and charts
4. ✅ Test mobile responsiveness (resize browser)
5. ✅ Try the print function on summary page

## 📞 Need Help?

Check the full README.md for detailed documentation and troubleshooting.

---

**Ready in 5 minutes!** 🚗💨
