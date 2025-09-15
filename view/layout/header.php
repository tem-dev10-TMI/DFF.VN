<div class="hmain">
    <div class="main">
        <div class="header-top">
            <div class="m-menu "><span class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarNav5"
                    aria-controls="navbarNav5" aria-expanded="false"><a href="javascript:void(0)"><i
                            class="fas fa-bars"></i></a></span>
            </div>
            <div class="header-logo">
                <a href="home">


                    <img style=" stroke: none !important;height: 50px; width:auto; "
                        alt="Mạng xã hội kinh tế tài chính DFF" title="Mạng xã hội kinh tế tài chính DFF"
                        src="public/img/logo.svg" ; /></a>

                <div class="box-search">
                    <div class="input-group ">
                        <span class="input-group-append">
                            <button
                                class="btn btn-outline-secondary bg-white border-bottom-0 border rounded-pill ms-n5 btn-seach"
                                module-load="onSearch" type="button">
                                <i class="fa fa-search"></i>
                            </button>
                        </span>
                        <input class="form-control border-end-0 border rounded-pill" onkeypress="return OnEnter(event)"
                            placeholder="Tìm kiếm" type="search" />
                    </div>
                    <div class="header-info"><i class="far fa-clock"></i><span class="currentDate"> </span></div>
                </div>
            </div>

            <div class="header-right">
                <ul>
                    <li><span><a href="#"><i class="fas fa-bars"></i></a></span> </li>
                    <li class="mnqtop"><span><a class="dropdown-toggle " data-bs-toggle="dropdown" aria-expanded="false"
                                title="Tạo mới" href="javascript:void(0)"><i class="fas fa-plus"></i></a>
                            <ul class="dropdown-menu hide">
                                <li><a style="position:relative" class="dropdown-item btquick" href="javascript:void(0)"
                                        module-load="loadwrite"><i class="fas fa-plus"></i><span class="number"><i
                                                class="bi bi-lightning-charge-fill"></i></span> Viết bài nhanh</a>
                                </li>
                                <li><a class="dropdown-item" href="javascript:void(0)" data-url="/write.html"
                                        module-load="redirect"><i class="fas fa-plus"></i> Viết bài thường</a></li>
                            </ul>
                        </span>
                    </li>
                    <li class="n-alert"><span data-bs-toggle="collapse" data-bs-target="#id_alert"
                            aria-controls="id_alert" aria-expanded="false"><a href="javascript:void(0)"
                                title="Thông báo"><i class="fas fa-bell"></i></a> <span class="number">0</span>
                        </span>
                    </li>
                    <li class="top-pro ">
                        <!-- <span class="signin"><a module-load="signin" href="javascript:void(0)"><img
                                    src="public/img/user.svg"></a> 
                        </span> -->
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <!-- Đã đăng nhập -->
                            <span class="dropdown signed" style="display: block;
                                <a class=" dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"
                                href="javascript:void(0)">
                                <img src="vendor/dffvn/content/img/user.svg">

                                <a class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"
                                    href="javascript:void(0)">
                                    <?php
                                    $avatarUrl = $_SESSION['user_avatar_url'] ?? null;
                                    if (!$avatarUrl || trim($avatarUrl) === '') {
                                        $avatarUrl = 'https://i.pinimg.com/1200x/83/0e/ea/830eea38f7a5d3d8e390ba560d14f39c.jpg';
                                    }
                                    ?>
                                    <img src="<?= htmlspecialchars($avatarUrl) ?>">

                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <div class="profiles">
                                            <ul>
                                                <!-- Có thể load profile user tại đây -->
                                            </ul>
                                            <div class="add">
                                                <a href="home">Xem tất cả Profile</a>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="menu-ai"><a class="dropdown-item" href="home"><i class="fas fa-dice-d20"></i>
                                            Hỗ trợ AI</a></li>
                                    <li><a class="dropdown-item" href="index.html"><i class="fas fa-plus"></i> Viết bài</a>
                                    </li>
                                    <li><a class="dropdown-item" href="index.html"><i class="fas fa-user"></i> Profile</a>
                                    </li>

                                    <li><a class="dropdown-item" href="home"><i class="fas fa-plus"></i> Viết bài</a></li>

                                    <li><a class="dropdown-item" href="<?= BASE_URL ?>/<?php if ($_SESSION['user_role'] == 'user') {
                                                                                            echo 'profileUser';
                                                                                        } else {
                                                                                            echo 'profile_business';
                                                                                        } ?>"><i class="fas fa-user"></i> Profile</a></li>

                                    <li><a class="dropdown-item" href="javascript:void(0)" module-load="info"><i
                                                class="fas fa-info-circle"></i> Thông tin tài khoản</a></li>
                                    <li><a class="dropdown-item" href="javascript:void(0)" module-load="changepass"><i
                                                class="fas fa-unlock"></i> Đổi mật khẩu</a></li>
                                    <li><a class="dropdown-item" module-load="logout" href="<?= BASE_URL ?>/logout"><i
                                                class="fas fa-sign-out-alt"></i> Đăng xuất</a></li>
                                </ul>
                            </span>
                        <?php else: ?>
                            <!-- Chưa đăng nhập -->
                            <span class="signin">
                                <a href="javascript:void(0)" onclick="showLoginModal()">
                                    <img src="https://dff.vn/vendor/dffvn/content/img/user.svg">
                                </a>
                            </span>
                        <?php endif; ?>
                    </li>
                </ul>
            </div>
            <div class="collapse box-alert" id="id_alert">

                <ul class="nav nav-pills" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-semibold active position-relative" id="pills-home-tab"
                            data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab"
                            aria-controls="pills-home" aria-selected="true">Thông báo</button>
                    </li>

                </ul>
                <div class="tab-content" id="pills-tabContent">

                </div>
            </div>
            <div class="m-search"><span><a href="javascript:void(0)"><i class="fas fa-search"></i></a></span></div>

        </div>
    </div>
