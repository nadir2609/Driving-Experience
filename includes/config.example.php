<?php
/**
 * Database Configuration File
 * Update these settings to match your database setup
 */

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database connection parameters
define('DB_HOST', 'localhost');           // Your database host (localhost for XAMPP)
define('DB_USER', 'root');                // Your database username
define('DB_PASS', '');                    // Your database password
define('DB_NAME', 'driving_experience');  // Your database name

// Timezone settings
date_default_timezone_set('Europe/Paris');

/**
 * Autoload classes
 */
spl_autoload_register(function ($class_name) {
    $file = __DIR__ . '/classes/' . $class_name . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// Load User class for authentication
require_once __DIR__ . '/classes/User.php';

// Error reporting (set to 0 in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Application settings
define('APP_NAME', 'Supervised Driving Log');
define('APP_VERSION', '2.0.0');

// Base URL (update for your environment)
define('BASE_URL', 'http://localhost/Backend%20Project/');

// Security settings
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0); // Set to 1 if using HTTPS

/**
 * IMPORTANT SETUP INSTRUCTIONS:
 * ==============================
 * 1. Copy this file and rename it to 'config.php'
 * 2. Update the database credentials above to match your environment
 * 3. Import database/database.sql into your MySQL database
 * 4. Import database/database_users.sql to add user authentication
 * 5. Never commit the actual config.php file to version control
 */
?>
