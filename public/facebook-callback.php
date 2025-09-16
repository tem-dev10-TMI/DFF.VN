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

// Lấy thông tin user (name, email, avatar)
$userUrl = "https://graph.facebook.com/me?fields=id,name,email,picture.width(200).height(200)&access_token={$accessToken}";
$userResponse = file_get_contents($userUrl);
$user = json_decode($userResponse, true);

// Lưu user vào DB hoặc đăng ký mới
$fbUser = UserModel::loginOrRegisterFacebookUser(
    $user['name'] ?? '',
    $user['picture']['data']['url'] ?? null
);

// Lưu session khi đăng nhập bằng Facebook
$_SESSION['user'] = [
    'id' => $fbUser['id'],
    'name' => $fbUser['name'],
    'username' => $fbUser['username'] ?? null,
    'email' => $fbUser['email'] ?? null,
    'role' => $fbUser['role'] ?? 'user',
    'avatar_url' => $fbUser['avatar_url'] ?? ($user['picture']['data']['url'] ?? null)
];

// Các biến session riêng lẻ (nếu cần)
$_SESSION['user_id'] = $fbUser['id'];
$_SESSION['user_name'] = $fbUser['name'];
$_SESSION['user_username'] = $fbUser['username'] ?? null;
$_SESSION['user_email'] = $fbUser['email'] ?? null;
$_SESSION['user_role'] = $fbUser['role'] ?? 'user';
$_SESSION['user_avatar_url'] = $fbUser['avatar_url'] ?? ($user['picture']['data']['url'] ?? null);

// Redirect về homepage
header('Location: ' . BASE_URL . '/');
exit();
