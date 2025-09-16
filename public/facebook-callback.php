<?php
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../model/user/userModel.php';

$appId = '813811857990201';
$appSecret = '87d73962040afdd642d7419686017f68';
$redirectUri = 'http://localhost/DFF.VN/public/facebook-callback.php';

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
    'client_id' => $appId,
    'redirect_uri' => $redirectUri,
    'client_secret' => $appSecret,
    'code' => $code
]);

$response = file_get_contents($tokenUrl);
$data = json_decode($response, true);

if (!isset($data['access_token'])) {
    die('Không lấy được access token');
}

$accessToken = $data['access_token'];

// Lấy thông tin user (chỉ cần name + avatar)
$userUrl = "https://graph.facebook.com/me?fields=name,picture.width(200).height(200)&access_token={$accessToken}";
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
    'username' => $fbUser['username'] ?? null, // nếu FB không có username
    'email' => $fbUser['email'] ?? null,
    'role' => $fbUser['role'] ?? 'user',
    'avatar_url' => $fbUser['avatar_url'] ?? ($fbUser['picture']['data']['url'] ?? null)
];

// Các biến session riêng lẻ (nếu vẫn cần)
$_SESSION['user_id'] = $fbUser['id'];
$_SESSION['user_name'] = $fbUser['name'];
$_SESSION['user_username'] = $fbUser['username'] ?? null;
$_SESSION['user_email'] = $fbUser['email'] ?? null;
$_SESSION['user_role'] = $fbUser['role'] ?? 'user';
$_SESSION['user_avatar_url'] = $fbUser['avatar_url'] ?? ($fbUser['picture']['data']['url'] ?? null);


// Redirect về homepage
header('Location: http://localhost/DFF.VN/');
exit();

