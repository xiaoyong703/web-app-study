<?php
session_start();
require_once '../../config/oauth.php';

// Build Google OAuth URL
$params = array(
    'client_id' => GOOGLE_CLIENT_ID,
    'redirect_uri' => GOOGLE_REDIRECT_URI,
    'scope' => 'email profile',
    'response_type' => 'code',
    'state' => bin2hex(random_bytes(16)) // CSRF protection
);

$_SESSION['oauth_state'] = $params['state'];

$google_auth_url = 'https://accounts.google.com/o/oauth2/auth?' . http_build_query($params);

// Redirect to Google
header('Location: ' . $google_auth_url);
exit;
?>