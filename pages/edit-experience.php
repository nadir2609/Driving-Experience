<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Edit driving experience">
    <title>Edit Experience - Supervised Driving Log</title>
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
    
    // Get experience ID
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    
    if (!$id) {
        header('Location: summary.php');
        exit();
    }
    
    // Get experience data
    $experienceModel = new DrivingExperience();
    $experience = $experienceModel->find($id);
    
    // Check if experience exists and belongs to current user
    if (!$experience || $experience['user_id'] != User::getCurrentUserId()) {
        $_SESSION['error'] = 'Experience not found or access denied.';
        header('Location: summary.php');
        exit();
    }
    
    // Get dropdown options
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
                <a href="add-experience.php">Add Experience</a>
                <a href="summary.php">Summary</a>
                <a href="statistics.php">Statistics</a>
                <a href="../logout.php" style="margin-left: auto;">Logout (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a>
            </nav>
        </div>
    </header>
    
    <main class="main-content">
        <div class="container">
            <section class="form-section">
                <div class="section-header">
                    <h2>✏️ Edit Driving Experience</h2>
                    <p>Update your driving experience details</p>
                </div>
                
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-error">
                        <?php 
                        echo $_SESSION['error']; 
                        unset($_SESSION['error']);
                        ?>
                    </div>
                <?php endif; ?>
                
                <form action="update-experience.php" method="POST" class="experience-form" id="editForm">
                    <input type="hidden" name="id" value="<?php echo $experience['id']; ?>">
                    
                    <div class="form-row">
                        <!-- Date -->
                        <div class="form-group">
                            <label for="experience_date">
                                <span class="label-text">Date</span>
                                <span class="required">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="experience_date" 
                                name="experience_date" 
                                value="<?php echo htmlspecialchars($experience['experience_date']); ?>"
                                required
                                readonly
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
                                value="<?php echo htmlspecialchars($experience['start_time']); ?>"
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
                                value="<?php echo htmlspecialchars($experience['end_time']); ?>"
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
                                value="<?php echo htmlspecialchars($experience['kilometers']); ?>"
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
                                <?php foreach ($weatherOptions as $option): ?>
                                    <option value="<?php echo $option['id']; ?>" 
                                        <?php echo ($option['id'] == $experience['weather_id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($option['name']); ?>
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
                                <?php foreach ($trafficOptions as $option): ?>
                                    <option value="<?php echo $option['id']; ?>"
                                        <?php echo ($option['id'] == $experience['traffic_id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($option['name']); ?>
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
                                <?php foreach ($roadTypeOptions as $option): ?>
                                    <option value="<?php echo $option['id']; ?>"
                                        <?php echo ($option['id'] == $experience['road_type_id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($option['name']); ?>
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
                                <?php foreach ($surfaceQualityOptions as $option): ?>
                                    <option value="<?php echo $option['id']; ?>"
                                        <?php echo ($option['id'] == $experience['surface_quality_id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($option['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Notes -->
                    <div class="form-group full-width">
                        <label for="notes">
                            <span class="label-text">Additional Notes</span>
                            <span class="optional">(Optional)</span>
                        </label>
                        <textarea 
                            id="notes" 
                            name="notes" 
                            rows="4" 
                            placeholder="Any observations, difficulties, or comments about this driving session..."
                        ><?php echo htmlspecialchars($experience['notes'] ?? ''); ?></textarea>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">💾 Update Experience</button>
                        <a href="summary.php" class="btn btn-secondary">Cancel</a>
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
    
    <!-- jQuery and jQuery UI -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#experience_date").datepicker({
                dateFormat: 'yy-mm-dd',
                maxDate: 0,
                changeMonth: true,
                changeYear: true,
                yearRange: "-1:+0",
                showButtonPanel: true
            });
        });
    </script>
    
    <script src="../assets/js/form-validation.js"></script>
</body>
</html>
