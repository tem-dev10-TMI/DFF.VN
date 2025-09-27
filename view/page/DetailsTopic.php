<main class="main-content">
    <div class="content-left cover-page">
        <div class="block-k box-company-label">
            <h5>
                <span><a href="javascript:void(0);">Chủ đề</a></span>
                <span class="c-note"><i class="fas fa-tag"></i> <?= htmlspecialchars($topic['name']) ?></span>
            </h5>
            <div class="box-company">
                <div class="item">
                    <ul>
                        <li>
                            <img class="topic-logo" alt="<?= htmlspecialchars($topic['name']) ?>"
                                src="<?= !empty($topic['icon_url']) ? htmlspecialchars($topic['icon_url']) : 'https://via.placeholder.com/80x80/4A90E2/FFFFFF?text=🌍' ?>">
                        </li>
                        <li class="alias">Chủ đề</li>
                        <li class="name">
                            <a href="<?= BASE_URL ?>/details_topic/<?= $topic['slug'] ?>">
                                <?= htmlspecialchars($topic['name']) ?>
                            </a>
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
                                        <a href="view_profile?id=<?= $article['author_id'] ?>">
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
                          <a href="view_profile?id=${article.author_id}">${article.author_name}</a>
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