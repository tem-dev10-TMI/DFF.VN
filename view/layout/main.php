<!-- Lâm Phương Khánh logic lượt truy cập -->
<?php
require_once __DIR__ . '/../../TRACK/track.php'; // ghi nhận mỗi lần mở trang

// Lấy số liệu
$metrics = json_decode(file_get_contents(__DIR__ . '/../../TRACK/metrics.php'), true) ?: [
    'totalVisitors' => 0,
    'onlineVisitors' => 0,
    'totalViews' => 0
];
?>
<!-- Lâm Phương Khánh END logic lượt truy cập -->

<?php require_once __DIR__ . '/../../helpers/cache_helper.php';
require_once __DIR__ . '/_sidebar_content.php'; ?>
<!DOCTYPE html>
<html lang="vi" xmlns="../www.w3.org/1999/xhtml/index.html">


<meta http-equiv="content-type" content="text/html;charset=utf-8" /><!-- /Added by HTTrack -->

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="content-language" content="vi" />
    <meta http-equiv="REFRESH" content="1800" />
    <title>Mạng xã hội Kinh tế - MXH Trung Tâm </title>
    <base href="http://localhost/DFF.VN/">
    <meta name="description" content="MXHTT - Mạng xã hội kinh tế tài chính chuyên biệt cho nhà đầu tư và thị trường" />
    <meta name="keywords"
        content="mxh, mxh.org.vn, Mạng xã hội TMI, Mạng xã hội kinh tế, Mạng xã hội đầu tư, doanh nghiệp, doanh nhân, cổ phiếu, chứng khoán, quản lý tài chính, kinh doanh,Cộng đồng nhà đầu tư, mạng lưới nhà đầu tư, diễn đàn tài chính, diễn đàn nhà đầu tư, phân tích tài chính, thông tin doanh nghiệp, phân tích doanh nghiệp" />
    <meta property="fb:app_id" content="" />
    <meta property="og:type" content="website" />
    <meta property="og:site_name" content="MXH.ORG.VN" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-title" content="mxh.org.vn" />
    <meta name="robots" content="noarchive, max-image-preview:large, index, follow" />
    <meta name="GOOGLEBOT" content="noarchive, max-image-preview:large, index, follow" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=5, user-scalable=1" />
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
    <meta name="google-site-verification" content="ZiVfvsyC2xTt_28WtjowgQZVnyfZY85dGo5J548z-P8" />

    <style>
        /* Định nghĩa animation cho hiệu ứng gradient */
        .user-gradient-name {
            /* --- Chỉ giữ lại những gì cốt lõi nhất --- */
            font-weight: bold;
            font-size: 0.95em;
            /* Hơi thu nhỏ lại một chút để an toàn hơn */

            /* Hiệu ứng gradient */
            background-image: linear-gradient(to right,
                    #372f6a,
                    /* Tím vũ trụ */
                    #a73737,
                    /* Đỏ hoàng hôn */
                    #f09819,
                    /* Cam mặt trời */
                    #a73737,
                    /* Đỏ hoàng hôn */
                    #372f6a
                    /* Tím vũ trụ (lặp lại để animation mượt) */
                );
            /*background-image: linear-gradient(to right, #FFD700, #FF8C00, #FF4500, #FFD700);*/
            background-size: 200% auto;
            animation: gradientAnimation 5s ease infinite;

            /* Phép thuật cho chữ */
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            color: transparent;
            background-size: 400% 400%;
            animation: smoothGradientAnimation 15s linear infinite;
        }

        /* Đừng quên keyframes animation */
        @keyframes smoothGradientAnimation {
            0% {
                background-position: 0% 50%;
            }

            25% {
                background-position: 50% 0%;
            }

            50% {
                background-position: 100% 50%;
            }

            75% {
                background-position: 50% 100%;
            }

            100% {
                background-position: 0% 50%;
            }
        }
    </style>

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">


    <link rel="stylesheet" href="<?= asset_url('public/css/style.css') ?>" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.5.2/bootbox.min.js"></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/mouse0270-bootstrap-notify/3.1.5/bootstrap-notify.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/@editorjs/editorjs@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/header@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/link-autocomplete@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/table@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/editorjs-paragraph-with-alignment@3.0.0"></script>
    <script src="content/edi/quote%40latest.js"></script><!-- Quote -->
    <script src="content/edi/image%40latest.js"></script>


    <script>
        var can, code;
    </script>





    <!-- Google tag (gtag.js) -->
    <!-- <script async src="../www.googletagmanager.com/gtag/js2102?id=G-74K75Z0MDE"></script>
<script> // không cần cái này, để gg thu thập dữ liệu người
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-74K75Z0MDE');
</script>

<script async type="application/javascript"
        src="../news.google.com/swg/js/v1/swg-basic.js"></script>
<script>
  (self.SWG_BASIC = self.SWG_BASIC || []).push( basicSubscriptions => {
    basicSubscriptions.init({
      type: "NewsArticle",
      isPartOfType: ["Product"],
      isPartOfProductId: "CAow84mwDA:openaccess",
      clientOptions: { theme: "light", lang: "vi" },
    });
  });
