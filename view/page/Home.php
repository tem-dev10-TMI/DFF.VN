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
                                href="article_detail.php?id=<?= $article['id'] ?>">
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
            <a href="javascript:void(0)" class="img-own"> <img src="vendor/dffvn/content/img/user.svg"> </a>
            <div class="input-group box-search">
                <div class="post-input"><a href="javascript:void(0)" module-load="loadwrite"><span>Viết bài,
                            chia sẻ, đặt câu hỏi…</span></a></div>
            </div>
            <img alt="Viết bài, chia sẻ, đặt câu hỏi" module-load="loadwrite"
                src="vendor/dffvn/content/img/img_small.jpg" width="30">
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
                                                    <a href="/viewProfilebusiness.php?id=<?= $biz['id'] ?>">
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
            <?php foreach ($articles as $article): ?>
                <div class="block-k ">
                    <div class="view-carde f-frame">
                        <div class="provider">
                            <?php
                            $authorAvatar = $article['avatar_url'] ?? 'https://i.pinimg.com/1200x/83/0e/ea/830eea38f7a5d3d8e390ba560d14f39c.jpg';
                            ?>
                            <img class="logo" alt="" src="<?= htmlspecialchars($authorAvatar) ?>">
                            <div class="p-covers">
                                <span class="name" title="">
                                    <a href="/DFF.VN/view_profile_user?id=<?= $article['author_id'] ?>" title="<?= htmlspecialchars($article['author_name']) ?>"><?= htmlspecialchars($article['author_name']) ?></a>
                                </span><span class="date"> <?= timeAgo($article['created_at']) ?></span>
                            </div>
                        </div>

                        <div class="title">
                            <a title="<?= htmlspecialchars($article['title']) ?>"
                                href="/article-<?= $article['slug'] ?>-p<?= $article['id'] ?>.html"><?= htmlspecialchars($article['title']) ?></a>
                        </div>
                        <div class="sapo">
                            <?= htmlspecialchars($article['summary']) ?>
                            <a href="/article-<?= $article['slug'] ?>-p<?= $article['id'] ?>.html" class="d-more">Xem thêm</a>
                        </div>

                        <?php if (!empty($article['main_image_url'])): ?>
                            <img class="h-img" src="<?= htmlspecialchars($article['main_image_url']) ?>"
                                title="<?= htmlspecialchars($article['title']) ?>" alt="<?= htmlspecialchars($article['title']) ?>" border="0">
                        <?php endif; ?>

                        <div class="item-bottom">
                            <div class="bt-cover com-like" data-id="<?= $article['id'] ?>">
                                <span class="for-up">
                                    <svg rpl="" data-voted="false" data-type="up" fill="currentColor" height="16"
                                        icon-name="upvote-fill" viewBox="0 0 20 20" width="16"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M18.706 8.953 10.834.372A1.123 1.123 0 0 0 10 0a1.128 1.128 0 0 0-.833.368L1.29 8.957a1.249 1.249 0 0 0-.171 1.343 1.114 1.114 0 0 0 1.007.7H6v6.877A1.125 1.125 0 0 0 7.123 19h5.754A1.125 1.125 0 0 0 14 17.877V11h3.877a1.114 1.114 0 0 0 1.005-.7 1.251 1.251 0 0 0-.176-1.347Z">
                                        </path>
                                    </svg>
                                </span>
                                <span class="value" data-old="<?= $article['upvotes'] ?? 0 ?>"><?= $article['upvotes'] ?? 0 ?></span>
                                <span class="for-down">
                                    <svg rpl="" data-voted="false" data-type="down" fill="currentColor" height="16"
                                        icon-name="downvote-fill" viewBox="0 0 20 20" width="16"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M18.88 9.7a1.114 1.114 0 0 0-1.006-.7H14V2.123A1.125 1.125 0 0 0 12.877 1H7.123A1.125 1.125 0 0 0 6 2.123V9H2.123a1.114 1.114 0 0 0-1.005.7 1.25 1.25 0 0 0 .176 1.348l7.872 8.581a1.124 1.124 0 0 0 1.667.003l7.876-8.589A1.248 1.248 0 0 0 18.88 9.7Z">
                                        </path>
                                    </svg>
                                </span>
                            </div>
                            <div class="button-ar">
                                <a href="/article-<?= $article['slug'] ?>-p<?= $article['id'] ?>.html#anc_comment">
                                    <svg rpl="" aria-hidden="true" class="icon-comment" fill="currentColor"
                                        height="15" icon-name="comment-outline" viewBox="0 0 20 20" width="15"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M7.725 19.872a.718.718 0 0 1-.607-.328.725.725 0 0 1-.118-.397V16H3.625A2.63 2.63 0 0 1 1 13.375v-9.75A2.629 2.629 0 0 1 3.625 1h12.75A2.63 2.63 0 0 1 19 3.625v9.75A2.63 2.63 0 0 1 16.375 16h-4.161l-4 3.681a.725.725 0 0 1-.489.191ZM3.625 2.25A1.377 1.377 0 0 0 2.25 3.625v9.75a1.377 1.377 0 0 0 1.375 1.375h4a.625.625 0 0 1 .625.625v2.575l3.3-3.035a.628.628 0 0 1 .424-.165h4.4a1.377 1.377 0 0 0 1.375-1.375v-9.75a1.377 1.377 0 0 0-1.374-1.375H3.625Z">
                                        </path>
                                    </svg>
                                    <span><?= $article['comment_count'] ?? 0 ?></span>
                                </a>
                            </div>
                            <div class="button-ar">
                                <div class="dropdown home-item">
                                    <i class="far fa-share-square"></i><span data-bs-toggle="dropdown"
                                        aria-expanded="false">Chia sẻ</span>
                                    <ul class="dropdown-menu">
                                        <li><i class="bi bi-link-45deg"></i> <a class="dropdown-item copylink"
                                                data-url="/article-<?= $article['slug'] ?>-p<?= $article['id'] ?>.html"
                                                href="javascript:void(0)">Copy link</a></li>
                                        <li><i class="bi bi-facebook"></i> <a class="dropdown-item sharefb"
                                                data-url="/article-<?= $article['slug'] ?>-p<?= $article['id'] ?>.html"
                                                href="javascript:void(0)">Share FB</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
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
                        <script>
  // Mẫu dữ liệu bình luận
  const comments = [
    {
      name: "Minh Anh",
      time: "2 phút trước",
      text: "Bài viết này hay quá, mong admin chia sẻ thêm!",
      avatar: "https://i.pravatar.cc/40?img=1"
    },
    {
      name: "Quốc Huy",
      time: "5 phút trước",
      text: "Theo mình thì xu hướng crypto tuần này đang khá tích cực 😃",
      avatar: "https://i.pravatar.cc/40?img=2"
    },
    {
      name: "Thanh Trúc",
      time: "10 phút trước",
      text: "Mình vừa theo dõi thêm VHM, mọi người có đánh giá gì không?",
      avatar: "https://i.pravatar.cc/40?img=3"
    }
  ];

  // Lấy ul.list_comment
  const list = document.querySelector(".list_comment");

  // Render từng comment
  comments.forEach(c => {
    const li = document.createElement("li");
    li.className = "comment-item";
    li.innerHTML = `
      <div class="comment">
        <img src="${c.avatar}" alt="avatar" class="avatar">
        <div class="content">
          <strong>${c.name}</strong> <span class="time">${c.time}</span>
          <p>${c.text}</p>
        </div>
      </div>
    `;
    list.appendChild(li);
  });
