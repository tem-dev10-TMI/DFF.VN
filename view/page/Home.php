<?php
// Start session nếu chưa có
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../model/CommentGlobalModel.php';

$comments = CommentGlobalModel::getRootCommentsPaged(20, 0);

// require_once __DIR__ . '/../../config/db.php';
// require_once __DIR__ . '/../../model/article/articlesmodel.php';
// require_once __DIR__ . '/../../model/commentmodel.php';
// require_once __DIR__ . '/../../model/user/businessmenModel.php';

// $comments = CommentsModel::getComments();
// $articles = ArticlesModel::getAllArticles();      
// $topBusinessmen = businessmenModel::getAllBusinessmen(10); // Lấy tối đa 10 doanh nhân                                                                                                                                                                      
?>
<link rel="stylesheet" href="<?= BASE_URL ?>/public/css/style.css?v=1.3">

<main class="main-content">


    <!-- mo modal khi sai mat khau -->
    <?php if (isset($_SESSION['login_error'])): ?>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                var myModal = new bootstrap.Modal(document.getElementById('div_modal'));
                myModal.show();
            });
        </script>
    <?php endif; ?>

    <!-- 4 cục bài viết nổi bật start -->
    <div class="owl-slider home-slider">
        <div id="home_slider" class="owl-carousel">
            <?php if (!empty($featuredArticles)): ?>
                <?php foreach ($featuredArticles as $article): ?>
                    <div class="item">
                        <div class="" style="display: none">
                            <a title="<?= htmlspecialchars($article['title']) ?>"
                                href="<?= 'details_blog/' . urlencode($article['slug']) ?>"
                                target="_self">
                                <div class="mmavatar"><?= htmlspecialchars($article['title']) ?></div>
                            </a>
                        </div>
                        <div class="cover-hover" style="">
                            <?php if (!empty($article['main_image_url'])): ?>
                                <img src="<?= htmlspecialchars($article['main_image_url']) ?>"
                                    title="<?= htmlspecialchars($article['title']) ?>"
                                    alt="<?= htmlspecialchars($article['title']) ?>" border="0" />
                            <?php else: ?>
                                <div class="mmavatar" style="height: 157px; display: flex; align-items: center; justify-content: center; background-color:rgb(110, 130, 160);">
                                    <span>Người dùng này chưa thêm ảnh</span>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="text" style="">
                            <h4>
                                <a title="<?= htmlspecialchars($article['title']) ?>"
                                    href="<?= !empty($article['is_rss']) ? htmlspecialchars($article['link']) : ('details_blog/' . urlencode($article['slug'])) ?>"
                                    target="<?= !empty($article['is_rss']) ? '_blank' : '_self' ?>">
                                    <?= htmlspecialchars($article['title']) ?>
                                </a>
                            </h4>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Không có bài viết nào trong cơ sở dữ liệu.</p>
            <?php endif; ?>
        </div>
    </div>
    <!-- 4 cục bài viết nổi bật end -->

    <!-- bài viết chính block start -->
    <div class="content-left cover-page">

        <div class="block-k box-write openModalcreatePost">
            <a href="javascript:void(0)" class="img-own"> <img src="https://dff.vn/vendor/dffvn/content/img/user.svg">
            </a>
            <div class="input-group box-search">
                <div data-bs-toggle="modal"><span>Viết
                        bài, chia sẻ, đặt câu hỏi…</span></div>

            </div>
            <img alt="Viết bài, chia sẻ, đặt câu hỏi" module-load="loadwrite"
                src="https://dff.vn/vendor/dffvn/content/img/img_small.jpg" width="30">
        </div>
        <script>
            document.querySelector(".openModalcreatePost").addEventListener("click", function() {
                <?php if (isset($_SESSION['user_id'])): ?>
                    // Nếu đã đăng nhập thì mở modal
                    var myModal = new bootstrap.Modal(document.getElementById('createPostModal'));
                    myModal.show();
                <?php else: ?>
                    // Nếu chưa đăng nhập thì chuyển sang login hoặc cảnh báo
                    alert("Bạn cần đăng nhập để viết bài.");
                <?php endif; ?>
            });
        </script>

        <!-- ////////////////////// -->
        <div class="block-k box-company-label">
            <h5>
                <span><a href="#">Top doanh nhân</a></span>
                <span class="c-note">
                    <i class="fas fa-chart-line"></i> Được tìm kiếm nhiều nhất
                </span>
            </h5>


            <div class="owl-slider">
                <div class="owl-carousel box-company owl-loaded owl-drag">
                    <div class="owl-stage-outer owl-height" style="height: 256px;">

                        <div class="owl-stage"
                            style="transform: translate3d(0px, 0px, 0px); transition: all; width: <?= count($topBusinessmen) * 182.667 + (count($topBusinessmen) - 1) * 10 ?>px;">
                            <?php if (!empty($topBusinessmen)): ?>
                                <?php //var_dump($topBusinessmen);
                                ?>

                                <?php foreach ($topBusinessmen as $biz): ?>
                                    <?php
                                    $isFollowing = false;
                                    if (isset($_SESSION['user']['id'])) {
                                        require_once __DIR__ . '/../../model/user/UserFollowModel.php';
                                        $db = new connect();
                                        $pdo = $db->db;
                                        $followModel = new UserFollowModel($pdo);
                                        $isFollowing = $followModel->isFollowing($_SESSION['user']['id'], $biz['user_id']);
                                    }
                                    ?>
                                    <div class="owl-item active" style="width: 182.667px; margin-right: 10px;">
                                        <div class="item">
                                            <ul>
                                                <li>
                                                    <img class="logo"
                                                        alt="<?= htmlspecialchars($biz['username'] ?? $biz['name']) ?>"
                                                        src="<?= htmlspecialchars($biz['avatar_url'] ?? 'https://via.placeholder.com/150') ?>">
                                                </li>
                                                <li class="alias">
                                                    <?= htmlspecialchars($biz['position'] ?? 'Doanh nhân') ?>
                                                </li>
                                                <li class="name">
                                                    <a href="<?= BASE_URL ?>/view_profile?id=<?= $biz['user_id'] ?>">
                                                        <?= htmlspecialchars($biz['username'] ?? $biz['name']) ?>
                                                    </a>
                                                </li>
                                                <li class="f-folw">
                                                    <a class="btn-follow" href="javascript:void(0)"
                                                        data-user="<?= $biz['user_id'] ?>">
                                                        <span
                                                            class="follow-text"><?= $isFollowing ? "Đang theo dõi" : "Theo dõi" ?></span>
                                                        <span class="number"><?= intval($biz['followers'] ?? 0) ?></span>
                                                    </a>


                                                </li>


                                            </ul>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p>Chưa có doanh nhân nào.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="owl-nav">

                        <button type="button" role="presentation" class="owl-prev disabled">
                            <i class="fa fa-chevron-left"></i>
                        </button>
                        <button type="button" role="presentation" class="owl-next">
                            <i class="fa fa-chevron-right"></i>
                        </button>

                    </div>
                    <div class="owl-dots disabled"></div>
                </div>
            </div>
        </div>


        <!-- KOL -->
        <div class="block-k box-kol-section">
            <h5 class="d-flex justify-content-between align-items-center">
                <span><a href="#">Top KOL</a></span>
                <span class="c-note"><i class="fas fa-chart-line"></i> Được theo dõi nhiều nhất</span>
            </h5>

            <!-- Slider -->
            <div class="owl-carousel kol-carousel">
                <?php if (!empty($topKOLs)): ?>
                    <?php foreach ($topKOLs as $kol): ?>

                        <div class="item">
                            <div class="card text-center shadow-sm kol-card">
                                <img src="<?= htmlspecialchars($kol['avatar_url'] ?? 'https://via.placeholder.com/150', ENT_QUOTES, 'UTF-8') ?>"
                                    alt="<?= htmlspecialchars(($kol['name'] ?? '') ?: ($kol['username'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                                    class="card-img-top rounded-circle mx-auto mt-4"
                                    style="width:70px;height:70px;object-fit:cover;">
                                <div class="card-body">
                                    <h6 class="card-title mb-1">
                                        <?= htmlspecialchars(($kol['name'] ?? '') ?: ($kol['username'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
                                    </h6>
                                    <p class="text-muted small mb-2">@<?= htmlspecialchars($kol['username'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
                                    <div class="folloewrs"><i class="fas fa-user-friends"></i> <?= (int)($kol['followers'] ?? 0) ?> follower</div>
                                    <div class="likes"><i class="fas fa-thumbs-up"></i> <?= (int)($kol['likes'] ?? 0) ?> lượt thích</div>
                                    <a href="<?= BASE_URL ?>/view_profile?id=<?= urlencode($kol['user_id']) ?>" class="btn btn-sm btn-outline-primary mt-2">Xem thêm</a>
                                </div>
                            </div>
                        </div>

                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-muted">Chưa có KOL nào.</p>
                <?php endif; ?>
            </div>
        </div>
        <!-- END KOL -->

        <!-- ///////////////////////////// -->


        <!-- blog -->
        <?php

        //LẤY TRONG CSDL
        // Function to calculate time ago
        require_once __DIR__ . '/../../time.php';
        ?>

        <?php if (!empty($articlesInitial)): ?>
            <?php
            // Lấy ID người dùng hiện tại để so sánh trong vòng lặp
            $currentUserIdForView = $_SESSION['user']['id'] ?? null;
            ?>
            <!-- Bọc danh sách bài viết -->
            <div id="articles-list">
                <?php foreach ($articlesInitial as $i => $article): ?>
                    <div class="block-k article-item">
                        <div class="view-carde f-frame">
                            <div class="provider">
                                <?php
                                $authorAvatar = $article['avatar_url'] ?? 'https://i.pinimg.com/1200x/83/0e/ea/830eea38f7a5d3d8e390ba560d14f39c.jpg';
                                ?>
                                <img class="logo" alt="" src="<?= htmlspecialchars($authorAvatar) ?>">
                                <div class="p-covers">
                                    <span class="name">
                                        <a href="<?= BASE_URL ?>/view_profile?id=<?= $article['author_id'] ?>">
                                            <?= htmlspecialchars($article['author_name']) ?>
                                        </a>
                                    </span>
                                    <span class="date"><?= timeAgo($article['created_at']) ?></span>
                                </div>
                            </div>

                            <?php
                            // LOGIC MỚI: Kiểm tra author_id và dùng cột status gốc
                            if ($currentUserIdForView && $article['author_id'] == $currentUserIdForView) {
                                $status = $article['status']; // Sử dụng cột status từ DB
                                $badgeClass = '';
                                $badgeText = '';

                                switch ($status) {
                                    case 'pending':
                                        $badgeClass = 'bg-warning text-dark';
                                        $badgeText = 'Chờ duyệt';
                                        break;
                                    case 'public':
                                        $badgeClass = 'bg-success';
                                        $badgeText = 'Công khai';
                                        break;
                                        // Bạn có thể thêm các trường hợp khác như 'private', 'draft' ở đây
                                }

                                if ($badgeText) {
                                    echo '<div class="article-status-badge" style="margin-bottom: 8px; margin-top: 5px;">';
                                    echo '<span class="badge ' . $badgeClass . '">' . htmlspecialchars($badgeText) . '</span>';
                                    echo '</div>';
                                }
                            }
                            ?>

                            <div class="title">

                                <a href="<?= BASE_URL . '/details_blog/' . $article['slug'] ?>"
                                    target="_self">

                                    <?= htmlspecialchars($article['title']) ?>
                                </a>
                            </div>

                            <div class="sapo">
                                <?= htmlspecialchars($article['summary']) ?>
                                <a href="<?= 'details_blog/' . $article['slug'] ?>"
                                    class="d-more" target="_self">
                                    Xem thêm
                                </a>
                            </div>

                            <?php if (!empty($article['main_image_url'])) : ?>
                                <img class="h-img" src="<?= htmlspecialchars($article['main_image_url']) ?>"
                                    alt="<?= htmlspecialchars($article['title']) ?>">
                            <?php endif; ?>

                            

                            <!-- Giữ nguyên phần like, comment, share -->
                            <div class="item-bottom">


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
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Loading hiển thị khi đang load thêm -->
            <div id="loading" style="text-align:center; display:none; margin:20px;">
                <em>Đang tải thêm...</em>
            </div>
            <!-- Nút tải thêm cho mobile -->
            <div id="load-more-container" class="text-center" style="display: none; margin: 20px;">
                <button id="load-more-btn" class="btn btn-primary">Xem thêm</button>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const currentUserId = <?= json_encode($_SESSION['user']['id'] ?? null) ?>;

                    function timeAgo(datetime) {
                        if (!datetime) return '';
                        const time = (new Date().getTime() / 1000) - (new Date(new Date(datetime)).getTime() / 1000);
                        if (time < 60) return 'vừa xong';
                        if (time < 3600) return Math.floor(time / 60) + ' phút trước';
                        if (time < 86400) return Math.floor(time / 3600) + ' giờ trước';
                        if (time < 2592000) return Math.floor(time / 86400) + ' ngày trước';
                        const date = new Date(datetime);
                        const day = String(date.getDate()).padStart(2, '0');
                        const month = String(date.getMonth() + 1).padStart(2, '0');
                        const year = date.getFullYear();
                        return `${day}/${month}/${year}`;
                    }

                    function renderHomepageArticle(article) {
                        const div = document.createElement('div');
                        div.className = 'block-k article-item';
                        const articleLink = article.is_rss ? article.link : `details_blog/${article.slug}`;
                        const target = article.is_rss ? '_blank' : '_self';

                        let statusBadgeHtml = '';
                        if (currentUserId && article.author_id == currentUserId && article.status) {
                            let badgeClass = '';
                            let badgeText = '';
                            switch (article.status) {
                                case 'pending':
                                    badgeClass = 'bg-warning text-dark';
                                    badgeText = 'Chờ duyệt';
                                    break;
                                case 'public':
                                    badgeClass = 'bg-success';
                                    badgeText = 'Công khai';
                                    break;
                            }
                            if (badgeText) {
                                statusBadgeHtml = `<div class="article-status-badge" style="margin-bottom: 8px; margin-top: 5px;"><span class="badge ${badgeClass}">${badgeText}</span></div>`;
                            }
                        }

                        div.innerHTML = `
                        <div class="view-carde f-frame">
                            <div class="provider">
                                <img class="logo" alt="Avatar" src="${article.avatar_url || 'https://i.pinimg.com/1200x/83/0e/ea/830eea38f7a5d3d8e390ba560d14f39c.jpg'}">
                                <div class="p-covers">
                                    <span class="name"><a href="/DFF.VN/view_profile?id=${article.author_id}">${article.author_name || ''}</a></span>
                                    <span class="date">${timeAgo(article.created_at)}</span>
                                </div>
                            </div>
                            ${statusBadgeHtml}
                            <div class="title">
                                <a href="${articleLink}" target="${target}">${article.title || ''}</a>
                            </div>
                            <div class="sapo">
                                ${article.summary || ''}
                                <a href="${articleLink}" class="d-more" target="${target}">Xem thêm</a>
                            </div>
                            ${article.main_image_url ? `<img class="h-img" src="${article.main_image_url}" alt="${article.title || ''}">` : ''}
                            <div class="item-bottom">

                                <div class="button-ar">
                                    <div class="dropdown home-item">
                                        <span class="dropdown-toggle" data-bs-toggle="dropdown">Chia sẻ</span>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item copylink" data-url="${article.is_rss ? article.link : '<?= BASE_URL ?>/details_blog/' + article.slug}" href="javascript:void(0)">Copy link</a></li>
                                            <li><a class="dropdown-item sharefb" data-url="${article.is_rss ? article.link : '<?= BASE_URL ?>/details_blog/' + article.slug}" href="javascript:void(0)">Share FB</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>`;
                        return div;
                    }

                    setupInfiniteScroll({
                        listElementId: 'articles-list',
                        loadingElementId: 'loading',
                        loadMoreContainerId: 'load-more-container',
                        loadMoreBtnId: 'load-more-btn',
                        apiUrl: '<?= BASE_URL ?>/api/loadMoreArticles',
                        initialOffset: 5,
                        limit: 5,
                        renderItemFunction: renderHomepageArticle
                    });

                    // JS for Share & Copy Link
                    document.addEventListener('click', function(event) {
                        const target = event.target;

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
                    });
                });
            </script>
        <?php else: ?>
            <div class="block-k ">
                <div class="view-carde f-frame">
                    <div class="text-center p-4">
                        <p>Chưa có bài viết nào trong cơ sở dữ liệu.</p>
                    </div>
                </div>
            </div>
        <?php endif; ?>






        <script>
            //// Đừng có xóa dòng này mấy cha
            document.querySelectorAll(".btn-follow").forEach(btn => {
                btn.addEventListener("click", function() {
                    const userId = this.getAttribute("data-user");
                    const token = "<?= htmlspecialchars($_SESSION['user']['session_token'] ?? '') ?>";

                    fetch("api/follow", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/x-www-form-urlencoded"
                            },
                            body: `user_id=${encodeURIComponent(userId)}&session_token=${encodeURIComponent(token)}`,
                            credentials: "include"
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                // cập nhật text nút
                                this.querySelector(".follow-text").innerText =
                                    data.action === "follow" ? "Đang theo dõi" : "Theo dõi";

                                // cập nhật số follower
                                this.querySelector(".number").innerText = data.followers;
                            } else {
                                alert(data.message);
                            }
                        })
                        .catch(err => {
                            console.error(err);
                            alert("Không thể kết nối đến server!");
                        });
                });
            });
        </script>



    </div>

    <!-- bài viết chính block end -->


    <div class="content-right">


        <div class="content-right">
            <div class="block-k cover-chat ">
                <h5 class="bg-success"><i class="fas fa-comments"></i> Hi! TMI - DEV K25</h5>
                <ul class="list_comment">
                    <?php foreach ($comments as $c): ?>
                        <li class="chat-item <?= ($c['ai_checked'] && $c['ai_violation']) ? 'violation' : '' ?>" data-id="<?= $c['id'] ?>">
                            <div class="chat-avatar">
                                <?php if ($c['avatar_url']): ?>
                                    <img src="<?= htmlspecialchars($c['avatar_url']) ?>">
                                <?php else: ?>
                                    <span class="avatar-fallback"><?= strtoupper(substr($c['username'], 0, 1)) ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="chat-body" data-comment-id="<?= (int) $c['id'] ?>"
                                data-username="<?= htmlspecialchars($c['username']) ?>">
                                <div class="chat-meta">
                                    <span class="chat-name"><?= htmlspecialchars($c['username']) ?></span>
                                    <span class="chat-time"><?= timeAgo($c['created_at']) ?></span>
                                </div>
                                <div class="chat-content">
                                    <?= nl2br(preg_replace('/@(\w+)/u', '<span style="color: #007bff; font-weight: bold;">@$1</span>', htmlspecialchars($c['content']))) ?>
                                </div>

                                <?php if ($c['ai_checked'] && $c['ai_violation']): ?>
                                    <div class="ai-violation-warning">⚠️ Bạn đã vi phạm quy tắc cộng đồng</div>
                                <?php endif; ?>

                                <div class="chat-actions">
                                    <button>⬆</button>
                                    <span class="vote-count"><?= (int) $c['upvotes'] ?></span>
                                    <button>⬇</button>
                                    <a href="#" class="chat-reply">Trả lời</a>
                                </div>
                            </div>
                            <input type="hidden" id="parent_id" name="parent_id" value="">

                            <script>
                                document.addEventListener('click', function(e) {
                                    if (e.target.classList.contains('chat-reply')) {
                                        e.preventDefault();

                                        const chatBody = e.target.closest('.chat-body');
                                        const parentId = chatBody.dataset.commentId;
                                        const username = chatBody.dataset.username;

                                        // Gán id comment cha
                                        document.getElementById('parent_id').value = parentId;

                                        // Chèn @username (nếu muốn)
                                        const textarea = document.getElementById('comment-content');
                                        const formattedUsername = username.replace(/\s/g, '');

                                        if (!textarea.value.startsWith('@' + formattedUsername)) {
                                            // Sử dụng tên người dùng đã được định dạng
                                            textarea.value = '@' + formattedUsername + ' ' + textarea.value;
                                        }

                                        // Cuộn tới ô nhập và focus
                                        textarea.scrollIntoView({
                                            behavior: 'smooth',
                                            block: 'center'
                                        });
                                        textarea.focus();
                                    }
                                });
                            </script>








                            <script>
                                document.addEventListener('click', function(e) {
                                    if (e.target.classList.contains('chat-reply')) {
                                        e.preventDefault();

                                        // Tìm khối comment chứa nút này
                                        const chatBody = e.target.closest('.chat-body');
                                        const parentId = chatBody.dataset.commentId;
                                        const username = chatBody.dataset.username;

                                        // Gán vào hidden input & chèn @username vào đầu ô nhập
                                        document.getElementById('parent_id').value = parentId;

                                        const box = document.getElementById('comment-box');
                                        box.focus();
                                        // Nếu chưa có @username ở đầu thì thêm
                                        if (!box.value.startsWith('@' + username)) {
                                            box.value = '@' + username + ' ' + box.value;
                                        }
                                    }
                                });
                            </script>







                        </li>
                    <?php endforeach; ?>
                </ul>
                <div class="h-comment">
                    <textarea id="comment-content" placeholder="Viết bình luận"></textarea>
                    <i class="fas fa-paper-plane" id="send-comment" style="cursor:pointer"></i>
                </div>
            </div>
        </div>


        <style>
            /* AI Violation Warning Styles */
            .ai-violation-warning {
                background: linear-gradient(135deg, #fff3cd, #ffeaa7);
                border: 2px solid #f39c12;
                border-radius: 8px;
                padding: 8px 12px;
                margin: 8px 0;
                color: #856404;
                font-weight: 600;
                font-size: 14px;
                text-align: center;
                box-shadow: 0 2px 4px rgba(243, 156, 18, 0.2);
                animation: pulse 2s infinite;
            }

            @keyframes pulse {
                0% { transform: scale(1); }
                50% { transform: scale(1.02); }
                100% { transform: scale(1); }
            }

            /* Comment với vi phạm */
            .chat-item.violation {
                border-left: 4px solid #f39c12;
                background: linear-gradient(90deg, rgba(243, 156, 18, 0.05), transparent);
            }

            /* Comment đang được check - Ẩn trạng thái checking */
            .chat-item.checking {
                /* Không hiển thị gì đặc biệt khi đang check */
            }

            .chat-item.checking::after {
                /* Ẩn hoàn toàn phần "Đang kiểm tra..." */
                display: none;
            }
        </style>

        <script>
            let lastId = <?= !empty($comments) ? max(array_column($comments, 'id')) : 0 ?>;

            // Render comment (cũ - không dùng nữa)
            function createCommentElement(c) {
                // Redirect to new function
                return createCommentElementWithAI(c);
            }
            // Gửi comment với AI check
            document.getElementById("send-comment").addEventListener("click", () => {
                const textarea = document.getElementById("comment-content");
                const content = textarea.value.trim();
                
                // Kiểm tra user có login không
                const userId = <?= (int)($_SESSION['user']['id'] ?? 0) ?>;
                if (userId <= 0) {
                    alert("Vui lòng đăng nhập để gửi bình luận!");
                    return;
                }
                
                if (!content) {
                    alert("Vui lòng nhập nội dung bình luận!");
                    return;
                }
                
                console.log("🚀 User ID:", userId, "Content:", content);

                // Tạo temp ID cho comment
                const tempId = "temp-" + Date.now();
                
                // Tạo comment element tạm thời
                const tempComment = {
                    id: tempId,
                    username: "<?= htmlspecialchars($_SESSION['user']['name'] ?? 'User') ?>",
                    avatar_url: "<?= htmlspecialchars($_SESSION['user']['avatar_url'] ?? '') ?>",
                    content: content,
                    time_ago: "Vừa xong",
                    upvotes: 0,
                    ai: { isChecking: true }
                };

                console.log("🔍 Creating temp comment:", tempComment);

                // Hiển thị comment tạm thời
                const ul = document.querySelector(".list_comment");
                if (!ul) {
                    console.error("❌ Không tìm thấy .list_comment");
                    return;
                }
                
                const li = createCommentElementWithAI(tempComment);
                console.log("🔍 Created temp element:", li);
                
                ul.prepend(li);
                ul.scrollTop = 0;
                
                console.log("✅ Temp comment displayed");

                // Gửi comment với AI check
                sendCommentWithAI(content, tempId, userId);
                
                // Clear textarea
                textarea.value = "";
            });

            // nhấn enter 
            const textarea = document.getElementById("comment-content");

            textarea.addEventListener("keydown", function(e) {
                if (e.key === "Enter" && !e.shiftKey) {
                    e.preventDefault(); // chặn xuống dòng
                    document.getElementById("send-comment").click(); // gọi nút gửi
                }
            });

            // Load comment mới
            function loadNewComments() {
                fetch("<?= BASE_URL ?>/controller/CommentsGlobalController.php?action=getComments&last_id=" + lastId + "&_=" + new Date().getTime())
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === "success") {
                            console.log("📥 Loaded new comments:", data.comments.length);
                            const ul = document.querySelector(".list_comment");
                            data.comments.forEach(c => {
                                // Kiểm tra comment đã tồn tại chưa (bao gồm cả temp comment)
                                const existingElement = document.querySelector(`.chat-item[data-id="${c.id}"]`);
                                const tempElement = document.querySelector(`.chat-item[data-id*="temp-"]`);
                                
                                if (!existingElement && !tempElement) {
                                    console.log("🆕 New comment found:", c.id, c.content);
                                    
                                    // Thêm thông tin AI vào comment
                                    if (c.ai_checked) {
                                        c.ai = {
                                            isViolation: c.ai_violation == 1,
                                            isChecking: false,
                                            details: c.ai_details
                                        };
                                        console.log("🤖 AI info added:", c.ai);
                                    }
                                    
                                    const li = createCommentElementWithAI(c);

                                    // ✅ cũng append lên đầuđầu
                                    ul.prepend(li);

                                    // ✅ scroll xuống  lên đàu khi có comment mới
                                    ul.scrollTop = 0;

                                    if (c.id > lastId) lastId = c.id;
                                } else {
                                    console.log("⏭️ Comment already exists or temp comment present:", c.id);
                                }
                            });
                        } else {
                            console.log("❌ Failed to load comments:", data);
                        }
                    })
                    .catch(error => {
                        console.error("❌ Error loading comments:", error);
                    });
            }

            // Auto refresh (tạm thời tắt để tránh duplicate)
            // setInterval(loadNewComments, 2000);

            // ========== AI CHECK FUNCTIONS ==========
            
            // Function gửi comment với AI check
            async function sendCommentWithAI(content, tempId, userId) {
                try {
                    console.log("🚀 Sending comment to server:", content, "User ID:", userId);
                    
                    // 1. Gửi comment vào database
                    const res = await fetch("<?= BASE_URL ?>/controller/CommentsGlobalController.php?action=addComment", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded"
                        },
                        body: "user_id=" + encodeURIComponent(userId) + 
                              "&content=" + encodeURIComponent(content)
                    });
                    
                    console.log("📡 Server response status:", res.status);
                    
                    const data = await res.json();
                    console.log("📡 Server response data:", data);
                    
                    if (data.status === "success") {
                        console.log("✅ Comment saved to database. ID:", data.comment_id);
                        
                        // Thay thế temp comment bằng real comment
                        const tempElement = document.querySelector(`[data-id="${tempId}"]`);
                        if (tempElement) {
                            tempElement.dataset.id = data.comment_id;
                            console.log("🔄 Replaced temp comment with real ID:", data.comment_id);
                        }
                        
                        // 2. AI check comment
                        checkCommentWithAI(tempId, content, data.comment_id);
                        
                        if (data.comment_id > lastId) lastId = data.comment_id;
                    } else {
                        console.error("❌ Lỗi khi thêm comment:", data.message);
                        // Xóa comment tạm nếu lỗi
                        const tempElement = document.querySelector(`[data-id="${tempId}"]`);
                        if (tempElement) tempElement.remove();
                    }
                } catch (error) {
                    console.error("❌ Lỗi gửi comment:", error);
                    // Xóa comment tạm nếu lỗi
                    const tempElement = document.querySelector(`[data-id="${tempId}"]`);
                    if (tempElement) tempElement.remove();
                }
            }

            // Function AI check comment
            async function checkCommentWithAI(tempId, content, commentId) {
                try {
                    console.log("🔍 Bắt đầu AI check cho comment:", content);
                    
                    const response = await fetch("<?= BASE_URL ?>/checkCmt/check_comment.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            content: content
                        })
                    });

                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }

                    const result = await response.json();
                    console.log("🤖 AI check result:", result);

                    // Cập nhật UI với kết quả AI
                    updateCommentWithAIResult(tempId, result);
                    
                    // Lưu kết quả AI vào database
                    saveAIResultToDatabase(commentId, result);

                } catch (error) {
                    console.error("❌ Lỗi AI check:", error);
                    // Xóa trạng thái checking nếu có lỗi
                    const tempElement = document.querySelector(`[data-id="${tempId}"]`);
                    if (tempElement) {
                        tempElement.classList.remove("checking");
                    }
                }
            }

            // Function cập nhật UI với kết quả AI
            function updateCommentWithAIResult(tempId, aiResult) {
                console.log("🔄 Updating UI with AI result for tempId:", tempId);
                
                // Tìm element bằng tempId
                let element = document.querySelector(`[data-id="${tempId}"]`);
                if (!element) {
                    console.warn("❌ Không tìm thấy element với tempId:", tempId);
                    // Thử tìm bằng cách khác - tìm comment mới nhất
                    const allComments = document.querySelectorAll('.chat-item');
                    if (allComments.length > 0) {
                        element = allComments[0]; // Lấy comment đầu tiên (mới nhất)
                        console.log("🔄 Using latest comment element instead");
                    }
                }
                
                if (!element) {
                    console.error("❌ Không thể tìm thấy element để cập nhật");
                    return;
                }

                // Xóa class checking
                element.classList.remove("checking");

                // Debug AI result
                console.log("🤖 AI Result:", aiResult);
                console.log("🤖 isViolation:", aiResult.isViolation);
                
                // Nếu có vi phạm, thêm cảnh báo
                if (aiResult.isViolation) {
                    console.log("🚨 VIOLATION DETECTED - Adding warning to UI");
                    element.classList.add("violation");
                    
                    // Thêm message cảnh báo
                    const chatBody = element.querySelector(".chat-body");
                    if (chatBody) {
                        // Kiểm tra xem đã có cảnh báo chưa
                        if (!chatBody.querySelector(".ai-violation-warning")) {
                            const warningDiv = document.createElement("div");
                            warningDiv.className = "ai-violation-warning";
                            warningDiv.innerHTML = "⚠️ Bạn đã vi phạm quy tắc cộng đồng";
                            chatBody.appendChild(warningDiv);
                            console.log("✅ Violation warning added to UI");
                        } else {
                            console.log("⚠️ Violation warning already exists");
                        }
                    } else {
                        console.error("❌ Cannot find chat-body to add warning");
                    }
                    
                    console.log("🚨 VIOLATION DETECTED - Auto-updating database");
                    console.log("🚨 Violation details:", aiResult);
                } else {
                    console.log("✅ Comment is safe, no violation detected");
                }

                console.log("✅ AI result applied to UI");
            }

            // Function lưu kết quả AI vào database
            async function saveAIResultToDatabase(commentId, aiResult) {
                try {
                    const response = await fetch("<?= BASE_URL ?>/controller/updateAIresultGlobalController.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            comment_id: commentId,
                            isViolation: aiResult.isViolation,
                            aiDetails: aiResult
                        })
                    });

                    if (response.ok) {
                        console.log("💾 AI result saved to database for comment:", commentId);
                        if (aiResult.isViolation) {
                            console.log("🚨 User will see violation warning in UI");
                        }
                        
                        // Không cần load comment mới vì đã cập nhật UI trực tiếp
                        // setTimeout(() => {
                        //     loadNewComments();
                        // }, 1000);
                    } else {
                        console.error("❌ Failed to save AI result to database");
                    }
                } catch (error) {
                    console.error("❌ Error saving AI result:", error);
                }
            }

            // Function tạo comment element với AI info
            function createCommentElementWithAI(c) {
                const li = document.createElement("li");
                li.className = "chat-item";
                li.dataset.id = c.id;
                
                // Thêm class dựa trên AI result
                if (c.ai) {
                    if (c.ai.isChecking) {
                        li.classList.add("checking");
                    } else if (c.ai.isViolation) {
                        li.classList.add("violation");
                    }
                }
                
                let aiWarning = "";
                if (c.ai && c.ai.isViolation && !c.ai.isChecking) {
                    aiWarning = '<div class="ai-violation-warning">⚠️ Bạn đã vi phạm quy tắc cộng đồng</div>';
                }
                
                li.innerHTML = `
        <div class="chat-avatar">
            ${c.avatar_url
                        ? `<img src="${c.avatar_url}">`
                        : `<span class="avatar-fallback">${c.username ? c.username[0].toUpperCase() : '#'}</span>`}
        </div>
        <div class="chat-body" data-comment-id="${c.id}" data-username="${c.username}">
            <div class="chat-meta">
                <span class="chat-name">${c.username}</span>
                <span class="chat-time">${c.time_ago}</span>
            </div>
            <div class="chat-content">${c.content.replace(/@([\p{L}\p{N}_]+)/gu, '<span style="color: #007bff; font-weight: bold;">@$1</span>')}</div>
            ${aiWarning}
            <div class="chat-actions">
                <button>⬆</button>
                <span class="vote-count">${c.upvotes || 0}</span>
                <button>⬇</button>
                <a href="#" class="chat-reply">Trả lời</a>
            </div>
        </div>`;
                return li;
            }
        </script>





        <?php
        // Giả sử $topArticles chứa 6 bài viết HOT đã lấy từ database
        // $topArticles = ArticlesModel::getTopArticles(6);
        ?>

        <?php if (!empty($rssArticles3)): ?>
            <div class="block-k bg-box-a">
                <div class="tieu-diem">
                    <h2>
                        <i class="fab fa-hotjar"></i> TMI <span>HOT</span>
                    </h2>
                    <ul>
                        <?php foreach ($rssArticles3 as $article): ?>
                            <li class="new-style">
                                <a title="<?= htmlspecialchars($article['title']) ?>" href="<?= !empty($article['is_rss'])
                                                                                                ? htmlspecialchars($article['link'])
                                                                                                : 'details_blog/' . urlencode($article['slug']) ?>">
                                    <?= htmlspecialchars($article['title']) ?>
                                </a>

                                <?php if (!empty($article['main_image_url'])): ?>
                                    <img src="<?= htmlspecialchars($article['main_image_url']) ?>"
                                        title="<?= htmlspecialchars($article['title']) ?>"
                                        alt="<?= htmlspecialchars($article['title']) ?>" border="0" />
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php else: ?>
            <div class="block-k">
                <div class="view-carde f-frame">
                    <div class="text-center p-4">
                        <p>Chưa có bài viết nổi bật nào.</p>
                    </div>
                </div>
            </div>
        <?php endif; ?>










        <?php if (!empty($rssArticles4)): ?>
            <div class="block-k bg-box-a">
                <div class="tieu-diem t-analysis">
                    <h2>
                        <i class="fas fa-search-dollar"></i> MXH <span>ANALYSIS</span>
                    </h2>
                    <ul>
                        <?php foreach ($rssArticles4 as $article): ?>
                            <li class="new-style">
                                <a title="<?= htmlspecialchars($article['title']) ?>" href="<?= !empty($article['is_rss'])
                                                                                                ? htmlspecialchars($article['link'])
                                                                                                : 'details_blog/' . urlencode($article['slug']) ?>">
                                    <?= htmlspecialchars($article['title']) ?>
                                </a>

                                <?php if (!empty($article['main_image_url'])): ?>
                                    <img src="<?= htmlspecialchars($article['main_image_url']) ?>"
                                        title="<?= htmlspecialchars($article['title']) ?>"
                                        alt="<?= htmlspecialchars($article['title']) ?>" border="0" />
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php else: ?>
            <div class="block-k">
                <div class="view-carde f-frame">
                    <div class="text-center p-4">
                        <p>Chưa có bài viết phân tích nào.</p>
                    </div>
                </div>
            </div>
        <?php endif; ?>





        <a target="_blank" href="<?= BASE_URL ?>/crypton">
            <img src="<?= BASE_URL ?>/public/img/crypto.png" alt="Crypto" style="width:100px; height:auto;">
        </a>



        <div class="block-k bg-box-a">
            <div class="box-follow"></div>
        </div>



        <script>
            $(function() {
                var height = $(".content-right").outerHeight() + 600;
                $(window).scroll(function() {
                    var rangeToTop = $(this).scrollTop();
                    if (rangeToTop > height) {
                        $(".cover-chat").css("position", "fixed").css("top", "118px");
                    } else {
                        $(".cover-chat").css("position", "relative").css("top", "0");
                    }
                });

                //Page.flSuggest();


            });
        </script>
        <script>
            $(document).ready(function() {
                $('.owl-carousel.box-company').owlCarousel({
                    loop: false,
                    margin: 10,
                    nav: true,
                    dots: true,
                    navText: [
                        '<i class="fa fa-chevron-left"></i>',
                        '<i class="fa fa-chevron-right"></i>'
                    ],
                    responsive: {
                        0: {
                            items: 1
                        },
                        600: {
                            items: 3
                        },
                        1000: {
                            items: 3
                        }
                    }
                });
            });

            /*================ KOL =================*/

            $('.owl-carousel.kol-carousel').owlCarousel({
                loop: false,
                margin: 20,
                nav: true,
                dots: false,
                navText: [
                    '<i class="fa fa-chevron-left"></i>',
                    '<i class="fa fa-chevron-right"></i>'
                ],
                responsive: {
                    0: {
                        items: 1
                    },
                    500: {
                        items: 2
                    },
                    1000: {
                        items: 3
                    }
                }
            });

            /*================ End KOL =================*/
        </script>




    </div>


    <!-- Modal for creating a new post -->
    <div class="modal-dialog modal-lg modal-dialog-scrollable" style="margin:10px auto;">

        <!-- them -->
        <div class="modal-content shadow-lg border-0 rounded-3 mb-4">


</main>