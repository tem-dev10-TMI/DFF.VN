<?php
class profileUserController
{
    // Trang hồ sơ người dùng
    public static function index()
    {
        if (!isset($_SESSION['user'])) {
            header("Location: " . BASE_URL . "");
            exit;
        }
        require_once __DIR__ . '/../../model/user/userModel.php';
        require_once __DIR__ . '/../../model/article/articlesmodel.php';
        require_once __DIR__ . '/../../model/user/profileUserModel.php';
        require_once __DIR__ . '/../../model/article/topicsmodel.php';

        $modelTopic = new TopicsModel();

        $modelArticle = new ArticlesModel();
        $modelUser = new UserModel();
        $modelProfile = new profileUserModel();

        $userId = $_SESSION['user']['id'];

        $user = $modelUser->getUserById($userId);

        $topics = $modelTopic->getAllTopics();

        /*         $articles = $modelArticle->getArticleById($userId);
 */
        $role = $_SESSION['user']['role'];
        if ($role === 'user') {
            $profileUser = $modelProfile->getProfileUserByUserId($userId);
            $stats = $modelProfile->getUserStats($userId);
            //Load view
            ob_start();
            $profile_category = 'user';
            require_once __DIR__ . '/../../view/layout/Profile.php';
            $content = ob_get_clean();
            $profile = true;
            //Load layout
            // đừng ai xóa
            require_once __DIR__ . '/../../view/layout/main.php';
        } else {
            header("Location: " . BASE_URL);
            exit;
        }
    }

    public static function viewprofileUser()
    {
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            header("Location: " . BASE_URL);
            exit;
        }

        require_once __DIR__ . '/../../model/user/userModel.php';
        $user_id = intval($_GET['id']);
        $user = UserModel::getUserById($user_id);

        if (!$user) {
            echo "Không tìm thấy người dùng!";
            exit;
        }

        $articles = [];

