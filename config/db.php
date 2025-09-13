<?php
require_once 'config.php';

class connect {
    public $db = null;

    public function __construct(){
        $host = DB_HOST;
        $port = DB_PORT;
        $dbname = DB_NAME;
        $user = DB_USER;
        $pass = DB_PASS;
        
        $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4;port=$port";
        
        try {
            $this->db = new PDO($dsn, $user, $pass, array(
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ));
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    public function getList($select){
        return $this->db->query($select);
    }

    public function exec($query){
        return $this->db->exec($query);
    }

    public function getInstance($select){
        $results = $this->db->query($select);
        return $results->fetch();
    }

    public function prepare($query){
        return $this->db->prepare($query);
    }

    // ======================= Hàm tạo slug tự động =========================
    // VD: Khi thêm bài viết "Phân tích thị trường chứng khoán" ==> phan-tich-thi-truong-chung-khoan
    public static function createSlug($string) {
        $slug = mb_strtolower($string, 'UTF-8');
        //Bỏ dấu
        $slug = preg_replace(
            array('/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/',
                '/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/',
                '/(ì|í|ị|ỉ|ĩ)/',
                '/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/',
                '/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/',
                '/(ỳ|ý|ỵ|ỷ|ỹ)/',
                '/(đ)/'),
            array('a', 'e', 'i', 'o', 'u', 'y', 'd'),
            $slug
        );
        //Bỏ kí tự đặt biệt
        $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);
        $slug = preg_replace('/\s+/', '-', $slug);
        return trim($slug, '-');
    }

    // ======================= Tạo mã ngẫu nhiên =========================
    // Tạo mã bài viết ngẫu nhiên
    public static function generateArticleCode($id) {
        $random = substr(str_shuffle('abcdefghijklmnopqrstuvwxyz0123456789'), 0, 7);
        return 'DFF' . $random . $id; // DFF + 7 ký tự + ID
    }

    // ======================= Format tiền tệ =========================
    public static function formatCurrency($amount) {
        return number_format($amount, 0, ',', '.') . ' VNĐ';
    }

    // ======================= Format ngày tháng =========================
    public static function formatDate($date, $format = 'd/m/Y H:i') {
        return date($format, strtotime($date));
    }
}

$conn = (new connect())->db;
?>
