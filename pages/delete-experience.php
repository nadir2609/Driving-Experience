<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Require login
User::requireLogin();

// Get experience ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!$id) {
    $_SESSION['error'] = 'Invalid experience ID.';
    header('Location: summary.php');
    exit();
}

$userId = User::getCurrentUserId();

// Verify the experience exists and belongs to current user
$experienceModel = new DrivingExperience();
$experience = $experienceModel->find($id);

if (!$experience || $experience['user_id'] != $userId) {
    $_SESSION['error'] = 'Experience not found or access denied.';
    header('Location: summary.php');
    exit();
}

// Delete the experience
if ($experienceModel->delete($id)) {
    $_SESSION['success'] = 'Driving experience deleted successfully.';
} else {
    $_SESSION['error'] = 'Error deleting experience. Please try again.';
}

header('Location: summary.php');
exit();
?>
