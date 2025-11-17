<?php
// require_once __DIR__ . '/../../config/db.php';
// require_once __DIR__ . '/../../model/article/articlesmodel.php';
// require_once __DIR__ . '/../../model/commentmodel.php';
// require_once __DIR__ . '/../../model/user/businessmenModel.php';

// $comments = CommentsModel::getComments();
// $articles = ArticlesModel::getAllArticles();      
// $topBusinessmen = businessmenModel::getAllBusinessmen(10); // Lấy tối đa 10 doanh nhân                                                                                                                                                                      
?>
<style type="text/css">
    .cdx-notify--error {
        background: #fffbfb !important
    }

    .cdx-notify--error::before {
        background: #fb5d5d !important
    }

    .cdx-notify__input {
        max-width: 130px;
        padding: 5px 10px;
        background: #f7f7f7;
        border: 0;
        border-radius: 3px;
        font-size: 13px;
        color: #656b7c;
        outline: 0
    }

    .cdx-notify__input:-ms-input-placeholder {
        color: #656b7c
    }

    .cdx-notify__input::placeholder {
        color: #656b7c
    }

    .cdx-notify__input:focus:-ms-input-placeholder {
        color: rgba(101, 107, 124, .3)
    }

    .cdx-notify__input:focus::placeholder {
        color: rgba(101, 107, 124, .3)
    }

    .cdx-notify__button {
        border: none;
        border-radius: 3px;
        font-size: 13px;
        padding: 5px 10px;
        cursor: pointer
    }

    .cdx-notify__button:last-child {
        margin-left: 10px
    }

    .cdx-notify__button--cancel {
        background: #f2f5f7;
        box-shadow: 0 2px 1px 0 rgba(16, 19, 29, 0);
        color: #656b7c
    }

    .cdx-notify__button--cancel:hover {
        background: #eee
    }

    .cdx-notify__button--confirm {
        background: #34c992;
        box-shadow: 0 1px 1px 0 rgba(18, 49, 35, .05);
        color: #fff
    }

    .cdx-notify__button--confirm:hover {
        background: #33b082
    }

    .cdx-notify__btns-wrapper {
        display: -ms-flexbox;
        display: flex;
        -ms-flex-flow: row nowrap;
        flex-flow: row nowrap;
        margin-top: 5px
    }

    .cdx-notify__cross {
        position: absolute;
        top: 5px;
        right: 5px;
        width: 10px;
        height: 10px;
        padding: 5px;
        opacity: .54;
        cursor: pointer
    }

    .cdx-notify__cross::after,
    .cdx-notify__cross::before {
        content: '';
        position: absolute;
        left: 9px;
        top: 5px;
        height: 12px;
        width: 2px;
        background: #575d67
    }

    .cdx-notify__cross::before {
        transform: rotate(-45deg)
    }

    .cdx-notify__cross::after {
        transform: rotate(45deg)
    }

    .cdx-notify__cross:hover {
        opacity: 1
    }

    .cdx-notifies {
        position: fixed;
        z-index: 2;
        bottom: 20px;
        left: 20px;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen, Ubuntu, Cantarell, "Fira Sans", "Droid Sans", "Helvetica Neue", sans-serif
    }

    .cdx-notify {
        position: relative;
        width: 220px;
        margin-top: 15px;
        padding: 13px 16px;
        background: #fff;
        box-shadow: 0 11px 17px 0 rgba(23, 32, 61, .13);
        border-radius: 5px;
        font-size: 14px;
        line-height: 1.4em;
        word-wrap: break-word
    }

    .cdx-notify::before {
        content: '';
        position: absolute;
        display: block;
        top: 0;
        left: 0;
        width: 3px;
        height: calc(100% - 6px);
        margin: 3px;
        border-radius: 5px;
        background: 0 0
    }

    @keyframes bounceIn {
        0% {
            opacity: 0;
            transform: scale(.3)
        }

        50% {
            opacity: 1;
            transform: scale(1.05)
        }

        70% {
            transform: scale(.9)
        }

        100% {
            transform: scale(1)
        }
    }

    .cdx-notify--bounce-in {
        animation-name: bounceIn;
        animation-duration: .6s;
        animation-iteration-count: 1
    }

    .cdx-notify--success {
        background: #fafffe !important
    }

    .cdx-notify--success::before {
        background: #41ffb1 !important
    }
