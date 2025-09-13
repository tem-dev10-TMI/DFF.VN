<?php
class businessmenController
{
    // Trang hồ sơ doanh nhân
    public static function profile($user_id = null)
    {
        require_once 'model/businessmenModel.php';
        $model = new businessmenModel();

        if ($user_id === null && isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
        }

        if (!$user_id) {
            header("Location: index.php?action=login");
            exit;
        }

        $business = $model->getBusinessByUserId($user_id);
        $careers = [];
        if ($business) {
            $careers = $model->getCareersByBusinessmenId($business['id']);
        }

        require_once 'view/businessmen/profile.php';
    }

    // Form thêm mới doanh nhân
    public static function add()
    {
        require_once 'model/businessmenModel.php';
        $model = new businessmenModel();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
            $user_id     = $_SESSION['user_id'];
            $birth_year  = $_POST['birth_year'];
            $nationality = $_POST['nationality'];
            $education   = $_POST['education'];
            $position    = $_POST['position'];

            // kiểm tra follower / like
            $followers = $model->getFollowersCount($user_id);
            $likes     = $model->getLikesCount($user_id);

            if ($followers < 100 || $likes < 1000) {
                header("Location: index.php?action=businessmen_add&msg=not_enough");
                exit;
            }

            if ($model->registerBusiness($user_id, $birth_year, $nationality, $education, $position)) {
                header("Location: index.php?action=businessmen_profile&msg=added");
                exit;
            }
        }

        require_once 'view/businessmen/add.php';
    }

    // Cập nhật hồ sơ
    public static function edit()
    {
        require_once 'model/businessmenModel.php';
        $model = new businessmenModel();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
            $user_id     = $_SESSION['user_id'];
            $birth_year  = $_POST['birth_year'];
            $nationality = $_POST['nationality'];
            $education   = $_POST['education'];
            $position    = $_POST['position'];

            $model->updateBusiness($user_id, $birth_year, $nationality, $education, $position);
            header("Location: index.php?action=businessmen_profile&msg=updated");
            exit;
        }

        $business = $model->getBusinessByUserId($_SESSION['user_id']);
        require_once 'view/businessmen/edit.php';
    }

    // Danh sách top doanh nhân
    public static function top()
    {
        require_once 'model/businessmenModel.php';
        $model = new businessmenModel();

        $top = $model->getTopBusinessmen(10);
        require_once 'view/businessmen/top.php';
    }
}
