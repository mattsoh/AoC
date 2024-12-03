<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
    $error_message = '';
}
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

// Include the backend logic from the src folder (this includes processing the form)
require_once '../src/register.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="styles.css"> <!-- Ensure you have this file for styling -->
</head>
<body>
    <h1>Register</h1>


<!-- Registration Form -->
<form method="POST" action="register.php">
    <label for="year_group">Year Group (7 to 13):</label>
    <input type="number" id="year_group" name="year_group" min="7" max="13" required>
    <button type="submit">Register</button>
</form>
<?php if ($error_message): ?>
    <div style="color: red;"><?= htmlspecialchars($error_message) ?></div>
<?php endif; ?>
<p>Already have an account? <a href="login.php">Login here</a></p>
</body>
</html>
</body>
</html>
