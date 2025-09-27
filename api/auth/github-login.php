<?php
session_start();
require_once '../../config/oauth.php';

// Build GitHub OAuth URL
$params = array(
    'client_id' => GITHUB_CLIENT_ID,
    'redirect_uri' => GITHUB_REDIRECT_URI,
    'scope' => 'user:email',
    'state' => bin2hex(random_bytes(16)) // CSRF protection
);

$_SESSION['oauth_state'] = $params['state'];

$github_auth_url = 'https://github.com/login/oauth/authorize?' . http_build_query($params);

// Redirect to GitHub
header('Location: ' . $github_auth_url);
exit;
?>