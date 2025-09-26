<?php
require_once __DIR__ . '/../model/ArticleSavesModel.php';
require_once __DIR__ . '/../model/article/articlesmodel.php';
require_once __DIR__ . '/../model/user/businessmenModel.php';
require_once __DIR__ . '/../model/user/userModel.php';
require_once __DIR__ . '/../model/user/profileUserModel.php';

class SavedArticlesController {
    
    public function index() {
        // Kiểm tra đăng nhập
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user']['id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
        
        $userId = $_SESSION['user']['id'];
        $savesModel = new ArticleSavesModel();
        
        // Lấy danh sách bài viết đã lưu với thông tin author
        $savedArticles = $savesModel->getSavedArticlesWithAuthor($userId);
        
        // Lấy thông tin user profile (giống ProfileUser)
        $modelUser = new UserModel();
        $modelProfile = new profileUserModel();
        $user = $modelUser->getUserById($userId);
        $profileUser = $modelProfile->getProfileUserByUserId($userId);
        $stats = $modelProfile->getUserStats($userId);
        
        // Các biến cần thiết cho ProfileUser layout
        $profile_category = 'save';
        $hasBusinessRequest = false;
        
        // Load view sử dụng layout Profile.php
        ob_start();
        require_once __DIR__ . '/../view/page/SavedArticles.php';
        $content = ob_get_clean();
        $profile = true;
        //Load layout
        require_once __DIR__ . '/../view/layout/main.php';
    }
    
}