</script> -->






<body>
    <!-- TOKEN lượt truy cập Lâm Phương Khánh -->
    <!-- Live Counter - bottom-left -->
    <!-- Floating Live Counter -->
    <div class="live-counter position-fixed bottom-0 start-0 m-3" role="status" aria-live="polite">
        <div class="lc-inner d-flex align-items-center rongthem">
            <span class="lc-text">
                <div class="lc-line">
                    <i class="bi bi-people-fill me-1"></i>
                    Đang truy cập: <strong id="onlineCount" data-role="online-count">--</strong>
                </div>
                <div class="lc-line">
                    <i class="bi bi-eye me-1"></i>
                    Tổng lượt truy cập: <strong id="totalViews">--</strong>
                </div>
            </span>
        </div>
    </div>




    <!-- Kết thúc Token Lượt truy cập Lâm Phương Khánh -->

    <!-- Preloader Framework -->
    <style>
        #preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 99999;
            background-color: #000000;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: opacity 0.8s ease, visibility 0.8s ease;
        }

        #preloader.hidden {
            opacity: 0;
            visibility: hidden;
        }

        .spinner-container {
            position: relative;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        #globe-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
            opacity: 0.8;
            filter: blur(0.5px);
        }

        .loader-content {
            position: relative;
            z-index: 10;
        }

        .loading-text {
            color: #00FF41;
            font-size: 1.1rem;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            animation: pulse-text 2.5s ease-in-out infinite;
            text-shadow: 0 0 15px rgba(0, 255, 65, 0.7);
        }

        @keyframes pulse-text {

            0%,
            100% {
                opacity: 0.7;
                transform: scale(1);
            }

            50% {
                opacity: 1;
                transform: scale(1.02);
            }
        }

        /* Thêm style để đảm bảo hiệu ứng mờ dần cho nội dung chính */
        #main-content {
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
        }
    </style>
    <div id="preloader">
        <div class="spinner-container">
            <!-- Container cho canvas 3D -->
            <div id="globe-container"></div>

            <!-- Nội dung text -->
            <div class="loader-content">
                <h2 class="loading-text">Đang giải mã tín hiệu thị trường</h2>
            </div>
        </div>
    </div>
    <!-- End Preloader Framework -->

    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v20.0"
        nonce="9gkWGB3D"></script>



    <?php



    // Cache topics for 3 hours
    $allTopics = get_cache('all_topics', 5);
    if ($allTopics === false) {
        require_once __DIR__ . '/../../model/TopicModel.php';
        $topicModel = new TopicModel();
        $allTopics = $topicModel->getAll();
        set_cache('all_topics', $allTopics);
    }

    $topTopics = array_slice($allTopics, 0, 5); // 5 chủ đề đầu
    $moreTopics = array_slice($allTopics, 5);
    require_once __DIR__ . '/../layout/sidebarMobile.php';

    // Cache events for 30 minutes
    $headerEvents = get_cache('header_events', 300);
    if ($headerEvents === false) {
        require_once __DIR__ . '/../../model/event/Events.php';
        if (!isset($pdo)) {
            require_once __DIR__ . '/../../config/db.php';
        }
        global $pdo;
        $eventModelHeader = new EventModels($pdo);
        $headerEvents = $eventModelHeader->all(8);
        set_cache('header_events', $headerEvents);
    }

    ?>

    <!-- m-top-info Lâm Phương Khánh-->
    <div class="m-top-info">
        <span class="t-left"><i class="far fa-clock"></i><span class="currentDate"> </span></span>
        <span class="t-right">
            <div class="lc-line">
                <i class="bi bi-people-fill me-1"></i>
                Đang truy cập: <strong id="onlineCountHeader" data-role="online-count">--</strong>
            </div>
        </span>
    </div>
    <script>
        // VD: nếu đang ở http://localhost/DFF.VN/ thì BASE_URL = "/DFF.VN"
        window.BASE_URL = "<?= rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\') ?>";
    </script>

    <script>
        (function () {
            const onlineTargets = document.querySelectorAll('[data-role="online-count"], #onlineCount, #onlineCountHeader');
            const totalEl = document.getElementById('totalViews');
            const dotEl = document.getElementById('onlineDot'); // có thể không tồn tại

            async function updateCounter() {
                try {
                    const base = window.BASE_URL || '';
                    const metricsUrl = base + '/TRACK/metrics.php';

                    const res = await fetch(metricsUrl, { cache: 'no-store', credentials: 'same-origin' });
                    if (!res.ok) throw new Error('HTTP ' + res.status);

                    const data = await res.json();
                    const online = (data?.onlineVisitors ?? 0);
                    const total = (data?.totalViews ?? 0);

                    // cập nhật tất cả nơi hiển thị online
                    onlineTargets.forEach(el => el.textContent = online);

                    // tổng lượt xem (nếu có)
                    if (totalEl) totalEl.textContent = total;

                    // hiệu ứng chấm
                    if (dotEl) dotEl.textContent = '•';
                } catch (e) {
                    if (dotEl) dotEl.textContent = '×';
                }
                setTimeout(() => { if (dotEl) dotEl.textContent = '•'; }, 1000);
            }

            // chạy ngay và lặp
            updateCounter();
            let timer = setInterval(updateCounter, 15000);

            document.addEventListener('visibilitychange', () => {
                if (document.hidden) {
                    clearInterval(timer);
                } else {
                    updateCounter();
                    timer = setInterval(updateCounter, 15000);
                }
            });
        })();
    </script>
    <!-- End Lâm Phương Khánh -->
    <!-- header start -->

    <?php require_once __DIR__ . '/../layout/header.php'; // vị trí header nha cái này để hiện thị header ở phía trên  
    ?>
    <!-- cho cái thi trường chạy -->

    <!-- header end -->
    <!-- script chạy thị trường -->
    <script>
        $(function () {

            function Marquee(selector, speed) {
                const parentSelector = document.querySelector(selector);
                const clone = parentSelector.innerHTML;
                const firstElement = parentSelector.children[0];
                let i = 0;
                let marqueeInterval;
                parentSelector.insertAdjacentHTML('beforeend', clone);

                function startMarquee() {
                    marqueeInterval = setInterval(function () {
                        firstElement.style.marginLeft = `-${i}px`;
                        /*var fwid = $('.top-stock').width();*/
                        fwid = 1500;
                        if (i > fwid) {
                            i = 0;
                        }
                        i = i + speed;
                    }, 0);
                }

                function stopMarquee() {
                    clearInterval(marqueeInterval);
                }

                parentSelector.addEventListener('mouseenter', stopMarquee);
                parentSelector.addEventListener('mouseleave', startMarquee);

                startMarquee();
            }

            window.addEventListener('load', () => Marquee('.marquee', 0.2));

        });
    </script>
    <!-- script chạy thị trường end -->


    <?php
    $mobileNotifCount = (isset($headerEvents) && is_array($headerEvents)) ? count($headerEvents) : 0;
    ?>
    <div class="func-mobile">
        <ul>
            <li>
                <a href="home">
                    <i class="fas fa-home"></i>
                    <span>Home</span>
                </a>
            </li>
            <li class="fitem1">
                <a href="javascript:void(0)" title="Chatbot" onclick="toggleChatbotBox()">
                    <i class="fas fa-comment"></i>
                    <span>Chatbot</span>
                </a>
            </li>
            <li class="fitem2">
                <a href="trends" class="m-trend">
                    <i class="bi bi-lightning-charge-fill"></i>
                    <span>Xu hướng</span>
                </a>
            </li>
            <li><a href="javascript:void(0)" class="js-mobile-modal" data-mobile-modal="alerts"><i
                        class="fas fa-bell"></i>
                    <span class="number"><?= $mobileNotifCount ?></span>
                    <span class="falert">Thông báo</span>
                </a></li>
            <li>
                <a module-load="signin" href="javascript:void(0)" class="js-mobile-modal" data-mobile-modal="profile"><i
                        class="fas fa-user-alt"></i>
                    <span>Tôi</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Mobile Modal -->
    <div class="modal fade" role="dialog" id="mobileModal" aria-labelledby="mobileModalLabel" aria-modal="true"
        tabindex="-1" style="z-index: 9998;">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="mobileModalLabel">Menu</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="mobileModalBody" style="padding: 10px 15px; overflow-y: auto; "></div>
            </div>
        </div>
    </div>

    <style>
        /* Mobile alerts list */
        .m-alerts {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .m-alerts li {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 10px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .m-alerts li:last-child {
            border-bottom: 0;
        }

        .m-alerts .ic {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: #f5f7ff;
            color: #2b5cff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            flex: 0 0 28px;
        }

        .m-alerts .txt {
            flex: 1;
            min-width: 0;
        }

        .m-alerts .txt a {
            font-weight: 600;
            color: #111;
            display: block;
            text-decoration: none;
        }

        .m-alerts .meta {
            color: #6c757d;
            font-size: 12px;
            margin-top: 2px;
        }

        .m-section-title {
            font-weight: 700;
            font-size: 16px;
            margin: 0 0 8px;
        }
    </style>
    <script></script>
    <script>
        (function () {
            var modalEl = document.getElementById('mobileModal');
            var modalBody = document.getElementById('mobileModalBody');
            var modalTitle = document.getElementById('mobileModalLabel');
            var bsModal = null;
            if (modalEl && window.bootstrap && window.bootstrap.Modal) {
                bsModal = new bootstrap.Modal(modalEl);
            }

            // NÂNG CẤP: Thêm tham số `modalClass` để nhận tên lớp CSS
            function openMobileModal(title, html, modalClass) {
                if (!modalEl) return;

                // --- BẮT ĐẦU PHẦN CHỈNH SỬA QUAN TRỌNG ---

                // 1. Luôn xóa các lớp style cũ trước khi mở để reset
                modalEl.classList.remove('modal-mobile-custom', 'modal-account-style');

                // 2. Nếu có lớp mới được truyền vào, hãy thêm nó
                if (modalClass) {
                    modalEl.classList.add(modalClass);
                }

                // --- KẾT THÚC PHẦN CHỈNH SỬA ---

                if (modalTitle) modalTitle.textContent = title || 'Menu';
                if (modalBody) modalBody.innerHTML = html || '';
                if (bsModal) bsModal.show();
                else modalEl.style.display = 'block';
                document.body.style.overflow = 'hidden';
            }

            function closeMobileModal() {
                if (!modalEl) return;
                if (bsModal) bsModal.hide();
                else modalEl.style.display = 'none';
                document.body.style.overflow = '';
            }

            modalEl && modalEl.addEventListener('click', function (e) {
                if (e.target === modalEl) closeMobileModal();
            });

            document.querySelectorAll('.js-mobile-modal').forEach(function (a) {
                a.addEventListener('click', function (e) {
                    var type = this.getAttribute('data-mobile-modal');
                    if (!type) return;

                    if (type === 'alerts') {
                        var alerts = document.getElementById('id_alert');
                        var html = alerts ? alerts.innerHTML : '<p>Chưa có thông báo.</p>';
                        // NÂNG CẤP: Truyền vào lớp 'modal-mobile-custom'
                        openMobileModal('Thông báo', html, 'modal-mobile-custom');
                    } else if (type === 'profile') {
                        var tpl = document.getElementById('mobile-profile-template');
                        var html = tpl ? tpl.innerHTML : '<p>Không có dữ liệu.</p>';
                        // NÂNG CẤP: Truyền vào lớp 'modal-account-style'
                        openMobileModal('Tài khoản', html, 'modal-account-style');
                    } else if (type === 'create') {
                        var createHtml = '<div><a href="javascript:void(0)" module-load="loadwrite"><i class="fas fa-bolt"></i> Viết nhanh</a></div><div class="mt-2"><a href="javascript:void(0)" data-url="/write.html" module-load="redirect"><i class="fas fa-pen"></i> Viết bài thường</a></div>';
                        // Giữ nguyên, không cần style đặc biệt, nó sẽ tự động căn giữa
                        openMobileModal('Tạo mới', createHtml);
                    }
                });
            });
        })();
    </script>

    <!-- Hidden template: profile menu -->
    <div id="mobile-profile-template" class="d-none">
        <ul class="list-group list-group-flush">
            <?php if (isset($_SESSION['user'])): ?>
                <li class="list-group-item"><a href="<?= BASE_URL ?>/<?php if ($_SESSION['user']['role'] == 'user' || $_SESSION['user']['role'] == 'admin' || $_SESSION['user']['role'] == 'businessmen') {
                      echo 'profile_user';
                  } else {
                      //echo 'profile_business';
                  } ?>"><i class="fas fa-user"></i> Trang cá
                        nhân</a></li>
                <li class="list-group-item"><a href="profile_user"><i class="fas fa-plus"></i> Viết bài</a></li>
                <li class="list-group-item"><a href="<?= BASE_URL ?>/change_password"><i class="fas fa-unlock"></i> Đổi mật
                        khẩu</a></li>
                <li class="list-group-item"><a href="<?= BASE_URL ?>/logout"><i class="fas fa-sign-out-alt"></i> Đăng
                        xuất</a></li>
            <?php else: ?>
                <li class="list-group-item"><a href="javascript:void(0)"
                        onclick="if(window.showLoginModal) showLoginModal();"><i class="fas fa-sign-in-alt"></i> Đăng
                        nhập</a></li>
                <li class="list-group-item"><a href="javascript:void(0)"
                        onclick="if(window.showRegisterModal) showRegisterModal();"><i class="fas fa-user-plus"></i> Đăng
                        ký</a></li>
            <?php endif; ?>
        </ul>
    </div>

    <script>
        // Bọc trong IIFE để tránh xung đột
        (function () {
            // Chỉ lấy element, không khởi tạo modal ở đây
            const modalEl = document.getElementById('mobileModal');
            if (!modalEl) return; // Nếu không có element thì dừng luôn

            /**
             * HÀM QUAN TRỌNG: Lấy hoặc Tạo đối tượng Modal duy nhất.
             * Dù hàm này được gọi bao nhiêu lần, nó luôn trả về cùng một đối tượng modal.
             */
            function getModalInstance() {
                if (!window.bootstrap || !window.bootstrap.Modal) return null;

                // Kiểm tra xem đã có instance nào được gắn vào element chưa
                let instance = bootstrap.Modal.getInstance(modalEl);

                // Nếu chưa có, tạo mới và lưu lại
                if (!instance) {
                    instance = new bootstrap.Modal(modalEl);
                }
                return instance;
            }

            // Hàm mở modal, giờ sẽ an toàn hơn
            window.openMobileModal = function (title, html, modalClass) {
                const bsModal = getModalInstance();
                if (!bsModal) return;

                modalEl.classList.remove('modal-mobile-custom', 'modal-account-style', 'modal-fullscreen-mobile'); // THÊM LỚP MỚI VÀO ĐÂY ĐỂ XÓA TRƯỚC
                if (modalClass) {
                    modalEl.classList.add(modalClass);
                }

                const modalTitle = modalEl.querySelector('#mobileModalLabel');
                const modalBody = modalEl.querySelector('#mobileModalBody');

                if (modalTitle) modalTitle.textContent = title || 'Menu';
                if (modalBody) modalBody.innerHTML = html || '';

                bsModal.show();
            };

            // Gắn sự kiện vào các nút bấm chỉ một lần
            // Dùng một cờ để đảm bảo code này chỉ chạy 1 lần duy nhất
            if (!window.mobileModalListenerAttached) {
                document.querySelectorAll('.js-mobile-modal').forEach(function (a) {
                    a.addEventListener('click', function (e) {
                        e.preventDefault();
                        const type = this.getAttribute('data-mobile-modal');
                        if (!type) return;

                        if (type === 'alerts') {
                            const alerts = document.getElementById('id_alert');
                            const html = alerts ? alerts.innerHTML : '<p>Chưa có thông báo.</p>';
                            window.openMobileModal('Thông báo', html, 'modal-mobile-custom');
                        } else if (type === 'profile') {
                            const tpl = document.getElementById('mobile-profile-template');
                            const html = tpl ? tpl.innerHTML : '<p>Không có dữ liệu.</p>';
                            // THAY ĐỔI: Dùng lớp 'modal-fullscreen-mobile' cho Tài khoản
                            window.openMobileModal('Tài khoản', html, 'modal-fullscreen-mobile');
                        } else if (type === 'create') {
                            const createHtml = '<div>...</div>'; // Nội dung của bạn
                            window.openMobileModal('Tạo mới', createHtml);
                        }
                    });
                });
                window.mobileModalListenerAttached = true; // Đánh dấu là đã gắn sự kiện
            }
        })();

        document.querySelectorAll(".btn-follow").forEach(btn => {
            btn.addEventListener("click", function() {
                const userId = this.getAttribute("data-user");
                const token = "<?= htmlspecialchars($_SESSION['user']['session_token'] ?? '') ?>";

                fetch("api/follow", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded"
                        },
                        body: `user_id=${encodeURIComponent(userId)}&session_token=${encodeURIComponent(token)}`,
                        credentials: "include"
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            // cập nhật text nút
                            this.querySelector(".follow-text").innerText =
                                data.action === "follow" ? "Đang theo dõi" : "Theo dõi";

                            // cập nhật số follower
                            this.querySelector(".number").innerText = data.followers;
                        } else {
                            alert(data.message);
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert("Không thể kết nối đến server!");
                    });
            });
        });
    </script>
    <a module-load="boxIndex"></a>

    <div id="wrapper">


        <a module-load="loadData">




        </a>


        <style>
            @media (max-width:768px) {
                #back-top {
                    bottom: 140px !important;
                }
            }
        </style>
        <!-- khúc này là hiện thị 4 cái cục bài viết nổi bật ở đầu á  -->
        <?php if (!empty($profile)): ?> <!-- ✅ fix: thay if ($profile) -->
            <?= $content ?>

        <?php else: ?>
            <div class="main">

                <!-- ?php require_once 'view/page/Home.php'; -->
                <?= $content ?>

                <?php require_once __DIR__ . '/../layout/sidebarLeft.php'; ?>
                <a href="crypton">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-coin mb-coins" viewBox="0 0 16 16">
                        <path
                            d="M5.5 9.511c.076.954.83 1.697 2.182 1.785V12h.6v-.709c1.4-.098 2.218-.846 2.218-1.932 0-.987-.626-1.496-1.745-1.76l-.473-.112V5.57c.6.068.982.396 1.074.85h1.052c-.076-.919-.864-1.638-2.126-1.716V4h-.6v.719c-1.195.117-2.01.836-2.01 1.853 0 .9.606 1.472 1.613 1.707l.397.098v2.034c-.615-.093-1.022-.43-1.114-.9zm2.177-2.166c-.59-.137-.91-.416-.91-.836 0-.47.345-.822.915-.925v1.76h-.005zm.692 1.193c.717.166 1.048.435 1.048.91 0 .542-.412.914-1.135.982V8.518z" />
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                        <path d="M8 13.5a5.5 5.5 0 1 1 0-11 5.5 5.5 0 0 1 0 11m0 .5A6 6 0 1 0 8 2a6 6 0 0 0 0 12" />
                    </svg>
                </a>
                <div class="modal fade" id="createPostModal" tabindex="-1" aria-labelledby="createPostModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-scrollable" style="margin:10px auto;">



                        <div class="modal-content shadow-lg border-0 rounded-3 mb-4">

                            <!-- Header -->
                            <div class="modal-header bg-success text-white justify-content-center position-relative">
                                <h5 class="modal-title fw-bold" id="createPostModalLabel">
                                    <i class="fas fa-pencil-alt me-2"></i> Tạo bài viết mới
                                </h5>
                                <button type="button" class="btn-close btn-close-white position-absolute top-50 end-0 translate-middle-y me-3" data-bs-dismiss="modal"
                                    aria-label="Đóng"></button>
                            </div>

                            <!-- Body -->
                            <!-- Body -->
                            <div class="modal-body bg-light p-4">
                                <div class="post-box p-3 rounded-3 bg-white shadow-sm mb-3">

                                    <div class="d-flex align-items-center mb-3">
                                        <?php
                                        $userName = htmlspecialchars($_SESSION['user']['name'] ?? 'Người dùng');
                                        $userAvatar = htmlspecialchars($_SESSION['user']['avatar_url'] ?? 'public/img/avatar/default.png');
                                        $userRole = $_SESSION['user']['role'] ?? 'user';
                                        $roleText = 'Thành viên';
                                        if ($userRole === 'businessmen') {
                                            $roleText = 'Doanh nhân';
                                        } elseif ($userRole === 'admin') {
                                            $roleText = 'Quản trị viên';
                                        }
                                        ?>
                                        <img src="<?= $userAvatar ?>" class="rounded-circle border border-2 border-success me-2" alt="avatar" style="width: 48px; height: 48px;" onerror="this.onerror=null;this.src='public/img/avatar/default.png';">
                                        <div>
                                            <h6 class="mb-0 fw-bold text-dark" id="modalPostUserName"><?= $userName ?></h6>
                                            <small class="text-muted" id="modalPostUserRole"><?= $roleText ?></small>
                                        </div>
                                    </div>

                                    <form id="postForm" class="needs-validation" novalidate>
                                        <input type="hidden" name="session_token" value="<?= htmlspecialchars($_SESSION['user']['session_token'] ?? '') ?>">
                                        <input type="text" id="postTitle" class="form-control form-control-lg mb-3 border-success" placeholder="Nhập tiêu đề bài viết..." required>

                                        <div class="mb-3">
                                            <label for="postSummary" class="form-label fw-bold text-success">Tóm tắt bài viết:</label>
                                            <textarea id="postSummary" class="form-control border-success" rows="3" placeholder="Nhập một đoạn tóm tắt ngắn gọn về nội dung bài viết..." required></textarea>
                                        </div>

                                        <div class="mb-3">
                                            <label for="postCoverImage" class="form-label fw-bold text-success">Ảnh bìa (cover):</label>
                                            <input type="file" id="postCoverImage" class="form-control border-success" accept="image/*" required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="topicSelect" class="form-label fw-bold text-success">Chọn chủ đề:</label>
                                            <select class="form-select border-success" id="topicSelect" required>
                                                <option value="">-- Chọn chủ đề --</option>
                                                <?php if (!empty($allTopics)) : ?>
                                                    <?php foreach ($allTopics as $topic) : ?>
                                                        <option value="<?= $topic['id'] ?>"><?= htmlspecialchars($topic['name']) ?></option>
                                                    <?php endforeach; ?>
                                                <?php else : ?>
                                                    <option value="1" selected>Kinh doanh</option>
                                                    <option value="2">Công nghệ</option>
                                                    <option value="3">Đời sống</option>
                                                <?php endif; ?>
                                            </select>
                                        </div>

                                        <div id="sectionsWrap" class="d-flex flex-column gap-3">

                                            <div class="card border-0 shadow-sm section-item" data-index="1">
                                                <div class="card-header bg-success-subtle d-flex align-items-center justify-content-between">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span class="badge bg-success text-white rounded-pill" style="min-width:2rem">1</span>
                                                        <strong>Phần 1</strong>
                                                    </div>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <button type="button" class="btn btn-outline-success btn-sm section-add-media" data-type="image">
                                                            <i class="fas fa-image me-1"></i> Ảnh
                                                        </button>
                                                        <button type="button" class="btn btn-outline-success btn-sm section-add-media" data-type="video">
                                                            <i class="fas fa-video me-1"></i> Video
                                                        </button>
                                                        <button type="button" class="btn btn-outline-danger btn-sm d-none section-remove">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold">Tiêu đề phần 1</label>
                                                        <input type="text" class="form-control" placeholder="Nhập tiêu đề phần 1..." value="" required>
                                                    </div>

                                                    <div class="mb-3">
                                                        <input type="file" class="d-none section-file" accept="image/*,video/*">
                                                        <div class="media-preview border rounded p-3 text-center">Chưa chọn ảnh/video.</div>
                                                    </div>

                                                    <div class="mb-2">
                                                        <label class="form-label fw-semibold">Nội dung phần 1</label>
                                                        <textarea class="form-control" rows="4" placeholder="Nhập nội dung phần 1..." required></textarea>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mt-3">
                                            <button type="button" id="btnAddSection" class="btn btn-outline-success">
                                                <i class="fas fa-plus me-1"></i> Thêm phần
                                            </button>
                                            <button type="submit" class="btn btn-success px-4 rounded-pill">
                                                <i class="fas fa-paper-plane me-1"></i> Đăng bài
                                            </button>
                                        </div>

                                    </form>

                                </div>
                            </div>

                            <!-- Style nhẹ cho preview -->
                            <style>
                                .media-preview {
                                    min-height: 140px;
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    background: #f8f9fa
                                }

                                .media-preview img,
                                .media-preview video {
                                    max-width: 100%;
                                    max-height: 320px
                                }
                            </style>
                            <script>
                                (function() {
                                    const sectionsWrap = document.getElementById('sectionsWrap');
                                    const btnAddSection = document.getElementById('btnAddSection');

                                    // Lấy số thứ tự kế tiếp
                                    const nextIndex = () => {
                                        const items = sectionsWrap.querySelectorAll('.section-item');
                                        let max = 0;
                                        items.forEach(i => {
                                            max = Math.max(max, parseInt(i.dataset.index, 10));
                                        });
                                        return max + 1;
                                    };

                                    // Tạo 1 block phần mới (UI fix cứng mẫu)
                                    const sectionHTML = (idx) => `
    <div class="card border-0 shadow-sm section-item" data-index="${idx}">
      <div class="card-header bg-success-subtle d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-2">
          <span class="badge bg-success text-white rounded-pill" style="min-width:2rem">${idx}</span>
          <strong>Phần ${idx}</strong>
        </div>
        <div class="d-flex align-items-center gap-2">
          <button type="button" class="btn btn-outline-success btn-sm section-add-media" data-type="image">
            <i class="fas fa-image me-1"></i> Ảnh
          </button>
          <button type="button" class="btn btn-outline-success btn-sm section-add-media" data-type="video">
            <i class="fas fa-video me-1"></i> Video
          </button>
          <button type="button" class="btn btn-outline-danger btn-sm section-remove">
            <i class="fas fa-trash-alt"></i>
          </button>
        </div>
      </div>
      <div class="card-body">
        <div class="mb-3">
          <label class="form-label fw-semibold">Tiêu đề phần ${idx}</label>
          <input type="text" class="form-control" placeholder="Nhập tiêu đề phần ${idx}..." value="" required>
        </div>

        <div class="mb-3">
          <input type="file" class="d-none section-file" accept="image/*,video/*">
          <div class="media-preview border rounded p-3 text-center">Chưa chọn ảnh/video.</div>
        </div>

        <div class="mb-2">
          <label class="form-label fw-semibold">Nội dung phần ${idx}</label>
          <textarea class="form-control" rows="4" placeholder="Nhập nội dung phần ${idx}..." required></textarea>
        </div>
      </div>
    </div>
  `;

                                    // Thêm phần mới
                                    btnAddSection.addEventListener('click', () => {
                                        const idx = nextIndex();
                                        sectionsWrap.insertAdjacentHTML('beforeend', sectionHTML(idx));
                                    });

                                    // Uỷ quyền sự kiện: chọn media + xoá phần
                                    sectionsWrap.addEventListener('click', (e) => {
                                        const addBtn = e.target.closest('.section-add-media');
                                        if (addBtn) {
                                            const card = addBtn.closest('.section-item');
                                            const fileInput = card.querySelector('.section-file');
                                            fileInput.setAttribute('accept', addBtn.dataset.type === 'image' ? 'image/*' : 'video/*');
                                            fileInput.click();
                                        }

                                        const removeBtn = e.target.closest('.section-remove');
                                        if (removeBtn) {
                                            const all = sectionsWrap.querySelectorAll('.section-item');
                                            if (all.length <= 1) return; // ít nhất phải còn 1 phần
                                            removeBtn.closest('.section-item').remove();
                                        }
                                    });

                                    // Preview media (UI)
                                    sectionsWrap.addEventListener('change', (e) => {
                                        if (!e.target.classList.contains('section-file')) return;
                                        const file = e.target.files?.[0];
                                        const preview = e.target.closest('.section-item').querySelector('.media-preview');
                                        preview.textContent = 'Chưa chọn ảnh/video.';
                                        if (!file) return;
                                        const url = URL.createObjectURL(file);
                                        preview.innerHTML = '';
                                        if (file.type.startsWith('image/')) {
                                            const img = document.createElement('img');
                                            img.src = url;
                                            img.alt = 'preview';
                                            preview.appendChild(img);
                                        } else if (file.type.startsWith('video/')) {
                                            const video = document.createElement('video');
                                            video.src = url;
                                            video.controls = true;
                                            preview.appendChild(video);
                                        } else {
                                            preview.textContent = 'Định dạng không hỗ trợ.';
                                        }
                                    });
                                })();
                            </script>

                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <input type="hidden" id="hdd_id" value="24166" />

        <script>
            $(function () {

                jQuery("#home_slider").owlCarousel({
                    autoplay: false,
                    rewind: false,
                    /* use rewind if you don't want loop */
                    margin: 20,
                    dots: false,
                    /*
                   animateOut: 'fadeOut',
                   animateIn: 'fadeIn',
                   */
                    responsiveClass: true,
                    autoHeight: true,
                    autoplayTimeout: 7000,
                    navText: ["<i class='fa fa-chevron-left'></i>", "<i class='fa fa-chevron-right'></i>"],
                    smartSpeed: 800,
                    nav: true,
                    responsive: {
                        0: {
                            items: 1
                        },

                        600: {
                            items: 1
                        },

                        1024: {
                            items: 3
                        },

                        1366: {
                            items: 4
                        }
                    }
                });

                var nid = $('#hdd_id').val();
                Page.loadCm(0, $('.list_comment'), 0, nid)

                $('.mb-chat').click(function () {
                    $('.cover-chat').show();
                });
                $('.cover-chat .cclose').click(function () {
                    $('.cover-chat').hide();
                });

                $('.cm-more').on('click', function (e) {
                    var id = $('.box_result:last').attr('data-ref');
                    Page.loadCm(0, $('.list_comment'), id, nid);
                });

                coin_search();

            });
        </script>

        <script>
            $(function () {
                type = 3;
            });
        </script>



        <footer class="footer">

        </footer>

        <script src="public/js/main1c07.js"></script>
        <script src="public/js/articleade1.js"></script>
        <script src="public/js/loadMore.js"></script>
        <script src="public/js/infinite-scroll.js"></script>

        <div id="fb-root"></div>
        <span id="back-top"><i class="fas fa-arrow-up"></i></span>
    </div>


    <script>
        $(function () {
            Page.registerModule(document);


            $(window).scroll(function () {
                var rangeToTop = $(this).scrollTop();
                if (rangeToTop > 500) {
                    $("#back-top").fadeIn("slow");
                } else {
                    $("#back-top").fadeOut("slow");
                }
                if ($(window).scrollTop() + $(window).height() + 1 >= $(document).height()) {
                    Page.loadData();
                }
            });

        });
    </script>
    <script src="<?= BASE_URL ?>/public/js/main.js?v=1.2"></script>
    <script src="<?= BASE_URL ?>/public/js/dangbai.js"></script>
    <script>
        // Make session token available to JS
        window.userSessionToken = "<?= htmlspecialchars($_SESSION['user']['session_token'] ?? '') ?>";
    </script>
    <script>
        // --- Kịch bản cho hiệu ứng 3D (Không đổi) ---
        const container = document.getElementById('globe-container');
        let scene, camera, renderer, globe, particles;
        let mouseX = 0,
            mouseY = 0;
        const windowHalfX = window.innerWidth / 2;
        const windowHalfY = window.innerHeight / 2;

        function init3D() {
            scene = new THREE.Scene();
            scene.fog = new THREE.FogExp2(0x000000, 0.001);
            camera = new THREE.PerspectiveCamera(60, window.innerWidth / window.innerHeight, 1, 3000);
            camera.position.z = 600;
            renderer = new THREE.WebGLRenderer({
                antialias: true,
                alpha: true
            });
            renderer.setPixelRatio(window.devicePixelRatio);
            renderer.setSize(window.innerWidth, window.innerHeight);
            container.appendChild(renderer.domElement);
            const globeGeometry = new THREE.SphereGeometry(180, 64, 64);
            const globeMaterial = new THREE.MeshPhongMaterial({
                color: 0x00FF41,
                wireframe: true,
                transparent: true,
                opacity: 0.05
            });
            globe = new THREE.Mesh(globeGeometry, globeMaterial);
            scene.add(globe);
            const particlesGeometry = new THREE.BufferGeometry();
            const particleCount = 4000;
            const posArray = new Float32Array(particleCount * 3);
            for (let i = 0; i < particleCount * 3; i++) {
                posArray[i] = (Math.random() - 0.5) * 1500;
            }
            particlesGeometry.setAttribute('position', new THREE.BufferAttribute(posArray, 3));
            const particlesMaterial = new THREE.PointsMaterial({
                size: 1.5,
                color: 0x00FF41,
                transparent: true,
                opacity: 0.8,
                blending: THREE.AdditiveBlending
            });
            particles = new THREE.Points(particlesGeometry, particlesMaterial);
            scene.add(particles);
            document.addEventListener('mousemove', onDocumentMouseMove, false);
            window.addEventListener('resize', onWindowResize, false);
        }

        function onWindowResize() {
            camera.aspect = window.innerWidth / window.innerHeight;
            camera.updateProjectionMatrix();
            renderer.setSize(window.innerWidth, window.innerHeight);
        }

        function onDocumentMouseMove(event) {
            mouseX = (event.clientX - windowHalfX) / 3;
            mouseY = (event.clientY - windowHalfY) / 3;
        }

        function animate() {
            requestAnimationFrame(animate);
            globe.rotation.y += 0.0008;
            particles.rotation.y += 0.0004;
            camera.position.x += (mouseX - camera.position.x) * 0.05;
            camera.position.y += (-mouseY - camera.position.y) * 0.05;
            camera.lookAt(scene.position);
            renderer.render(scene, camera);
        }

        init3D();
        animate();

        // --- Preloader logic (Không đổi) ---
        let loaderHidden = false;

        function hideLoader() {
            if (loaderHidden) return;
            loaderHidden = true;

            const preloader = document.getElementById('preloader');
            const mainContent = document.getElementById('main-content');

            if (preloader) {
                preloader.classList.add('hidden');
            }
            if (mainContent) {
                setTimeout(() => {
                    mainContent.style.opacity = '1';
                }, 100);
            }
            document.body.style.overflow = 'auto';
        }

        window.onload = function () {
            const preloader = document.getElementById('preloader');
            if (preloader) {
                setTimeout(function () {
                    preloader.classList.add('hidden');
                }, 500); // 500ms = 0.5 giây
            }
        };
        setTimeout(hideLoader, 5000);
    </script>
</body>



</html>