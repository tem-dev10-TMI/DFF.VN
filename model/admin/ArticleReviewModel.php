<?php
class ArticleReviewModel
{
    protected PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /* ====== Helper: lấy tên người dùng an toàn ====== */
    protected function resolveUserName(int $userId): string
    {
        $st = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
        $st->execute([$userId]);
        $u = $st->fetch(PDO::FETCH_ASSOC) ?: [];

        // Lấy theo ưu tiên cột nào có thì dùng
        foreach (['full_name', 'name', 'display_name', 'username'] as $k) {
            if (isset($u[$k]) && trim((string) $u[$k]) !== '') {
                return (string) $u[$k];
            }
        }
        // fallback cuối cùng: email hoặc "User #id"
        if (!empty($u['email']))
            return (string) $u['email'];
        return 'User #' . $userId;
    }

    /* ====== LIST ====== */
    // Lấy bài pending (có phân trang)
    public function getPendingArticles(int $limit = 10, int $offset = 0): array
    {
        $sql = "SELECT a.id, a.title, a.summary, a.content, a.main_image_url,
                   a.author_id, a.published_at, a.created_at
            FROM articles a
            WHERE a.status = 'pending'
            ORDER BY a.created_at DESC
            LIMIT :lim OFFSET :off";
        $st = $this->pdo->prepare($sql);
        $st->bindValue(':lim', $limit, PDO::PARAM_INT);
        $st->bindValue(':off', $offset, PDO::PARAM_INT);
        $st->execute();
        $rows = $st->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as &$r) {
            $r['author_name'] = $this->resolveUserName((int) $r['author_id']);
        }
        return $rows;
    }

    public function countPendingArticles(): int
    {
        return (int) $this->pdo->query("SELECT COUNT(*) FROM articles WHERE status='pending'")->fetchColumn();
    }

    // Lấy bài đã duyệt/xử lý (có phân trang)
    public function getReviewedArticles(int $limit = 10, int $offset = 0): array
    {
        $sql = "SELECT r.id, r.article_id, r.action, r.reason, r.reviewed_at,
                   a.title, a.author_id, r.admin_id
            FROM article_reviews r
            JOIN articles a ON a.id = r.article_id
            ORDER BY r.reviewed_at DESC
            LIMIT :lim OFFSET :off";
        $st = $this->pdo->prepare($sql);
        $st->bindValue(':lim', $limit, PDO::PARAM_INT);
        $st->bindValue(':off', $offset, PDO::PARAM_INT);
        $st->execute();
        $rows = $st->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as &$r) {
            $r['author_name'] = $this->resolveUserName((int) $r['author_id']);
            $r['admin_name'] = $this->resolveUserName((int) $r['admin_id']);
        }
        return $rows;
    }

    public function countReviewedArticles(): int
    {
        // Đếm theo log review
        return (int) $this->pdo->query("SELECT COUNT(*) FROM article_reviews")->fetchColumn();
    }

    /* ====== DETAIL ====== */
    public function getArticleFull(int $articleId): array
    {
        $st = $this->pdo->prepare(
            "SELECT a.*
             FROM articles a
             WHERE a.id = ?"
        );
        $st->execute([$articleId]);
        $article = $st->fetch(PDO::FETCH_ASSOC) ?: [];
        if ($article) {
            $article['author_name'] = $this->resolveUserName((int) $article['author_id']);
        }

        $st = $this->pdo->prepare(
            "SELECT id, article_id, position, title, content
             FROM article_sections
             WHERE article_id = ?
             ORDER BY position ASC, id ASC"
        );
        $st->execute([$articleId]);
        $sections = $st->fetchAll(PDO::FETCH_ASSOC);

        $secIds = array_column($sections, 'id');
        $mediaBySection = [];
        if ($secIds) {
            $in = implode(',', array_fill(0, count($secIds), '?'));
            $sql = "SELECT asm.section_id,
                           m.id, m.media_url, m.media_type, m.caption,
                           COALESCE(asm.position, 0) AS pos
                    FROM article_section_media asm
                    JOIN media m ON m.id = asm.media_id
                    WHERE asm.section_id IN ($in)
                    ORDER BY asm.position ASC, m.id ASC";
            $st = $this->pdo->prepare($sql);
            $st->execute($secIds);
            while ($row = $st->fetch(PDO::FETCH_ASSOC)) {
                $mediaBySection[(int) $row['section_id']][] = [
                    'id' => (int) $row['id'],
                    'url' => $row['media_url'],
                    'media_url' => $row['media_url'],
                    'type' => $row['media_type'],
                    'media_type' => $row['media_type'],
                    'caption' => $row['caption'],
                    'position' => (int) $row['pos'],
                ];
            }
        }
        foreach ($sections as &$s) {
            $s['media'] = $mediaBySection[(int) $s['id']] ?? [];
        }

        $st = $this->pdo->prepare(
            "SELECT m.id, m.media_url, m.media_type, m.caption
             FROM media m
             LEFT JOIN article_section_media asm ON asm.media_id = m.id
             WHERE m.article_id = ? AND asm.media_id IS NULL
             ORDER BY m.id ASC"
        );
        $st->execute([$articleId]);
        $articleMedia = $st->fetchAll(PDO::FETCH_ASSOC);

        return [
            'article' => $article,
            'sections' => $sections,
            'articleMedia' => $articleMedia,
        ];
    }

    /* ====== ACTIONS ====== */
    public function approve(int $articleId, int $adminId): bool
    {
        $this->pdo->beginTransaction();
        try {
            $st = $this->pdo->prepare(
                "UPDATE articles
                 SET status='approved', published_at=NOW(), updated_at=NOW()
                 WHERE id=?"
            );
            $st->execute([$articleId]);

            $st = $this->pdo->prepare(
                "INSERT INTO article_reviews(article_id, admin_id, action, reason, reviewed_at)
                 VALUES(?, ?, 'approved', NULL, NOW())"
            );
            $st->execute([$articleId, $adminId]);

            $this->pdo->commit();
            return true;
        } catch (\Throwable $e) {
            $this->pdo->rollBack();
            return false;
        }
    }

    public function reject(int $articleId, int $adminId, string $reason): bool
    {
        $this->pdo->beginTransaction();
        try {
            $st = $this->pdo->prepare(
                "UPDATE articles
                 SET status='rejected', updated_at=NOW()
                 WHERE id=?"
            );
            $st->execute([$articleId]);

            $st = $this->pdo->prepare(
                "INSERT INTO article_reviews(article_id, admin_id, action, reason, reviewed_at)
                 VALUES(?, ?, 'rejected', ?, NOW())"
            );
            $st->execute([$articleId, $adminId, $reason]);

            $this->pdo->commit();
            return true;
        } catch (\Throwable $e) {
            $this->pdo->rollBack();
            return false;
        }
    }
    public function deleteArticle(int $articleId, int $adminId, string $reason): bool
    {
        $this->pdo->beginTransaction();
        try {
            // Xoá quan hệ section-media
            $st = $this->pdo->prepare(
                "DELETE asm FROM article_section_media asm
             JOIN article_sections s ON s.id = asm.section_id
             WHERE s.article_id = ?"
            );
            $st->execute([$articleId]);

            // Xoá sections
            $st = $this->pdo->prepare("DELETE FROM article_sections WHERE article_id = ?");
            $st->execute([$articleId]);

            // Xoá media thuộc bài (nếu media chỉ dùng cho bài)
            $st = $this->pdo->prepare("DELETE FROM media WHERE article_id = ?");
            $st->execute([$articleId]);

            // Ghi log trước khi xoá article
            $st = $this->pdo->prepare(
                "INSERT INTO article_reviews(article_id, admin_id, action, reason, reviewed_at)
             VALUES(?, ?, 'deleted', ?, NOW())"
            );
            $st->execute([$articleId, $adminId, $reason]);

            // Xoá article
            $st = $this->pdo->prepare("DELETE FROM articles WHERE id = ?");
            $st->execute([$articleId]);

            $this->pdo->commit();
            return true;
        } catch (\Throwable $e) {
            $this->pdo->rollBack();
            return false;
        }
    }

}
