<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../../model/article/articlesmodel.php';

// Chỉ chấp nhận POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Phương thức không được hỗ trợ!'
    ]);
    exit;
}

session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Bạn cần đăng nhập để đăng bài!'
    ]);
    exit;
}

$modelArticle = new ArticlesModel();

$title = trim($_POST['title'] ?? '');
$summary = trim($_POST['summary'] ?? '');
$content = trim($_POST['content'] ?? '');
$topic_id = intval($_POST['topic_id'] ?? 0);
$author_id = $_SESSION['user_id'];
$main_image_url = null;

// Upload ảnh
if (isset($_FILES['main_image_url']) && $_FILES['main_image_url']['error'] === UPLOAD_ERR_OK) {
    $upload_dir = __DIR__ . '/../../public/img/articles/';
    if (!file_exists($upload_dir)) mkdir($upload_dir, 0777, true);

    $ext = pathinfo($_FILES['main_image_url']['name'], PATHINFO_EXTENSION);
    $file_name = 'article_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
    $file_path = $upload_dir . $file_name;

    if (move_uploaded_file($_FILES['main_image_url']['tmp_name'], $file_path)) {
        $main_image_url = 'public/img/articles/' . $file_name;
    }
}

if (empty($title) || empty($content)) {
    echo json_encode(['success' => false, 'message' => 'Vui lòng nhập đầy đủ tiêu đề và nội dung!']);
    exit;
}

if (strlen($content) < 10) {
    echo json_encode(['success' => false, 'message' => 'Nội dung phải có ít nhất 10 ký tự!']);
    exit;
}

$newArticleId = $modelArticle->addArticle($title, $summary, $content, $main_image_url, $author_id, $topic_id);

if ($newArticleId) {
    echo json_encode([
        'success' => true,
        'message' => 'Đăng bài thành công!',
        'article' => [
            'id' => $newArticleId,
            'title' => $title,
            'summary' => $summary,
            'content' => $content,
            'topic_id' => $topic_id,
            'author_id' => $author_id,
            'image' => $main_image_url
        ]
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Lỗi khi thêm bài viết vào cơ sở dữ liệu!']);
}
