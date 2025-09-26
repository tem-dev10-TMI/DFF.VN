<?php
class ArticleReviewModel
{
    private PDO $pdo;
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /* ========= Helpers: dò bảng/cột ========= */
    private function tableExists(string $table): bool
    {
        $sql = "SELECT 1 FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name = :t LIMIT 1";
        $st = $this->pdo->prepare($sql);
        $st->execute([':t' => $table]);
        return (bool) $st->fetchColumn();
    }
    private function columnExists(string $table, string $column): bool
    {
        $sql = "SELECT 1 FROM information_schema.columns
                WHERE table_schema = DATABASE() AND table_name = :t AND column_name = :c LIMIT 1";
        $st = $this->pdo->prepare($sql);
        $st->execute([':t' => $table, ':c' => $column]);
        return (bool) $st->fetchColumn();
    }

    /* ==================== PENDING ==================== */

    public function getPendingArticles(int $limit, int $offset): array
    {
        // Đổi tên cột table cho khớp DB của bạn nếu khác:
        $sql = "SELECT a.id, a.title, a.summary, a.main_image_url, a.created_at, a.published_at,
                       u.name AS author_name
                FROM articles a
                LEFT JOIN users u ON u.id = a.author_id
                WHERE a.status = 'pending'
                ORDER BY a.created_at DESC, a.id DESC
                LIMIT :lim OFFSET :off";
        $st = $this->pdo->prepare($sql);
        $st->bindValue(':lim', $limit, PDO::PARAM_INT);
        $st->bindValue(':off', $offset, PDO::PARAM_INT);
        $st->execute();
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countPendingArticles(): int
    {
        return (int) $this->pdo->query("SELECT COUNT(*) FROM articles WHERE status = 'pending'")->fetchColumn();
    }

    /* ==================== REVIEWED ==================== */

    public function getReviewedArticles(array $filters, int $limit, int $offset): array
    {
        $sql = "SELECT ar.id, ar.article_id, ar.action, ar.reason, ar.reviewed_at,
                       a.title,
                       au.name AS author_name,
                       ad.name AS admin_name
                FROM article_reviews ar
                JOIN articles a   ON a.id = ar.article_id
                LEFT JOIN users au ON au.id = a.author_id
                LEFT JOIN users ad ON ad.id = ar.admin_id
                WHERE 1=1";
        $p = [];

        if (!empty($filters['title'])) {
            $sql .= " AND a.title LIKE :title";
            $p[':title'] = '%' . $filters['title'] . '%';
        }
        if (!empty($filters['admin'])) {
            $sql .= " AND ad.name LIKE :admin";
            $p[':admin'] = '%' . $filters['admin'] . '%';
        }
        if (!empty($filters['status']) && in_array($filters['status'], ['approved', 'rejected'], true)) {
            $sql .= " AND ar.action = :status";
            $p[':status'] = $filters['status'];
        }
        if (!empty($filters['from'])) {
            $sql .= " AND DATE(ar.reviewed_at) >= :dfrom";
            $p[':dfrom'] = $filters['from'];
        }
        if (!empty($filters['to'])) {
            $sql .= " AND DATE(ar.reviewed_at) <= :dto";
            $p[':dto'] = $filters['to'];
        }

        $sql .= " ORDER BY ar.reviewed_at DESC, ar.id DESC
                  LIMIT :lim OFFSET :off";
        $st = $this->pdo->prepare($sql);
        foreach ($p as $k => $v)
            $st->bindValue($k, $v);
        $st->bindValue(':lim', $limit, PDO::PARAM_INT);
        $st->bindValue(':off', $offset, PDO::PARAM_INT);
        $st->execute();
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countReviewedArticles(array $filters): int
    {
        $sql = "SELECT COUNT(*)
                FROM article_reviews ar
                JOIN articles a   ON a.id = ar.article_id
                LEFT JOIN users ad ON ad.id = ar.admin_id
                WHERE 1=1";
        $p = [];

        if (!empty($filters['title'])) {
            $sql .= " AND a.title LIKE :title";
            $p[':title'] = '%' . $filters['title'] . '%';
        }
        if (!empty($filters['admin'])) {
            $sql .= " AND ad.name LIKE :admin";
            $p[':admin'] = '%' . $filters['admin'] . '%';
        }
        if (!empty($filters['status']) && in_array($filters['status'], ['approved', 'rejected'], true)) {
            $sql .= " AND ar.action = :status";
            $p[':status'] = $filters['status'];
        }
        if (!empty($filters['from'])) {
            $sql .= " AND DATE(ar.reviewed_at) >= :dfrom";
            $p[':dfrom'] = $filters['from'];
        }
        if (!empty($filters['to'])) {
            $sql .= " AND DATE(ar.reviewed_at) <= :dto";
            $p[':dto'] = $filters['to'];
        }

        $st = $this->pdo->prepare($sql);
        foreach ($p as $k => $v)
            $st->bindValue($k, $v);
        $st->execute();
        return (int) $st->fetchColumn();
    }

    /* ==================== ARTICLE FULL ==================== */

    public function getArticleFull(int $articleId): array
    {
        // 1) Article
        $st = $this->pdo->prepare("SELECT * FROM articles WHERE id = :id LIMIT 1");
        $st->execute([':id' => $articleId]);
        $article = $st->fetch(PDO::FETCH_ASSOC) ?: [];

        // 2) Sections: ưu tiên article_sections, fallback sections, nếu không có → []
        $sections = [];
        $sectionsTable = null;
        if ($this->tableExists('article_sections')) {
            $sectionsTable = 'article_sections';
        } elseif ($this->tableExists('sections')) {
            $sectionsTable = 'sections';
        }

        if ($sectionsTable) {
            $st = $this->pdo->prepare("SELECT id, article_id, title, content
                                       FROM {$sectionsTable}
                                       WHERE article_id = :id
                                       ORDER BY id ASC");
            $st->execute([':id' => $articleId]);
            $sections = $st->fetchAll(PDO::FETCH_ASSOC);
        }

        // 3) Media: ưu tiên article_media, fallback media; nếu không có bảng thì để trống
        $media = [];
        $mediaTable = null;
        if ($this->tableExists('article_media')) {
            $mediaTable = 'article_media';
        } elseif ($this->tableExists('media')) {
            $mediaTable = 'media';
        }

        if ($mediaTable) {
            $hasSectionCol = $this->columnExists($mediaTable, 'section_id');
            $hasCaptionCol = $this->columnExists($mediaTable, 'caption');
            $mediaTypeCol = $this->columnExists($mediaTable, 'media_type') ? 'media_type' : ($this->columnExists($mediaTable, 'type') ? 'type' : null);
            $mediaUrlCol = $this->columnExists($mediaTable, 'media_url') ? 'media_url' : ($this->columnExists($mediaTable, 'url') ? 'url' : null);

            // Nếu không có cột loại/url thì thôi bỏ qua media
            if ($mediaTypeCol && $mediaUrlCol) {
                $selectCols = "id, article_id, {$mediaTypeCol} AS media_type, {$mediaUrlCol} AS media_url";
                if ($hasSectionCol)
                    $selectCols .= ", section_id";
                if ($hasCaptionCol)
                    $selectCols .= ", caption";

                $st = $this->pdo->prepare("SELECT {$selectCols}
                                           FROM {$mediaTable}
                                           WHERE article_id = :id
                                           ORDER BY id ASC");
                $st->execute([':id' => $articleId]);
                $media = $st->fetchAll(PDO::FETCH_ASSOC);
            }
        }

        // 4) Gom media vào section nếu có cột section_id
        $articleMedia = [];
        if (!empty($sections) && !empty($media) && array_key_exists('section_id', $media[0])) {
            $bySec = [];
            foreach ($media as $m) {
                $sid = (int) ($m['section_id'] ?? 0);
                if ($sid > 0)
                    $bySec[$sid][] = $m;
                else
                    $articleMedia[] = $m; // media không gắn section
            }
            foreach ($sections as &$sec) {
                $secId = (int) $sec['id'];
                $sec['media'] = $bySec[$secId] ?? [];
            }
            unset($sec);
        } else {
            // Không có section_id: coi tất cả là article-level media
            $articleMedia = $media;
            // đồng thời gán mảng media rỗng cho từng section (nếu có section)
            foreach ($sections as &$sec)
                $sec['media'] = [];
            unset($sec);
        }

        return [
            'article' => $article,
            'sections' => $sections,
            'articleMedia' => $articleMedia,
        ];
    }

    /* ==================== ACTIONS ==================== */

    public function approve(int $articleId, int $adminId): bool
    {
        $this->pdo->beginTransaction();
        try {
            $st = $this->pdo->prepare("UPDATE articles
                                       SET status = 'public', published_at = COALESCE(published_at, NOW())
                                       WHERE id = :id");
            $st->execute([':id' => $articleId]);

            $st = $this->pdo->prepare("INSERT INTO article_reviews (article_id, admin_id, action, reason, reviewed_at)
                                       VALUES (:aid, :uid, 'approved', NULL, NOW())");
            $st->execute([':aid' => $articleId, ':uid' => $adminId]);

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
            $st = $this->pdo->prepare("UPDATE articles SET status = 'rejected' WHERE id = :id");
            $st->execute([':id' => $articleId]);

            $st = $this->pdo->prepare("INSERT INTO article_reviews (article_id, admin_id, action, reason, reviewed_at)
                                       VALUES (:aid, :uid, 'rejected', :reason, NOW())");
            $st->execute([':aid' => $articleId, ':uid' => $adminId, ':reason' => $reason]);

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
            // Xoá media nếu bảng tồn tại
            foreach (['article_media', 'media'] as $mt) {
                if ($this->tableExists($mt) && $this->columnExists($mt, 'article_id')) {
                    $st = $this->pdo->prepare("DELETE FROM {$mt} WHERE article_id = :id");
                    $st->execute([':id' => $articleId]);
                }
            }

            // Xoá sections nếu bảng tồn tại
            foreach (['article_sections', 'sections'] as $stb) {
                if ($this->tableExists($stb) && $this->columnExists($stb, 'article_id')) {
                    $st = $this->pdo->prepare("DELETE FROM {$stb} WHERE article_id = :id");
                    $st->execute([':id' => $articleId]);
                }
            }

            // Xoá article
            $st = $this->pdo->prepare("DELETE FROM articles WHERE id = :id");
            $st->execute([':id' => $articleId]);

            // Ghi log deleted
            $st = $this->pdo->prepare("INSERT INTO article_reviews (article_id, admin_id, action, reason, reviewed_at)
                                       VALUES (:aid, :uid, 'deleted', :reason, NOW())");
            $st->execute([':aid' => $articleId, ':uid' => $adminId, ':reason' => $reason]);

            $this->pdo->commit();
            return true;
        } catch (\Throwable $e) {
            $this->pdo->rollBack();
            return false;
        }
    }
}
