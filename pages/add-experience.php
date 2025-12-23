<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Log your supervised driving experience">
    <title>Add Driving Experience - Supervised Driving Log</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <!-- jQuery UI CSS -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
</head>
<body>
    <?php 
    require_once '../includes/config.php';
    require_once '../includes/functions.php';
    
    // Require login
    User::requireLogin();
    
    // Use OOP models to get dropdown options
    $weatherModel = new Weather();
    $trafficModel = new Traffic();
    $roadTypeModel = new RoadType();
    $surfaceQualityModel = new SurfaceQuality();
    
    $weatherOptions = $weatherModel->getAllOrdered();
    $trafficOptions = $trafficModel->getAllOrdered();
    $roadTypeOptions = $roadTypeModel->getAllOrdered();
    $surfaceQualityOptions = $surfaceQualityModel->getAllOrdered();
    ?>
    
    <header class="main-header">
        <div class="container">
            <h1>🚗 Supervised Driving Log</h1>
            <nav class="main-nav">
                <a href="../index.php">Home</a>
                <a href="add-experience.php" class="active">Add Experience</a>
                <a href="summary.php">Summary</a>
                <a href="statistics.php">Statistics</a>
                <a href="../logout.php" style="margin-left: auto;">Logout (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a>
            </nav>
        </div>
    </header>
    
    <main class="main-content">
        <div class="container">
            <section class="form-section">
                <h2>Log New Driving Experience</h2>
                
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success">
                        <?php 
                        echo $_SESSION['success']; 
                        unset($_SESSION['success']);
                        ?>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-error">
                        <?php 
                        echo $_SESSION['error']; 
                        unset($_SESSION['error']);
                        ?>
                    </div>
                <?php endif; ?>
                
                <form action="process-experience.php" method="POST" class="driving-form" id="drivingForm">
                    
                    <div class="form-grid">
                        <!-- Date -->
                        <div class="form-group">
                            <label for="experience_date">
                                <span class="label-text">Date of Experience</span>
                                <span class="required">*</span>
                            </label>
                            <input 
                                type="date" 
                                id="experience_date" 
                                name="experience_date" 
                                value="<?php echo date('Y-m-d'); ?>"
                                max="<?php echo date('Y-m-d'); ?>"
                                required
                            >
                        </div>
                        
                        <!-- Start Time -->
                        <div class="form-group">
                            <label for="start_time">
                                <span class="label-text">Start Time</span>
                                <span class="required">*</span>
                            </label>
                            <input 
                                type="time" 
                                id="start_time" 
                                name="start_time" 
                                value="<?php echo date('H:i'); ?>"
                                required
                            >
                        </div>
                        
                        <!-- End Time -->
                        <div class="form-group">
                            <label for="end_time">
                                <span class="label-text">End Time</span>
                                <span class="required">*</span>
                            </label>
                            <input 
                                type="time" 
                                id="end_time" 
                                name="end_time" 
                                required
                            >
                        </div>
                        
                        <!-- Kilometers -->
                        <div class="form-group">
                            <label for="kilometers">
                                <span class="label-text">Kilometers Traveled</span>
                                <span class="required">*</span>
                            </label>
                            <input 
                                type="number" 
                                id="kilometers" 
                                name="kilometers" 
                                step="0.01" 
                                min="0.01" 
                                placeholder="e.g., 25.5"
                                inputmode="decimal"
                                required
                            >
                        </div>
                        
                        <!-- Weather Condition -->
                        <div class="form-group">
                            <label for="weather_id">
                                <span class="label-text">Weather Condition</span>
                                <span class="required">*</span>
                            </label>
                            <select id="weather_id" name="weather_id" required>
                                <option value="">Select weather...</option>
                                <?php foreach ($weatherOptions as $weather): ?>
                                    <option value="<?php echo $weather['id']; ?>">
                                        <?php echo htmlspecialchars($weather['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <!-- Traffic Density -->
                        <div class="form-group">
                            <label for="traffic_id">
                                <span class="label-text">Traffic Density</span>
                                <span class="required">*</span>
                            </label>
                            <select id="traffic_id" name="traffic_id" required>
                                <option value="">Select traffic...</option>
                                <?php foreach ($trafficOptions as $traffic): ?>
                                    <option value="<?php echo $traffic['id']; ?>">
                                        <?php echo htmlspecialchars($traffic['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <!-- Road Type -->
                        <div class="form-group">
                            <label for="road_type_id">
                                <span class="label-text">Road Type</span>
                                <span class="required">*</span>
                            </label>
                            <select id="road_type_id" name="road_type_id" required>
                                <option value="">Select road type...</option>
                                <?php foreach ($roadTypeOptions as $roadType): ?>
                                    <option value="<?php echo $roadType['id']; ?>">
                                        <?php echo htmlspecialchars($roadType['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <!-- Road Surface Quality -->
                        <div class="form-group">
                            <label for="surface_quality_id">
                                <span class="label-text">Road Surface Quality</span>
                                <span class="required">*</span>
                            </label>
                            <select id="surface_quality_id" name="surface_quality_id" required>
                                <option value="">Select surface quality...</option>
                                <?php foreach ($surfaceQualityOptions as $surface): ?>
                                    <option value="<?php echo $surface['id']; ?>">
                                        <?php echo htmlspecialchars($surface['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Notes -->
                    <div class="form-group full-width">
                        <label for="notes">
                            <span class="label-text">Additional Notes (Optional)</span>
                        </label>
                        <textarea 
                            id="notes" 
                            name="notes" 
                            rows="4" 
                            placeholder="Any observations, challenges, or achievements during this drive..."
                        ></textarea>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Save Experience</button>
                        <button type="reset" class="btn btn-secondary">Clear Form</button>
                    </div>
                    
                </form>
            </section>
        </div>
    </main>
    
    <footer class="main-footer">
        <div class="container">
            <p>&copy; 2025 Supervised Driving Log. All rights reserved.</p>
        </div>
    </footer>
    
    <!-- jQuery (for jQuery UI) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- jQuery UI -->
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    
    <script src="../assets/js/form-validation.js"></script>
    <script>
        // jQuery UI Date Picker
        $(document).ready(function() {
            $("#experience_date").datepicker({
                dateFormat: 'yy-mm-dd',
                maxDate: 0, // Today is max
                changeMonth: true,
                changeYear: true,
                yearRange: "-1:+0",
                showButtonPanel: true
            });
        });
    </script>
</body>
</html>
