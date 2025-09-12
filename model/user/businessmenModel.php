<?php
class businessmenModel
{
    // =============== Lấy thông tin doanh nhân theo user_id ===============
    public static function getBusinessByUserId($user_id)
    {
        $db = new connect();
        $sql = "SELECT * FROM businessmen WHERE user_id = :user_id";
        $stmt = $db->db->prepare($sql);
        $stmt->execute([':user_id' => $user_id]);
        return $stmt->fetch();
    }

    // =============== Đăng kí doanh nhân dựa trên users ===============
    public static function registerBusiness($user_id, $birth_year, $nationality, $education, $position)
    {
        $db = new connect();
        $sql = "INSERT INTO businessmen (user_id, birth_year, nationality, education, position) 
                VALUES (:user_id, :birth_year, :nationality, :education, :position)";
        $stmt = $db->db->prepare($sql);
        return $stmt->execute([
            ':user_id' => $user_id,
            ':birth_year' => $birth_year,
            ':nationality' => $nationality,
            ':education' => $education,
            ':position' => $position
        ]);
    }
    // =============== Cập nhật thông tin doanh nhân ===============
    public static function updateBusiness($user_id, $birth_year, $nationality, $education, $position)
    {
        $db = new connect();
        $sql = "UPDATE businessmen 
                SET birth_year = :birth, nationality = :nationality, education = :education, position = :position 
                WHERE user_id = :user_id";
        $stmt = $db->db->prepare($sql);
        return $stmt->execute([
            ':user_id' => $user_id,
            ':birth' => $birth_year,
            ':nationality' => $nationality,
            ':education' => $education,
            ':position' => $position
        ]);
    }

    // =============== Xóa thông tin doanh nhân ===============
    public static function deleteBusiness($user_id)
    {
        $db = new connect();
        $sql = "DELETE FROM businessmen WHERE user_id = :user_id";
        $stmt = $db->db->prepare($sql);
        return $stmt->execute([':user_id' => $user_id]);
    }

    //==========================================================================================
    //================================ Quá trình công tác ======================================
    //==========================================================================================

    // =============== Lấy danh sách quá trình công tác của doanh nhân ===============
    public static function getCareersByBusinessmenId($businessmen_id)
    {
        $db = new connect();
        $sql = "SELECT * FROM businessmen_careers WHERE businessmen_id = :businessmen_id ORDER BY start_year DESC";
        $stmt = $db->db->prepare($sql);
        $stmt->execute([':businessmen_id' => $businessmen_id]);
        return $stmt->fetchAll();
    }

    // =============== Thêm quá trình công tác của doanh nhân ===============
    public static function addBusinessmenCareers($businessmen_id, $start_year, $end_year, $position, $company, $description)
    {
        $db = new connect();
        $sql = "INSERT INTO businessmen_careers (businessmen_id, start_year, end_year, position, company, description) 
                VALUES (:businessmen_id, :start_year, :end_year, :position, :company, :description)";
        $stmt = $db->db->prepare($sql);
        return $stmt->execute([
            ':businessmen_id' => $businessmen_id,
            ':start_year' => $start_year,
            ':end_year' => $end_year,
            ':position' => $position,
            ':company' => $company,
            ':description' => $description
        ]);
    }

    // =============== Cập nhật quá trình công tác của doanh nhân ===============
    public static function updateBusinessmenCareers($id, $start_year, $end_year, $position, $company, $description)
    {
        $db = new connect();
        $sql = "UPDATE businessmen_careers 
                SET start_year = :start_year, end_year = :end_year, position = :position, company = :company, description = :description 
                WHERE id = :id";
        $stmt = $db->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':start_year' => $start_year,
            ':end_year' => $end_year,
            ':position' => $position,
            ':company' => $company,
            ':description' => $description
        ]);
    }

    // =============== Xóa quá trình công tác của doanh nhân ===============
    public static function deleteBusinessmenCareers($id)
    {
        $db = new connect();
        $sql = "DELETE FROM businessmen_careers WHERE id = :id";
        $stmt = $db->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
