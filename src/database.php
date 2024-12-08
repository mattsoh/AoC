<?php
// src/database.php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Retrieve environment variables
$dbHost = getenv('DB_HOST');
$dbPort = getenv('DB_PORT');
$dbUser = getenv('DB_USER');
$dbPass = getenv('DB_PASS');
$dbName = getenv('DB_NAME');

$ssl_ca = getenv('MYSQL_SSL_CA');
$ssl_cert = getenv('MYSQL_SSL_CERT');
$ssl_key = getenv('MYSQL_SSL_KEY');

try {
    // Data Source Name (DSN) for PDO with MySQL
    $dsn = "mysql:host=$dbHost;port=$dbPort;dbname=$dbName;charset=utf8mb4";
    
    // SSL Options for PDO
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
        // Remove SSL options for testing
    ];

    // Create a new PDO instance with SSL connection options
    $db = new PDO($dsn, $dbUser, $dbPass, $options);
    // echo "Connected to the database successfully!<br>";

    // Create index if it does not exist
    $indexExists = $db->query("SHOW INDEX FROM leaderboard WHERE Key_name = 'idx_leaderboard_total_score'")->fetch();
    if (!$indexExists) {
        $db->exec("CREATE INDEX idx_leaderboard_total_score ON leaderboard (total_score DESC)");
    }
} catch (PDOException $e) {
    // Handle connection errors
    throw new PDOException($e->getMessage(), (int)$e->getCode());
}
?>
