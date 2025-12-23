<?php
/**
 * Common Functions for Driving Experience Application
 */

/**
 * Sanitize input data
 */
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

/**
 * Get all weather conditions
 */
function getWeatherConditions($conn) {
    $sql = "SELECT id, name FROM weather_conditions ORDER BY name";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

/**
 * Get all traffic density options
 */
function getTrafficDensity($conn) {
    $sql = "SELECT id, name FROM traffic_density ORDER BY 
            FIELD(name, 'Light', 'Moderate', 'Heavy', 'Standstill')";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

/**
 * Get all road types
 */
function getRoadTypes($conn) {
    $sql = "SELECT id, name FROM road_types ORDER BY name";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

/**
 * Get all road surface quality options
 */
function getRoadSurfaceQuality($conn) {
    $sql = "SELECT id, name FROM road_surface_quality ORDER BY name";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

/**
 * Get all driving experiences with related data
 */
function getAllDrivingExperiences($conn) {
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
            FROM driving_experiences de
            JOIN weather_conditions wc ON de.weather_id = wc.id
            JOIN traffic_density td ON de.traffic_id = td.id
            JOIN road_types rt ON de.road_type_id = rt.id
            JOIN road_surface_quality rsq ON de.surface_quality_id = rsq.id
            ORDER BY de.experience_date DESC, de.start_time DESC";
    
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

/**
 * Get total kilometers traveled
 */
function getTotalKilometers($conn) {
    $sql = "SELECT SUM(kilometers) as total FROM driving_experiences";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['total'] ?? 0;
}

/**
 * Get statistics by weather condition
 */
function getStatsByWeather($conn) {
    $sql = "SELECT 
                wc.name as weather,
                COUNT(de.id) as count,
                SUM(de.kilometers) as total_km,
                AVG(de.kilometers) as avg_km
            FROM weather_conditions wc
            LEFT JOIN driving_experiences de ON wc.id = de.weather_id
            GROUP BY wc.id, wc.name
            ORDER BY total_km DESC";
    
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

/**
 * Get statistics by traffic density
 */
function getStatsByTraffic($conn) {
    $sql = "SELECT 
                td.name as traffic,
                COUNT(de.id) as count,
                SUM(de.kilometers) as total_km,
                AVG(de.kilometers) as avg_km
            FROM traffic_density td
            LEFT JOIN driving_experiences de ON td.id = de.traffic_id
            GROUP BY td.id, td.name
            ORDER BY FIELD(td.name, 'Light', 'Moderate', 'Heavy', 'Standstill')";
    
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

/**
 * Get statistics by road type
 */
function getStatsByRoadType($conn) {
    $sql = "SELECT 
                rt.name as road_type,
                COUNT(de.id) as count,
                SUM(de.kilometers) as total_km,
                AVG(de.kilometers) as avg_km
            FROM road_types rt
            LEFT JOIN driving_experiences de ON rt.id = de.road_type_id
            GROUP BY rt.id, rt.name
            ORDER BY total_km DESC";
    
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

/**
 * Get statistics by road surface quality
 */
function getStatsBySurfaceQuality($conn) {
    $sql = "SELECT 
                rsq.name as surface_quality,
                COUNT(de.id) as count,
                SUM(de.kilometers) as total_km,
                AVG(de.kilometers) as avg_km
            FROM road_surface_quality rsq
            LEFT JOIN driving_experiences de ON rsq.id = de.surface_quality_id
            GROUP BY rsq.id, rsq.name
            ORDER BY total_km DESC";
    
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

/**
 * Get driving experiences by date range
 */
function getExperiencesByDateRange($conn, $startDate, $endDate) {
    $stmt = $conn->prepare("SELECT 
                de.id,
                de.experience_date,
                de.start_time,
                de.end_time,
                de.kilometers,
                wc.name as weather,
                td.name as traffic,
                rt.name as road_type,
                rsq.name as surface_quality,
                de.notes
            FROM driving_experiences de
            JOIN weather_conditions wc ON de.weather_id = wc.id
            JOIN traffic_density td ON de.traffic_id = td.id
            JOIN road_types rt ON de.road_type_id = rt.id
            JOIN road_surface_quality rsq ON de.surface_quality_id = rsq.id
            WHERE de.experience_date BETWEEN ? AND ?
            ORDER BY de.experience_date DESC, de.start_time DESC");
    
    $stmt->bind_param("ss", $startDate, $endDate);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

/**
 * Format date for display
 */
function formatDate($date) {
    return date('F j, Y', strtotime($date));
}

/**
 * Format time for display (24-hour format)
 */
function formatTime($time) {
    return date('H:i', strtotime($time));
}

/**
 * Calculate duration between two times
 */
function calculateDuration($startTime, $endTime) {
    $start = strtotime($startTime);
    $end = strtotime($endTime);
    $diff = $end - $start;
    
    $hours = floor($diff / 3600);
    $minutes = floor(($diff % 3600) / 60);
    
    return sprintf("%02d:%02d", $hours, $minutes);
}
?>
