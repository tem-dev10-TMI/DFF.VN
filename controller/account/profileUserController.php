<?php
class profileUserController
{
    // Trang hồ sơ người dùng
    public static function index()
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

        /*         $articles = $modelArticle->getArticleById($userId);
 */
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
    
    public static function viewprofileUser() // cái này để xem người ta nó tự tách phân biết nào là user và nào là doanh nhân
    {
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            header("Location: index.php");
            exit;
        }

        require_once 'model/user/userModel.php';

        $user_id = intval($_GET['id']);

        // Lấy thông tin user
        $user = UserModel::getUserById($user_id);

        if (!$user) {
            echo "Không tìm thấy người dùng!";
            exit;
        }

        // Lấy bài viết của user để truyền sang view
        $articles = UserModel::getArticlesByAuthorId($user_id);

        // Render view theo role
        ob_start();
        if (!empty($user['role']) && $user['role'] === 'businessmen') {
            require_once 'model/user/businessmenModel.php';
            $businessman = businessmenModel::getBusinessByUserId($user_id);
            $careers = !empty($businessman['businessman_id'])
                ? businessmenModel::getCareersByBusinessmenId($businessman['businessman_id'])
                : [];
            $stats = !empty($businessman['user_id'])
                ? businessmenModel::getBusinessStats($businessman['user_id'])
                : ['articles' => 0, 'followers' => 0, 'following' => 0, 'likes' => 0];

            require_once 'view/page/viewProfilebusiness.php';
        } else {
            require_once 'view/page/viewProfileuser.php';
        }
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

    public function editProfile()
    {
        // 1. Xác thực và Phân quyền
        if (!isset($_SESSION['user']) || !isset($_SESSION['user']['id'])) {
            header("Location: " . BASE_URL . "/login");
            exit;
        }
        require_once 'model/user/profileUserModel.php';
        require_once 'model/user/userModel.php';
        $userId = $_SESSION['user']['id'];

        $profileModel = new profileUserModel();
        $userModel = new userModel();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $currentUser = $userModel->getUserById($userId);

            // --- Xử lý upload file ---
            $avatarDir = "public/img/avatar/";
            $coverDir = "public/img/cover/";

            // Tạo thư mục nếu chưa tồn tại
            if (!is_dir($avatarDir)) {
                mkdir($avatarDir, 0777, true);
            }
            if (!is_dir($coverDir)) {
                mkdir($coverDir, 0777, true);
            }

            $avatar_url = $currentUser['avatar_url']; // Mặc định giữ lại ảnh cũ
            $cover_photo = $currentUser['cover_photo']; // Mặc định giữ lại ảnh cũ

            // Xử lý upload ảnh đại diện
            if (isset($_FILES['avatar_file']) && $_FILES['avatar_file']['error'] == 0) {
                $avatarName = uniqid('avatar_') . '_' . time() . '.' . pathinfo($_FILES['avatar_file']['name'], PATHINFO_EXTENSION);
                $avatarPath = $avatarDir . $avatarName;
                if (move_uploaded_file($_FILES['avatar_file']['tmp_name'], $avatarPath)) {
                    $avatar_url = BASE_URL . '/' . $avatarPath;
                }
            }

            // Xử lý upload ảnh bìa
            if (isset($_FILES['cover_file']) && $_FILES['cover_file']['error'] == 0) {
                $coverName = uniqid('cover_') . '_' . time() . '.' . pathinfo($_FILES['cover_file']['name'], PATHINFO_EXTENSION);
                $coverPath = $coverDir . $coverName;
                if (move_uploaded_file($_FILES['cover_file']['tmp_name'], $coverPath)) {
                    $cover_photo = BASE_URL . '/' . $coverPath;
                }
            }

            // Lấy và làm sạch dữ liệu từ form
            $user_name = htmlspecialchars($_POST['user_name'] ?? '');
            $email = htmlspecialchars($_POST['email'] ?? '');
            $phone = htmlspecialchars($_POST['phone'] ?? '');
            $description = htmlspecialchars($_POST['description'] ?? '');

            $display_name = htmlspecialchars($_POST['display_name'] ?? '');
            $birth_year = filter_var($_POST['birth_year'] ?? null, FILTER_VALIDATE_INT);
            $workplace = htmlspecialchars($_POST['workplace'] ?? '');
            $studied_at = htmlspecialchars($_POST['studied_at'] ?? '');
            $live_at = htmlspecialchars($_POST['live_at'] ?? '');

            // Cập nhật bảng `users`
            $successUser = $userModel->updateUser($userId, $user_name, $email, $phone, $avatar_url, $cover_photo, $description);

            // Cập nhật bảng `profile_user` (giữ nguyên logic)
            $existingProfile = $profileModel->getProfileUserByUserId($userId);
            $successProfile = false;
            if ($existingProfile && isset($existingProfile['id'])) {
                $successProfile = $profileModel->updateProfileUser(
                    $userId,
                    $display_name,
                    $birth_year,
                    $workplace,
                    $studied_at,
                    $live_at
                );
            } else {
                $successProfile = $profileModel->addProfileUser(
                    $userId,
                    $display_name,
                    $birth_year,
                    $workplace,
                    $studied_at,
                    $live_at
                );
            }

            // Chuyển hướng dựa trên kết quả
            if ($successUser && $successProfile) {
                header('Location: ' . BASE_URL . '/profileUser?msg=profile_updated');
                exit;
            } else {
                header('Location: ' . BASE_URL . '/profileUser?msg=profile_failed');
                exit;
            }
        }

        // 5. Xử lý yêu cầu GET (hiển thị form)
        $profileUser = $profileModel->getProfileUserByUserId($userId);
        $user = $userModel->getUserById($userId);
        require_once "view/page/profileUser.php";
    }

    // ========== Đổi mật khẩu ==========
    // Trong Controller của bạn
    public function changePassword()
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: " . BASE_URL . "/login");
            exit;
        }

        $userId = $_SESSION['user_id'];
        require_once 'model/user/userModel.php';
        $userModel = new userModel();
        $user = $userModel->getUserById($userId);

        // Khởi tạo các biến thông báo
        $changePasswordMessage = '';
        $messageType = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $old_password = $_POST['old_password'] ?? '';
            $new_password = $_POST['new_password'] ?? '';
            $confirm_new_password = $_POST['confirm_new_password'] ?? '';

            if (!password_verify($old_password, $user['password_hash'])) {
                $changePasswordMessage = "Mật khẩu cũ không chính xác.";
                $messageType = 'danger';
            } elseif ($new_password !== $confirm_new_password) {
                $changePasswordMessage = "Mật khẩu mới và xác nhận không khớp.";
                $messageType = 'danger';
            } elseif (strlen($new_password) < 8) {
                $changePasswordMessage = "Mật khẩu mới phải có ít nhất 8 ký tự.";
                $messageType = 'danger';
            } else {
                $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
                $success = $userModel->updatePassword($userId, $new_password_hash);

                if ($success) {
                    $changePasswordMessage = "Đổi mật khẩu thành công!";
                    $messageType = 'success';
                } else {
                    $changePasswordMessage = "Đã xảy ra lỗi khi đổi mật khẩu.";
                    $messageType = 'danger';
                }
            }
        }
        //Load view
        ob_start();
        require_once 'view/layout/header.php';
        $content = ob_get_clean();
        $profile = false;
        //Load layout;
        require_once 'view/layout/main.php';
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
