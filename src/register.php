<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include database connection
require_once '../src/database.php'; // Adjust the path as necessary

// Initialize error message variable
$error_message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    global $db;
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
    $year_group = $_POST['year_group'] ?? '';

    // Validate year_group and password length
    if ($year_group < 7 || $year_group > 13) {
        $error_message = 'Year group must be between 7 and 13.';
    } elseif (strlen($password) != 6) {
        echo $password;
        $error_message = 'Password must be exactly 6 characters long.';
    } else {
        // Insert the new user into the database
        $stmt = $db->prepare('INSERT INTO users (username, password, year_group) VALUES (?, ?, ?)');
        if ($stmt->execute([$username, $password, $year_group])) {
            // Retrieve the user_id of the newly inserted user
            $user_id = $db->lastInsertId();

            // Insert the new user into the leaderboard with an initial score of 0
            $stmt = $db->prepare('INSERT INTO leaderboard (user_id, total_score) VALUES (?, 0)');
            $stmt->execute([$user_id]);

            // Store user ID in session or perform other actions as needed
            $_SESSION['user_id'] = $user_id;

            // You can redirect the user or send a response as needed
            echo "Your username is $username, and your password is $password. Make sure you write down your username and your password in case you lose it.";
            echo "<br><a href='dashboard.php'>Go to Dashboard</a>";
            exit;
        } else {
            $error_message = 'Registration failed. Please try again.';
        }
    }
}

// Return the error message if needed (for example, for API purposes)
?>