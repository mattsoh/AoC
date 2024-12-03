<?php
// Start the session and include the database connection
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'database.php'; // Adjust the path as necessary

// Initialize error message variable
$error_message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    global $db;

    // Get and validate the year group
    $year_group = $_POST['year_group'] ?? '';
    if (empty($year_group) || $year_group < 7 || $year_group > 13) {
        $error_message = 'Please enter a valid year group between 7 and 13.';
    } else {
        // Keep generating username until a unique one is found
        do {
            // Generate random username
            $colors = ['Red', 'Blue', 'Green', 'Yellow', 'Purple', 'Orange', 'Pink', 'Brown', 'Black', 'White'];
            $words = ['Lion', 'Tiger', 'Bear', 'Wolf', 'Eagle', 'Shark', 'Panther', 'Leopard', 'Falcon', 'Hawk'];
            $username = $colors[array_rand($colors)] . $words[array_rand($words)] . $words[array_rand($words)];
        
            // Check if the username already exists
            $stmt = $db->prepare('SELECT COUNT(*) FROM users WHERE username = ?');
            $stmt->execute([$username]);
            $username_exists = $stmt->fetchColumn() > 0;
        } while ($username_exists);

        // Generate random 6-digit PIN as the password
        $password = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

        // Insert the new user into the database without hashing the password
        $stmt = $db->prepare('INSERT INTO users (username, password, year_group) VALUES (?, ?, ?)');
        if ($stmt->execute([$username, $password, $year_group])) {
            // Store user ID in session or perform other actions as needed
            $_SESSION['user_id'] = $db->lastInsertId();
            // You can redirect the user or send a response as needed
            echo "Your username is $username, and your password is $password. Make sure you write down your username and your password in case you lose it.";
            echo "<br><a href='dashboard.php'>Go to Dashboard</a>";
        exit;
        } else {
            $error_message = 'Registration failed. Please try again.';
        }
    }
}

// Handle error messages or other backend logic as needed
?>

