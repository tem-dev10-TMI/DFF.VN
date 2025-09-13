<?php
header('Content-Type: application/json');

// Kiểm tra method POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Phương thức không được hỗ trợ!'
    ]);
    exit;
}

// Lấy dữ liệu từ form
$company_name = isset($_POST['company_name']) ? trim($_POST['company_name']) : '';
$tax_code = isset($_POST['tax_code']) ? trim($_POST['tax_code']) : '';
$business_field = isset($_POST['business_field']) ? trim($_POST['business_field']) : '';
$business_address = isset($_POST['business_address']) ? trim($_POST['business_address']) : '';
$company_size = isset($_POST['company_size']) ? trim($_POST['company_size']) : '';
$website = isset($_POST['website']) ? trim($_POST['website']) : '';
$agree_terms = isset($_POST['agree_terms']) ? true : false;

// Validation dữ liệu
if (empty($company_name) || empty($tax_code) || empty($business_field) || empty($business_address)) {
    echo json_encode([
        'success' => false,
        'message' => 'Vui lòng điền đầy đủ thông tin bắt buộc!'
    ]);
    exit;
}

if (!$agree_terms) {
    echo json_encode([
        'success' => false,
        'message' => 'Vui lòng đồng ý với điều khoản sử dụng!'
    ]);
    exit;
}

// Kiểm tra mã số thuế (format cơ bản)
if (!preg_match('/^[0-9]{10,13}$/', $tax_code)) {
    echo json_encode([
        'success' => false,
        'message' => 'Mã số thuế không hợp lệ! Vui lòng nhập 10-13 chữ số.'
    ]);
    exit;
}

// Kiểm tra website URL nếu có
if (!empty($website) && !filter_var($website, FILTER_VALIDATE_URL)) {
    echo json_encode([
        'success' => false,
        'message' => 'Website không hợp lệ! Vui lòng nhập URL đúng định dạng.'
    ]);
    exit;
}

// TODO: Thêm logic kiểm tra điều kiện chuyển đổi
// Ví dụ: kiểm tra số bài viết tối thiểu, thời gian tham gia, v.v.
$min_posts_required = 10; // Số bài viết tối thiểu
$min_followers_required = 50; // Số người theo dõi tối thiểu
$min_account_age_days = 30; // Số ngày tài khoản phải tồn tại

// Giả sử lấy thông tin user từ session hoặc database
// Ở đây mình sẽ giả lập dữ liệu
$user_posts = 5; // Thay bằng dữ liệu thực từ database
$user_followers = 20; // Thay bằng dữ liệu thực từ database
$user_account_age_days = 15; // Thay bằng dữ liệu thực từ database

// Kiểm tra điều kiện chuyển đổi
$errors = [];

if ($user_posts < $min_posts_required) {
    $errors[] = "Cần ít nhất {$min_posts_required} bài viết để chuyển đổi (hiện tại: {$user_posts})";
}

if ($user_followers < $min_followers_required) {
    $errors[] = "Cần ít nhất {$min_followers_required} người theo dõi để chuyển đổi (hiện tại: {$user_followers})";
}

if ($user_account_age_days < $min_account_age_days) {
    $errors[] = "Tài khoản phải tồn tại ít nhất {$min_account_age_days} ngày để chuyển đổi (hiện tại: {$user_account_age_days} ngày)";
}

// Nếu có lỗi điều kiện
if (!empty($errors)) {
    $error_message = "Chưa đủ điều kiện để chuyển đổi:\n" . implode("\n", $errors);
    echo json_encode([
        'success' => false,
        'message' => $error_message
    ]);
    exit;
}

// TODO: Lưu thông tin chuyển đổi vào database
// Ví dụ:
/*
$conversion_data = [
    'user_id' => $_SESSION['user_id'], // Lấy từ session
    'company_name' => $company_name,
    'tax_code' => $tax_code,
    'business_field' => $business_field,
    'business_address' => $business_address,
    'company_size' => $company_size,
    'website' => $website,
    'status' => 'pending', // pending, approved, rejected
    'created_at' => date('Y-m-d H:i:s'),
    'updated_at' => date('Y-m-d H:i:s')
];

// Lưu vào database
// INSERT INTO business_conversions (...) VALUES (...)
*/

// Giả lập lưu thành công
$conversion_id = rand(1000, 9999); // Thay bằng ID thực từ database

// Gửi email thông báo (nếu cần)
// TODO: Gửi email cho admin và user

// Trả về kết quả thành công
echo json_encode([
    'success' => true,
    'message' => "Yêu cầu chuyển đổi đã được gửi thành công!\nMã yêu cầu: #{$conversion_id}\nChúng tôi sẽ xem xét và phản hồi trong vòng 1-3 ngày làm việc.\nBạn sẽ nhận được thông báo qua email khi có kết quả.",
    'conversion_id' => $conversion_id
]);
?>
