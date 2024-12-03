<?php
// Connect to the database
$db = new PDO('sqlite:database.db');

// Create users table
$db->exec("CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT UNIQUE NOT NULL,
    password_hash TEXT NOT NULL
)");

// Create challenges table
$db->exec("CREATE TABLE IF NOT EXISTS challenges (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT NOT NULL,
    description TEXT NOT NULL,
    input_data TEXT NOT NULL,
    correct_answer TEXT NOT NULL
)");

// Create sample challenges
$db->exec("INSERT INTO challenges (title, description, input_data, correct_answer) VALUES 
    ('Challenge 1', 'Find the sum of all numbers from 1 to 100', '1, 2, 3, ..., 100', '5050')");
?>