</div>
<!-- khu tự trị header nha cái này để hiện thị header ở phía trên  -->

<!-- Modal đăng nhập -->
<div class="modal" role="dialog" id="div_modal" aria-labelledby="myModalLabel" data-popup="true" data-popup-id="5560"
    aria-modal="true" tabindex="-1">
    <div class="modal-dialog modal-lg" style="width:450px">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" style="cursor: move;"><span class="core-popup-title">Đăng nhập </span></h4>
                <button type="button" class="close sh-popup-close"><i class="far fa-times-circle"></i></button>
            </div>
            <div class="modal-body" style="padding:10px 15px 10px">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="popup-area-msg">
                            <?php if (!empty($error)) : ?>
                                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                            <?php endif; ?>

                            <?php if (isset($_SESSION['success'])): ?>
                                <div class="alert alert-success">
                                    <?= $_SESSION['success'] ?>
                                    <?php unset($_SESSION['success']); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <form id="login" method="POST" action="<?= BASE_URL ?>/login" novalidate="novalidate">
                    <div class="f-login">
                        <div class="col-12">
                            <div class="input-group">
                                <div class="input-group-text"><i class="fas fa-user"></i></div>

                                <input name="userName" id="userName" type="text" class="form-control"
                                    placeholder="Nhập tài khoản" data-listener-added_226719fc="true">

                                <input name="username" id="username" type="text" class="form-control"
                                    placeholder="Nhập tài khoản" data-listener-added_226719fc="true">
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="input-group">
                                <div class="input-group-text"><i class="fas fa-lock"></i></div>
                                <input id="password" name="password" type="password" class="form-control"
                                    placeholder="Nhập mật khẩu">
                            </div>
                        </div>

                        <div class="col-12 text-right">
                            <a class="color-logo" id="boxforgot" href="javascript:Page.forgot()">Quên mật khẩu?</a> | <a
                                class="color-logo" id="boxregister" href="javascript:void(0)"
                                onclick="showRegisterModal()">Tạo tài khoản</a>
                        </div>
                        <div class="col-12">
                            <div class="f-submit">
                                <button type="submit" id="submit">Đăng nhập</button>
                            </div>
                        </div>
                        <div class="content-divider text-muted"> <span>OR</span> </div>

                        <div class="col-12">
                            <div class="input-social">

                                <button type="button" class="login-with-google-btn" onclick="window.location.href='<?= BASE_URL ?>/public/google-login.php'">

                                    Đăng nhập bằng Google
                                </button>
                            </div>
                        </div>


                        <input type="hidden" name="action" value="login">
                        <input type="hidden" name="t" value="3">
                    </div>
                </form>
            </div>
            <div class="modal-footer"><button type="button"
                    class="btn bg-purple cmd-cancel btn-flat btn-footer btn-sm"><span data-button="icon"
                        class="fas fa-sign-out-alt"></span> <span data-button="text">Thoát</span></button></div>
        </div>
    </div>
