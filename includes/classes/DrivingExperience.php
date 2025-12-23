<?php
/**
 * Driving Experience Model Class
 * Main model for managing driving experiences
 */
class DrivingExperience extends BaseModel {
    protected $table = 'driving_experiences';
    
    // Properties
    public $id;
    public $experience_date;
    public $start_time;
    public $end_time;
    public $kilometers;
    public $weather_id;
    public $traffic_id;
    public $road_type_id;
    public $surface_quality_id;
    public $notes;
    public $created_at;
    public $updated_at;
    
    // Related data
    public $weather;
    public $traffic;
    public $road_type;
    public $surface_quality;
    
    public function __construct($data = []) {
        parent::__construct();
        
        if (!empty($data)) {
            $this->loadFromArray($data);
        }
    }
    
    /**
     * Load data from array
     */
    private function loadFromArray($data) {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }
    
    /**
     * Save driving experience to database
     */
    public function save() {
        if (isset($this->id) && $this->id > 0) {
            return $this->update();
        } else {
            return $this->insert();
        }
    }
    
    /**
     * Insert new driving experience
     */
    private function insert() {
        $userId = User::getCurrentUserId();
        
        $sql = "INSERT INTO {$this->table} 
                (user_id, experience_date, start_time, end_time, kilometers, 
                 weather_id, traffic_id, road_type_id, surface_quality_id, notes) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $params = [
            $userId,
            $this->experience_date,
            $this->start_time,
            $this->end_time,
            $this->kilometers,
            $this->weather_id,
            $this->traffic_id,
            $this->road_type_id,
            $this->surface_quality_id,
            $this->notes
        ];
        
        $stmt = $this->db->query($sql, $params, 'isssdiiiss');
        
        if ($stmt) {
            $this->id = $this->db->getConnection()->insert_id;
            return true;
        }
        
        return false;
    }
    
    /**
     * Update existing driving experience
     */
    private function update() {
        $sql = "UPDATE {$this->table} SET 
                experience_date = ?, start_time = ?, end_time = ?, 
                kilometers = ?, weather_id = ?, traffic_id = ?, 
                road_type_id = ?, surface_quality_id = ?, notes = ? 
                WHERE id = ?";
        
        $params = [
            $this->experience_date,
            $this->start_time,
            $this->end_time,
            $this->kilometers,
            $this->weather_id,
            $this->traffic_id,
            $this->road_type_id,
            $this->surface_quality_id,
            $this->notes,
            $this->id
        ];
        
        return $this->db->query($sql, $params, 'sssdiiissi');
    }
    
    /**
     * Get all driving experiences with related data (JOINs)
     */
    public function getAllWithDetails($userId = null) {
        $userId = $userId ?? User::getCurrentUserId();
        
        $sql = "SELECT 
                    de.id,
                    de.experience_date,
                    de.start_time,
                    de.end_time,
                    de.kilometers,
                    wc.name as weather,
                    td.name as traffic,
                    rt.name as road_type,
                    rsq.name as surface_quality,
                    de.notes,
                    de.created_at
                FROM {$this->table} de
                JOIN weather_conditions wc ON de.weather_id = wc.id
                JOIN traffic_density td ON de.traffic_id = td.id
                JOIN road_types rt ON de.road_type_id = rt.id
                JOIN road_surface_quality rsq ON de.surface_quality_id = rsq.id
                WHERE de.user_id = ?
                ORDER BY de.experience_date DESC, de.start_time DESC";
        
        return $this->db->fetchAll($sql, [$userId], 'i');
    }
    
    /**
     * Get total kilometers
     */
    public function getTotalKilometers($userId = null) {
        $userId = $userId ?? User::getCurrentUserId();
        $sql = "SELECT SUM(kilometers) as total FROM {$this->table} WHERE user_id = ?";
        $result = $this->db->fetchOne($sql, [$userId], 'i');
        return $result['total'] ?? 0;
    }
    
