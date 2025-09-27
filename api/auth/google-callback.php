<?php
session_start();
require_once '../../config/database.php';
require_once '../../config/oauth.php';

// Check for authorization code
if (!isset($_GET['code']) || !isset($_GET['state'])) {
    header('Location: ../../pages/login.php?error=oauth_failed');
    exit;
}

// Verify state parameter (CSRF protection)
if ($_GET['state'] !== $_SESSION['oauth_state']) {
    header('Location: ../../pages/login.php?error=oauth_failed');
    exit;
}

try {
    // Exchange authorization code for access token
    $token_url = 'https://oauth2.googleapis.com/token';
    $token_data = array(
        'client_id' => GOOGLE_CLIENT_ID,
        'client_secret' => GOOGLE_CLIENT_SECRET,
        'redirect_uri' => GOOGLE_REDIRECT_URI,
        'grant_type' => 'authorization_code',
        'code' => $_GET['code']
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $token_url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($token_data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
    
    $response = curl_exec($ch);
    curl_close($ch);

    $token_info = json_decode($response, true);

    if (!isset($token_info['access_token'])) {
        throw new Exception('Failed to get access token');
    }

    // Get user info from Google
    $user_info_url = 'https://www.googleapis.com/oauth2/v2/userinfo?access_token=' . $token_info['access_token'];
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $user_info_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $user_response = curl_exec($ch);
    curl_close($ch);

    $user_info = json_decode($user_response, true);

    if (!isset($user_info['email'])) {
        throw new Exception('Failed to get user info');
    }

    // Check if user exists
    $stmt = $pdo->prepare("SELECT id, first_name, last_name, status FROM users WHERE email = ?");
    $stmt->execute([$user_info['email']]);
    $existing_user = $stmt->fetch();

    if ($existing_user) {
        // User exists, log them in
        if ($existing_user['status'] !== 'active') {
            header('Location: ../../pages/login.php?error=inactive');
            exit;
        }

        $_SESSION['user_id'] = $existing_user['id'];
        $_SESSION['user_email'] = $user_info['email'];
        $_SESSION['user_name'] = $existing_user['first_name'] . ' ' . $existing_user['last_name'];

        // Update last login
        $stmt = $pdo->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
        $stmt->execute([$existing_user['id']]);

    } else {
        // Create new user
        $names = explode(' ', $user_info['name'], 2);
        $firstName = $names[0];
        $lastName = isset($names[1]) ? $names[1] : '';

        $stmt = $pdo->prepare("
            INSERT INTO users (first_name, last_name, email, password, status, oauth_provider, oauth_id, created_at, last_login) 
            VALUES (?, ?, ?, '', 'active', 'google', ?, NOW(), NOW())
        ");
        $stmt->execute([$firstName, $lastName, $user_info['email'], $user_info['id']]);

        $userId = $pdo->lastInsertId();

        // Initialize user stats
        $stmt = $pdo->prepare("
            INSERT INTO user_stats (user_id, total_study_time, sessions_count, points, level, streak_days, created_at) 
            VALUES (?, 0, 0, 0, 1, 0, NOW())
        ");
        $stmt->execute([$userId]);

        $_SESSION['user_id'] = $userId;
        $_SESSION['user_email'] = $user_info['email'];
        $_SESSION['user_name'] = $firstName . ' ' . $lastName;
    }

    // Clean up session
    unset($_SESSION['oauth_state']);

    header('Location: ../../index.php?welcome=1');
    exit;

} catch (Exception $e) {
    error_log('Google OAuth error: ' . $e->getMessage());
    header('Location: ../../pages/login.php?error=oauth_failed');
    exit;
}
?>