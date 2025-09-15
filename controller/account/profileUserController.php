<?php
class profileUserController
{
    // Trang hồ sơ người dùng
    public static function profileUser()
    {
        if (!isset($_SESSION['user'])) {
            header("Location: " . BASE_URL . "/login");
            exit;
        }
        require_once 'model/user/userModel.php';
        require_once 'model/article/articlesmodel.php';
        require_once 'model/user/profileUserModel.php';

        $modelArticle = new ArticlesModel();
        $modelUser = new UserModel();
        $modelProfile = new profileUserModel();

        $userId = $_SESSION['user']['id'];

        $user = $modelUser->getUserById($userId);

        $articles = $modelArticle->getArticleById($userId);

        $role = $_SESSION['user']['role'];
        if ($role === 'user') {
            $profileUser = $modelProfile->getProfileUserByUserId($userId);
            $stats = $modelProfile->getUserStats($userId);
            //Load view
            ob_start();
            $profile_category = 'user';
            require_once 'view/layout/Profile.php';
            $content = ob_get_clean();
            $profile = true;
            //Load layout
            // đừng ai xóa
            require_once 'view/layout/main.php';
        } else {
            header("Location: " . BASE_URL);
            exit;
        }
    }
    public static function viewprofileBusiness()
    {
        ob_start();
        require_once 'view/page/viewProfilebusiness.php';
        $content = ob_get_clean();
        $profile = false; // đừng ai xóa
        require_once 'view/layout/main.php';
    }
    public static function viewprofileUser()
    {
        ob_start();
        require_once 'view/page/viewProfileuser.php';
        $content = ob_get_clean();
        $profile = false; // đừng ai xóa
        require_once 'view/layout/main.php';
    }

