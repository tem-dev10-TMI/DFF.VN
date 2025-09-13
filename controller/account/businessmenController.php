<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once "model/businessmenModel.php";
require_once "model/profileUserModel.php";

class businessmenController
{
    // Trang danh sách doanh nhân (top list)
    public function index()
    {
        $businessmen = businessmenModel::getTopBusinessmen(20);
        require_once "view/businessmen/index.php";
    }

    // Trang profile doanh nhân cụ thể
    public function profile()
    {
        $user_id = $_GET['user_id'] ?? null;

        if (!$user_id) {
            $_SESSION['error'] = "Không tìm thấy user!";
            header("Location: index.php?url=businessmen");
            exit;
        }

        $business = businessmenModel::getBusinessByUserId($user_id);
        $profile  = profileUserModel::getProfileUserByUserId($user_id);

        if (!$business) {
            $_SESSION['error'] = "Người dùng chưa đăng ký doanh nhân!";
            header("Location: index.php?url=businessmen");
            exit;
        }

        // Lấy quá trình công tác
        $careers = businessmenModel::getCareersByBusinessmenId($business['id']);

        require_once "view/businessmen/profile.php";
    }

    // Thêm quá trình công tác
    public function addCareer()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $businessmen_id = $_POST['businessmen_id'];
            $start_year     = $_POST['start_year'];
            $end_year       = $_POST['end_year'];
            $position       = $_POST['position'];
            $company        = $_POST['company'];
            $description    = $_POST['description'];

            $ok = businessmenModel::addBusinessmenCareers(
                $businessmen_id,
                $start_year,
                $end_year,
                $position,
                $company,
                $description
            );

            if ($ok) {
                $_SESSION['success'] = "Thêm quá trình công tác thành công!";
            } else {
                $_SESSION['error'] = "Thêm quá trình công tác thất bại!";
            }
            header("Location: index.php?url=businessmen_profile&user_id=" . $_SESSION['user_id']);
        }
    }

    // Cập nhật quá trình công tác
    public function updateCareer()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id          = $_POST['id'];
            $start_year  = $_POST['start_year'];
            $end_year    = $_POST['end_year'];
            $position    = $_POST['position'];
            $company     = $_POST['company'];
            $description = $_POST['description'];

            $ok = businessmenModel::updateBusinessmenCareers(
                $id,
                $start_year,
                $end_year,
                $position,
                $company,
                $description
            );

            if ($ok) {
                $_SESSION['success'] = "Cập nhật quá trình công tác thành công!";
            } else {
                $_SESSION['error'] = "Cập nhật thất bại!";
            }
            header("Location: " . $_SERVER['HTTP_REFERER']);
        }
    }

    // Xoá quá trình công tác
    public function deleteCareer()
    {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];

            $ok = businessmenModel::deleteBusinessmenCareers($id);

            if ($ok) {
                $_SESSION['success'] = "Xoá quá trình công tác thành công!";
            } else {
                $_SESSION['error'] = "Xoá thất bại!";
            }
            header("Location: " . $_SERVER['HTTP_REFERER']);
        }
    }
}
    