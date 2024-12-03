<?php
// src/submit_answer.php

// Assuming $day and $user_answer are already validated and available from the parent script

// Optionally, verify that the challenge exists for the specified day
require_once 'database.php'; // Adjust the path as necessary
global $day;
$stmt = $db->prepare('SELECT challenge_id FROM user_challenges WHERE user_id = ? AND day = ?');
$stmt->execute([$_SESSION['user_id'], $day]);
$challenge = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$challenge) {
    die('Specified challenge does not exist. Maybe read the input first?');
}

$challenge_id = $challenge['challenge_id'];

// Path to the expected output file
$expected_output_file = "../challenges/{$day}/test/{$challenge_id}.out";

if (!file_exists($expected_output_file)) {
    die('Expected output file does not exist.');
}

// Read the expected output
$expected_output = file_get_contents($expected_output_file);

// Compare the user's submitted answer with the expected output
if (trim($user_answer) === trim($expected_output)) {
    require_once 'database.php'; // Adjust the path as necessary
    // Check if the answer has already been submitted for the day by the user
    $stmt = $db->prepare('SELECT * FROM answers WHERE user_id = ? AND day = ?');
    $stmt->execute([$_SESSION['user_id'], $day]);
    $existing_answer = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existing_answer) {
        echo 'Already submitted.<br>';
        echo '<a href="/dashboard.php">Back to dashboard</a>';
        exit;
    }
    // Calculate the score based on the time of submission
    $submission_time = new DateTime();
    $start_time = new DateTime("2024-12-{$day} 08:30:00");
    $interval = $start_time->diff($submission_time);
    $minutes = $interval->days * 24 * 60 + $interval->h * 60 + $interval->i;

    if ($minutes <= 10) {
        $score = 1000 - ($minutes * 50);
    } elseif ($minutes <= 60) {
        $score = 1000 - (10 * 50) - (floor(($minutes - 10) / 10) * 100);
    } else {
        $score = 1000 - (10 * 50) - (5 * 100) - (floor(($minutes - 60) / 60) * 100);
    }

    if ($score < 0) {
        $score = 5;
    }
    // Update the leaderboard
    $stmt = $db->prepare('SELECT SUM(score) as total_score FROM answers WHERE user_id = ?');
    $stmt->execute([$_SESSION['user_id']]);
    $total_score = $stmt->fetch(PDO::FETCH_ASSOC)['total_score'];
    if (!$total_score) {
        $total_score = 0;
    }
    $stmt = $db->prepare('SELECT * FROM leaderboard WHERE user_id = ?');
    $stmt->execute([$_SESSION['user_id']]);
    $leaderboard_entry = $stmt->fetch(PDO::FETCH_ASSOC);
    $total_score += $score;
    if ($leaderboard_entry) {
        $stmt = $db->prepare('UPDATE leaderboard SET total_score = ? WHERE user_id = ?');
        $stmt->execute([$total_score, $_SESSION['user_id']]);
    } else {
        // $stmt = $db->prepare('SELECT username FROM users WHERE id = ?');
        // $stmt->execute([$_SESSION['user_id']]);
        // $username = $stmt->fetch(PDO::FETCH_ASSOC)['username'];
        $stmt = $db->prepare('INSERT INTO leaderboard (user_id, total_score) VALUES (?, ?)');
        $stmt->execute([$_SESSION['user_id'], $total_score]);
    }
    // Insert the answer into the answers table
    $stmt = $db->prepare('INSERT INTO answers (user_id, day, score, submission_time) VALUES (?, ?, ?, ?)');
    $stmt->execute([$_SESSION['user_id'], $day, $score, $submission_time->format('Y-m-d H:i:s')]);
    echo 'Correct answer!<br>';
    echo 'You have earned ' . $score . ' points.<br>';
    echo 'You are now on '.$total_score.' points.<br>';
    echo '<a href="/dashboard.php">Back to dashboard</a><br>';
} else {
    echo 'Incorrect answer.';
    echo "<a href=\"/challenge.php?day={$day}\">Back to challenge</a>";
}
?>