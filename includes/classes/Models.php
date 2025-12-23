<?php
/**
 * Weather Condition Model Class
 */
class Weather extends BaseModel {
    protected $table = 'weather_conditions';
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Get all weather conditions ordered by name
     */
    public function getAllOrdered() {
        $sql = "SELECT id, name FROM {$this->table} ORDER BY name";
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Add new weather condition
     */
    public function add($name) {
        $sql = "INSERT INTO {$this->table} (name) VALUES (?)";
        return $this->db->query($sql, [$name], 's');
    }
}

/**
 * Traffic Density Model Class
 */
class Traffic extends BaseModel {
    protected $table = 'traffic_density';
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Get all traffic density options in order
     */
    public function getAllOrdered() {
        $sql = "SELECT id, name FROM {$this->table} 
                ORDER BY FIELD(name, 'Light', 'Moderate', 'Heavy', 'Standstill')";
        return $this->db->fetchAll($sql);
    }
}

/**
 * Road Type Model Class
 */
class RoadType extends BaseModel {
    protected $table = 'road_types';
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Get all road types ordered by name
     */
    public function getAllOrdered() {
        $sql = "SELECT id, name FROM {$this->table} ORDER BY name";
        return $this->db->fetchAll($sql);
    }
}

/**
 * Road Surface Quality Model Class
 */
class SurfaceQuality extends BaseModel {
    protected $table = 'road_surface_quality';
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Get all surface quality options ordered by name
     */
    public function getAllOrdered() {
        $sql = "SELECT id, name FROM {$this->table} ORDER BY name";
        return $this->db->fetchAll($sql);
    }
}
?>
