<?php
// Simple script to create a test user
require_once 'config/database.php';

$email = 'test@example.com';
$password = 'password123';
$firstName = 'Test';
$lastName = 'User';

try {
    // Check if user already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    
    if ($stmt->fetch()) {
        echo "User already exists: $email\n";
    } else {
        // Create new user
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (email, password, first_name, last_name, status, created_at) VALUES (?, ?, ?, ?, 'active', NOW())");
        $stmt->execute([$email, $hashedPassword, $firstName, $lastName]);
        
        echo "Test user created successfully!\n";
        echo "Email: $email\n";
        echo "Password: $password\n";
    }
    
    // List all users
    echo "\nAll users in database:\n";
    $stmt = $pdo->query("SELECT id, email, first_name, last_name, status, created_at FROM users");
    while ($user = $stmt->fetch()) {
        echo "ID: {$user['id']}, Email: {$user['email']}, Name: {$user['first_name']} {$user['last_name']}, Status: {$user['status']}, Created: {$user['created_at']}\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>