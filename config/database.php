<?php
// Database configuration for MySQL
define('DB_HOST', 'localhost');  // Usually localhost on cPanel
define('DB_NAME', 'xynx4483_ypt_study_app');  // Your cPanel database name
define('DB_USER', 'xynx4483_ypt_user');    // Your cPanel database username
define('DB_PASS', 'Cl[[T^QQgI[*kGIA');    // Your cPanel database password

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    // In production, log the error instead of displaying it
    error_log("Database connection failed: " . $e->getMessage());
    die("Database connection failed. Please check your configuration.");
}
?>