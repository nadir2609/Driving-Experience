<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Statistics and charts for driving experiences">
    <title>Statistics - Supervised Driving Log</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
</head>
<body>
    <?php 
    require_once '../includes/config.php';
    require_once '../includes/functions.php';
    
    // Require login
    User::requireLogin();
    
    // Use OOP to get statistics
    $experienceModel = new DrivingExperience();
    $weatherStats = $experienceModel->getStatsByWeather();
    $trafficStats = $experienceModel->getStatsByTraffic();
    $roadTypeStats = $experienceModel->getStatsByRoadType();
    $surfaceStats = $experienceModel->getStatsBySurfaceQuality();
    $totalKm = $experienceModel->getTotalKilometers();
    ?>
    
    <header class="main-header">
        <div class="container">
            <h1>🚗 Supervised Driving Log</h1>
            <nav class="main-nav">
                <a href="../index.php">Home</a>
                <a href="add-experience.php">Add Experience</a>
                <a href="summary.php">Summary</a>
                <a href="statistics.php" class="active">Statistics</a>
                <a href="../logout.php" style="margin-left: auto;">Logout (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a>
            </nav>
        </div>
    </header>
    
    <main class="main-content">
        <div class="container">
            <section class="statistics-section">
                <h2>📊 Driving Statistics</h2>
                
                <!-- Weather Statistics -->
                <div class="stats-block">
                    <h3>Weather Conditions</h3>
                    <div class="stats-layout">
                        <div class="stats-table-container">
                            <table class="stats-table">
                                <thead>
                                    <tr>
                                        <th>Condition</th>
                                        <th>Trips</th>
                                        <th>Total KM</th>
                                        <th>Avg KM</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($weatherStats as $stat): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($stat['weather']); ?></td>
                                        <td><?php echo $stat['count']; ?></td>
                                        <td><?php echo number_format($stat['total_km'] ?? 0, 2); ?></td>
                                        <td><?php echo number_format($stat['avg_km'] ?? 0, 2); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="chart-container">
                            <canvas id="weatherChart"></canvas>
                        </div>
                    </div>
                </div>
                
                <!-- Traffic Statistics -->
                <div class="stats-block">
                    <h3>Traffic Density</h3>
                    <div class="stats-layout">
                        <div class="stats-table-container">
                            <table class="stats-table">
                                <thead>
                                    <tr>
                                        <th>Density</th>
                                        <th>Trips</th>
                                        <th>Total KM</th>
                                        <th>Avg KM</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($trafficStats as $stat): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($stat['traffic']); ?></td>
                                        <td><?php echo $stat['count']; ?></td>
                                        <td><?php echo number_format($stat['total_km'] ?? 0, 2); ?></td>
                                        <td><?php echo number_format($stat['avg_km'] ?? 0, 2); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="chart-container">
                            <canvas id="trafficChart"></canvas>
                        </div>
                    </div>
                </div>
                
                <!-- Road Type Statistics -->
                <div class="stats-block">
                    <h3>Road Types</h3>
                    <div class="stats-layout">
                        <div class="stats-table-container">
                            <table class="stats-table">
                                <thead>
                                    <tr>
                                        <th>Type</th>
                                        <th>Trips</th>
                                        <th>Total KM</th>
                                        <th>Avg KM</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($roadTypeStats as $stat): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($stat['road_type']); ?></td>
                                        <td><?php echo $stat['count']; ?></td>
                                        <td><?php echo number_format($stat['total_km'] ?? 0, 2); ?></td>
                                        <td><?php echo number_format($stat['avg_km'] ?? 0, 2); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="chart-container">
                            <canvas id="roadTypeChart"></canvas>
                        </div>
                    </div>
                </div>
                
                <!-- Surface Quality Statistics -->
                <div class="stats-block">
                    <h3>Road Surface Quality</h3>
                    <div class="stats-layout">
                        <div class="stats-table-container">
                            <table class="stats-table">
                                <thead>
                                    <tr>
                                        <th>Quality</th>
                                        <th>Trips</th>
                                        <th>Total KM</th>
                                        <th>Avg KM</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($surfaceStats as $stat): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($stat['surface_quality']); ?></td>
                                        <td><?php echo $stat['count']; ?></td>
                                        <td><?php echo number_format($stat['total_km'] ?? 0, 2); ?></td>
                                        <td><?php echo number_format($stat['avg_km'] ?? 0, 2); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="chart-container">
                            <canvas id="surfaceChart"></canvas>
                        </div>
                    </div>
                </div>
                
            </section>
        </div>
    </main>
    
    <footer class="main-footer">
        <div class="container">
            <p>&copy; 2025 Supervised Driving Log. All rights reserved.</p>
        </div>
    </footer>
    
    <script>
        // Prepare data for charts
        const weatherData = <?php echo json_encode($weatherStats); ?>;
        const trafficData = <?php echo json_encode($trafficStats); ?>;
        const roadTypeData = <?php echo json_encode($roadTypeStats); ?>;
        const surfaceData = <?php echo json_encode($surfaceStats); ?>;
        
        // Chart colors
        const colors = [
            '#3B82F6', '#8B5CF6', '#EC4899', '#F59E0B', '#10B981',
            '#6366F1', '#14B8A6', '#F97316', '#EF4444', '#84CC16'
        ];
        
        // Weather Chart
        new Chart(document.getElementById('weatherChart'), {
            type: 'bar',
            data: {
                labels: weatherData.map(item => item.weather),
                datasets: [{
                    label: 'Total Kilometers',
                    data: weatherData.map(item => item.total_km || 0),
                    backgroundColor: colors[0],
                    borderColor: colors[0],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    title: { display: false }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
        
        // Traffic Chart
        new Chart(document.getElementById('trafficChart'), {
            type: 'doughnut',
            data: {
                labels: trafficData.map(item => item.traffic),
                datasets: [{
                    label: 'Total Kilometers',
                    data: trafficData.map(item => item.total_km || 0),
                    backgroundColor: colors,
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
        
        // Road Type Chart
        new Chart(document.getElementById('roadTypeChart'), {
            type: 'bar',
            data: {
                labels: roadTypeData.map(item => item.road_type),
                datasets: [{
                    label: 'Total Kilometers',
                    data: roadTypeData.map(item => item.total_km || 0),
                    backgroundColor: colors[1],
                    borderColor: colors[1],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
        
        // Surface Quality Chart
        new Chart(document.getElementById('surfaceChart'), {
            type: 'pie',
            data: {
                labels: surfaceData.map(item => item.surface_quality),
                datasets: [{
                    label: 'Total Kilometers',
                    data: surfaceData.map(item => item.total_km || 0),
                    backgroundColor: colors,
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    </script>
</body>
</html>
