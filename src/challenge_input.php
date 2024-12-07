<?php
// src/challenge_input.php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'database.php'; // Adjust the path if necessary

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
// Validate the day parameter
$day = isset($_GET['day']) ? (int)$_GET['day'] : null;

if ($day === null || $day < 1 || $day > 25) {
    echo 'Invalid day.';
    exit;
}
// Fetch the release day for the challenge
$stmt = $db->prepare('SELECT release_day FROM challenges WHERE id = ?');
$stmt->execute([$day]);
$release_day = $stmt->fetchColumn();

if (!$release_day) {
    echo 'Challenge not found (yet).';
    exit;
}
// Check if the current date is after the release date
$release_date = new DateTime("2024-12-{$release_day} 08:30:00", new DateTimeZone('UTC'));
$current_date = new DateTime('now', new DateTimeZone('UTC'));

if ($current_date < $release_date) {
    echo 'Please wait until the challenge unlocks.';
    exit;
}
// echo 'Challenge not found.';
// exit;
$user_id = $_SESSION['user_id'];
// Function to assign a random challenge to the user
function assignRandomchallenge($db, $user_id, $day) {
    // Generate a random challenge ID between 1 and 100
    $challenge_id = rand(1, 100);
    // Assign the challenge to the user
    $stmt = $db->prepare('INSERT INTO user_challenges (user_id, challenge_id, day) VALUES (?, ?,?)');
    $stmt->execute([$user_id, $challenge_id,$day]);

    return $challenge_id;
}
// Fetch the assigned challenge ID
$stmt = $db->prepare('SELECT challenge_id FROM user_challenges WHERE user_id = ? AND day = ?');
$stmt->execute([$user_id, $day]);
$challenge_id = $stmt->fetchColumn();

if (!$challenge_id) {
    $challenge_id = assignRandomchallenge($db, $user_id, $day);
}
// Validate the day parameter
$day = isset($_GET['day']) ? (int)$_GET['day'] : null;

if ($day === null || $day < 1 || $day > 25) {
    echo 'Invalid day.';
    exit;
}

// Define the file path
$file_path =  "https://storage.googleapis.com/aoc-challenges/challenges/{$day}/test/{$challenge_id}.in";
// Check if the challenge input file exists
$headers = get_headers($file_path);
if ($headers && strpos($headers[0], '200') !== false) {
    $challenge_input = file_get_contents($file_path);
} else {
    $challenge_input = 'challenge input file not found.';
}
echo nl2br($challenge_input);
?>