</div>
<!-- Modal đăng kí -->
<div class="modal" role="dialog" id="register_modal" aria-labelledby="registerModalLabel" data-popup="true"
    data-popup-id="8268" aria-modal="true" tabindex="-1">
    <div class="modal-dialog modal-lg" style="width:450px">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" style="cursor: move;"><span class="core-popup-title">Đăng ký tài khoản </span>
                </h4> <button type="button" class="close sh-popup-close"><i class="far fa-times-circle"></i></button>
            </div>
            <div class="modal-body" style="padding:10px 15px 10px">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="popup-area-msg"></div>
                    </div>
                </div>

                <form id="register" action="<?= BASE_URL ?>/register" method="POST" novalidate="novalidate">
                    <div class="f-register">
                        <div class="col-12">
                            <div class="title">
                                Tạo tài khoản để sử dụng đầy đủ tính năng và tham gia cộng đồng thành viên của DFF.VN
                            </div>
                        </div>
                        <input type="hidden" name="role" value="customer">
                        <div class="col-12">
                            <div class="input-group">
                                <div class="input-group-text"><i class="bi bi-person"></i></div>
                                <input name="name" id="name" type="text" class="form-control" placeholder="Họ và tên"
                                    data-listener-added_14c2e35c="true">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="input-group">
                                <div class="input-group-text"><i class="bi bi-person"></i></div>
                                <input name="username" id="username" type="text" class="form-control"
                                    placeholder="Tên đăng nhập (Viết liền không Dấu)">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="input-group">
                                <div class="input-group-text"><i style="opacity: 0.5;" class="fas fa-at"></i></div>
                                <input name="email" id="email" type="text" class="form-control" placeholder="Email">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="input-group">
                                <div class="input-group-text"><i class="bi bi-phone"></i></div>
                                <input name="phone" id="phone" type="text" class="form-control"
                                    placeholder="Số điện thoại">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="input-group">
                                <div class="input-group-text"><i class="bi bi-lock"></i></div>
                                <input id="password" name="password" type="password" class="form-control"
                                    placeholder="Mật khẩu">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="input-group">
                                <div class="input-group-text"><i class="bi bi-lock"></i></div>
                                <input id="password_confirm" name="password_confirm" type="password"
                                    class="form-control" placeholder="Xác nhận mật khẩu">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="f-submit">
                                <button type="submit" id="submit">Đăng ký</button>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="input-social text-center">
                                Khi bấm tạo tài khoản bạn đã đồng ý
                                với <a href="/policy.html" target="_blank">quy định</a> của DFF.VN
                            </div>
                        </div>
                        <div class="col-12 text-center">
                            <a class="color-logo" href="javascript:switchToLogin()">Đã có tài khoản? Đăng nhập</a>
                        </div>

                        <input type="hidden" name="t" value="2">

                    </div>
                </form>
            </div>
            <div class="modal-footer"><button type="button"
                    class="btn bg-purple cmd-cancel btn-flat btn-footer btn-sm"><span data-button="icon"
                        class="fas fa-sign-out-alt"></span> <span data-button="text">Thoát</span></button></div>
        </div>
    </div>
</div>
<!-- Xử lý ẩn hiện modal -->
<script>
    $(function() {
        // Lấy modal login
        var loginElement = document.getElementById('div_modal');
        var loginModal = loginElement ? new bootstrap.Modal(loginElement) : null;

        // Lấy modal register
        var registerElement = document.getElementById('register_modal');
        var registerModal = registerElement ? new bootstrap.Modal(registerElement) : null;

        // Hàm mở modal đăng nhập
        window.showLoginModal = function() {
            if (registerModal) registerModal.hide(); // ẩn modal đăng ký nếu đang mở
            if (loginModal) loginModal.show(); // mở modal đăng nhập
        };

        // Hàm mở modal đăng ký
        window.showRegisterModal = function() {
            if (loginModal) loginModal.hide(); // ẩn modal đăng nhập nếu đang mở
            if (registerModal) registerModal.show(); // mở modal đăng ký
        };

        // Hàm chuyển ngược từ đăng ký sang đăng nhập
        window.switchToLogin = function() {
            if (registerModal) registerModal.hide();
            if (loginModal) loginModal.show();
        };

        // Đóng modal khi click nút close hoặc thoát
        $('#div_modal .sh-popup-close, #div_modal .cmd-cancel').on('click', function() {
            if (loginModal) loginModal.hide();
        });

        $('#register_modal .sh-popup-close, #register_modal .cmd-cancel').on('click', function() {
            if (registerModal) registerModal.hide();
        });
    });