    // ========== Quản lý Bài viết ==========
    public static function addArticle()
    {
        header('Content-Type: application/json');
        require_once 'model/article/articlesmodel.php';

        // Chỉ chấp nhận POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode([
                'success' => false,
                'message' => 'Phương thức không được hỗ trợ!'
            ]);
            exit;
        }

        // Kiểm tra đăng nhập

        if (!isset($_SESSION['user_id'])) {
            echo json_encode([
                'success' => false,
                'message' => 'Bạn cần đăng nhập để đăng bài!'
            ]);
            exit;
        }

        $modelArticle = new ArticlesModel();

        // Lấy dữ liệu từ form
        $title = isset($_POST['title']) ? trim($_POST['title']) : '';
        $summary = isset($_POST['summary']) ? trim($_POST['summary']) : '';
        $content = isset($_POST['content']) ? trim($_POST['content']) : '';
        $topic_id = isset($_POST['topic_id']) ? intval($_POST['topic_id']) : null;
        $author_id = $_SESSION['user_id'];
        $main_image_url = null;

        // Xử lý upload ảnh nếu có
        if (isset($_FILES['main_image_url']) && $_FILES['main_image_url']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = 'public/img/articles/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $file_extension = pathinfo($_FILES['main_image_url']['name'], PATHINFO_EXTENSION);
            $file_name = 'article_' . time() . '_' . rand(1000, 9999) . '.' . $file_extension;
            $file_path = $upload_dir . $file_name;

            if (move_uploaded_file($_FILES['main_image_url']['tmp_name'], $file_path)) {
                $main_image_url = $file_path;
            }
        }

        // Validation
        if (empty($title) || empty($content)) {
            echo json_encode([
                'success' => false,
                'message' => 'Vui lòng nhập đầy đủ tiêu đề và nội dung!'
            ]);
            exit;
        }

        if (strlen($content) < 10) {
            echo json_encode([
                'success' => false,
                'message' => 'Nội dung bài viết phải có ít nhất 10 ký tự!'
            ]);
            exit;
        }

        // Thêm bài viết vào DB
        $newArticleId = $modelArticle->addArticle($title, $summary, $content, $main_image_url, $author_id, $topic_id);

        if ($newArticleId) {
            echo json_encode([
                'success' => true,
                'message' => 'Đăng bài thành công!',
                'article' => [
                    'id' => $newArticleId,
                    'title' => $title,
                    'summary' => $summary,
                    'content' => $content,
                    'topic_id' => $topic_id,
                    'author_id' => $author_id,
                    'image' => $main_image_url
                ]
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Lỗi khi thêm bài viết vào cơ sở dữ liệu!'
            ]);
        }
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
            header('Location: ' . BASE_URL . '/profile_user?msg=article_updated');
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
        if (!isset($_SESSION['user'])) {
            header("Location: " . BASE_URL . "/login");
            exit;
        }
        $userId = $_SESSION['user']['id'];
        require_once 'model/user/profileUserModel.php';
        $modelProfile = new profileUserModel();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $display_name = $_POST['display_name'] ?? null;
            $birth_year   = $_POST['birth_year'] ?? null;
            $workplace    = $_POST['workplace'] ?? null;
            $studied_at   = $_POST['studied_at'] ?? null;
            $live_at      = $_POST['live_at'] ?? null;

            // Kiểm tra xem user đã có hồ sơ chưa
            $profileUser  = $modelProfile->getProfileUserByUserId($userId);
            if ($profileUser) {
                // Có rồi → update
                $result = $modelProfile->updateProfileUser($userId, $display_name, $birth_year, $workplace, $studied_at, $live_at);
            } else {
                // Chưa có → insert mới
                $result = $modelProfile->addProfileUser($userId, $display_name, $birth_year, $workplace, $studied_at, $live_at);
            }

            if ($result) {
                header('Location: ' . BASE_URL . '/profileUser?msg=profile_updated');
                exit;
            } else {
                header('Location: ' . BASE_URL . '/profileUser?msg=profile_failed');
                exit;
            }
        }

        $profileUser = $modelProfile->getProfileUserByUserId($userId);
        require_once "view/page/profileUser.php";
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
    
    // ========== API: Load bài viết theo user, trả về cấu trúc như loadPosts.php ==========
    public static function loadArticle()
    {
        header('Content-Type: application/json');
        require_once 'model/article/articlesmodel.php';

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode([
                'success' => false,
                'message' => 'Phương thức không được hỗ trợ!'
            ]);
            return;
        }

        // Nhận JSON input
        $rawInput = file_get_contents('php://input');
        $input = json_decode($rawInput, true);
        if (!is_array($input)) {
            // Cũng hỗ trợ form-encoded như fallback
            $input = $_POST;
        }

        $profile_category = isset($input['profile_category']) ? $input['profile_category'] : '';
        $user_id = isset($input['user_id']) ? (int)$input['user_id'] : 0;

        try {
            // Fallback user_id từ session nếu thiếu
            if ($user_id === 0 && isset($_SESSION['user']['id'])) {
                $user_id = (int)$_SESSION['user']['id'];
            }

            // Lấy bài viết theo author (bao gồm mọi trạng thái)
            $filtered = ArticlesModel::getArticlesByAuthorId($user_id);

            // Helper time ago
            $toTimeAgo = function ($datetimeStr) {
                if (empty($datetimeStr)) return '';
                $ts = strtotime($datetimeStr);
                if ($ts === false) return '';
                $diff = time() - $ts;
                if ($diff < 60) return $diff . ' giây trước';
                if ($diff < 3600) return floor($diff / 60) . ' phút trước';
                if ($diff < 86400) return floor($diff / 3600) . ' giờ trước';
                $days = floor($diff / 86400);
                return $days . ' ngày trước';
            };

            // Map về cấu trúc posts như sample loadPosts.php
            $posts = array_map(function ($row) use ($profile_category, $user_id, $toTimeAgo) {
                $articleId = (int)($row['id'] ?? 0);
                $status = (string)($row['status'] ?? 'pending');
                $reason = null;
                if ($status === 'rejected' || $status === 'reject') {
                    // Lý do mới nhất từ bảng review
                    $reason = ArticlesModel::getLatestReviewReasonByArticleId($articleId);
                }

                return [
                    'id' => $articleId,
                    'title' => (string)($row['title'] ?? ''),
                    'content' => (string)($row['summary'] ?? $row['content'] ?? ''),
                    'author_name' => $profile_category === 'businessmen' ? ($row['author_name'] ?? 'Doanh nghiệp') : ($row['author_name'] ?? 'Người dùng'),
                    'author_id' => (int)($row['author_id'] ?? $user_id),
                    'avatar' => $row['avatar_url'] ?? 'https://i.pinimg.com/1200x/83/0e/ea/830eea38f7a5d3d8e390ba560d14f39c.jpg',
                    'time_ago' => $toTimeAgo($row['created_at'] ?? ''),
                    'image' => $row['main_image_url'] ?? null,
                    'likes_count' => (int)($row['likes_count'] ?? 0),
                    'comments_count' => (int)($row['comment_count'] ?? 0),
                    'status' => $status,
                    'review_reason' => $reason,
                ];
            }, $filtered);

            echo json_encode([
                'success' => true,
                'message' => 'Load bài viết thành công!',
                'posts' => $posts,
                'total' => count($posts),
                'profile_category' => $profile_category,
                'user_id' => $user_id
            ]);
        } catch (Throwable $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Lỗi tải bài viết: ' . $e->getMessage()
            ]);
        }
    }

}
