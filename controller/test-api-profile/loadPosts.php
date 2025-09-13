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

// Lấy dữ liệu từ request
$input = json_decode(file_get_contents('php://input'), true);
$profile_category = isset($input['profile_category']) ? $input['profile_category'] : '';
$user_id = isset($input['user_id']) ? intval($input['user_id']) : 0;

// Data structure mẫu để test
$sample_posts = [
    [
        'id' => 1,
        'title' => 'Thị trường tài chính hôm nay có nhiều biến động tích cực',
        'content' => 'Các chỉ số chính đều tăng trưởng mạnh mẽ. VN-Index tăng 2.5%, HNX-Index tăng 1.8%. Các cổ phiếu ngân hàng và bất động sản dẫn đầu xu hướng tăng. Nhà đầu tư nên chú ý theo dõi các tín hiệu từ thị trường quốc tế...',
        'author_name' => $profile_category == 'businessmen' ? 'CTCP Tài chính số' : 'Nguyễn Văn A',
        'author_id' => $user_id,
        'avatar' => 'https://i.pinimg.com/1200x/83/0e/ea/830eea38f7a5d3d8e390ba560d14f39c.jpg',
        'time_ago' => '2 giờ trước',
        'image' => 'https://via.placeholder.com/600x300/4a90e2/ffffff?text=Financial+Market+Analysis',
        'likes_count' => 15,
        'comments_count' => 8
    ],
    [
        'id' => 2,
        'title' => 'Phân tích xu hướng đầu tư Crypto trong năm 2024',
        'content' => 'Bitcoin và Ethereum tiếp tục cho thấy sự ổn định sau giai đoạn biến động. Các altcoin mới nổi như Solana, Cardano đang thu hút sự chú ý của nhà đầu tư. Xu hướng DeFi và NFT vẫn đang phát triển mạnh mẽ...',
        'author_name' => $profile_category == 'businessmen' ? 'CTCP Tài chính số' : 'Nguyễn Văn A',
        'author_id' => $user_id,
        'avatar' => 'https://i.pinimg.com/1200x/83/0e/ea/830eea38f7a5d3d8e390ba560d14f39c.jpg',
        'time_ago' => '5 giờ trước',
        'image' => 'https://via.placeholder.com/600x300/7b68ee/ffffff?text=Crypto+Analysis+2024',
        'likes_count' => 23,
        'comments_count' => 12
    ],
    [
        'id' => 3,
        'title' => 'Hướng dẫn đầu tư chứng khoán cho người mới bắt đầu',
        'content' => 'Đầu tư chứng khoán không phải là trò chơi may rủi. Cần có kiến thức cơ bản về thị trường, phân tích kỹ thuật và cơ bản. Bắt đầu với số tiền nhỏ, học hỏi từ các chuyên gia và không bao giờ đầu tư quá khả năng tài chính của mình...',
        'author_name' => $profile_category == 'businessmen' ? 'CTCP Tài chính số' : 'Nguyễn Văn A',
        'author_id' => $user_id,
        'avatar' => 'https://i.pinimg.com/1200x/83/0e/ea/830eea38f7a5d3d8e390ba560d14f39c.jpg',
        'time_ago' => '1 ngày trước',
        'image' => null,
        'likes_count' => 8,
        'comments_count' => 5
    ],
    [
        'id' => 4,
        'title' => 'Cập nhật lãi suất ngân hàng tháng 12/2024',
        'content' => 'Các ngân hàng lớn đã điều chỉnh lãi suất tiết kiệm và cho vay. Lãi suất tiết kiệm kỳ hạn 12 tháng dao động từ 6.5-7.2%/năm. Lãi suất cho vay mua nhà từ 8.5-9.5%/năm. Nhà đầu tư nên so sánh các gói sản phẩm trước khi quyết định...',
        'author_name' => $profile_category == 'businessmen' ? 'CTCP Tài chính số' : 'Nguyễn Văn A',
        'author_id' => $user_id,
        'avatar' => 'https://i.pinimg.com/1200x/83/0e/ea/830eea38f7a5d3d8e390ba560d14f39c.jpg',
        'time_ago' => '2 ngày trước',
        'image' => 'https://via.placeholder.com/600x300/32cd32/ffffff?text=Bank+Interest+Rates',
        'likes_count' => 31,
        'comments_count' => 18
    ],
    [
        'id' => 5,
        'title' => 'Xu hướng Fintech tại Việt Nam năm 2024',
        'content' => 'Thị trường Fintech Việt Nam đang phát triển mạnh mẽ với sự xuất hiện của nhiều startup mới. Mobile banking, digital wallet, và blockchain đang thay đổi cách thức giao dịch tài chính. Cơ hội đầu tư rất lớn cho các nhà đầu tư có tầm nhìn dài hạn...',
        'author_name' => $profile_category == 'businessmen' ? 'CTCP Tài chính số' : 'Nguyễn Văn A',
        'author_id' => $user_id,
        'avatar' => 'https://i.pinimg.com/1200x/83/0e/ea/830eea38f7a5d3d8e390ba560d14f39c.jpg',
        'time_ago' => '3 ngày trước',
        'image' => 'https://via.placeholder.com/600x300/ff6b6b/ffffff?text=Fintech+Vietnam+2024',
        'likes_count' => 42,
        'comments_count' => 25
    ]
];

// Simulate delay để test loading
sleep(1);

// Trả về dữ liệu
echo json_encode([
    'success' => true,
    'message' => 'Load bài viết thành công!',
    'posts' => $sample_posts,
    'total' => count($sample_posts),
    'profile_category' => $profile_category,
    'user_id' => $user_id
]);
?>
