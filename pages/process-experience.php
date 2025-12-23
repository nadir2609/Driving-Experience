<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: add-experience.php');
    exit();
}

// Create DrivingExperience object with form data
$experience = new DrivingExperience([
    'experience_date' => sanitizeInput($_POST['experience_date']),
    'start_time' => sanitizeInput($_POST['start_time']),
    'end_time' => sanitizeInput($_POST['end_time']),
    'kilometers' => floatval($_POST['kilometers']),
    'weather_id' => intval($_POST['weather_id']),
    'traffic_id' => intval($_POST['traffic_id']),
    'road_type_id' => intval($_POST['road_type_id']),
    'surface_quality_id' => intval($_POST['surface_quality_id']),
    'notes' => isset($_POST['notes']) ? sanitizeInput($_POST['notes']) : ''
]);

// Validate the experience
$errors = $experience->validate();

// If there are errors, redirect back with error message
if (!empty($errors)) {
    $_SESSION['error'] = implode('<br>', $errors);
    header('Location: add-experience.php');
    exit();
}

// Save the experience to database using OOP method
if ($experience->save()) {
    $_SESSION['success'] = "Driving experience added successfully! (" . number_format($experience->kilometers, 2) . " km)";
    header('Location: add-experience.php');
} else {
    $_SESSION['error'] = "Error saving experience. Please try again.";
    header('Location: add-experience.php');
}

exit();
?>

