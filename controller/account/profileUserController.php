<?php
class profileUserController
{
    // Trang hồ sơ người dùng
    public static function profileUser()
    {
        require_once 'model/user/userModel.php';
        require_once 'model/article/articlesmodel.php';
        require_once 'model/user/profileUserModel.php';

        $modelArticle = new ArticlesModel();
        $modelUser = new UserModel();
        $modelProfile = new profileUserModel();

        if (isset($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id'];
            $user = $modelUser->getUserById($userId);
            $profile = $modelProfile->getProfileUserByUserId($userId);
            $articles = $modelArticle->getArticleById($userId);

            require_once 'view/account/profileUser.php';
        }
    }

    // ========== Quản lý Bài viết ==========
    public static function addArticle()
    {
        require_once 'model/article/articlesmodel.php';
        $modelArticle = new ArticlesModel();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
            $title = $_POST['title'];
            $summary = $_POST['summary'];
            $content = $_POST['content'];
            $main_image_url = $_POST['main_image_url'] ?? null;
            $topic_id = $_POST['topic_id'] ?? null;
            $author_id = $_SESSION['user_id'];

            $modelArticle->addArticle($title, $summary, $content, $main_image_url, $author_id, $topic_id);
            header('Location: ' . BASE_URL . '/profileUser?msg=article_added');
            exit;
        }
        require_once 'view/account/addArticle.php';
    }

    public static function editArticle($id)
    {
        require_once 'model/article/articlesmodel.php';
        $modelArticle = new ArticlesModel();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = $_POST['title'];
            $summary = $_POST['summary'];
            $content = $_POST['content'];
            $main_image_url = $_POST['main_image_url'] ?? null;
            $topic_id = $_POST['topic_id'] ?? null;

            $modelArticle->updateArticle($id, $title, $summary, $content, $main_image_url, $topic_id);
            header('Location: ' . BASE_URL . '/profileUser?msg=article_updated');
            exit;
        }

        $article = $modelArticle->getArticleById($id);
        require_once 'view/account/editArticle.php';
    }

    // ========== Quản lý Hồ sơ ==========
    public static function addProfile()
    {
        require_once 'model/user/profileUserModel.php';
        $modelProfile = new profileUserModel();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
            $display_name = $_POST['display_name'];
            $birth_year = $_POST['birth_year'];
            $workplace = $_POST['workplace'];
            $studied_at = $_POST['studied_at'];
            $live_at = $_POST['live_at'];

            // Gọi model để thêm thông tin
            if ($modelProfile->addProfileUser($user_id, $display_name, $birth_year, $workplace, $studied_at, $live_at)) {
                // Quay lại profileUser với thông báo
                header('Location: ' . BASE_URL . '/profileUser?msg=profile_added');
                exit;
            } else {
                $error = "❌ Lỗi khi thêm thông tin cá nhân!";
            }
        }

        // Hiển thị form thêm thông tin
        require_once 'view/account/addProfile.php';
    }

    public static function editProfile()
    {
        require_once 'model/user/profileUserModel.php';
        $modelProfile = new profileUserModel();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
            $display_name = $_POST['display_name'];
            $birth_year = $_POST['birth_year'];
            $workplace = $_POST['workplace'];
            $studied_at = $_POST['studied_at'];
            $live_at = $_POST['live_at'];

            $modelProfile->updateProfileUser($user_id, $display_name, $birth_year, $workplace, $studied_at, $live_at);
            header('Location: ' . BASE_URL . '/profileUser?msg=profile_updated');
            exit;
        }

        $profile = $modelProfile->getProfileUserByUserId($_SESSION['user_id']);
        require_once 'view/account/editProfile.php';
    }

    // ========== Đổi mật khẩu ==========
    public static function changePassword()
    {
        require_once 'model/user/userModel.php';
        $modelUser = new UserModel();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id'];
            $user = $modelUser->getUserById($userId);

            $oldPassword = $_POST['old_password'];
            $newPassword = $_POST['new_password'];
            $confirmPassword = $_POST['confirm_password'];

            if (password_verify($oldPassword, $user['password_hash'])) {
                if ($newPassword === $confirmPassword) {
                    $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
                    $db = new connect();
                    $sql = "UPDATE users SET password_hash = :password WHERE id = :id";
                    $stmt = $db->db->prepare($sql);
                    $stmt->execute([':password' => $hashed, ':id' => $userId]);

                    header('Location: ' . BASE_URL . '/profileUser?msg=password_changed');
                    exit;
                } else {
                    $error = "Mật khẩu mới không khớp!";
                }
            } else {
                $error = "Mật khẩu cũ không đúng!";
            }
        }

        require_once 'view/account/changePassword.php';
    }
}

/* 
View hiển thị thông báo
<?php if (isset($_GET['msg'])): ?>
    <script>
        switch ("<?= $_GET['msg'] ?>") {
            case "article_added":
                alert("✅ Bài viết đã được thêm thành công!");
                break;
            case "article_updated":
                alert("✏️ Bài viết đã được cập nhật thành công!");
                break;
            case "profile_updated":
                alert("📝 Thông tin cá nhân đã được cập nhật thành công!");
                break;
            case "profile_added":
                alert("📝 Thông tin cá nhân đã được thêm thành công!");
                break;
            case "password_changed":
                alert("🔑 Mật khẩu đã được đổi thành công!");
                break;
        }
    </script>
<?php endif; ?> */