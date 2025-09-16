<?php
// controller/viewBusinessmenController.php
require_once __DIR__ . "/../../model/user/businessmenModel.php";

class viewBusinessmenController
{
    public function detail($user_id = null)
    {
        if ($user_id === null) {
            $user_id = isset($_GET['id']) ? (int) $_GET['id'] : null;
        }

        if (empty($user_id)) {
            echo "<p>ID không hợp lệ.</p>";
            return;
        }

        // Lấy thông tin user + businessman
        $businessman = businessmenModel::getBusinessByUserId($user_id);

        if (!$businessman) {
            echo "<p>Không tìm thấy doanh nhân.</p>";
            return;
        }

        // Lấy quá trình công tác (cần businessman_id)
        $careers = businessmenModel::getCareersByBusinessmenId($businessman['businessman_id']);

        // Lấy thống kê
        $stats = businessmenModel::getBusinessStats($businessman['user_id']);

        // Gọi view
        include __DIR__ . "/../view/page/viewProfilebusiness.php";
    }
}

