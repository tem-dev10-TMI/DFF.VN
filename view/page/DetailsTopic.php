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
                            <a href="javascript:void(0)" class="follow-btn" data-topic-id="<?= (int)($topic['id'] ?? 0) ?>">
                                <span class="val"><?= $isFollowing ? "Đang theo dõi" : "Theo dõi" ?></span>
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
        <div id="topic-articles-list">
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
                                <a href="<?php
                                            if (isset($article['link'])) {
                                                echo htmlspecialchars($article['link']);
                                            } else {
                                                echo "details_blog/" . $article['slug'];
                                            }
                                            ?>">

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
        <!-- 2. Thêm các div cho việc loading -->
        <div id="loading" style="text-align:center; display:none; margin:20px;">
            <em>Đang tải thêm...</em>
        </div>
        <div id="load-more-container" class="text-center" style="display: none; margin: 20px;">
            <button id="load-more-btn" class="btn btn-primary">Xem thêm</button>
        </div>
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

        <script>
            $(document).on("click", ".follow-btn", function(e) {
                e.preventDefault();
                let btn = $(this);
                let topicId = btn.data("topic-id");

                $.post("<?= BASE_URL ?>/controller/topic/topic_follow.php", {
                    topic_id: topicId
                }, function(data) {
                    if (data.success) {
                        btn.find(".val").text(data.following ? "Đang theo dõi" : "Theo dõi");
                        btn.find(".number").text(data.followers_count);
                    } else {
                        alert(data.message);
                    }
                }, "json").fail(function(xhr) {
                    console.error("AJAX error:", xhr.responseText);
                });
            });
        </script>

    </div>
</main>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Hàm này định nghĩa cách một bài viết được vẽ ra HTML
        function renderTopicArticle(article) {
            const div = document.createElement('div');
            div.className = 'block-k article-item';

            const articleLink = article.is_rss ? article.link : `details_blog/${article.slug}`;
            const target = article.is_rss ? '_blank' : '_self';
            const createdAt = new Date(article.created_at).toLocaleString('vi-VN');

            div.innerHTML = `
          <div class="view-carde f-frame">
              <div class="provider">
                  <img class="logo" src="${article.avatar_url || 'https://via.placeholder.com/50'}" alt="${article.author_name}">
                  <div class="p-covers">
                      <span class="name">
                          <a href="/DFF.VN/view_profile?id=${article.author_id}">${article.author_name}</a>
                      </span>
                      <span class="date">${createdAt}</span>
                  </div>
              </div>
              <div class="title">
                  <a href="${articleLink}" target="${target}">${article.title}</a>
              </div>
              <div class="sapo">${article.summary}</div>
              ${article.main_image_url ? `<img class="h-img" src="${article.main_image_url}" alt="${article.title}">` : ''}
          </div>`;
            return div;
        }

        // Gọi hàm scroll vô tận với cấu hình cho trang chủ đề
        setupInfiniteScroll({
            listElementId: 'topic-articles-list',
            loadingElementId: 'loading',
            loadMoreContainerId: 'load-more-container',
            loadMoreBtnId: 'load-more-btn',
            apiUrl: 'api/loadMoreForTopic?slug=<?= $topic['slug'] ?>',
            initialOffset: <?= count($articles) ?>, // Bắt đầu tải từ vị trí sau các bài đã có
            limit: 7,
            renderItemFunction: renderTopicArticle
        });
    });
</script>