<?php
session_start();

// Facebook app info
$appId = '1518770855805260';
$redirectUri = 'http://localhost/DFF.VN/public/facebook-callback.php';
$scope = 'public_profile,email';

// Tạo state để bảo mật CSRF
$state = bin2hex(random_bytes(8));
$_SESSION['fb_state'] = $state;

// Tạo URL login
$loginUrl = "https://www.facebook.com/v16.0/dialog/oauth?" . http_build_query([
    'client_id' => $appId,
    'redirect_uri' => $redirectUri,
    'state' => $state,
    'scope' => $scope,
    'response_type' => 'code'
]);

header('Location: ' . $loginUrl);
exit();
