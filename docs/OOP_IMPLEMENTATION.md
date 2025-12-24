# OOP Implementation Documentation

## Overview
The project now implements Object-Oriented Programming (OOP) principles with proper class structure and inheritance.

## Class Architecture

### 1. **Database.php** - Singleton Pattern
**Location**: `includes/classes/Database.php`

**Purpose**: Manages database connections using the Singleton design pattern to ensure only one connection instance exists.

**Key Features**:
- Singleton pattern implementation
- Connection pooling
- Prepared statement wrapper methods
- Query execution methods (`query()`, `fetchAll()`, `fetchOne()`)

**Usage Example**:
```php
$db = Database::getInstance();
$results = $db->fetchAll("SELECT * FROM driving_experiences");
```

---

### 2. **BaseModel.php** - Abstract Base Class
**Location**: `includes/classes/BaseModel.php`

**Purpose**: Provides common CRUD functionality for all model classes through inheritance.

**Key Features**:
- Abstract class that cannot be instantiated directly
- Common methods: `find()`, `all()`, `delete()`, `count()`
- Protected database instance
- Reusable across all models

**Inherited By**: DrivingExperience, Weather, Traffic, RoadType, SurfaceQuality

---

### 3. **DrivingExperience.php** - Main Domain Model
**Location**: `includes/classes/DrivingExperience.php`

**Purpose**: Represents a driving experience record with full CRUD operations and business logic.

**Key Properties**:
- `id`, `experience_date`, `start_time`, `end_time`, `kilometers`
- `weather_id`, `traffic_id`, `road_type_id`, `surface_quality_id`
- `notes`, `created_at`, `updated_at`

**Key Methods**:
- `save()` - Inserts or updates based on ID presence
- `validate()` - Business logic validation
- `getAllWithDetails()` - Fetches data with JOINs
- `getTotalKilometers()` - Aggregation query
- `getStatsByWeather()` - Statistics generation
- `getStatsByTraffic()` - Traffic analysis
- `getStatsByRoadType()` - Road type statistics
- `getStatsBySurfaceQuality()` - Surface analysis
- `getDuration()` - Calculates time difference

**Usage Example**:
```php
// Create new experience
$experience = new DrivingExperience([
    'experience_date' => '2024-12-23',
    'start_time' => '10:00:00',
    'end_time' => '11:30:00',
    'kilometers' => 25.5,
    'weather_id' => 1,
    'traffic_id' => 2,
    'road_type_id' => 3,
    'surface_quality_id' => 1,
    'notes' => 'Highway practice'
]);

// Validate
$errors = $experience->validate();
if (empty($errors)) {
    $experience->save(); // Auto-inserts or updates
}

// Get all with details
$allExperiences = $experience->getAllWithDetails();
```

---

### 4. **Models.php** - Lookup Table Classes
**Location**: `includes/classes/Models.php`

**Purpose**: Contains model classes for lookup tables (weather, traffic, road types, surface quality).

**Classes Included**:
- **Weather** - Weather conditions
- **Traffic** - Traffic density levels
- **RoadType** - Types of roads
- **SurfaceQuality** - Road surface conditions

**Key Methods** (all classes):
- `getAllOrdered()` - Returns ordered list for dropdowns

**Usage Example**:
```php
$weatherModel = new Weather();
$weatherOptions = $weatherModel->getAllOrdered();

foreach ($weatherOptions as $option) {
    echo "<option value='{$option['id']}'>{$option['name']}</option>";
}
```

---

## Implementation in Pages

### **process-experience.php** - Form Processing
**OOP Implementation**:
```php
// Before (procedural):
$stmt = $conn->prepare("INSERT INTO...");
$stmt->bind_param("sssdiiiss", ...);
$stmt->execute();

// After (OOP):
$experience = new DrivingExperience($_POST);
$errors = $experience->validate();
if (empty($errors)) {
    $experience->save();
}
```

---

