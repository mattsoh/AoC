<?php
// Start the session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Redirect if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../public/login.php');  // Adjusted to the correct login path
    exit;
}

// Include the database connection
require_once 'database.php'; // Ensure the correct path to your database file
function getChallenges(){
    global $db;
    // Fetch challenges from the database
    try {
        // Query to fetch challenges
        $stmt = $db->query('SELECT * FROM challenges');
        $challenges = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $user_id = $_SESSION['user_id'];
        $userd = $db->prepare('SELECT username FROM users WHERE id = :user_id');
        $userd->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $userd->execute();
        $user = $userd->fetch(PDO::FETCH_ASSOC);
        
        // Return challenges as JSON
        return [$challenges, $user['username']];
        $filteredChallenges = array_filter($challenges, function($challenge) {
        $releaseDate = new DateTime('2024-12-' . $challenge['release_day'] . ' 08:30:00');
        $cutoffDate = new DateTime();
        return $releaseDate >= $cutoffDate;
        });
    return [$filteredChallenges, $user['username']];
    } catch (PDOException $e) {
        // Handle potential database errors
        return json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}
?>
