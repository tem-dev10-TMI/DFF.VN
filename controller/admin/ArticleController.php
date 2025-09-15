<?php
class ArticleController {
    protected $pdo; 
    protected $model;

    public function __construct($pdo) { 
        $this->pdo = $pdo; 
        $this->model = new Article($pdo); 
    }

    public function admin() { 
        $articles = $this->model->listWithJoin(200); 
        include __DIR__ . '/../../view/admin/views/articles/list.php'; 
    }

    public function create() { 
        $topics = (new Topic($this->pdo))->all(200); 
        $users = (new User($this->pdo))->all(200); 
        include __DIR__ . '/../../view/admin/views/articles/form.php'; 
    }

    public function store() {
        if(!empty($_FILES['main_image']['name'])) {
            $fn = basename($_FILES['main_image']['name']);
            $target = UPLOADS_DIR . '/' . time() . '_' . $fn;
            move_uploaded_file($_FILES['main_image']['tmp_name'], $target);
            $_POST['main_image_url'] = str_replace(__DIR__ . '/..', '', $target);
        }
        $this->model->create($_POST);
        redirect(BASE_URL . '/admin.php?route=articles');
    }

    public function edit($id) { 
        $article = $this->model->find($id); 
        $topics = (new Topic($this->pdo))->all(200); 
        $users = (new User($this->pdo))->all(200); 
        include __DIR__ . '/../../view/admin/views/articles/form.php'; 
    }

    public function update($id) {
        if(!empty($_FILES['main_image']['name'])) {
            $fn = basename($_FILES['main_image']['name']);
            $target = UPLOADS_DIR . '/' . time() . '_' . $fn;
            move_uploaded_file($_FILES['main_image']['tmp_name'], $target);
            $_POST['main_image_url'] = str_replace(__DIR__ . '/..', '', $target);
        }
        $this->model->update($id, $_POST);
        redirect(BASE_URL . '/admin.php?route=articles');
    }

    public function delete($id) { 
        $this->model->delete($id); 
        redirect(BASE_URL . '/admin.php?route=articles'); 
    }

   // ✅ Danh sách bài viết chờ duyệt + đã duyệt
public function reviewList() {
    // Lấy danh sách bài pending
    $stmt = $this->pdo->query("
        SELECT a.*, u.username as author_name
        FROM articles a
        JOIN users u ON a.author_id = u.id
        WHERE a.status = 'pending'
        ORDER BY a.published_at DESC
    ");
    $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Lấy danh sách bài đã duyệt
    require_once __DIR__ . '/../../model/admin/ArticleReviewModel.php';
    $reviewModel = new ArticleReviewModel($this->pdo); // ✅ sửa lại đúng class
    $reviewedArticles = $reviewModel->getReviewedArticles();

    // Load view và truyền biến
    include __DIR__ . '/../../view/admin/views/articles/review_list.php';
}


    // ✅ Thực hiện duyệt hoặc từ chối
    public function reviewAction() {
        $article_id = $_GET['id'] ?? null;
        $action = $_GET['do'] ?? null;
        $reason = $_POST['reason'] ?? null;
        $admin_id = $_SESSION['user']['id']; // id admin đăng nhập

        if ($article_id && in_array($action, ['approved', 'rejected'])) {
            // Cập nhật trạng thái bài viết
            $stmt = $this->pdo->prepare("UPDATE articles SET status = :status WHERE id = :id");
            $stmt->execute([
                ':status' => $action == 'approved' ? 'public' : 'rejected',
                ':id' => $article_id
            ]);

            // Ghi lịch sử duyệt
            require_once __DIR__ . '/../../model/admin/ArticleReviewModel.php';
            $reviewModel = new ArticleReview($this->pdo);
            $reviewModel->addReview($article_id, $admin_id, $action, $reason);
        }

        header("Location: admin.php?route=article&action=reviewList");
        exit;
    }

    
}
