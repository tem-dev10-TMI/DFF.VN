<?php
function render_sidebar_content($context = 'desktop', $topTopics = [], $moreTopics = [])
{
    // This function expects $context to be 'mobile' or 'desktop'.
    $is_mobile = ($context === 'mobile');

    ob_start(); // Start output buffering to capture HTML
?>
    <style>
        /* Căn chỉnh lại text cho phần thông tin ở cuối sidebar trên mobile */
        @media (max-width: 991.98px) {
            .h-info li {
                text-align: left !important;
                /* Dùng !important để ghi đè các style khác nếu cần */
                word-wrap: break-word;
            }
        }
    </style>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <div class="<?= $is_mobile ? '' : 'block-k left-menu bg-transparent border0 p-l-0' ?>">

        <ul class="<?= $is_mobile ? '' : 'nav nav-second-level' ?>">
            <li class="item <?= $is_mobile ? 'active' : '' ?>">
                <i class="fa-solid fa-house home-icon" style="font-size:20px;"></i>
                <a href="<?= $is_mobile ? 'home' : BASE_URL . '/home' ?>" title="Trang chủ">Trang chủ</a>
            </li>
            <li class="item">
                <i class="bi bi-book-half idiscuss"></i>
                <a href="<?= $is_mobile ? 'news' : BASE_URL . '/news' ?>" title="Mới nhất">Mới nhất</a>
            </li>
            <li class="item">
                <i class="bi bi-currency-exchange trend-icon"></i>
                <a href="<?= $is_mobile ? 'trends' : BASE_URL . '/trends' ?>" title="Xu hướng">Xu hướng</a>
            </li>
            <?php if (!$is_mobile): ?>
                <li class="item">
                    <i class="fa-solid fa-film" style="font-size:20px;"></i>
                    <a href="<?= BASE_URL ?>/video" title="Video">Video</a>
                </li>
            <?php endif; ?>
        </ul>
        <div class="line"></div>

        <?php if (!$is_mobile): ?>
            <div class="line"></div>
            <label class="bg-tranparent">CHỦ ĐỀ</label>
        <?php endif; ?>


        <?php if (!empty($moreTopics)): ?>
            <?php $collapse_id = $is_mobile ? 'm-collapseTopics' : 'flush-collapseOne'; ?>
            <div id="<?= $collapse_id ?>" class="accordion-collapse collapse">
                <ul class="<?= $is_mobile ? 'top-item' : 'nav nav-second-level top-item' ?>">
                    <?php foreach ($moreTopics as $topic): ?>
                        <li>
                            <img src="<?= BASE_URL ?>/<?= htmlspecialchars($topic['icon_url'] ?? '') ?>"
                                title="<?= htmlspecialchars($topic['name'] ?? '') ?>"
                                alt="<?= htmlspecialchars($topic['name'] ?? '') ?>">
                            <a title="<?= htmlspecialchars($topic['name'] ?? '') ?>"
                                href="<?= BASE_URL ?>/details_topic/<?= htmlspecialchars($topic['slug'] ?? '') ?>">
                                <?= htmlspecialchars($topic['name'] ?? '') ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="to-expend">
                <?php if ($is_mobile): ?>
                    <i class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#<?= $collapse_id ?>"
                        aria-expanded="false" aria-controls="<?= $collapse_id ?>"></i>
                <?php else: ?>
                    <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#<?= $collapse_id ?>"
                        aria-expanded="true" aria-controls="<?= $collapse_id ?>"></button>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="line"></div>
        <ul class="<?= $is_mobile ? 'about-c' : 'nav nav-second-level about-c' ?>">
            <li><i class="bi bi-tv"></i>
                <a href="about#"> Về chúng tôi</a>
            </li>
            <li <?= $is_mobile ? '' : 'class="mline"' ?>>
                <i class="bi bi-book"></i>
                <a href="about#gioithieu">
                    <?= $is_mobile ? 'Chính sách nội dung' : 'Thỏa thuận cung cấp và sử dụng dịch vụ MXH' ?></a>
            </li>
            <li>
                <?php if ($is_mobile): ?>
                    <svg rpl="" fill="currentColor" height="20" icon-name="topic-law-outline" viewBox="0 0 20 20" width="20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M2.3 8.625 3.621 5.31l1.324 3.315h1.346L4.256 3.53a1.37 1.37 0 0 1 1.362-1.28h8.764a1.37 1.37 0 0 1 1.362 1.28l-2.035 5.1h1.346l1.324-3.32L17.7 8.625h1.346l-2.061-5.16A2.62 2.62 0 0 0 14.382 1H5.618a2.62 2.62 0 0 0-2.606 2.465L.951 8.625H2.3Z">
                        </path>
                        <path
                            d="M6.617 10H.625a.625.625 0 0 0-.625.625 3.62 3.62 0 1 0 7.242 0A.625.625 0 0 0 6.617 10Zm-3 3a2.376 2.376 0 0 1-2.288-1.75h4.58A2.376 2.376 0 0 1 3.621 13h-.004Z">
                        </path>
                        <path
                            d="M19.375 10h-5.992a.624.624 0 0 0-.625.625 3.622 3.622 0 0 0 6.966 1.386c.182-.44.276-.91.276-1.386a.624.624 0 0 0-.625-.625Zm-3 3a2.376 2.376 0 0 1-2.288-1.75h4.576A2.375 2.375 0 0 1 16.379 13h-.004Z">
                        </path>
                        <path d="M10.625 5h-1.25v12.7H6.479v1.25h7.042V17.7h-2.896V5Z"></path>
                    </svg>
                <?php endif; ?>
                <a href="about#thuthap"> Chính sách riêng tư</a>
            </li>
        </ul>

        <div class="line"></div>
        <ul class="nav nav-second-level h-info">
            <li class="w-100 text-center">POWERED BY</li>
            <li>Chịu trách nhiệm nội dung: TEAM TMI (DEV - K25)</li>
            <li>Hotline: 083 403 8128 - Email: tmigroup.vn</li>
        </ul>

    </div>

<?php
    echo ob_get_clean(); // Echo the captured HTML
}
?>