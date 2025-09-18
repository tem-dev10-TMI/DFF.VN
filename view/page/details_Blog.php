<?php
// $article should be provided by Controller similar to view/page/detail_blog.php
// Fallbacks
$authorName = htmlspecialchars($article['author_name'] ?? 'Tác giả');
$authorId = isset($article['author_id']) ? intval($article['author_id']) : 0;
$authorAvatar = $article['author_avatar_url'] ?? '/vendor/dffvn/content/img/user.svg';
if (!$authorAvatar || trim($authorAvatar) === '') {
    $authorAvatar = 'https://i.pravatar.cc/100?u=' . urlencode($authorName);
}
$createdAt = isset($article['created_at']) ? date('d/m/Y H:i', strtotime($article['created_at'])) : '';
$title = htmlspecialchars($article['title'] ?? '');
$summary = nl2br(htmlspecialchars($article['summary'] ?? ''));
$content = nl2br(htmlspecialchars($article['content'] ?? ''));
$mainImage = $article['main_image_url'] ?? '';
?>

<main class="main-content">
    <div class="block-k">

        <div class="d-topinfo">
            <div class="provider">
                <a href="javascript:void(0)" class="img-news"><img class="logo" alt="" src="<?= htmlspecialchars($authorAvatar) ?>"></a>
                <div class="p-covers">
                    <span class="name" title="">
                        <a href="<?= BASE_URL ?>/view_profile?id=<?= $authorId ?>" title="<?= $authorName ?>"><?= $authorName ?></a>
                        <i title="Đã xác thực" class="accu_none fas fa-check-circle"></i>
                    </span><span class="date"><?= htmlspecialchars($createdAt) ?></span>
                </div>
            </div>

            <div class="follower-r f-right" style="font-size: 13px;"><b class="total-fol">0</b> Người theo dõi</div>
        </div>

        <div class="detail">
            <div class="line"></div>

            <h1><?= $title ?></h1>

            <article>
                <div class="dcontent">
                    <?php if (!empty($summary)): ?>
                        <p><?= $summary ?></p>
                    <?php endif; ?>

                    <?php if (!empty($mainImage)): ?>
                        <figure><img src="<?= htmlspecialchars($mainImage) ?>" alt="<?= $title ?>"></figure>
                    <?php endif; ?>

                    <?php if (!empty($content)): ?>
                        <p><?= $content ?></p>
                    <?php endif; ?>

                    <div class="bysource"></div>
                </div>

                <div class="box-trends" bis_skin_checked="1">
                    <h5>
                        <a href="#" title="360° Doanh nghiệp">Nội dung liên quan </a>
                    </h5>
                    <ul>

                        <li><a href="/hyperliquid-vuot-moc-1500-ty-usd-khoi-luong-giao-dich-phai-sinh-p20250630152347523.html" title="Hyperliquid vượt mốc 1.500 tỷ USD khối lượng giao dịch phái sinh">Hyperliquid vượt mốc 1.500 tỷ USD khối lượng giao dịch phái sinh</a>
                        </li>


                    </ul>
                </div>

                <div id="anc_comment" style="padding-bottom:20px;"></div>

                <div class="d-tags">
                    <ul>
                        <li><i class="fas fa-tags"></i></li>
                        <?php if (!empty($article['tags']) && is_array($article['tags'])): foreach ($article['tags'] as $tag): ?>
                                <li><a href="/search.html?q=<?= urlencode($tag) ?>"><?= htmlspecialchars($tag) ?></a></li>
                        <?php endforeach;
                        endif; ?>
                    </ul>
                </div>

            </article>
            <input type="hidden" id="hdd_id" value="<?= htmlspecialchars($article['id'] ?? '') ?>">

            <div data-id="<?= htmlspecialchars($article['id'] ?? '') ?>" data-type="1" class="box-n-sc">

                <div class="item-bottom">
                    <div class="bt-cover com-like" data-id="<?= htmlspecialchars($article['id'] ?? '') ?>">
                        <span class="for-up">
                            <svg rpl="" class="" data-voted="false" data-type="up" fill="currentColor" height="16" icon-name="upvote-fill" viewBox="0 0 20 20" width="16" xmlns="http://www.w3.org/2000/svg">
                                <path d="M18.706 8.953 10.834.372A1.123 1.123 0 0 0 10 0a1.128 1.128 0 0 0-.833.368L1.29 8.957a1.249 1.249 0 0 0-.171 1.343 1.114 1.114 0 0 0 1.007.7H6v6.877A1.125 1.125 0 0 0 7.123 19h5.754A1.125 1.125 0 0 0 14 17.877V11h3.877a1.114 1.114 0 0 0 1.005-.7 1.251 1.251 0 0 0-.176-1.347Z"></path>
                            </svg>
                        </span>
                        <span class="value" data-old="0">0</span>
                        <span class="for-down">
                            <svg rpl="" class="" data-voted="false" data-type="down" fill="currentColor" height="16" icon-name="downvote-fill" viewBox="0 0 20 20" width="16" xmlns="http://www.w3.org/2000/svg">
                                <path d="M18.88 9.7a1.114 1.114 0 0 0-1.006-.7H14V2.123A1.125 1.125 0 0 0 12.877 1H7.123A1.125 1.125 0 0 0 6 2.123V9H2.123a1.114 1.114 0 0 0-1.005.7 1.25 1.25 0 0 0 .176 1.348l7.872 8.581a1.124 1.124 0 0 0 1.667.003l7.876-8.589A1.248 1.248 0 0 0 18.88 9.7Z"></path>
                            </svg>
                        </span>
                    </div>

                    <div class="button-ar sharefb" data-url="/post-<?= htmlspecialchars($article['id'] ?? '') ?>.html">
                        <i class="far fa-share-square " data-url="/post-<?= htmlspecialchars($article['id'] ?? '') ?>.html"></i><span>Chia sẻ</span>
                    </div>
                    <div class="button-ar fc-saved">
                        <i title="copy link bài viết" class="fas fa-link copylink" data-url="/post-<?= htmlspecialchars($article['id'] ?? '') ?>.html"></i>
                        <i module-load="savenews" title="lưu bài viết" class="far fa-bookmark"></i>
                    </div>
                    <div class="button-ar">
                        <i class="fas fa-exclamation-triangle"></i><span module-load="report">Báo cáo</span>
                    </div>
                </div>

            </div>

        </div>

        <div class="line"></div>
        <div class="d-bottom">
            <div class="col1">
                <div class="provider">
                    <img class="logo" alt="" src="<?= htmlspecialchars($authorAvatar) ?>">
                    <div class="p-covers">
                        <span class="name" title=""><a href="<?= BASE_URL ?>/view_profile?id=<?= $authorId ?>" title="<?= $authorName ?>"><?= $authorName ?></a>
                        </span><span class="date">Người dùng</span>
                    </div>
                </div>
                <div class="bt-foll">
                    <a href="javascript:void(0)" data-type="3" module-load="follow" data-ref="<?= $authorId ?>">
                        <val> Theo dõi</val>
                    </a>
                </div>
            </div>
            <div class="col2">
                <div class="provider">
                    <span class="cus-avatar">D</span>
                    <div class="p-covers">
                        <span class="name" title=""><a href="#" title="Chủ đề">Chủ đề</a>
                        </span><span class="date">Chủ đề</span>
                    </div>
                </div>
                <div class="bt-foll">
                    <a href="javascript:void(0)" data-type="3" module-load="follow" data-ref="<?= $authorId ?>">
                        <val> Theo dõi</val>
                    </a>
                </div>
            </div>
        </div>

    </div>
    <div class="block-k">
        <h5 class="total-cm"><i class="fas fa-comments"></i> Bình luận <span></span></h5>
        <div class="comment-box">
            <a href="javascript:void(0)" class="img-own"> <img src="/vendor/dffvn/content/img/user.svg"> </a>
            <textarea class="form-control autoresizing" placeholder=" Bạn nghĩ gì về nội dung này?"></textarea>
            <i class="fas fa-paper-plane" data-id="<?= htmlspecialchars($article['id'] ?? '') ?>" module-load="csend"></i>
        </div>

        <div class="comment-cover" style="display: none;">
            <ul class="list_comment col-md-12">
            </ul>
            <div class="cm-more">Xem thêm</div>
        </div>

        <div class="first-comment">
            <i class="far fa-comments"></i>
            <p>Trở thành người bình luận đầu tiên</p>
        </div>
    </div>
</main>