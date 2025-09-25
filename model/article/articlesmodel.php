<?php
class ArticlesModel
{

    // Thêm bài viết mới -> mặc định pending

    public static function addArticle($title, $summary, $content, $main_image_url, $author_id, $topic_id, $status = 'pending', $is_hot = 0, $is_analysis = 0)
    {
        $db = new connect();
        $slug = connect::createSlug($title);

        $sql = "INSERT INTO articles 
                (title, slug, summary, content, main_image_url, author_id, topic_id, status, published_at, is_hot, is_analysis) 
                VALUES 
                (:title, :slug, :summary, :content, :main_image_url, :author_id, :topic_id, :status, NOW(), :is_hot, :is_analysis)";
        $stmt = $db->db->prepare($sql);

        $success = $stmt->execute([
            ':title' => $title,
            ':slug' => $slug,
            ':summary' => $summary,
            ':content' => $content,
            ':main_image_url' => $main_image_url,
            ':author_id' => $author_id,
            ':topic_id' => $topic_id,
            ':status' => $status, // mặc định pending
            ':is_hot' => $is_hot,
            ':is_analysis' => $is_analysis
        ]);

        if ($success) {
            return $db->db->lastInsertId();
        }
        return false;
    }

    // Lấy tất cả bài viết (chỉ public)
    public static function getAllArticles()
    {
        $db = new connect();
        $sql = "SELECT a.*, u.name AS author_name, u.avatar_url, t.name AS topic_name
                FROM articles a
                LEFT JOIN users u ON a.author_id = u.id
                LEFT JOIN topics t ON a.topic_id = t.id
                WHERE a.status = 'public'
                ORDER BY a.created_at DESC, a.id DESC";
        $stmt = $db->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy bài viết phân trang (chỉ public)
    public static function getArticlesPaged(int $offset, int $limit)
    {
        $currentUserId = $_SESSION['user']['id'] ?? null;
        $db = new connect();

        $sql = "SELECT a.*, u.name AS author_name, u.avatar_url, t.name AS topic_name, m.media_url AS video_url
                FROM articles a
                LEFT JOIN users u ON a.author_id = u.id
                LEFT JOIN topics t ON a.topic_id = t.id
                LEFT JOIN media m ON a.id = m.article_id AND m.media_type = 'video'";

        if ($currentUserId) {
            // Nếu đã đăng nhập: lấy bài public HOẶC bài của chính mình (để xem được cả bài pending)
            $sql .= " WHERE a.status = 'public' OR a.author_id = :current_user_id";
        } else {
            // Nếu là khách: chỉ lấy bài public
            $sql .= " WHERE a.status = 'public'";
        }

        $sql .= " ORDER BY a.created_at DESC, a.id DESC LIMIT :limit OFFSET :offset";

        $stmt = $db->db->prepare($sql);

        if ($currentUserId) {
            $stmt->bindValue(':current_user_id', $currentUserId, PDO::PARAM_INT);
        }

        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private static function timeAgo($datetime)
    {
        $time = time() - strtotime($datetime);
        if ($time < 60) return 'vừa xong';
        if ($time < 3600) return floor($time / 60) . ' phút trước';
        if ($time < 86400) return floor($time / 3600) . ' giờ trước';
        if ($time < 2592000) return floor($time / 86400) . ' ngày trước';
        return date('d/m/Y', strtotime($datetime));
    }


    // Lấy bài viết theo ID (chỉ public)
    public static function getArticleById($id)
    {
        $db = new connect();
        $sql = "SELECT a.*, u.name AS author_name, u.avatar_url, t.name AS topic_name
                FROM articles a
                LEFT JOIN users u ON a.author_id = u.id
                LEFT JOIN topics t ON a.topic_id = t.id
                WHERE a.id = :id AND a.status = 'public'";
        $stmt = $db->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getArticleBySlug($slug, $currentUserId = null)
    {
        $db = new connect();

        // 1) Lấy bản ghi bài viết + tác giả + chủ đề (không join media để tránh nhân dòng)
        $sql = "SELECT 
                a.*,
                u.name       AS author_name,
                u.avatar_url AS author_avatar_url,
                t.name       AS topic_name
            FROM articles a
            LEFT JOIN users  u ON a.author_id = u.id
            LEFT JOIN topics t ON a.topic_id  = t.id
            WHERE a.slug = :slug";

        $params = [':slug' => $slug];

        // Quyền xem: khách chỉ xem 'public', người đăng nhập xem 'public' + bài của chính họ
        if ($currentUserId === null) {
            $sql .= " AND a.status = 'public'";
        } else {
            $sql .= " AND (a.status = 'public' OR a.author_id = :uid)";
            $params[':uid'] = $currentUserId;
        }

        $stmt = $db->db->prepare($sql);
        $stmt->execute($params);
        $article = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$article) {
            return null; // Không tìm thấy hoặc không có quyền
        }

        $articleId = (int)$article['id'];

        // 2) Lấy danh sách section của bài viết (theo position ASC)
        $sqlSec = "SELECT 
                    s.id, s.article_id, s.position, s.title, s.content,
                    s.created_at, s.updated_at
               FROM article_sections s
               WHERE s.article_id = :aid
               ORDER BY s.position ASC, s.id ASC";
        $stmtSec = $db->db->prepare($sqlSec);
        $stmtSec->execute([':aid' => $articleId]);
        $sections = $stmtSec->fetchAll(PDO::FETCH_ASSOC);

        // 3) Nếu có section, lấy toàn bộ media của các section đó, group theo section_id
        $sectionsById = [];
        $sectionIds    = [];
        foreach ($sections as $sec) {
            $sec['media'] = [];                // chỗ chứa media của section
            $sectionsById[$sec['id']] = $sec;  // tạm map
            $sectionIds[] = (int)$sec['id'];
        }

        if (!empty($sectionIds)) {
            // Tạo placeholder động cho mảng section_id
            $inPlaceholders = [];
            $inParams = [];
            foreach ($sectionIds as $i => $sid) {
                $key = ":sid{$i}";
                $inPlaceholders[]   = $key;
                $inParams[$key]     = $sid;
            }

            $sqlSM = "SELECT 
                      asm.section_id,
                      asm.position     AS media_position,
                      m.id             AS media_id,
                      m.media_url,
                      m.media_type,
                      m.caption,
                      m.created_at     AS media_created_at
                  FROM article_section_media asm
                  JOIN media m ON m.id = asm.media_id
                  WHERE asm.section_id IN (" . implode(',', $inPlaceholders) . ")
                  ORDER BY asm.section_id ASC, asm.position ASC, m.id ASC";

            $stmtSM = $db->db->prepare($sqlSM);
            $stmtSM->execute($inParams);
            $rowsSM = $stmtSM->fetchAll(PDO::FETCH_ASSOC);

            foreach ($rowsSM as $row) {
                $sid = (int)$row['section_id'];
                if (isset($sectionsById[$sid])) {
                    $sectionsById[$sid]['media'][] = [
                        'id'         => (int)$row['media_id'],
                        'url'        => $row['media_url'],
                        'type'       => $row['media_type'],   // 'image' | 'video'
                        'caption'    => $row['caption'],
                        'position'   => (int)$row['media_position'],
                        'created_at' => $row['media_created_at'],
                    ];
                }
            }
        }

        // Build lại mảng sections theo thứ tự position
        $articleSections = [];
        if (!empty($sectionsById)) {
            // $sections ban đầu đã theo position; chỉ cần đọc lại theo thứ tự cũ
            foreach ($sections as $sec) {
                $articleSections[] = $sectionsById[$sec['id']];
            }
        }

        // 4) Media cấp bài viết (legacy): media gắn trực tiếp article_id
        $sqlAM = "SELECT 
                 m.id, m.media_url, m.media_type, m.caption, m.created_at
              FROM media m
              WHERE m.article_id = :aid
              ORDER BY m.id ASC";
        $stmtAM = $db->db->prepare($sqlAM);
        $stmtAM->execute([':aid' => $articleId]);
        $articleLevelMedia = $stmtAM->fetchAll(PDO::FETCH_ASSOC);

        // 5) Trả về: vẫn giữ fields cũ + bổ sung 2 key mới
        $article['sections']      = $articleSections;   // mảng section, mỗi section có 'media'
        $article['article_media'] = $articleLevelMedia; // media cấp bài (nếu có, ví dụ video legacy)

        return $article;
    }



    public static function getRelatedArticles($topic_id, $exclude_id, $limit = 5)
    {
        $db = new connect();
        $sql = "SELECT id, title, slug 
            FROM articles 
            WHERE topic_id = :topic_id 
              AND id != :exclude_id 
              AND status = 'public'
            ORDER BY created_at DESC
            LIMIT :limit";

        $stmt = $db->db->prepare($sql);
        // PDO không hỗ trợ bindParam trực tiếp với LIMIT, phải ép kiểu int
        $stmt->bindValue(':topic_id', $topic_id, PDO::PARAM_INT);
        $stmt->bindValue(':exclude_id', $exclude_id, PDO::PARAM_INT);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getByUserId($userId)
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM articles WHERE author_id = ? ORDER BY created_at DESC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    // Cập nhật bài viết
    public static function updateArticle($id, $title, $summary, $content, $main_image_url, $topic_id, $status = 'public', $is_hot = 0, $is_analysis = 0)
    {
        $db = new connect();
        $slug = connect::createSlug($title);

        $sql = "UPDATE articles 
                SET title = :title, slug = :slug, summary = :summary, content = :content, 
                    main_image_url = :main_image_url, topic_id = :topic_id, status = :status, 
                    is_hot = :is_hot, is_analysis = :is_analysis, updated_at = NOW()
                WHERE id = :id";
        $stmt = $db->db->prepare($sql);

        return $stmt->execute([
            ':id' => $id,
            ':title' => $title,
            ':slug' => $slug,
            ':summary' => $summary,
            ':content' => $content,
            ':main_image_url' => $main_image_url,
            ':topic_id' => $topic_id,
            ':status' => $status,
            ':is_hot' => $is_hot,
            ':is_analysis' => $is_analysis
        ]);
    }

    // Xóa bài viết
    public static function deleteArticle($id)
    {
        $db = new connect();
        $sql = "DELETE FROM articles WHERE id = :id";
        $stmt = $db->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    // Tăng view count
    public static function increaseView($id)
    {
        $db = new connect();
        $sql = "UPDATE articles SET view_count = view_count + 1 WHERE id = :id";
        $stmt = $db->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    // Tăng comment count
    public static function increaseCommentCount($id)
    {
        $db = new connect();
        $sql = "UPDATE articles SET comment_count = comment_count + 1 WHERE id = :id";
        $stmt = $db->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    // Lấy bài viết theo topic (chỉ public)
    public static function getArticlesByTopicId($topic_id, $limit = 10)
    {
        $db = new connect();
        $sql = "SELECT a.*, u.name AS author_name, u.avatar_url
                FROM articles a
                LEFT JOIN users u ON a.author_id = u.id
                WHERE a.topic_id = :topic_id AND a.status = 'public'
                ORDER BY a.created_at DESC, a.id DESC
                LIMIT :limit";

        $stmt = $db->db->prepare($sql);
        $stmt->bindValue(':topic_id', $topic_id, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy bài viết theo topic (chỉ public)
    public static function getArticlesByTopicSlug($topic_slug, $limit = 10, $offset = 0)
    {
        $db = new connect();
        $sql = "SELECT a.*, u.name AS author_name, u.avatar_url, t.name AS topic_name, t.slug AS topic_slug
            FROM articles a
            LEFT JOIN users u ON a.author_id = u.id
            LEFT JOIN topics t ON a.topic_id = t.id
            WHERE t.slug = :topic_slug AND a.status = 'public'
            ORDER BY a.created_at DESC, a.id DESC
            LIMIT :limit OFFSET :offset";

        $stmt = $db->db->prepare($sql);
        $stmt->bindValue(':topic_slug', $topic_slug, PDO::PARAM_STR);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy bài viết theo author (bao gồm mọi trạng thái)
    public static function getArticlesByAuthorId($author_id)
    {
        $db = new connect();
        $sql = "SELECT a.*, u.name AS author_name, u.avatar_url
                FROM articles a
                LEFT JOIN users u ON a.author_id = u.id
                WHERE a.author_id = :author_id
                ORDER BY a.created_at DESC, a.id DESC";

        $stmt = $db->db->prepare($sql);
        $stmt->bindValue(':author_id', $author_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy lý do review mới nhất cho bài viết
    public static function getLatestReviewReasonByArticleId($article_id)
    {
        $db = new connect();
        $sql = "SELECT reason
                FROM article_reviews
                WHERE article_id = :article_id
                ORDER BY id DESC
                LIMIT 1";
        $stmt = $db->db->prepare($sql);
        $stmt->bindValue(':article_id', $article_id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? ($row['reason'] ?? null) : null;
    }
    public static function getLatestArticles($limit = 6)
    {
        $db = new connect();
        $sql = "SELECT a.*, 
                   u.name AS author_name, 
                   u.avatar_url, 
                   t.name AS topic_name
            FROM articles a
            LEFT JOIN users u ON a.author_id = u.id
            LEFT JOIN topics t ON a.topic_id = t.id
            WHERE a.status = 'public'
            ORDER BY a.created_at DESC, a.id DESC
            LIMIT :limit";

        $stmt = $db->db->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Helper để kiểm tra slug tồn tại
    public static function slugExists($slug)
    {
        $db = new connect();
        $sql = "SELECT COUNT(*) FROM articles WHERE slug = :slug";
        $stmt = $db->db->prepare($sql);
        $stmt->execute([':slug' => $slug]);
        return $stmt->fetchColumn() > 0;
    }

    public static function addArticleFromProfile(array $data, array $sections = [])
    {
        $db  = new connect();
        $pdo = $db->db;

        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $created_at = date("Y-m-d H:i:s");

        // Validate cơ bản
        if (empty($data['title']) || empty($data['content']) || empty($data['author_id'])) {
            return false;
        }

        // Tạo slug duy nhất
        $baseSlug = connect::createSlug($data['title']);
        $slug = $baseSlug;
        $counter = 1;
        while (self::slugExists($slug)) {
            $slug = $baseSlug . '-' . $counter++;
        }

        try {
            $pdo->beginTransaction();

            // 1) Insert bài viết (cover = main_image_url, tóm tắt = summary)
            $sql = "INSERT INTO articles 
                (title, slug, summary, content, main_image_url, author_id, topic_id, status, created_at, is_hot, is_analysis) 
                VALUES 
                (:title, :slug, :summary, :content, :main_image_url, :author_id, :topic_id, :status, :created_at, :is_hot, :is_analysis)";
            $stmt = $pdo->prepare($sql);

            $ok = $stmt->execute([
                ':title'          => $data['title'],
                ':slug'           => $slug,
                ':summary'        => $data['summary']        ?? '',
                ':content'        => $data['content'],
                ':main_image_url' => $data['main_image_url'] ?? null, // ảnh cover
                ':author_id'      => $data['author_id'],
                ':topic_id'       => $data['topic_id']       ?? null,
                ':status'         => $data['status']         ?? 'pending',
                ':is_hot'         => $data['is_hot']         ?? 0,
                ':is_analysis'    => $data['is_analysis']    ?? 0,
                ':created_at'     => $created_at
            ]);

            if (!$ok) {
                $pdo->rollBack();
                return false;
            }

            $articleId = (int)$pdo->lastInsertId();

            // 2) Nếu có sections từ UI thì lưu
            if (!empty($sections)) {
                // Chuẩn bị statement
                $insSection = $pdo->prepare("
                INSERT INTO article_sections (article_id, position, title, content)
                VALUES (:article_id, :position, :title, :content)
            ");
                $insMedia = $pdo->prepare("
                INSERT INTO media (article_id, media_url, media_type, caption)
                VALUES (:article_id, :url, :type, :caption)
            ");
                $insMap = $pdo->prepare("
                INSERT INTO article_section_media (section_id, media_id, position)
                VALUES (:section_id, :media_id, :position)
            ");

                foreach ($sections as $i => $sec) {
                    $position = isset($sec['position']) ? (int)$sec['position'] : ($i + 1);

                    // 2.1) Lưu section
                    $insSection->execute([
                        ':article_id' => $articleId,
                        ':position'   => $position,
                        ':title'      => $sec['title']   ?? null,
                        ':content'    => $sec['content'] ?? null,
                    ]);
                    $sectionId = (int)$pdo->lastInsertId();

                    // 2.2) Lưu media & mapping (nếu có)
                    if (!empty($sec['media']) && is_array($sec['media'])) {
                        foreach ($sec['media'] as $j => $m) {
                            // Cho phép dùng media sẵn có (id) hoặc tạo mới (url/type/caption)
                            if (!empty($m['id'])) {
                                $mediaId = (int)$m['id'];
                            } else {
                                $insMedia->execute([
                                    ':article_id' => $articleId,
                                    ':url'        => $m['url']     ?? null,
                                    ':type'       => $m['type']    ?? null, // 'image' | 'video'
                                    ':caption'    => $m['caption'] ?? null,
                                ]);
                                $mediaId = (int)$pdo->lastInsertId();
                            }

                            // mapping vào section
                            $insMap->execute([
                                ':section_id' => $sectionId,
                                ':media_id'   => $mediaId,
                                ':position'   => ($m['position'] ?? ($j + 1)),
                            ]);
                        }
                    }
                }
            }

            $pdo->commit();
            return $articleId;
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            // TODO: ghi log $e->getMessage()
            return false;
        }
    }

    /**
     * Helper kiểm tra slug đã tồn tại
     */


    public static function searchArticles($q)
    {
        $db = new connect();
        $sql = "SELECT a.*, u.name AS author_name
                FROM articles a
                LEFT JOIN users u ON a.author_id = u.id
               WHERE name COLLATE utf8mb4_general_ci LIKE :q 
               OR username COLLATE utf8mb4_general_ci LIKE :q 
            ORDER BY name ASC";
        $stmt = $db->db->prepare($sql);
        $stmt->execute([':q' => "%$q%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