### **summary.php** - Data Display
**OOP Implementation**:
```php
// Before (procedural):
$experiences = getAllDrivingExperiences($conn);
$totalKm = getTotalKilometers($conn);

// After (OOP):
$experienceModel = new DrivingExperience();
$experiences = $experienceModel->getAllWithDetails();
$totalKm = $experienceModel->getTotalKilometers();
```

---

### **statistics.php** - Statistics
**OOP Implementation**:
```php
// Before (procedural):
$weatherStats = getStatsByWeather($conn);
$trafficStats = getStatsByTraffic($conn);

// After (OOP):
$experienceModel = new DrivingExperience();
$weatherStats = $experienceModel->getStatsByWeather();
$trafficStats = $experienceModel->getStatsByTraffic();
```

---

### **add-experience.php** - Form Dropdowns
**OOP Implementation**:
```php
// Before (procedural):
$weatherOptions = getWeatherConditions($conn);

// After (OOP):
$weatherModel = new Weather();
$weatherOptions = $weatherModel->getAllOrdered();
```

---

### **index.php** - Dashboard
**OOP Implementation**:
```php
// Before (procedural):
$result = $conn->query("SELECT COUNT(*) as count FROM...");
$totalKm = getTotalKilometers($conn);

// After (OOP):
$experienceModel = new DrivingExperience();
$totalExperiences = $experienceModel->count();
$totalKm = $experienceModel->getTotalKilometers();
```

---

## OOP Principles Applied

### 1. **Encapsulation**
- Data and methods are bundled within classes
- Properties are controlled through methods
- Internal implementation hidden from external code

### 2. **Inheritance**
- All models extend `BaseModel`
- Shared functionality inherited (find, all, delete, count)
- Code reusability achieved

### 3. **Abstraction**
- `BaseModel` is abstract (cannot be instantiated)
- Common interface for all models
- Implementation details hidden

### 4. **Single Responsibility Principle**
- Each class has one responsibility
- Database class: connection management
- Models: data operations
- Validation in domain objects

### 5. **Singleton Pattern**
- Database uses singleton for single connection instance
- Prevents multiple connections
- Resource efficiency

---

## Benefits of This Implementation

✅ **Code Reusability**: Common methods inherited from BaseModel  
✅ **Maintainability**: Changes to database logic centralized in Database class  
✅ **Testability**: Classes can be unit tested independently  
✅ **Type Safety**: Object properties provide structure  
✅ **Business Logic**: Validation logic encapsulated in models  
✅ **Scalability**: Easy to add new models following same pattern  
✅ **Separation of Concerns**: Data access separated from presentation  

---

## Autoloading

Classes are automatically loaded via `config.php`:

```php
spl_autoload_register(function ($class_name) {
    $file = __DIR__ . '/classes/' . $class_name . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});
```

---

## Implementation Benefits

### Architecture Features:
- **OOP Classes**: ✅ Full implementation
  - Database (Singleton)
  - BaseModel (Abstract)
  - DrivingExperience (Domain Model)
  - Weather, Traffic, RoadType, SurfaceQuality (Lookup Models)

- **Design Patterns**: ✅ Singleton, Inheritance, Abstract Class
- **Code Quality**: ✅ Clean, documented, maintainable

---

## Files Modified

1. `includes/config.php` - Added autoloader
2. `pages/process-experience.php` - Uses DrivingExperience class
3. `pages/summary.php` - Uses model methods
4. `pages/statistics.php` - Uses model statistics methods
5. `pages/add-experience.php` - Uses lookup model classes
6. `index.php` - Uses DrivingExperience for dashboard

---

## Testing Checklist

- [ ] Form submission creates new experience using OOP
- [ ] Summary page displays data using OOP methods
- [ ] Statistics page shows charts using OOP statistics
- [ ] Dropdown options load from OOP models
- [ ] Dashboard stats calculated via OOP
- [ ] No PHP errors on any page
- [ ] Data saves correctly to database

---

## Next Steps for Full MVC

To achieve full MVC architecture:
1. Create `controllers/` folder with controller classes
2. Create `views/` folder with template files
3. Implement routing system
4. Separate business logic from presentation
5. Add request/response handling

Current Status: **Partial MVC** (basic separation + OOP models)
