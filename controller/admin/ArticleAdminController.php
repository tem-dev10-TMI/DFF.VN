<?php
// controller/admin/ArticleAdminController.php
class ArticleAdminController
{
    protected PDO $pdo;
    protected ArticleReviewModel $model;

    public function __construct(PDO $pdo)
    {
        require_once __DIR__ . '/../../model/admin/ArticleReviewModel.php';
        $this->pdo = $pdo;
        $this->model = new ArticleReviewModel($pdo);
        $this->ensureAdmin();
    }

    protected function ensureAdmin(): void
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();
        $role = $_SESSION['user']['role'] ?? '';
        if ($role !== 'admin') {
            http_response_code(403);
            exit('Forbidden');
        }
    }

    /* ====== GET: danh sách + render view ====== */
    public function reviewList(): void
    {
        $perPage = 10;

        // Trang của bảng pending (p1) và reviewed (p2)
        $pageP = max(1, (int) ($_GET['p1'] ?? 1));
        $pageR = max(1, (int) ($_GET['p2'] ?? 1));
        $offP = ($pageP - 1) * $perPage;
        $offR = ($pageR - 1) * $perPage;

        // ----- FILTERS cho REVIEWED -----
        $filtersReviewed = [
            'title' => trim($_GET['r_title'] ?? ''),
            'admin' => trim($_GET['r_admin'] ?? ''),
            'status' => trim($_GET['r_status'] ?? ''),      // 'approved' | 'rejected' | ''
            'from' => trim($_GET['r_from'] ?? ''),      // YYYY-MM-DD
            'to' => trim($_GET['r_to'] ?? ''),      // YYYY-MM-DD
        ];
        // chuẩn hóa status
        if (!in_array($filtersReviewed['status'], ['', 'approved', 'rejected'], true)) {
            $filtersReviewed['status'] = '';
        }
        // chuẩn hóa định dạng ngày
        foreach (['from', 'to'] as $k) {
            if ($filtersReviewed[$k] && !preg_match('~^\d{4}-\d{2}-\d{2}$~', $filtersReviewed[$k])) {
                $filtersReviewed[$k] = '';
            }
        }

        // ----- PENDING -----
        $articles = $this->model->getPendingArticles($perPage, $offP);
        $pendingTotal = $this->model->countPendingArticles();
        $pendingPages = max(1, (int) ceil($pendingTotal / $perPage));

        // ----- REVIEWED (có filter) -----
        // YÊU CẦU: trong ArticleReviewModel phải có 2 hàm dưới (ở phần 2)
        $reviewedArticles = $this->model->getReviewedArticles($filtersReviewed, $perPage, $offR);
        $reviewedTotal = $this->model->countReviewedArticles($filtersReviewed);
        $reviewedPages = max(1, (int) ceil($reviewedTotal / $perPage));

        // ----- DETAIL -----
        $details = [];
        foreach ($articles as $a) {
            $details[$a['id']] = $this->model->getArticleFull((int) $a['id']);
        }

        $reviewDetails = [];
        foreach ($reviewedArticles as $r) {
            $reviewDetails[$r['id']] = $this->model->getArticleFull((int) $r['article_id']);
        }

        // ----- PAGING INFO -----
        $paging = [
            'perPage' => $perPage,
            'pageP' => $pageP,
            'pendingPages' => $pendingPages,
            'pendingTotal' => $pendingTotal,
            'pageR' => $pageR,
            'reviewedPages' => $reviewedPages,
            'reviewedTotal' => $reviewedTotal,
        ];

        // GỬI FILTER SANG VIEW để hiện form & chips
        include __DIR__ . '/../../view/admin/views/articles/review_list.php';
    }

    /* ====== POST/GET: hành động approve/reject ====== */
    public function reviewAction(int $id): void
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();
        $adminId = (int) ($_SESSION['user']['id'] ?? 0);
        $do = $_GET['do'] ?? ($_POST['do'] ?? '');

        if ($do === 'approved') {
            $ok = $this->model->approve($id, $adminId);
            header('Location: ' . BASE_URL . '/admin.php?route=article&action=reviewList&ok=' . (int) $ok);
            exit;
        }

        if ($do === 'rejected') {
            $reason = trim($_POST['reason'] ?? '');
            if ($reason === '')
                $reason = 'Không nêu lý do';
            $ok = $this->model->reject($id, $adminId, $reason);
            header('Location: ' . BASE_URL . '/admin.php?route=article&action=reviewList&ok=' . (int) $ok);
            exit;
        }

        http_response_code(400);
        echo 'Bad request';
    }

    public function deleteAction(int $id): void
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();
        $adminId = (int) ($_SESSION['user']['id'] ?? 0);
        $reason = trim($_POST['reason'] ?? '');
        if ($reason === '')
            $reason = 'Không nêu lý do';

        $ok = $this->model->deleteArticle($id, $adminId, $reason);
        header('Location: ' . BASE_URL . '/admin.php?route=article&action=reviewList&deleted=' . (int) $ok);
        exit;
    }
}