</script>

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
            <div class="fb-page" data-href="https://www.facebook.com/dffvn.official" data-tabs="timeline"
                data-width="" data-height="" data-small-header="false" data-adapt-container-width="true"
                data-hide-cover="false" data-show-facepile="true">
                <blockquote cite="https://www.facebook.com/dffvn.official" class="fb-xfbml-parse-ignore"><a
                        href="../www.facebook.com/dffvn.html">DFF.VN - Mạng xã hội kinh tế tài chính </a>
                </blockquote>
            </div>
        </div>




        <div class="block-k bg-box-a">
            <div class="tieu-diem">
                <h2>

                    <i class="fab fa-hotjar"></i> DFF <span>HOT</span>
                </h2>
                <ul>

                    <li class="new-style">
                        <a title="TPBank muốn chi 3.600 tỷ đồng thâu tóm TPS, lập công ty quản lý nợ vốn 100 tỷ đồng" href="tpbank-muon-chi-3600-ty-dong-thau-tom-tps-lap-cong-ty-quanly-no-von-100-ty-dong-p20250827121341119.html">TPBank
                            muốn chi 3.600 tỷ đồng thâu tóm TPS, lập công ty quản
                            lý nợ vốn 100 tỷ đồng

                        </a>

                        <img src="../media.dff.vn/web/image/2025/8/tpbank638918936210729258.jpg" title="TPBank muốn chi 3.600 tỷ đồng th&#226;u t&#243;m TPS, lập c&#244;ng ty quảnl&#253; nợ vốn 100 tỷ đồng" alt="TPBank muốn chi 3.600 tỷ đồng th&#226;u t&#243;m TPS, lập c&#244;ng ty quản l&#253; nợ vốn 100 tỷ đồng" border="0" />
                    </li>

                    <li class="new-style">
                        <a title="Chương mới ở RCC: Cựu CEO SHS Vũ Đức Tiến làm Chủ tịch, ông chủ Kita Nguyễn Duy Kiên làm Phó Chủ tịch"
                            href="chuong-moi-o-rcc-cuu-ceo-shs-vu-duc-tien-lam-chu-tich-ong-chu-kita-nguyen-duy-kien-lam-pho-chu-tich-p2025082712131457.html">Chương
                            mới ở RCC: Cựu CEO SHS Vũ Đức Tiến làm Chủ tịch, ông chủ Kita Nguyễn Duy Kiên
                            làm Phó Chủ tịch

                        </a>

                        <img src="../media.dff.vn/web/image/2025/8/egm-2025-rcc638918935939949422.jpg"
                            title="Chương mới ở RCC: Cựu CEO SHS Vũ Đức Tiến l&#224;m Chủ tịch, &#244;ng chủ Kita Nguyễn Duy Ki&#234;n l&#224;m Ph&#243; Chủ tịch"
                            alt="Chương mới ở RCC: Cựu CEO SHS Vũ Đức Tiến l&#224;m Chủ tịch, &#244;ng chủ Kita Nguyễn Duy Ki&#234;n l&#224;m Ph&#243; Chủ tịch"
                            border="0" />
                    </li>

                    <li class="new-style">
                        <a title="NHNN bơm ròng gần 3.000 tỷ đồng tuần qua, lãi suất liên ngân hàng tăng nhẹ"
                            href="nhnn-bom-rong-gan-3000-ty-dong-tuan-qua-lai-suat-lien-ngan-hang-tang-nhe-p20250825074521390.html">NHNN
                            bơm ròng gần 3.000 tỷ đồng tuần qua, lãi suất liên ngân hàng tăng nhẹ

                        </a>

                        <img src="../media.dff.vn/web/image/2025/8/tien638917047213072765.jpg"
                            title="NHNN bơm r&#242;ng gần 3.000 tỷ đồng tuần qua, l&#227;i suất li&#234;n ng&#226;n h&#224;ng tăng nhẹ"
                            alt="NHNN bơm r&#242;ng gần 3.000 tỷ đồng tuần qua, l&#227;i suất li&#234;n ng&#226;n h&#224;ng tăng nhẹ"
                            border="0" />
                    </li>

                    <li class="new-style">
                        <a title="570.000 tỷ đồng tồn kho của loạt 'ông trùm' địa ốc"
                            href="570000-ty-dong-ton-kho-cua-loat-ong-trum-dia-oc-p20250825093523869.html">570.000
                            tỷ đồng tồn kho của loạt 'ông trùm' địa ốc

                        </a>

                        <img src="../media.dff.vn/web/image/2025/8/ton-kho-bat-dong-san638917113238077644.jpg"
                            title="570.000 tỷ đồng tồn kho của loạt &#39;&#244;ng tr&#249;m&#39; địa ốc"
                            alt="570.000 tỷ đồng tồn kho của loạt &#39;&#244;ng tr&#249;m&#39; địa ốc"
                            border="0" />
                    </li>

                    <li class="new-style">
                        <a title="Con trai 'bầu' Đức đăng ký gom thêm 25 triệu cổ phiếu HAG"
                            href="con-trai-bau-duc-dang-ky-gom-them-25-trieu-co-phieu-hag-p20250825110742354.html">Con
                            trai 'bầu' Đức đăng ký gom thêm 25 triệu cổ phiếu HAG

                        </a>

                        <img src="../media.dff.vn/web/image/2025/8/bau-duc638917168623228185.jpg"
                            title="Con trai &#39;bầu&#39; Đức đăng k&#253; gom th&#234;m 25 triệu cổ phiếu HAG"
                            alt="Con trai &#39;bầu&#39; Đức đăng k&#253; gom th&#234;m 25 triệu cổ phiếu HAG"
                            border="0" />
                    </li>

                    <li class="new-style">
                        <a title="Taseco Land muốn chào bán 48 triệu cổ phiếu, tăng vốn điều lệ lên 3.600 tỷ đồng"
                            href="taseco-land-muon-chao-ban-48-trieu-co-phieu-tang-von-dieu-le-len-3600-ty-dong-p20250825120759448.html">Taseco
                            Land muốn chào bán 48 triệu cổ phiếu, tăng vốn điều lệ lên 3.600 tỷ đồng

                        </a>

                        <img src="../media.dff.vn/web/image/2025/8/taseco-land-tal638917204793700058.jpg"
                            title="Taseco Land muốn ch&#224;o b&#225;n 48 triệu cổ phiếu, tăng vốn điều lệ l&#234;n 3.600 tỷ đồng"
                            alt="Taseco Land muốn ch&#224;o b&#225;n 48 triệu cổ phiếu, tăng vốn điều lệ l&#234;n 3.600 tỷ đồng"
                            border="0" />
                    </li>



                </ul>
            </div>
        </div>




        <div class="block-k bg-box-a">
            <div class="view-right-a h-lsk">
                <div class="title">
                    <h3><a href="event.html">Lịch sự kiện</a> </h3>
                </div>

                <ol class="content-ol">

                    <li class="card-list-item" key="0">
                        <a title="D11: Ngày GDKHQ trả cổ tức năm 2021 bằng cổ phiếu (100:12)"
                            href="d11-ngay-gdkhq-tra-co-tuc-nam-2021-bang-co-phieu-10012-e13975.html">D11:
                            Ngày GDKHQ trả cổ tức năm 2021 bằng cổ phiếu (100:12)</a>
                    </li>

                    <li class="card-list-item" key="0">
                        <a title="MSB: Ngày GDKHQ trả cổ tức năm 2024 bằng cổ phiếu (100:20)"
                            href="msb-ngay-gdkhq-tra-co-tuc-nam-2024-bang-co-phieu-10020-e13976.html">MSB:
                            Ngày GDKHQ trả cổ tức năm 2024 bằng cổ phiếu (100:20)</a>
                    </li>

                    <li class="card-list-item" key="0">
                        <a title="VLW: Ngày GDKHQ trả cổ tức năm 2024 bằng tiền (14,33%)"
                            href="vlw-ngay-gdkhq-tra-co-tuc-nam-2024-bang-tien-1433-e13977.html">VLW: Ngày
                            GDKHQ trả cổ tức năm 2024 bằng tiền (14,33%)</a>
                    </li>

                    <li class="card-list-item" key="0">
                        <a title="HT1: Ngày GDKHQ trả cổ tức năm 2024 bằng tiền (1%)"
                            href="ht1-ngay-gdkhq-tra-co-tuc-nam-2024-bang-tien-1-e13978.html">HT1: Ngày
                            GDKHQ trả cổ tức năm 2024 bằng tiền (1%)</a>
                    </li>

                    <li class="card-list-item" key="0">
                        <a title="SVT: Ngày GDKHQ trả cổ tức năm 2024 bằng tiền (15%)"
                            href="svt-ngay-gdkhq-tra-co-tuc-nam-2024-bang-tien-15-e13979.html">SVT: Ngày
                            GDKHQ trả cổ tức năm 2024 bằng tiền (15%)</a>
                    </li>

                    <li class="card-list-item" key="0">
                        <a title="PJS: Ngày GDKHQ trả cổ tức năm 2024 bằng tiền (8,5%)"
                            href="pjs-ngay-gdkhq-tra-co-tuc-nam-2024-bang-tien-85-e13980.html">PJS: Ngày
                            GDKHQ trả cổ tức năm 2024 bằng tiền (8,5%)</a>
                    </li>

                    <li class="card-list-item" key="0">
                        <a title="NWT: Ngày GDKHQ trả cổ tức năm 2024 bằng tiền (7%)"
                            href="nwt-ngay-gdkhq-tra-co-tuc-nam-2024-bang-tien-7-e13981.html">NWT: Ngày
                            GDKHQ trả cổ tức năm 2024 bằng tiền (7%)</a>
                    </li>

                    <li class="card-list-item" key="0">
                        <a title="VGI: Ngày GDKHQ trả cổ tức năm 2024 bằng tiền (7,5%) "
                            href="vgi-ngay-gdkhq-tra-co-tuc-nam-2024-bang-tien-75-e13982.html">VGI: Ngày
                            GDKHQ trả cổ tức năm 2024 bằng tiền (7,5%) </a>
                    </li>

                </ol>
            </div>
        </div>





        <div class="block-k bg-box-a">
            <div class="tieu-diem t-analysis">
                <h2>

                    <i class="fas fa-search-dollar"></i> DFF <span>ANALYSIS</span>
                </h2>
                <ul>

                    <li class="new-style">
                        <a title="Vietcap nâng giá mục tiêu cổ phiếu HPG lên 35.300 đồng/cp, duy trì khuyến nghị MUA"
                            href="vietcap-nang-gia-muc-tieu-co-phieu-hpg-len-35300-dongcp-duy-tri-khuyen-nghi-mua-p20250824182258588.html">Vietcap
                            nâng giá mục tiêu cổ phiếu HPG lên 35.300 đồng/cp, duy trì khuyến nghị MUA

                        </a>
                        <img src="../media.dff.vn/web/image/2025/8/hoa-phat-1638916570313071160.jpg"
                            title="Vietcap n&#226;ng gi&#225; mục ti&#234;u cổ phiếu HPG l&#234;n 35.300 đồng/cp, duy tr&#236; khuyến nghị MUA"
                            alt="Vietcap n&#226;ng gi&#225; mục ti&#234;u cổ phiếu HPG l&#234;n 35.300 đồng/cp, duy tr&#236; khuyến nghị MUA"
                            border="0" />
                    </li>

                    <li class="new-style">
                        <a title="Nếu sàn giao dịch tiền số triển khai blockchain riêng?"
                            href="neu-san-giao-dich-tien-so-trien-khai-blockchain-rieng-p20250822165039104.html">Nếu
                            sàn giao dịch tiền số triển khai blockchain riêng?

                        </a>
                        <img src="../media.dff.vn/web/image/2025/8/btc638914838374635395.jpg"
                            title="Nếu s&#224;n giao dịch tiền số triển khai blockchain ri&#234;ng?"
                            alt="Nếu s&#224;n giao dịch tiền số triển khai blockchain ri&#234;ng?"
                            border="0" />
                    </li>

                    <li class="new-style">
                        <a title="
									 
									 
									 
									 
									 Nữ đại gia 8x bí ẩn thâu tóm siêu tháp Saigon Marina IFC
								 
								 
								 
								 
								 " href="le-thi-huyen-linh-8x-bi-an-thau-tom-sieu-thap-saigon-marina-ifc-p20250819192005571.html">




                            Nữ đại gia 8x bí ẩn thâu tóm siêu tháp Saigon Marina IFC






                        </a>
                        <img src="../media.dff.vn/web/image/2025/8/saigon-marina-ifc638912280054461019.jpg"
                            title="
									 
									 
									 
									 
									 Nữ đại gia 8x b&#237; ẩn th&#226;u t&#243;m si&#234;u th&#225;p Saigon Marina IFC
								 
								 
								 
								 
								 " alt="
									 
									 
									 
									 
									 Nữ đại gia 8x b&#237; ẩn th&#226;u t&#243;m si&#234;u th&#225;p Saigon Marina IFC
								 
								 
								 
								 
								 " border="0" />
                    </li>

                    <li class="new-style">
                        <a title="VPS chuẩn bị IPO?" href="vps-chuan-bi-ipo-p20250820180003352.html">VPS
                            chuẩn bị IPO?

                        </a>
                        <img src="../media.dff.vn/web/image/2025/8/vps638913096033366787.jpg"
                            title="VPS chuẩn bị IPO?" alt="VPS chuẩn bị IPO?" border="0" />
                    </li>

                    <li class="new-style">
                        <a title="'Gió đông' đã về, ACBS có chịu IPO?"
                            href="gio-dong-da-co-acbs-co-chiu-ipo-p20250821181224916.html">'Gió đông' đã về,
                            ACBS có chịu IPO?

                        </a>
                        <img src="../media.dff.vn/web/image/2025/8/acbs638913967448542399.jpg"
                            title="&#39;Gi&#243; đ&#244;ng&#39; đ&#227; về, ACBS c&#243; chịu IPO?"
                            alt="&#39;Gi&#243; đ&#244;ng&#39; đ&#227; về, ACBS c&#243; chịu IPO?"
                            border="0" />
                    </li>

                    <li class="new-style">
                        <a title="
									 
									 Chuyển tiền số trên 1.000 USD tại Trung tâm tài chính phải báo cáo, liệu có khắt khe?
								 
								 " href="chuyen-tien-so-tren-1000-usd-tai-trung-tam-tai-chinh-phai-bao-cao-lieu-co-khat-khe-p20250820183104493.html">

                            Chuyển tiền số trên 1.000 USD tại Trung tâm tài chính phải báo cáo, liệu có khắt
                            khe?



                        </a>
                        <img src="../media.dff.vn/web/image/2025/8/vn1638913114644334454.jpg" title="
									 
									 Chuyển tiền số tr&#234;n 1.000 USD tại Trung t&#226;m t&#224;i ch&#237;nh phải b&#225;o c&#225;o, liệu c&#243; khắt khe?
								 
								 " alt="
									 
									 Chuyển tiền số tr&#234;n 1.000 USD tại Trung t&#226;m t&#224;i ch&#237;nh phải b&#225;o c&#225;o, liệu c&#243; khắt khe?
								 
								 " border="0" />
                    </li>



                </ul>
            </div>
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