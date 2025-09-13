<?php
require_once "models/businessmenModel.php";

class viewBusinessmenController
{
    // Hiển thị thông tin chi tiết doanh nhân khi click vào
    public function detail($id)
    {
        // lấy thông tin doanh nhân theo user_id
        $businessman = businessmenModel::getBusinessByUserId($id);

        if (!$businessman) {
            echo "<p>Không tìm thấy doanh nhân.</p>";
            return;
        }

        // lấy danh sách quá trình công tác
        $careers   = businessmenModel::getCareersByBusinessmenId($businessman['id']);
        $followers = businessmenModel::getFollowersCount($businessman['user_id']);
        $likes     = businessmenModel::getLikesCount($businessman['user_id']);

        // gọi view
        include "views/businessmen/detail.php";
    }
}
