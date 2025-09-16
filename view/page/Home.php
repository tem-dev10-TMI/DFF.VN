<?php
// require_once __DIR__ . '/../../config/db.php';
// require_once __DIR__ . '/../../model/article/articlesmodel.php';
// require_once __DIR__ . '/../../model/commentmodel.php';
// require_once __DIR__ . '/../../model/user/businessmenModel.php';

// $comments = CommentsModel::getComments();
// $articles = ArticlesModel::getAllArticles();      
// $topBusinessmen = businessmenModel::getAllBusinessmen(10); // Lấy tối đa 10 doanh nhân                                                                                                                                                                      
?>

<main class="main-content">


    <!-- 4 cục bài viết nổi bật start -->
    <div class="owl-slider home-slider">
        <div id="home_slider" class="owl-carousel">
            <?php if (!empty($articles)): ?>
                <?php foreach ($articles as $article): ?>
                    <div class="item">
                        <div class="" style="display: none">
                            <a title="<?= htmlspecialchars($article['title']) ?>"
                                href="details_Blog?id=<?= $article['id'] ?>">
                                <div class="mmavatar"><?= htmlspecialchars($article['title']) ?></div>
                            </a>
                        </div>
                        <div class="cover-hover" style="">
                            <img src="<?= htmlspecialchars($article['main_image_url']) ?>"
                                title="<?= htmlspecialchars($article['title']) ?>"
                                alt="<?= htmlspecialchars($article['title']) ?>"
                                border="0" />
                        </div>
                        <div class="text" style="">
                            <h4>
                                <a title="<?= htmlspecialchars($article['title']) ?>"
                                    href="article_detail.php?id=<?= $article['id'] ?>">
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
        <div class="block-k box-write">
            <a href="javascript:void(0)" class="img-own"> <img src="https://dff.vn/vendor/dffvn/content/img/user.svg"> </a>
            <div class="input-group box-search">
                <div class="post-input"><a href="javascript:void(0)" module-load="loadwrite"><span>Viết bài,
                            chia sẻ, đặt câu hỏi…</span></a></div>
            </div>
            <img alt="Viết bài, chia sẻ, đặt câu hỏi" module-load="loadwrite"
                src="https://dff.vn/vendor/dffvn/content/img/img_small.jpg" width="30">
        </div>
        <!-- ////////////////////// -->
        <div class="block-k box-company-label">

            <h5>
                <span><a href="#">Top doanh nhân</a> </span>
                <span class="c-note"><i class="fas fa-chart-line"></i> Được tìm kiếm nhiều nhất </span>
            </h5>
            <div class="owl-slider">
                <div class="owl-carousel box-company owl-loaded owl-drag">
                    <div class="owl-stage-outer owl-height" style="height: 256px;">
                        <div class="owl-stage" style="transform: translate3d(0px, 0px, 0px); transition: all; width: <?= count($topBusinessmen) * 182.667 + (count($topBusinessmen) - 1) * 10 ?>px;">
                            <?php if (!empty($topBusinessmen)): ?>
                                <?php //var_dump($topBusinessmen);?>
                                <?php foreach ($topBusinessmen as $biz): ?>
                                    <div class="owl-item active" style="width: 182.667px; margin-right: 10px;">
                                        <div class="item">
                                            <ul>
                                                <li>
                                                    <img class="logo" alt="<?= htmlspecialchars($biz['username'] ?? $biz['name']) ?>"
                                                        src="<?= htmlspecialchars($biz['logo_url'] ?? 'https://via.placeholder.com/150') ?>">
                                                </li>
                                                <li class="alias"><?= htmlspecialchars($biz['position'] ?? 'Doanh nhân') ?></li>
                                                <li class="name">

                                                    <a href="viewProfilebusiness?id=<?= $biz['id'] ?>">
                                                    <a href="/DFF.VN/view_profile?id=<?= $biz['user_id'] ?>">
                                                        <?= htmlspecialchars($biz['username'] ?? $biz['name']) ?>
                                                    </a>
                                                </li>
                                                <li class="f-folw">
                                                    <a data-type="5" href="javascript:void(0)" data-ref="<?= $biz['id'] ?>">
                                                        <val>Theo dõi</val>
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
                        <button type="button" role="presentation" class="owl-prev disabled"><i class="fa fa-chevron-left"></i></button>
                        <button type="button" role="presentation" class="owl-next"><i class="fa fa-chevron-right"></i></button>
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
        function timeAgo($datetime)
        {
            $time = time() - strtotime($datetime);
            if ($time < 60) return 'vừa xong';
            if ($time < 3600) return floor($time / 60) . ' phút trước';
            if ($time < 86400) return floor($time / 3600) . ' giờ trước';
            if ($time < 2592000) return floor($time / 86400) . ' ngày trước';
            return date('d/m/Y', strtotime($datetime));
        }
        ?>

        <?php if (!empty($articles)): ?>
            <!-- Bọc danh sách bài viết -->
            <div id="articles-list">
                <?php foreach ($articles as $i => $article): ?>
                    <!-- Ẩn bài từ số 10 trở đi -->
                    <div class="block-k article-item" style="<?= $i < 10 ? 'display:none;' : '' ?>">
                        <div class="view-carde f-frame">
                            <div class="provider">
                                <?php
                                $authorAvatar = $article['avatar_url'] ?? 'https://i.pinimg.com/1200x/83/0e/ea/830eea38f7a5d3d8e390ba560d14f39c.jpg';
                                ?>
                                <img class="logo" alt="" src="<?= htmlspecialchars($authorAvatar) ?>">
                                <div class="p-covers">
                                    <span class="name">
                                        <a href="/DFF.VN/view_profile?id=<?= $article['author_id'] ?>">
                                            <?= htmlspecialchars($article['author_name']) ?>
                                        </a>
                                    </span>
                                    <span class="date"><?= timeAgo($article['created_at']) ?></span>
                                </div>
                            </div>

                            <div class="title">
                                <a href="<?= !empty($article['is_rss']) ? $article['link'] : 'details_blog?id='.$article['id'] ?>"
                                target="<?= !empty($article['is_rss']) ? '_blank' : '_self' ?>">
                                <?= htmlspecialchars($article['title']) ?>
                                </a>
                            </div>

                            <div class="sapo">
                                <?= htmlspecialchars($article['summary']) ?>
                                <a href="<?= !empty($article['is_rss']) ? $article['link'] : 'details_blog?id='.$article['id'] ?>"
                                class="d-more" target="<?= !empty($article['is_rss']) ? '_blank' : '_self' ?>">
                                Xem thêm
                                </a>
                            </div>

                            <?php if (!empty($article['main_image_url'])): ?>
                                <img class="h-img"
                                    src="<?= htmlspecialchars($article['main_image_url']) ?>"
                                    alt="<?= htmlspecialchars($article['title']) ?>">
                            <?php endif; ?>

                            <!-- Giữ nguyên phần like, comment, share -->
                            <div class="item-bottom">
                                <div class="bt-cover com-like" data-id="<?= $article['id'] ?>">
                                    <span class="value"><?= $article['upvotes'] ?? 0 ?></span>
                                </div>
                                <div class="button-ar">
                                    <a href="details_blog?id=<?= $article['id'] ?>#anc_comment">
                                        <span><?= $article['comment_count'] ?? 0 ?></span>
                                    </a>
                                </div>
                                <div class="button-ar">
                                    <div class="dropdown home-item">
                                        <span data-bs-toggle="dropdown">Chia sẻ</span>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item copylink"
                                                data-url="details_blog?id=<?= $article['id'] ?>"
                                                href="javascript:void(0)">Copy link</a></li>
                                            <li><a class="dropdown-item sharefb"
                                                data-url="details_blog?id=<?= $article['id'] ?>"
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
        <div class="block-k bs-coin">
            <div class="search-container">
                <div class="input-group imput-group-lg">
                    <span class="input-group-text border-end-0"><i class="fas fa-search"></i></span>
                    <input type="text" id="searchInput" class="form-control border-start-0"
                        placeholder="Tra cứu crypto: BTC, ETH, SOL, BNB,...">
                </div>
                <ul id="coin_suggestions">
                </ul>
            </div>
        </div>
        <div class="block-k cover-chat">
            <h5>
                <a href="#" title=""> <i class="fas fa-comments"></i> Hi! DFF </a>
            </h5>
            <div class="comment-cover">
                <div class="fr-content">
                    <ul class="list_comment col-md-12">
                    </ul>
                    <div class="cm-more">Xem thêm</div>
                </div>
                <div class="h-comment">
                    <a href="javascript:void(0)" class="img-own">
                        <img src="vendor/dffvn/content/img/user.svg">
                    </a>
                    <textarea class="form-control autoresizing" placeholder="Viết bình luận"></textarea>
                    <i class="fas fa-paper-plane" module-load="csend"></i>
                </div>
            </div>
        </div>
        <div class="adv block-k">
            <div class="fb-page" data-href="https://www.facebook.com/vientmi/?locale=vi_VN" data-tabs="timeline"
                data-width="" data-height="" data-small-header="false" data-adapt-container-width="true"
                data-hide-cover="false" data-show-facepile="true">
                <blockquote cite="https://www.facebook.com/vientmi/?locale=vi_VN" class="fb-xfbml-parse-ignore"><a
                        href="../www.facebook.com/vientmi.html">TMI - Viện Phát Triển Đào Tạo và Quản Lý </a>
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
            <i class="fab fa-hotjar"></i> DFF <span>HOT</span>
        </h2>
        <ul>
            <?php foreach ($rssArticles3 as $article): ?>
            <li class="new-style">
                <a title="<?= htmlspecialchars($article['title']) ?>"
                   href="<?= !empty($article['is_rss']) 
                            ? htmlspecialchars($article['link']) 
                            : 'details_blog?id=' . urlencode($article['id']) ?>">
                    <?= htmlspecialchars($article['title']) ?>
                </a>

                <?php if (!empty($article['main_image_url'])): ?>
                <img src="<?= htmlspecialchars($article['main_image_url']) ?>"
                     title="<?= htmlspecialchars($article['title']) ?>"
                     alt="<?= htmlspecialchars($article['title']) ?>"
                     border="0" />
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
                        <a 
    title="<?= htmlspecialchars($event['title']); ?>"
    href="<?= BASE_URL ?>?url=event&id=<?= $event['id'] ?>"
