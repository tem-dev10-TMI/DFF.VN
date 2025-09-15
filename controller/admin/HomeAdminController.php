<?php


class HomeAdminController {
    protected $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function dashboard() {
        // Đếm user
        $stmt = $this->pdo->query("SELECT COUNT(*) AS total FROM users");
        $totalUsers = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        // Đếm bài viết
        $stmt = $this->pdo->query("SELECT COUNT(*) AS total FROM articles");
        $totalArticles = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        // Đếm bài viết pending
        $stmt = $this->pdo->query("SELECT COUNT(*) AS total FROM articles WHERE status = 'pending'");
        $pendingArticles = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        // Gọi view
        include __DIR__ . '/../../view/admin/views/dashboard.php';
    }
}

