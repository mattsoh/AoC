<?php
// Database connection
require_once '../src/database.php'; // Make sure to include the database connection file

session_start();

// Fetch the top 10 leaderboard data sorted by total_score in descending order
// $stmt = $db->prepare('SELECT * FROM leaderboard ORDER BY total_score DESC LIMIT 10');
$stmt = $db->prepare('SELECT leaderboard.*, users.username FROM leaderboard INNER JOIN users ON leaderboard.user_id = users.id ORDER BY total_score DESC LIMIT 10');
$stmt->execute();
$leaderboard = $stmt->fetchAll();

// Check if the user is logged in and get their score
$user_score = null;
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    
    // Fetch the user's score from the leaderboard table
    $stmt = $db->prepare('SELECT total_score FROM leaderboard WHERE user_id = ?');
    $stmt->execute([$user_id]);
    $user_data = $stmt->fetch();
    
    if ($user_data) {
        $user_score = $user_data['total_score'];
    } else {
        $user_score = 0;
    }
}

// Display the leaderboard
echo "<h1>Leaderboard</h1>";
echo "<a href='logout.php' class='logout-btn'>Logout</a>";
echo "<a href='dashboard.php' class='logout-btn'>Go back to dashboard</a>";
echo "<table align='center' border='1'>";
echo "<tr><th>Rank</th><th>Username</th><th>Total Score</th></tr>";

$rank = 1;
$prev = -1;
$prev_rank = 1;
foreach ($leaderboard as $entry) {
    echo "<tr>";
    if ($prev != $entry['total_score']) {
        echo "<td>{$rank}</td>";
        $prev_rank = $rank;
    }
    echo "<td>" . htmlspecialchars($entry['username']) . "</td>";
    echo "<td>{$entry['total_score']}</td>";    
    echo "</tr>";
    $rank++;
}

// Optionally display the logged-in user's score
if ($user_score !== null) {
    echo "<p>Your current score: {$user_score}</p>";
}

echo "</table>";
?>