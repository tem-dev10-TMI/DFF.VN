<?php

class TopicController {
    
     protected $pdo; protected $model;
    public function __construct($pdo){ $this->pdo = $pdo; $this->model = new Topic($pdo); }
    // protected $model;

    // public function __construct() {
    //     require_once __DIR__ . '/../../model/article/topicsmodel.php';
    //     require_once __DIR__ . '/../../model/article/articlesmodel.php';
    //     $this->model = new TopicsModel();
    // }

    // ===================== LIST =====================
    public function admin(){ 
        $topics = $this->model->all(200); 
        include __DIR__ . '/../../view/admin/views/topics/list.php'; 
    }

    // ===================== CREATE FORM =====================
    public function create(){ 
        include __DIR__ . '/../../view/admin/views/topics/form.php'; 
    }

    // ===================== STORE =====================
    public function store(){ 
        $data = $_POST;

        // Nếu chưa có slug thì tự tạo
        if (empty($data['slug']) && !empty($data['name'])) {
            $data['slug'] = $this->slugify($data['name']);
        }

        // Upload icon (nếu có)
        $icon = $this->uploadIcon('icon_file');
        if ($icon) {
            $data['icon_url'] = $icon; // ví dụ: /topic_img/abc.webp
        }

        $this->model->create($data); 
        redirect(BASE_URL . '/admin.php?route=topics');
    }

    // ===================== EDIT FORM =====================
    public function edit($id){ 
        $topic = $this->model->find($id); 
        include __DIR__ . '/../../view/admin/views/topics/form.php'; 
    }

    // ===================== UPDATE =====================
    public function update($id){ 
        $data = $_POST;

        $removeIcon = !empty($_POST['remove_icon']);
        $oldIcon    = $_POST['icon_url_old'] ?? null;

        // Upload icon mới nếu có
        $newIcon = $this->uploadIcon('icon_file');

        if ($removeIcon) {
            $data['icon_url'] = null;
            $this->deleteLocalFile($oldIcon);
        } elseif ($newIcon) {
            $data['icon_url'] = $newIcon;
            $this->deleteLocalFile($oldIcon);
        } else {
            unset($data['icon_url']); // giữ nguyên icon cũ
        }

        // Nếu slug rỗng mà có name → tạo lại
        if (array_key_exists('slug', $data) && empty($data['slug']) && !empty($data['name'])) {
            $data['slug'] = $this->slugify($data['name']);
        }

        $this->model->update($id, $data); 
        redirect(BASE_URL . '/admin.php?route=topics');
    }

    // ===================== DELETE =====================
    public function delete($id){ 
        $topic = $this->model->find($id);
        if ($topic && !empty($topic['icon_url'])) {
            $this->deleteLocalFile($topic['icon_url']);
        }

        $this->model->delete($id); 
        redirect(BASE_URL . '/admin.php?route=topics');
    }

// ===================== DETAILS (theo slug) =====================
public function details_topic($slug = null) {
    if (!$slug) $slug = $_GET['slug'] ?? null;
    if (!$slug) { echo "Thiếu slug!"; return; }

    // Lấy danh sách bài viết theo slug chủ đề
    $articles = ArticlesModel::getArticlesByTopicSlug($slug, 10);

    // Nếu không có bài nào => coi như chủ đề không tồn tại
    if (!$articles || count($articles) === 0) {
        echo "Chủ đề không tồn tại!";
        return;
    }

    // Chủ đề có thể lấy từ record đầu tiên trong $articles
    $topic = [
        'slug' => $slug,
        'name' => $articles[0]['topic_name'] ?? 'Chưa đặt tên'
    ];

    ob_start();
    require __DIR__ . '/../../view/page/DetailsTopic.php';
    $content = ob_get_clean();

    $profile = false; 
    require __DIR__ . '/../../view/layout/main.php';
}


    // ===================== helpers =====================
    private function slugify(string $str): string {
        $s = @iconv('UTF-8','ASCII//TRANSLIT', $str);
        $s = preg_replace('~[^a-zA-Z0-9]+~', '-', $s);
        $s = trim($s, '-');
        return strtolower($s);
    }

    // Gốc dự án: controller/admin/TopicController.php -> ../../
    private function projectRoot(): string {
        return realpath(__DIR__ . '/../../');
    }

    /**
     * Upload icon vào public/topic_img, trả về URL tương đối (vd: /topic_img/abc.webp)
     */
   private function uploadIcon(string $field = 'icon_file'): ?string {
    if (empty($_FILES[$field]) || $_FILES[$field]['error'] !== UPLOAD_ERR_OK) {
        error_log("uploadIcon: no file or err=" . ($_FILES[$field]['error'] ?? 'n/a'));
        return null;
    }
    $file = $_FILES[$field];

    $allowExt = ['png','jpg','jpeg','webp','svg'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowExt, true)) {
        error_log("uploadIcon: invalid ext $ext");
        return null;
    }

    if ($ext !== 'svg') {
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime  = $finfo->file($file['tmp_name']);
        $allowMime = ['image/png','image/jpeg','image/webp'];
        if (!in_array($mime, $allowMime, true)) {
            error_log("uploadIcon: invalid mime $mime");
            return null;
        }
        if ($file['size'] > 2 * 1024 * 1024) {
            error_log("uploadIcon: file too large " . $file['size']);
            return null;
        }
    }

    $publicFsDir = $this->projectRoot() . DIRECTORY_SEPARATOR . 'public';
    $destDir     = $publicFsDir . DIRECTORY_SEPARATOR . 'topic_img';
    if (!is_dir($destDir)) { @mkdir($destDir, 0755, true); }

    $safeBase = preg_replace('~[^a-z0-9_-]+~i', '-', pathinfo($file['name'], PATHINFO_FILENAME));
    $filename = $safeBase . '-' . date('Ymd-His') . '-' . substr(sha1(random_bytes(8)),0,8) . '.' . $ext;
    $destPath = $destDir . DIRECTORY_SEPARATOR . $filename;

    if (!move_uploaded_file($file['tmp_name'], $destPath)) {
        error_log("uploadIcon: move_uploaded_file failed");
        return null;
    }
    return 'public/topic_img/' . $filename;
}


    /**
     * Xoá file trong public/topic_img nếu relPath bắt đầu bằng /topic_img/
     */
  private function deleteLocalFile(?string $relPath): void {
    if (!$relPath) return;
    $rel = ltrim($relPath, '/'); // 'public/topic_img/...'
    $full = $this->projectRoot() . DIRECTORY_SEPARATOR . $rel;
    if (is_file($full)) @unlink($full);
}

}
