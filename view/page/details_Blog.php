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
?>

<main class="main-content">
    <div class="block-k">

        <div class="d-topinfo">
            <div class="provider">
                <a href="javascript:void(0)" class="img-news"><img class="logo" alt="" src="<?= htmlspecialchars($authorAvatar) ?>"></a>
                <div class="p-covers">
                    <span class="name" title="">
                        <a href="<?= BASE_URL ?>/view_profile?id=<?= $authorId ?>" title="<?= $authorName ?>"><?= $authorName ?></a>
                        <i title="Đã xác thực" class="accu_none fas fa-check-circle"></i>
                    </span><span class="date"><?= htmlspecialchars($createdAt) ?></span>
                </div>
            </div>

            <div class="follower-r f-right" style="font-size: 13px;"><b class="total-fol">0</b> Người theo dõi</div>
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

                <div class="d-tags">
                    <ul>
                        <li><i class="fas fa-tags"></i></li>
                        <?php if (!empty($article['tags']) && is_array($article['tags'])): foreach ($article['tags'] as $tag): ?>
                                <li><a href="/search.html?q=<?= urlencode($tag) ?>"><?= htmlspecialchars($tag) ?></a></li>
                        <?php endforeach;
                        endif; ?>
                    </ul>
                </div>

            </article>
            <input type="hidden" id="hdd_id" value="<?= htmlspecialchars($article['id'] ?? '') ?>">

            <div data-id="<?= htmlspecialchars($article['id'] ?? '') ?>" data-type="1" class="box-n-sc">

                <div class="item-bottom">
                    <div class="bt-cover com-like" data-id="<?= htmlspecialchars($article['id'] ?? '') ?>">
                        <span class="for-up">
                            <svg rpl="" class="" data-voted="false" data-type="up" fill="currentColor" height="16" icon-name="upvote-fill" viewBox="0 0 20 20" width="16" xmlns="http://www.w3.org/2000/svg">
                                <path d="M18.706 8.953 10.834.372A1.123 1.123 0 0 0 10 0a1.128 1.128 0 0 0-.833.368L1.29 8.957a1.249 1.249 0 0 0-.171 1.343 1.114 1.114 0 0 0 1.007.7H6v6.877A1.125 1.125 0 0 0 7.123 19h5.754A1.125 1.125 0 0 0 14 17.877V11h3.877a1.114 1.114 0 0 0 1.005-.7 1.251 1.251 0 0 0-.176-1.347Z"></path>
                            </svg>
                        </span>
                        <span class="value" data-old="0">0</span>
                        <span class="for-down">
                            <svg rpl="" class="" data-voted="false" data-type="down" fill="currentColor" height="16" icon-name="downvote-fill" viewBox="0 0 20 20" width="16" xmlns="http://www.w3.org/2000/svg">
                                <path d="M18.88 9.7a1.114 1.114 0 0 0-1.006-.7H14V2.123A1.125 1.125 0 0 0 12.877 1H7.123A1.125 1.125 0 0 0 6 2.123V9H2.123a1.114 1.114 0 0 0-1.005.7 1.25 1.25 0 0 0 .176 1.348l7.872 8.581a1.124 1.124 0 0 0 1.667.003l7.876-8.589A1.248 1.248 0 0 0 18.88 9.7Z"></path>
                            </svg>
                        </span>
                    </div>

                    <div class="button-ar sharefb" data-url="/post-<?= htmlspecialchars($article['id'] ?? '') ?>.html">
                        <i class="far fa-share-square " data-url="/post-<?= htmlspecialchars($article['id'] ?? '') ?>.html"></i><span>Chia sẻ</span>
                    </div>
                    <div class="button-ar fc-saved">
                        <i title="copy link bài viết" class="fas fa-link copylink" data-url="/post-<?= htmlspecialchars($article['id'] ?? '') ?>.html"></i>
                        <i module-load="savenews" title="lưu bài viết" class="far fa-bookmark"></i>
                    </div>
                    <div class="button-ar">
                        <i class="fas fa-exclamation-triangle"></i><span module-load="report">Báo cáo</span>
                    </div>
                </div>

            </div>

        </div>

        <div class="line"></div>
        <div class="d-bottom">
            <div class="col1">
                <div class="provider">
                    <img class="logo" alt="" src="<?= htmlspecialchars($authorAvatar) ?>">
                    <div class="p-covers">
                        <span class="name" title=""><a href="<?= BASE_URL ?>/view_profile?id=<?= $authorId ?>" title="<?= $authorName ?>"><?= $authorName ?></a>
                        </span><span class="date">Người dùng</span>
                    </div>
                </div>
                <div class="bt-foll">
                    <a href="javascript:void(0)" data-type="3" module-load="follow" data-ref="<?= $authorId ?>">
                        <val> Theo dõi</val>
                    </a>
                </div>
            </div>
            <div class="col2">
                <div class="provider">
                    <span class="cus-avatar">D</span>
                    <div class="p-covers">
                        <span class="name" title=""><a href="#" title="Chủ đề">Chủ đề</a>
                        </span><span class="date">Chủ đề</span>
                    </div>
                </div>
                <div class="bt-foll">
                    <a href="javascript:void(0)" data-type="3" module-load="follow" data-ref="<?= $authorId ?>">
                        <val> Theo dõi</val>
                    </a>
                </div>
            </div>
        </div>

    </div>
    <div class="block-k">
        <h5 class="total-cm"><i class="fas fa-comments"></i> Bình luận <span></span></h5>
        <div class="comment-box">
            <a href="javascript:void(0)" class="img-own"> <img src="/vendor/dffvn/content/img/user.svg"> </a>
            <textarea id="comment-content" class="form-control autoresizing" placeholder="Bạn nghĩ gì về nội dung này?"></textarea>
            <i id="send-comment" class="fas fa-paper-plane" data-id="<?= htmlspecialchars($article['id'] ?? '') ?>" module-load="csend"></i>
            </div>

        <div class="comment-cover" style="display: none;">
    <ul class="list_comment col-md-12 comment-list"></ul>
    <div class="cm-more">Xem thêm</div>
