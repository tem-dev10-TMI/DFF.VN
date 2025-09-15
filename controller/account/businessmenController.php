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
        if (!isset($_SESSION['user'])) {
            header("Location: " . BASE_URL . "/login");
            exit;
        }
        $userId = $_SESSION['user']['id'];
        require_once 'model/user/businessmenModel.php';
        $modelBusiness = new businessmenModel();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $birth_year = $_POST['birth_year'] ?? null;
            $nationality   = $_POST['nationality'] ?? null;
            $education   = $_POST['education'] ?? null;
            $position   = $_POST['position'] ?? null;

            // Kiểm tra xem user đã có hồ sơ chưa
            $Business  = $modelBusiness->getBusinessByUserId($userId);
            if ($Business) {
                // Có rồi → update
                $result = $modelBusiness->updateBusiness($userId, $birth_year, $nationality, $education, $position);
            } else {
                // Chưa có → insert mới
                $result = $modelBusiness->registerBusiness($userId, $birth_year, $nationality, $education, $position);
            }

            if ($result) {
                header('Location: ' . BASE_URL . '/profile_business?msg=profile_updated');
                exit;
            } else {
                header('Location: ' . BASE_URL . '/profile_business?msg=profile_failed');
                exit;
            }
        }

        $Business  = $modelBusiness->getBusinessByUserId($userId);
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