</script>



<?php
// Load market data nếu chưa có
if (!isset($marketData)) {
    require_once __DIR__ . '/../../model/MarketDataModel.php';
    $marketData = MarketDataModel::getCachedMarketData();
}
// Debug: Kiểm tra dữ liệu market
echo "<!-- Debug: marketData count = " . (isset($marketData) ? count($marketData) : 'undefined') . " -->";
?>
<div class="top-stock">
    <div class="marquee">
        <div class="item co-VNINDEX">
            <div class="irow label">
                <span>VNINDEX</span>
                <span class="value"><?= $marketData['VNINDEX']['price'] ?? '1,667.26' ?></span>
            </div>
            <div class="irow content">
                <span>
                    <i
                        class="<?= ($marketData['VNINDEX']['isPositive'] ?? true) ? 'fa fa-arrow-up' : 'fa fa-arrow-down' ?>"></i>
                    <index><?= $marketData['VNINDEX']['change'] ?? '9.51' ?></index>
                </span>
                <span
                    class="per <?= ($marketData['VNINDEX']['isPositive'] ?? true) ? 'positive' : 'negative' ?>"><?= ($marketData['VNINDEX']['changePercent'] ?? 0.57) ?>%</span>
            </div>
        </div>
        <div class="item co-HNX">
            <div class="irow label">
                <span>HNX</span>
                <span class="value"><?= $marketData['HNX']['price'] ?? '245.33' ?></span>
                <!-- Debug: <?= isset($marketData['HNX']) ? 'HNX data exists' : 'HNX data missing' ?> -->
            </div>
            <div class="irow content">
                <span>
                    <i
                        class="<?= ($marketData['HNX']['isPositive'] ?? true) ? 'fa fa-arrow-up' : 'fa fa-arrow-down' ?>"></i>
                    <index><?= $marketData['HNX']['change'] ?? '2.33' ?></index>
                </span>
                <span
                    class="per <?= ($marketData['HNX']['isPositive'] ?? true) ? 'positive' : 'negative' ?>"><?= ($marketData['HNX']['changePercent'] ?? 0.96) ?>%</span>
            </div>
        </div>
        <div class="item co-VN30F1M">
            <div class="irow label">
                <span>VN30F1M</span>
                <span class="value"><?= $marketData['VN30F1M']['price'] ?? '276.51' ?></span>
            </div>
            <div class="irow content">
                <span>
                    <i
                        class="<?= ($marketData['VN30F1M']['isPositive'] ?? true) ? 'fa fa-arrow-up' : 'fa fa-arrow-down' ?>"></i>
                    <index><?= $marketData['VN30F1M']['change'] ?? '5.5' ?></index>
                </span>
                <span
                    class="per <?= ($marketData['VN30F1M']['isPositive'] ?? true) ? 'positive' : 'negative' ?>"><?= ($marketData['VN30F1M']['changePercent'] ?? 0.85) ?>%</span>
            </div>
        </div>
        <div class="item co-VN30">
            <div class="irow label">
                <span>VN30</span>
                <span class="value"><?= $marketData['VN30']['price'] ?? '1,859.00' ?></span>
            </div>
            <div class="irow content">
                <span>
                    <i
                        class="<?= ($marketData['VN30']['isPositive'] ?? true) ? 'fa fa-arrow-up' : 'fa fa-arrow-down' ?>"></i>
                    <index><?= $marketData['VN30']['change'] ?? '10.37' ?></index>
                </span>
                <span
                    class="per <?= ($marketData['VN30']['isPositive'] ?? true) ? 'positive' : 'negative' ?>"><?= ($marketData['VN30']['changePercent'] ?? 0.3) ?>%</span>
            </div>
        </div>
        <div class="item co-UPCOM">
            <div class="irow label">
                <span>UPCOM</span>
                <span class="value"><?= $marketData['UPCOM']['price'] ?? '1,865.45' ?></span>
            </div>
            <div class="irow content">
                <span>
                    <i
                        class="<?= ($marketData['UPCOM']['isPositive'] ?? true) ? 'fa fa-arrow-up' : 'fa fa-arrow-down' ?>"></i>
                    <index><?= $marketData['UPCOM']['change'] ?? '-0.01' ?></index>
                </span>
                <span
                    class="per <?= ($marketData['UPCOM']['isPositive'] ?? true) ? 'positive' : 'negative' ?>"><?= ($marketData['UPCOM']['changePercent'] ?? 0.56) ?>%</span>
            </div>
        </div>

        <div class="item co-Slave">
            <div class="irow label">
                <span>Bạc</span>
                <span class="value"><?= $marketData['Silver']['price'] ?? '110.09' ?></span>
            </div>
            <div class="irow content">
                <span>
                    <i
                        class="<?= ($marketData['Silver']['isPositive'] ?? true) ? 'fa fa-arrow-up' : 'fa fa-arrow-down' ?>"></i>
                    <index><?= $marketData['Silver']['change'] ?? '0.68' ?></index>
                </span>
                <span
                    class="per <?= ($marketData['Silver']['isPositive'] ?? true) ? 'positive' : 'negative' ?>"><?= ($marketData['Silver']['changePercent'] ?? -0.01) ?>%</span>
            </div>
        </div>
        <div class="item co-Oil">
            <div class="irow label">
                <span>Dầu Thô WTI</span>
                <span class="value"><?= $marketData['Oil']['price'] ?? '42.83' ?></span>
            </div>
            <div class="irow content">
                <span>
                    <i
                        class="<?= ($marketData['Oil']['isPositive'] ?? true) ? 'fa fa-arrow-up' : 'fa fa-arrow-down' ?>"></i>
                    <index><?= $marketData['Oil']['change'] ?? '0.32' ?></index>
                </span>
                <span
                    class="per <?= ($marketData['Oil']['isPositive'] ?? true) ? 'positive' : 'negative' ?>"><?= ($marketData['Oil']['changePercent'] ?? 1.62) ?>%</span>
            </div>
        </div>

        <div class="item co-BTC">
            <div class="irow label">
                <span><a target="_blank" href="coins-bitcoin.html">Bitcoin</a></span>
                <span class="value"><?= $marketData['Bitcoin']['price'] ?? '62.69' ?></span>
            </div>
            <div class="irow content">
                <span>
                    <i
                        class="<?= ($marketData['Bitcoin']['isPositive'] ?? true) ? 'fa fa-arrow-up' : 'fa fa-arrow-down' ?>"></i>
                    <index><?= $marketData['Bitcoin']['change'] ?? '745.53' ?></index>
                </span>
                <span
                    class="per <?= ($marketData['Bitcoin']['isPositive'] ?? true) ? 'positive' : 'negative' ?>"><?= ($marketData['Bitcoin']['changePercent'] ?? 0.51) ?>%</span>
            </div>
        </div>

        <div class="item co-ETH">
            <div class="irow label">
                <span><a target="_blank" href="coins-ethereum.html">Ethereum</a></span>
                <span class="value"><?= $marketData['Ethereum']['price'] ?? '115,974.00' ?></span>
            </div>
            <div class="irow content">
                <span>
                    <i
                        class="<?= ($marketData['Ethereum']['isPositive'] ?? true) ? 'fa fa-arrow-up' : 'fa fa-arrow-down' ?>"></i>
                    <index><?= $marketData['Ethereum']['change'] ?? '271.52' ?></index>
                </span>
                <span
                    class="per <?= ($marketData['Ethereum']['isPositive'] ?? true) ? 'positive' : 'negative' ?>"><?= ($marketData['Ethereum']['changePercent'] ?? 0.64) ?>%</span>
            </div>
        </div>

        <div class="item co-BNB">
            <div class="irow label">
                <span><a target="_blank" href="coins-binancecoin.html">BNB</a></span>
                <span class="value"><?= $marketData['BNB']['price'] ?? '4,760.21' ?></span>
            </div>
            <div class="irow content">
                <span>
                    <i
                        class="<?= ($marketData['BNB']['isPositive'] ?? true) ? 'fa fa-arrow-up' : 'fa fa-arrow-down' ?>"></i>
                    <index><?= $marketData['BNB']['change'] ?? '25.54' ?></index>
                </span>
                <span
                    class="per <?= ($marketData['BNB']['isPositive'] ?? true) ? 'positive' : 'negative' ?>"><?= ($marketData['BNB']['changePercent'] ?? 5.7) ?>%</span>
            </div>
        </div>
    </div>
</div>