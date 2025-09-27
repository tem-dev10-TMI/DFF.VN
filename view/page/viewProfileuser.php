<main class="main-content">
    <?php
    require_once __DIR__ . '/../../time.php';
    require_once __DIR__ . '/../../config/db.php';
    require_once __DIR__ . '/../../model/article/articlesmodel.php';
    require_once __DIR__ . '/../../model/user/UserModel.php'; // cần model để lấy user

    // Lấy id từ URL (dùng ?id=56)
    $profileId = intval($_GET['id'] ?? 0);

    // Nếu không có id thì về trang chủ
    if ($profileId <= 0) {
        header("Location: " . BASE_URL);
        exit;
    }

    // Lấy thông tin user
    $user = UserModel::getUserById($profileId);

    // Nếu user không tồn tại
    if (!$user) {
        echo "<p>Người dùng không tồn tại!</p>";
        exit;
    }

    // Lấy danh sách bài viết của user này
    $offset = intval($_GET['offset'] ?? 0);
    $limit  = intval($_GET['limit'] ?? 5);

    $userId = intval($_GET['user_id'] ?? 0);
    $articles = ArticlesModel::getArticlesByAuthorIdLimit($profileId, $offset, $limit);

    // Các biến follow
    $isFollowing = false;
    $followersCount = 0;

    if (!empty($user['id'])) {
        require_once __DIR__ . '/../../model/user/UserFollowModel.php';
        $db = new connect();
        $pdo = $db->db;
        $followModel = new UserFollowModel($pdo);

        $followersCount = $followModel->countFollowers($user['id']);

        if (isset($_SESSION['user']['id'])) {
            $isFollowing = $followModel->isFollowing($_SESSION['user']['id'], $user['id']);
        }
    }

    $isFollowing = false; // mặc định chưa theo dõi
    $followersCount = 0;

    if (isset($user) && !empty($user['id'])) {
        require_once __DIR__ . '/../../model/user/UserFollowModel.php';
        $db = new connect();
        $pdo = $db->db;
        $followModel = new UserFollowModel($pdo);

        // Lấy số người theo dõi. Giả định phương thức là `countFollowers`.
        $followersCount = $followModel->countFollowers($user['id']);

        // Kiểm tra trạng thái theo dõi nếu người dùng đã đăng nhập.
        if (isset($_SESSION['user']['id'])) {
            $isFollowing = $followModel->isFollowing($_SESSION['user']['id'], $user['id']);
        }
    }
    ?>
    <div class="content-left cover-page">
        <div class="block-k box-company-label">
            <h5>
                <span><a href="javascript:void(0);">Người dùng</a></span>
                <span class="c-note">
                    <i class="fas fa-user"></i> <?= htmlspecialchars($user['name'] ?? 'Không rõ') ?>
                </span>
            </h5>
            <div class="box-company">
                <div class="item">
                    <ul>
                        <li>
                            <img class="logo" alt="<?= htmlspecialchars($user['name'] ?? 'Người dùng') ?>" src="<?= !empty($user['avatar_url'])
                                                                                                                    ? htmlspecialchars($user['avatar_url'])
                                                                                                                    : 'https://i.pravatar.cc/100' ?>"
                                style="width:100px; height:100px; object-fit:cover; border-radius:50%;">

                        </li>
                        <li class="name">
                            <a href="javascript:void(0);"><?= htmlspecialchars($user['name'] ?? 'Không rõ') ?></a>
                        </li>
                        <li class="f-folw">
                            <a class="btn-follow" href="javascript:void(0)"
                                data-user="<?= $user['id'] ?>"
                                style="display:inline-block;padding:8px 16px;border-radius:6px;
              background:<?= $isFollowing ? '#6c757d' : '#28a745' ?>;
              color:#fff;font-weight:600;text-decoration:none;">
                                <span class="follow-text"><?= $isFollowing ? "Đang theo dõi" : "Theo dõi" ?></span>
                                <span class="number"><?= intval($followersCount) ?></span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <?php if (!empty($articles)): ?>
                <?php $currentUserIdForView = $_SESSION['user']['id'] ?? null; ?>

                <div id="articles-list">
                    <?php foreach ($articles as $article): ?>
                        <div class="block-k article-item">
                            <div class="view-carde f-frame">
                                <div class="provider">
                                    <img class="logo"
                                        alt="Avatar"
                                        src="<?= htmlspecialchars($article['avatar_url'] ?? 'https://i.pinimg.com/1200x/83/0e/ea/830eea38f7a5d3d8e390ba560d14f39c.jpg') ?>">
                                    <div class="p-covers">
                                        <span class="name">
                                            <a href="<?= BASE_URL ?>/view_profile?id=<?= $article['author_id'] ?>">
                                                <?= htmlspecialchars($article['author_name']) ?>
                                            </a>
                                        </span>
                                        <span class="date"><?= timeAgo($article['created_at']) ?></span>
                                    </div>
                                </div>

                                <?php if ($currentUserIdForView && $article['author_id'] == $currentUserIdForView): ?>
                                    <?php
                                    $badgeClass = '';
                                    $badgeText  = '';
                                    switch ($article['status']) {
                                        case 'pending':
                                            $badgeClass = 'bg-warning text-dark';
                                            $badgeText  = 'Chờ duyệt';
                                            break;
                                        case 'public':
                                            $badgeClass = 'bg-success';
                                            $badgeText  = 'Công khai';
                                            break;
                                    }
                                    ?>
                                    <?php if ($badgeText): ?>
                                        <div class="article-status-badge" style="margin-bottom: 8px; margin-top: 5px;">
                                            <span class="badge <?= $badgeClass ?>"><?= $badgeText ?></span>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <div class="title">
                                    <a href="<?= BASE_URL . '/details_blog/' . $article['slug'] ?>" target="_self">
                                        <?= htmlspecialchars($article['title']) ?>
                                    </a>
                                </div>

                                <div class="sapo">
                                    <?= htmlspecialchars($article['summary']) ?>
                                    <a href="<?= BASE_URL . '/details_blog/' . $article['slug'] ?>"
                                        class="d-more" target="_self">Xem thêm</a>
                                </div>

                                <?php if (!empty($article['main_image_url'])): ?>
                                    <img class="h-img"
                                        src="<?= htmlspecialchars($article['main_image_url']) ?>"
                                        alt="<?= htmlspecialchars($article['title']) ?>">
                                <?php endif; ?>

                                <div class="item-bottom">
                                    <div class="button-ar">
                                        <div class="dropdown home-item">
                                            <span class="dropdown-toggle" data-bs-toggle="dropdown">Chia sẻ</span>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item copylink"
                                                        data-url="<?= BASE_URL ?>/details_blog/<?= urlencode($article['slug']) ?>"
                                                        href="javascript:void(0)">Copy link</a></li>
                                                <li><a class="dropdown-item sharefb"
                                                        data-url="<?= BASE_URL ?>/details_blog/<?= urlencode($article['slug']) ?>"
                                                        href="javascript:void(0)">Share FB</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div id="loading" style="text-align:center; display:none; margin:20px;">
                    <em>Đang tải thêm...</em>
                </div>
                <div id="load-more-container" class="text-center" style="display: none; margin: 20px;">
                    <button id="load-more-btn" class="btn btn-primary">Xem thêm</button>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        const currentUserId = <?= json_encode($_SESSION['user']['id'] ?? null) ?>;

                        function timeAgo(datetime) {
                            if (!datetime) return '';
                            const time = (Date.now() / 1000) - (new Date(datetime).getTime() / 1000);
                            if (time < 60) return 'vừa xong';
                            if (time < 3600) return Math.floor(time / 60) + ' phút trước';
                            if (time < 86400) return Math.floor(time / 3600) + ' giờ trước';
                            if (time < 2592000) return Math.floor(time / 86400) + ' ngày trước';
                            const d = new Date(datetime);
                            return `${d.getDate().toString().padStart(2,'0')}/${(d.getMonth()+1).toString().padStart(2,'0')}/${d.getFullYear()}`;
                        }

                        function renderHomepageArticle(article) {
                            let statusBadgeHtml = '';
                            if (currentUserId && article.author_id == currentUserId && article.status) {
                                let badgeClass = '',
                                    badgeText = '';
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
                                    statusBadgeHtml = `<div class="article-status-badge" style="margin:5px 0 8px;">
                        <span class="badge ${badgeClass}">${badgeText}</span>
                    </div>`;
                                }
                            }

                            return `
                <div class="block-k article-item">
                  <div class="view-carde f-frame">
                    <div class="provider">
                      <img class="logo" src="${article.avatar_url || 'https://i.pinimg.com/1200x/83/0e/ea/830eea38f7a5d3d8e390ba560d14f39c.jpg'}" alt="">
                      <div class="p-covers">
                        <span class="name"><a href="/view_profile?id=${article.author_id}">${article.author_name || ''}</a></span>
                        <span class="date">${timeAgo(article.created_at)}</span>
                      </div>
                    </div>
                    ${statusBadgeHtml}
                    <div class="title"><a href="/details_blog/${article.slug}" target="_self">${article.title || ''}</a></div>
                    <div class="sapo">
                      ${article.summary || ''}
                      <a href="/details_blog/${article.slug}" class="d-more" target="_self">Xem thêm</a>
                    </div>
                    ${article.main_image_url ? `<img class="h-img" src="${article.main_image_url}" alt="${article.title || ''}">` : ''}
                    <div class="item-bottom">
                      <div class="button-ar">
                        <div class="dropdown home-item">
                          <span class="dropdown-toggle" data-bs-toggle="dropdown">Chia sẻ</span>
                          <ul class="dropdown-menu">
                            <li><a class="dropdown-item copylink" data-url="/details_blog/${article.slug}" href="javascript:void(0)">Copy link</a></li>
                            <li><a class="dropdown-item sharefb" data-url="/details_blog/${article.slug}" href="javascript:void(0)">Share FB</a></li>
                          </ul>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
            `;
                        }

                        setupInfiniteScroll({
                            listElementId: 'articles-list',
                            loadingElementId: 'loading',
                            loadMoreContainerId: 'load-more-container',
                            loadMoreBtnId: 'load-more-btn',
                            apiUrl: '<?= BASE_URL ?>/api/loadMoreArticles?user_id=<?= $user['id'] ?>',
                            initialOffset: 5,
                            limit: 5,
                            renderItemFunction: renderHomepageArticle
                        });
                    });
                </script>
            <?php else: ?>
                <div class="block-k">
                    <div class="view-carde f-frame">
                        <div class="text-center p-4">
                            <p>Chưa có bài viết nào trong cơ sở dữ liệu.</p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

        </div>
        <!-- bài viết chính block end -->
    </div>



    <div class="content-right">
        <div class="adv-banner">
            <a href="#" target="_blank" rel="nofollow">
                <img src="<?= BASE_URL ?>/public/img/banner/Post4.jpg" alt="Banner" />
            </a>
        </div>

        <div class="adv-banner">
            <a href="#" target="_blank" rel="nofollow">
                <img src="<?= BASE_URL ?>/public/img/banner/Post3.jpg" alt="Banner" />
            </a>
        </div>

        <div class="adv-banner">
            <a href="#" target="_blank" rel="nofollow">
                <img src="<?= BASE_URL ?>/public/img/banner/Post1.jpg" alt="Banner" />
            </a>
        </div>

        <div class="adv-banner">
            <a href="#" target="_blank" rel="nofollow">
                <img src="<?= BASE_URL ?>/public/img/banner/Post2.jpg" alt="Banner" />
            </a>
        </div>




        <div class="adv">
            <a target="_blank" href="coins-bitcoin.html"><img alt="Crypto"
                    src="public/logo/coin.jpg"></a>
        </div>

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

                Page.flSuggest();


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
        </script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
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
            });

            // JavaScript for Share & Copy Link functionality
            document.addEventListener('click', function(event) {
                const target = event.target;

                // Toggle Dropdown
                if (target.hasAttribute('data-bs-toggle') && target.getAttribute('data-bs-toggle') === 'dropdown') {
                    event.preventDefault();
                    const dropdown = target.closest('.home-item');
                    const menu = dropdown.querySelector('.dropdown-menu');
                    if (menu) {
                        menu.classList.toggle('show');
                    }
                }

                // Copy Link
                if (target.classList.contains('copylink')) {
                    event.preventDefault();
                    const urlToCopy = target.getAttribute('data-url');
                    if (urlToCopy) {
                        navigator.clipboard.writeText(urlToCopy).then(() => {
                            alert('Đã sao chép link!');
                        }).catch(err => {
                            console.error('Lỗi khi sao chép: ', err);
                            // Fallback for older browsers
                            const textArea = document.createElement('textarea');
                            textArea.value = urlToCopy;
                            document.body.appendChild(textArea);
                            textArea.select();
                            try {
                                document.execCommand('copy');
                                alert('Đã sao chép link!');
                            } catch (fallbackErr) {
                                console.error('Fallback copy failed: ', fallbackErr);
                                alert('Không thể sao chép link.');
                            }
                            document.body.removeChild(textArea);
                        });
                    }
                }

                // Share to Facebook
                if (target.classList.contains('sharefb')) {
                    event.preventDefault();
                    const urlToShare = target.getAttribute('data-url');
                    if (urlToShare) {
                        const facebookShareUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(urlToShare)}`;
                        window.open(facebookShareUrl, 'facebook-share-dialog', 'width=800,height=600');
                    }
                }

                // Close dropdown when clicking outside
                if (!target.closest('.home-item')) {
                    document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
                        menu.classList.remove('show');
                    });
                }
            });
        </script>
</main>