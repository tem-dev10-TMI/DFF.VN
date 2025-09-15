<?php
class BusinessmenController
{
    // Danh sách tất cả doanh nhân
    public static function index()
    {
        if (!isset($_SESSION['user'])) {
            header("Location: " . BASE_URL . "/login");
            exit;
        }
        require_once 'model/user/userModel.php';
        require_once 'model/user/businessmenModel.php';
        require_once 'model/article/articlesmodel.php';

        $modelArticle = new ArticlesModel();
        $modelUser = new UserModel();
        $modelBusiness = new businessmenModel();

        $userId = $_SESSION['user']['id'];

        $user = $modelUser->getUserById($userId);

        $articles = $modelArticle->getArticleById($userId);

        $role = $_SESSION['user']['role'];
        if ($role === 'businessmen') {
            $business = $modelBusiness->getBusinessByUserId($userId);
            $stats = $modelBusiness->getBusinessStats($userId);
            //Load view
            ob_start();
            $profile_category = 'businessmen';
            require_once 'view/layout/Profile.php';
            $content = ob_get_clean();

            //Load layout
            $profile = true; // đừng ai xóa
            require_once 'view/layout/main.php';
        } else {
            header("Location: " . BASE_URL);
            exit;
        }
    }

    // Chi tiết 1 doanh nhân theo user_id
    public static function show($user_id)
    {
        $biz = businessmenModel::getBusinessByUserId($user_id);

        if (!$biz) {
            // Nếu không có dữ liệu → load 404
            require __DIR__ . '/../views/errors/404.php';
            return;
        }

        require __DIR__ . '/../views/businessmen/show.php';
    }

    // Form tạo doanh nhân
    public static function create()
    {
        require __DIR__ . '/../views/businessmen/create.php';
    }

    // Xử lý lưu doanh nhân mới
    public static function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_id = $_POST['user_id'] ?? '';
            $birth_year = $_POST['birth_year'] ?? '';
            $nationality = $_POST['nationality'] ?? '';
            $education = $_POST['education'] ?? '';
            $position = $_POST['position'] ?? '';

            businessmenModel::registerBusiness($user_id, $birth_year, $nationality, $education, $position);

            header('Location: index.php?controller=businessmen&action=index');

            exit;
        }
    }


    // Form sửa doanh nhân
    public static function editBusiness()
    {
        // 1. Xác thực và Phân quyền: Đảm bảo người dùng đã đăng nhập.
        if (!isset($_SESSION['user']) || !isset($_SESSION['user']['id'])) {
            header("Location: " . BASE_URL . "/login");
            exit;
        }
        $userId = $_SESSION['user']['id'];

        // 2. Khởi tạo Model (chỉ một lần)
        require_once 'model/user/businessmenModel.php';
        $modelBusiness = new businessmenModel();

        // 3. Xử lý yêu cầu POST (gửi form)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Lấy và làm sạch dữ liệu đầu vào.
            $birth_year  = filter_var($_POST['birth_year'] ?? null, FILTER_VALIDATE_INT);
            $nationality = htmlspecialchars($_POST['nationality'] ?? '');
            $education   = htmlspecialchars($_POST['education'] ?? '');
            $position    = htmlspecialchars($_POST['position'] ?? '');

            // Kiểm tra xem hồ sơ doanh nhân đã tồn tại hay chưa bằng cách xem kết quả từ Model.
            $existingBusiness = $modelBusiness->getBusinessByUserId($userId);

            $result = false;
            // Logic được cải thiện: Kiểm tra xem có bản ghi 'id' trong kết quả trả về không.
            if ($existingBusiness && isset($existingBusiness['id'])) {
                // Có hồ sơ -> Cập nhật thông tin hiện có.
                $result = $modelBusiness->updateBusiness(
                    $userId,
                    $birth_year,
                    $nationality,
                    $education,
                    $position
                );
            } else {
                // Chưa có hồ sơ -> Đăng ký hồ sơ mới.
                $result = $modelBusiness->registerBusiness(
                    $userId,
                    $birth_year,
                    $nationality,
                    $education,
                    $position
                );
            }

            // 4. Chuyển hướng người dùng dựa trên kết quả.
            if ($result) {
                header('Location: ' . BASE_URL . '/profile_business?msg=business_updated');
                exit;
            } else {
                header('Location: ' . BASE_URL . '/profile_business?msg=business_failed');
                exit;
            }
        }

        // 5. Xử lý yêu cầu GET (hiển thị form)
        // Lấy dữ liệu hồ sơ hiện tại để điền vào form chỉnh sửa.
        $Business = $modelBusiness->getBusinessByUserId($userId);

        // Tải trang view để hiển thị form.
        require_once "view/page/profileUser.php";
    }

    // Xử lý update doanh nhân
    public static function update($user_id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $birth_year = $_POST['birth_year'] ?? '';
            $nationality = $_POST['nationality'] ?? '';
            $education = $_POST['education'] ?? '';
            $position = $_POST['position'] ?? '';

            businessmenModel::updateBusiness($user_id, $birth_year, $nationality, $education, $position);

            header('Location: index.php?controller=businessmen&action=index');

            exit;
        }
    }


    // Xóa doanh nhân
    public static function destroy($user_id)
    {
        businessmenModel::deleteBusiness($user_id);
        header('Location: index.php?controller=businessmen&action=index');
        exit;
    }
}
