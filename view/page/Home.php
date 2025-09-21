<?php
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
                                href="<?= !empty($article['is_rss']) ? htmlspecialchars($article['link']) : ('details_blog/' . urlencode($article['slug'])) ?>"
                                target="<?= !empty($article['is_rss']) ? '_blank' : '_self' ?>">
                                <div class="mmavatar"><?= htmlspecialchars($article['title']) ?></div>
                            </a>
                        </div>
                        <div class="cover-hover" style="">
                            <img src="<?= htmlspecialchars($article['main_image_url']) ?>"
                                title="<?= htmlspecialchars($article['title']) ?>"
                                alt="<?= htmlspecialchars($article['title']) ?>" border="0" />
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
            document.querySelector(".openModalcreatePost").addEventListener("click", function () {
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
                                <a href="<?= !empty($article['is_rss']) ? $article['link'] : 'details_blog/' . $article['slug'] ?>"
                                    target="<?= !empty($article['is_rss']) ? '_blank' : '_self' ?>">
                                    <?= htmlspecialchars($article['title']) ?>
                                </a>
                            </div>

                            <div class="sapo">
                                <?= htmlspecialchars($article['summary']) ?>
                                <a href="<?= !empty($article['is_rss']) ? $article['link'] : 'details_blog/' . $article['slug'] ?>"
                                    class="d-more" target="<?= !empty($article['is_rss']) ? '_blank' : '_self' ?>">
                                    Xem thêm
                                </a>
                            </div>

                            <?php if (!empty($article['main_image_url'])): ?>
                                <img class="h-img" src="<?= htmlspecialchars($article['main_image_url']) ?>"
                                    alt="<?= htmlspecialchars($article['title']) ?>">
                            <?php endif; ?>

                            <!-- Giữ nguyên phần like, comment, share -->
                            <div class="item-bottom">
                                <div class="bt-cover com-like" data-id="<?= $article['id'] ?>">
                                    <span class="value"><?= $article['upvotes'] ?? 0 ?></span>
                                </div>
                                <div class="button-ar">
                                    <a href="details_blog?id<?= $article['id'] ?>#anc_comment">
                                        <span><?= $article['comment_count'] ?? 0 ?></span>
                                    </a>
                                </div>
                                <div class="button-ar">
                                    <div class="dropdown home-item">
                                        <span data-bs-toggle="dropdown">Chia sẻ</span>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item copylink"
                                                    data-url="<?= BASE_URL ?>/details_blog/<?= $article['slug'] ?>"
                                                    href="javascript:void(0)">Copy link</a></li>
                                            <li><a class="dropdown-item sharefb"
                                                    data-url="<?= BASE_URL ?>/details_blog/<?= $article['slug'] ?>"
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
                document.addEventListener('DOMContentLoaded', function () {
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
                                case 'pending': badgeClass = 'bg-warning text-dark'; badgeText = 'Chờ duyệt'; break;
                                case 'public': badgeClass = 'bg-success'; badgeText = 'Công khai'; break;
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
                                <div class="bt-cover com-like" data-id="${article.id}">
                                    <span class="value">${article.upvotes || 0}</span>
                                </div>
                                <div class="button-ar">
                                    <a href="details_blog/${article.slug}#anc_comment">
                                        <span>${article.comment_count || 0}</span>
                                    </a>
                                </div>
                                <div class="button-ar">
                                    <div class="dropdown home-item">
                                        <span class="dropdown-toggle" data-bs-toggle="dropdown">Chia sẻ</span>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item copylink" data-url="details_blog/${article.slug}" href="javascript:void(0)">Copy link</a></li>
                                            <li><a class="dropdown-item sharefb" data-url="details_blog/${article.slug}" href="javascript:void(0)">Share FB</a></li>
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
                        apiUrl: 'api/loadMoreArticles',
                        initialOffset: 5,
                        limit: 5,
                        renderItemFunction: renderHomepageArticle
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










    </div>

    <!-- bài viết chính block end -->


    <div class="content-right">


        <div class="content-right">
            <div class="block-k cover-chat">
                <h5><i class="fas fa-comments"></i> Hi! TMI - DEV K25</h5>
                <ul class="list_comment">
                    <?php foreach ($comments as $c): ?>
                        <li class="chat-item" data-id="<?= $c['id'] ?>">
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

                                <div class="chat-actions">
                                    <button>⬆</button>
                                    <span class="vote-count"><?= (int) $c['upvotes'] ?></span>
                                    <button>⬇</button>
                                    <a href="#" class="chat-reply">Trả lời</a>
                                </div>
                            </div>
                            <input type="hidden" id="parent_id" name="parent_id" value="">

                            <script>
                                document.addEventListener('click', function (e) {
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
                                document.addEventListener('click', function (e) {
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


        <script>
            let lastId = <?= !empty($comments) ? max(array_column($comments, 'id')) : 0 ?>;

            // Render comment
            function createCommentElement(c) {
                const li = document.createElement("li");
                li.className = "chat-item";
                li.dataset.id = c.id;
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
            <div class="chat-actions">
                <button>⬆</button>
                <span class="vote-count">${c.upvotes || 0}</span>
                <button>⬇</button>
                <a href="#" class="chat-reply">Trả lời</a>
            </div>
        </div>`;
                return li;
            }
            // Gửi comment
            document.getElementById("send-comment").addEventListener("click", () => {
                const textarea = document.getElementById("comment-content");
                const content = textarea.value.trim();
                if (!content) return;

                fetch("comment_add.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    body: "content=" + encodeURIComponent(content)
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            const ul = document.querySelector(".list_comment");
                            const li = createCommentElement(data.comment);

                            // ✅ thêm xuống cuối
                            // ul.prepend(li);

                            // ✅ auto scroll xuống cuối
                            ul.scrollTop = 0;

                            if (data.comment.id > lastId) lastId = data.comment.id;
                        } else {
                            // Ghi log lỗi nếu có để dễ debug
                            console.error("Lỗi khi thêm comment:", data.error || "Dữ liệu trả về không hợp lệ");
                            alert(data.error || "Có lỗi xảy ra, vui lòng thử lại.");
                        }
                    })
                    .finally(() => textarea.value = "");
            });

            // nhấn enter 
            const textarea = document.getElementById("comment-content");

            textarea.addEventListener("keydown", function (e) {
                if (e.key === "Enter" && !e.shiftKey) {
                    e.preventDefault(); // chặn xuống dòng
                    document.getElementById("send-comment").click(); // gọi nút gửi
                }
            });

            // Load comment mới
            function loadNewComments() {
                fetch("comment_list.php?last_id=" + lastId + "&_=" + new Date().getTime())
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            const ul = document.querySelector(".list_comment");
                            data.comments.forEach(c => {
                                if (!document.querySelector(`.chat-item[data-id="${c.id}"]`)) {
                                    const li = createCommentElement(c);

                                    // ✅ cũng append lên đầuđầu
                                    ul.prepend(li);

                                    // ✅ scroll xuống  lên đàu khi có comment mới
                                    ul.scrollTop = 0;

                                    if (c.id > lastId) lastId = c.id;
                                }
                            });
                        }
                    });
            }

            // Auto refresh
            setInterval(loadNewComments, 2000);
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




        <div class="block-k bg-box-a">
            <div class="view-right-a h-lsk">
                <div class="title">
                    <h3><a href="javascript:void(0)">Lịch sự kiện</a> </h3>
                </div>

                <ol class="content-ol">
                    <?php if (!empty($events)): ?>
                        <?php foreach ($events as $index => $event): ?>
                            <li class="card-list-item" key="<?php echo $index; ?>">
                                <a title="<?= htmlspecialchars($event['title']); ?>"
                                    href="<?= BASE_URL ?>/event?id=<?= $event['id'] ?>">
                                    <?= htmlspecialchars($event['title']); ?>
                                </a>

                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li class="card-list-item">
                            <span>Chưa có sự kiện nào</span>
                        </li>
                    <?php endif; ?>
                </ol>
            </div>
        </div>





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
            $(function () {
                var height = $(".content-right").outerHeight() + 600;
                $(window).scroll(function () {
                    var rangeToTop = $(this).scrollTop();
                    if (rangeToTop > height) {
                        $(".cover-chat").css("position", "fixed").css("top", "118px");
                    } else {
                        $(".cover-chat").css("position", "relative").css("top", "0");
                    }
                });

                Page.flSuggest();


            });
        </script>
        <script>
            $(document).ready(function () {
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
        </script>




    </div>


    <!-- Modal for creating a new post -->

    <!-- Modal: Tạo bài viết mới -->
    

        <div class="modal-dialog modal-lg modal-dialog-scrollable" style="margin:10px auto;">



         <!-- them -->
             <div class="modal-content shadow-lg border-0 rounded-3 mb-4">

                <!-- Header -->
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title fw-bold" id="createPostModalLabel">
                        <i class="fas fa-pencil-alt me-2"></i> Tạo bài viết mới
                    </h5>

                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Đóng"></button>

                </div>

                <!-- Body -->
                <div class="modal-body bg-light">
                    <div class="post-box p-3 rounded-3 bg-white shadow-sm mb-3">

                        <!-- Avatar + tên -->
                        <div class="d-flex align-items-center mb-3">
                            <?php
                                $avatarUrl = $_SESSION['user']['avatar_url'] ?? null;
                                if (!$avatarUrl || trim($avatarUrl) === '') {
                                    $avatarUrl = 'https://i.pinimg.com/1200x/83/0e/ea/830eea38f7a5d3d8e390ba560d14f39c.jpg';
                                }
                                ?>
                                <img src="<?= htmlspecialchars($avatarUrl) ?>"
                                    class="rounded-circle border border-2 border-success me-2" alt="avatar"
                                    style="width: 48px; height: 48px;">
                                <div>
                                    <h6 class="mb-0 fw-bold text-dark">
                                        <?php
                                            echo htmlspecialchars($_SESSION['user']['name'] ?? 'Doanh nhân hoặc người dùng');
                                        ?>
                                    </h6>
                                    <small class="text-muted">
                                        <?= htmlspecialchars( (($_SESSION['user']['role'] ?? '') === 'user') ? 'Người dùng' : 'Doanh nhân' ) ?>

                                    </small>
                                    
                                </div>
                            </div>

                        <!-- Tiêu đề -->
                        <input type="text" id="postTitle" class="form-control form-control-lg mb-3 border-success"
                            placeholder="✏️ Nhập tiêu đề bài viết...">

                        <!-- Tóm tắt -->
                        <textarea id="postSummary" class="form-control mb-3 border-success" rows="2"
                            placeholder="📝 Tóm tắt ngắn gọn nội dung..."></textarea>

                        <!-- Nội dung chính -->
                        <textarea id="newPost" class="form-control mb-3 border-success" rows="5"
                            placeholder="💡 Nội dung chính của bài viết..."></textarea>

                        <!-- Chọn chủ đề -->
                        <div class="mb-3">
                            <label for="topicSelect" class="form-label fw-bold text-success">🌿 Chọn chủ đề:</label>
                            <select class="form-select border-success" id="topicSelect" name="topic_id" required>
                                <option value="">-- Chọn chủ đề --</option>
                                <?php foreach ($allTopics as $topic): ?>
                                    <option value="<?= $topic['id'] ?>"><?= htmlspecialchars($topic['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Thanh công cụ -->
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex gap-2">
                                <label class="btn btn-outline-success btn-sm mb-0" for="postImage">
                                    <i class="fas fa-image me-1"></i> Hình ảnh
                                </label>
                                <label class="btn btn-outline-success btn-sm mb-0" for="postVideo">
                                    <i class="fas fa-video me-1"></i> Video
                                </label>
                                <button class="btn btn-outline-success btn-sm" type="button">
                                    <i class="fas fa-link me-1"></i> Link
                                </button>
                            </div>
                            <button class="btn btn-success px-4 rounded-pill" onclick="addPost()">
                                <i class="fas fa-paper-plane me-1"></i> Đăng bài
                            </button>
                        </div>

                        <!-- Input hidden -->

                        <input type="file" id="postImage" class="d-none" accept="image/*" onchange="previewImage(event)">
                        <input type="file" id="postVideo" class="d-none" accept="video/*" onchange="previewVideo(event)">

                    </div>

                    <!-- Preview ảnh / video -->
                    <div id="imagePreview" class="mt-2 bt-4"></div>
                    <div id="videoPreview" class="mt-2 bt-4"></div>
               



        </div>




</main>