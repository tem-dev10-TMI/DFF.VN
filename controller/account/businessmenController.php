<?php

// ví dụ trong controller
//$isFollowing = $model->isFollowing($currentUserId, $businessId);



class businessmenController
{
    // Danh sách tất cả doanh nhân
    public static function index()
    {
        if (!isset($_SESSION['user'])) {
            header("Location: " . BASE_URL . "/login");
            exit;
        }
        
        require_once __DIR__.'/../../model/user/userModel.php';
        require_once __DIR__.'/../../model/user/businessmenModel.php';
        require_once __DIR__.'/../../model/article/articlesmodel.php';

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
            require_once __DIR__.'/../../view/layout/Profile.php';
            $content = ob_get_clean();

            //Load layout
            $profile = true; // đừng ai xóa
            require_once __DIR__.'/../../view/layout/main.php';
        } else {
            header("Location: " . BASE_URL);
            exit;
        }
    }

    // Chi tiết 1 doanh nhân theo user_id
    // public static function show($user_id)
    // {
    //     $biz = businessmenModel::getBusinessByUserId($user_id);

    //     if (!$biz) {
    //         // Nếu không có dữ liệu → load 404
    //         require __DIR__ . '/../views/errors/404.php';
    //         return;
    //     }

    //     require __DIR__ . '/../views/businessmen/show.php';
    // }

    // Form tạo doanh nhân
    // public static function create()
    // {
    //     require __DIR__ . '/../views/businessmen/create.php';
    // }

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
    if (!isset($_SESSION['user']) || !isset($_SESSION['user']['id'])) {
        header("Location: " . BASE_URL . "/login");
        exit;
    }

    $userId = $_SESSION['user']['id'];

    require_once __DIR__ . '/../../model/user/businessmenModel.php';
    require_once __DIR__ . '/../../model/user/userModel.php';

    $modelBusiness = new businessmenModel();
    $modelUser     = new userModel();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $currentUserData = $modelUser->getUserById($userId);

        $avatar_url  = $currentUserData['avatar_url']  ?? null;
        $cover_photo = $currentUserData['cover_photo'] ?? null;

        $avatarDir = __DIR__ . '/../../public/img/avatar/';
        $coverDir  = __DIR__ . '/../../public/img/cover/';

        if (!is_dir($avatarDir)) mkdir($avatarDir, 0777, true);
        if (!is_dir($coverDir))  mkdir($coverDir, 0777, true);

        // Upload avatar
        if (!empty($_FILES['avatar_file']['tmp_name']) && $_FILES['avatar_file']['error'] === 0) {
            $avatarName = uniqid('avatar_') . '_' . time() . '.' . pathinfo($_FILES['avatar_file']['name'], PATHINFO_EXTENSION);
            $avatarPath = $avatarDir . $avatarName;
            if (move_uploaded_file($_FILES['avatar_file']['tmp_name'], $avatarPath)) {
                $avatar_url = BASE_URL . '/public/img/avatar/' . $avatarName;
            }
        }

        // Upload cover
        if (!empty($_FILES['cover_file']['tmp_name']) && $_FILES['cover_file']['error'] === 0) {
            $coverName = uniqid('cover_') . '_' . time() . '.' . pathinfo($_FILES['cover_file']['name'], PATHINFO_EXTENSION);
            $coverPath = $coverDir . $coverName;
            if (move_uploaded_file($_FILES['cover_file']['tmp_name'], $coverPath)) {
                $cover_photo = BASE_URL . '/public/img/cover/' . $coverName;
            }
        }

        // Dữ liệu form
        $name        = htmlspecialchars($_POST['name'] ?? '');
        $username    = $_POST['username'] ?? $currentUserData['username'] ?? '';
        $email       = htmlspecialchars($_POST['email'] ?? '');
        $phone       = htmlspecialchars($_POST['phone'] ?? '');
        $description = htmlspecialchars($_POST['description'] ?? '');

        // Update bảng users
        $successUser = $modelUser->updateUser(
            $userId,
            $name,
            $username,
            $email,
            $phone,
            $avatar_url,
            $cover_photo,
            $description
        );

        // Update bảng businessmen
        $birth_year  = filter_var($_POST['birth_year'] ?? null, FILTER_VALIDATE_INT);
        $nationality = htmlspecialchars($_POST['nationality'] ?? '');
        $education   = htmlspecialchars($_POST['education'] ?? '');
        $position    = htmlspecialchars($_POST['position'] ?? '');

        $existingBusiness = $modelBusiness->getBusinessByUserId($userId);
        $successBusiness  = false;

        if ($existingBusiness && isset($existingBusiness['businessman_id'])) {
            $successBusiness = $modelBusiness->updateBusiness($userId, $birth_year, $nationality, $education, $position);
        } else {
            $successBusiness = $modelBusiness->registerBusiness($userId, $birth_year, $nationality, $education, $position);
        }

        // Update session sau khi user thành công
        if ($successUser) {
            $updatedUser = $modelUser->getUserById($userId);
            foreach (['avatar_url','cover_photo','name','email','phone','description'] as $key) {
                if (isset($updatedUser[$key])) {
                    $_SESSION['user'][$key] = $updatedUser[$key];
                }
            }
        }

        if ($successUser && $successBusiness) {
            header('Location: ' . BASE_URL . '/profile_business?msg=business_updated');
            exit;
        } else {
            header('Location: ' . BASE_URL . '/profile_business?msg=business_failed');
            exit;
        }
    }

    $business  = $modelBusiness->getBusinessByUserId($userId);
    $userData  = $modelUser->getUserById($userId);

    $avatarUrl = $userData['avatar_url']   ?? '/vendor/dffvn/content/img/default-avatar.png';
    $coverUrl  = $userData['cover_photo']  ?? '/vendor/dffvn/content/img/default-cover.jpg';

    require_once __DIR__ . "/../../view/page/profileBusiness.php";
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

    //Xử lý quá trình công tác 
    public static function editBusinessCareer()
    {
        if (!isset($_SESSION['user']) || !isset($_SESSION['user']['id'])) {
            header("Location: " . BASE_URL . "/login");
            exit;
        }
        $userId = $_SESSION['user']['id'];
        require_once __DIR__.'/../../model/user/businessmenModel.php';
        $modelBusiness = new businessmenModel();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Lấy businessman_id từ CSDL
            $businessData = $modelBusiness->getBusinessByUserId($userId);
            if (!$businessData || !isset($businessData['businessman_id'])) {
                header('Location: ' . BASE_URL . '/profile_business?msg=no_business_profile');
                exit;
            }
            $businessmen_id = $businessData['businessman_id'];

            // Lấy dữ liệu từ form
            $career_id = $_POST['career_id'] ?? null;
            $start_year = filter_var($_POST['start_year'] ?? null, FILTER_VALIDATE_INT);
            $end_year = filter_var($_POST['end_year'] ?? null, FILTER_VALIDATE_INT);
            $position = htmlspecialchars($_POST['position'] ?? '');
            $company = htmlspecialchars($_POST['company'] ?? '');
            $description = htmlspecialchars($_POST['description'] ?? '');

            if ($career_id) {
                // Cập nhật quá trình công tác hiện có
                $result = $modelBusiness->updateBusinessmenCareers($id, $start_year, $end_year, $position, $company, $description);
            } else {
                // Thêm mới quá trình công tác
                $result = $modelBusiness->addBusinessmenCareers($businessmen_id, $start_year, $end_year, $position, $company, $description);
            }

            if ($result) {
                header('Location: ' . BASE_URL . '/profile_business?msg=career_updated');
                exit;
            } else {
                header('Location: ' . BASE_URL . '/profile_business?msg=career_failed');
                exit;
            }
        }

        // Xử lý GET, có thể lấy danh sách các quá trình công tác để hiển thị
        $business = $modelBusiness->getBusinessByUserId($userId);
        $careers = $modelBusiness->getCareersByBusinessmenId($business['businessman_id']);
        // Truyền dữ liệu này đến View nếu bạn muốn hiển thị ngay khi load trang

        require_once __DIR__."/../../view/page/profileUser.php";
    }

    
}