    /**
     * Get statistics by weather
     */
    public function getStatsByWeather($userId = null) {
        $userId = $userId ?? User::getCurrentUserId();
        $sql = "SELECT 
                    wc.name as weather,
                    COUNT(de.id) as count,
                    SUM(de.kilometers) as total_km,
                    AVG(de.kilometers) as avg_km
                FROM weather_conditions wc
                LEFT JOIN {$this->table} de ON wc.id = de.weather_id AND de.user_id = ?
                GROUP BY wc.id, wc.name
                ORDER BY total_km DESC";
        
        return $this->db->fetchAll($sql, [$userId], 'i');
    }
    
    /**
     * Get statistics by traffic
     */
    public function getStatsByTraffic($userId = null) {
        $userId = $userId ?? User::getCurrentUserId();
        $sql = "SELECT 
                    td.name as traffic,
                    COUNT(de.id) as count,
                    SUM(de.kilometers) as total_km,
                    AVG(de.kilometers) as avg_km
                FROM traffic_density td
                LEFT JOIN {$this->table} de ON td.id = de.traffic_id AND de.user_id = ?
                GROUP BY td.id, td.name
                ORDER BY FIELD(td.name, 'Light', 'Moderate', 'Heavy', 'Standstill')";
        
        return $this->db->fetchAll($sql, [$userId], 'i');
    }
    
    /**
     * Get statistics by road type
     */
    public function getStatsByRoadType($userId = null) {
        $userId = $userId ?? User::getCurrentUserId();
        $sql = "SELECT 
                    rt.name as road_type,
                    COUNT(de.id) as count,
                    SUM(de.kilometers) as total_km,
                    AVG(de.kilometers) as avg_km
                FROM road_types rt
                LEFT JOIN {$this->table} de ON rt.id = de.road_type_id AND de.user_id = ?
                GROUP BY rt.id, rt.name
                ORDER BY total_km DESC";
        
        return $this->db->fetchAll($sql, [$userId], 'i');
    }
    
    /**
     * Get statistics by surface quality
     */
    public function getStatsBySurfaceQuality($userId = null) {
        $userId = $userId ?? User::getCurrentUserId();
        $sql = "SELECT 
                    rsq.name as surface_quality,
                    COUNT(de.id) as count,
                    SUM(de.kilometers) as total_km,
                    AVG(de.kilometers) as avg_km
                FROM road_surface_quality rsq
                LEFT JOIN {$this->table} de ON rsq.id = de.surface_quality_id AND de.user_id = ?
                GROUP BY rsq.id, rsq.name
                ORDER BY total_km DESC";
        
        return $this->db->fetchAll($sql, [$userId], 'i');
    }
    
    /**
     * Calculate duration between times
     */
    public function getDuration() {
        if (!$this->start_time || !$this->end_time) {
            return '00:00';
        }
        
        $start = strtotime($this->start_time);
        $end = strtotime($this->end_time);
        $diff = $end - $start;
        
        $hours = floor($diff / 3600);
        $minutes = floor(($diff % 3600) / 60);
        
        return sprintf("%02d:%02d", $hours, $minutes);
    }
    
    /**
     * Validate driving experience data
     */
    public function validate() {
        $errors = [];
        
        // Validate date (only block future dates, not today)
        if (empty($this->experience_date)) {
            $errors[] = "Date is required.";
        } else {
            // Compare date strings directly to avoid timezone issues
            $experienceDate = date('Y-m-d', strtotime($this->experience_date));
            $today = date('Y-m-d');
            
            if ($experienceDate > $today) {
                $errors[] = "Date cannot be in the future.";
            }
        }
        
        // Validate times
        if (empty($this->start_time) || empty($this->end_time)) {
            $errors[] = "Start and end times are required.";
        } elseif (strtotime($this->end_time) <= strtotime($this->start_time)) {
            $errors[] = "End time must be after start time.";
        }
        
        // Validate kilometers
        if (empty($this->kilometers) || $this->kilometers <= 0) {
            $errors[] = "Kilometers must be greater than 0.";
        } elseif ($this->kilometers > 1000) {
            $errors[] = "Kilometers seem unusually high.";
        }
        
        // Validate IDs
        if (empty($this->weather_id)) $errors[] = "Weather condition is required.";
        if (empty($this->traffic_id)) $errors[] = "Traffic density is required.";
        if (empty($this->road_type_id)) $errors[] = "Road type is required.";
        if (empty($this->surface_quality_id)) $errors[] = "Surface quality is required.";
        
        return $errors;
    }
}
?>
