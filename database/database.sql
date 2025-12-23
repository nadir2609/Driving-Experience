-- Supervised Driving Experience Database
-- Note: Database should already be created via hosting control panel
-- This file only creates tables and inserts data

-- Table for Weather Conditions
CREATE TABLE IF NOT EXISTS weather_conditions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table for Traffic Density
CREATE TABLE IF NOT EXISTS traffic_density (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table for Road Types
CREATE TABLE IF NOT EXISTS road_types (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table for Road Surface Quality
CREATE TABLE IF NOT EXISTS road_surface_quality (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Main Table for Driving Experiences
CREATE TABLE IF NOT EXISTS driving_experiences (
    id INT AUTO_INCREMENT PRIMARY KEY,
    experience_date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    kilometers DECIMAL(10,2) NOT NULL,
    weather_id INT NOT NULL,
    traffic_id INT NOT NULL,
    road_type_id INT NOT NULL,
    surface_quality_id INT NOT NULL,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (weather_id) REFERENCES weather_conditions(id),
    FOREIGN KEY (traffic_id) REFERENCES traffic_density(id),
    FOREIGN KEY (road_type_id) REFERENCES road_types(id),
    FOREIGN KEY (surface_quality_id) REFERENCES road_surface_quality(id)
);

-- Insert Default Weather Conditions
INSERT INTO weather_conditions (name) VALUES 
    ('Clear'),
    ('Rain'),
    ('Snow'),
    ('Fog'),
    ('Storm');

-- Insert Default Traffic Density
INSERT INTO traffic_density (name) VALUES 
    ('Light'),
    ('Moderate'),
    ('Heavy'),
    ('Standstill');

-- Insert Default Road Types
INSERT INTO road_types (name) VALUES 
    ('Highway'),
    ('City Street'),
    ('Country Road'),
    ('Gravel'),
    ('Dirt Trail');

-- Insert Default Road Surface Quality
INSERT INTO road_surface_quality (name) VALUES 
    ('Pristine'),
    ('Cracked'),
    ('Potholed');
