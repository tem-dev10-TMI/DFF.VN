<?php
// ====== Chuẩn bị biến ======
$authorName   = htmlspecialchars($article['author_name'] ?? 'Tác giả');
$authorId     = isset($article['author_id']) ? intval($article['author_id']) : 0;
$authorAvatar = $article['avatar_url'] ?? '/vendor/dffvn/content/img/user.svg';
if (!$authorAvatar || trim($authorAvatar) === '') {
    $authorAvatar = 'https://i.pravatar.cc/100?u=' . urlencode($authorName);
}
$createdAt = isset($article['created_at']) ? date('d/m/Y H:i', strtotime($article['created_at'])) : '';
$title     = htmlspecialchars($article['title'] ?? '');
$summary   = nl2br(htmlspecialchars($article['summary'] ?? ''));
$content   = nl2br(htmlspecialchars($article['content'] ?? '')); // fallback khi chưa có sections
$mainImage = $article['main_image_url'] ?? '';

// NEW: dữ liệu theo thiết kế mới
$sections      = $article['sections'] ?? [];        // array các section {id, position, title, content, media[]}
$articleMedia  = $article['article_media'] ?? [];   // media cấp bài (legacy/tuỳ chọn)
?>

<?php
require_once __DIR__ . '/../../model/user/UserFollowModel.php';
$db = new connect();
$pdo = $db->db;
$followModel = new UserFollowModel($pdo);
$authorId    = isset($authorId) ? intval($authorId) : 0;
$totalFollowers = $authorId > 0 ? $followModel->countFollowers($authorId) : 0;
?>
<style>
    /* ẢNH COVER (đang ổn – giữ nguyên) */
    .dcontent>figure img {
        width: 100%;
        max-height: 420px;
        display: block;
        object-fit: cover;
        border-radius: 14px;
    }

    /* ẢNH/VIDEO TRONG CÁC PHẦN -> TO NHƯ COVER */
    .dcontent .section-media-grid {
        display: block !important;
        /* bỏ grid để ảnh chiếm full hàng */
        margin: 16px 0 !important;
    }

    .dcontent .section-media-grid figure {
        margin: 0 0 16px;
        border-radius: 14px;
        overflow: hidden;
        background: #f6f7f8;
    }

    /* ảnh full-width, cao như cover, cắt tràn đẹp */
    .dcontent .section-media-grid img {
        width: 100% !important;
        height: 420px;
        /* giống cover */
        object-fit: cover;
        display: block;
        border-radius: 14px;
    }

    /* video cũng full-width như cover */
    .dcontent .section-media-grid .video-wrapper {
        margin: 8px 0;
    }

    .dcontent .section-media-grid video {
        width: 100% !important;
        height: 420px;
        display: block;
        object-fit: cover;
        /* khung video thống nhất */
        border-radius: 14px;
    }

    /* Fallback cho bài cũ dùng article-media-grid */
    .dcontent .article-media-grid {
        display: block !important;
    }

    .dcontent .article-media-grid figure {
        margin: 0 0 16px;
        border-radius: 14px;
        overflow: hidden;
    }

    .dcontent .article-media-grid img,
    .dcontent .article-media-grid video {
        width: 100% !important;
        height: 420px;
        object-fit: cover;
        display: block;
        border-radius: 14px;
    }

    /* Mobile có thể hạ chiều cao chút cho đỡ dài */
    @media (max-width:576px) {

        .dcontent .section-media-grid img,
        .dcontent .section-media-grid video,
        .dcontent .article-media-grid img,
        .dcontent .article-media-grid video {
            height: 320px;
        }
    }
