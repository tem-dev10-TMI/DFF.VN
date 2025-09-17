<?php
require_once __DIR__."/../../model/businessmenModel.php";

class businessmenController
{
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_id = $_SESSION['user_id'] ?? null;

            if (!$user_id) {
                $_SESSION['error'] = "Bạn cần đăng nhập để đăng ký doanh nhân!";
                header("Location: index.php?action=login");
                exit;
            }

            $birth_year  = $_POST['birth_year'] ?? null;
            $nationality = $_POST['nationality'] ?? null;
            $education   = $_POST['education'] ?? null;
            $position    = $_POST['position'] ?? null;

            // Gọi model để lấy số follow / like
            $followers = businessmenModel::getFollowersCount($user_id);
            $likes     = businessmenModel::getLikesCount($user_id);

            // Kiểm tra điều kiện
            if ($followers < 100 || $likes < 1000) {
                $_SESSION['error'] = "Bạn cần tối thiểu 100 follow và 1000 like để đăng ký doanh nhân. 
                (Hiện tại: $followers follow, $likes like)";
                header("Location: index.php?action=businessmen_form");
                exit;
            }

            // Đăng ký
            $result = businessmenModel::registerBusiness($user_id, $birth_year, $nationality, $education, $position);

            if ($result) {
                $_SESSION['success'] = "Đăng ký doanh nhân thành công!";
                header("Location: index.php?action=businessmen_profile&user_id=$user_id");
            } else {
                $_SESSION['error'] = "Có lỗi xảy ra, vui lòng thử lại!";
                header("Location: index.php?action=businessmen_form");
            }
        }
    }
}
