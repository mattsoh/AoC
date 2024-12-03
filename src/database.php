<?php
// Create a PDO connection to the SQLite database
try {
    $db = new PDO('sqlite:' . __DIR__ . '/database.db');
  // Adjust this path if necessary
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Database connection failed: " . $e->getMessage();
    exit;
}
?>
