<!-- Lâm Phương Khánh logic lượt truy cập -->
 <?php
require_once __DIR__ . '/../../TRACK/track.php'; // ghi nhận mỗi lần mở trang

// Lấy số liệu
$metrics = json_decode(file_get_contents(__DIR__ . '/../../TRACK/metrics.php'), true) ?: [
  'totalVisitors'=>0,'onlineVisitors'=>0,'totalViews'=>0
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">


    <link rel="stylesheet" href="<?= asset_url('public/css/style.css') ?>" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.5.2/bootbox.min.js"></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/mouse0270-bootstrap-notify/3.1.5/bootstrap-notify.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
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
  <div class="lc-inner d-flex align-items-center">
    <span class="lc-dot" id="onlineDot" aria-hidden="true"></span>
    <span class="lc-text">
      <span class="lc-line">
        <i class="bi bi-people-fill me-1"></i>
        Đang truy cập: <strong id="onlineCount">--</strong>
      </span>
      <span class="lc-line lc-sub">
        <i class="bi bi-eye me-1"></i>
        Tổng: <strong id="totalViews">--</strong>
      </span>
    </span>
  </div>
</div>

<script>
  // VD: nếu đang ở http://localhost/DFF.VN/ thì BASE_URL = "/DFF.VN"
  window.BASE_URL = "<?= rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\') ?>";
</script>

<script>
(function () {
  const onlineEl = document.getElementById('onlineCount');
  const totalEl  = document.getElementById('totalViews');
  const dotEl    = document.getElementById('onlineDot');

  async function updateCounter() {
    try {
     // trong JS
const base = window.BASE_URL || '';
const metricsUrl = base + '/TRACK/metrics.php';
// console.log('metricsUrl =', metricsUrl); // kiểm tra trên Console

const res = await fetch(metricsUrl, { cache: 'no-store', credentials: 'same-origin' });

      if (!res.ok) throw new Error('HTTP ' + res.status);
      const data = await res.json();

      onlineEl.textContent = (data?.onlineVisitors ?? 0);
      if (totalEl) totalEl.textContent = (data?.totalViews ?? 0);

      // hiệu ứng chớp nhẹ khi cập nhật
      dotEl.textContent = '•';
    } catch (e) {
      // lỗi thì giữ số cũ, chấm chuyển x
      dotEl.textContent = '×';
    }
    // chuyển lại dấu chấm sau 1s cho gọn
    setTimeout(() => { dotEl.textContent = '•'; }, 1000);
  }

  // cập nhật ngay khi tải trang
  updateCounter();

  // cập nhật mỗi 15 giây
  let timer = setInterval(updateCounter, 15000);

  // tiết kiệm tài nguyên khi tab bị ẩn
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
<!-- Kết thúc Token Lượt truy cập Lâm Phương Khánh -->

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
    
    <div class="m-top-info">
        <span class="t-left"><i class="far fa-clock"></i><span class="currentDate"> </span></span>
        <span class="t-right"><i class="bi bi-text-indent-right"></i><a href="profile_user" class="user-gradient-name"> <?php echo htmlspecialchars($_SESSION['user']['name'] ?? 'Hello World'); ?> </a></span>
    </div>
    <!-- header start -->

    <?php require_once __DIR__ . '/../layout/header.php'; // vị trí header nha cái này để hiện thị header ở phía trên  
    ?>
    <!-- cho cái thi trường chạy -->

    <!-- header end -->
    <!-- script chạy thị trường -->
    <script>
        $(function() {

            function Marquee(selector, speed) {
                const parentSelector = document.querySelector(selector);
                const clone = parentSelector.innerHTML;
                const firstElement = parentSelector.children[0];
                let i = 0;
                let marqueeInterval;
                parentSelector.insertAdjacentHTML('beforeend', clone);

                function startMarquee() {
                    marqueeInterval = setInterval(function() {
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
    <div class="modal" role="dialog" id="mobileModal" aria-labelledby="mobileModalLabel" aria-modal="true" tabindex="-1"
        style="z-index: 9998;">
        <div class="modal-dialog modal-fullscreen modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="mobileModalLabel">Menu</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="mobileModalBody"
                    style="padding: 10px 15px; overflow-y: auto; "></div>
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

    <script>
        (function() {
            // Dữ liệu sự kiện từ PHP
            const headerEvents = <?php echo json_encode($headerEvents ?? [], JSON_UNESCAPED_UNICODE); ?>;

            function renderAlerts(events) {

            }

            let mobileModalInstance = null;

            function openMobileModal(type) {
                const body = document.getElementById('mobileModalBody');
                const title = document.getElementById('mobileModalLabel');
                if (!body || !title) return;

                if (type === 'alerts') {
                    title.textContent = 'Thông báo';

                    // Tìm đến nội dung thông báo của phiên bản desktop
                    const desktopAlertsContent = document.querySelector('#id_alert .tab-content');

                    // Sao chép HTML từ desktop vào modal body
                    if (desktopAlertsContent) {
                        body.innerHTML = desktopAlertsContent.innerHTML;
                    } else {
                        body.innerHTML = '<div>Không tìm thấy nội dung thông báo.</div>';
                    }
                } else if (type === 'create') {
                    title.textContent = 'Tạo mới';
                    body.innerHTML = '<div>Chức năng tạo mới sẽ cập nhật sau.</div>';
                } else if (type === 'profile') {
                    // Thay vì chỉ hiển thị text, mở modal login
                    showLoginModal(); // <-- Gọi trực tiếp modal login từ header.php
                    return; // thoát luôn để không mở modal mobile
                } else {
                    title.textContent = 'Menu';
                    body.innerHTML = '';
                }

                const modalEl = document.getElementById('mobileModal');
                if (modalEl) {
                    document.querySelectorAll('.modal-backdrop').forEach(b => b.remove());
                    document.body.classList.remove('modal-open');
                    document.body.style.removeProperty('padding-right');

                    mobileModalInstance = bootstrap.Modal.getOrCreateInstance(modalEl, {
                        backdrop: true,
                        focus: true
                    });
                    mobileModalInstance.show();
                }
            }

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
        (function() {
            var modalEl = document.getElementById('mobileModal');
            var modalBody = document.getElementById('mobileModalBody');
            var modalTitle = document.getElementById('mobileModalLabel');
            var bsModal = null;
            if (modalEl && window.bootstrap && window.bootstrap.Modal) {
                bsModal = new bootstrap.Modal(modalEl);
            }

            function openMobileModal(title, html) {
                if (!modalEl) return;
                if (modalTitle) modalTitle.textContent = title || 'Menu';
                if (modalBody) modalBody.innerHTML = html || '';
                if (bsModal) bsModal.show();
                else modalEl.style.display = 'block';
                // Khóa scroll nền khi mở modal thủ công (fallback)
                document.body.style.overflow = 'hidden';
            }

            function closeMobileModal() {
                if (!modalEl) return;
                if (bsModal) bsModal.hide();
                else modalEl.style.display = 'none';
                document.body.style.overflow = '';
            }

            // Đóng khi click nút đóng (fallback nếu không dùng bootstrap)
            modalEl && modalEl.addEventListener('click', function(e) {
                if (e.target === modalEl) closeMobileModal();
            });

            document.querySelectorAll('.js-mobile-modal').forEach(function(a) {
                a.addEventListener('click', function(e) {
                    var type = this.getAttribute('data-mobile-modal');
                    if (!type) return;
                    if (type === 'alerts') {
                        var alerts = document.getElementById('id_alert');
                        var html = alerts ? alerts.innerHTML : '<p>Chưa có thông báo.</p>';
                        openMobileModal('Thông báo', html);
                    } else if (type === 'profile') {
                        var tpl = document.getElementById('mobile-profile-template');
                        var html = tpl ? tpl.innerHTML : '<p>Không có dữ liệu.</p>';
                        openMobileModal('Tài khoản', html);
                    } else if (type === 'create') {
                        // Tái sử dụng quick write nếu có
                        openMobileModal('Tạo mới', '<div><a href="javascript:void(0)" module-load="loadwrite"><i class="fas fa-bolt"></i> Viết nhanh</a></div><div class="mt-2"><a href="javascript:void(0)" data-url="/write.html" module-load="redirect"><i class="fas fa-pen"></i> Viết bài thường</a></div>');
                    }
                });
            });
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
                            <div class="modal-header bg-success text-white">
                                <h5 class="modal-title fw-bold" id="createPostModalLabel">
                                    <i class="fas fa-pencil-alt me-2"></i> Tạo bài viết mới
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                    aria-label="Đóng"></button>
                            </div>

                            <!-- Body -->
                            <div class="modal-body bg-light p-10">
                                <div class="post-box p-3 rounded-3 bg-white shadow-sm mb-3">

                                    <!-- Avatar + tên -->
                                    <div class="d-flex align-items-center mb-3">
                                        <?php
                                        $avatarUrl = $_SESSION['user']['avatar_url'] ?? null;
                                        if (!$avatarUrl || trim($avatarUrl) === '') {
                                            $avatarUrl = 'https://i.pinimg.com/1200x/83/0e/ea/830eea38f7a5d3d8e390ba560d14f39c.jpg';
                                        }
                                        ?>
                                        <img src="<?= htmlspecialchars($avatarUrl) ?>"
                                            class="rounded-circle border border-2 border-success me-2" alt="avatar"
                                            style="width: 48px; height: 48px;">
                                        <div>
                                            <h6 class="mb-0 fw-bold text-dark">
                                                <?php
                                                echo htmlspecialchars($_SESSION['user']['name'] ?? 'Doanh nhân hoặc người dùng');
                                                ?>
                                            </h6>
                                            <small class="text-muted">
                                                <?= htmlspecialchars((($_SESSION['user']['role'] ?? '') === 'user') ? 'Người dùng' : 'Doanh nhân') ?>

                                            </small>

                                        </div>
                                    </div>

                                    <!-- Tiêu đề -->
                                    <input type="text" id="postTitle" class="form-control form-control-lg mb-3 border-success"
                                        placeholder="Nhập tiêu đề bài viết...">

                                    <!-- Tóm tắt -->
                                    <textarea id="postSummary" class="form-control mb-3 border-success" rows="2"
                                        placeholder="Tóm tắt ngắn gọn nội dung..."></textarea>

                                    <!-- Nội dung chính -->
                                    <textarea id="newPost" class="form-control mb-3 border-success" rows="5"
                                        placeholder="Nội dung chính của bài viết..."></textarea>

                                    <!-- Chọn chủ đề -->
                                    <div class="mb-3">
                                        <label for="topicSelect" class="form-label fw-bold text-success">Chọn chủ đề:</label>
                                        <select class="form-select border-success" id="topicSelect" name="topic_id" required>
                                            <option value="">-- Chọn chủ đề --</option>
                                            <?php foreach ($allTopics as $topic): ?>
                                                <option value="<?= $topic['id'] ?>"><?= htmlspecialchars($topic['name']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <!-- Thanh công cụ -->
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex gap-2">
                                            <label class="btn btn-outline-success btn-sm mb-0" for="postImage">
                                                <i class="fas fa-image me-1"></i> Hình ảnh
                                            </label>
                                            <label class="btn btn-outline-success btn-sm mb-0" for="postVideo">
                                                <i class="fas fa-video me-1"></i> Video
                                            </label>
                                            <button class="btn btn-outline-success btn-sm" type="button">
                                                <i class="fas fa-link me-1"></i> Link
                                            </button>
                                        </div>
                                        <button class="btn btn-primary btn-success px-4 rounded-pill" onclick="addPost()">
                                            <i class="fas fa-paper-plane me-1"></i> Đăng bài
                                        </button>

                                    </div>

                                    <!-- Input hidden -->
                                    <input type="file" id="postImage" class="d-none" accept="image/*"
                                        onchange="previewImage(event)">
                                    <input type="file" id="postVideo" class="d-none" accept="video/*"
                                        onchange="previewVideo(event)">
                                </div>

                                <!-- Preview ảnh / video -->
                                <div id="imagePreview" class="mt-2 bt-4"></div>
                                <div id="videoPreview" class="mt-2 bt-4"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <input type="hidden" id="hdd_id" value="24166" />

        <script>
            $(function() {

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

                $('.mb-chat').click(function() {
                    $('.cover-chat').show();
                });
                $('.cover-chat .cclose').click(function() {
                    $('.cover-chat').hide();
                });

                $('.cm-more').on('click', function(e) {
                    var id = $('.box_result:last').attr('data-ref');
                    Page.loadCm(0, $('.list_comment'), id, nid);
                });

                coin_search();

            });
        </script>

        <script>
            $(function() {
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
        $(function() {
            Page.registerModule(document);


            $(window).scroll(function() {
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
    <script src="<?= BASE_URL ?>/public/js/main.js?v=1.1"></script>
    <script src="<?= BASE_URL ?>/public/js/dangbai.js"></script>
    <script>
        // Make session token available to JS
        window.userSessionToken = "<?= htmlspecialchars($_SESSION['user']['session_token'] ?? '') ?>";
    </script>
</body>



</html>