</style>
<main class="main-content">
    <div class="block-k">

        <div class="d-topinfo">
            <div class="provider">
                <a href="javascript:void(0)" class="img-news">
                    <img class="logo" alt="" src="<?= htmlspecialchars($authorAvatar) ?>">
                </a>
                <div class="p-covers">
                    <span class="name" title="">
                        <a href="<?= BASE_URL ?>/view_profile?id=<?= $authorId ?>" title="<?= $authorName ?>"><?= $authorName ?></a>
                        <i title="Đã xác thực" class="accu_none fas fa-check-circle"></i>
                    </span>
                    <span class="date"><?= htmlspecialchars($createdAt) ?></span>
                </div>
            </div>

            <div class="follower-r f-right" style="font-size: 13px;">
                <b class="total-fol"><?= intval($totalFollowers) ?></b> Người theo dõi
            </div>
        </div>

        <div class="detail">
            <div class="line"></div>

            <h1><?= $title ?></h1>

            <article>
                <div class="dcontent">

                    <?php if (!empty($summary)): ?>
                        <p><?= $summary ?></p>
                    <?php endif; ?>

                    <?php if (!empty($mainImage)): ?>
                        <figure><img src="<?= htmlspecialchars($mainImage) ?>" alt="<?= $title ?>"></figure>
                    <?php endif; ?>

                    <?php if (!empty($sections)): ?>
                        <!-- ====== Hiển thị theo từng phần (thiết kế mới) ====== -->
                        <?php foreach ($sections as $sec): ?>
                            <section class="article-section" data-section-id="<?= intval($sec['id']) ?>">
                                <?php if (!empty($sec['title'])): ?>
                                    <h3><?= htmlspecialchars($sec['title']) ?></h3>
                                <?php endif; ?>

                                <?php if (!empty($sec['media'])): ?>
                                    <div class="section-media-grid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:12px;margin:12px 0;">
                                        <?php foreach ($sec['media'] as $m): ?>
                                            <?php if (($m['type'] ?? $m['media_type'] ?? '') === 'image'): ?>
                                                <figure>
                                                    <img src="<?= htmlspecialchars($m['url'] ?? $m['media_url']) ?>"
                                                        alt="<?= htmlspecialchars($m['caption'] ?? '') ?>"
                                                        style="width:100%;height:auto;display:block;">
                                                    <?php if (!empty($m['caption'])): ?>
                                                        <figcaption style="font-size:12px;color:#666;"><?= htmlspecialchars($m['caption']) ?></figcaption>
                                                    <?php endif; ?>
                                                </figure>
                                            <?php elseif (($m['type'] ?? $m['media_type'] ?? '') === 'video'): ?>
                                                <div class="video-wrapper" style="margin: 8px 0;">
                                                    <video width="100%" height="auto" controls>
                                                        <source src="<?= htmlspecialchars($m['url'] ?? $m['media_url']) ?>" type="video/mp4">
                                                        Trình duyệt của bạn không hỗ trợ video.
                                                    </video>
                                                    <?php if (!empty($m['caption'])): ?>
                                                        <div style="font-size:12px;color:#666;"><?= htmlspecialchars($m['caption']) ?></div>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($sec['content'])): ?>
                                    <div class="section-content" style="margin-bottom:16px;">
                                        <?= nl2br(htmlspecialchars($sec['content'])) ?>
                                    </div>
                                <?php endif; ?>
                            </section>
                            <hr class="my-3">
                        <?php endforeach; ?>

                    <?php else: ?>
                        <!-- ====== Fallback (bài cũ chưa có sections) ====== -->
                        <?php if (!empty($content)): ?>
                            <p><?= $content ?></p>
                        <?php endif; ?>

                        <?php if (!empty($articleMedia)): ?>
                            <div class="article-media-grid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:12px;margin:12px 0;">
                                <?php foreach ($articleMedia as $m): ?>
                                    <?php if (($m['type'] ?? $m['media_type'] ?? '') === 'image'): ?>
                                        <figure>
                                            <img src="<?= htmlspecialchars($m['url'] ?? $m['media_url']) ?>" alt="<?= htmlspecialchars($m['caption'] ?? '') ?>"
                                                style="width:100%;height:auto;display:block;">
                                            <?php if (!empty($m['caption'])): ?>
                                                <figcaption style="font-size:12px;color:#666;"><?= htmlspecialchars($m['caption']) ?></figcaption>
                                            <?php endif; ?>
                                        </figure>
                                    <?php elseif (($m['type'] ?? $m['media_type'] ?? '') === 'video'): ?>
                                        <div class="video-wrapper" style="margin: 8px 0;">
                                            <video width="100%" height="auto" controls>
                                                <source src="<?= htmlspecialchars($m['url'] ?? $m['media_url']) ?>" type="video/mp4">
                                                Trình duyệt của bạn không hỗ trợ video.
                                            </video>
                                            <?php if (!empty($m['caption'])): ?>
                                                <div style="font-size:12px;color:#666;"><?= htmlspecialchars($m['caption']) ?></div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <div class="bysource"></div>
                </div>

                <div class="box-trends" bis_skin_checked="1">
                    <h5><a href="#" title="Bài viết liên quan">Nội dung liên quan</a></h5>
                    <ul>
                        <?php if (!empty($relatedArticles)): ?>
                            <?php foreach ($relatedArticles as $ra): ?>
                                <li>
                                    <a href="<?= BASE_URL ?>/details_blog/<?= $ra['slug'] ?>" title="<?= htmlspecialchars($ra['title']) ?>">
                                        <?= htmlspecialchars($ra['title']) ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li>Chưa có bài viết liên quan</li>
                        <?php endif; ?>
                    </ul>
                </div>

                <div id="anc_comment" style="padding-bottom:20px;"></div>
            </article>
            <input type="hidden" id="hdd_id" value="<?= htmlspecialchars($article['id'] ?? '') ?>">

            <div data-id="<?= htmlspecialchars($article['id'] ?? '') ?>" data-type="1" class="box-n-sc">

                <div class="item-bottom">
                    <div class="bt-cover com-like" data-id="<?= htmlspecialchars($article['id'] ?? '') ?>">
                        <span class="for-up">
                            <svg rpl="" class="" data-voted="false" data-type="up" fill="currentColor" height="16"
                                icon-name="upvote-fill" viewBox="0 0 20 20" width="16"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M18.706 8.953 10.834.372A1.123 1.123 0 0 0 10 0a1.128 1.128 0 0 0-.833.368L1.29 8.957a1.249 1.249 0 0 0-.171 1.343 1.114 1.114 0 0 0 1.007.7H6v6.877A1.125 1.125 0 0 0 7.123 19h5.754A1.125 1.125 0 0 0 14 17.877V11h3.877a1.114 1.114 0 0 0 1.005-.7 1.251 1.251 0 0 0-.176-1.347Z">
                                </path>
                            </svg>
                        </span>
                        <span class="value" data-old="0">0</span>
                        <span class="for-down">
                            <svg rpl="" class="" data-voted="false" data-type="down" fill="currentColor" height="16"
                                icon-name="downvote-fill" viewBox="0 0 20 20" width="16"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M18.88 9.7a1.114 1.114 0 0 0-1.006-.7H14V2.123A1.125 1.125 0 0 0 12.877 1H7.123A1.125 1.125 0 0 0 6 2.123V9H2.123a1.114 1.114 0 0 0-1.005.7 1.25 1.25 0 0 0 .176 1.348l7.872 8.581a1.124 1.124 0 0 0 1.667.003l7.876-8.589A1.248 1.248 0 0 0 18.88 9.7Z">
                                </path>
                            </svg>
                        </span>
                    </div>

                    <div class="button-ar">
                        <div class="dropdown home-item">
                            <span data-bs-toggle="dropdown">Chia sẻ</span>
                            <ul class="dropdown-menu">
                                <?php
                                $shareUrl = BASE_URL . '/details_blog/' . urlencode($article['slug']);
                                ?>
                                <li><a class="dropdown-item copylink"
                                        data-url="<?= $shareUrl ?>"
                                        href="javascript:void(0)">Copy link</a></li>
                                <li><a class="dropdown-item sharefb"
                                        data-url="<?= $shareUrl ?>"
                                        href="javascript:void(0)">Share FB</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="button-ar fc-saved">
                        <i title="copy link bài viết" class="fas fa-link copylink"
                            data-url="/post-<?= htmlspecialchars($article['id'] ?? '') ?>.html"></i>
                        <i save-article="savenews" title="lưu bài viết" class="<?= $iconClass ?> fa-bookmark" data-id="<?= $article['id'] ?>"></i>

                        <script>
                            document.querySelectorAll('[save-article="savenews"]').forEach(btn => {
                                btn.addEventListener('click', function() {
                                    const articleId = this.dataset.id;

                                    fetch(`index.php?url=ArticleSave`, {
                                            method: 'POST',
                                            headers: {
                                                'Content-Type': 'application/x-www-form-urlencoded'
                                            },
                                            body: `article_id=${articleId}`
                                        })
                                        .then(res => res.json())
                                        .then(res => {
                                            alert(res.msg);
                                            if (res.status === 'success') {
                                                btn.classList.toggle('fas'); // fas = đã lưu
                                                btn.classList.toggle('far'); // far = chưa lưu
                                            }
                                        })
                                        .catch(err => console.error(err));
                                });
                            });
                        </script>


                    </div>
                    <div class="button-ar">
                        <i class="fas fa-exclamation-triangle"></i><span module-load="report">Báo cáo</span>
                    </div>
                </div>

            </div>

        </div>

        <div class="line"></div>


    </div>
    <div class="comment-section">
        <h5 class="comment-section-title">
            <i class="fas fa-comments"></i> Bình luận
            <span id="comments-count">(0)</span>
        </h5>

        <!-- Form nhập -->
        <div class="comment-form-box">
            <textarea id="new-comment" class="comment-form-input" placeholder="Bạn nghĩ gì về nội dung này?"></textarea>
            <button id="submit-comment" class="comment-form-btn"
                data-id="<?= htmlspecialchars($article['id'] ?? '') ?>">
                Gửi
            </button>
        </div>

        <!-- Danh sách -->
        <ul class="comment-items" id="comment-items"></ul>

        <!-- Không có comment -->
        <div class="comment-empty" id="comment-empty">
            <i class="far fa-comments"></i> Trở thành người bình luận đầu tiên
        </div>
    </div>

    <script>
        window.CURRENT_USER_ID = <?= (int)($_SESSION['user']['id'] ?? 0) ?>;
        window.CURRENT_USER_NAME = <?= json_encode($_SESSION['user']['name'] ?? 'Bạn cần đăng nhập để bình luận') ?>;
    </script>


    <script>
        (function() {
            // ===== Guard tránh khởi tạo trùng =====
            const section = document.querySelector(".comment-section");
            if (!section || section.dataset.inited === "1") return;
            section.dataset.inited = "1";

            // ===== Dom refs =====
            const articleId = document.getElementById("submit-comment").dataset.id;
            const btnSend = document.getElementById("submit-comment");
            const textarea = document.getElementById("new-comment");

            const listEl = document.getElementById("comment-items");
            const emptyEl = document.getElementById("comment-empty");
            const countEl = document.getElementById("comments-count");

            // ===== State =====
            const comments = []; // mảng hiển thị tại chỗ (trộn DB + local vừa gửi)
            let isSubmitting = false;

            // ===== Utils =====
            function nowIso() {
                return new Date().toLocaleString('vi-VN');
            }

            function autoGrow(el) {
                el.style.height = 'auto';
                el.style.height = Math.min(el.scrollHeight, 160) + 'px';
            }

            function escapeHtml(str) {
                return (str || '')
                    .replace(/&/g, '&amp;').replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#039;');
            }

            function avatarByName(name) {
                const base = 'https://ui-avatars.com/api/?background=random&color=fff&name=';
                return base + encodeURIComponent(name || 'User');
            }

            // ===== Render =====
            function renderComments(items) {
                listEl.innerHTML = '';
                if (!items || !items.length) {
                    emptyEl.style.display = 'block';
                    countEl.textContent = '(0)';
                    return;
                }

                // Lọc comments: ẩn comment vi phạm và chưa AI check khỏi các user khác
                const currentUserId = <?= (int)($_SESSION['user']['id'] ?? 0) ?>;
                const filteredItems = items.filter(c => {
                    // Nếu comment có vi phạm
                    if (c.ai && !c.ai.isChecking && c.ai.isViolation) {
                        // Chỉ hiển thị cho user đã viết comment đó
                        return c.user_id === currentUserId;
                    }

                    // Nếu comment chưa được AI check, chỉ hiển thị cho user đã viết comment đó
                    if (!c.ai_checked && c.user_id !== currentUserId) {
                        return false;
                    }

                    // Comment đã được AI check và không vi phạm - hiển thị cho tất cả
                    return true;
                });

                if (!filteredItems.length) {
                    emptyEl.style.display = 'block';
                    countEl.textContent = '(0)';
                    return;
                }

                emptyEl.style.display = 'none';
                countEl.textContent = '(' + filteredItems.length + ')';

                filteredItems.forEach(c => {
                    const li = document.createElement('li');
                    li.className = 'comment-card';
                    li.setAttribute('data-comment-id', c.id); // Thêm data attribute để xóa
                    // Chỉ thêm class violation khi có vi phạm, ẩn các trạng thái khác
                    if (c.ai && !c.ai.isChecking && c.ai.isViolation) {
                        li.classList.add('violation');
                    }

                    const name = c.name || 'Ẩn danh';
                    const time = c.time || (c.created_at ? new Date(c.created_at).toLocaleString("vi-VN") : nowIso());
                    const avatar = c.avatar_url || avatarByName(name);

                    // Thay nội dung comment bằng cảnh báo vi phạm
                    let commentContent = escapeHtml(c.content || c.text || '');
                    let deleteButton = '';

                    if (c.ai && !c.ai.isChecking && c.ai.isViolation) {
                        // Thay nội dung comment bằng cảnh báo vi phạm
                        commentContent = `
                                <div class="ai-violation-warning" style="background: #fff3cd; border: 1px solid #ffeaa7; border-radius: 4px; padding: 8px; margin: 4px 0; font-size: 13px; color: #856404; font-style: italic;">
                                    ⚠️ Bạn đã vi phạm quy tắc cộng đồng
                                </div>
                            `;

                        // Thêm nút xóa cho comment vi phạm
                        deleteButton = `
                                <button class="delete-violation-btn" onclick="deleteViolationComment('${c.id}')">
                                    <i class="fas fa-trash"></i> Xóa
                                </button>
                            `;
                    }

                    li.innerHTML = `
        <img src="${avatar}" class="comment-card-avatar">
        <div class="comment-card-body">
          <div class="comment-card-meta">
            <span class="comment-card-name">${escapeHtml(name)}</span>
            <span class="comment-card-time">${escapeHtml(time)}</span>
          </div>
          <div class="comment-card-content">${commentContent}</div>
          <div class="comment-card-actions">
            <a href="javascript:void(0)" onclick="replyComment('${escapeHtml(name)}')">Trả lời</a>
            ${deleteButton}
          </div>
        </div>
      `;
                    listEl.appendChild(li);
                });
            }

            // ===== Load comment mới từ database =====
            async function loadNewComments() {
                try {
                    const currentUserId = <?= (int)($_SESSION['user']['id'] ?? 0) ?>;
                    const res = await fetch("<?= BASE_URL ?>/?url=comment&action=getComments&article_id=" + encodeURIComponent(articleId) + "&_=" + new Date().getTime());
                    const data = await res.json();

                    if (data.status === "success") {
                        // console.log("📥 Loaded new comments:", data.comments.length);
                        let hasNewComments = false;

                        (data.comments || []).forEach(c => {
                            // Chỉ load comment của người khác (không phải của user hiện tại)
                            if (c.user_id == currentUserId) {
                                console.log("⏭️ Skipping own comment:", c.id);
                                return;
                            }

                            // CHỈ load comment đã được AI check và KHÔNG vi phạm
                            if (!c.ai_checked || c.ai_violation == 1) {
                                console.log("⏭️ Skipping comment - not AI checked or violation:", c.id, "ai_checked:", c.ai_checked, "ai_violation:", c.ai_violation);
                                return;
                            }

                            // Kiểm tra comment đã tồn tại chưa
                            const existingComment = comments.find(existing => existing.id === 'db-' + c.id);

                            if (!existingComment) {
                                console.log("🆕 New SAFE comment from others:", c.id, c.content);

                                const newComment = {
                                    id: 'db-' + c.id,
                                    commentId: c.id, // Thêm commentId thật từ database
                                    name: c.name || 'Ẩn danh',
                                    avatar_url: c.avatar_url || '',
                                    content: c.content || '',
                                    created_at: c.created_at,
                                    user_id: c.user_id || null,
                                    ai_checked: true,
                                    ai: {
                                        isViolation: false,
                                        isChecking: false,
                                        details: c.ai_details?.details || '',
                                        violationType: c.ai_details?.violationType || null,
                                        severity: c.ai_details?.severity || null,
                                        confidence: c.ai_details?.confidence || null,
                                        analysisMethod: c.ai_details?.analysisMethod || null,
                                    }
                                };

                                // Thêm comment mới vào đầu danh sách
                                comments.unshift(newComment);
                                hasNewComments = true;
                            } else {
                                console.log("⏭️ Comment already exists:", c.id);
                            }
                        });

                        // Chỉ render lại nếu có comment mới
                        if (hasNewComments) {
                            renderComments(comments);
                        }
                    }
                } catch (error) {
                    console.error("❌ Error loading new comments:", error);
                }
            }

            // ===== Load comments từ DB như cũ rồi ghép vào state local =====
            async function loadCommentsFromDB() {
                try {
                    const res = await fetch("<?= BASE_URL ?>/?url=comment&action=getComments&article_id=" + encodeURIComponent(articleId) + "&_=" + new Date().getTime());
                    const data = await res.json();
                    if (data.status === "success") {
                        // map DB -> model hiển thị
                        const currentUserId = <?= (int)($_SESSION['user']['id'] ?? 0) ?>;
                        const mapped = (data.comments || []).map(c => ({
                            id: 'db-' + c.id,
                            commentId: c.id, // Thêm commentId thật từ database
                            name: c.name || 'Ẩn danh',
                            avatar_url: c.avatar_url || '',
                            content: c.content || '',
                            created_at: c.created_at,
                            user_id: c.user_id || null, // Thêm user_id để kiểm tra quyền
                            ai_checked: c.ai_checked || false, // Thêm flag AI checked
                            // Load thông tin AI check từ database
                            ai: c.ai_checked ? {
                                isViolation: !!c.ai_violation,
                                isChecking: false,
                                details: c.ai_details?.details || '',
                                violationType: c.ai_details?.violationType || null,
                                severity: c.ai_details?.severity || null,
                                confidence: c.ai_details?.confidence || null,
                                analysisMethod: c.ai_details?.analysisMethod || null,
                            } : {
                                // Comment cũ chưa được AI check - sẽ check sau
                                isViolation: false,
                                isChecking: false,
                                details: '',
                                violationType: null,
                                severity: null,
                                confidence: null,
                                analysisMethod: null,
                                needsCheck: true // Flag để biết cần check
                            }
                        }));
                        // reset & nạp
                        comments.length = 0;
                        mapped.forEach(x => comments.push(x));
                        renderComments(comments);

                        // AI check các comment cũ chưa được check
                        checkOldComments();
                    }
                } catch (err) {
                    console.error('Lỗi fetch:', err);
                }
            }

            // ===== AI check các comment cũ chưa được check =====
            async function checkOldComments() {
                const currentUserId = <?= (int)($_SESSION['user']['id'] ?? 0) ?>;
                // Chỉ check comment của user hiện tại
                const commentsToCheck = comments.filter(c => c.ai && c.ai.needsCheck && c.user_id === currentUserId);
                console.log('Found', commentsToCheck.length, 'old comments to check for current user');

                for (const comment of commentsToCheck) {
                    try {
                        console.log('Checking old comment:', comment.content);
                        await checkCommentAsync(comment.id, comment.content, comment.content);
                    } catch (error) {
                        console.error('Error checking old comment:', error);
                    }
                }
            }

            // ===== Gửi comment: lưu DB trước để lấy ID thật, rồi mới AI check =====
            async function sendComment() {
                const content = textarea.value.trim();

                // 0) Chặn khi chưa đăng nhập (đặt trước mọi state flag để không “kẹt” nút)
                if (!window.CURRENT_USER_ID || window.CURRENT_USER_ID === 0) {
                    alert("Bạn cần đăng nhập để bình luận");
                    return;
                }

                if (!content) {
                    alert("Vui lòng nhập nội dung bình luận!");
                    return;
                }
                if (isSubmitting) return;
                isSubmitting = true;
                btnSend.disabled = true;

                const tempId = 'local-' + Date.now();
                const currentUser = window.CURRENT_USER_NAME || 'Bạn đọc';

                // 1) Đẩy vào UI ngay (đang kiểm tra)
                const temp = {
                    id: tempId,
                    name: currentUser,
                    avatar_url: '',
                    text: content,
                    time: nowIso(),
                    user_id: <?= (int)($_SESSION['user']['id'] ?? 0) ?>,
                    ai_checked: false, // Chưa AI check
                    commentId: null, // Sẽ cập nhật sau khi lưu DB
                    ai: {
                        isViolation: false,
                        isChecking: true,
                        details: 'Đang kiểm tra...'
                    }
                };
                comments.push(temp);
                renderComments(comments);

                // 2) LƯU DB TRƯỚC để lấy comment_id thật
                let savedCommentId = null;
                try {
                    const res = await fetch("<?= BASE_URL ?>/?url=comment&action=addComment", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded"
                        },
                        body: "article_id=" + encodeURIComponent(articleId) +
                            "&content=" + encodeURIComponent(content) +
                            "&user_id=" + encodeURIComponent(<?= (int)($_SESSION['user']['id'] ?? 0) ?>)
                    });

                    const data = await res.json();

                    if (data.status === "success" && data.comment_id) {
                        savedCommentId = data.comment_id;

                        // Gán comment_id thật vào object tạm + mảng
                        temp.commentId = savedCommentId;
                        const idx = comments.findIndex(c => c.id === tempId);
                        if (idx !== -1) {
                            comments[idx].commentId = savedCommentId;
                        }

                        // 3) CHỈ BÂY GIỜ MỚI GỌI AI CHECK (khi đã có ID thật)
                        //    => loại bỏ race condition hoàn toàn
                        checkCommentAsync(tempId, content, content);
                    } else {
                        // Lưu DB thất bại: rollback nhẹ UI (tuỳ chọn)
                        alert(data.message || "Lỗi khi lưu bình luận. Vui lòng thử lại!");
                        // Xoá comment tạm khỏi UI để tránh kẹt
                        const i = comments.findIndex(c => c.id === tempId);
                        if (i !== -1) comments.splice(i, 1);
                        renderComments(comments);
                    }
                } catch (err) {
                    console.error("Fetch lỗi:", err);
                    alert("Không thể gửi bình luận. Kiểm tra kết nối mạng và thử lại!");
                    // Xoá comment tạm khỏi UI để tránh kẹt
                    const i = comments.findIndex(c => c.id === tempId);
                    if (i !== -1) comments.splice(i, 1);
                    renderComments(comments);
                } finally {
                    // Dọn form & unlock nút
                    textarea.value = '';
                    autoGrow(textarea);
                    isSubmitting = false;
                    btnSend.disabled = false;
                }
            }

            // ===== AI check bất đồng bộ =====
            async function checkCommentAsync(localId, rawText, originalText = null) {
                try {
                    console.log('Starting AI check for comment:', rawText);

                    const res = await fetch('<?= BASE_URL ?>/checkCmt/check_comment.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            comment: rawText
                        })
                    });

                    console.log('AI check response status:', res.status);

                    if (!res.ok) {
                        const errText = await res.text();
                        console.error('AI check HTTP error:', errText);
                        throw new Error('HTTP ' + res.status + ': ' + errText);
                    }

                    const data = await res.json();
                    console.log('AI check response data:', data);

                    const result = data.result || {
                        isViolation: false
                    };

                    console.log('AI check result:', result);
                    result.originalText = originalText || rawText;
                    applyAIResult(localId, result);

                    // Lưu kết quả AI check vào database ngay lập tức
                    saveAIResultToDatabase(localId, result);

                    // Nếu có vi phạm, log thông báo đặc biệt
                    if (result.isViolation) {
                        console.log('🚨 VIOLATION DETECTED - Comment will be hidden from other users');
                    } else {
                        console.log('✅ Comment is safe, will be visible to all users');
                    }
                } catch (e) {
                    console.error('AI check error:', e);
                    applyAIResult(localId, {
                        isViolation: false,
                        details: 'Lỗi khi kiểm tra: ' + e.message,
                        isChecking: false
                    });
                }
            }

            function applyAIResult(localId, result) {
                console.log('Applying AI result for comment:', localId, result);
                console.log('Current comments array:', comments);

                const idx = comments.findIndex(c => c.id === localId);
                if (idx === -1) {
                    console.error('Comment not found for localId:', localId);
                    console.log('Available comment IDs:', comments.map(c => c.id));

                    // Thử tìm comment theo content hoặc timestamp gần nhất
                    const recentComment = comments.find(c =>
                        (c.text === result.originalText) ||
                        (c.content === result.originalText) ||
                        (c.ai && c.ai.isChecking)
                    );

                    if (recentComment) {
                        console.log('Found comment by content/timestamp:', recentComment);
                        const foundIdx = comments.findIndex(c => c.id === recentComment.id);
                        if (foundIdx !== -1) {
                            comments[foundIdx] = {
                                ...comments[foundIdx],
                                ai_checked: true, // Đã được AI check
                                ai: {
                                    isViolation: !!result.isViolation,
                                    isChecking: false,
                                    details: result.details || '',
                                    violationType: result.violationType || null,
                                    severity: result.severity || null,
                                    confidence: result.confidence ?? null,
                                    analysisMethod: result.analysisMethod || null,
                                }
                            };
                            console.log('Updated comment by fallback method:', comments[foundIdx]);
                            renderComments(comments);
                        }
                    } else {
                        // Nếu không tìm thấy comment nào, tạo một comment mới với kết quả AI
                        console.log('Creating new comment with AI result');
                        const currentUser = <?= json_encode($_SESSION['user']['name'] ?? 'Bạn đọc') ?>;
                        const newComment = {
                            id: 'ai-result-' + Date.now(),
                            name: currentUser || 'Bạn đọc',
                            avatar_url: '',
                            content: result.originalText || 'Comment đã được kiểm tra',
                            time: nowIso(),
                            user_id: <?= (int)($_SESSION['user']['id'] ?? 0) ?>,
                            ai_checked: true, // Đã được AI check
                            ai: {
                                isViolation: !!result.isViolation,
                                isChecking: false,
                                details: result.details || '',
                                violationType: result.violationType || null,
                                severity: result.severity || null,
                                confidence: result.confidence ?? null,
                                analysisMethod: result.analysisMethod || null,
                            }
                        };
                        comments.push(newComment);
                        console.log('Added new comment with AI result:', newComment);
                        renderComments(comments);
                    }
                    return;
                }

                const prev = comments[idx];
                comments[idx] = {
                    ...prev,
                    ai_checked: true, // Đã được AI check
                    ai: {
                        isViolation: !!result.isViolation,
                        isChecking: false,
                        details: result.details || '',
                        violationType: result.violationType || null,
                        severity: result.severity || null,
                        confidence: result.confidence ?? null,
                        analysisMethod: result.analysisMethod || null,
                        needsCheck: false // Đã check xong
                    }
                };

                console.log('Updated comment with AI result:', comments[idx]);
                renderComments(comments);
            }

            // ===== Lưu kết quả AI check vào database =====
            async function saveAIResultToDatabase(localId, aiResult) {
                try {
                    // Tìm comment trong mảng comments để lấy comment ID
                    const comment = comments.find(c => c.id === localId);
                    let commentId = null;

                    if (comment && comment.commentId) {
                        // Sử dụng comment ID đã lưu
                        commentId = comment.commentId;
                    } else {
                        // Fallback: lấy comment ID từ localId (cho comment cũ)
                        const realCommentId = localId.replace('db-', '').replace('local-', '');
                        commentId = realCommentId;
                    }

                    if (commentId) {
                        console.log('Saving AI result for comment ID:', commentId, 'Result:', aiResult);

                        // Lưu kết quả AI check
                        const saveRes = await fetch('<?= BASE_URL ?>/controller/updateAIresultController.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                comment_id: commentId,
                                ai_result: aiResult
                            })
                        });

                        const saveData = await saveRes.json();
                        console.log('AI result saved to database:', saveData);

                        if (saveData.success) {
                            console.log('✅ AI violation automatically updated in database');

                            // Hiển thị thông báo trong UI nếu có vi phạm
                            if (aiResult.isViolation) {
                                console.log('🔔 User will see violation warning in UI');
                            } else {
                                console.log('✅ Comment is safe, will be visible to all users');
                            }

                            // Trigger refresh để load comment mới cho user khác
                            setTimeout(() => {
                                loadNewComments();
                            }, 1000);
                        }
                    } else {
                        console.error('Could not find comment ID to save AI result');
                    }
                } catch (error) {
                    console.error('Error saving AI result to database:', error);
                }
            }

            // ===== Function xóa comment vi phạm =====
            window.deleteViolationComment = async function(commentId) {
                console.log('🗑️ Attempting to delete comment:', commentId);

                if (!confirm('Bạn có chắc chắn muốn xóa bình luận vi phạm này?')) {
                    return;
                }

                try {
                    // Tìm comment element bằng nhiều cách
                    let commentElement = null;

                    // Cách 1: Tìm bằng data-comment-id
                    commentElement = document.querySelector(`[data-comment-id="${commentId}"]`);
                    console.log('🔍 Method 1 - data-comment-id:', commentElement);

                    // Cách 2: Tìm bằng data-id
                    if (!commentElement) {
                        commentElement = document.querySelector(`[data-id="${commentId}"]`);
                        console.log('🔍 Method 2 - data-id:', commentElement);
                    }

                    // Cách 3: Tìm bằng class comment-card và data-comment-id
                    if (!commentElement) {
                        commentElement = document.querySelector(`.comment-card[data-comment-id="${commentId}"]`);
                        console.log('🔍 Method 3 - .comment-card[data-comment-id]:', commentElement);
                    }

                    // Cách 4: Tìm bằng onclick attribute
                    if (!commentElement) {
                        const allDeleteButtons = document.querySelectorAll('.delete-violation-btn');
                        console.log('🔍 Found', allDeleteButtons.length, 'delete buttons');
                        for (let btn of allDeleteButtons) {
                            if (btn.onclick && btn.onclick.toString().includes(commentId)) {
                                commentElement = btn.closest('.comment-card') || btn.closest('li');
                                console.log('🔍 Method 4 - onclick attribute:', commentElement);
                                break;
                            }
                        }
                    }

                    // Debug: List all elements with data-comment-id
                    const allCommentElements = document.querySelectorAll('[data-comment-id]');
                    console.log('🔍 All elements with data-comment-id:', allCommentElements.length);
                    allCommentElements.forEach((el, index) => {
                        console.log(`🔍 Element ${index}:`, el.dataset.commentId, el);
                    });

                    console.log('🔍 Found comment element:', commentElement);

                    // Xóa comment khỏi UI ngay lập tức
                    if (commentElement) {
                        // Tìm li element chứa comment
                        const liElement = commentElement.closest('li') || commentElement;
                        liElement.remove();
                        console.log('✅ Comment removed from UI');
                    } else {
                        console.warn('⚠️ Comment element not found in UI, but will still try to delete from database');
                    }

                    // TÌM COMMENT ID THẬT TRƯỚC KHI XÓA KHỎI MẢNG
                    const currentUserId = <?= (int)($_SESSION['user']['id'] ?? 0) ?>;

                    // Debug: Log tất cả comments trong mảng TRƯỚC KHI XÓA
                    console.log('📡 All comments in array (BEFORE DELETE):', comments.length);
                    comments.forEach((c, index) => {
                        console.log(`📡 Comment ${index}:`, {
                            id: c.id,
                            commentId: c.commentId,
                            user_id: c.user_id,
                            content: c.content?.substring(0, 50) + '...'
                        });
                    });

                    // Tìm comment trong mảng để lấy comment ID thật
                    const commentFromArray = comments.find(c => c.id === commentId);
                    let realCommentId = null;

                    if (commentFromArray) {
                        console.log('📡 Comment from array:', commentFromArray);
                        console.log('📡 Comment user_id from array:', commentFromArray.user_id);

                        // Nếu comment có commentId (đã lưu DB), dùng nó
                        if (commentFromArray.commentId) {
                            realCommentId = commentFromArray.commentId;
                            console.log('📡 Using commentId from array:', realCommentId);
                        } else {
                            // Fallback: xử lý ID như cũ
                            realCommentId = commentId.replace('db-', '').replace('local-', '');
                            console.log('📡 Using processed ID as fallback:', realCommentId);

                            // Kiểm tra nếu realCommentId là số hợp lệ
                            if (!realCommentId || isNaN(realCommentId) || realCommentId <= 0) {
                                console.error('❌ Invalid realCommentId:', realCommentId);
                                alert('❌ Không thể xác định ID comment để xóa!');
                                return;
                            }
                        }
                    } else {
                        console.log('📡 Comment not found in comments array');
                        // Fallback: xử lý ID như cũ
                        realCommentId = commentId.replace('db-', '').replace('local-', '');
                        console.log('📡 Using processed ID as fallback:', realCommentId);

                        // Kiểm tra nếu realCommentId là số hợp lệ
                        if (!realCommentId || isNaN(realCommentId) || realCommentId <= 0) {
                            console.error('❌ Invalid realCommentId:', realCommentId);
                            alert('❌ Không thể xác định ID comment để xóa!');
                            return;
                        }
                    }

                    console.log('📡 Calling delete API for comment:', realCommentId);
                    console.log('📡 Current user ID:', currentUserId);
                    console.log('📡 Original comment ID:', commentId);
                    console.log('📡 Final real comment ID:', realCommentId);

                    // BÂY GIỜ MỚI XÓA COMMENT KHỎI MẢNG VÀ RENDER UI
                    const commentIndex = comments.findIndex(c => c.id === commentId);
                    if (commentIndex !== -1) {
                        comments.splice(commentIndex, 1);
                        console.log('✅ Comment removed from comments array');
                    }

                    // Cập nhật UI
                    renderComments(comments);

                    // Debug: Log request body
                    const requestBody = "comment_id=" + encodeURIComponent(realCommentId) +
                        "&user_id=" + encodeURIComponent(currentUserId);
                    console.log('📡 Request body:', requestBody);
                    console.log('📡 Encoded realCommentId:', encodeURIComponent(realCommentId));
                    console.log('📡 Encoded currentUserId:', encodeURIComponent(currentUserId));

                    const response = await fetch("<?= BASE_URL ?>/?url=comment&action=deleteComment", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded"
                        },
                        body: requestBody
                    });

                    console.log('📡 Delete API response status:', response.status);

                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }

                    const data = await response.json();
                    console.log('📡 Delete API response data:', data);

                    if (data.status === "success") {
                        console.log('✅ Comment vi phạm đã được xóa');
                    } else {
                        console.error('❌ Lỗi khi xóa comment:', data.message);
                        alert('❌ Có lỗi xảy ra khi xóa bình luận: ' + (data.message || 'Lỗi không xác định'));
                    }

                } catch (error) {
                    console.error('❌ Lỗi khi xóa comment:', error);
                    alert('❌ Có lỗi xảy ra khi xóa bình luận: ' + error.message);
                }
            };

            // ===== Events =====
            btnSend.addEventListener("click", sendComment);
            textarea.addEventListener("keydown", function(e) {
                if (e.key === "Enter" && !e.shiftKey) {
                    e.preventDefault();
                    sendComment();
                }
            });
            textarea.addEventListener('input', () => autoGrow(textarea));

            // đặt replyComment lên window cho onclick
            window.replyComment = function(name) {
                const pre = name ? '@' + name + ' ' : '';
                textarea.value = pre + textarea.value;
                textarea.focus();
                autoGrow(textarea);
            };

            // ===== Khởi tạo =====
            document.addEventListener('DOMContentLoaded', () => {
                loadCommentsFromDB(); // nạp từ DB như cũ

                // Auto refresh - chỉ load comment mới của người khác đã được AI check và không vi phạm
                setInterval(loadNewComments, 3000);
            });

        })();

        //Like/Dislike
        document.addEventListener("DOMContentLoaded", function() {
            const comLike = document.querySelector(".com-like");
            if (!comLike) return;

            const articleId = comLike.dataset.id;
            const btnUp = comLike.querySelector(".for-up");
            const btnDown = comLike.querySelector(".for-down");
            const valueSpan = comLike.querySelector(".value");

            let isVoting = false;

            function updateLikeUI(likeCount, userLiked) {
                valueSpan.textContent = likeCount;
                btnUp.classList.toggle("active", userLiked === true);
                // btnDown chỉ để hủy like nên active khi user đã like
                btnDown.classList.toggle("active", userLiked === true);
            }

            async function loadLikeStatus() {
                try {
                    const res = await fetch(`<?= BASE_URL ?>/?url=like&action=get&article_id=${articleId}`);
                    const data = await res.json();
                    if (data.status === "success") {
                        // data.like = số like
                        // data.user_vote = "like" hoặc ""
                        updateLikeUI(data.like, data.user_vote === "like");
                    }
                } catch (err) {
                    console.error("Lỗi load like:", err);
                }
            }

            async function handleVote(type) {
                const userId = <?= $_SESSION['user']['id'] ?? 0 ?>;
                if (!userId) return alert("Bạn cần đăng nhập để thực hiện thao tác này!");
                if (isVoting) return;

                isVoting = true;

                try {
                    const res = await fetch(`<?= BASE_URL ?>/?url=like&action=toggle`, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded"
                        },
                        body: `article_id=${encodeURIComponent(articleId)}&type=${encodeURIComponent(type)}&user_id=${encodeURIComponent(userId)}`
                    });

                    const data = await res.json();

                    if (data.status === "success") {
                        // data.like = số like mới
                        // data.user_vote = 'like' hoặc ''
                        updateLikeUI(data.like, data.user_vote === "like");
                    } else {
                        alert(data.msg || "Có lỗi xảy ra khi gửi vote.");
                    }
                } catch (err) {
                    console.error("Lỗi khi gửi vote:", err);
                } finally {
                    isVoting = false;
                }
            }

            btnUp.addEventListener("click", () => handleVote("like"));
            btnDown.addEventListener("click", () => handleVote("dislike"));

            loadLikeStatus();
        });

        // JS for Share & Copy Link
        document.addEventListener('click', function(event) {
            const target = event.target;

            // --- Toggle Dropdown ---
            if (target.hasAttribute('data-bs-toggle') && target.getAttribute('data-bs-toggle') === 'dropdown') {
                event.preventDefault();
                const dropdown = target.closest('.home-item');
                const menu = dropdown.querySelector('.dropdown-menu');
                if (menu) {
                    menu.classList.toggle('show');
                }
            }

            // --- Copy Link ---
            if (target.classList.contains('copylink')) {
                event.preventDefault();
                const urlToCopy = target.getAttribute('data-url');
                if (urlToCopy) {
                    navigator.clipboard.writeText(urlToCopy).then(() => {
                        alert('Đã sao chép link!');
                    }).catch(err => {
                        console.error('Lỗi khi sao chép: ', err);
                        alert('Không thể sao chép link.');
                    });
                }
            }

            // --- Share to Facebook ---
            if (target.classList.contains('sharefb')) {
                event.preventDefault();
                const urlToShare = target.getAttribute('data-url');
                if (urlToShare) {
                    const facebookShareUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(urlToShare)}`;
                    window.open(facebookShareUrl, 'facebook-share-dialog', 'width=800,height=600');
                }
            }

            // --- Close dropdown when clicking outside ---
            if (!target.closest('.home-item')) {
                document.querySelectorAll('.home-item .dropdown-menu.show').forEach(menu => {
                    menu.classList.remove('show');
                });
            }
        });
    </script>

    <style>
        /* Share Dropdown Styles */
        .home-item {
            position: relative;
            display: inline-block;
        }

        .home-item .dropdown-menu {
            position: absolute;
            top: 100%;
            left: 0;
            z-index: 1000;
            display: none;
            min-width: 160px;
            padding: 5px 0;
            margin: 2px 0 0;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, .175);
        }

        .home-item .dropdown-menu.show {
            display: block;
        }

        .home-item .dropdown-item {
            display: block;
            width: 100%;
            padding: 8px 16px;
            clear: both;
            font-weight: normal;
            line-height: 1.42857143;
            color: #333;
            white-space: nowrap;
            text-decoration: none;
            background-color: transparent;
            border: 0;
        }

        .home-item .dropdown-item:hover,
        .home-item .dropdown-item:focus {
            color: #262626;
            text-decoration: none;
            background-color: #f5f5f5;
        }

        /* AI Comment Check Styles - Enhanced */
        .ai-violation-warning {
            animation: fadeIn 0.3s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-5px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .comment-card.violation {
            border-left: 4px solid #ffc107;
            background-color: #fffbf0;
        }

        /* Ẩn các trạng thái checking và safe */
        .comment-card.checking,
        .comment-card.safe {
            /* Không có styling đặc biệt */
        }

        /* Styling cho nút xóa comment vi phạm - Cải thiện */
        .delete-violation-btn {
            background: linear-gradient(135deg, #dc3545, #c82333) !important;
            color: white !important;
            border: none !important;
            padding: 8px 12px !important;
            border-radius: 6px !important;
            font-size: 12px !important;
            font-weight: 600 !important;
            cursor: pointer !important;
            margin-left: 10px !important;
            box-shadow: 0 2px 4px rgba(220, 53, 69, 0.3) !important;
            transition: all 0.3s ease !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 4px !important;
            min-width: 70px !important;
            justify-content: center !important;
        }

        .delete-violation-btn:hover {
            background: linear-gradient(135deg, #c82333, #a71e2a) !important;
            transform: translateY(-1px) !important;
            box-shadow: 0 4px 8px rgba(220, 53, 69, 0.4) !important;
            opacity: 1 !important;
        }

        .delete-violation-btn:active {
            transform: translateY(0) !important;
            box-shadow: 0 2px 4px rgba(220, 53, 69, 0.3) !important;
        }

        .delete-violation-btn i {
            font-size: 11px !important;
        }

        .comment-card-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        /* Ẩn nút xóa mặc định, chỉ hiện khi hover */
        .comment-card .delete-violation-btn {
            opacity: 0;
            transition: all 0.3s ease;
        }

        .comment-card:hover .delete-violation-btn {
            opacity: 0.9;
        }

        .comment-card.violation .delete-violation-btn {
            opacity: 0.9;
            /* Luôn hiện cho comment vi phạm */
        }

        /* Animation cho nút xóa khi xuất hiện */
        .delete-violation-btn {
            animation: slideInRight 0.3s ease-out;
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(20px);
            }

            to {
                opacity: 0.9;
                transform: translateX(0);
            }
        }
    </style>

</main>