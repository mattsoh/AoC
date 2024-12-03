<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Include the backend logic from src to fetch challenges
require_once '../src/get_challenges.php';
[$challenges, $user] = getChallenges();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Challenges</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<script>
        // Add event listeners to make rows clickable
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.clickable-row').forEach(row => {
                row.addEventListener('click', () => {
                    window.location.href = row.dataset.href;
                });
            });
        });
    </script>
    <div class="container">
        <h1>Welcome, <?php echo $user?></h1>
        <a href="logout.php" class="logout-btn">Logout</a>

        <h2>Daily Challenges</h2>
        <div class="challenges-list">
        <table>
        <thead>
            <tr>
                <th>Day</th>
                <th>Name</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($challenges as $challenge): ?>
                <tr class="clickable-row" data-href="/challenge.php?day=<?php echo $challenge['id']; ?>">
                    <td><?php echo $challenge['id']; ?></td>
                    <td><?php echo htmlspecialchars($challenge['title']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
        </div>
    </div>
</body>
</html>
