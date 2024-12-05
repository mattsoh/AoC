<?php
// public/challenge_input.php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Include the backend logic
require_once '../src/challenge_input.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Puzzle Input</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <pre style="word-wrap: break-word; white-space: pre-wrap;
    <?php 
    global $puzzle_input;
    echo htmlspecialchars($puzzle_input); ?></pre>
</body>
</html>