<?php
require_once 'controller/account/viewUserController.php';
?>
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
                    <div class="block-k">
                        <div class="view-carde f-frame">
                            <div class="provider">
                                <img class="logo" src="<?= !empty($article['avatar_url'])
                                    ? htmlspecialchars($article['avatar_url'])
                                    : 'https://i.pravatar.cc/100' ?>"
                                    alt="<?= htmlspecialchars($article['author_name']) ?>">
                                <div class="p-covers">
                                    <span class="name">
                                        <a href="/profile.html?q=<?= $article['author_id'] ?>">
                                            <?= htmlspecialchars($article['author_name']) ?>
                                        </a>
                                    </span>
                                    <!-- Bỏ timeAgo, thay bằng hiển thị ngày giờ -->
                                    <span class="date"><?= date("d/m/Y H:i", strtotime($article['created_at'])) ?></span>
                                </div>
                            </div>

                            <div class="title">
                                <a href="/article-<?= $article['slug'] ?>-p<?= $article['id'] ?>.html">
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
                <p>Chưa có bài viết nào từ người dùng này.</p>
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




            <div class="block-k bg-box-a">
                <div class="tieu-diem">
                    <h2>

                        <i class="fab fa-hotjar"></i> DFF <span>HOT</span>
                    </h2>
                    <ul>

                        <li class="new-style">
                            <a title="TPBank muốn chi 3.600 tỷ đồng thâu tóm TPS, lập công ty quản lý nợ vốn 100 tỷ đồng"
                                href="tpbank-muon-chi-3600-ty-dong-thau-tom-tps-lap-cong-ty-quanly-no-von-100-ty-dong-p20250827121341119.html">TPBank
                                muốn chi 3.600 tỷ đồng thâu tóm TPS, lập công ty quản
                                lý nợ vốn 100 tỷ đồng

                            </a>

                            <img src="../media.dff.vn/web/image/2025/8/tpbank638918936210729258.jpg"
                                title="TPBank muốn chi 3.600 tỷ đồng th&#226;u t&#243;m TPS, lập c&#244;ng ty quảnl&#253; nợ vốn 100 tỷ đồng"
                                alt="TPBank muốn chi 3.600 tỷ đồng th&#226;u t&#243;m TPS, lập c&#244;ng ty quản l&#253; nợ vốn 100 tỷ đồng"
                                border="0" />
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
                                alt="570.000 tỷ đồng tồn kho của loạt &#39;&#244;ng tr&#249;m&#39; địa ốc" border="0" />
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
                                alt="Nếu s&#224;n giao dịch tiền số triển khai blockchain ri&#234;ng?" border="0" />
                        </li>

                        <li class="new-style">
                            <a title="
                                     
                                     
                                     
                                     
                                     Nữ đại gia 8x bí ẩn thâu tóm siêu tháp Saigon Marina IFC
                                 
                                 
                                 
                                 
                                 "
                                href="le-thi-huyen-linh-8x-bi-an-thau-tom-sieu-thap-saigon-marina-ifc-p20250819192005571.html">



                                Nữ đại gia 8x bí ẩn thâu tóm siêu tháp Saigon Marina IFC




                            </a>
                            <img src="../media.dff.vn/web/image/2025/8/saigon-marina-ifc638912280054461019.jpg" title="
                                     
                                     
                                     
                                     
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
                                alt="&#39;Gi&#243; đ&#244;ng&#39; đ&#227; về, ACBS c&#243; chịu IPO?" border="0" />
                        </li>

                        <li class="new-style">
                            <a title="
                                     
                                     Chuyển tiền số trên 1.000 USD tại Trung tâm tài chính phải báo cáo, liệu có khắt khe?
                                 
                                 "
                                href="chuyen-tien-so-tren-1000-usd-tai-trung-tam-tai-chinh-phai-bao-cao-lieu-co-khat-khe-p20250820183104493.html">

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
                $(function () {
                    var height = $(".content-right").outerHeight() + 600;
                    $(window).scroll(function () {
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
                $(document).ready(function () {
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