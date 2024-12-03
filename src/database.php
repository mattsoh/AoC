<?php
// src/database.php

// Retrieve environment variables
$dbHost = getenv('DB_HOST') ?: '34.142.98.105';
$dbPort = getenv('DB_PORT') ?: 3306;
$dbUser = getenv('DB_USER') ?: 'mattsoh';
$dbPass = getenv('DB_PASS') ?: 'mattsoh';
$dbName = getenv('DB_NAME') ?: 'aoc';

try {
    // Data Source Name
    $dsn = "mysql:host=$dbHost;port=$dbPort;dbname=$dbName;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    // Create a new PDO instance
    $db = new PDO($dsn, $dbUser, $dbPass, $options);
    echo "Connected to the database successfully!";

    // Create tables if they do not exist
    $db->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(255) NOT NULL,
            password VARCHAR(255) NOT NULL,
            year_group INT NOT NULL CHECK (year_group BETWEEN 7 AND 13)
        );

        CREATE TABLE IF NOT EXISTS challenges (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );

        CREATE TABLE IF NOT EXISTS user_challenges (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            challenge_id INT NOT NULL,
            assigned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            day INT,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (challenge_id) REFERENCES challenges(id) ON DELETE CASCADE,
            UNIQUE(user_id, challenge_id)
        );

        CREATE TABLE IF NOT EXISTS leaderboard (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            username VARCHAR(255) NOT NULL,
            total_score INT NOT NULL
        );

        CREATE TABLE IF NOT EXISTS answers (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            day INT NOT NULL,
            score INT NOT NULL,
            submission_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id)
        );
    ");

    // Create index if it does not exist
    $indexExists = $db->query("SHOW INDEX FROM leaderboard WHERE Key_name = 'idx_leaderboard_total_score'")->fetch();
    if (!$indexExists) {
        $db->exec("CREATE INDEX idx_leaderboard_total_score ON leaderboard (total_score DESC)");
    }
} catch (PDOException $e) {
    throw new PDOException($e->getMessage(), (int)$e->getCode());
}
?>