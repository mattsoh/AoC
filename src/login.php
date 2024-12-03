<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include database connection
require_once 'database.php'; // Assuming you have a separate file to handle database connection

// Initialize error message variable
$error_message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    global $db;
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Check if the user exists
    $stmt = $db->prepare('SELECT * FROM users WHERE username = ?');
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    // If user exists and password is correct
    if ($user && $password ==  $user['password']) {
        $_SESSION['user_id'] = $user['id'];
        header('Location: /dashboard.php'); // Redirect to dashboard or another page
        exit;
    } else {
        // Set error message if credentials are invalid
        $error_message = 'Invalid credentials! Please try again.';
    }
}

// Return the error message if needed (for example, for API purposes)
?>
