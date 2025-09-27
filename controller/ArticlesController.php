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


    public static function editArticle()
    {
        header('Content-Type: application/json');

        require_once __DIR__ . '/../model/user/userModel.php';
        require_once __DIR__ . '/../model/article/articlesmodel.php';
        require_once __DIR__ . '/../model/mediamodel.php';
        require_once __DIR__ . '/../config/db.php'; // <- cần có

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Phương thức không được hỗ trợ!']);
            return;
        }

        if (!isset($_SESSION['user']['id'])) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Bạn cần đăng nhập để sửa bài viết!']);
            return;
        }
        $currentUserId = (int)$_SESSION['user']['id'];

        // Check token
        $submittedToken = $_POST['session_token'] ?? '';
        if (!UserModel::isTokenValid($currentUserId, $submittedToken)) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Phiên làm việc không hợp lệ. Vui lòng tải lại trang.']);
            return;
        }

        // Định danh bài
        $slug   = trim($_POST['slug'] ?? $_POST['post_slug'] ?? '');
        $postId = isset($_POST['post_id']) ? (int)$_POST['post_id'] : 0;

        $db  = new connect();
        $pdo = $db->db;

        try {
            // Lấy bài viết (để fallback/kiểm quyền)
            if ($slug !== '') {
                $stmt = $pdo->prepare("
                SELECT id, author_id, main_image_url, slug, title, summary, content, topic_id
                FROM articles WHERE slug = :slug LIMIT 1
            ");
                $stmt->execute([':slug' => $slug]);
            } else {
                if ($postId <= 0) {
                    echo json_encode(['success' => false, 'message' => 'Thiếu slug hoặc post_id.']);
                    return;
                }
                $stmt = $pdo->prepare("
                SELECT id, author_id, main_image_url, slug, title, summary, content, topic_id
                FROM articles WHERE id = :id LIMIT 1
            ");
                $stmt->execute([':id' => $postId]);
            }
            $article = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$article) {
                echo json_encode(['success' => false, 'message' => 'Không tìm thấy bài viết.']);
                return;
            }
            if ((int)$article['author_id'] !== $currentUserId) {
                http_response_code(403);
                echo json_encode(['success' => false, 'message' => 'Bạn không có quyền sửa bài viết này.']);
                return;
            }

            $articleId = (int)$article['id'];
            $oldCover  = $article['main_image_url'];

            // ====== INPUT ======
            $inTitle   = isset($_POST['title']) ? trim($_POST['title']) : '';
            $inSummary = isset($_POST['summary']) ? trim($_POST['summary']) : '';
            $inContent = isset($_POST['content']) ? trim($_POST['content']) : '';
            $inTopicId = isset($_POST['topic_id']) ? (int)$_POST['topic_id'] : null;

            // sections_json: nếu có => thay sections
            $hasSectionsKey = array_key_exists('sections_json', $_POST);
            $sections_json  = $_POST['sections_json'] ?? '[]';
            $sections_in    = json_decode($sections_json, true);
            if ($hasSectionsKey && !is_array($sections_in)) $sections_in = [];

            // Nếu content rỗng nhưng có sections_json => nối content
            if ($inContent === '' && $hasSectionsKey && !empty($sections_in)) {
                $parts = [];
                foreach ($sections_in as $sec) {
                    if (!empty($sec['title']))   $parts[] = trim($sec['title']);
                    if (!empty($sec['content'])) $parts[] = trim($sec['content']);
                }
                $inContent = trim(implode("\n\n", $parts));
            }

            // ====== FALLBACK ======
            $title    = ($inTitle   !== '') ? $inTitle   : (string)$article['title'];
            $summary  = ($inSummary !== '') ? $inSummary : (string)$article['summary'];
            $content  = ($inContent !== '' || ($hasSectionsKey && !empty($sections_in)))
                ? $inContent
                : (string)$article['content'];
            $topic_id = ($inTopicId !== null) ? $inTopicId : (int)$article['topic_id'];

            // VALIDATION
            if ($title === '' || $content === '' || mb_strlen($content) < 10) {
                echo json_encode(['success' => false, 'message' => 'Tiêu đề hoặc nội dung không hợp lệ!']);
                return;
            }

            // ====== UPLOAD COVER (nếu có) ======
            $newCoverPath = null;
            if (isset($_FILES['main_image_url']) && $_FILES['main_image_url']['error'] === UPLOAD_ERR_OK) {
                $upload_dir = 'public/img/articles/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                $ext       = pathinfo($_FILES['main_image_url']['name'], PATHINFO_EXTENSION);
                $file_name = 'article_cover_' . $articleId . '_' . time() . '_' . mt_rand(1000, 9999) . '.' . $ext;
                $file_path = $upload_dir . $file_name;
                if (move_uploaded_file($_FILES['main_image_url']['tmp_name'], $file_path)) {
                    $newCoverPath = $file_path;
                }
            }

            // ====== TX: UPDATE + (optional) REPLACE SECTIONS ======
            $pdo->beginTransaction();

            // Update articles
            $sqlUpdate = "UPDATE articles
                      SET title = :title,
                          summary = :summary,
                          content = :content,
                          topic_id = :topic_id,
                          updated_at = NOW()";
            $params = [
                ':title'   => $title,
                ':summary' => $summary,
                ':content' => $content,
                ':topic_id' => $topic_id,
                ':id'      => $articleId
            ];
            if ($newCoverPath !== null) {
                $sqlUpdate .= ", main_image_url = :cover";
                $params[':cover'] = $newCoverPath;
            }
            $sqlUpdate .= " WHERE id = :id";
            $pdo->prepare($sqlUpdate)->execute($params);

            // Nếu FE gửi sections_json -> xoá & ghi lại
            if ($hasSectionsKey) {
                // lấy section cũ
                $qOld = $pdo->prepare("SELECT id FROM article_sections WHERE article_id = :aid");
                $qOld->execute([':aid' => $articleId]);
                $oldSecIds = $qOld->fetchAll(PDO::FETCH_COLUMN);

                if (!empty($oldSecIds)) {
                    $in = implode(',', array_fill(0, count($oldSecIds), '?'));
                    $pdo->prepare("DELETE FROM article_section_media WHERE section_id IN ($in)")->execute($oldSecIds);
                    $pdo->prepare("DELETE FROM article_sections WHERE id IN ($in)")->execute($oldSecIds);
                }

                // chuẩn bị insert
                $insertSec   = $pdo->prepare("INSERT INTO article_sections (article_id, position, title, content)
                                          VALUES (:aid, :pos, :title, :content)");
                // *** dùng media_url thay vì file_path ***
                $insertMedia = $pdo->prepare("INSERT INTO media (article_id, media_url, media_type, caption)
                                          VALUES (:aid, :path, :type, :cap)");
                $insertMap   = $pdo->prepare("INSERT INTO article_section_media (section_id, media_id, position)
                                          VALUES (:sid, :mid, :pos)");

                foreach ($sections_in as $idx => $sec) {
                    $pos      = isset($sec['position']) ? (int)$sec['position'] : ($idx + 1);
                    $titleS   = isset($sec['title'])   ? trim($sec['title'])   : null;
                    $contentS = isset($sec['content']) ? trim($sec['content']) : null;

                    // tạo section
                    $insertSec->execute([
                        ':aid'     => $articleId,
                        ':pos'     => $pos,
                        ':title'   => $titleS,
                        ':content' => $contentS
                    ]);
                    $sectionId = (int)$pdo->lastInsertId();

                    // Upload file theo key section_media_{pos}[]
                    $seq = 0;
                    $key = 'section_media_' . $pos;
                    if (isset($_FILES[$key]) && is_array($_FILES[$key]['name'])) {
                        $total = count($_FILES[$key]['name']);
                        $uploadDir = 'public/uploads/articles/';
                        if (!is_dir($uploadDir)) {
                            mkdir($uploadDir, 0777, true);
                        }
                        for ($i = 0; $i < $total; $i++) {
                            if ($_FILES[$key]['error'][$i] !== UPLOAD_ERR_OK) continue;
                            $tmp  = $_FILES[$key]['tmp_name'][$i];
                            $name = $_FILES[$key]['name'][$i];
                            $type = $_FILES[$key]['type'][$i];

                            $ext  = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                            $new  = 'sec_' . $pos . '_' . time() . '_' . mt_rand(1000, 9999) . '.' . $ext;
                            $path = $uploadDir . $new;

                            if (move_uploaded_file($tmp, $path)) {
                                $mtype = (strpos($type, 'image/') === 0) ? 'image' : ((strpos($type, 'video/') === 0) ? 'video' : null);
                                if ($mtype) {
                                    $insertMedia->execute([
                                        ':aid'  => $articleId,
                                        ':path' => $path,           // -> media_url
                                        ':type' => $mtype,
                                        ':cap'  => $sec['caption'] ?? null
                                    ]);
                                    $mediaId = (int)$pdo->lastInsertId();
                                    $insertMap->execute([
                                        ':sid' => $sectionId,
                                        ':mid' => $mediaId,
                                        ':pos' => (++$seq)
                                    ]);
                                }
                            }
                        }
                    }

                    // Map media URL/id từ FE
                    if (!empty($sec['media']) && is_array($sec['media'])) {
                        foreach ($sec['media'] as $m) {
                            if (!empty($m['id'])) {
                                $mediaId = (int)$m['id'];
                                $insertMap->execute([
                                    ':sid' => $sectionId,
                                    ':mid' => $mediaId,
                                    ':pos' => (++$seq)
                                ]);
                            } elseif (!empty($m['url']) && !empty($m['type'])) {
                                $insertMedia->execute([
                                    ':aid'  => $articleId,
                                    ':path' => $m['url'],         // -> media_url
                                    ':type' => $m['type'],        // image|video
                                    ':cap'  => $m['caption'] ?? null
                                ]);
                                $mediaId = (int)$pdo->lastInsertId();
                                $insertMap->execute([
                                    ':sid' => $sectionId,
                                    ':mid' => $mediaId,
                                    ':pos' => (++$seq)
                                ]);
                            }
                        }
                    }
                }
            }

            // (Tuỳ chọn) video mức-bài
            $debug_info = [];
            $video_message = 'Không thêm video mới.';
            if (isset($_FILES['post_video'])) {
                $video_file = $_FILES['post_video'];
                if ($video_file['error'] === UPLOAD_ERR_OK) {
                    $uploadDir = __DIR__ . '/../../public/videos/';
                    if (!is_dir($uploadDir) && !mkdir($uploadDir, 0777, true)) {
                        $video_message = 'Lỗi: Không thể tạo thư mục videos.';
                    } else {
                        $extension    = strtolower(pathinfo($video_file['name'], PATHINFO_EXTENSION));
                        $allowedTypes = ['mp4', 'webm', 'ogg', 'mov'];
                        if (in_array($extension, $allowedTypes, true)) {
                            $newFileName = 'video_' . $articleId . '_' . time() . '.' . $extension;
                            $uploadPath  = $uploadDir . $newFileName;
                            if (move_uploaded_file($video_file['tmp_name'], $uploadPath)) {
                                $webPath = 'public/videos/' . $newFileName;
                                MediaModel::addMedia($articleId, $webPath, 'video', null);
                                $video_message = 'Video mới đã được tải lên và lưu thành công.';
                            } else {
                                $video_message = 'Lỗi: Không thể di chuyển file đã tải lên.';
                            }
                        } else {
                            $video_message = 'Lỗi: Định dạng video không hợp lệ. Chỉ chấp nhận: ' . implode(', ', $allowedTypes);
                        }
                    }
                } else {
                    $upload_errors = [
                        UPLOAD_ERR_INI_SIZE   => "File vượt quá dung lượng cho phép trong php.ini (upload_max_filesize).",
                        UPLOAD_ERR_FORM_SIZE  => "File vượt quá dung lượng cho phép đã khai báo trong form HTML.",
                        UPLOAD_ERR_PARTIAL    => "File chỉ được tải lên một phần.",
                        UPLOAD_ERR_NO_FILE    => "Không có file nào được tải lên.",
                        UPLOAD_ERR_NO_TMP_DIR => "Thiếu thư mục tạm.",
                        UPLOAD_ERR_CANT_WRITE => "Không thể ghi file vào ổ đĩa.",
                        UPLOAD_ERR_EXTENSION  => "Một tiện ích mở rộng của PHP đã dừng việc tải file.",
                    ];
                    $video_message = 'Lỗi tải lên: ' . ($upload_errors[$video_file['error']] ?? 'Lỗi không xác định');
                }
            }
            $debug_info['video_processing'] = $video_message;

            // Commit
            $pdo->commit();

            // Xoá cover cũ nếu đã thay
            if ($newCoverPath !== null && !empty($oldCover)) {
                $oldFs = __DIR__ . '/../../' . ltrim($oldCover, '/');
                if (is_file($oldFs)) @unlink($oldFs);
            }

            echo json_encode([
                'success' => true,
                'message' => 'Cập nhật bài viết thành công!',
                'article' => [
                    'id'            => $articleId,
                    'slug'          => $article['slug'],
                    'title'         => $title,
                    'summary'       => $summary,
                    'content'       => $content,
                    'topic_id'      => $topic_id,
                    'main_image_url' => $newCoverPath ?? $oldCover
                ],
                'debug' => $debug_info
            ]);
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            error_log('editArticle error: ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Lỗi khi cập nhật bài viết.']);
        }
    }

    public static function getPostForEdit()
    {
        header('Content-Type: application/json');
        require_once __DIR__ . '/../config/db.php';
        require_once __DIR__ . '/../model/article/articlesmodel.php';

        if (!isset($_SESSION['user']['id'])) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Bạn cần đăng nhập.']);
            return;
        }
        $currentUserId = (int)$_SESSION['user']['id'];

        $id   = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $slug = trim($_GET['slug'] ?? '');

        if ($id <= 0 && $slug === '') {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Thiếu id hoặc slug.']);
            return;
        }

        try {
            $db  = new connect();
            $pdo = $db->db;

            // Lấy article để kiểm tra quyền và suy ra slug (nếu chỉ có id)
            if ($id > 0) {
                $st = $pdo->prepare("SELECT id, slug, author_id FROM articles WHERE id=:id LIMIT 1");
                $st->execute([':id' => $id]);
            } else {
                $st = $pdo->prepare("SELECT id, slug, author_id FROM articles WHERE slug=:slug LIMIT 1");
                $st->execute([':slug' => $slug]);
            }
            $row = $st->fetch(PDO::FETCH_ASSOC);
            if (!$row) {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Không tìm thấy bài viết.']);
                return;
            }
            if ((int)$row['author_id'] !== $currentUserId) {
                http_response_code(403);
                echo json_encode(['success' => false, 'message' => 'Bạn không có quyền sửa bài này.']);
                return;
            }

            // Lấy đầy đủ dữ liệu (sections + media)
            $article = ArticlesModel::getArticleBySlug($row['slug'], $currentUserId);
            if (!$article) {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Không thể tải dữ liệu bài viết.']);
                return;
            }

            // Chuẩn dữ liệu cho FE
            $sections = [];
            foreach (($article['sections'] ?? []) as $s) {
                $media = [];
                foreach (($s['media'] ?? []) as $m) {
                    $media[] = [
                        'id'         => (int)($m['id'] ?? 0),
                        'url'        => $m['url'] ?? $m['media_url'] ?? null,
                        'media_url'  => $m['url'] ?? $m['media_url'] ?? null,
                        'type'       => $m['type'] ?? $m['media_type'] ?? null,
                        'media_type' => $m['type'] ?? $m['media_type'] ?? null,
                        'caption'    => $m['caption'] ?? null,
                        'position'   => (int)($m['position'] ?? 0),
                    ];
                }
                $sections[] = [
                    'id'       => (int)$s['id'],
                    'position' => (int)$s['position'],
                    'title'    => $s['title'] ?? null,
                    'content'  => $s['content'] ?? null,
                    'media'    => $media,
                ];
            }

            echo json_encode([
                'success' => true,
                'article' => [
                    'id'             => (int)$article['id'],
                    'slug'           => $article['slug'],
                    'title'          => $article['title'],
                    'summary'        => $article['summary'],
                    'content'        => $article['content'],
                    'topic_id'       => (int)$article['topic_id'],
                    'topic_name'     => $article['topic_name'] ?? null,
                    'main_image_url' => $article['main_image_url'],
                    'sections'       => $sections,
                    // optional cho bạn dùng thêm
                    'article_media'  => $article['article_media'] ?? [],
                ]
            ]);
        } catch (Throwable $e) {
            error_log('getPostForEdit error: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Lỗi máy chủ.']);
        }
    }

    public static function details_blog($slug)
    {
        require_once __DIR__ . '/../model/article/articlesmodel.php';
        require_once __DIR__ . '/../model/user/userModel.php';
        require_once __DIR__ . '/../model/ArticleSavesModel.php';
        // require_once __DIR__ . '/../model/mediamodel.php'; // Không cần nếu view không gọi trực tiếp

        $currentUserId = $_SESSION['user']['id'] ?? null;

        // 1) Lấy bài viết (đã bao gồm sections + media theo thiết kế mới)
        $article = ArticlesModel::getArticleBySlug($slug, $currentUserId);
        if (!$article) {
            echo 'Bài viết không tồn tại.';
            // exit;
        }

        //
        

        // 2) Tác giả
        $author = UserModel::getUserById($article['author_id']);

        // 3) Trạng thái đã lưu (chỉ check khi đăng nhập)
        $saved = false;
        if ($currentUserId) {
            $save = new ArticleSavesModel();
            $saved = $save->isSavedbySlug($slug, $currentUserId);
        }
        $iconClass = $saved ? 'fas' : 'far';

        // 4) Bài viết liên quan
        $relatedArticles = ArticlesModel::getRelatedArticles($article['topic_id'], $article['id'], 5);

        // 5) (Tuỳ chọn) tăng view_count
        if (method_exists('ArticlesModel', 'incrementViewCount')) {
            //ArticlesModel::incrementViewCount($article['id']);
        }

        // 6) Render
        ob_start();
        require_once __DIR__ . '/../view/page/details_Blog.php';
        $content = ob_get_clean();

        $profile = false;
        require_once __DIR__ . '/../view/layout/main.php';
    }
}
