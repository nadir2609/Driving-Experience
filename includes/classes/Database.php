<?php
/**
 * Database Connection Class
 * Singleton pattern for database connection management
 */
class Database {
    private static $instance = null;
    private $connection;
    
    private function __construct() {
        $this->connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        
        if ($this->connection->connect_error) {
            die("Database connection failed: " . $this->connection->connect_error);
        }
        
        $this->connection->set_charset("utf8mb4");
    }
    
    /**
     * Get singleton instance
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Get mysqli connection
     */
    public function getConnection() {
        return $this->connection;
    }
    
    /**
     * Execute a prepared query
     */
    public function query($sql, $params = [], $types = '') {
        $stmt = $this->connection->prepare($sql);
        
        if ($params && $types) {
            $stmt->bind_param($types, ...$params);
        }
        
        $stmt->execute();
        return $stmt;
    }
    
    /**
     * Fetch all results from query
     */
    public function fetchAll($sql, $params = [], $types = '') {
        $stmt = $this->query($sql, $params, $types);
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    /**
     * Fetch single result
     */
    public function fetchOne($sql, $params = [], $types = '') {
        $stmt = $this->query($sql, $params, $types);
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    
    /**
     * Close connection
     */
    public function close() {
        if ($this->connection) {
            $this->connection->close();
        }
    }
    
    /**
     * Prevent cloning
     */
    public function __clone() {
        throw new Exception("Cannot clone singleton");
    }
    
    /**
     * Prevent unserialization
     */
    public function __wakeup() {
        throw new Exception("Cannot unserialize singleton");
    }
}
?>
