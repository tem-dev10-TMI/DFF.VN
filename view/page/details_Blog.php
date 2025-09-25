    <?php
    // $article should be provided by Controller similar to view/page/detail_blog.php
    // Fallbacks
    $authorName = htmlspecialchars($article['author_name'] ?? 'Tác giả');
    $authorId = isset($article['author_id']) ? intval($article['author_id']) : 0;
    $authorAvatar = $article['avatar_url'] ?? '/vendor/dffvn/content/img/user.svg';
    if (!$authorAvatar || trim($authorAvatar) === '') {
        $authorAvatar = 'https://i.pravatar.cc/100?u=' . urlencode($authorName);
    }
    $createdAt = isset($article['created_at']) ? date('d/m/Y H:i', strtotime($article['created_at'])) : '';
    $title = htmlspecialchars($article['title'] ?? '');
    $summary = nl2br(htmlspecialchars($article['summary'] ?? ''));
    $content = nl2br(htmlspecialchars($article['content'] ?? ''));
    $mainImage = $article['main_image_url'] ?? '';
    $videoUrl = $article['media_url'] ?? '';

    ?>
<?php
require_once __DIR__ . '/../../model/user/UserFollowModel.php';
$db = new connect();
$pdo = $db->db;
$followModel = new UserFollowModel($pdo);

// Đảm bảo có authorId
$authorId = isset($authorId) ? intval($authorId) : 0;
$totalFollowers = $authorId > 0 ? $followModel->countFollowers($authorId) : 0;
?>
    <main class="main-content">
        <div class="block-k">

            <div class="d-topinfo">
                <div class="provider">
                    <a href="javascript:void(0)" class="img-news"><img class="logo" alt=""
                            src="<?= htmlspecialchars($authorAvatar) ?>"></a>
                    <div class="p-covers">
                        <span class="name" title="">
                            <a href="<?= BASE_URL ?>/view_profile?id=<?= $authorId ?>"
                                title="<?= $authorName ?>"><?= $authorName ?></a>
                            <i title="Đã xác thực" class="accu_none fas fa-check-circle"></i>
                        </span><span class="date"><?= htmlspecialchars($createdAt) ?></span>
                    </div>
                </div>

<div class="follower-r f-right" style="font-size: 13px;">
    <b class="total-fol"><?= intval($totalFollowers) ?></b> Người theo dõi
</div>            </div>

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
                        <?php if (!empty($videoUrl)): ?>
                            <div class="video-wrapper" style="margin: 20px 0;">
                                <video width="100%" height="auto" controls>
                                    <source src="<?= htmlspecialchars($videoUrl) ?>" type="video/mp4">
                                    Trình duyệt của bạn không hỗ trợ video.
                                </video>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($content)): ?>
                            <p><?= $content ?></p>
                        <?php endif; ?>


                        <div class="bysource"></div>
                    </div>

                    <div class="box-trends" bis_skin_checked="1">
                        <h5>
                            <a href="#" title="Bài viết liên quan">Nội dung liên quan</a>
                        </h5>
                        <ul>
                            <?php if (!empty($relatedArticles)): ?>
                                <?php foreach ($relatedArticles as $ra): ?>
                                    <li>
                                        <a href="<?= BASE_URL ?>/details_blog/<?= $ra['slug'] ?>"
                                            title="<?= htmlspecialchars($ra['title']) ?>">
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

                        <div class="button-ar sharefb" data-url="/post-<?= htmlspecialchars($article['id'] ?? '') ?>.html">
                            <i class="far fa-share-square "
                                data-url="/post-<?= htmlspecialchars($article['id'] ?? '') ?>.html"></i><span>Chia sẻ</span>
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
            (function() {
                // Tránh khởi tạo trùng nếu script bị load 2 lần
                const section = document.querySelector(".comment-section");
                if (!section || section.dataset.inited === "1") return;
                section.dataset.inited = "1";

                document.addEventListener("DOMContentLoaded", function() {
                    const articleId = document.getElementById("submit-comment").dataset.id;
                    const btnSend = document.getElementById("submit-comment");
                    const textarea = document.getElementById("new-comment");

                    let isSubmitting = false; // chặn gửi trùng

                    // ---- Load list ban đầu
                    loadComments(articleId);

                    // ---- Gửi comment
                    async function sendComment() {
                        const content = textarea.value.trim();
                        if (!content) {
                            alert("Vui lòng nhập nội dung bình luận!");
                            return;
                        }
                        if (isSubmitting) return; // chặn double-click
                        isSubmitting = true;
                        btnSend.disabled = true;

                        try {
                            const res = await fetch("<?= BASE_URL ?>/?url=comment&action=addComment", {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/x-www-form-urlencoded"
                                },
                                body: "article_id=" + encodeURIComponent(articleId) +
                                    "&content=" + encodeURIComponent(content) +
                                    "&user_id=" + encodeURIComponent(<?= $_SESSION['user']['id'] ?? '0' ?>)
                            });
                            const data = await res.json();

                            if (data.status === "success") {
                                textarea.value = "";
                                await loadComments(articleId);
                            } else {
                                alert(data.message || "Lỗi khi gửi bình luận!");
                            }
                        } catch (err) {
                            console.error("Fetch lỗi:", err);
                            alert("Không thể gửi bình luận. Vui lòng thử lại.");
                        } finally {
                            isSubmitting = false;
                            btnSend.disabled = false;
                        }
                    }

                    // ---- Gắn sự kiện 1 lần
                    btnSend.addEventListener("click", sendComment, {
                        once: false
                    });

                    // Enter để gửi, Shift+Enter xuống dòng
                    textarea.addEventListener("keydown", function(e) {
                        if (e.key === "Enter" && !e.shiftKey) {
                            e.preventDefault();
                            sendComment();
                        }
                    });
                });

                // ====== Helpers ======
                function renderComments(comments) {
                    const list = document.getElementById("comment-items");
                    const empty = document.getElementById("comment-empty");
                    const countSpan = document.getElementById("comments-count");

                    list.innerHTML = "";

                    if (!comments || comments.length === 0) {
                        empty.style.display = "block";
                        countSpan.textContent = "(0)";
                        return;
                    }

                    empty.style.display = "none";
                    countSpan.textContent = "(" + comments.length + ")";

                    comments.forEach(c => {
                        const li = document.createElement("li");
                        li.classList.add("comment-card");
                        li.innerHTML = `
            <img src="${c.avatar_url}" class="comment-card-avatar">
            <div class="comment-card-body">
            <div class="comment-card-meta">
                <span class="comment-card-name">${c.name || "Ẩn danh"}</span>
                <span class="comment-card-time">${new Date(c.created_at).toLocaleString("vi-VN")}</span>
            </div>
            <div class="comment-card-content">${c.content}</div>
            <div class="comment-card-actions">
                <a href="javascript:void(0)" onclick="replyComment('${c.name || ""}')">Trả lời</a>
            </div>
            </div>`;
                        list.appendChild(li);
                    });
                }

                async function loadComments(articleId) {
                    try {
                        const res = await fetch("<?= BASE_URL ?>/?url=comment&action=getComments&article_id=" + articleId);
                        const data = await res.json();
                        if (data.status === "success") {
                            renderComments(data.comments);
                        }
                    } catch (err) {
                        console.error("Lỗi fetch:", err);
                    }
                }

                // đặt replyComment lên window để onclick dùng được
                window.replyComment = function(name) {
                    const input = document.getElementById("new-comment");
                    input.value = (name ? "@" + name + " " : "") + input.value;
                    input.focus();
                };
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
        </script>
    </main>