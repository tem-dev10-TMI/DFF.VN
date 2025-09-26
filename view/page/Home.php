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
                        <div class="cover-hover">
                            <?php if (!empty($article['main_image_url'])): ?>
                                <img src="<?= htmlspecialchars($article['main_image_url']) ?>"
                                    title="<?= htmlspecialchars($article['title']) ?>"
                                    alt="<?= htmlspecialchars($article['title']) ?>" border="0" />
                            <?php else: ?>
                                <div class="mmavatar">
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
                    <?php
                    // Lọc comments: ẩn comment vi phạm khỏi các user khác
                    $currentUserId = $_SESSION['user']['id'] ?? 0;

                    $filteredComments = array_filter($comments, function ($c) use ($currentUserId) {
                        // Nếu comment có vi phạm
                        if ($c['ai_checked'] && $c['ai_violation']) {
                            // Chỉ hiển thị cho user đã viết comment đó
                            return $c['user_id'] == $currentUserId;
                        }

                        // Nếu comment chưa được AI check, chỉ hiển thị cho user đã viết comment đó
                        if (!$c['ai_checked']) {
                            return $c['user_id'] == $currentUserId;
                        }

                        // Comment đã được AI check và không vi phạm - hiển thị cho tất cả
                        return true;
                    });

                    foreach ($filteredComments as $c):
                        // Thay nội dung comment bằng cảnh báo vi phạm
                        $commentContent = nl2br(preg_replace('/@(\w+)/u', '<span style="color: #007bff; font-weight: bold;">@$1</span>', htmlspecialchars($c['content'])));
                        $deleteButton = '';

                        if ($c['ai_checked'] && $c['ai_violation']) {
                            // Thay nội dung comment bằng cảnh báo vi phạm
                            $commentContent = '<div class="ai-violation-warning" style="background: #fff3cd; border: 1px solid #ffeaa7; border-radius: 4px; padding: 8px; margin: 4px 0; font-size: 13px; color: #856404; font-style: italic;">⚠️ Bạn đã vi phạm quy tắc cộng đồng</div>';

                            // Thêm nút xóa cho comment vi phạm
                            $deleteButton = '<button class="delete-violation-btn" onclick="deleteViolationComment(' . $c['id'] . ')"><i class="fas fa-trash"></i> Xóa</button>';
                        }
                    ?>
                        <li class="chat-item <?= ($c['ai_checked'] && $c['ai_violation']) ? 'violation' : '' ?>" data-id="<?= $c['id'] ?>" data-comment-id="<?= $c['id'] ?>">
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
                                    <?= $commentContent ?>
                                </div>

                                <div class="chat-actions">
                                    <button>⬆</button>
                                    <span class="vote-count"><?= (int) $c['upvotes'] ?></span>
                                    <button>⬇</button>
                                    <a href="#" class="chat-reply">Trả lời</a>
                                    <?= $deleteButton ?>
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
                0% {
                    transform: scale(1);
                }

                50% {
                    transform: scale(1.02);
                }

                100% {
                    transform: scale(1);
                }
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

            .chat-actions {
                display: flex;
                align-items: center;
                gap: 8px;
                flex-wrap: wrap;
            }

            /* Ẩn nút xóa mặc định, chỉ hiện khi hover */
            .chat-item .delete-violation-btn {
                opacity: 0;
                transition: all 0.3s ease;
            }

            .chat-item:hover .delete-violation-btn {
                opacity: 0.9;
            }

            .chat-item.violation .delete-violation-btn {
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
                    user_id: userId,
                    ai_checked: false, // Chưa được AI check
                    ai: {
                        isChecking: true
                    }
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

            // Load comment mới - chỉ load comment của người khác đã được AI check và không vi phạm
            function loadNewComments() {
                const currentUserId = <?= (int)($_SESSION['user']['id'] ?? 0) ?>;

                fetch("<?= BASE_URL ?>/controller/CommentsGlobalController.php?action=getComments&last_id=" + lastId + "&_=" + new Date().getTime())
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === "success") {
                            console.log("📥 Loaded new comments:", data.comments.length);
                            const ul = document.querySelector(".list_comment");
                            let hasNewComments = false;

                            data.comments.forEach(c => {
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
                                const existingElement = document.querySelector(`.chat-item[data-id="${c.id}"]`);

                                if (!existingElement) {
                                    console.log("🆕 New SAFE comment from others:", c.id, c.content);

                                    // Thêm thông tin AI vào comment
                                    c.ai = {
                                        isViolation: false,
                                        isChecking: false,
                                        details: c.ai_details
                                    };

                                    // Thêm user_id để kiểm tra quyền
                                    c.user_id = c.user_id || null;

                                    const li = createCommentElementWithAI(c);

                                    // Thêm comment mới vào đầu danh sách
                                    ul.prepend(li);
                                    hasNewComments = true;

                                    if (c.id > lastId) lastId = c.id;
                                } else {
                                    console.log("⏭️ Comment already exists:", c.id);
                                }
                            });

                            // Chỉ scroll lên đầu nếu có comment mới
                            if (hasNewComments) {
                                ul.scrollTop = 0;
                            }
                        } else {
                            console.log("❌ Failed to load comments:", data);
                        }
                    })
                    .catch(error => {
                        console.error("❌ Error loading comments:", error);
                    });
            }

            // Auto refresh - chỉ load comment mới của người khác
            setInterval(loadNewComments, 3000);

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
                            comment: content
                        })
                    });

                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }

                    const result = await response.json();
                    console.log("🤖 AI check result:", result);

                    // Extract actual result from response
                    const aiResult = result.result || result;
                    console.log("🤖 Extracted AI result:", aiResult);

                    // Cập nhật UI với kết quả AI
                    updateCommentWithAIResult(tempId, aiResult);

                    // Lưu kết quả AI vào database
                    saveAIResultToDatabase(commentId, aiResult);

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

                    // Thay nội dung comment bằng cảnh báo vi phạm
                    const chatContent = element.querySelector(".chat-content");
                    if (chatContent) {
                        chatContent.innerHTML = '<div class="ai-violation-warning" style="background: #fff3cd; border: 1px solid #ffeaa7; border-radius: 4px; padding: 8px; margin: 4px 0; font-size: 13px; color: #856404; font-style: italic;">⚠️ Bạn đã vi phạm quy tắc cộng đồng</div>';
                        console.log("✅ Comment content replaced with violation warning");
                    }

                    // Thêm nút xóa
                    const chatActions = element.querySelector(".chat-actions");
                    if (chatActions && !chatActions.querySelector(".delete-violation-btn")) {
                        const deleteBtn = document.createElement("button");
                        deleteBtn.className = "delete-violation-btn";
                        deleteBtn.innerHTML = '<i class="fas fa-trash"></i> Xóa';
                        deleteBtn.onclick = () => deleteViolationComment(element.dataset.id);
                        chatActions.appendChild(deleteBtn);
                        console.log("✅ Delete button added");
                    }

                    console.log("🚨 VIOLATION DETECTED - Comment will be hidden from other users");
                    console.log("🚨 Violation details:", aiResult);
                } else {
                    console.log("✅ Comment is safe, will be visible to all users");
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
                li.dataset.commentId = c.id;

                // Kiểm tra quyền hiển thị comment
                const currentUserId = <?= (int)($_SESSION['user']['id'] ?? 0) ?>;

                // Ẩn comment vi phạm khỏi user khác
                if (c.ai && c.ai.isViolation && !c.ai.isChecking && c.user_id !== currentUserId) {
                    li.style.display = 'none';
                    return li;
                }

                // Ẩn comment chưa được AI check khỏi user khác
                if (!c.ai_checked && c.user_id !== currentUserId) {
                    li.style.display = 'none';
                    return li;
                }

                // Thêm class dựa trên AI result
                if (c.ai) {
                    if (c.ai.isChecking) {
                        li.classList.add("checking");
                    } else if (c.ai.isViolation) {
                        li.classList.add("violation");
                    }
                }

                // Thay nội dung comment bằng cảnh báo vi phạm
                let commentContent = c.content.replace(/@([\p{L}\p{N}_]+)/gu, '<span style="color: #007bff; font-weight: bold;">@$1</span>');
                let deleteButton = '';

                if (c.ai && c.ai.isViolation && !c.ai.isChecking) {
                    // Thay nội dung comment bằng cảnh báo vi phạm
                    commentContent = '<div class="ai-violation-warning" style="background: #fff3cd; border: 1px solid #ffeaa7; border-radius: 4px; padding: 8px; margin: 4px 0; font-size: 13px; color: #856404; font-style: italic;">⚠️ Bạn đã vi phạm quy tắc cộng đồng</div>';

                    // Thêm nút xóa cho comment vi phạm
                    deleteButton = '<button class="delete-violation-btn" onclick="deleteViolationComment(' + c.id + ')"><i class="fas fa-trash"></i> Xóa</button>';
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
            <div class="chat-content">${commentContent}</div>
            <div class="chat-actions">
                <button>⬆</button>
                <span class="vote-count">${c.upvotes || 0}</span>
                <button>⬇</button>
                <a href="#" class="chat-reply">Trả lời</a>
                ${deleteButton}
            </div>
        </div>`;
                return li;
            }

            // Function xóa comment vi phạm
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

                    // Cách 2: Tìm bằng data-id
                    if (!commentElement) {
                        commentElement = document.querySelector(`[data-id="${commentId}"]`);
                    }

                    // Cách 3: Tìm bằng class chat-item và data-id
                    if (!commentElement) {
                        commentElement = document.querySelector(`.chat-item[data-id="${commentId}"]`);
                    }

                    // Cách 4: Tìm bằng onclick attribute
                    if (!commentElement) {
                        const allDeleteButtons = document.querySelectorAll('.delete-violation-btn');
                        for (let btn of allDeleteButtons) {
                            if (btn.onclick && btn.onclick.toString().includes(commentId)) {
                                commentElement = btn.closest('.chat-item') || btn.closest('li');
                                break;
                            }
                        }
                    }

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

                    // Gọi API xóa comment khỏi database
                    console.log('📡 Calling delete API for comment:', commentId);
                    const response = await fetch("<?= BASE_URL ?>/controller/CommentsGlobalController.php?action=deleteComment", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded"
                        },
                        body: "comment_id=" + encodeURIComponent(commentId) +
                            "&user_id=" + encodeURIComponent(<?= (int)($_SESSION['user']['id'] ?? 0) ?>)
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
                    alert('Có lỗi xảy ra khi xóa bình luận: ' + error.message);
                }
            };
        </script>

        <div class="adv block-k">
            <div class="fb-page" data-href="https://www.facebook.com/vientmi" data-tabs="timeline" data-width=""
                data-height="" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false"
                data-show-facepile="true">
                <blockquote cite="https://www.facebook.com/vientmi" class="fb-xfbml-parse-ignore"><a
                        href="https://www.facebook.com/vientmi">TMI - Viện Phát Triển Đào Tạo và Quản Lý </a>
                </blockquote>
            </div>
        </div>




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





        <!-- Crypton Card -->
<a class="crypton-card" href="<?= BASE_URL ?>/crypton" aria-label="Đi đến trang Crypton">
  <img
    class="crypton-card__img"
    src="<?= BASE_URL ?>/public/img/Crypton.png"
    alt="Crypto — đi đến trang Crypton"
    loading="lazy"
    width="1200" height="630"
  />

  <div class="crypton-card__overlay" aria-hidden="true"></div>

  <div class="crypton-card__content">
    <h3 class="crypton-card__title">Crypton</h3>
    <p class="crypton-card__desc">Tỷ giá • Biểu đồ • Tin tức</p>
    <span class="crypton-card__cta">
      Mở trang
      <svg class="crypton-card__icon" width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
        <path d="M5 12h14M13 5l7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    </span>
  </div>
</a>

<style>
  .crypton-card {
    --radius: 18px;
    --shadow: 0 10px 30px rgba(0,0,0,.15);
    --shadow-hover: 0 16px 40px rgba(0,0,0,.22);
    position: relative;
    display: block;
    width: 100%;
    max-width: 980px;
    margin: 16px auto;
    aspect-ratio: 21/9; /* Giữ tỉ lệ đẹp, responsive */
    border-radius: var(--radius);
    overflow: hidden;
    box-shadow: var(--shadow);
    text-decoration: none;
    color: inherit;
    transition: transform .25s ease, box-shadow .25s ease;
    background: #0b1020;
  }
  .crypton-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-hover); }
  .crypton-card:active { transform: translateY(0); }

  .crypton-card:focus-visible {
    outline: 3px solid #70b5ff;
    outline-offset: 4px;
  }

  .crypton-card__img {
    position: absolute; inset: 0;
    width: 100%; height: 100%;
    object-fit: cover;
    filter: saturate(1.05) contrast(1.02);
    transform: scale(1.02);
  }

  .crypton-card__overlay {
    position: absolute; inset: 0;
    background:
      linear-gradient(180deg, rgba(4,6,20,.15) 0%, rgba(4,6,20,.55) 55%, rgba(4,6,20,.85) 100%),
      radial-gradient(120% 120% at 100% 0%, rgba(0,140,255,.35) 0%, rgba(0,140,255,0) 60%);
    pointer-events: none;
  }

  .crypton-card__content {
    position: absolute; left: 24px; right: 24px; bottom: 22px;
    display: grid; gap: 6px;
    color: #eef3ff;
  }

  .crypton-card__title {
    margin: 0;
    font-size: clamp(20px, 3.2vw, 28px);
    font-weight: 700;
    letter-spacing: .2px;
  }

  .crypton-card__desc {
    margin: 0 0 6px 0;
    font-size: clamp(13px, 2vw, 15px);
    opacity: .9;
  }

  .crypton-card__cta {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 10px 14px;
    font-weight: 600;
    border-radius: 999px;
    background: rgba(255,255,255,.1);
    backdrop-filter: blur(4px);
    transition: background .25s ease, gap .25s ease;
    width: fit-content;
  }
  .crypton-card:hover .crypton-card__cta { background: rgba(255,255,255,.18); gap: 10px; }

  .crypton-card__icon { transition: transform .25s ease; }
  .crypton-card:hover .crypton-card__icon { transform: translateX(3px); }

  @media (prefers-reduced-motion: reduce) {
    .crypton-card, .crypton-card__icon { transition: none; }
  }
</style>



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