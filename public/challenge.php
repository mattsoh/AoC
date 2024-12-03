<?php
// Get the challenge ID from the URL (default to 1 if not provided)
$challenge_id = isset($_GET['day']) ? (int) $_GET['day'] : 1;
if ($challenge_id < 1 || $challenge_id > 25) {
    $challenge_id = 1;
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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Challenge <?php echo htmlspecialchars($challenge_id); ?></title>
</head>
<body>
    <h1>Challenge <?php echo htmlspecialchars($challenge_id); ?></h1>
    <p><?php echo nl2br(htmlspecialchars($problem_description)); ?></p>
    <p>Get your puzzle input <a href="/challenge_input.php?day=<?php echo htmlspecialchars($challenge_id); ?>">here</a>. Triple-click to select all!</p>
    <form method="POST" action="/submit_answer.php?day=<?php echo htmlspecialchars($challenge_id); ?>">
        <label for="answer">Your Answer:</label><br>
        <input type="text" name="user_answer" id="user_answer" required><br><br>
        <button type="submit">Submit Answer</button>
    </form>
</body>
</html>