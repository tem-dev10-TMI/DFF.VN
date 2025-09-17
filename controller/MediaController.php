<?php
require_once __DIR__ . '/../model/mediamodel.php';

class MediaController
{
    /**
     * Xử lý video được tải lên cho một bài viết.
     *
     * @param int $article_id ID của bài viết.
     * @return array Kết quả xử lý.
     */
    public function handleVideoUpload($article_id)
    {
        if (isset($_FILES['post_video']) && $_FILES['post_video']['error'] === UPLOAD_ERR_OK) {
            $video = $_FILES['post_video'];
            $uploadDir = __DIR__ . '/../public/videos/';

            // Tạo thư mục nếu chưa tồn tại
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // Kiểm tra loại file hợp lệ
            $extension = strtolower(pathinfo($video['name'], PATHINFO_EXTENSION));
            $allowedTypes = ['mp4', 'webm', 'ogg', 'mov'];
            if (!in_array($extension, $allowedTypes)) {
                return ['success' => false, 'message' => 'Định dạng video không hợp lệ. Chỉ chấp nhận MP4, WebM, OGG, MOV.'];
            }

            // Tạo tên file mới để tránh trùng lặp
            $newFileName = 'video_' . $article_id . '_' . time() . '.' . $extension;
            $uploadPath = $uploadDir . $newFileName;

            // Di chuyển file vào thư mục uploads
            if (move_uploaded_file($video['tmp_name'], $uploadPath)) {
                // Đường dẫn web-accessible để lưu vào DB
                $media_url = '/public/videos/' . $newFileName;

                // Sử dụng MediaModel để lưu thông tin vào DB
                MediaModel::addMedia($article_id, $media_url, 'video', null);

                return ['success' => true, 'url' => $media_url];
            } else {
                return ['success' => false, 'message' => 'Không thể di chuyển file đã tải lên.'];
            }
        }

        // Không có video hoặc có lỗi
        return ['success' => false, 'message' => 'Không có file video nào được tải lên hoặc đã xảy ra lỗi.'];
    }
}
?>