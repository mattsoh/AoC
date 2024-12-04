<?php
require_once('database.php');
// global $challenge_id;
// Query to get the release date and time for the challenge
$query = $db->prepare("SELECT release_day FROM challenges WHERE id = :challenge_id");
$query->execute(['challenge_id' => $challenge_id]);
$release_day = $query->fetchColumn();

// Check if the release day is set and compare it with the current date and time
if ($release_day) {
    $release_datetime = new DateTime('2024-12-'.$release_day . ' 08:30:00');
    $current_datetime = new DateTime();

    if ($release_datetime > $current_datetime) {
        echo "Challenges not unlocked yet.";
        exit;
    }
}
// Path to the challenge description file (outside the public directory)
$challenge_file_path = __DIR__ . "/../challenges/{$challenge_id}/desc.txt";

// Check if the file exists
if (file_exists($challenge_file_path)) {
    // Read the content of the challenge description file
    $problem_description = file_get_contents($challenge_file_path);
} else {
    $problem_description = "Challenge not found.";
}
?>