<?php
/**
 * User Model Class
 * Handles user authentication and management
 */
class User extends BaseModel {
    protected $table = 'users';
    
    // Properties
    public $id;
    public $username;
    public $email;
    public $password;
    public $full_name;
    public $created_at;
    
    public function __construct($data = []) {
        parent::__construct();
        
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                if (property_exists($this, $key)) {
                    $this->$key = $value;
                }
            }
        }
    }
    
    /**
     * Register new user
     */
    public function register($username, $email, $password, $fullName) {
        // Check if username exists
        if ($this->findByUsername($username)) {
            return ['success' => false, 'message' => 'Username already exists.'];
        }
        
        // Check if email exists
        if ($this->findByEmail($email)) {
            return ['success' => false, 'message' => 'Email already exists.'];
        }
        
        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // Insert user
        $sql = "INSERT INTO {$this->table} (username, email, password, full_name) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->query($sql, [$username, $email, $hashedPassword, $fullName], 'ssss');
        
        if ($stmt) {
            $this->id = $this->db->getConnection()->insert_id;
            return ['success' => true, 'message' => 'Registration successful!'];
        }
        
        return ['success' => false, 'message' => 'Registration failed. Please try again.'];
    }
    
    /**
     * Login user
     */
    public function login($username, $password) {
        $user = $this->findByUsername($username);
        
        if (!$user) {
            return ['success' => false, 'message' => 'Invalid username or password.'];
        }
        
        if (!password_verify($password, $user['password'])) {
            return ['success' => false, 'message' => 'Invalid username or password.'];
        }
        
        // Set session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['full_name'] = $user['full_name'];
        
        return ['success' => true, 'message' => 'Login successful!', 'user' => $user];
    }
    
    /**
     * Find user by username
     */
    public function findByUsername($username) {
        $sql = "SELECT * FROM {$this->table} WHERE username = ?";
        return $this->db->fetchOne($sql, [$username], 's');
    }
    
    /**
     * Find user by email
     */
    public function findByEmail($email) {
        $sql = "SELECT * FROM {$this->table} WHERE email = ?";
        return $this->db->fetchOne($sql, [$email], 's');
    }
    
    /**
     * Logout user
     */
    public static function logout() {
        session_destroy();
        header('Location: ../login.php');
        exit();
    }
    
    /**
     * Check if user is logged in
     */
    public static function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
    
    /**
     * Require login
     */
    public static function requireLogin() {
        if (!self::isLoggedIn()) {
            header('Location: ../login.php');
            exit();
        }
    }
    
    /**
     * Get current user ID
     */
    public static function getCurrentUserId() {
        return $_SESSION['user_id'] ?? null;
    }
}
?>