        // RSS riêng theo user id
        if ($user_id == 66 || $user_id == 67) {
            require_once __DIR__ . '/../../model/rss/RssModel.php';

            $feedUrls = [];
            if ($user_id == 66) {
                // RSS cho user 66 → baochinhphu.vn
                $feedUrls = ["https://baochinhphu.vn/kinh-te.rss"];
            } elseif ($user_id == 67) {
                // RSS cho user 67 → thanhnien.vn
                $feedUrls = ["https://thanhnien.vn/rss/kinh-te.rss"];
            }

            $articles = RssModel::getMultipleFeeds($feedUrls, 50, 15);

            // Sắp xếp giảm dần theo ngày tạo
            usort($articles, function ($a, $b) {
                return strtotime($b['created_at']) - strtotime($a['created_at']);
            });
        } else {
            // User bình thường → lấy bài viết từ DB
            require_once __DIR__ . '/../../model/article/articlesmodel.php';
            $articles = ArticlesModel::getArticlesByAuthorId($user_id);
        }
        $feedUrls = [
            "https://baochinhphu.vn/kinh-te.rss",
            "https://thanhnien.vn/rss/kinh-te.rss"
        ];
        ob_start();
        if (!empty($user['role']) && $user['role'] === 'businessmen') {
            require_once __DIR__ . '/../../model/user/businessmenModel.php';
            $businessman = businessmenModel::getBusinessByUserId($user_id);
            $careers = !empty($businessman['businessman_id'])
                ? businessmenModel::getCareersByBusinessmenId($businessman['businessman_id'])
                : [];
            $stats = !empty($businessman['user_id'])
                ? businessmenModel::getBusinessStats($businessman['user_id'])
                : ['articles' => 0, 'followers' => 0, 'following' => 0, 'likes' => 0];

            require_once __DIR__ . '/../../view/page/viewProfilebusiness.php';
        } else {
            require_once __DIR__ . '/../../view/page/viewProfileuser.php';
        }
        $content = ob_get_clean();
        $profile = false;
        require_once __DIR__ . '/../../view/layout/main.php';
    }

    // ========== Quản lý Bài viết ==========
    public static function addArticle()
    {
        header('Content-Type: application/json');
        require_once __DIR__ . '/../../model/article/articlesmodel.php';
        require_once __DIR__ . '/../../model/mediamodel.php';

        // Chỉ chấp nhận POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode([
                'success' => false,
                'message' => 'Phương thức không được hỗ trợ!'
            ]);
            exit;
        }

        // Kiểm tra đăng nhập và session token
        if (!isset($_SESSION['user']['id'])) {
            http_response_code(401);
            echo json_encode([
                'success' => false,
                'message' => 'Bạn cần đăng nhập để đăng bài!'
            ]);
            exit;
        }

        $submittedToken = $_POST['session_token'] ?? '';
        if (!isset($_SESSION['user']['session_token']) || $submittedToken !== $_SESSION['user']['session_token']) {
            http_response_code(401);
            echo json_encode([
                'success' => false,
                'message' => 'Phiên làm việc không hợp lệ. Vui lòng tải lại trang.'
            ]);
            exit;
        }

        $modelArticle = new ArticlesModel();
        $debug_info = []; // Mảng chứa thông tin gỡ lỗi

        // Lấy dữ liệu từ form
        $title = isset($_POST['title']) ? trim($_POST['title']) : '';
        $summary = isset($_POST['summary']) ? trim($_POST['summary']) : '';
        $content = isset($_POST['content']) ? trim($_POST['content']) : '';
        $topic_id = isset($_POST['topic_id']) ? intval($_POST['topic_id']) : null;
        $author_id = $_SESSION['user']['id'];
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
        $articleData = [
            'title' => $title,
            'summary' => $summary,
            'content' => $content,
            'main_image_url' => $main_image_url,
            'author_id' => $author_id,
            'topic_id' => $topic_id
        ];
        $newArticleId = $modelArticle->addArticleFromProfile($articleData);

        if ($newArticleId) {
            $video_message = 'Không có video nào được tải lên.';
            // Kiểm tra xem có file video nào được gửi không
            if (isset($_FILES['post_video'])) {
                $video_file = $_FILES['post_video'];
                // Kiểm tra lỗi tải lên
                if ($video_file['error'] === UPLOAD_ERR_OK) {
                    $uploadDir = __DIR__ . '/../../public/videos/';

                    // Kiểm tra và tạo thư mục
                    if (!is_dir($uploadDir)) {
                        if (!mkdir($uploadDir, 0777, true)) {
                            $video_message = 'Lỗi: Không thể tạo thư mục videos. Vui lòng kiểm tra quyền ghi.';
                        }
                    }

                    // Tiếp tục nếu thư mục tồn tại
                    if (is_dir($uploadDir)) {
                        $extension = strtolower(pathinfo($video_file['name'], PATHINFO_EXTENSION));
                        $allowedTypes = ['mp4', 'webm', 'ogg', 'mov'];

                        if (in_array($extension, $allowedTypes)) {
                            $newFileName = 'video_' . $newArticleId . '_' . time() . '.' . $extension;
                            $uploadPath = $uploadDir . $newFileName;

                            if (move_uploaded_file($video_file['tmp_name'], $uploadPath)) {
                                $webPath = 'public/videos/' . $newFileName; // Đường dẫn web tương đối
                                MediaModel::addMedia($newArticleId, $webPath, 'video', null);
                                $video_message = 'Video đã được tải lên và lưu thành công.';
                            } else {
                                $video_message = 'Lỗi: Không thể di chuyển file đã tải lên. Vui lòng kiểm tra quyền ghi của thư mục videos.';
                            }
                        } else {
                            $video_message = 'Lỗi: Định dạng video không hợp lệ. Chỉ chấp nhận: ' . implode(', ', $allowedTypes);
                        }
                    }
                } else {
                    // Cung cấp thông báo lỗi tải lên rõ ràng
                    $upload_errors = [
                        UPLOAD_ERR_INI_SIZE   => "File vượt quá dung lượng cho phép trong php.ini (upload_max_filesize).",
                        UPLOAD_ERR_FORM_SIZE  => "File vượt quá dung lượng cho phép đã khai báo trong form HTML.",
                        UPLOAD_ERR_PARTIAL    => "File chỉ được tải lên một phần.",
                        UPLOAD_ERR_NO_FILE    => "Không có file nào được tải lên.",
                        UPLOAD_ERR_NO_TMP_DIR => "Thiếu thư mục tạm.",
                        UPLOAD_ERR_CANT_WRITE => "Không thể ghi file vào ổ đĩa.",
                        UPLOAD_ERR_EXTENSION  => "Một tiện ích mở rộng của PHP đã dừng việc tải file.",
                    ];
                    $video_message = 'Lỗi tải lên: ' . ($upload_errors[$video_file['error']] ?? 'Lỗi không xác định');
                }
            }
            $debug_info['video_processing'] = $video_message;

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
                ],
                'debug' => $debug_info // Thêm thông tin gỡ lỗi vào phản hồi
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
        require_once __DIR__ . '/../../model/article/articlesmodel.php';
        $modelArticle = new ArticlesModel();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Bảo mật: Kiểm tra session token
            $submittedToken = $_POST['session_token'] ?? '';
            if (!isset($_SESSION['user']['session_token']) || $submittedToken !== $_SESSION['user']['session_token']) {
                header('Location: ' . BASE_URL . '/profile_user?msg=invalid_token');
                exit;
            }

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
        require_once __DIR__ . '/../../view/account/editArticle.php';
    }

    // ========== Quản lý Hồ sơ ==========
    public static function addProfile()
    {
        require_once __DIR__ . '/../../model/user/profileUserModel.php';
        $modelProfile = new profileUserModel();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
            // Bảo mật: Kiểm tra session token
            $submittedToken = $_POST['session_token'] ?? '';
            if (!isset($_SESSION['user']['session_token']) || $submittedToken !== $_SESSION['user']['session_token']) {
                header('Location: ' . BASE_URL . '/profile_user?msg=invalid_token');
                exit;
            }

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
        require_once __DIR__ . '/../../view/account/addProfile.php';
    }

    public function editProfile()
    {
        // 1. Xác thực và Phân quyền
        if (!isset($_SESSION['user']) || !isset($_SESSION['user']['id'])) {
            header("Location: " . BASE_URL . "/login");
            exit;
        }
        require_once __DIR__ . '/../../model/user/profileUserModel.php';
        require_once __DIR__ . '/../../model/user/userModel.php';
        $userId = $_SESSION['user']['id'];

        $profileModel = new profileUserModel();
        $userModel = new userModel();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Bảo mật: Kiểm tra session token
            $submittedToken = $_POST['session_token'] ?? '';
            if (!isset($_SESSION['user']['session_token']) || $submittedToken !== $_SESSION['user']['session_token']) {
                header('Location: ' . BASE_URL . '/profile_user?msg=invalid_token');
                exit;
            }

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
            $name = isset($_POST['name']) && $_POST['name'] !== '' ? htmlspecialchars($_POST['name']) : null;
            $username = isset($_POST['user_name']) && $_POST['user_name'] !== '' ? htmlspecialchars($_POST['user_name']) : null;
            $email = isset($_POST['email']) && $_POST['email'] !== '' ? htmlspecialchars($_POST['email']) : null;
            $phone = isset($_POST['phone']) && $_POST['phone'] !== '' ? htmlspecialchars($_POST['phone']) : null;
            $description = isset($_POST['description']) && $_POST['description'] !== '' ? htmlspecialchars($_POST['description']) : null;

            $display_name = isset($_POST['display_name']) && $_POST['display_name'] !== '' ? htmlspecialchars($_POST['display_name']) : null;
            $birth_year = isset($_POST['birth_year']) && is_numeric($_POST['birth_year']) ? (int)$_POST['birth_year'] : null;
            $workplace = isset($_POST['workplace']) && $_POST['workplace'] !== '' ? htmlspecialchars($_POST['workplace']) : null;
            $studied_at = isset($_POST['studied_at']) && $_POST['studied_at'] !== '' ? htmlspecialchars($_POST['studied_at']) : null;
            $live_at = isset($_POST['live_at']) && $_POST['live_at'] !== '' ? htmlspecialchars($_POST['live_at']) : null;


            // Cập nhật bảng `users`
            $successUser = $userModel->updateUser($userId, $name, $username, $email, $phone, $avatar_url, $cover_photo, $description);

            // Kiểm tra có dữ liệu để lưu không
            $hasProfileData = !empty($display_name) || !empty($birth_year) || !empty($workplace) || !empty($studied_at) || !empty($live_at);

            // Cập nhật bảng `profile_user` (giữ nguyên logic)
            $existingProfile = $profileModel->getProfileUserByUserId($userId);

            $successProfile = true; // mặc định true để không chặn redirect

            if ($hasProfileData) {
                if ($existingProfile && isset($existingProfile['id'])) {
                    // Update khi đã có profile
                    $successProfile = $profileModel->updateProfileUser(
                        $userId,
                        $display_name,
                        $birth_year,
                        $workplace,
                        $studied_at,
                        $live_at
                    );
                } else {
                    // Insert khi có dữ liệu nhập
                    $successProfile = $profileModel->addProfileUser(
                        $userId,
                        $display_name,
                        $birth_year,
                        $workplace,
                        $studied_at,
                        $live_at
                    );
                }
            }

            // Chuyển hướng dựa trên kết quả
            if ($successUser || $successProfile) {
                // --- BẮT ĐẦU CẬP NHẬT LẠI SESSION ---
                $updatedUser = $userModel->getUserById($userId);
                if ($updatedUser) {
                    $_SESSION['user'] = [
                        'id' => $updatedUser['id'],
                        'name' => $updatedUser['name'],
                        'username' => $updatedUser['username'],
                        'password_hash' => $updatedUser['password_hash'],
                        'email' => $updatedUser['email'],
                        'phone' => $updatedUser['phone'],
                        'role' => $updatedUser['role'],
                        'avatar_url' => $updatedUser['avatar_url'] ?? null,
                    ];
                }
                // --- KẾT THÚC CẬP NHẬT LẠI SESSION ---

                header('Location: ' . BASE_URL . '/profile_user?msg=profile_updated');
                exit;
            } else {
                header('Location: ' . BASE_URL . '/profile_user?msg=profile_failed');
                exit;
            }
        }

        // 5. Xử lý yêu cầu GET (hiển thị form)
        $profileUser = $profileModel->getProfileUserByUserId($userId);
        $user = $userModel->getUserById($userId);
        require_once __DIR__ . "/../../view/page/profileUser.php";
    }
    // ========== Đăng kí làm doanh nhân ==========
    public static function registerBusiness()
    {
        require_once __DIR__ . '/../../model/user/profileUserModel.php';
        // 1. Xác thực và Phân quyền
        if (!isset($_SESSION['user']) || !isset($_SESSION['user']['id'])) {
            header("Location: " . BASE_URL . "/login");
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Bảo mật: Kiểm tra session token
            $submittedToken = $_POST['session_token'] ?? '';
            if (!isset($_SESSION['user']['session_token']) || $submittedToken !== $_SESSION['user']['session_token']) {
                header('Location: ' . BASE_URL . '/profile_user?msg=invalid_token');
                exit;
            }

            $user_id     = $_SESSION['user']['id'];
            $birth_year  = $_POST['birth_year'] ?? null;
            $nationality = $_POST['nationality'] ?? null;
            $education   = $_POST['education'] ?? null;
            $position    = $_POST['position'] ?? null;

            $registerBusinessModel = new profileUserModel();
            $result = $registerBusinessModel->createBusinessmen($user_id, $birth_year, $nationality, $education, $position);

            if ($result) {
                // Cập nhật session role
                $_SESSION['user']['role'] = 'businessmen';

                header('Location: ' . BASE_URL . '/profile_user?msg=user_updated');
                exit;
            } else {
                header('Location: ' . BASE_URL . '/profile_user?msg=user_failed');
                exit;
            }
        }
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
        require_once __DIR__ . '/../../model/user/userModel.php';
        $userModel = new userModel();
        $user = $userModel->getUserById($userId);

        // Khởi tạo các biến thông báo
        $changePasswordMessage = '';
        $messageType = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Bảo mật: Kiểm tra session token
            $submittedToken = $_POST['session_token'] ?? '';
            if (!isset($_SESSION['user']['session_token']) || $submittedToken !== $_SESSION['user']['session_token']) {
                header('Location: ' . BASE_URL . '/profile_user?msg=invalid_token');
                exit;
            }

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
                    header('Location: ' . BASE_URL . '/profile_user?msg=password_changed');
                    exit;
                } else {
                    header('Location: ' . BASE_URL . '/profile_user?msg=password_changed_failed');
                    exit;
                }
            }
        }
        //Load view
        header('Location: ' . BASE_URL . '');
    }

    // ========== API: Load bài viết theo user, trả về cấu trúc như loadPosts.php ==========
    public static function loadArticle()
    {
        header('Content-Type: application/json');
        require_once __DIR__ . '/../../model/article/articlesmodel.php';
        require_once __DIR__ . '/../../model/mediamodel.php';

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

                // Lấy media cho bài viết
                $mediaItems = MediaModel::getMediaByArticle($articleId);
                $video_url = null;
                foreach ($mediaItems as $item) {
                    if (isset($item['media_type']) && $item['media_type'] === 'video') {
                        $video_url = $item['media_url'];
                        break; // Lấy video đầu tiên
                    }
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
                    'video_url' => $video_url, // Thêm URL video
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
    public static function deleteArticle()
    {
        header('Content-Type: application/json');

        // Giả sử các file này đã được autoload hoặc require ở một nơi khác
        require_once __DIR__ . '/../../model/article/articlesmodel.php';
        require_once __DIR__ . '/../../model/mediamodel.php';
        require_once __DIR__ . '/../../model/connect.php';

        // 1. Chỉ chấp nhận phương thức POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Phương thức không hợp lệ.']);
            exit;
        }

        // 2. Kiểm tra đăng nhập và token
        if (!isset($_SESSION['user']['id'])) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Bạn cần đăng nhập để thực hiện hành động này.']);
            exit;
        }

        $submittedToken = $_POST['session_token'] ?? '';
        if (!isset($_SESSION['user']['session_token']) || $submittedToken !== $_SESSION['user']['session_token']) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Phiên làm việc không hợp lệ.']);
            exit;
        }

        // 3. Lấy và xác thực dữ liệu đầu vào
        $postId = $_POST['post_id'] ?? null;
        if (empty($postId) || !is_numeric($postId)) {
            echo json_encode(['success' => false, 'message' => 'ID bài viết không hợp lệ.']);
            exit;
        }

        $currentUserId = $_SESSION['user']['id'];
        $db = new connect();

        try {
            // --- BƯỚC 1: LẤY THÔNG TIN BÀI VIẾT VÀ KIỂM TRA QUYỀN SỞ HỮU ---
            // Lấy đường dẫn ảnh và video trước khi xóa record
            $sql_select = "SELECT main_image_url FROM articles WHERE id = :post_id AND author_id = :author_id";
            $stmt_select = $db->db->prepare($sql_select);
            $stmt_select->bindValue(':post_id', (int)$postId, PDO::PARAM_INT);
            $stmt_select->bindValue(':author_id', (int)$currentUserId, PDO::PARAM_INT);
            $stmt_select->execute();
            $article = $stmt_select->fetch(PDO::FETCH_ASSOC);

            if (!$article) {
                // Nếu không tìm thấy bài viết hoặc người dùng không phải tác giả
                echo json_encode(['success' => false, 'message' => 'Không tìm thấy bài viết hoặc bạn không có quyền xóa.']);
                exit;
            }

            // Lấy đường dẫn video từ bảng media
            $videos = MediaModel::getMediaByArticle((int)$postId);
            $videoPath = !empty($videos) ? $videos[0]['file_path'] : null;
            $mainImagePath = $article['main_image_url'];

            // --- BƯỚC 2: XÓA BÀI VIẾT KHỎI CSDL ---
            // Câu lệnh DELETE vẫn phải kiểm tra author_id một lần nữa để đảm bảo an toàn tuyệt đối
            $sql_delete = "DELETE FROM articles WHERE id = :post_id AND author_id = :author_id";
            $stmt_delete = $db->db->prepare($sql_delete);
            $stmt_delete->bindValue(':post_id', (int)$postId, PDO::PARAM_INT);
            $stmt_delete->bindValue(':author_id', (int)$currentUserId, PDO::PARAM_INT);
            $stmt_delete->execute();

            // 4. Kiểm tra xem có dòng nào thực sự bị xóa không
            if ($stmt_delete->rowCount() > 0) {
                // --- BƯỚC 3: XÓA CÁC FILE VẬT LÝ ---
                // Xóa ảnh chính
                if (!empty($mainImagePath) && file_exists(__DIR__ . '/../../' . $mainImagePath)) {
                    unlink(__DIR__ . '/../../' . $mainImagePath);
                }

                // Xóa video
                if (!empty($videoPath) && file_exists(__DIR__ . '/../../' . $videoPath)) {
                    unlink(__DIR__ . '/../../' . $videoPath);
                }

                // (Tùy chọn) Xóa các media liên quan trong bảng media
                //MediaModel::deleteMediaForArticle((int)$postId);

                echo json_encode(['success' => true, 'message' => 'Đã xóa bài viết thành công.']);
            } else {
                // Trường hợp này hiếm khi xảy ra nếu câu SELECT ở trên đã chạy thành công,
                // nhưng vẫn cần để phòng ngừa race condition.
                echo json_encode(['success' => false, 'message' => 'Không thể xóa bài viết. Vui lòng thử lại.']);
            }
        } catch (PDOException $e) {
            // Ghi lỗi vào log thay vì hiển thị cho người dùng
            error_log("Delete article error: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Lỗi cơ sở dữ liệu. Vui lòng thử lại sau.']);
        }
    }
}
