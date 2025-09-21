<?php
require_once __DIR__ . '/../model/article/articlesmodel.php';

class ArticlesController
{
    // Danh sách bài viết
    public static function index()
    {
        $articles = ArticlesModel::getAllArticles();
        // nạp view
        require_once __DIR__ . '/../view/articles/index.php';
    }

    // Chi tiết 1 bài viết
    public static function show($id)
    {
        $article = ArticlesModel::getArticleById($id);
        if (!$article) {
            // có thể chuyển hướng hoặc load trang 404
            require_once __DIR__ . '/../view/errors/404.php';
            return;
        }
        require_once __DIR__ . '/../view/articles/show.php';
    }

    // Form tạo bài viết
    public static function create()
    {
        require_once __DIR__ . '/../view/articles/create.php';
    }

    // Xử lý lưu bài viết
    public static function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title   = $_POST['title']   ?? '';
            $summary = $_POST['summary'] ?? '';
            $content = $_POST['content'] ?? '';
            $image   = $_POST['main_image_url'] ?? '';
            $author  = $_POST['author_id'] ?? '';
            $topic   = $_POST['topic_id'] ?? '';

            ArticlesModel::addArticle($title, $summary, $content, $image, $author, $topic);
            header('Location: index.php?controller=articles&action=index');
            exit;
        }
    }

    // Form sửa
    public static function edit($id)
    {
        $article = ArticlesModel::getArticleById($id);
        require_once __DIR__ . '/../view/articles/edit.php';
    }

    // Xử lý update
    public static function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            ArticlesModel::updateArticle(
                $id,
                $_POST['title'],
                $_POST['summary'],
                $_POST['content'],
                $_POST['main_image_url'],
                $_POST['topic_id'],
                $_POST['status'] ?? 'public',
                $_POST['is_hot'] ?? 0,
                $_POST['is_analysis'] ?? 0
            );
            header('Location: index.php?controller=articles&action=index');
            exit;
        }
    }

    // Xoá bài viết
    public static function destroy($id)
    {
        ArticlesModel::deleteArticle($id);
        header('Location: index.php?controller=articles&action=index');
        exit;
    }




    // protected $pdo;

    // public function __construct($pdo)
    // {
    //     $this->pdo = $pdo;
    // }

    // Danh sách bài viết chờ duyệt
    // public function reviewList()
    // {
    //     $stmt = $this->pdo->query(
    //         "SELECT a.*, u.username AS author_name
    //              FROM articles a
    //              JOIN users u ON a.author_id = u.id
    //              WHERE a.status = 'pending'
    //              ORDER BY a.published_at DESC"
    //     );
    //     $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

    //     include __DIR__ . '/../../view/admin/views/articles/review_list.php';
    // }

    // Thực hiện duyệt hoặc từ chối
    // public function reviewAction()
    // {
    //     $article_id = $_GET['id'] ?? null;
    //     $action = $_GET['do'] ?? null;
    //     $reason = $_POST['reason'] ?? null;
    //     $admin_id = $_SESSION['user']['id'] ?? null;

    //     if ($article_id && in_array($action, ['approved', 'rejected'])) {
    //         // Cập nhật trạng thái
    //         $stmt = $this->pdo->prepare("UPDATE articles SET status = :status WHERE id = :id");
    //         $stmt->execute([
    //             ':status' => $action == 'approved' ? 'public' : 'rejected',
    //             ':id' => $article_id
    //         ]);

    //         // Lưu lịch sử duyệt
    //         require_once __DIR__ . '/../../model/admin/ArticleReview.php';
    //         $reviewModel = new ArticleReview($this->pdo);
    //         $reviewModel->addReview($article_id, $admin_id, $action, $reason);
    //     }

    //     header("Location: admin.php?route=article&action=reviewList");
    //     exit;
    // }
    public static function details_blog($slug)
    {
        require_once __DIR__ . '/../model/article/articlesmodel.php';
        require_once __DIR__ . '/../model/user/userModel.php';

        $currentUserId = null;
        if (isset($_SESSION['user']['id'])) {
            $currentUserId = $_SESSION['user']['id'];
        }

        // Lấy dữ liệu từ model
        $article = ArticlesModel::getArticleBySlug($slug, $currentUserId);
        $authorId = UserModel::getUserById($article['author_id']);
        $user = UserModel::getUserById($authorId['id']);

        // Lấy bài viết liên quan
        $relatedArticles = ArticlesModel::getRelatedArticles($article['topic_id'], $article['id'], 5);

        if (!$article) {
            require_once __DIR__ . '/../view/errors/404.php';
            return;
        }

        // Nạp view
        ob_start();
        require_once __DIR__ . '/../view/page/details_Blog.php';
        $content = ob_get_clean();

        // Layout chính
        $profile = false;
        require_once __DIR__ . '/../view/layout/main.php';
    }
}
