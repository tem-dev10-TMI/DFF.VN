<main class="main-content">
    <div class="content-left cover-page">
        <div class="block-k box-company-label">
            <h5>
                <span><a href="#">Chủ đề</a></span>
                <span class="c-note"><i class="fas fa-tag"></i> <?= htmlspecialchars($topic['name']) ?></span>
            </h5>
            <div class="box-company">
                <div class="item">
                    <ul>
                        <li>
                            <img class="logo" alt="<?= htmlspecialchars($topic['name']) ?>"
                                src="<?= !empty($topic['icon_url']) ? htmlspecialchars($topic['icon_url']) : 'https://via.placeholder.com/80x80/4A90E2/FFFFFF?text=🌍' ?>">
                        </li>
                        <li class="alias">Chủ đề</li>
                        <li class="name">
                            <a href="/topic-<?= $topic['slug'] ?>-t<?= $topic['slug'] ?>.html"><?= htmlspecialchars($topic['name']) ?></a>
                        </li>
                        <li class="f-folw">
                            <a data-type="5" href="javascript:void(0)" data-ref="topic-<?= $topic['slug'] ?>">
                                <val>Theo dõi</val>
                                <span class="number"><?= number_format($topic['follower_count'] ?? 0) ?></span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <?php if (!empty($topic['description'])): ?>
                <div class="topic-description mt-2">
                    <?= nl2br(htmlspecialchars($topic['description'])) ?>
                </div>
            <?php endif; ?>
        </div>

        <?php if (!empty($articles)): ?>
            <?php foreach ($articles as $article): ?>
                <div class="block-k">
                    <div class="view-carde f-frame">
                        <div class="provider">
                            <img class="logo"
                                src="<?= htmlspecialchars($article['avatar_url'] ?? 'https://via.placeholder.com/50') ?>"
                                alt="<?= htmlspecialchars($article['author_name']) ?>">
                            <div class="p-covers">
                                <span class="name">
                                    <a href="/DFF.VN/view_profile?id=<?= $article['author_id'] ?>">
                                        <?= htmlspecialchars($article['author_name']) ?>
                                    </a>
                                </span>
                                <!-- Bỏ timeAgo, thay bằng hiển thị ngày giờ -->
                                <span class="date"><?= date("d/m/Y H:i", strtotime($article['created_at'])) ?></span>
                            </div>
                        </div>

                        <div class="title">
                            <a href="<?= BASE_URL ?>/details_blog/<?= $article['slug'] ?>">

                                <?= htmlspecialchars($article['title']) ?>
                            </a>
                        </div>

                        <div class="sapo">
                            <?= htmlspecialchars($article['summary']) ?>
                        </div>

                        <?php if (!empty($article['main_image_url'])): ?>
                            <img class="h-img" src="<?= htmlspecialchars($article['main_image_url']) ?>"
                                alt="<?= htmlspecialchars($article['title']) ?>">
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Chưa có bài viết nào trong chủ đề này.</p>
        <?php endif; ?>
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
    </div>
</main>