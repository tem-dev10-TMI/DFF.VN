<?php
session_start();
require_once __DIR__ . '/../config/config.php';

// Tạo state để bảo mật CSRF
$state = bin2hex(random_bytes(8));
$_SESSION['fb_state'] = $state;

// Tạo URL login
$loginUrl = "https://www.facebook.com/v16.0/dialog/oauth?" . http_build_query([
    'client_id' => FACEBOOK_APP_ID,
    'redirect_uri' => FACEBOOK_REDIRECT_URI,
    'state' => $state,
    'scope' => FACEBOOK_SCOPE,
    'response_type' => 'code'
]);

header('Location: ' . $loginUrl);
exit();
