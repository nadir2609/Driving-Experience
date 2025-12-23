<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Require login
User::requireLogin();

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: summary.php');
    exit();
}

$id = intval($_POST['id']);
$userId = User::getCurrentUserId();

// Verify the experience exists and belongs to current user
$experienceModel = new DrivingExperience();
$existing = $experienceModel->find($id);

if (!$existing || $existing['user_id'] != $userId) {
    $_SESSION['error'] = 'Experience not found or access denied.';
    header('Location: summary.php');
    exit();
}

// Create experience object with updated data
$experience = new DrivingExperience([
    'id' => $id,
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

// Validate
$errors = $experience->validate();

if (!empty($errors)) {
    $_SESSION['error'] = implode('<br>', $errors);
    header('Location: edit-experience.php?id=' . $id);
    exit();
}

// Update
if ($experience->save()) {
    $_SESSION['success'] = 'Driving experience updated successfully!';
    header('Location: summary.php');
} else {
    $_SESSION['error'] = 'Error updating experience. Please try again.';
    header('Location: edit-experience.php?id=' . $id);
}

exit();
?>
