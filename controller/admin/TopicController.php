<?php
// controller/admin/TopicsController.php
class TopicsController
{
    private PDO $pdo;
    private Topic $model;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->model = new Topic($pdo);
    }

    public function index()
    {
        // danh sách + phân trang, nếu bạn đã có rồi thì giữ nguyên
        $perPage = 10;
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $offset = ($page - 1) * $perPage;

        $topics = $this->model->listPaged($perPage, $offset);
        $total = $this->model->countAll();
        $pages = max(1, (int) ceil($total / $perPage));

        include __DIR__ . '/../../view/admin/views/topics/list.php';
    }

    public function create()
    {
        // hiển thị form rỗng
        $topic = null;
        include __DIR__ . '/../../view/admin/views/topics/form.php';
    }

    public function edit($id)
    {
        // lấy topic theo id (bạn có thể dùng TopicModel::getById nếu muốn)
        $stmt = $this->pdo->prepare("SELECT * FROM topics WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => (int) $id]);
        $topic = $stmt->fetch(PDO::FETCH_ASSOC);
        include __DIR__ . '/../../view/admin/views/topics/form.php';
    }

    public function store()
    {
        try {
            $payload = $this->buildPayloadFromRequest();
            $this->model->create($payload);
            flash('success', 'Tạo topic thành công');
        } catch (Exception $e) {
            flash('error', $e->getMessage());
        }
        header('Location: ' . BASE_URL . '/admin.php?route=topics&action=index');
    }

    public function update($id)
    {
        try {
            $payload = $this->buildPayloadFromRequest();
            $this->model->update((int) $id, $payload);
            flash('success', 'Cập nhật topic thành công');
            // → quay lại danh sách
            header('Location: ' . BASE_URL . '/admin.php?route=topics&action=index');
        } catch (Exception $e) {
            flash('error', $e->getMessage());
            // có lỗi thì ở lại trang edit để sửa tiếp
            header('Location: ' . BASE_URL . '/admin.php?route=topics&action=edit&id=' . (int) $id);
        }
    }

    /* ---------- Helpers ---------- */

    private function buildPayloadFromRequest(): array
    {
        // name, slug, description từ POST
        $name = trim($_POST['name'] ?? '');
        if ($name === '')
            throw new Exception('Tên topic không được để trống');

        $slug = trim($_POST['slug'] ?? '');
        if ($slug === '')
            $slug = $this->slugify($name);

        $description = trim($_POST['description'] ?? '');
        $iconUrl = trim($_POST['icon_url'] ?? ''); // nếu người dùng tự nhập đường dẫn sẵn có

        // Nếu có upload file -> ghi đè iconUrl bằng file mới
        if (!empty($_FILES['icon_file']['name'] ?? '')) {
            $iconUrl = $this->handleIconUpload($_FILES['icon_file']);
        }

        return [
            'name' => $name,
            'slug' => $slug,
            'description' => $description,
            'icon_url' => $iconUrl, // có thể rỗng nếu không nhập/không upload
        ];
    }

    private function slugify(string $str): string
    {
        $s = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $str);
        $s = preg_replace('~[^a-zA-Z0-9]+~', '-', $s);
        $s = strtolower(trim($s, '-'));
        return $s ?: 'topic';
    }

    /** Upload file icon vào /public/topic_img và trả về icon_url tương đối: public/topic_img/xxx.ext */
    private function handleIconUpload(array $file): string
    {
        if ($file['error'] !== UPLOAD_ERR_OK)
            throw new Exception('Upload icon thất bại');

        $allowed = ['image/png' => 'png', 'image/jpeg' => 'jpg', 'image/webp' => 'webp', 'image/svg+xml' => 'svg'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!isset($allowed[$mime]))
            throw new Exception('Định dạng ảnh không được hỗ trợ');
        if ($mime !== 'image/svg+xml' && $file['size'] > 2 * 1024 * 1024)
            throw new Exception('Ảnh vượt quá 2MB');

        $ext = $allowed[$mime];
        $ts = date('Ymd-His');
        $rand = substr(bin2hex(random_bytes(4)), 0, 8);
        $base = 'topic-' . $ts . '-' . $rand . '.' . $ext;

        // Thư mục upload (đường dẫn vật lý)
        $root = dirname(__DIR__, 2); // tới project root
        $dir = $root . '/public/topic_img';
        if (!is_dir($dir)) {
            if (!mkdir($dir, 0775, true) && !is_dir($dir))
                throw new Exception('Không tạo được thư mục upload');
        }

        $dest = $dir . '/' . $base;
        if (!move_uploaded_file($file['tmp_name'], $dest))
            throw new Exception('Không thể lưu file upload');

        // Trả về đường dẫn tương đối để lưu DB
        return 'public/topic_img/' . $base;
    }
}
