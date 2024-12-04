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

function getChallenges() {
    try {
        global $db;
        $user_id = $_SESSION['user_id'];
        // Fetch challenges from the database
        $stmt = $db->prepare('SELECT * FROM challenges');
        $stmt->execute();
        $challenges = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Fetch the username of the user
        $userd = $db->prepare('SELECT username FROM users WHERE id = :user_id');
        $userd->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $userd->execute();
        $user = $userd->fetch(PDO::FETCH_ASSOC);

        // Filter challenges based on the release date and check if the user has already submitted
        $filteredChallenges = array_filter($challenges, function($challenge) use ($db, $user_id) {
            $releaseDate = new DateTime('2024-12-' . $challenge['release_day'] . ' 08:30:00');
            $currentDate = new DateTime();
            if ($releaseDate > $currentDate) {
                return false;
            }
            return true;

        });
        // Check if the user has already submitted for this challenge
        // $stmt = $db->prepare('SELECT COUNT(*) FROM user_challenges WHERE user_id = ? AND day = ?');
        // $stmt->execute([$user_id, $challenge['id']]);
        // $submissionExists = $stmt->fetchColumn() > 0;

        // return !$submissionExists;
        foreach ($filteredChallenges as &$challenge) {
            echo $challenge['id'];
            $stmt = $db->prepare('SELECT COUNT(*) FROM user_challenges WHERE user_id = ? AND day = ?');
            $stmt->execute([$user_id, $challenge['id']]);
            $submissionExists = $stmt->fetchColumn() > 0;
            $challenge['submission_exists'] = $submissionExists;
        }

        // Return filtered challenges and username as JSON
        return [$filteredChallenges, $user['username']];
    } catch (PDOException $e) {
        // Handle potential database errors
        return json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}
?>
