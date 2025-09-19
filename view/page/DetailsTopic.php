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
            <div class="fb-page" data-href="https://www.facebook.com/dffvn.official" data-tabs="timeline"
                data-width="" data-height="" data-small-header="false" data-adapt-container-width="true"
                data-hide-cover="false" data-show-facepile="true">
                <blockquote cite="https://www.facebook.com/dffvn.official" class="fb-xfbml-parse-ignore"><a
                        href="../www.facebook.com/dffvn.html">DFF.VN - Mạng xã hội kinh tế tài chính </a>
                </blockquote>
            </div>
        </div>




        <?php if (!empty($articles)): ?>
            <div class="block-k bg-box-a">
                <div class="tieu-diem">
                    <h2>
                        <i class="fab fa-hotjar"></i> DFF <span>HOT</span>
                    </h2>
                    <ul>
                        <?php foreach ($articles as $article): ?>
                            <li class="new-style">
                                <a title="<?= htmlspecialchars($article['title']) ?>"
                                    href="details_blog?id=<?= $article['id'] ?>">
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
        <?php endif; ?>



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

                    <i class="fas fa-search-dollar"></i> MXH <span>ANALYSIS</span>
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