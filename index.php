<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Track and manage your supervised driving experience">
    <meta name="author" content="Supervised Driving Log">
    <title>Supervised Driving Log - Home</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php 
    require_once 'includes/config.php';
    require_once 'includes/functions.php';
    
    // Require login
    User::requireLogin();
    
    // Use OOP to get dashboard statistics
    $experienceModel = new DrivingExperience();
    $userId = User::getCurrentUserId();
    $totalExperiences = $experienceModel->count($userId);
    $totalKm = $experienceModel->getTotalKilometers($userId);
    
    // Get recent experiences using OOP
    $allExperiences = $experienceModel->getAllWithDetails($userId);
    $recentExperiences = array_slice($allExperiences, 0, 3);
    ?>
    
    <header class="main-header">
        <div class="container">
            <h1>🚗 Supervised Driving Log</h1>
            <nav class="main-nav">
                <a href="index.php" class="active">Home</a>
                <a href="pages/add-experience.php">Add Experience</a>
                <a href="pages/summary.php">Summary</a>
                <a href="pages/statistics.php">Statistics</a>
                <a href="logout.php" style="margin-left: auto;">Logout (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a>
            </nav>
        </div>
    </header>
    
    <main class="main-content">
        <div class="container">
            
            <!-- Hero Section -->
            <section class="hero-section">
                <div class="hero-icon">🚗</div>
                <h2>Welcome to Supervised Driving Log</h2>
                <p>Track your driving progress, analyze your experiences, and prepare for your driver's license with confidence.</p>
                <a href="pages/add-experience.php" class="btn btn-primary">Log Your First Drive</a>
            </section>
            
            <!-- Quick Stats -->
            <section>
                <h2 style="text-align: center; margin-bottom: 2rem;">Your Progress</h2>
                <div class="stats-cards">
                    <div class="stat-card">
                        <div class="stat-icon">📊</div>
                        <div class="stat-info">
                            <div class="stat-value"><?php echo $totalExperiences; ?></div>
                            <div class="stat-label">Total Driving Sessions</div>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon">🛣️</div>
                        <div class="stat-info">
                            <div class="stat-value"><?php echo number_format($totalKm, 1); ?></div>
                            <div class="stat-label">Kilometers Traveled</div>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon">⭐</div>
                        <div class="stat-info">
                            <div class="stat-value">
                                <?php 
                                echo $totalExperiences > 0 
                                    ? number_format($totalKm / $totalExperiences, 1) 
                                    : '0.0'; 
                                ?>
                            </div>
                            <div class="stat-label">Average KM per Session</div>
                        </div>
                    </div>
                </div>
            </section>
            
            <!-- Recent Experiences -->
            <?php if (count($recentExperiences) > 0): ?>
            <section style="margin-top: 3rem;">
                <h2 style="text-align: center; margin-bottom: 2rem;">Recent Experiences</h2>
                <div class="features-grid">
                    <?php foreach ($recentExperiences as $exp): ?>
                    <div class="feature-card">
                        <div class="feature-icon">📅</div>
                        <h3><?php echo formatDate($exp['experience_date']); ?></h3>
                        <p>
                            <strong><?php echo number_format($exp['kilometers'], 2); ?> km</strong><br>
                            <?php echo htmlspecialchars($exp['weather']); ?> • <?php echo htmlspecialchars($exp['road_type']); ?>
                        </p>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div style="text-align: center; margin-top: 2rem;">
                    <a href="pages/summary.php" class="btn btn-secondary">View All Experiences</a>
                </div>
            </section>
            <?php endif; ?>
            
            <!-- Features -->
            <section style="margin-top: 3rem;">
                <h2 style="text-align: center; margin-bottom: 2rem;">Features</h2>
                <div class="features-grid">
                    <div class="feature-card">
                        <div class="feature-icon">✍️</div>
                        <h3>Log Experiences</h3>
                        <p>Easily record your driving sessions with details about weather, road conditions, and more.</p>
                        <a href="pages/add-experience.php" class="btn btn-primary">Add Experience</a>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">📋</div>
                        <h3>View Summary</h3>
                        <p>See all your driving experiences in one place with total kilometers and detailed information.</p>
                        <a href="pages/summary.php" class="btn btn-primary">View Summary</a>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">📊</div>
                        <h3>Analyze Statistics</h3>
                        <p>Visualize your driving data with charts and statistics across different conditions.</p>
                        <a href="pages/statistics.php" class="btn btn-primary">View Statistics</a>
                    </div>
                </div>
            </section>
            
            <!-- Information Section -->
            <section style="margin-top: 3rem; background: white; padding: 2rem; border-radius: 0.75rem; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                <h2 style="color: #3B82F6; margin-bottom: 1.5rem;">Why Track Your Driving?</h2>
                <div style="display: grid; gap: 1rem;">
                    <p style="margin: 0;">
                        ✅ <strong>Meet Requirements:</strong> Many jurisdictions require a minimum number of supervised driving hours before obtaining a license.
                    </p>
                    <p style="margin: 0;">
                        ✅ <strong>Build Confidence:</strong> Track your progress and see how you improve across different driving conditions.
                    </p>
                    <p style="margin: 0;">
                        ✅ <strong>Identify Patterns:</strong> Understand which conditions you're most experienced in and which need more practice.
                    </p>
                    <p style="margin: 0;">
                        ✅ <strong>Prepare for Testing:</strong> Ensure you have diverse experience before taking your driving test.
                    </p>
                </div>
            </section>
            
        </div>
    </main>
    
    <footer class="main-footer">
        <div class="container">
            <p>&copy; 2025 Supervised Driving Log. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