</div>

<!-- Nếu chưa có comment -->
<div class="first-comment">
    <i class="far fa-comments"></i>
    <p>Trở thành người bình luận đầu tiên</p>
</div>

<div class="no-comment" style="display:none;">
    <i class="far fa-comments"></i>
    <p>Chưa có bình luận nào</p>
</div>

<style>
.comment-list {
    list-style: none;
    margin: 0;
    padding: 0;
}

.comment-list .chat-item {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    border-bottom: 1px solid #eee;
    padding: 12px 0;
}

.comment-list .chat-avatar {
    flex-shrink: 0;
}

.comment-list .chat-avatar img,
.comment-list .chat-avatar .avatar-fallback {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    object-fit: cover;
    background: #ddd;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 16px;
    color: #555;
}

.comment-list .chat-body {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.comment-list .chat-meta {
    margin-bottom: 4px;
}

.comment-list .chat-name {
    font-weight: 600;
    color: #333;
    margin-right: 8px;
    display: inline-block;
}

.comment-list .chat-time {
    font-size: 12px;
    color: #999;
    display: inline-block;
}

.comment-list .chat-content {
    font-size: 14px;
    color: #222;
    margin-bottom: 6px;
    line-height: 1.4;
    white-space: pre-wrap;
    word-break: break-word;
}

.comment-list .chat-actions {
    font-size: 13px;
    color: #555;
    display: flex;
    align-items: center;
    gap: 12px;
}

.comment-list .chat-actions button {
    border: none;
    background: none;
    cursor: pointer;
    padding: 0;
    font-size: 14px;
}

.comment-list .chat-actions .vote-count {
    font-size: 13px;
    font-weight: 500;
    color: #333;
}

.comment-list .chat-actions a {
    color: #004080;
    text-decoration: none;
    font-weight: 500;
    cursor: pointer;
}

.comment-list .chat-actions a:hover {
    text-decoration: underline;
}

</style>
<script>
function renderComments(comments) {
    const ul = document.querySelector(".list_comment");
    ul.innerHTML = "";

    if (!comments || comments.length === 0) {
        document.querySelector(".first-comment").style.display = "block"; 
        document.querySelector(".comment-cover").style.display = "none";  
        return;
    }

    document.querySelector(".first-comment").style.display = "none";  
    document.querySelector(".comment-cover").style.display = "block"; 

    comments.forEach(c => {
        const li = document.createElement("li");
        li.classList.add("chat-item");
        li.innerHTML = `
            <div class="chat-avatar">
                ${c.avatar_url 
                    ? `<img src="${c.avatar_url}" alt="${c.username}">`
                    : `<span class="avatar-fallback">${c.username ? c.username.charAt(0).toUpperCase() : "?"}</span>`}
            </div>
            <div class="chat-body">
                <div class="chat-meta">
                    <span class="chat-name">${c.username || "Ẩn danh"}</span>
                    <span class="chat-time">${new Date(c.created_at).toLocaleString("vi-VN")}</span>
                </div>
                <div class="chat-content">${c.content}</div>
                <div class="chat-actions">
                    <button class="vote-btn">⬆</button>
                    <span class="vote-count">${c.upvotes ?? 0}</span>
                    <button class="vote-btn">⬇</button>
                    <a href="javascript:void(0)" class="chat-reply">Trả lời</a>
                </div>
            </div>
        `;
        // Thêm bình luận mới nhất lên trên cùng
        ul.prepend(li);
    });
}

function loadComments(articleId) {
    fetch(`<?= BASE_URL ?>/controller/commentController.php?action=getComments&article_id=${articleId}`)
        .then(res => res.json())
        .then(data => {
            console.log("Server trả về:", data); // debug
            if (data.status === "success") {
                renderComments(data.comments);
            } else {
                document.querySelector(".first-comment").style.display = "block";
                document.querySelector(".comment-cover").style.display = "none";
            }
        })
        .catch(err => {
            console.error("Lỗi fetch:", err);
            document.querySelector(".first-comment").style.display = "block";
            document.querySelector(".comment-cover").style.display = "none";
        });
}

document.addEventListener("DOMContentLoaded", function () {
    const articleId = document.getElementById("hdd_id").value;
    const btnSend   = document.getElementById("send-comment"); 
    const textarea  = document.getElementById("comment-content");

    // Load sẵn bình luận khi vào trang
    loadComments(articleId);

    // Gửi comment mới
    btnSend.addEventListener("click", function () {
        const content = textarea.value.trim();
        if (!content) {
            alert("Vui lòng nhập nội dung bình luận!");
            return;
        }

        fetch("<?= BASE_URL ?>/controller/commentController.php?action=addComment", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "article_id=" + encodeURIComponent(articleId) +
                  "&content=" + encodeURIComponent(content) +
                  "&user_id=" + encodeURIComponent(<?= $_SESSION['user']['id'] ?? '0' ?>)
        })
        .then(res => res.json())
        .then(data => {
            console.log("Kết quả add:", data); // debug
            if (data.status === "success") {
                textarea.value = "";
                loadComments(articleId); // reload lại danh sách
            } else {
                alert(data.message || "Lỗi khi gửi bình luận!");
            }
        })
        .catch(err => {
            console.error("Fetch lỗi:", err);
            alert("Không thể kết nối server!");
        });
    });
});
</script>



</main>