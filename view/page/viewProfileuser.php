<main class="main-content">
    <div class="content-left cover-page">
        <div class="block-k box-company-label">
            <h5>
                <span><a href="#">Người dùng</a></span>
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
                            <a href="#"><?= htmlspecialchars($user['name'] ?? 'Không rõ') ?></a>
                        </li>
                        <li class="f-folw">
                            <a data-type="5" href="javascript:void(0)" data-ref="user-profile">
                                <val>Theo dõi</val>
                                <span class="number">0</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <?php if (!empty($articles)): ?>
                <?php foreach ($articles as $article): ?>
                    <div class="block-k article-item">
                        <div class="view-carde f-frame">
                            <div class="provider">
                                <img class="logo" src="<?= $article['avatar_url'] ?>" alt="">
                                <div class="p-covers">
                                    <span class="name">
                                        <a href="/DFF.VN/view_profile?id=<?= $article['author_id'] ?>">
                                            <?= htmlspecialchars($article['author_name']) ?>
                                        </a>
                                    </span>
                                    <span class="date"><?= date("d/m/Y H:i", strtotime($article['created_at'])) ?></span>
                                </div>
                            </div>

                            <div class="title">
                                <a href="<?= !empty($article['is_rss']) ? $article['link'] : 'details_blog?id=' . $article['id'] ?>"
                                    target="<?= !empty($article['is_rss']) ? '_blank' : '_self' ?>">
                                    <?= htmlspecialchars($article['title']) ?>
                                </a>
                            </div>

                            <div class="sapo">
                                <?= htmlspecialchars($article['summary']) ?>
                                <a href="<?= !empty($article['is_rss']) ? $article['link'] : 'details_blog?id=' . $article['id'] ?>"
                                    class="d-more" target="<?= !empty($article['is_rss']) ? '_blank' : '_self' ?>">
                                    Xem thêm
                                </a>
                            </div>

                            <?php if (!empty($article['main_image_url'])): ?>
                                <img class="h-img" src="<?= htmlspecialchars($article['main_image_url']) ?>" alt="<?= htmlspecialchars($article['title']) ?>">
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Chưa có bài viết nào.</p>
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
                    src="../media.dff.vn/static/img/coins.jpg"></a>
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
</main>