<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../model/user/userModel.php';

// ===================== UserController.php =====================
use Google\Client as GoogleClient;
use Google\Service\Oauth2 as GoogleServiceOauth2;

class UserController
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Redirect tới Google OAuth2
    public function loginWithGoogle(): void
    {
        $client = $this->createGoogleClient();
        $authUrl = $client->createAuthUrl();
        header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
        exit();
    }

    // Callback xử lý Google code
    public function googleCallback(): void
    {
        if (!isset($_GET['code'])) {
            $this->loginWithGoogle();
        }

        $client = $this->createGoogleClient();

        try {
            $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

            if (isset($token['error'])) {
                die('Google login failed: ' . ($token['error_description'] ?? $token['error']));
            }

            $client->setAccessToken($token['access_token']);
            $googleService = new GoogleServiceOauth2($client);
            $googleUser = $googleService->userinfo->get();

            // Avatar URL Google (size 200)
            $avatarUrl = str_replace('s96-c', 's200-c', $googleUser->picture ?? '');

            // Lưu user vào DB
            $user = UserModel::loginOrRegisterGoogleUser(
                $googleUser->name,
                $googleUser->email,
                $avatarUrl
            );
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_username'] = $user['username'] ?? null;
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role'] = $user['role'] ?? 'user';
            $_SESSION['user_avatar_url'] = $user['avatar_url'] ?? $googleUser->picture; // link Google

            // Lấy avatar để hiển thị
            $avatarUrl = $_SESSION['user_avatar_url'] ?? null;

            // Nếu rỗng hoặc null, dùng avatar mặc định
            if (!$avatarUrl || trim($avatarUrl) === '') {
                $avatarUrl = 'https://i.pinimg.com/1200x/83/0e/ea/830eea38f7a5d3d8e390ba560d14f39c.jpg';
            }

            // Nếu là link Google, tăng kích thước avatar
            $avatarUrl = str_replace('s96-c', 's200-c', $avatarUrl);


            header('Location: ' . BASE_URL);
            exit();

        } catch (\Exception $e) {
            die('Google callback error: ' . $e->getMessage());
        }
    }


    private function createGoogleClient(): GoogleClient
    {
        $client = new GoogleClient();
        $client->setClientId(GOOGLE_CLIENT_ID);
        $client->setClientSecret(GOOGLE_CLIENT_SECRET);
        $client->setRedirectUri(GOOGLE_REDIRECT_URI);
        $client->addScope('email');
        $client->addScope('profile');

        $client->setHttpClient(new \GuzzleHttp\Client([
            'timeout' => 15,
            'verify' => false
        ]));

        return $client;
    }
}