>
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
            <i class="fas fa-search-dollar"></i> DFF <span>ANALYSIS</span>
        </h2>
        <ul>
            <?php foreach ($rssArticles4 as $article): ?>
            <li class="new-style">
                <a title="<?= htmlspecialchars($article['title']) ?>"
                   href="<?= !empty($article['is_rss']) 
                            ? htmlspecialchars($article['link']) 
                            : 'details_blog?id=' . urlencode($article['id']) ?>">
                    <?= htmlspecialchars($article['title']) ?>
                </a>

                <?php if (!empty($article['main_image_url'])): ?>
                <img src="<?= htmlspecialchars($article['main_image_url']) ?>"
                     title="<?= htmlspecialchars($article['title']) ?>"
                     alt="<?= htmlspecialchars($article['title']) ?>"
                     border="0" />
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





        <div class="adv">
            <a target="_blank" href="coins-bitcoin.html"><img alt="Crypto"
                    src="../media.dff.vn/static/img/coins.jpg"></a>
        </div>

        <div class="block-k bg-box-a">
            <div class="box-follow"></div>
        </div>

        <!-- <div class="modal show" role="dialog" id="div_modal" aria-labelledby="myModalLabel" data-popup="true" data-popup-id="6134" aria-modal="true" style="display: block;"> -->
            <!-- <div class="modal-dialog modal-lg" style="width:700px">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" style="cursor: move;"><span class="core-popup-title">Tạo bài viết</span></h4> <button type="button" class="close sh-popup-close"><i class="far fa-times-circle"></i></button>
                    </div>
                    <div class="modal-body" style="padding:10px 15px 10px">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="popup-area-msg"></div>
                            </div>
                        </div>
                        <style>
                            .modal-body {
                                padding: 10px 5px 10px 15px !important;
                            }

                            .main>.left-sidebar {
                                z-index: 1;
                            }

                            .modal-backdrop {
                                z-index: 1;
                            }

                            .modal {
                                z-index: 2;
                            }

                            .modal-content {
                                margin-bottom: 300px;
                            }
                        </style>
                        <div class="mpost">

                            <div id="tab-estep1" class="step-container active ">
                                <div class="smsfc1">
                                    <div class="provider">
                                        <img class="logo" alt="" src="/Upload/img_static//profile_638930940350250052.png">
                                        <div class="p-covers">
                                            <span class="name" title="">
                                                <a href="javascript:void(0)" title=""></a>
                                            </span>
                                        </div>

                                    </div>

                                    <form id="formQQEdit" runat="server" class="editor">
                                        <div class="mncolor0">
                                            <textarea id="Title" name="Title" class="form-control autoresizing" placeholder="Nhập chủ đề..."></textarea>
                                            <textarea id="qTitle" name="qTitle" class="form-control autoresizing hide" placeholder="Nhập chủ đề..."></textarea>
                                            <div id="bodyjs" class="sticky-offset">
                                                <div class="codex-editor codex-editor--empty">
                                                    <div class="codex-editor__redactor" style="padding-bottom: 300px;">
                                                        <div class="ce-block" data-id="KtdDyVSvzS">
                                                            <div class="ce-block__content">
                                                                <div class="ce-paragraph cdx-block ce-paragraph--left" contenteditable="true" data-placeholder="Nội dung chủ đề" data-empty="true"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="codex-editor-overlay">
                                                        <div class="codex-editor-overlay__container">
                                                            <div class="codex-editor-overlay__rectangle" style="display: none;"></div>
                                                        </div>
                                                    </div>
                                                    <div class="ce-inline-toolbar"></div>
                                                    <div class="ce-toolbar ce-toolbar--opened" style="top: 6px;">
                                                        <div class="ce-toolbar__content">
                                                            <div class="ce-toolbar__actions ce-toolbar__actions--opened">
                                                                <div class="ce-toolbar__plus"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                                        <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M12 7V12M12 17V12M17 12H12M12 12H7"></path>
                                                                    </svg></div><span class="ce-toolbar__settings-btn ce-toolbar__settings-btn--hidden"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                                        <path stroke="currentColor" stroke-linecap="round" stroke-width="2.6" d="M9.40999 7.29999H9.4"></path>
                                                                        <path stroke="currentColor" stroke-linecap="round" stroke-width="2.6" d="M14.6 7.29999H14.59"></path>
                                                                        <path stroke="currentColor" stroke-linecap="round" stroke-width="2.6" d="M9.30999 12H9.3"></path>
                                                                        <path stroke="currentColor" stroke-linecap="round" stroke-width="2.6" d="M14.6 12H14.59"></path>
                                                                        <path stroke="currentColor" stroke-linecap="round" stroke-width="2.6" d="M9.40999 16.7H9.4"></path>
                                                                        <path stroke="currentColor" stroke-linecap="round" stroke-width="2.6" d="M14.6 16.7H14.59"></path>
                                                                    </svg></span>
                                                                <div class="ce-toolbox">
                                                                    <div class="ce-popover">
                                                                        <div class="ce-popover__container">
                                                                            <div class="cdx-search-field ce-popover__search">
                                                                                <div class="cdx-search-field__icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                                                        <circle cx="10.5" cy="10.5" r="5.5" stroke="currentColor" stroke-width="2"></circle>
                                                                                        <line x1="15.4142" x2="19" y1="15" y2="18.5858" stroke="currentColor" stroke-linecap="round" stroke-width="2"></line>
                                                                                    </svg></div><input class="cdx-search-field__input" placeholder="Filter" tabindex="-1">
                                                                            </div>
                                                                            <div class="ce-popover__nothing-found-message">Nothing found</div>
                                                                            <div class="ce-popover__items">
                                                                                <div class="ce-popover-item" data-item-name="paragraph">
                                                                                    <div class="ce-popover-item__icon ce-popover-item__icon--tool"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0.2 -0.3 9 11.4" width="12" height="14">
                                                                                            <path d="M0 2.77V.92A1 1 0 01.2.28C.35.1.56 0 .83 0h7.66c.28.01.48.1.63.28.14.17.21.38.21.64v1.85c0 .26-.08.48-.23.66-.15.17-.37.26-.66.26-.28 0-.5-.09-.64-.26a1 1 0 01-.21-.66V1.69H5.6v7.58h.5c.25 0 .45.08.6.23.17.16.25.35.25.6s-.08.45-.24.6a.87.87 0 01-.62.22H3.21a.87.87 0 01-.61-.22.78.78 0 01-.24-.6c0-.25.08-.44.24-.6a.85.85 0 01.61-.23h.5V1.7H1.73v1.08c0 .26-.08.48-.23.66-.15.17-.37.26-.66.26-.28 0-.5-.09-.64-.26A1 1 0 010 2.77z"></path>
                                                                                        </svg>
                                                                                    </div>
                                                                                    <div class="ce-popover-item__title">Text</div>
                                                                                </div>
                                                                                <div class="ce-popover-item" data-item-name="header">
                                                                                    <div class="ce-popover-item__icon ce-popover-item__icon--tool"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                                                            <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M9 7L9 12M9 17V12M9 12L15 12M15 7V12M15 17L15 12"></path>
                                                                                        </svg></div>
                                                                                    <div class="ce-popover-item__title">Heading</div>
                                                                                    <div class="ce-popover-item__secondary-title">Ctrl + ⇧ + H</div>
                                                                                </div>
                                                                                <div class="ce-popover-item" data-item-name="image">
                                                                                    <div class="ce-popover-item__icon ce-popover-item__icon--tool"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                                                            <rect width="14" height="14" x="5" y="5" stroke="currentColor" stroke-width="2" rx="4"></rect>
                                                                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.13968 15.32L8.69058 11.5661C9.02934 11.2036 9.48873 11 9.96774 11C10.4467 11 10.9061 11.2036 11.2449 11.5661L15.3871 16M13.5806 14.0664L15.0132 12.533C15.3519 12.1705 15.8113 11.9668 16.2903 11.9668C16.7693 11.9668 17.2287 12.1705 17.5675 12.533L18.841 13.9634"></path>
                                                                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.7778 9.33331H13.7867"></path>
                                                                                        </svg></div>
                                                                                    <div class="ce-popover-item__title">Image</div>
                                                                                </div>
                                                                                <div class="ce-popover-item" data-item-name="imageWithText">
                                                                                    <div class="ce-popover-item__icon ce-popover-item__icon--tool"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                                                                                            <!-- <path d="M464 448H48c-26.5 0-48-21.5-48-48V112c0-26.5 21.5-48 48-48h416c26.5 0 48 21.5 48 48v288c0 26.5-21.5 48-48 48zM112 120c-30.9 0-56 25.1-56 56s25.1 56 56 56 56-25.1 56-56-25.1-56-56-56zM64 384h384V272l-87.5-87.5c-4.7-4.7-12.3-4.7-17 0L208 320l-55.5-55.5c-4.7-4.7-12.3-4.7-17 0L64 336v48z"></path>
                                                                                        </svg></div>
                                                                                    <div class="ce-popover-item__title">Image with Text</div>
                                                                                </div>
                                                                                <div class="ce-popover-item" data-item-name="table">
                                                                                    <div class="ce-popover-item__icon ce-popover-item__icon--tool"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                                                            <path stroke="currentColor" stroke-width="2" d="M10 5V18.5"></path>
                                                                                            <path stroke="currentColor" stroke-width="2" d="M5 10H19"></path>
                                                                                            <rect width="14" height="14" x="5" y="5" stroke="currentColor" stroke-width="2" rx="4"></rect>
                                                                                        </svg></div>
                                                                                    <div class="ce-popover-item__title">Table</div>
                                                                                    <div class="ce-popover-item__secondary-title">Ctrl + ALT + T</div>
                                                                                </div>
                                                                                <div class="ce-popover-item" data-item-name="quote">
                                                                                    <div class="ce-popover-item__icon ce-popover-item__icon--tool"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 10.8182L9 10.8182C8.80222 10.8182 8.60888 10.7649 8.44443 10.665C8.27998 10.5651 8.15181 10.4231 8.07612 10.257C8.00043 10.0909 7.98063 9.90808 8.01922 9.73174C8.0578 9.55539 8.15304 9.39341 8.29289 9.26627C8.43275 9.13913 8.61093 9.05255 8.80491 9.01747C8.99889 8.98239 9.19996 9.00039 9.38268 9.0692C9.56541 9.13801 9.72159 9.25453 9.83147 9.40403C9.94135 9.55353 10 9.72929 10 9.90909L10 12.1818C10 12.664 9.78929 13.1265 9.41421 13.4675C9.03914 13.8084 8.53043 14 8 14"></path>
                                                                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 10.8182L15 10.8182C14.8022 10.8182 14.6089 10.7649 14.4444 10.665C14.28 10.5651 14.1518 10.4231 14.0761 10.257C14.0004 10.0909 13.9806 9.90808 14.0192 9.73174C14.0578 9.55539 14.153 9.39341 14.2929 9.26627C14.4327 9.13913 14.6109 9.05255 14.8049 9.01747C14.9989 8.98239 15.2 9.00039 15.3827 9.0692C15.5654 9.13801 15.7216 9.25453 15.8315 9.40403C15.9414 9.55353 16 9.72929 16 9.90909L16 12.1818C16 12.664 15.7893 13.1265 15.4142 13.4675C15.0391 13.8084 14.5304 14 14 14"></path>
                                                                                        </svg></div>
                                                                                    <div class="ce-popover-item__title">Quote</div>
                                                                                    <div class="ce-popover-item__secondary-title">Ctrl + ⇧ + O</div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="ce-settings"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="">
                                                <div class="bg-options">
                                                    <label>Màu nền:</label>
                                                    <span class="mncolor0" style="border: 1px solid #333;"></span>
                                                    <span class="mncolor1"></span>
                                                    <span class="mncolor2"></span>
                                                    <span class="mncolor4"></span>
                                                    <span class="mncolor5"></span>
                                                    <span class="mncolor7"></span>
                                                    <span class="mncolor8"></span>
                                                    <span class="mncolor9"></span>
                                                    <span class="mncolor10"></span>
                                                    <span class="mncolor11"></span>
                                                    <span class="mncolor12"></span>
                                                    <span class="mncolor13"></span>
                                                </div>
                                                <div class="line"></div>
                                                <div class="mmBar">
                                                    <ul>
                                                        <li>
                                                            <label>Thêm vào bài viết của bạn: </label>
                                                        </li>
                                                        <li>
                                                            <span class="change-ava">
                                                                <i class="bi bi-camera-fill"></i>
                                                                <input type="file" ref="1" id="mnAvatar" name="mnAvatar">
                                                            </span>
                                                        </li>

                                                    </ul>
                                                </div>
                                                <span class="mnImg">
                                                </span>
                                            </div>


                                            <div class="step1-footr">
                                                <button onclick="stepEditor(2)" type="button" class="btn btn-primary mfsend">Tiếp theo</button>
                                                <button type="button" class="btn btn-primary mqsend hide">Đăng bài</button>
                                            </div>
                                        </div>
                                    </form>


                                </div>
                            </div>












                            <div id="tab-estep2" class="step-container ">
                                <div class="smsfc2">
                                    <form id="formEdit" runat="server">
                                        <div class="sticky-offset con-step2">

                                            <div class="step2-col2">
                                                <ul class="sccontent">
                                                    <li>
                                                        <label>Chọn chủ đề(<span class="red">*</span>)</label>
                                                        <select class="form-control select2 cat-id" id="CatId" name="CatId">
                                                            <option selected="selected" value="0">Chọn chủ đề</option>
                                                            <option value="128">Vĩ mô</option>
                                                            <option value="129">Thị trường</option>
                                                            <option value="2202">Crypto </option>
                                                            <option value="123">360° Doanh nghiệp</option>
                                                            <option value="139">Tài chính</option>
                                                            <option value="140">Nhà đất</option>
                                                            <option value="2201">Quốc tế </option>
                                                            <option value="2203">Thảo luận</option>
                                                        </select>
                                                        <asp:dropdownlist id="cboCat" runat="server" cssclass="form-control cat-id form-select"></asp:dropdownlist>
                                                        <span id="errorCat" class="error" style="display:none;">Bạn chưa chọn chủ đề</span>
                                                    </li>
                                                    <li>
                                                        <div class="select2-container select2-container-multi form-control" id="s2id_txtTags">
                                                            <ul class="select2-choices">
                                                                <li class="select2-search-field"> <label for="s2id_autogen6" class="select2-offscreen"></label> <input type="text" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" class="select2-input select2-default" id="s2id_autogen6" style="width: 100%;" placeholder=""> </li>
                                                            </ul>
                                                            <div class="select2-drop select2-drop-multi select2-display-none">
                                                                <ul class="select2-results"> </ul>
                                                            </div>
                                                        </div><input type="text" value="" placeholder="Tags" class="form-control" id="txtTags" name="txtTags" tabindex="-1" style="display: none;">
                                                    </li>
                                                </ul>

                                            </div>
                                            <div>
                                                <textarea class="form-control" placeholder="Mã nhúng video" cols="20" id="UrlVideo" name="UrlVideo" rows="3"></textarea>
                                            </div>
                                            <div class="block-k m-t-20">
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header">
                                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#viewNote" aria-expanded="true" aria-controls="collapseOne">
                                                            SEO
                                                        </button>
                                                    </h2>
                                                    <div id="viewNote" class="accordion-collapse collapse ">
                                                        <table>
                                                            <tbody>
                                                                <tr>
                                                                    <td>
                                                                        <div class="form-group">
                                                                            <textarea class="form-control" rows="2" id="MetaTitle" name="MetaTitle" placeholder="Tiêu đề SEO không quá 70 ký tự, chứa từ khóa "></textarea>
                                                                        </div>
                                                                    </td>
                                                                </tr>

                                                                <tr>
                                                                    <td>
                                                                        <div class="form-group">
                                                                            <textarea class="form-control" rows="6" id="MetaDescription" name="MetaDescription" placeholder="Mô tả SEO không quá 165 ký tự, chứa từ khóa chính và từ khóa phụ "></textarea>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <div class="form-group">
                                                                            <textarea class="form-control" rows="6" id="MetaKeyword" name="MetaKeyword" placeholder="Từ khóa SEO nhập từ khóa chính và từ khóa phụ "></textarea>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="container_actions">
                                            <div class="spinner f-left"></div>
                                            <a class="a-priview" href="javascript:stepEditor(1)">Quay lại</a>

                                            <input type="button" ref="2" class="btn btnEditSave" value="Đăng bài">



                                        </div>
                                    </form>
                                </div>



                            </div>

                            <div class="d-flex">
                                <div class="nav nav-tabs" role="tablist">
                                    <a href="#tab-estep1" data-bs-toggle="tab" role="tab" id="bt_estep1" class=" active"></a>
                                    <a href="#tab-estep2" data-bs-toggle="tab" role="tab" id="bt_estep2"></a>
                                </div>
                            </div>

                            <script type="module">
                                const ImageTool = window.ImageTool;
                            </script>

                            <script>
                                var data = 'Dow Jones,Masan Consumer Holdings,Masan Consumer,PMI,Lại Xuân Thanh,ACV,PMI Việt Nam,Sungroup,Chip bán dẫn,SDI Corp,Vinahud,VHD,R&H Group,BGI Group,Trương Quang Minh,Masterise Group,The Global City,PDR,Đất nền Hà Nội,Trần Bá Dương,V-Green,Gojek,Grab,Xanh SM,Be,DGW,TPBank,Thaco Agri,Thanh Hóa,Thọ Xuân,Tập đoàn LH,Khu đô thị Lam Sơn - Sao Vàng,Tasco,Tasco Auto,Aeon Mall,Aeon Mall Biên Hòa,CMG,Nợ công,VNG,VNZ,Lê Hồng Minh,Zalo,Nguyễn Cảnh Anh,ZaloPay,Kinh tế Việt Nam,SpaceX,Starlink,Quỹ hưu trí,Becamex IDC,BCM,VSIP,ETH,Đào Minh Tú,Tăng trưởng tín dụng,Nova Thảo Điền,Arthur Hayes,Vũ Thế Phiệt,TPB,Đỗ Minh Phú,SBI Holdings,VIX,EV,Toyota,Volkswagen,GM,Tencent,Yagi,Agriseco,Hong Kong,Bất động sản Hong Kong,Goutai Junan Securities,Haitong Securities,Chứng khoán Trung Quốc,Fomeco,FBC,VEAM,VEA,Bảng giá đất,Vĩ mô,Thị trường chứng khoán,Đấu giá đất Thủ Thiêm,Giảm phát,Ngân hàng Nhà nước,Bão Yagi,Doji Group,Vương Quang Khải,TMS,Transimex,Garmex,GMC,Him Lam,Dương Công Minh,ACG,Trường Sơn Land,Gỗ An Cường,Thắng Lợi Group,Yeah1,YEG,Phú Mỹ Hưng,Khu đô thị Phú Mỹ Hưng,PVI,AIA Việt Nam,VTSS,Chứng khoán Việt Tín,Ant Group,IDG Ventures,IAV Insurance,PVT,PVTrans,Mitsui & Co,Vũ Đình Độ,HUT,Sun World,Sun World Hòn Thơm Phú Quốc,Mitsui,Mặt trời Phú Quốc,Tatsuo Yasunaga,CS Wind,Flamingo Holding Group,Flamingo,Nguyễn Tử Quảng,BKAV,BKAV Pro,F88,F88 Invest,Phùng Anh Tuấn,FiinRatings,Đồng Tâm Group,Võ Quốc Thắng,Chengdu Gaozhen,Enel,Ciputra,Nasdaq,Samsung Electronics Việt Nam,Bắc Ninh,Samsung Display Việt Nam,VBI,Michael Saylor,MicroStrategy,Kelly Wong,Samsung Electronics,Standard Chartered,Nguyễn Thúy Hạnh,Petroyuan,Petrodollar,Katinat,Nguyễn Văn Đỉnh,Đầu tư và Phát triển Du lịch Phú Quốc,Casino Corona Phú Quốc,TCBS,ECB,Du lịch Phú Quốc,Highlands Coffee,Bảo Việt,Lê Thị Hà Thành,Làng Nủ,Thuế nhà đất,Mặt trận Tổ quốc Việt Nam,Ủng hộ vùng bão lũ,Mai Linh,Hồ Huy,Mekong Xanh SM,VFS,Chứng khoán Nhất Việt,Trần Anh Thắng,VPCA,Startup,Golden Gate Ventures,Xe điện Trung Quốc,Cuộc chiến thương mại Mỹ Trung,Kita Invest,Kita Group,Nguyễn Duy Kiên,TT AVIO,Ben Hill,Xe điện Ấn Độ,Bất động sản Bình Dương,TTC Land,SCR,Đặng Huỳnh Ức My,MSB,VNPT,Nguyễn Chí Dũng,Đầu tư tiền ảo,Skoda,Nguyễn Tâm Thịnh,SmartInvest,AAS,HVS,Chứng khoán HVS,Smart Invest,DSC,Thép Trung Quốc,Trường Lộc,Vinhomes Royal Islands,Golden Gate,Đào Thế Vinh,Gogi House,Manwah,Golden Gate International,Golden Gate Partners,Trần Việt Trung,iPhone 16,Phát Đạt,Nguyễn Huy Đức,McDonald`s,Khu công nghiệp Hưng Phú,Cherry,Amkor,NVB,Khu đô thị mới G19,Vietravel Airlines,Đào Đức Vũ,Lương Hoài Nam,Samsung Việt Nam,World Liberty Financial,Bill Gates,Bill & Melinda Gates Foundation Trust,Acecook Việt Nam,Mì ăn liền,Hiroki Kaneda,Jonathan Levin,Park Hark Kyu,Viettel Construction,CTR,Đỗ Mạnh Hùng,Vinare,Hanoi Re,Tái bảo hiểm,The Trump Organization,Idico,IDC,IDICO - CONAC,Novaland Group,Sonadezi,SNZ,SDV,SZL,SZE,Nới lỏng tiền tệ,Đoàn Nguyên Đức,CNGL,Đoàn Hoàng Anh,Nguyễn Phi Long,Hoàng Tùng,Capitaland Tower,Lương Phan Sơn,Landmark 60 Bason,CSI,Chứng khoán Kiến thiết Việt Nam,UHY,Kirin Capital,Hoàng Xuân Hùng,Vương Vệ Á,Eschuri Vung Bau Golf,Phạm Ngọc Thanh,Đoàn Thị Phương Thảo,Shopee,Youtube,Thương mại điện tử,YouTube Shopping,Joe Biden,Narendra Modi,Golf Long Thành,Lê Văn Kiểm,Bàu Cạn - Tân Hiệp,Sân bay Long Thành,Coninco,CNN,Nike,Elliott Hill,KCN Bàu Cạn - Tân Hiệp,Bầu Kiên,Nguyễn Đức Kiên,Đường sắt kết nối Trung Quốc,Đường sắt cao tốc Bắc - Nam,BOE,Qualcomm,Vũ Quang Hội,The Spirit of Saigon,Saigon Glory,Hồ Hùng Anh,Phạm Quang Dũng,VIB,Đặng Khắc Vỹ,Trần Hùng Huy,Âu Lạc,Ngô Thu Thuý,Ramky,Đỗ Quang Hiển,Bầu Hiển,NTL,Lideco,Khu đô thị sinh thái Hồng Hạc – Xuân Lâm,Đầu tư Công đoàn Ngân hàng Công Thương,Viva Land,Chu Lập Cơ,HPX,Hải Phát,Hoà Phát,Liên Hợp Quốc,Landmark 72,Aon,thổi giá bất động sản,giá nhà hà nội,Brendan Nelson,Boeing,Vietnam Airlines,HVN,Chính sách tiền tệ,Lý Xuân Hải,Saigonres,Phạm Thu,SGR,Bệnh viện TNH Việt Yên,Landmark 81,Kido Foods,Cầu Kênh Vàng,Khu đô thị mới Quế Võ,Gary Tseng,Trịnh Văn Quyết,Lê Bá Nguyên,Nutifood,Cầu Tứ Liên,Cầu Trần Hưng Đạo,Nguyễn Văn Hương,Hateco,Maersk,2M Alliance,Dự trữ bắt buộc,An Khang,Đặng Minh Lượm,Geely,Lynk & Co,Xây dựng Hòa Bình,Trần Đình Long,Dung Quất 2,Vũ Thị Chân Phương,Đông Anh,POBC,Luật Đất đai 2024,Ted Osius,Quan hệ Việt Mỹ,Meta,SCB,CBA,Commonwealth Bank of Australia,Deloitte,Vietjet,Honeywell,Xổ số kiến thiết Hà Tĩnh,Vietlott,Nhân dân tệ,Pan Gongsheng,CNY,Ocean Alliance,THE Alliance,Cao tốc Bến Lức - Long Thành,ADB,Nguyễn Thị Hồng,Phan Công Thắng,Đặng Hùng Võ,Nam Long Group,Nguyễn Xuân Quang,Chứng khoán DSC,Pepsi,Nước giải khát Việt Nam,Times Square,Amigo,Larkhall Holdings Limited,Khủng hoảng bất động sản,BCG,BCG Land,Nguyễn Thị Quý Phương,Dragon Capital,PVD,ROS,Mai Tiến Dũng,Vinhomes Ocean Park,Vinhomes Smart City,CII,Thụy Sĩ,SNB,Đinh Bằng Vân,Nhậm Đức Chương,CBRE,Đấu giá đất Đà Nẵng,Kích thích kinh tế,Thái Lan,Aeon,Furusawa Yasuyuki,Phạm Phú Khôi,LPBS,Thu hồi đất,Thăng Long Power,Nhiệt điện Thăng Long,Metro Cầu Giấy - Nhổn,Thiso,Besoverse Cosmic Cafe,Sân bay Gia Bình,Foxconn,Khu đô thị Tây Hồ Tây,APG,Nguyễn Hồ Hưng,DXG,Lương Trí Thìn,Đông Triều,Nga,Hungary,BB Sunrise Power,OceanBank,Dong A Bank,Ấn Độ,Công nghiệp bán dẫn,Bill Smead,Pat Gelsinger,Masterise,VSC,Viconship,Vinaship,VNA,EVNFinance,PYN Elite Fund,Petri Deryng,Binance,Changpeng Zhao,Aeon Mall Hải Dương,Võ Thành Đàng,Vũ Đặng Hải Yến,Vincons,ChatGPT,OpenAI,Tuấn Kiệt HD,Facebook,Hapaco Group,Haseco,HAC,Vũ Hoàng Việt,Nguyễn Hoàng Giang,VMG Media,Vũ Đức Toàn,Kiều Xuân Nam,Nick Clegg,Nghiêm Thị Quỳnh Hương,Khu đô thị - dịch vụ Long Thành,BVB,BVBank,Aeon Mall Giáp Bát,GSM,Căng thẳng Iran - Israel,Thiso Mall,Emart,PVN,Điện gió,Quy hoạch điện VIII,Pin hạt nhân,VGC,Viglacera,Nguyễn Phương Hằng,Khu du lịch Đại Nam,Huỳnh Uy Dũng,Khu đô thị lấn biển Cần Giờ,Shigeru Fujimoto,Xuất nhập khẩu,Lương thực,Ân Thanh Sơn,HVA,Vương Lê Vĩnh Nhân,OnusChain,Blockchain,Luxshare,Raymond Teh,Jensen Huang,Thợ đào Bitcoin,CryptoQuant,Đầu tư Tây Hà Nội,Khu dân cư nông thôn mới xã Cuối Hạ,Lê Văn Quý,Microsoft,Tiền gửi cư dân,Tây Hà Nội,Long Châu,Nguyễn Bạch Điệp,Metro,Đường sắt đô thị,Phan Văn Mãi,Đấu giá đất Hà Đông,VDG,Vạn Đạt Group,Đặng Khắc Cường,Unicap,Sun World Ha Long,Nguyễn Thùy Nga,Tống Ngọc Mỹ Trâm,La Mỹ Phượng,4 Oranges,VNE,Vneco,VE3,Đường sắt đô thị Metro,Sun Paradise Land,Trần Đình Thiên,IDJ,SGB,Saigonbank,Nguyễn Cao Trí,Quỹ bình ổn giá xăng dầu,Đấu giá đất Hà Nội,Huawei,Nhậm Chính Phi,Mạnh Vãn Chu,Meng Wanzhou,Satoshi Nakamoto,SBBS,Nguyễn Thị Hương Giang,Tititada,Saigonbank Berjaya,Hoàng Nam Tiến,Đà Lạt,Lâm Đồng,Singapore,Vinhomes Cổ Loa,Vành đai 4,HJC Group,Khu đô thị Hoà Lạc,Hà Đô Group,HDG,Nguyễn Trọng Thông,Nguyễn Trọng Minh,Lê Xuân Long,Đường sắt cao tốc,Lạng Sơn,Phenikaa,Hồ Xuân Năng,Vicostone,Vaani Research Labs,Metaplanet,VRC,Phan Văn Tướng,Anthony Tan,MB,Vinhomes Global Gate,MBS,Vinacontrol,VNC,Phan Văn Hùng,Heineken,Satra,Sabeco,SAB,CBBank,Dan Gallagher,SEC,UOB,GDP Việt Nam,Đập Bạch Hạc Than,Nhiệt điện Phả Lại,Nguyễn Hồng Khiêm,Nguyễn Văn Kha,Kiều by Kita,Vimedimex,Khu công nghiệp Phong Phú,Robot,Todd Graves,Craig Silvey,Raising Cane`s,Chung cư Hà Nội,VICEM,Lừa đảo,New Zealand,Đất nền TP.HCM,Don Lam,Alex Hambly,TikTok Shop,POW,BID,CTG,Keb Hana Bank,Habaco,Pin con thỏ,PHN,BIC,Trần Hoài An,Nguyễn Đức Tài,Giao dịch tự doanh,TMT,TMT Motors,Wuling Mini EV,VF3,VF 3,Vũ Quang Bảo,Bitexco,Bitcoin,Tiền mã hóa,NTP,SCIC,Nhựa Thiếu niên - Tiền Phong,Điện Máy Xanh,EraBlue,Đoàn Văn Hiểu Em,Kinh tế Mỹ,Giá nhà đất,Nhà Khang Điền,KDH,The Privia Bình Tân,VDSC,Lãi suất,Jerome Powell,TLG,Thiên Long Group,PPC,VPS,AI,Trí tuệ nhân tạo,Quy tắc Sahm,PropertyGuru Group,Pat Sutong,PNJ,Đặng Tất Thắng,Bamboo Airways,FLC,Haxaco,HAX,Mercedes-Benz,Đỗ Tiến Dũng,S&P Global,Lê Xuân Nghĩa,Nguyễn Hùng Cường,Circle K,7 - Eleven,Seven & i Holdings,QNS,Coca-cola,Masan High-Tech Materials,MHT,Masan Consumer Corporation,MCH,Goldman Sachs,KDC,VinaCapital,VNDirect,VND,Phạm Minh Hương,Ringgit,Baht,Won,EVN,Mirae Asset,Cryppto,Jackson Hole 2024,Đấu giá đất Sóc Sơn,Đấu giá đất Đông Anh,Crypto,VEF,Thế giới số Trần Anh,Tỷ giá,BSC,LPBank,LPB,Loic Faussier,TAL,Taseco Land,Giá vàng thế giới,SSI,Nguyễn Duy Hưng,Nguyễn Duy Linh,GAS,PV GAS,NLG,Sốt đất Hà Nội,Nguyễn Hồng Diên,Nguyễn Thanh Nam,Viettel Post,VTP,Viettel,FPT,Tào Đức Thắng,Yên,Carry Trade,VCBS,KBSV,Chứng khoán ASEAN,ASEAN Securities,Mt. Gox,ITA,Đặng Thị Hoàng Yến,Tân Phương Đông,BHS Group,Lê Xuân Nga,TC Group,Blue chip,FOMC,POM,Thép Pomina,Vạn Hương Investoco,Geleximco,Vũ Văn Tiền,ABBank,Glexhomes,Đấu giá đất Hoài Đức,Luật Đất đai,Fubon ETF,Nguyên Xuân Thành,NovaGroup,Novaland,NVL,Vinamilk,VNM,Vũ Quang Nam,Ford,Xe điện,Nâng hạng chứng khoán,Luật Chứng khoán,MSCI,HAH,Vũ Thanh Hải,Starbucks,Brian Niccol,Chipotle,Nhiệt điện LNG Nghi Sơn,Sovico,T&T Group,Thaco,Giá thuê trọ,SK Group,Hồ Quốc Tuấn,Goldin Finance 117,Kido,Hùng Vương Plaza,Bùi Thành Nhơn,NHNN,APH,An Phát Holdings,Phạm Ánh Dương,AAA,Họp Quốc hội,Quốc hội,Justin Sun,Stablecoin,USDD,Xiaomi,Lei Jun,Xuân Thiện Group,Nguyễn Văn Thiện,Cò đất,Nhận định chứng khoán,Trái phiếu,Trái phiếu,FiinGroup,FiinGroup,Nguyễn Quang Thuân,Bất động sản Trung Quốc,Phi đô la,Vladimir Putin,BRICS,Nguyễn Việt Cường,Kosy,Bí quyết chứng khoán,Jack Kellogg,KBC,Kinh Bắc,Đặng Thành Tâm,Kosy Group,KOS,Telegram,Pavel Durov,Đấu giá đất Ba Vì,SSB,SeABank,ABB,SHB,NCB,FRT,FTS,Nguyễn Anh Tuấn,VCF,Vinacafé,Vinacafé Biên Hòa,FWD,FWD Việt Nam,Thắt chặt định lượng,Vua Nệm,Mobicast,Nguyễn Đăng Quang,Bộ Công an,REE,STK,Sự kiện chứng khoán,Nhận định thị trường,Platinum Victory,Sợi Thế Kỷ,Fed,PPI,Lạm phát,Giá vàng hôm nay,Giá vàng SJC,Lazada,Alibaba,Phạm Minh Chính,Giá vàng,HPG,TPS,ORS,Vietcombank,VCB,TCM,MB Bank,JPMorgan Chase,J.P. Morgan Securities PLC,MBB,MBBank,JP Morgan,Trungnam,TTC Group,BB Group,Tài Tâm Group,GEE,Suy thoái,Mỹ,Suy thoái kinh tế,Trungnam Group,Mixue,Trung Quốc,DPM,CSV,BFC,DCM,Lãi suất tiền gửi,BIDV,Agribank,Big 4 ngân hàng,Hàn Quốc,Chứng khoán Mỹ,Tesla,Elon Musk,Kospi,PBOC,Vàng,BSR,Lọc hóa dầu Bình Sơn,Tô Lâm,SEA,IMP,CPI,Imexpharm,Tổng Bí thư Tô Lâm,Intel,USD,MWG,Bách Hóa Xanh,Thế giới Di Động,ETF,PGBank,PGB,HAG,Hoàng Anh Gia Lai,ACB,Bất động sản,Bất động sản Hà Nội,VMS,VIMC,HHV,margin,GDP,Nhật Bản,Hà Nội,Sốt đất,IPA,D2D,BOJ,Giao dịch xuyên trưa,Phiên FTD,HSG,NKG,cổ phiếu tốt,DIG,Tăng trưởng kinh tế,Trần Đình Cung,Nguyễn Đình Cung,Đặng Văn Thành,Samsung,Nvidia,Apple,SK Hynix,Vạn Thịnh Phát,Hồ Quốc Minh,Trương Mỹ Lan,Nguyễn Thiện Tuấn,DIC Corp,,EIB,Tianhong,Hồ Đức Phớc,Bùi Thanh Sơn,Nguyễn Hòa Bình,Giá dầu quốc tế,Dầu Brent,OPEC,Techcombank,Giá xăng dầu,Kinh tế Trung Quốc,Vinpearl,Đất Hà Nội,VPG,Nguyễn Văn Bình,Đầu cơ đất,Hataco Thăng Long,HBS,Nguyễn Phan Trung Kiên,Lê Đình Dương,JPMorgan,CTP,Winmart,Wincommerce,HBC,Hoàng Tuyên,Lê Đức Thọ,Xuyên Việt Oil,VCI,Trương Nguyễn Thiên Kim,Vietcap,Nguyễn Việt Dũng,Hodeco,HDC,Đoàn Hữu Thuận,Pickleball,Cúp Quán Ngon Ba Miền,Nguyễn Đỗ Lăng,API,APS,Apec Group,Kevan Parekh,Tim Cook,Quy hoạch,Quảng Ninh,Đầm hà,Tỉnh Quảng Ninh,PC1,Phan Ngọc Hiếu,Mai Thị  Hồng Hạnh,Room tín dụng,STB,Sacombank,Trầm Bê,SGP,MSC,Cảng Cần Giờ,Trần Hồng Hà,BYD,SAIC,Kylian Mbappé,X,Twitter,Coin,Memecoin,Bất động sản TP. HCM,Giá thuê văn phòng,CSC,Huỳnh Thị Mai Dung,Nguyễn Đỗ Hoàng Lan,Bẫy thu nhập trung bình,FDI,Dai-ichi Life Việt Nam,Google,Berkshire Hathaway,Warren Buffett,Benjamin Graham,Li Ying,Jack Ma,EVF,Mirae Asset Finance Việt Nam,FE Credit,SMBC,FTSE Russell,Home Credit,Donald Trump,Bầu cử Tổng thống Mỹ,BB Power Holdings,Kamala Harris,Nguyễn Quang Bảo,Vương Truyền Phúc,LDG,HDB,HDBank,TCB,Ngân hàng,VinWonders Nha Trang,Cảng Liên Chiểu,Adani Group,Sumitomo Group,Cao tốc,Nợ xấu,Bình Định,Hồ Quốc Dũng,Per Hornung Pedersen,Tập đoàn PNE,Sun Urban City Hà Nam,Jamie Dimon,Lê Minh Hưng,Vũ Đại Thắng,Vinhomes Vũng Áng,SJF,Đỗ Bảo Ngọc,AIC,Nguyễn Thị Thanh Nhàn,CTCP Quản lý Quỹ Genesis,Gotion,VinES,Thu ngân sách Nhà nước,Bảo trì công trình xây dựng,Construction Maintenance,Kinh tế Nhật Bản,Yen,Nada Choueiri,Tamer Wagih Salem,Mohamed Juma Al Shamisi,Neils De Bruijn,Khaled Al Shemeili,M. A. Yusuff Ali,Lulu,Prime Group,Abu Dhabi Ports Group,NMDC Group,Emirates Driving,VinVentures,Non-Owner occupied,Nguyễn Thị Phương Thảo,Qazaq Air,Chiếm giữ không sở hữu,Chuyển quyền sử dụng đất,Transfer of land use rights,Shreya Life Sciences,Bancassurance,BTC,Sheikh Mohammed bin Rashid Al Maktoum,Chủ đầu tư xây dựng,(Construction project owner),Lê Thanh Hoàn,Trịnh Xuân An,Golden Nile,Saltan Bin Ahmed Al Jaber,Tập đoàn Dầu khí Quốc gia Abu Dhabi,Chiếu sáng đô thị,Urban lighting,Quỹ Đầu tư quốc gia Abu Dhabi,ADIA,Sheikh Hamed Bin Zayed Al Nahyan,Abu Dhabi Investment Authority,Phạm Nhật Minh Hoàng,Tianhong Vietnam,Alibaba Group,Ant Financial,Nguyễn Mạnh Tường,Vincom NCT,Nguyễn Hoài Nam,Phạm Thị Thu Hiền,Vincent Tan,Berjaya,Amin Al-Nasser,Saudi Aramco,Thaco Industries,VIMEXPO 2024,Nguyễn Danh Huy,Tổng chi phí phát triển,Total development cost,Gelex Electric,Securities Exchange Act of 1934,Jeremy Siegel,Michelle Bowman,Nguyễn Hưng,Thiết kế cơ sở,Basic Design,Đất quy hoạch,Việt Phúc Fund,Emirates Driving Company,VinDT,GDP (PPP),ASEAN,Cảng Trần Đề,Ted Pick,Trần Lệ Nguyên,Trần Hồng Minh,Luật PPP,BCR,Sheikh Bandar Al Thani,Cơ quan Đầu tư Qatar,QIA,SET,chứng chỉ lưu ký,Mật độ xây dựng,FPT Retail,Đầu tư công,Nguyễn Mại,VNG Data Center,STT GDC,Shophouse,JTA,TNPM,Khu liên cơ quan Vân Hồ,CZ,GDP Indonesia,Dương Văn Bắc,Bất động sản Lạng Sơn,Complex,Khu phức hợp,Bản đồ quy hoạch,Lê Thị Huệ,Multiply Group,Pre-funding,Tổ hợp Hóa dầu Long Sơn,Siam Cement Group,SCG,Khu Kinh tế Ninh Cơ,Nam Định,Eurozone,Fukang Technology,Phạm Như Ánh,TNG,CBS,điện gió ngoài khơi,GlobalFoundries,SMIC,Trưng dụng đất,Eco Pharma,C4G,Trustlink,Cienco4,Tuyến đường tỉnh 769E,FGF,SHBFinance,MUFG,Krungsri,Phân lô bán nền,Nguyễn Đạt,Cao Anh Sơn,Viettel Global,VGI,Thi công công trình xây dựng,FPT Smart Cloud,Góp vốn bằng quyền sử dụng đất,SEC Fillings,Vũ Hữu Điền,TNR,Chuyển mục đích sử dụng đất,CRCC,Đới Hòa Căn,Kevin Hassett,Arthur Laffer,Kevin Warsh,AIG,Nguyễn Thiên Trúc,Dự án cao tốc TP.HCM - Môc Bài,Ngụy Ứng Bưu,COMAC,Cao tốc TP. HCM - Mộc Bài,Bạch Mai cơ sở 2,Việt Đức cơ sở 2,Tập Cận Bình,Đất tái định cư,Khu công nghiệp VSIP II Quảng Ngãi,Quảng Ngãi,VSIP Quảng Ngãi,Hòa  Phát,Tiền thuê đất,Hancorp,Cho thuê quyền sử dụng đất,THILOGI,Sản lượng sản xuất ô tô,Nissan,Pin Hà Nội,Đoàn Quốc Việt,BIM Group,Đoàn Quốc Huy,Scott Bessent,Key Square Capital,HSC,HCM,AI Factory,Quảng Văn Viết Cương,Cao tốc Nam Định - Thái Bình,Kinh tế Đức,Cao tốc Đồng Đăng - Trà Lĩnh,Ukraine,Đấu giá đất Hải Phòng,Đấu giá đất Thủy Nguyên,Khu đô thị mới Mái Dầm,VBMA,Đất Xanh Group,quyền biểu quyết cổ đông,quyền cổ đông,Điểm hòa vốn,CASC,Phí Văn Thịnh,Đất ở đô thị,HNX-Index,Khu đô thị mới phía Tây Bắc (Khu 1),chỉ số tài chính,GPBank,DongABank,GDP Mỹ,Tuyến đường tỉnh 769,Cải cách tiền lương,Trần Thị Thanh Nhã,Ngo Tony,Đấu giá đất Ứng Hòa,Khu nghỉ dưỡng Trung Phường,Adventure Ocean,Tách thửa đất,Ramaswamy,Đường Vành đai 4 TP. HCM,Marcelo Ebrard,Pete Hegseth,DOGE,Dogecoin,LNG,Đất ở tại nông thôn,CapitaLand,CapitaLand Development,Newtecons,Ricons,SOL E&C,Nguyễn Bá Dương,Tranh chấp đất đai,Phạm Quốc Thanh,Lãi suất vay,Hoàng Văn Cường,Nguyễn Phi Thường,El Salvado,Howard Lutnick,Black Spade,Cơ sở dữ liệu quốc gia về đất đai,Tael Two Partners Ltd,Vinasun,Trần Khánh Quang,VNS,TikTok,Shou Zi Chew,Return on Sales,Công trình đặc thù,LG,LG Display,Asia Group,BRG Group,Cantor Fitzgerald,Johnathan Hạnh Nguyễn,Lê Hồng Thủy Tiên,SAS,IPPG,ZingPlay,Nguyễn Ngọc Quỳnh,Lương Duy Đông,Nguyễn Văn Hùng,Nguyễn Đoàn Tùng,Nguyễn Mạnh Cường,TD Bank,CoinMarketCap,Cao tốc Dinh Bà - Cao Lãnh,High-Flyer,Liang Wenfeng,Nguyễn Xuân Trường,Tập đoàn Xuân Trường,Tỷ giá hôm nay,Tỷ giá trung tâm,De-banking,Khu công nghiệp Đồng Phúc,Thadico,Khu đô thị Sala,Litecoin,Bitfinex,Nguyễn Mạnh Hùng,Porsche-Piech,Harry Bolz,Pumpfun,DEX,Sasco,Pi Network,PI,OKX,BMP,Vanke,World FZO,Hapro,HTM,Dương Thị Lam,ETF Ethereum,Kinh tế tư nhân,Khu đô thị công nghệ FPT,Chuyện làm giàu,Phạm Duy Khoa,Nguyễn Duy Khoa,ESOP,RWA,Web3,Viet A Bank,Sri Lanka,Gửi tiết kiệm,Tiền gửi,a16z,Sân bay Phù Cát,Nhà hát Ngọc trai,Khu đô thị Tây An Tây,Khu đô thị mới Tân Bình,Khu đô thị phía Bắc đường Vành đai 4,FSC,DongA Bank,Quảng Yên,VAMA,TC Motor,Reciprocal tarifs,Chiến tranh thương mại,RDP,Staking,Montreal,HGM,Khoáng sản Hà Giang,Antimon,SJE,Nguyễn Văn Sơn,Chun Chae Rhan,Bitcoin Act,VanEck,STABLE Act,GENIUS Act,Khu đô thị Tiên Sơn,Khu đô thị Tiên Ngoại,Vikki Bank,FCM,EPH,AVAX,Buhtan,Citibank,State Street,Lưu ký crypto,Đặc khu kinh tế,Nguyễn Văn Thân,Nguyên Thị Hà,DVSC,Nguyễn Thị Hà,Abu Dhabi,Cuộc chiến thương mại,Javier Milei,LIBRA,Rug bull,Khu kinh tế Vân Phong,Khánh Hòa,Debanking,Khu đô thị Long Vân,Bất động sản Hòa Bình,Ivory Villas & Resort,Archi Reenco Hòa Bình,Nhập khẩu điện,Token AI,ADG,Clever Group,FSN Asia Private Limited,Viettel Cyber Security,Chiến lược đầu tư,MELANIA,VN30,Đấu giá đất Phúc Thọ,Petrovietnam,Goldsun Food,NLG Corp,Liên danh Vietur,Đấu thầu nghiên cứu khoa học,Luật Đấu thầu,JD Vance,Trần Thành Vinh,UBCKNN,APRC,IOSCO,ETF Solana,HK Asia Holdings Limited,Unlock token,Cao tốc TP. HCM - Trung Lương - Mỹ Thuận,Yi He,Khu công nghiệp Thanh Miện I,Khu công nghiệp Gia Lộc III,Grok 3,Mai Văn Chính,Lệ phí trước bạ xe điện,Kimi AI,Moonshot AI,Zhilin Yang,Tiền gử,Chứng khoán BOS,FTX,Market Maker,CrossFi,Đấu giá đất Thái Bình,Sáp nhập tỉnh thành,PET,Petrosetco,The Coffee House,Seedcom,Doanh nghiệp bất động sản,Ý,Giao dịch bất động sản,VJC,Khu công nghiệp Thăng Long Vĩnh Phúc,Sumitomo Corporation,Nigeria,Nikola,Phá sản,Vietnam Ventures Limited,Lakeview City,The Water Bay,Pump.fun,Moonshot,Jupiter Exchange,DApp,Bitget,Gate,Mexc,BaoViet Bank,$PI,Nicolas Kokkalis,TCLife,Nguyễn Tiến Dĩnh,Vimico,Đặng Đức Hưng,Trịnh Văn Tuế,Ngô Quốc Trung,Khu đô thị mới Từ Sơn,Khu đô thị mới Tiên Du,Chip lượng tử,Máy tính lượng tử,Pha tô-pô,Willow,Bán dẫn tô-pô,Majorana 1,Philippe Jean Broianigo,Cung tiền M1,Khu đô thị Tiên Hiệp,KIM Việt Nam,Chống bán phá giá,AmCham,Reciprocal tariffs,Ben Zhou,Arkham,Bybit bị hack,FCN,CC1,Bitexco Power,Lazarus,CCI,ZachXBT,Zachary,Sam Bankman-Fried,Sergei Ryabkov,Subnet,dTAO,Yuma Consensus,Hack sàn giao dịch,Altcoin season index,Bitcoin season,PDN,Cảng Đồng Nai,Đấu giá đất Sơn Tây,eXch,Orochi Network,Goertek Electronics Vietnam,Infini,Infini Neobank,Mỹ Latinh,VISecurities,OCBS,Tiền kỹ thuật số,Rạng Đông Holding,Hồ Đức Lam,BROCCOLI,EURI,Cầu Cần Giờ,Cầu Thủ Thiêm 4,Thuế bất động sản,kế hoạch 21/21,Ví mã hóa,Khóa công khai,Khóa riêng tư,Crypto 101,Helios,Nguyễn Ngọc Cảnh,Portal Global Limited,Robinhood,Vũ Quốc Khánh,Đinh Văn Chiến,Citadel,MM,Lâm Nguyễn Thiện Nhơn,EB-5,Grayscale Bitcoin Trust,BITB,Ki Young Ju,Đỗ Anh Tuấn,Đỗ Thị Định,Sunshine Homes,SSH,Sunshine Group,Nguyễn Thị Thanh Ngọc,Nợ công Mỹ,Generali Việt Nam,Saigon Ratings,S&I Ratings,Thien Minh Rating,Moody’s,FOX,FPT Telecom,Lãi suất cho vay,KSF,Lynn Maxwell,BoA,Chip AI,Micheal Saylor,NEAR,KAITO,RENDER,VGS,Lê Minh Hải,Nguyễn Thị Thanh Thủy,Thép Việt Đức,TraderTraitor,Hồ Viết Thùy,Nguyễn Tùng Lâm,Phan Đức Tú,Safe Wallet,Cadivi,Đỗ Hữu Huy,Sàn giao dịch tiền số,Nissan Automotive Technology,KCN Dầu khí Long Sơn,AI inferencing,MARA,ROX Key Holdings,Ivan Tan,Hacker,Ransomware,Ủy ban Quản lý vốn Nhà nước,Dương Khiết Trì,Volodymyr Zelensky,Crypto Fear & Greed,PUMP,Tracodi,TCD,Huỳnh Thị Kim Tuyến,Hard cap,Soft cap,ICO,DHC,DNC,Rollback,Soft fork,Hard fork,SIMD-0228,Crypto Strategic Reserve,Unitree,Wang Xingxing,Vương Hưng Hưng,Dự trữ Crypto,XPR,BGE,MarketVector Vietnam Local,Trần Sơn Hải,Circle,KIS Việt Nam,DAI,KISVN,Đào Hữu Duy Anh,Luật Nhà ở 2023,SBIC,THORChain,Unstake,The Ninety Complex,Reciprocal tariff,Thuế đối ứng,Đỗ Anh Việt,Nguyễn Tuấn Cường,Khủng hoảng năng lượng,Khu đô thị và dịch vụ Tràng Cát,Phát triển đô thị Tràng Cát,Năng lượng hạt nhân,SMR,SIMDs,validator,SIMD 0228,Ricardo Salinas,Grupo Elektra SAB,Kênh đào Panama,CK Hutchison,Lý Gia Thành,Khu đô thị phức hợp Cam Hòa,Khu đô thị phức hợp Cam Thượng,Khu đô thị phức hợp Suối Tân,Khu đô thị phức hợp Cam Tân,Reddit,Project Liberty,Frank McCourt,Polkadot,Khu đô thị mới huyện Cam Lâm,Lừa đảo tiền số,ATM crypto,Danny Le,Giancarlo Devasini,Jeremy Allaire,PLX,Bitwise,Chagee,Nguyễn Anh Duy,Chagee Việt Nam,Trương Tuấn Kiệt,Đấu giá đất Bắc Ninh,Hồ Anh Ngọc,CBP,Token chứng khoán,Brian Armstrong,Synthetic token,Lending Protocol,Goldfinch,Ondo Finance,Chatbot AI,Sân bay Vân Phong,Sun Air,Khu công nghiệp Bắc Tân Uyên 1,Kho tài sản số,Kho dự trữ Bitcoin chiến lược,Doãn Hồ Lan,Amber Capital,Hầm chui Hoàng Quốc Việt - Vành đai 3,La Phúc Lợi,Cathie Wood,đường vành đai,Hạ tầng nghìn tỷ,Long An,Miễn thị thực,Du lịch Việt Nam,Phú Quốc Airways,Hội nghị APEC,IPP Group,Lê Viết Lam,Phu Quoc Airways,Phu Quoc Airport,Cao tốc Tuyên Quang - Hà Giang (giai đoạn 2),FIFA Coin,Giao dịch xuyên đêm,Cổ tức,USDKG,Kyrgyzstan,Gold Dollar,Lê Quang Vinh,Nguyễn Hoàng Linh,ENA,Cao tốc Gia Nghĩa - Chơn Thành,Mỏ vàng,Mekong Capital,ETH Denver,Vitalik Buterin,USYC,BUIDL,Tokenized Treasury,Trái phiếu token hóa,Đặng Nguyễn Quỳnh Anh,Nhà Long An,Bất động sản Tây Ninh,Tây Ninh,Christine Lagarde,Euro kỹ thuật số,Utah,Xoá nhà tạm, nhà dột nát,nhà dột nát,Maye Musk,Simon McWilliams,Magnificent 7,Môi giới crypto,Thấu chi,DCA Bitcoin,Excelsior Capital Vietnam Partners,Bò Tơ Quán Mộc,Aladdin,Khu đô thị Hồng Hạc – Xuân Lâm,PYUSD,Popplife,FamilyMart,Thuế tiền điện tử,HDS,Aurora Ocean,Phạm Tiến Thăng,Nguyễn Hoàng Nhật Di,Vina Đại Phước,Đại Phước Lotus,ABS,HDBS,SwanBay Đại Phước,HBAR,GPU,Khu công nghiệp Yên Mỹ II,Thời đại mới T&T,Masterise Homes,MGX,Richard Teng,UAE,Luật Công nghiệp Công nghệ số,Verichains,Olivier Brochet,EDF,Cao tốc Vũng Ánh - Bùng,Sean Duffy,CEX,Vinhomes Đan Phượng,Grayscale,Sài Gòn Broadway,Sun City,Khu liên hợp gang thép Dung Quất,SEI,Hồ Anh Minh,Tuyến metro số 1 Thành phố mới Bình Dương - Suối Tiên,HEX,CMF,ABT,DSN,DP3,Đặng Nguyễn Nam Anh,David Solomon,Vimeco,Vinaconex,VCG,BRICS Clear,Thổ Nhĩ Kỳ,Saigon Broadway,Tuyến metro số 2 (Nam Thăng Long - Trần Hưng Đạo),Khu đô thị Bách Đạt,Khu đô thị 7B mở rộng,Hera Complex Riverside,Remitano,Kou Kok Yiow,GF,Khu đô thị mới Mê Linh,Green Future,Bùi Cao Nhật Quân,Cảng Quy Nhơn,Moscow Exchange,Layer-2,Ethereum Foundation,Base,SIP,Lê Phước Vũ,Hoa Sen,Centerville,XRPL,dApps,StilachiRAT,UBS AG London Branch,Manulife Việt Nam,Cao tốc Châu Đốc - Cần Thơ - Sóc Trăng,DNSE,DSE,Charles James Boyd Bowman,Kathy Wood,Ark Invest,Bitcoin Standard,SOL Futures,Đỗ Xuân Thụ,The Nelson Private Residences,Đoàn Thái Sơn,Phạm Thị Thuỳ Linh,Tan Bo Quan Andy,Trịnh Mai Linh,Trịnh Mai Vân,Lê Đức Khánh,Trần Văn,Doanh nghiệp công nghệ,Ripple,Đinh Thị Hoa,Oobit,Lightning Network,TVL,Room ngoại,Dubai,DLD,Bất động sản token hóa,VFBS,Võ Thị Thiên Nga,Bae In han,Đỗ Anh Tú,Firedancer,T&T Energy,Paolo Ardoino,Lê Duy Bình,The Open Netwwork,Six Miles Coast Resort,DFF,Lê Duy Hưng,Đua Fat,Sunshine Hospital,CHP,ADC,CCM,e-CNY,Trust Wallet,BNB Chain,Ledger,Pig butchering,Phạm Thanh Hà,New Hope Group,Lưu Vĩnh Hạo,China Minsheng Bank,Thép Hòa Phát Dung Quất,Hashprice,Eaton Park,Gamuda Land,Đấu giá đất Mỹ Đức,Ngô Thị Thùy Linh,Trần Minh Tuấn,Han Jong-hee,com,Cronos,Brand Finance,Stablecoin neo giá vàng,SBI,FSA,Trần Thăng Long,Cảng Panama,USD1,WLF,Lê Bá Thọ,FYHXX,Dabaco,Nguyễn Thị Minh Giang,PDSI,Peter Dalkeith Scott,Nguyễn Hữu Đặng,Đoàn Mai Hạnh,Hiệp hội Blockchain Việt Nam,Phan Đức Trung,Phạm Tiến Dũng,Prabowo Subianto,Rupiah,Trần Huyền Dinh,KHS,TMW,NAV,Lynn Hoàng,Thẻ vàng nhập cư Mỹ,Noble Crystal Tây Hồ,Đường vành đai 4 Hà Nội,Vinaconex ITC,VCR,Dương Văn Mậu,Cát Bà Amatina,Thuế,Nộp thuế,Hộ kinh doanh,SEC Thái Lan,Nguyễn Thu Hằng,Chu Thanh Tuấn,Perplexity,Harvey AI,Abridge,AI wrappers,AI App,Larry Fink,Home Credit Việt Nam,Four.meme,PancakeSwap,Y Khoa Hoàn Mỹ,Hoàn Mỹ,Nguyễn Thị Châu Loan,Nguyễn Hữu Tùng,Nguyễn Điệp Tùng,Tô Hải,Đường Quảng Ngãi,Đào Ngọc Dũng,Đỗ Vinh Quang,OTC,Tokyu,Tuyến LRT Thủ Dầu Một,GRDP TP. HCM,Kazakhstan,Nâng cấp Etna,Avalanche,Trương Bá Tuấn,Thuế quan đối ứng,Phạm Lưu Hưng,Khu nhà ở xã hội Tiên Dương 1,Khu nhà ở xã hội Tiên Dương 2,USTR,Nguyễn Đức Anh,Tạ Hoàng Linh,Bạch Quốc Vinh,Layer 2,Điện máy Gia dụng Hòa Phát,Kinh tế tuần hoàn Bitcoin,Thanh tra Chính phủ,Kinh tế Đông Nam Á,APAC,Upbit,Kimchi Premium,KYC,AML,LPBank AMC,SSG,VNX,HTC,Đạo luật STABLE,Đỗ Thành Trung,Đất nền Bình Dương,MSTR,Le Viết Hải,SFC,Pakistan,PCC,Nguyễn Chí Thành,Hashrate,Pectra,GRDP Hà Nội,FTA,Hội nhập quốc tế,Hiệp định thương mại tự do,BIM Land,Nguyễn Thị Thanh Huyền,Hoàng Việt Cường,Trần Ngọc Báu,HQC,Địa ốc Hoàng Quân,Trương Anh Tuấn,AAVE,REV,Giao dịch dầu khí,Trà Vinh,Ngô Chí Cường,Uniswap,Hashkey Exchange,Cliff unlock,ARB,Xuất nhập khẩu Bình Dương,VinEnergo,NAC,Lê Khánh Lâm,Central Huijin,China Chengtong Holdings,Thuế bán dẫn,Aeon Mall Việt Nam,Đặng Sĩ Thuỳ Tâm,Bùi Thị Thanh Trà,MANTRA,OM,JP Mullin,Hồ Đông Phong,Hà Đông Phong,Đấu giá đất Quảng Ninh,LG Electronics,Ví điện tử,CMC Corporation,Đặng Hồng Anh,EURC,Tác nhân AI,Xuân Thiện Nam Định,Hapag-Lloyd,Cụm công nghiệp Mai Đình,ZKsync,KuCoin,Tchaikovsky,Roshan Robert,Khu đô thị hỗn hợp Nha Trang,GameFi,Bill Zanker,Scott Morris,Đấu giá đất Phú Thọ,Thomas L.Friedman,Garantex,Benzhou,Đào Mạnh Kháng,Trần Minh Bình,Nguyễn Vân Anh,Thái Hương,Trần Thị Thoảng,Slovenia,Finance One,Bangkok,Cross-chain,TradFi,ZRO,Chainlink,Junjie Zhang,Haidilao,Mùa đại hội đồng cổ đông,Toncoin,TNEX Finance,FCCOM,Trần Anh Tuấn,Núi Chứa Chan,Nguyễn Như So,HYPE,Hyperliquid,RNDR,TNTech,T.FM,Khu công nghiệp,Blocksquare,Vera Group,Vera Capital,Zero-knowledge Proof,Ngô Thu Hà,FBTC,Circle Payments Network,VOXEL,MOVE,TH School,Tập đoàn TH,TH University,Phạm Thiếu Hoa,Chứng khoán APG,Chứng khoán Everest,Nguyễn Hải Châu,21 Capital,Brandon Lutnick,Jamieson L. Greer,Đàm phán thương mại Việt Mỹ,Ripple Labs,Cao Thị Thuý Nga,Twenty One Capital,Jack Mallers,Susanna Campbell,Syre,H&M,Bitcoin Strike,Sun Mega City,Quách Gia Côn,SLS,Mía đường Sơn La,Hà Thu Giang,Kích cầu tiêu dùng nội địa,Nguyễn Hữu Đường,Đường "bia",Tập đoàn Hòa Bình,Hoà Bình Group,Lê Thu Thủy,Lê Ngọc Lâm,Lưu Trung Thái,Phạm Tuấn Anh,Nhựa Bình Minh,Khu dân cư mới xã Đồng Cẩm,Khu dân cư mới xã Tam Kỳ,Khu đô thị mới và sân gôn Liên Hồng,Khu dân cư mới xã Thống Nhất,ETF XRP,Stripe,GameStop,Huỳnh Thị Thảo,Phan Trung Tuấn,Sun World Hon Thom,Paxos,XAUT,PAXG,Đỗ Ngọc Hưng,FAB,AE Coin,Dirham,Vinhomes Cần Giờ,Cảng Hải Phòng,PHP,Cảng Lạch Huyện,Nguyễn Tường Anh,Đường kết nối cầu Tứ Liên,Đường trục kết nối Nam Định - Hoa Lư,Fintech,Chứng khoán Alpha,APSC,City Auto,CTF,Ngô Thị Hạnh,CHứng khoán VIX,PTG,VDB,HSP,Khu công nghiệp Yên Bình 2,EVM,Nguyễn Thiên Hương JENNY,Nguyễn Đức Hiếu JONNY,Sadyr Japarov.,1Matrix,Maldives,Tài sản mã hóa,World,Sam Altman,$WLD,Thúy Rosie,Nghị quyết 68,Zombie coin,Thoả thuận tài nguyên,Thoả thuận tài nguyên Mỹ - Ucraina,Nghị quyết số 68,Avit,Mỹ Ucraina,ETF BNB,Tether AI,Chi ngân sách Nhà nước,Đào Nam Hải,Petrolimex,PGI,Pjico,Stablecoin neo giá USD,aUSDT,BUSD,Vietjet Qazaqstan,ETF altcoin,ETF Litecoin,3C iNC,DeCom Holdings,Nguyễn Đức Vinh,Gói tín dụng 500.000 tỷ đồng,Metro TP. HCM - Cần Giờ,TIN,WLD,Hal Finney,BTC.D,Đánh giá công chức,Riot Platforms,Luxor Technology,Phạm Văn Thanh,Anh,Khu du lịch Tân Thanh,Khu đô thị Tường Vân,Khu phức hợp du lịch – giải trí – sân golf La Vuông,Khu resort nghỉ dưỡng sân golf Bãi Con,Nguyễn Ngọc Quang,Deribit,Khu công nghiệp Quế Võ mở rộng 2,Trần Nam Hưng,Đàm phán thương mại,NNT,MEF,HEC,UDL,DVW,Âu Châu,VinSpeed,Sergei Kudryashov,Zarubezhneft,Positive Technologies,Yury Maksimov,AFK Sistema,Bitcoin SV,Aeon Mall Bắc Giang,Newland,Phạm Trung Kiên,$MELANIA,MEME Act,Cao Thị Ngọc Sương,UNI,GreenFeed Việt Nam,Lý Anh Dũng,Tuyến đường kết nối Hưng Yên - Thái Bình,GRDP Thái Bình,Phạm Hữu Quốc,QD.Tek,Quang Dũng Group,CELAC,Thâm hụt ngân sách,Tether Gold,Maxbit,Carlos Ghosn,Lê Ngọc Xuân,Đầu tư chứng khoán,Fusaka,G-Token,Pichai Chunhavajira,Hưng Yên,VBILL,CCECC,Trần Tư Xương,G-Dragon,Tín dụng TP. HCM,Thao túng thị trường tài sản mã hóa,Sân golf Cẩm Phả,Sân golf đảo Hà Loan,ONDO,American Bitcoin,Qatar Airways,Aerospace,Emirates,GD Culture Group,Qatar,Phạm Văn Đẩu,Nguyễn Văn Sinh,Tín dụng kinh doanh bất động sản,Phạm Văn Trọng,Gói tín dụng nhà ở xã hội,Đấu giá đất Bảo Lộc,Giát đất Bảo Lộc,Khu đô thị mới huyện Gia Bình,Khu đô thị mới phía Tây Bắc TP Bắc Ninh (khu 1),Nestlé,Nestlé Việt Nam,Milo,Viện Dinh Dưỡng,Hà Đức Hiếu,Trần Hoài Nam,Sonic,KLB,FOC,HRB,Hoá chất Đức Giang,Khu đô thị mới phường Đình Bảng,Khu đô thị Thành Công (khu số 4),Khu đô thị sinh thái Lâm Sơn,Khu đô thị mới (DT-ĐT11.21),Giá đất Hưng Yên,Marketing 5.0,Khu đô thị Mông Hoá - Kỳ Sơn,Hoà Bình,Giá đất Bình Định,Bất động sản Bình Định,T. Rowe Price Associates,T. Rowe Price Group,Neocloud,CoreWeave,Đỗ Cao Bảo,AGR,Zeng Yuqun,Pin xe điện,Chip,Kinh tế Úc,RBA,Sun PhuQuoc Airways,SPA,Nguyễn Đức Thạch Diễm,Choi Youngsam,Kelly Yin Hon Wong,Lockheed Martin,Excelerate Energy,Lendable,PowerChina,Châu Gia Nghĩa,Trung tâm chính trị - hành chính tỉnh Đồng Nai,Trật tự kinh tế toàn cầu,Thế giới đa cực,Vinhome Green Bay Mễ Trì,Giá biệt thự Hà Nội,Vinhomes Green Bay Mễ Trì,EPF,Employees Provident Fund Board,Laszlo Hanyecz,Pizza Bitcoin Day,Nguyễn Thanh Nhung,Jamieson Greer,SK Investment,Livzon Pharmaceutical,CETUS,Nguyễn Xuân Lan,Trương Ngọc Lân,Nguyễn Tuấn Dũng,Xếp dỡ Hải An,Thị trường bất động sản,The DAO,Đấu giá đất Phú Xuyên,Giá đất Phú Xuyên,BSI,Đánh thuế đất hoang hoá,Maros Sefcovic,Thương mại Mỹ - EU,Thương mại EU - Mỹ,Nord Stream,Sàn giao dịch vàng,CCV,QHP,DVP,QPH,Khu công nghiệp Hòa Tâm - giai đoạn 1,Thủ tục đầu tư nhà ở xã hội,Booz Allen,Thép,Madam Ngo,Bất động sản Phú Thọ,R3,Corda,Public blockchain,Private blockchain,VN30F1M,Fat finger,Thị trường phái sinh,Nguyễn Lưu Hưng,Đất nền Thạch Thất,World Network,World ID,World Chain,Alex Blania,Proof of Human,Orb,MRS,APT,Korea Telecom,KT,Sổ hồng,CEO,CEO Group,TACO Trade,Zoom,L14,Nợ xấu bất động sản,Sun Life Việt Nam,Sun Life Financial,Euro,Nhựa Thiếu niên Tiền Phong,Đào Hồng Dương,MetaMask,GVR,Tập đoàn Cao su Việt Nam,Lê Toàn,Khu đô thị mới TP Từ Sơn,AI Gemini,Đầu tư Tây Bắc,Multichain wallet,Khu đô thị mới TP. Thái Nguyên,Taseco,Matrix Chain,SafePal,Khu đô thị mới An Phú,Bất động sản Quảng Ngãi,Nippon Steel,US Steel,Golden share,Cổ phiếu vàng,Thị trường vàng,Sở giao dịch vàng quốc gia,SMS Group,Grok,Steven Kobos,Quỹ nhà ở quốc gia,Nestlé Milo,Harvard,SharpLink Gaming,Flywheel ETH,Proof of Stake,Blockchain PoS,JC&C,Doji,Dữ liệu phân tán,Decentralized,HPP,VGL,TAW,DNW,Tuyến đường sắt Hà Nội - Đồng Đăng,Tuyến đường sắt Hải Phòng - Hạ Long - Móng Cái,Tuyến đường sắt TP HCM - Cần Thơ,OFAC,Funnull Technology,Triad Nexus,XChat,X Money,Khu du lịch biển Nhơn Lý - Cát Tiến,Xây dựng C BHI,Địa Ốc THC Bình Định,Christopher Waller,King`s Land,Thăng Long TJC,Aqua City,Thành phố Aqua,Phan Hữu Quốc,TAIKO,Đỗ Đức Duy,VNG Group,Nguyễn Văn Được,Giấy phép xây dựng,MEV,Front-running,Air India,IndiGo,Bầu Đức,Hướng Việt,Thủ tục đất đai,Saudi Arabia,Khu đô thị mới Cam Lâm,Vương Nghị,Khu đô thị thông minh xã Xuân Thọ,Terry Gou,Khu đô thị mới phường Kỳ Trinh,Lee Jae Myung,ETF Bitcoin,Bảo hiểm BIDV,Token,Vũ Ngọc Anh,BCG Energy,Apple Intelligence,ABUSA,Khoáng sản Bắc Kạn,Nguyễn Văn Ngọc,Trần Thị Quỳnh Như,Cidico,Phan Văn Tới,Vsap Lab,Phòng thí nghiệm vi mạch bán dẫn TP Đà Nẵng,PTF,Khu đô thị mới Tây Bắc TP Bắc Ninh (khu 1),Văn Phú Invest,VPI,Phạm Hồng Châu,TVH,PTX,Scale AI,Nguyễn Tấn Danh,SCS,Đất nền Thanh Hóa,MAS,FSMA,Hayden Davis,ETHA,Lê Hoài Anh,CTX Holdings,CTX,Khu du lịch sinh thái biển Xuân Hội,Đầu tư Xuân Hội,Siri,Khu đô thị mới Tượng Lĩnh,Bất động sản Tượng Lĩnh,Joachim Nagel,Bolivia,Nguyễn Long Triều,GLD,AEOI,Ngân hàng bị chuyển giao bắt buộc,Mekolor,Robotaxi,Đào Xuân Tuấn,Khu đô thị mới Bắc Sông Cấm,CPI Mỹ,Thales,Alstom,Ant International,Alipay,Lotte,Lotte Finance,Lotte Card,James Wynn,PEPE,Nguyễn Văn Trúc,Nguyễn Quang Khánh,NDA Chain,NDA DID,Wealthtech Innovations,VNP,TCW,Xung đột Iran - Israel,Khu công nghiệp Cà Ná giai đoạn 1,Tội phạm tiền số,ZKJ,KOGE,Polyhedra,Binance Alpha,Nhà máy điện gió Vân Canh Bình Định,Nhà máy điện gió Thiên Long Chợ Mới,Nhà máy điện gió Quảng Trị Win,Nhà máy điện gió Đông Hải 3,Phan Đình Tuệ,AIPAC,Ủy ban Công vụ Mỹ – Israel,ETP Crypto,Khu thương mại tự do TP. Đà Nẵng,Nam Tân Uyên,Cảng Sài Gòn,Resco,Saigontourist,Perpetual futures,BitMEX,Funding rate,Chứng khoán Hải Phòng,Giá thuê mặt bằng,Paul Chan,Smartphone T1,Hoàng Huy,APEC 2027,Khu đô thị hỗn hợp - Bãi Đất Đỏ,Trung tâm tổ chức Hội nghị APEC,Đất nền Bắc Ninh,Đất nền Bắc Giang,Đất nền Hưng Yên,Đất nền Hòa Bình,Đất nền Vĩnh Phúc,U.S. Steel,Stablecoin neo đồng won,Tây Bắc,Tập đoàn Tây Bắc,Vũ Thế Trường,Aeon Tiền Giang,Sân golf Quang Hanh,Nobitex,Predatory Sparrow,Sân bay Phú Quốc,Đồng franc Thụy Sĩ,Đạm Cà Mau,TRX,Thương mại VIệt Mỹ,Toshihiro Mibe,CAD,LKW,DFC,TMG,TVG,Giá đất Đà Nẵng,Đất Đà Nẵng,Bất động sản Đà Nẵng,Khu đô thị số 1 Măng Đen,Khu đô thị số 4 Măng Đen,Khu đô thị số 5 Măng Đen,Kon Tum,Metro thành phố mới Bình Dương – Suối Tiên,Lê Phong,The Emerald 68,Nguyễn Văn Thành,Sân bay quốc tế,Khu đô thị mới Long Hậu,Khu dân cư đô thị phía bắc sông Bến Lức - Chợ Đệm,Mai Hữu Đạt,Cổ phiếu dầu khí,OIL,Abbas Araghchi,Vinhomes Green Paradise,Na Uy,Khu nhà ở Tiến Hưng,Trung tâm hội nghị Khu đô thị Bắc sông Cấm,BOC,Cát Hải Kiều,Ngân hàng Trung Quốc,Phú Long,VTK,Thuế crypto,NCTS,NCT,Luật Ngân sách nhà nước (sửa đổi),Luật Ngân sách nhà nước sửa đổi,KB Kookmin Bank,Đầu tư tư nhân,One Capital Hospitality,Nguyễn Hoàn Vũ,ASEAN-5,Dòng vốn đầu tư toàn cầu,Rúp số,Ocean Group,Lê Thị Việt Nga,Hóa đơn điện tử,Sean Stein,Khu dịch vụ du lịch phức hợp cao cấp huyện Vân Đồn,Căng thẳng Mỹ - Iran,Nguyễn Thị Thu Hương,Nguyễn Thị Bích Ngọc,VRG - Bảo Lộc,JBIC,Maeda Tadashi,RLUSD,Khu công nghiệp Bình Giang,Capital erosion,Semler Scientific,Bất động sản Hoàng Long,Trần Tấn Lộc,Tuyến đường sắt Thủ Thiêm - Long Thành,Kỷ nguyên mới,Chính sách thuế,Trung tâm dữ liệu,Thanh toán số MobiFone,Nguyễn Thành Long,BeGroup,Malaysia,Chứng khoán Sen Vàng,Chứng khoán Xuân Thiện,XTSC,GLS,Khu đô thị mới Tu Bông,Khu đô thị mới Đầm Môn,KSB,Khu công nghiệp Đất Cuốc,Khu nhà ở xã hội phường 10 TP Vũng Tàu,Lam Hạ Center Point,Sổ đổ,Khu đô thị mới Đông Nam Dung Quất - phía Bắc,Khu đô thị mới Đông Nam Dung Quất - phía Nam,Deutsche Bank,Dịch vụ lưu ký tài sản số,Stablecoin neo nhân dân tệ,Gillian Bird,Khu đô thị mới phía Nam thị xã Ba Đồn,Khu đô thị Trung Trạch,Khu đô thị hỗn hợp ven biển Nhật Lệ,Khu đô thị Đại Trạch 2,Đất Đông Anh,Xuất khẩu nông lâm thủy sản,Khu đô thị mới Hồng Thái - An Hải,Khu đô thị Thủy Đường - Thủy Sơn,Khu đô thị tại phường Hồng Thái,Cao tốc TP. HCM - Long Thành,Cen Land,Thừa cung bất động sản,HLB,JD.com,FATF,VASPs,CFT,Travel Rule,ZKP,Lê Xuân Hải,Nguyễn Thanh Sơn,JBS,Fábio Maia de Oliveira,Jose Serrador Neto,Cổ phiếu ngân hàng,Vietchain Talents,Khu công nghiệp Phú Bình,Phạm Ngọc Vịnh,Vương Công Đức,Sân bay Ninh Bình,Daniel Guardado,Vale,Eric Balchunas,Hà Nội Anpha,Khu công nghiệp Hoàng Diệu,Tài sản thực token hóa,Tài sản thực toke hóa,VGT,Nguyễn Thị Mùi,Bùi Hoàng Hải,Hội nghị đầu tư Techcombank 2025,CRO,Kinh tế thị trường,Marc Knapper,Google Chrome,Sky M,BONK,GMX,Úc,Dự án Acacia,ASIC,MASVN,Tài sản sô,Bộ Khoa học & Công nghệ,Nguyễn Khắc Lịch,Lương tối thiểu vùng,GBTC,USBAC,USABC,Thượng Hải,Anti-CBDC Surveillance State Act,Luật Tài sản số,CLARITY Act,Bùi Vương Anh,Chu Thị Hường,Ursula von der Leyen,Sovico Group,Bất động sản phía Nam,Vàng miếng,Nghị định 24,Xe máy điện,Cấm xe máy xăng,Marubeni,Masayuki Omoto,Vietravel,Nguyễn Quốc Kỳ,Synopsys,Ansys,Trần Mai Hoa,Hóa chất Minh Đức,Citygroup,Tài chính hành vi,Sự thiên lệch Longshot,Favorite-longshot bias,Khu công nghiệp Thủy Nguyên,Khu công nghiệp sân bay Tiên Lãng,Nhà ở xã hội Phú Sơn, Thanh Hóa,Cá voi Bitcoin,Crypto Week,Nguyễn Sơn,Nguyễn Ngọc Linh,LetsBonk,SHIB,Dự án Phân khu A – Khu đô thị phía Bắc quốc lộ 5,Khu dân cư nông thôn mới Đầm Bạch Thủy,Khu dân cư mới Vạn Xuân,Khu dân cư xã Tu Vũ,Layer-1,Mạng dịch vụ đa chuỗi,Đỗ Văn Thuật,Khu nhà ở xã hội phường Nam Sơn,Khu nhà ở xã hội phường Đại Phúc,Lưu Văn Tuyển,SZB,VDN,DHN,Yield-bearing stablecoin,VBSN,Khu đô thị sinh thái núi Sông Cầu,Nguyễn Đức Thông,Chứng khoán BIDV,NT2,Nhà máy điện Nhơn Trạch 2,CryptoPunks,Penguins,Pudgy Penguins,Infinex Patrons,Conflux,$CFX,Thuế lãi chuyển nhượng chứng khoán,Mori Kazuki,Ladotea,Bệnh viện Đa khoa Quốc tế Huế,Thuế chứng khoán,Khu đô thị mới đại học II - Tiểu khu 111.1,Digital Pound,COINS Act,Yukihito Honda,Thành phố thông minh Bắc Hà Nội,TMTG,Khu đô thị CK54,Payment stablecoin,Yield-bearing stablecoins,Vạn Hạnh Mall,Đầu tư Xây dựng Bắc Bình,Đỗ Hữu Hưng,Khu đô thị cao cấp Mê Linh,Thu ngân sách TP. HCM,HKMA,VietinBank Capital,Tôn Đông Á,GDA,BAF,Khu nhà ở,dịch vụ khu công nghiệp Phố Nối, dịch vụ Khu công nghiệp Phố Nối,Phố Nối House,GrabPay,Philippines,Kho Dự trữ Dầu chiến lược,SPR,Happy Home Tràng Cát,Dunamu,Kim Hyoung-nyon,GEG,Nhà Trắng,RIC,Casio Royal Hạ Long,Casino Royal Hạ Long,Kinh tế Thổ Nhĩ Kỳ,NCA,Phùng Quang Hưng,Travel Ruler,Khu dân cư cao cấp Cần Giờ,Khu công nghiệp Xuyên Á,LEAP,Cảng Nam Đồ Sơn,SegWit,BIP,HDCapital,T&T City Millennia,G-SIBs,CitiGroup,Hiệp hội Dữ liệu Quốc gia,Cá voi Ethereum,MAMO,BitMine,Token Securities Act,VARS,Airbnb,G7,Mastercard,Bất động sản Singapore,PropertyGuru,HSA,Kiến thức đầu tư,Chỉ số giá xây dựng,Vốn đầu tư công,Tổng diện tích sàn xây dựng,Gross Floor Area,NHA,Đô thị Nam Hà Nội,Hợp đồng EPC,Engineering - Procurenment - Construction,Simon Holland,Proxima B,Tổng mức đầu tư xây dựng,Total Construction Investment Amount,CVS,MoMo,M_Service,Nguyễn Trần Mạnh Trung,DAG,Nhựa Đông Á,Tập đoàn Nhựa Đông Á,Nguyễn Xuân Thành,Triều Tiên,S&P 500,Tuyến đường sắt Lào Cai - Quảng Ninh,Luật Đường bộ,Phí đường bộ,Báo cáo nghiên cứu khả thi đầu tư xây dựng,Amazon,LVMH,Bernard Arnault,Khu đô thị du lịch Cồn Ấu,Dương Tấn Hiển,Môi trường kinh doanh,Định mức xây dựng,Báo cáo tiền khả thi xây dựng,Hệ số điều chỉnh giá đất,Halico,HNR,Habeco,Diageo,Hệ số sử dụng đất,Floor Area Ratio,LTG,Lộc Trời,Nguyễn Tấn Hoàng,Nguyễn Duy Thuận,Ni Hong,Kim Jong-un,EY,Big4,Capital Place,Twin Peaks,Bộ Công Thương,Delta Electronics,HAGL,Sân bay Nà Sản,Sân bay Quảng Trị,Sân bay Biên Hòa,M&A bất động sản,David Jackson,Cẩm nang đầu tư,Biểu đồ nến,Nến Doji,Build - Transfer,Hợp đồng BT,Hợp đồng xây dựng - chuyển giao,Đỗ Đức Thành,Phạm Chí Thành,Phạm Văn Minh,Kristalina Georgieva,Tim Leelahaphan,MGC,DPR,AVC,TTT,AWS,Airbus,UniCredit,Raiffeisen,Phương Đông Hà Nội,Khu Tứ giác Bến Thành,Aeon Mall Hà Nam,Tuấn Kiệt Hà Nam,VietABank,VAB,Phương Hữu Việt,Việt Phương Group,Shark Tank Việt Nam,Capella Group,LEC Group,Khu du lịch nghỉ dưỡng sinh thái núi Mỏ Neo,giá dầu,Mỏ cát ĐB2B,Quảng Nam,Lê Văn Dũng,ISCVina,Nhà máy điện khí LNG Quỳnh Lập,Nghệ An,Đinh Trọng Thịnh,VinAI,VinBrain,Artemis Investment,VietCredit,Tài khoản ngân hàng,Thông tư 17,Luật Điện lực,Điện hạt nhân,Luật Năng lượng nguyên tử,Lương Cường,Chủ tịch nước Lương Cường,Cẩm nang nhà đất,Luật PPP 2020,Luật Thủ đô,Bản đồ hiện trạng sử dụng đất,Vốn hoá chi phí lãi vay,PVcomBank,Đặng Thế Hiển,Nguyễn Thị Linh Chi,Serbia,Aleksandar Vucic,EU,ANV,VHC,Jeffrey Schmid,Lorie Logan,Neel Kashkari,Cho vay margin,Ray Dalio,Hòa Phát,Khu liên hợp Gang Thép Hòa Phát,Yorkville,Savico,Ngô Đức Vũ,Sông Sài Gòn,Báo cáo định giá,Bản vẽ hoàng công,DGC,Đào Hữu Huyền,GDP Malaysia,Đồng euro,Nvidia Việt Nam,Điện mặ trời,Lương hưu,Lương công chức,Điện mặt trời,Tiki,Phó Thủ tướng Hồ Đức Phớc,Anton Siluanov,Lê Hoàng Châu,HoREA,Bất động sản nông nghiệp,PAN Group,PAN,Đô Đức Duy,SHS,Đỗ Quang Vinh,TSMC,DBC,iShares,KRX,Bất động sản nghỉ dưỡng,Bất động sản thương mại,Form 20-F,Nguyễn Sinh Nhật Tân,Savills,Cao Thị Thanh Hương,Khu đô thị mới xã Lạc Hồng,Cao tốc Hà Tiên - Rạch Giá - Bạc Liêu,G20,Bồi thường về đất,Land Compensation,Nguyễn Văn Tuấn,Chi phí đầu tư vào đất còn lại,Lehman Brothers,Shadow banking,Nguyễn Yến Linh,SMART,Tín dụng đen,Sở hữu chéo,Mô hình nến,NIM,CASA,Phân tích kỹ thuật,RSI,Bear Market,Bull Market,Thị trường 1,Thị trường 2,Lãi suất qua đêm,Moving Average,Bollinger Bands,VFMVN Diamond,Bitcoin Halving,Biên an toàn,Margin call,Force sell,Aleksander Lukashenko,Vĩnh Hoàn,DXS,Rosatom,Viện Nghiên cứu hạt nhân Dubna,IEA,Đông Nam Á,Chi phí quản lý dự án,Rainbow Insurance,Phan Thanh Bình,Phan Thanh Sơn,Vinhomes Park,Chung cư TP. HCM,Chi phí thay thế,Replacement cost,TDC,Land price table,Thao túng tiền tệ,Nguyễn Đức Thụy,VietBank,VBB,Nguyễn Bá Cảnh Sơn,Dat Bike,Dat Bike Pte Ltd,InfraCo Asia,Apax English,Asanzo,Đỗ Thị Kim Liên,Khải Silk,Cao Trần Duy Nam,Trần Thị Thanh Vân,Jens Lottner,VAT,Thuế suất thuế GTGT,PAT,NTC,Chris Wright,Đoàn Hồng Việt,Digiworld,Đậu Minh Thanh,HUD,Embraer,José Serrador,SGI Capital,Ngyễn Quốc Anh,Thái Nguyên,Sông Công,Phổ Yên,Central Retail,Khang Minh Group,GKM,Nguyễn Việt Hà,Trương Tuấn Anh,HFIC,Lê Ngọc Thủy Trang,Nguyễn Kỳ Phùng,Nguyễn Văn Tài,Marc Rowan,Bill Hagerty,Gemalink,Gemadept,Cảng Nam Đình Vũ,Kazuo Ueda,COG Investments I B.V.,VESTA VN Investments B.V.,Warburg Pincus,Cụm công nghiệp,EVN Finance,GKM Holdings,Tinh gọn bộ máy,Trần Thanh Mẫn,Khu công nghệp Cam Liên,Capella,Capella Quảng Bình,Bac A Bank,BAB,DFK,Na Sungsoo,Finhay,VNSC,Nghiêm Xuân Huy,El Salvador,Nayib Bukele,DNP,DNP Holding,DNP Water,Hải Dương,Tuyến metro số 2 (Bến Thành - Tham Lương),Transit Oriented Development,TOD,Chrome,Spirit Airlines,Capella Land,DFK Việt Nam,TTF,Saigon Bank,Sài Gòn Đại Ninh,Mai Hữu Tín,Thông tư 50/2024/TT-NHNN,thuế quan,Bộ trưởng Năng lượng Mỹ,Mathieu Friedberg,CMA-CGM,Phạm Thị Bích Huệ,Western Pacific Group,HNIP,Khu công nghiệp Đồng Văn VI,VinRobotics,Phạm Nhật Quân Anh,P/E,P/E forward,Aeon Mall Cần Thơ,Hoa Lâm Cần Thơ,Hoa Lâm,Dương Khắc Mai,CMA Terminals,Bhutan,GMD,Gautam Adani,Hindenburg Research,Mukesh Ambani,Đồng Khởi,Tràng Tiền,Moore AISC,PwC,Giá bất động sản,Khu du lịch Quốc tế Đồi Rồng,Cầu Ngọc Hồi,Đê tả Bùi,Đê hữu Đáy,Chương Mỹ,Gò Vấp,Nguyễn Đức Mạnh,Chris Blank,Pharmacity,Phoenix Pharma,kinh tế châu Á,Kiểm toán,GIC,KKR,Nút giao Đầm Nhà Mạc,Vũ Đình Ánh,Cao tốc Bảo Lộc - Liên Khương,Nguyễn Công Long,Wu Chongbo,dự án sân golf Glory,MDA G&C,MDA,Liên minh Châu ÂU,Đức,Lê Thành Long,Zhong Shanshan,PV Power,Trump Media & Technology,TruthFi,Tòa 29 Liễu Giai,APM Luxe,James Howells,Nguyễn Thị Mai Thanh,Alain Xavier Cany,Gary Gensler,Doanh nghiệp Nhà nước,Trần Công Thành,Hòa Bình Takara,YTL Corporation Berhad,Francis Yeoh Sock Ping,Như Anh Investment,Fico-YTL,Pacific Airlines,MSH,HTL,Tiêu dùng,PCEPI,Bất động sản Quảng Bình,Chung cư Quảng Bình,Nguyễn Quốc Anh,Khu kinh tế Dung Quất,Nguyễn Thành Trung,Sky Mavis,Axie Infinity,AXS,Vietnam Listed Company Awards,VLCA,VDS,Olaf Scholz,Dự trữ ngoại hối,Thuế chuyển nhượng bất động sản,Bộ Chính trị,Dự án điện hạt nhân Ninh Thuận,Lương lao động Việt Nam,Đánh thuế bất động sản,Gaw Capital,Nguyễn Thị Như Loan,Nguyễn Quốc Cường,Jack Smith,Thuế tiêu thụ đặc biệt,VBA,Tỷ lệ an toàn vốn,CAR,Chairatchakarn,Đấu giá đất Thạch Thất,Luật thuế thu nhập cá nhân,Cắt lỗ chứng khoán,George Soros,Jesse Livermore,Căn hộ hạng sang,Đồng Nai,Đấu giá đất Đồng Nai,Sembcorp,Frasers Property Vietnam,VIS Rating,Quyền chọn bán,Put option,Xuất khẩu Việt Nam,Thyssenkrupp Steel,Thuế VAT,Thuế giá trị gia tăng,Bắc Giang,Phân khu số 4 thị xã Việt Yên,Bulgaria,Rumen Radev,Lee Jae-yong,Gazprombank,Trần Văn An,Bùi Thị Thanh Hương,Tạ Kiều Hưng,Võ Thị Thùy Dương,Vương Quốc Tuấn,Lương Thị Cẩm Tú,Luật Các tổ chức tín dụng,Nguyễn Quốc Hiệp,Đấu giá đất Yên Mỹ,VCCI,World Business Outlook Awards,Việt Nam SuperPortTM,Yap Kwong Weng,24X National Exchange,Nguyễn Hoàng Hải,Thuế thu nhập doanh nghiệp,Nghị định 88,Phạm Văn Hòa,Nguyễn Văn Thắng,``,MPC,Venezuela Maduro,Venezuela,NetChoise,Gỗ Trường Thành,Nguyễn Trọng Hiếu,VPMS,Golf Thiên Đường,SeASecurities,Thủy sản Minh Phú,Toshifumi Suzuki,Trương Thành Nam,Thaco Chu Lai,Thaco Auto,BMW Golf Cup,Luật Quản lý thuế,Tiền số,Trương Gia Bình,Cường Đô la,Nguyễn Lê Thăng Long,S&P500,Protege Partners,ROX Group,ROX iPark,Lê Trí Thông,Đặng Thị Lài,Jessica Chen,Kỹ năng mềm,Alphabet,Võ Huỳnh Tuấn Kiệt,SWIFT,CBDC,Lê Tiến Dũng,Temu,BoK,Morgan Stanley,Nguyễn Quốc Việt,TCSC,Fideco,FDC,7-Eleven,Alimentation Couche-Tard,Đèo Cả,CFHEC,Tập đoàn Xây dựng Thái Bình Dương,Bão Milton,Fitch Ratings,Morningstar,TTC AgriS,Nguyễn Văn Đính,Bamboo Capital,Nguyễn Hồ Nam,Phạm Đăng Khoa,Daewoo,Daewoo E&C Vina,Thái Bình,Phát triển THT,Lai Châu,Điện Biên,GRDP,SBT,Nhà đất TP.HCM,Bất động sản TP.Vinh,Pinduoduo,Colin Huang,Giá điện,Kinh tế Hàn Quốc,Chứng khoán Hòa Bình,Trần Mỹ Linh,GGG,Stella Mega City Cần Thơ,kiều hối,Chỉ thị 24,Hưng Việt,CRV,TCH,Đỗ Hữu Hạ,Golden Land Building,HSBC,Baemin,Doanh nhân Việt Nam,Lý Cường,Napas,ETF Bitcoin,Vũ Hiền,CSI 300,Hoàng Tranh,Indonesia,UnionPay International,Thanh toán quốc tế,Shein,Cybercab,Cuộc chiến Nga - Ukraine,Chiến tranh tiền tệ,Sổ cái phân tán,Quasi-Blockchain,tranh cử Mỹ,MVN,Alfred Nobel,Giải Nobel Kinh tế,Claudia Goldin,DLT,Kelly Ortberg,Đỗ Hữu Hậu,HHS,InvestingPro,Chu Thị Lụa,Lý Anh Tuấn,PineTree,Vietam Airlines,Sổ đỏ,ECOLIVES,Bất động sản Đà Lạt,GDP Trung Quốc,CIPS,SPFS,Daron Acemoglu,Simon Johnson,James A. Robinson,Kinh tế Singapore,GDP Singapore,Masteri Grand Avenue,Hyosung,Bất động sản Phát Đạt,ADNOC,CERC,Trần Vân,Chen Yun,Tổng cục Thuế,Nợ thuế,Bất động sản Nhơn Trạch,Bất động sản Long Thành,Luật Đầu tư theo phương thức đối tác công tư,NO1,DBD,Huỳnh Thanh Phong,Nguyễn MInh Tuấn,Lâm Kim Khôi,Luật Đầu tư công,Dự án quan trọng quốc gia,BVH,Trần Thị Diệu Hằng,Nguyễn Xuân Việt,Huế,Thành phố trực thuộc trung ương,Thung lũng Silicon,Black Friday,tài sản số,NFT,Bộ Thương mại Mỹ,Thuốc lá điện tử,Bộ Y tế,Vinataba,Kash Patel,Bộ Xây dựng,PVS,Vietnam Investment Property Holding Limited,Bất động sản Đông Anh,Đất nền Đông Anh,Đô thị Long Thành,Khu đô thị sinh thái số 4 xã Thịnh Minh,Hòa Bình,Rác thải điện tử,Khai thác vàng,Vinhomes Vũ Yên,Sông Tô Lịch,Đất nông nghiệp,Nhà ở thương mại,Bán đảo Quảng An,KDI Holdings,Trần Thanh Hiền,Phạm Thanh Thọ,Big C Thăng Long,GO! Thăng Long,Giá dầu thô,WTI,Tiền sử dụng đất,Hataphar,DHT,ASKA Pharmaceutical,Lee Ark Boon,BSC Research,FTSE ETF,VNM ETF,cảng biển Việt Nam,EZ Property,Phạm Đức Toản,Bard,Hunter Biden,DMM Bitcoin,ABIC,ABI,Brazil,Nam Phi,Dmitry Peskov,Bất động sản hạng sang,Mua nhà,Ba Lan,VASEP,xuất khẩu thủy sản,EVFTA,Bất động sản khu công nghiệp,Lê Tuấn Khang,ELC,Elcom,IVB,xAI,Twitter-X,Tàu đệm từ,Cargill,Nguyễn Thị Dịu,Pharmedic,PMC,CSI 2024,Cao tốc TP.HCM - Long Thành - Dầu Giây,Nhà tái định cư,Iraq,HOSE,Kinh tế Nga,Yoon Suk Yeol,Phạm Ngọc Tuấn,Stefan Thomas,Vinashin,Nyobolt,Nhà ở xã hội Ninh Bình,Google Việt Nam,Jeff Bezos,FTSE Vietnam Index,IDV,Hoàng Đình Thắng,Nguyễn Thị Kiến,VCA,VTJ,IIP,sản xuất công nghiệp,PHR,Hoàng Anh Tuấn,ByteDance,Sunshine Sky Villa,Đỗ Văn Trường,Nhà đất Hà Nội,Gemini,Plaza Accord 2.0,Nghị định 100,Thuế thu nhập cá nhân,Thưởng Tết,Nova Final Solution,Khu công nghiệp Dốc Đá Trắng,Giấy chứng nhận quyền sử dụng đất,Chứng chỉ quy hoạch,Gia hạ quyền sử dụng đất,Đất đang tranh chấp,OGC,Lê Vũ Hải,Gen Z,Golden West,The Legacy,Starcity,Sông Đà - Việt Đức,Starcity Center,ZTE,Trần Hồ Bắc,Lê Mạnh Cường,PTSC,Hanosimex,HSM,Yoon Suk-yeol,TVS,Ban Kinh tế Trung ương,Đấu giá đất Hoàng Mai,Coteccons,CTD,Hudland,HLD,Bộ Giáo dục và Đào tạo,Eric Trump,Công viên hồ Phùng Khoang,VNeID,AGI,ChatGPT-o1,China Pingan Insurandce,Nghiêm Giới Hòa,DHG,DHG Pharma,Bà Rịa - Vũng Tàu,Đấu giá đất Bà Rịa - Vũng Tàu,Đấu giá đất Nam Định,SSISCA,VLGF,SSIBF,SSIAM,Ninh Bình,VPL,Khu du lịch Làng Vân,Khu công nghiệp Phú Xuân,Evergrande,Hứa Gia Ấn,BlackRock,Ngành du lịch,Phạm Đức Ấn,SIA,VEC,Lãi suất liên ngân hàng,Đường vành đai 1,MBV,Vũ Thành Trung,Lê Xuân Vũ,IATA,Vicasa,VNSTEEL,Mr Pips,Lê Khắc Ngọ,Khu du lịch văn hóa và nghỉ dưỡng Lạc Thủy,Artex Vina,Phó Đức Năm,Lê Hoài Ân,Thông tư 02,Đường sắt Thủ Thiêm - Long Thành,Đường trục phía Nam,Đấu giá đất Hưng Yên,Luật Lao động,Nguyễn Đức Chi,Cầu Thượng Cát,Trần Sỹ Thanh,FBI,AFX,Đặng Quang Thái,Khu công nghệ cao Hà Nam,Vĩnh Phúc,Bosch,Cao tốc Biên Hoà-Vũng Tàu,Đấu giá đất Long Biên,Bảo hiểm xã hội,Cán cân thương mại,Phân khu đô thị Sóc Sơn khu 3,Trần Ngọc Phương Thảo,Cao Thị Ngọc Dung,WGC,Trần Phương Ngọc Thảo,TTL,KDI,Đấu giá đất Ninh Bình,Keppel,CNC,VPD,Walton,Yandex,Mạng lưới Liên minh AI,NPC,Nhựa Tiền Phong,Trái phiếu doanh nghiệp,Đấu giá đất Mê Linh,Đấu giá đất Quốc Oai,Trung Linh Phát,Anh Phát Corp,Baidu,Jiyue,Tuyến đường sắt TP. HCM - Cà Mau,LSE,VPS SmartOne,Ninh Thuận,Sân golf,Huy Dương Group,Đầu tư MST,MST,EQT Private Capital Asia,The CrownX,Khu đô thị Vinaline,Khu đô thị Hồng Thái,Vinalines,Sông Đà,Slovakia,Great Wall Motor,Cao tốc Bắc - Nam,Cao tốc Cần Thơ - Cà Mau,Trần Hoàng Sơn,Vũ Chiến Thắng,Phạm Thế Anh,Narayana Murthy,Khu đô thị đường 3/2,NSH Petro,Mai Văn Huy,PSH,Đặng Quốc Dũng,KTT,TKG,Môi giớ bất động sản,Môi giới bất động sản,Đất nền Đà Nẵng,Big Tech,Phạm Thị Thanh Trà,Ban Chỉ đạo,IDP,Phạm Toàn Vượng,Phòng chống lãng phí,Đấu giá đất Lào Cai,ACBS,SoftBank,Masayoshi Son,Techcombank Priority,lạm phát thực phẩm,Honda,Mitsubishi Motors,MSR,H.C. Starck Holding,Trung tâm tài chính,Đất dịch vụ,Cụm công nghiệp Cát Hiệp,BHYT,Luật Bảo hiểm y tế,Đấu giá đát,Khu đô thị 3/2,Real,Khủng hoảng kinh tế,Nguyễn Đình Vinh,CIC,Tín dụng bất động sản,Mag7,Sắp xếp bộ máy,TTG,May Thanh Trì,Fast+,Công viên Logistics Viettel Lạng Sơn,Hanoimilk,HNM,CTCP Hoàng Mai Xanh,Hà Quang Tuấn,Dot plot,LUNC,Terra Classic,Terra,SAGS,SGN,Seaprodex,Đấu giá đất Thường Tín,Bộ Nông nghiệp và Môi trường,PCE,Lãi suất điều hành,MIK Group,Kohl’s,Tồn kho bất động sản,CBR,Sundar Pichai,Đấu giá đất Bắc Giang,Đường sắt Lào Cai - Hà Nội - Hải Phòng,WHO,Anh trai vượt ngàn chông gai,Larry Ellison,Tấn công có chủ đích APT,Tấn công mạng,Vĩnh Long,Hà Tĩnh,Tài sản công,Brian Brooks,Hải quan Việt Nam,Cắt giảm nhân sự,Renault,Mitsubishi,TON,Đấu giá đất Hai Bà Trưng,Tiền gửi thanh toán,Nguyễn Việt Đức,Becamex Bình Phước,Khu công nghiệp và dân cư Bình Phước,Chuyển đổi số,Mạng xã hội,Sân bay Tây Ninh,One Mount Group,Khủng hoảng tài chính,BPI,The Body Shop,HNX,HMD,BMK,Mèo đen,Hoá chất Minh Đức,Công ty TNHH Dịch vụ Thương mại Tổng hợp An Thịnh,CTCP Phát triển và Đầu tư Kinh doanh Minh An,An Thịnh,Minh An,Đấu giá nhà,SJC,Đào Công Thắng,Bộ Tư pháp,Khu đô thị Sóc Sơn khu 5,ROX Key,TN1,Xiangdixian,Altcoin,Ng Teck Yow,Nguyễn Mỹ Hạnh,Bitcoin Dominance,Bệnh viện TNH Lạng Sơn,Khu chức năng đô thị ĐM1,Bùi Ngọc Dương,Nguyễn Việt Thắng,FLC Premier Parc Đại Mỗ,Cao tốc Quy Nhơn - Pleiku,Walmart,Web Agents,VLB,ICN,TRA,TDH,VMC,Khu công nghiệp Phước Bình 2,Hồ Vân Long,DeFi,Ethereum,IPO,Kinh tế Ấn Độ,Chiến tranh thế hệ 6,Chiến tranh bản vị,Đấu giá đất Vĩnh Phúc,WB,Châu Phi,Franc CFA,Peter Navarro,Luật Đấu giá tài sản,Sân vận động Thái Nguyên,MiCA,Klaas Knot,Amazon Haul,CFTC,Cầu Cát Lái,DeepSeek,GAB,Trịnh Quốc Thi,IBIT,Gold Shares,PHS,Chứng khoán Phú Hưng,Đấu giá đất Huế,Đấu giá đất Bình Định,OECD,Bán dẫn,HURC,Ruble kỹ thuật số,Digital Ruble,Công chức viên chức,Cắt lỗ chung cư,Xác thực sinh trắc học,Khu công nghiệp Hòa Ninh,Solana,Bernstein,Ngành sản xuất,USDT,Yuxing Technology,HAGL Agrico,HNG,Đấu giá đất Quy Nhơn,MPI,Cao tốc Diễn Châu - Bãi Vọt,Cao tốc Can Lâm - Vĩnh Hảo,Cao tốc Cam Lâm - Vĩnh Hảo,Mexico,Bình Dương,Luxembourg,LDP,DCVFM VN30 ETF,Đấu giá đất Thanh Hoá,Hiroto Kiritani,Evergreen Marine,Cảng tổng hợp Nam Cửa Việt,Quảng Trị,Khu công nghiệp Vĩnh Thạnh (giai đoạn 2),Quốc lộ 51,Tái cơ cấu doanh nghiệp nhà nước,Đề án cơ cấu lại doanh nghiệp Nhà nước,Thuế nhập khẩu,Thuế GTGT,Việt Nam,Bitmain,Đào coin,Cango,State Grid,Naver,Kakao,Dự trữ Bitcoin,Khu công nghiệp Xuân Cẩm - Hương Lâm,S-Dragon,Giá vé máy bay,Bóng đá Việt Nam,Bolt,VNeTraffic,Steve Jobs,Lợi nhuận ngân hàng,Khu công nghiệp Võ Lao,Trương Ngọc Dũng,Bùi Quang Anh Vũ,Nguyễn Khắc Sinh,Michael Barr,Broadcom,Nguyễn Xuân Son,JPY,DYX,Thủ tướng Canada,Justin Trudeau,Nguyễn Cảnh Tĩnh,Aristino,Cocolux,HMPV,Covid-19,CATL,AI Agent,Agentic AI,Cải tạo chung cư cũ,Thuế thương mại điện tử,Fidelity,tokenization,CNB,Brian Quintenz,Cynthia Lummis,Azure,Khu công nghiệp Yên Bình 3,Central Group,Siam Commercial Bank,Agoda,Booking,Paypal,Già hóa dân số,Dân số Việt Nam,Nông nghiệp Kon Thụp,Bộ Kế hoạch & Đầu tư,KienlongBank,Sân bay Côn Đảo,Nguyễn Đức Lệnh,Phạm Thị Nguyên Thanh,Nhà đất Đông Anh,Shinsegae Group,Đấu giá đất Hà Nam,Nhà máy điện gió Savan 1,Cà Mau,Xổ số,Visa,DOJ,Silk Road,VinMotion,ETF S&P 500,ETF QQQ,Bộ Tài chính Mỹ,Dầu khí,NSC,HVT,SEB,Khu công nghiệp Phụng Hiệp,Khu công nghiệp Bắc Thường Tín,MobiFone,Đất nền Củ Chi,Đất nền Hóc Môn,Luc Frieden,NCPPR,Greenland,Đan Mạch,Bế Công Sơn,Thuduc House,Hoàng Anh Phúc,Soros Fund Management,Bộ trưởng Tài chính Mỹ,Trái phiếu kho bạc Mỹ,Bộ máy Chính phủ,WTO,2MoreBits,VFF,USDC,Polymarket,Singapore Pools,Dự án nhiệt điện Long Phú 1,Maha Kumbh Mela,NFTtrace,Lê Anh Tuấn,Đấu giá đất Điện Biên,Xử lý tài sản công,Phát Đại Cát,Nguyễn Kim,Khu công nghiệp Tràng Duệ 3,RedNote,AEON Financial,FLC Faros,Nguyễn Phương Linh,Nguyễn Công Lãi,Trần Nhất Minh,Khu công nghiệp Nam Tràng Cát,Portland,San Francisco,Intesa Sanpaolo,Tân Hoàng Minh,Khu du lịch nghỉ dưỡng sinh thái Bàu Sen,Đỗ Anh Dũng,Nhiệt điện Hải Phòng,HND,Đánh thuế đầu cơ bất động sản,Panama,Nguyễn Văn Kim,VTV,Táo quân,Wells Fargo,Mô hình kinh doanh hệ sinh thái,Sui,Cardano,Khu công nghiệp Hưng Đạo,Novaworld Phan Thiết,Bình Thuận,Kem Tràng Tiền,OCH,Nguyễn Đức Minh,Crypto Hub,XRP,LTC,Vivo,Bất động sản Hà Nam,Khu kinh tế ven biển phía Nam Hải Phòng,Chu Thị Thành,Thiên Minh Đức,Khu đô thị Đông Nam Tiên Sơn,Khu đô thị sinh thái số 4,Chu Đăng Khoa,TRUMP,CIC Digital,Khu đô thị Thanh Hà,Cienco5,Cảng Phú Mỹ,$Trump,BNB,FPTS,Klaus Zellmer,Tổng thống Mỹ,FACA,Liobank,Chung cư Bình Dương,Trái phiếu bất động sản,Bất động sản Vĩnh Phúc,EVS,Khu công nghệ cao TP. HCM,SAM,SAM Holdings,Dự án tuyến đường bộ ven biển Thái Bình,Mark Uyeda,SSI Digital Ventures,SSID,Tether,VCBNeo,Khu công nghiệp Song Mai - Nghĩa Trung,Khu công nghiệp Hà Nội - Bắc Giang,Thủ Đức,EI Salvador,Caroline Pham,Đường Vành đai 3 Hà Nội,Trung tâm tài chính quốc tế,Ngân sách Nhà nước,Hester Peirce,Tây Ban Nha,CBOJ,Downside Protected Bitcoin ETF,LINK,Gilimex,GIL,SSI Digital,Kompa Group,Filum,Vòng Thanh Cường,Trần Văn Viển,ADA,GDPR,Big Data,Shinhan Finance,HD Saison,Mcredit,SOL,Be Group,Sàn giao dịch carbon,Tín chỉ carbon,Khu công nghiệp HD,Wooribank,Oracle,Hoàng Trung Nghĩa,LayerZero,Bất động sản Đan Phượng,SCBX,Cao tốc TP. HCM - Thủ Dầu Một - Chơn Thành,Lương Văn Phong,DeepSeek R1,OpenAI o1,Fansipan,Sun World Fansipan Legend,Kho bạc Nhà nước,Truth.Fi,Trump Media,Đô thị Vạn Ninh,Crypto.com,Trịnh Văn Tuấn,Mai Kiều Liên,du lịch,ETF Crypto,TinyZero,BharatTradeNet,Hash function,Hash rate,Proof of work,Cầu Nhơn Trạch,Bất động sản Mỹ,DXY,USAID,Ngành ô tô,Phạm Thị Nhung,Quỹ tài sản có chủ quyền,Solana ETF,Quỹ đầu tư quốc gia,DRH,DRH Holdings,Goertek,DCA,Eugene Fama,Tiền mặt,Giao dịch tiền mặt,UNCAC,Tiền ảo,KyberSwap Elastic,Trần Huy Vũ,Crypto Task Force,Tuyến đường tỉnh 773,TAO,Etherfi,Bittensor,ETHFI,Nguyễn Thị Việt Hà,Cao tốc Hòa Bình - Mộc Châu,TP. HCM,Tết Nguyên đán,Strategy,Cầu đường Nguyễn Khoái,Đường Vành đai 2 TP. HCM,Bybit,Kinh tế thế giới,Argentina,BKC,KSV,BOT,Chính sách "3-3-3",VMD,Doanh nghiệp Việt Nam,Metro Cần Giờ,SET Index,ICC,Monbay Vân Đồn,Kraken,Peter Todd,HDMon Group,HDMon Holdings,Bitcoin Act 2024,Nguyễn Thị Hoa,Phố Wall,FDIC,Mức giảm trừ gia cảnh,Vành đai 5,Bộ Tài chính,Nguyễn Thị Nga,Lê Tuấn Anh,IMF,Vạn Phát Hưng,VPH,Huỳnh Bích Ngọc,Nguyễn Thanh Ngữ,WLFI,Giới siêu giàu Trung Quốc,Lã Quang Bình,ECPay,Đào Hoàng Thắng,EIN,Bất động sản Hải Phòng,TCGIns,Bảo hiểm Techcom,Giá vàng nhẫn,Cấn Văn Lực,Bộ Giao thông vận tải,Tuyến metro số 1 Bến Thành - Suối Tiên,Gelex,GEX,PXL,Khu công nghiệp Dầu Khí Long Sơn,Bản đồ địa chính,Hạ tầng Gelex,Cadastral Map,Passion Investment,Lã Giang Trung,Chỉ giới xây dựng,Luật xây dựng,Căn hộ thô,Bất động sản Nhật Bản,Manulife,Landlocked,Khu biệt lập,Khu đô thị Hồ Tuổi trẻ,PNI Việt Nam,Nguyễn Thanh Hải,Tạ Thị Minh,Trần Mạnh Tưởng,Chỉ số giá bất động sản,Composite Capital Master Fund LP,đất nền,Công Minh,Sun Group,FHS,Fahasa,Giao dịch khối ngoại,Khối ngoại mua ròng,Nhà ở xã hội,Vũ Minh Khương,ETC,VPBank,VPB,Opes,Ngô Chí Dũng,VPBankS,Chứng khoán hôm nay,NAB,MSCI Frontier Market Index,BWE,DLG,Vinhomes,VHM,VIC,VRE,Vincom Retail,Phạm Nhật Vượng,OCB,TNH,PVPower,Khu phức hợp Tháp quan sát Thủ Thiêm,Antimony,Hà Nam,KRW,Coinbase,Canada,FLC Legacy Kon Tum,Luật cán bộ, công chức,công chức,Bộ Nội vụ,Đà Nẵng,Hyndai,Mark Zuckerberg,UnitedHealthcare,Brian Thompson,ESG,Pháp,Michel Barnier,Hải Phòng,Paul Atkins,Tô Thị Dựa,Couche-Tard,Trần Minh Tiến,Châu Âu,DeepMind,Fomo,Đào Xuân Dũng,Egroup,Nguyễn Ngọc Thuỷ,Egame,Trái phiếu Chính phủ,Bùi Hải Huyền,Vũ Anh Tuân,Cảng Cà Ná,VCS,Khu đô thị Hiệp Hoà,David Sacks,Nhà đất Hóc Môn,Nhà đất Củ Chi,Tạ Thị Thanh Bình,Bộ Kế hoạch và Đầu tư,Bảo hiểm,VSDC,Ohno Keiji,Tập đoàn đường sắt đô thị,TP.HCM,thức ăn chăn nuôi,Tổng cục Hải quan,SERC,Campuchia,Giá xăng hôm nay,Chứng khoán Navibank,NVS,Phan Anh Tuấn,Chi ngân sách,Cát Lái,Kim Yong-hyun,Thiết quân luật,Tinh giản biên chế,gia lai,Nam A Bank,MSN,Chứng khoán mã hóa,USDe,USDS,SEC Philippines,mBridge,Agorá,BIS,Sữa quốc tế Lof,Đoàn Hữu Nguyên,tài sản mã hoá,tài sản ảo,Thuế tài sản mã hóa,KCN Nam Tân Uyên,Sơn La,Tài chính tiêu dùng,Hyundai,Đặng Đình Tuấn,Lũng Lô,Basel,CRR 3,Định giá đất,Giá đất,Thaigroup,Đặng Minh Trường,Metro nối TP HCM và sân bay long Thành,Nhà ở xã hội KCN Tân Trường,AFC VF Limited,Nhà máy điện gió Đông Hải 13,AMD,ETP Ethereum,VN50 GROWTH,Chỉ số VN50 GROWTH,VNMITECH,Chỉ số VNMITECH,VNMITECH TRI,Chỉ số VNMITECH TRI,Gelex Infra,Hyundai Rotem,Paynet Coin,PAYN,Red Capital,Eurowindow,Eurowindow Nha Trang,Nguyễn Cảnh Sơn,Đoàn Hoàng Nam,Forrest Li,Nguyễn Văn Hạ,Shinkansen,Shinkanse,Lê Thái Sâm,Trương Phương Thành,TCEX,Gia cầm Hòa Phát,Khu thương mại tự do Cần Giờ,Khu thương mại tự do Cái Mép Hạ,Khu thương mại tự do Bàu Bàng,Khu thương mại tự do An Bình,NDT,Khu công nghiệp Tây Bắc Hồ Xá,Amber Industrial Parks,SK Innovation,TV2,Nhà máy Nhiệt điện Ô Môn IV,Amata,Somhatai Panichewa,Smart contract,Ví lạnh,Ví nóng,Hợp đồng thông minh,Ronin,Metro Đà Nẵng - Hội An - Chu Lai,BTT,Lê Mạnh Linh,JPYC,Stablecoin neo đồng yên,Cầu vượt sông Đáy,AGG,Da Nang Downtown,SkyM,Hải An Green Shipping Lines,PAP,Trần Thị Hiền Lương,CMB,Bithumb,Nông nghiệp Hòa Phát,Lê Thị Huyền Linh,Saigon Marina IFC,Cảng Bãi Gốc,TNR Realty,TNG Global,Container Hòa Phát,Bùi Hải Quân,Bùi Hải Ngân,Bùi Cẩm Thi,Landmark Đà Nẵng,Stanley Brothers,VUA,VCK,Chứng khoán VPS,Cao tốc Cà Mau - Đất Mũi,Cầu vượt biển,Antesco,ANT,Ylang Holdings,Phạm Ngô Quốc Thắng,Luật Đầu tư,Khu đô thị mới Thủ Thiêm,OKB,HRC,Nguyễn Thị Loan,Khu nhà xã hội phường An Dương,Khu du lịch Biển Hồ – Chư Đăng Ya,Khung giá đất,PDD Holdings,Citadel Mining,Basal Pay,KCN Long Đức 3,KN Investment Group,KN Investment,Vũ Đức Tiến,RCC,VRCC,Tổng công ty Công trình Đường sắt Việt Nam,SSG Group,Nguyễn Hồng Phương,ROX Energy,Nguyễn Thị Nguyệt Hường,GCUL,CME Group,VIXEX,FTG,PYTH,Nguyễn Văn Đạt,IST,DRL,JBSV,JB Financial Group,Kwangju,DCJPY,Japan Post Bank,Token hóa tiền gửi,Thông tư 14,ETP Bitcoin,Đồng yên,Peter Kerstens,$WLFI,Nguyễn Văn Hải,Đầu tư Đông Ngàn,Becamex IJC,IJC,Chainalysis,Nghị quyết 70,Luật Điện lực 2024,FIEA,HAN,Piero Cipollone,Genfarma,Genfarma Holdings,BioCubaFarma,Đoàn  Thái Sơn,Nhà ở xã hội Long Vân 1,Nhà ở xã hội Phú Lãm,Sumec,Sun Grand City Cầu Giấy,Việt Minh Hoàng,Giá điện hai thành phần,Rowe Price,MYX,ICBC,Giwa,WCM,Giá thép,Nguyễn Vân Hiền,Nhà đầu tư F0,AntChain,RBI,Khung pháp lý tài sản số,Nghị định 245,Coin98,Coin98 Wallet,Ví không lưu ký,Trung tâm logistics,Giá bạc,Bạc,USAT,KyberSwap,Holdstation,Viction,Jeff Yan,Masan,Quốc Cường Gia Lai,QCG,VN-Index,Đấu giá đất thanh oai,Đấu giá đất,Tăng lương cơ sở,Phó Đức Nam,Năng lượng tái tạo,VinFast,VietinBank,Tổng thu ngân sách Nhà nước,Altcoin season,Review bẩn,Yuanta Việt Nam,Phạm Việt Hà,OCB OMNI,Trần Cẩm Tú,Đất hiếm,Lumi Hanoi,Vingroup,Phòng chống tham nhũng,Eximbank,ROX,Vipo Mall'.split(',');
                                $("#txtTags").select2({
                                    tags: data,
                                    closeOnSelect: true
                                });

                                //$("#Title").emojioneArea({
                                //    pickerPosition: "bottom",
                                //    filtersPosition: "bottom",
                                //});

                                bindEditor();
                            </script>





                        </div>

                    </div>
                    <div class="modal-footer"></div>
                </div>
            </div> 
        </div> -->
        <!-- <div role="dialog" aria-modal="true" aria-labelledby="swal2-title" aria-describedby="swal2-content" class="swal2-popup swal2-modal swal2-show" tabindex="-1" aria-live="assertive" style="width: 500px; padding: 20px; background: rgb(255, 255, 255); display: flex;">
            <ul class="swal2-progresssteps" style="display: none;"></ul>
            <div class="swal2-icon swal2-error" style="display: none;"><span class="swal2-x-mark"><span class="swal2-x-mark-line-left"></span><span class="swal2-x-mark-line-right"></span></span></div>
            <div class="swal2-icon swal2-question" style="display: none;">?</div>
            <div class="swal2-icon swal2-warning" style="display: none;">!</div>
            <div class="swal2-icon swal2-info" style="display: none;">i</div>
            <div class="swal2-icon swal2-success" style="display: none;">
                <div class="swal2-success-circular-line-left" style="background: rgb(255, 255, 255);"></div><span class="swal2-success-line-tip"></span> <span class="swal2-success-line-long"></span>
                <div class="swal2-success-ring"></div>
                <div class="swal2-success-fix" style="background: rgb(255, 255, 255);"></div>
                <div class="swal2-success-circular-line-right" style="background: rgb(255, 255, 255);"></div>
            </div><img class="swal2-image" style="display: none;">
            <div class="swal2-contentwrapper">
                <h2 class="swal2-title" id="swal2-title"></h2>
                <div id="swal2-content" class="swal2-content" style="display: block;">Bạn đã đăng bài thành công</div>
            </div><input class="swal2-input" style="display: none;"><input type="file" class="swal2-file" style="display: none;">
            <div class="swal2-range" style="display: none;"><output></output><input type="range"></div><select class="swal2-select" style="display: none;"></select>
            <div class="swal2-radio" style="display: none;"></div><label for="swal2-checkbox" class="swal2-checkbox" style="display: none;"><input type="checkbox"></label><textarea class="swal2-textarea" style="display: none;"></textarea>
            <div class="swal2-validationerror" id="swal2-validationerror" style="display: none;"></div>
            <div class="swal2-buttonswrapper" style="display: flex;"><button type="button" class="swal2-confirm swal2-styled" aria-label="" style="background-color: rgb(48, 133, 214); border-left-color: rgb(48, 133, 214); border-right-color: rgb(48, 133, 214);">OK</button><button type="button" class="swal2-cancel swal2-styled" aria-label="" style="display: none; background-color: rgb(170, 170, 170);">Cancel</button></div><button type="button" class="swal2-close" style="display: none;">×</button>
        </div> -->

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