</style>
<main class="main-content">


    <!-- 4 cục bài viết nổi bật start -->

    <div class="content-left">
        <h2>Kết quả tìm kiếm "<i><?= htmlspecialchars($q) ?></i>"</h2>
        <ul class="nav nav-pills" id="pills-search-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-semibold position-relative" id="pills-articles-tab" data-type="5"
                    data-bs-toggle="pill" data-bs-target="#pills-articles" type="button" role="tab"
                    aria-controls="pills-articles" aria-selected="true"><i class="fas fa-file-alt"></i> Bài
                    viết</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-semibold position-relative" id="pills-tags-tab" data-type="25"
                    data-bs-toggle="pill" data-bs-target="#pills-tags" type="button" role="tab"
                    aria-controls="pills-tags" aria-selected="false"><i class="fas fa-hashtag"></i> Tags</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-semibold position-relative" id="pills-users-tab" data-type="26"
                    data-bs-toggle="pill" data-bs-target="#pills-users" type="button" role="tab"
                    aria-controls="pills-users" aria-selected="false"><i class="fas fa-user"></i> Người dùng</button>
            </li>
        </ul>


        <style>
            /* Tabs */
            #pills-search-tab .nav-link {
                border-radius: 20px;
                padding: 8px 14px;
                background: #f5f5f7;
                color: #333;
                transition: all .2s ease;
                margin-right: 6px;
            }

            #pills-search-tab {
                margin: 10px 0 18px;
            }

            #pills-search-tab .nav-link:hover {
                background: #ececf1;
            }

            #pills-search-tab .nav-link.active {
                background: linear-gradient(135deg, #3b82f6, #06b6d4);
                color: #fff;
                box-shadow: 0 6px 18px rgba(59, 130, 246, .35);
            }

            /* Result sections */
            .cover-page {
                display: none;
                margin-top: 14px;
            }

            .cover-page.active {
                display: block;
            }

            /* Cards chung */
            .sea-news,
            .sea-tag,
            .sea-user {
                background: #fff;
                border: 1px solid #eee;
                border-radius: 10px;
                padding: 12px;
                margin-bottom: 16px;
                transition: transform .15s ease, box-shadow .15s ease;
            }

            .sea-news:hover,
            .sea-tag:hover,
            .sea-user:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 20px rgba(0, 0, 0, .06);
            }

            /* Bài viết */
            .sea-news {
                display: flex;
                gap: 12px;
                align-items: flex-start;
            }

            .sea-news .s-img {
                width: 120px;
                height: 80px;
                object-fit: cover;
                border-radius: 8px;
                margin-right: 12px;
            }

            .sea-news .item h3 {
                font-size: 16px;
                margin: 0 0 6px;
            }

            .sea-news .item span {
                color: #555;
                font-size: 13px;
            }

            /* Tag */
            .sea-tag h3 {
                font-size: 15px;
                margin: 0;
            }

            .sea-tag a {
                text-decoration: none;
                color: #3b82f6;
            }

            .sea-tag a:hover {
                text-decoration: underline;
            }

            /* User */
            .sea-user {
                display: flex;
                align-items: center;
                gap: 12px;
            }

            .sea-user .user-avatar {
                width: 50px;
                height: 50px;
                border-radius: 50%;
                object-fit: cover;
                flex-shrink: 0;
            }

            .sea-user .info h3 {
                font-size: 15px;
                margin: 0 0 4px;
            }

            .sea-user .info p {
                margin: 0;
                font-size: 13px;
                color: #555;
            }

            .sea-user .info small {
                font-size: 12px;
                color: #777;
            }
        </style>

        <div class="tab-content">

            <!-- Tab Bài viết -->
            <div class="tab-pane fade show active" id="pills-articles" role="tabpanel"
                aria-labelledby="pills-articles-tab">
                <?php if (!empty($articles)): ?>
                    <?php foreach ($articles as $article): ?>
                        <div class="sea-news">
                            <!-- Nếu có ảnh thì dùng, còn không thì bỏ -->
                            <img src="<?= !empty($article['main_image_url']) ? htmlspecialchars($article['main_image_url']) : '/uploads/default.jpg' ?>"
                                class="s-img" alt="<?= htmlspecialchars($article['title']) ?>">
                            <div class="item">
                                <h3>
                                    <a href="<?= BASE_URL ?>/details_blog/<?= $article['slug'] ?>">
                                        <?= htmlspecialchars($article['title']) ?>
                                    </a>
                                </h3>
                                <span><?= htmlspecialchars($article['summary']) ?></span><br>
                                <small>
                                    Tác giả: <?= htmlspecialchars($article['author_name']) ?> |
                                    <?= htmlspecialchars($article['created_at']) ?>
                                </small>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Không tìm thấy bài viết.</p>
                <?php endif; ?>
            </div>

            <!-- Tab Tags -->
            <div class="tab-pane fade" id="pills-tags" role="tabpanel" aria-labelledby="pills-tags-tab">
                <?php if (!empty($tags)): ?>
                    <?php foreach ($tags as $tag): ?>
                        <div class="sea-tag">
                            <img src="<?= !empty($tag['icon_url'])
                                            ? htmlspecialchars($tag['icon_url'])
                                            : '/uploads/default.jpg' ?>" class=""
                                alt="<?= htmlspecialchars($tag['name']) ?>">

                            <h3>
                                <a href="<?= BASE_URL ?>/details_topic/<?= $tag['slug'] ?>">
                                    <?= htmlspecialchars($tag['name']) ?>
                                </a>
                            </h3>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Không tìm thấy tag.</p>
                <?php endif; ?>
            </div>

            <!-- Tab Người dùng -->
           <div class="tab-pane fade" id="pills-users" role="tabpanel" aria-labelledby="pills-users-tab">
    <?php if (!empty($users)): ?>
        <?php foreach ($users as $user): ?>
            <div class="sea-user">
                <!-- Avatar -->
                <img src="<?= !empty($user['avatar_url'])
                                ? htmlspecialchars($user['avatar_url'])
                                : '/uploads/default.jpg' ?>" 
                     class="user-avatar"
                     alt="<?= htmlspecialchars($user['name']) ?>">

                <!-- Thông tin -->
                <div class="info">
                    <h3 class="name">
                        <a title="<?= htmlspecialchars($user['name']) ?>"
                           href="view_profile?id=<?= htmlspecialchars($user['id']) ?>">
                            <?= htmlspecialchars($user['name']) ?>
                        </a>
                    </h3>
                    <p>@<?= htmlspecialchars($user['username']) ?></p>

                    <?php if (!empty($user['role']) && $user['role'] === 'businessman'): ?>
                        <small>Email: <?= htmlspecialchars($user['email']) ?></small>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Không tìm thấy người dùng.</p>
    <?php endif; ?>
</div>


        </div>



        <script>
            (function() {
                var tabs = document.querySelectorAll('#pills-search-tab .nav-link');

                function showPane(target) {
                    document.querySelectorAll('.cover-page').forEach(function(p) {
                        p.classList.remove('active');
                    });
                    var el = document.querySelector(target);
                    if (el) el.classList.add('active');
                }
                tabs.forEach(function(btn) {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        tabs.forEach(function(b) {
                            b.classList.remove('active');
                        });
                        this.classList.add('active');
                        var target = this.getAttribute('data-bs-target');
                        showPane(target);
                    });
                });

                // Init based on active tab at load
                var active = document.querySelector('#pills-search-tab .nav-link.active');
                showPane(active ? active.getAttribute('data-bs-target') : '#pills-news');
            })();
        </script>
    </div>

    <!-- bài viết chính block end -->


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
            <a target="_blank" href="coins-bitcoin.html"><img alt="Crypto" src="public/logo/coin.jpg"></a>
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
    </div>

</main>