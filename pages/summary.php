<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Summary of all driving experiences">
    <title>Summary - Supervised Driving Log</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
</head>
<body>
    <?php 
    require_once '../includes/config.php';
    require_once '../includes/functions.php';
    
    // Require login
    User::requireLogin();
    
    // Use OOP to get data
    $experienceModel = new DrivingExperience();
    $experiences = $experienceModel->getAllWithDetails();
    $totalKm = $experienceModel->getTotalKilometers();
    ?>
    
    <header class="main-header">
        <div class="container">
            <h1>🚗 Supervised Driving Log</h1>
            <nav class="main-nav">
                <a href="../index.php">Home</a>
                <a href="add-experience.php">Add Experience</a>
                <a href="summary.php" class="active">Summary</a>
                <a href="statistics.php">Statistics</a>
                <a href="../logout.php" style="margin-left: auto;">Logout (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a>
            </nav>
        </div>
    </header>
    
    <main class="main-content">
        <div class="container">
            <section class="summary-section">
                <div class="summary-header">
                    <h2>Driving Experience Summary</h2>
                    <div class="total-km-badge">
                        <span class="badge-label">Total Distance</span>
                        <span class="badge-value"><?php echo number_format($totalKm, 2); ?> km</span>
                    </div>
                </div>
                
                <div class="stats-cards">
                    <div class="stat-card">
                        <div class="stat-icon">📊</div>
                        <div class="stat-info">
                            <div class="stat-value"><?php echo count($experiences); ?></div>
                            <div class="stat-label">Total Trips</div>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon">🛣️</div>
                        <div class="stat-info">
                            <div class="stat-value"><?php echo number_format($totalKm, 2); ?></div>
                            <div class="stat-label">Total Kilometers</div>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon">📈</div>
                        <div class="stat-info">
                            <div class="stat-value">
                                <?php 
                                echo count($experiences) > 0 
                                    ? number_format($totalKm / count($experiences), 2) 
                                    : '0.00'; 
                                ?>
                            </div>
                            <div class="stat-label">Average per Trip</div>
                        </div>
                    </div>
                </div>
                
                <?php if (count($experiences) > 0): ?>
                <!-- Total Kilometers Progress Chart -->
                <div class="progress-chart-c display" id="experiencesTable" style="width:100%
                    <h3>Total Kilometers Progress</h3>
                    <canvas id="totalKmChart" style="max-height: 300px;"></canvas>
                </div>
                <?php endif; ?>
                
                <?php if (count($experiences) > 0): ?>
                <div class="table-responsive">
                    <table class="data-table" id="experiencesTable">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Duration</th>
                                <th>Distance (km)</th>
                                <th>Weather</th>
                                <th>Traffic</th>
                                <th>Road Type</th>
                                <th>Surface</th>
                                <th>Notes</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($experiences as $exp): ?>
                            <tr>
                                <td data-label="Date">
                                    <?php echo formatDate($exp['experience_date']); ?>
                                </td>
                                <td data-label="Time">
                                    <?php echo formatTime($exp['start_time']); ?> - 
                                    <?php echo formatTime($exp['end_time']); ?>
                                </td>
                                <td data-label="Duration">
                                    <?php echo calculateDuration($exp['start_time'], $exp['end_time']); ?>
                                </td>
                                <td data-label="Distance" class="number">
                                    <?php echo number_format($exp['kilometers'], 2); ?>
                                </td>
                                <td data-label="Weather">
                                    <span class="badge badge-weather">
                                        <?php echo htmlspecialchars($exp['weather']); ?>
                                    </span>
                                </td>
                                <td data-label="Traffic">
                                    <span class="badge badge-traffic">
                                        <?php echo htmlspecialchars($exp['traffic']); ?>
                                    </span>
                                </td>
                                <td data-label="Road Type">
                                    <span class="badge badge-road">
                                        <?php echo htmlspecialchars($exp['road_type']); ?>
                                    </span>
                                </td>
                                <td data-label="Surface">
                                    <span class="badge badge-surface">
                                        <?php echo htmlspecialchars($exp['surface_quality']); ?>
                                    </span>
                                </td>
                                <td data-label="Notes" class="notes-cell">
                                    <?php 
                                    $notes = htmlspecialchars($exp['notes']);
                                    echo !empty($notes) ? $notes : '<em>No notes</em>'; 
                                    ?>
                                </td>
                                <td data-label="Actions" class="actions-cell">
                                    <a href="edit-experience.php?id=<?php echo $exp['id']; ?>" class="btn-icon" title="Edit">
                                        ✏️
                                    </a>
                                    <a href="delete-experience.php?id=<?php echo $exp['id']; ?>" 
                                       class="btn-icon btn-delete" 
                                       title="Delete"
                                       onclick="return confirm('Are you sure you want to delete this experience?');">
                                        🗑️
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr class="total-row">
                                <td colspan="3"><strong>Total</strong></td>
                                <td class="number"><strong><?php echo number_format($totalKm, 2); ?> km</strong></td>
                                <td colspan="6"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                
                <div class="table-actions">
                    <button onclick="window.print()" class="btn btn-secondary">🖨️ Print Summary</button>
                    <a href="add-experience.php" class="btn btn-primary">➕ Add New Experience</a>
                </div>
                
                <?php else: ?>
                <div class="empty-state">
                    <div class="empty-icon">🚗</div>
                    <h3>No Driving Experiences Yet</h3>
                    <p>Start logging your supervised driving experiences to track your progress.</p>
                    <a href="add-experience.php" class="btn btn-primary">Add Your First Experience</a>
                </div>
                <?php endif; ?>
                
            </section>
        </div>
    <!-- jQuery (required for DataTables) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    
    <script>
        // Initialize DataTables
        $(document).ready(function() {
            $('#experiencesTable').DataTable({
                order: [[0, 'desc']], // Sort by date descending
                pageLength: 10,
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                language: {
                    search: "Search experiences:",
                    lengthMenu: "Show _MENU_ experiences per page",
                    info: "Showing _START_ to _END_ of _TOTAL_ experiences",
                    infoEmpty: "No experiences to show",
                    infoFiltered: "(filtered from _MAX_ total experiences)",
                    zeroRecords: "No matching experiences found"
                },
                columnDefs: [
                    { targets: [3], className: 'dt-right' }, // Align kilometers right
                    { targets: [8], orderable: false } // Disable sorting on notes
                ]
            });
        });
        
        <?php if (count($experiences) > 0): ?>
        // Total Kilometers Progress Chart
        const dates = [];
        const cumulativeKm = [];
        let runningTotal = 0;
        
        // Sort experiences by date
        const experiencesData = <?php echo json_encode($experiences); ?>;
        experiencesData.sort((a, b) => new Date(a.experience_date) - new Date(b.experience_date));
        
        experiencesData.forEach(exp => {
            dates.push(exp.experience_date);
            runningTotal += parseFloat(exp.kilometers);
            cumulativeKm.push(runningTotal);
        });
        
        new Chart(document.getElementById('totalKmChart'), {
            type: 'line',
            data: {
                labels: dates,
                datasets: [{
                    label: 'Cumulative Kilometers',
                    data: cumulativeKm,
                    borderColor: '#3B82F6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    pointBackgroundColor: '#3B82F6',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    title: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Total: ' + context.parsed.y.toFixed(2) + ' km';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Kilometers'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Date'
                        }
                    }
                }
            }
        });
        <?php endif; ?>
    
    
    <footer class="main-footer">
        <div class="container">
            <p>&copy; 2025 Supervised Driving Log. All rights reserved.</p>
        </div>
    </footer>
    
    <script src="../assets/js/table-features.js"></script>
</body>
</html>
