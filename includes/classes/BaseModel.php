<?php
/**
 * Base Model Class
 * Provides common functionality for all models
 */
abstract class BaseModel {
    protected $db;
    protected $table;
    protected $primaryKey = 'id';
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Find record by ID
     */
    public function find($id) {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ?";
        return $this->db->fetchOne($sql, [$id], 'i');
    }
    
    /**
     * Get all records
     */
    public function all() {
        $sql = "SELECT * FROM {$this->table}";
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Delete record by ID
     */
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?";
        return $this->db->query($sql, [$id], 'i');
    }
    
    /**
     * Count records
     */
    public function count($userId = null) {
        if ($userId && $this->table === 'driving_experiences') {
            $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE user_id = ?";
            $result = $this->db->fetchOne($sql, [$userId], 'i');
        } else {
            $sql = "SELECT COUNT(*) as total FROM {$this->table}";
            $result = $this->db->fetchOne($sql);
        }
        return $result['total'] ?? 0;
    }
}
?>
