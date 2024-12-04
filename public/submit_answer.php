<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include database connection
// Initialize error message variable
$error_message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $day = isset($_GET['day']) ? (int)$_GET['day'] : 0;
    $user_answer = isset($_POST['user_answer']) ? trim($_POST['user_answer']) : '';
    // Validate 'day'
    if ($day < 1 || $day > 25) {
        $error_message = 'Invalid day specified.';
    } else {
        // Include the submit_answer logic
        require_once '../src/submit_answer.php';
    }
} else{
    echo "Don't even try...";
}
?>