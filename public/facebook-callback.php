<?php
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../model/user/userModel.php';

// Kiểm tra state
if (!isset($_GET['state']) || $_GET['state'] !== $_SESSION['fb_state']) {
    die('Invalid state');
}

// Kiểm tra code
if (!isset($_GET['code'])) {
    die('Chưa nhận được code!');
}

$code = $_GET['code'];

// Lấy access token
$tokenUrl = "https://graph.facebook.com/v16.0/oauth/access_token?" . http_build_query([
    'client_id' => FACEBOOK_APP_ID,
    'redirect_uri' => FACEBOOK_REDIRECT_URI,
    'client_secret' => FACEBOOK_APP_SECRET,
    'code' => $code
]);

$response = file_get_contents($tokenUrl);
$data = json_decode($response, true);

if (!isset($data['access_token'])) {
    die('Không lấy được access token');
}

$accessToken = $data['access_token'];

// Lấy thông tin user từ Facebook
$userUrl = "https://graph.facebook.com/me?fields=id,name,email,picture.width(200).height(200)&access_token={$accessToken}";
$userResponse = file_get_contents($userUrl);
$user = json_decode($userResponse, true);

// Xử lý username từ name
$username = $user['name'] ?? 'guest';
$username = strtolower(trim($username));
$username = preg_replace('/[^a-z0-9]+/', '', $username);
if (empty($username)) {
    $username = 'user' . time();
}

// Lưu user vào DB hoặc đăng ký mới
$fbUser = UserModel::loginOrRegisterFacebookUser(
    $user['name'] ?? '',
    $user['picture']['data']['url'] ?? null,
    $username,
    $user['email'] ?? null
);

// Lưu session
$_SESSION['user'] = [
    'id'       => $fbUser['id'],
    'name'     => $fbUser['name'],
    'username' => $fbUser['username'],
    'email'    => $fbUser['email'],
    'role'     => $fbUser['role'],
    'avatar_url' => $fbUser['avatar_url']
];


// Các biến session riêng lẻ (nếu cần)
$_SESSION['user_id'] = $fbUser['id'];
$_SESSION['user_name'] = $fbUser['name'];
$_SESSION['user_username'] = $fbUser['username'] ?? $username;
$_SESSION['user_email'] = $fbUser['email'] ?? null;
$_SESSION['user_role'] = $fbUser['role'] ?? 'user';
$_SESSION['user_avatar_url'] = $fbUser['avatar_url'] ?? ($user['picture']['data']['url'] ?? null);


// Redirect về homepage
header('Location: ' . BASE_URL . '/');
exit();
