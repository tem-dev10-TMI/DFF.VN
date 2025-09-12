<div class="hmain">
    <div class="main">
        <div class="header-top">
            <div class="m-menu "><span class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarNav5"
                    aria-controls="navbarNav5" aria-expanded="false"><a href="javascript:void(0)"><i
                            class="fas fa-bars"></i></a></span>
            </div>
            <div class="header-logo">
                <a href="index.html">
                    <img alt="Mạng xã hội kinh tế tài chính DFF" title="Mạng xã hội kinh tế tài chính DFF"
                        src="../img.dff.vn/static/img/logo.png" /></a>
                <div class="box-search">
                    <div class="input-group ">
                        <span class="input-group-append">
                            <button
                                class="btn btn-outline-secondary bg-white border-bottom-0 border rounded-pill ms-n5 btn-seach"
                                module-load="onSearch" type="button">
                                <i class="fa fa-search"></i>
                            </button>
                        </span>
                        <input class="form-control border-end-0 border rounded-pill"
                            onkeypress="return OnEnter(event)" placeholder="Tìm kiếm" type="search" />
                    </div>
                    <div class="header-info"><i class="far fa-clock"></i><span class="currentDate"> </span></div>
                </div>
            </div>

            <div class="header-right">
                <ul>
                    <li><span><a href="#"><i class="fas fa-bars"></i></a></span> </li>
                    <li class="mnqtop"><span><a class="dropdown-toggle " data-bs-toggle="dropdown"
                                aria-expanded="false" title="Tạo mới" href="javascript:void(0)"><i
                                    class="fas fa-plus"></i></a>
                            <ul class="dropdown-menu hide">
                                <li><a style="position:relative" class="dropdown-item btquick"
                                        href="javascript:void(0)" module-load="loadwrite"><i
                                            class="fas fa-plus"></i><span class="number"><i
                                                class="bi bi-lightning-charge-fill"></i></span> Viết bài nhanh</a>
                                </li>
                                <li><a class="dropdown-item" href="javascript:void(0)" data-url="/write.html"
                                        module-load="redirect"><i class="fas fa-plus"></i> Viết bài thường</a></li>
                            </ul>
                        </span>
                    </li>
                    <li class="n-alert"><span data-bs-toggle="collapse" data-bs-target="#id_alert"
                            aria-controls="id_alert" aria-expanded="false"><a href="javascript:void(0)"
                                title="Thông báo"><i class="fas fa-bell"></i></a> <span class="number">4</span>
                        </span>
                    </li>
                    <li class="top-pro ">
                        <!-- <span class="signin"><a module-load="signin" href="javascript:void(0)"><img
                                    src="vendor/dffvn/content/img/user.svg"></a> 
                        </span> -->
                        <span class="dropdown signed hide" style="display: block;">
                            <a class="dropdown-toggle  " data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="vendor/dffvn/content/img/user.svg">
                            </a>

                            <ul class="dropdown-menu hide">
                                <li>
                                    <div class="profiles">
                                        <ul>
                                        </ul>
                                        <div class="add">
                                            <a href="index.html">Xem tất cả Profile</a>
                                            <?php if (isset($_SESSION['user_id'])): ?>
                                                <!-- Đã đăng nhập -->
                                                <span class="dropdown signed" style="display: block;">
                                                    <a class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" href="javascript:void(0)">
                                                        <img src="vendor/dffvn/content/img/user.svg">
                                                    </a>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <div class="profiles">
                                                                <ul>
                                                                    <!-- Có thể load profile user tại đây -->
                                                                </ul>
                                                                <div class="add">
                                                                    <a href="index.html">Xem tất cả Profile</a>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="menu-ai"><a class="dropdown-item" href="index.html"><i
                                                                    class="fas fa-dice-d20"></i> Hỗ trợ AI</a></li>
                                                        <li><a class="dropdown-item" href="index.html"><i class="fas fa-plus"></i> Viết bài</a></li>
                                                        <li><a class="dropdown-item" href="index.html"><i class="fas fa-user"></i> Profile</a></li>
                                                        <li><a class="dropdown-item" href="javascript:void(0)" module-load="info"><i
                                                                    class="fas fa-info-circle"></i> Thông tin tài khoản</a></li>
                                                        <li><a class="dropdown-item" href="javascript:void(0)" module-load="changepass"><i
                                                                    class="fas fa-unlock"></i> Đổi mật khẩu</a></li>
                                                        <li><a class="dropdown-item" href="javascript:void(0)" module-load="logout"><i
                                                                    class="fas fa-sign-out-alt"></i> Đăng xuất</a></li>
                                                    </ul>
                                                </span>
                                            <?php else: ?>
                                                <!-- Chưa đăng nhập -->
                                                <span class="signin">
                                                    <a href="javascript:void(0)" onclick="showLoginModal()">
                                                        <img src="vendor/dffvn/content/img/user.svg">
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
<div class="modal" role="dialog" id="div_modal" aria-labelledby="myModalLabel" data-popup="true" data-popup-id="5560" aria-modal="true" tabindex="-1">
    <div class="modal-dialog modal-lg" style="width:450px">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" style="cursor: move;"><span class="core-popup-title">Đăng nhập </span></h4> <button type="button" class="close sh-popup-close"><i class="far fa-times-circle"></i></button>
            </div>
            <div class="modal-body" style="padding:10px 15px 10px">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="popup-area-msg">
                            <?php
                            // Hiển thị thông báo lỗi
                            if (isset($_SESSION['login_errors']) && !empty($_SESSION['login_errors'])) {
                                echo '<div class="alert alert-danger">';
                                foreach ($_SESSION['login_errors'] as $error) {
                                    echo '<p>' . htmlspecialchars($error) . '</p>';
                                }
                                echo '</div>';
                                unset($_SESSION['login_errors']);
                            }

                            // Hiển thị thông báo thành công
                            if (isset($_SESSION['login_success'])) {
                                echo '<div class="alert alert-success">' . htmlspecialchars($_SESSION['login_success']) . '</div>';
                                unset($_SESSION['login_success']);
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <form id="login" method="POST" action="controller/UserController.php" novalidate="novalidate">
                    <div class="f-login">
                        <div class="col-12">
                            <div class="input-group">
                                <div class="input-group-text"><i class="fas fa-user"></i></div>
                                <input name="userName" id="userName" type="text" class="form-control" placeholder="Nhập tài khoản" data-listener-added_226719fc="true">
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="input-group">
                                <div class="input-group-text"><i class="fas fa-lock"></i></div>
                                <input id="password" name="password" type="password" class="form-control" placeholder="Nhập mật khẩu">
                            </div>
                        </div>

                        <div class="col-12 text-right">
                            <a class="color-logo" id="boxforgot" href="javascript:Page.forgot()">Quên mật khẩu?</a> | <a class="color-logo" id="boxregister" href="javascript:void(0)" onclick="showRegisterModal()">Tạo tài khoản</a>
                        </div>
                        <div class="col-12">
                            <div class="f-submit">
                                <button type="submit" id="submit" href="javascript:void(0)">Đăng nhập</button>
                            </div>
                        </div>
                        <div class="content-divider text-muted"> <span>OR</span> </div>

                        <div class="col-12">
                            <div class="input-social">
                                <button type="button" class="login-with-google-btn">Đăng nhập bằng Google</button>
                            </div>
                        </div>

                        <input type="hidden" name="action" value="login">
                        <input type="hidden" name="t" value="3">
                    </div>
                </form>
            </div>
            <div class="modal-footer"><button type="button" class="btn bg-purple cmd-cancel btn-flat btn-footer btn-sm"><span data-button="icon" class="fas fa-sign-out-alt"></span> <span data-button="text">Thoát</span></button></div>
        </div>
    </div>
</div>
<!-- Modal đăng kí -->
<div class="modal" role="dialog" id="register_modal" aria-labelledby="registerModalLabel" data-popup="true" data-popup-id="8268" aria-modal="true" tabindex="-1">
    <div class="modal-dialog modal-lg" style="width:450px">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" style="cursor: move;"><span class="core-popup-title">Đăng ký tài khoản </span></h4> <button type="button" class="close sh-popup-close"><i class="far fa-times-circle"></i></button>
            </div>
            <div class="modal-body" style="padding:10px 15px 10px">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="popup-area-msg"></div>
                    </div>
                </div>

                <form id="register" novalidate="novalidate">
                    <div class="f-register">
                        <div class="col-12">
                            <div class="title">
                                Tạo tài khoản để sử dụng đầy đủ tính năng và tham gia cộng đồng thành viên của DFF.VN
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="input-group">
                                <div class="input-group-text"><i class="bi bi-person"></i></div>
                                <input name="fullName" id="fullName" type="text" class="form-control" placeholder="Họ và tên" data-listener-added_14c2e35c="true">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="input-group">
                                <div class="input-group-text"><i class="bi bi-person"></i></div>
                                <input name="userName" id="userName" type="text" class="form-control" placeholder="Tên đăng nhập (Viết liền không Dấu)">
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
                                <input name="phone" id="phone" type="text" class="form-control" placeholder="Số điện thoại">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="input-group">
                                <div class="input-group-text"><i class="bi bi-lock"></i></div>
                                <input id="password" name="password" type="password" class="form-control" placeholder="Mật khẩu">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="input-group">
                                <div class="input-group-text"><i class="bi bi-share"></i></div>
                                <input name="refCode" id="refCode" type="text" value="" class="form-control" placeholder="Mã liên kết">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="f-submit">
                                <button type="submit" id="submit" href="javascript:void(0)">Đăng ký</button>
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

                <script>
                    // Form đăng ký chỉ để hiển thị, không xử lý
                    $("#register").submit(function(e) {
                        e.preventDefault();
                        alert('Chức năng đăng ký đang được phát triển!');
                    });
                </script>
            </div>
            <div class="modal-footer"><button type="button" class="btn bg-purple cmd-cancel btn-flat btn-footer btn-sm"><span data-button="icon" class="fas fa-sign-out-alt"></span> <span data-button="text">Thoát</span></button></div>
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

<div class="top-stock">
    <div class="marquee">
        <div class="item co-VNINDEX">
            <div class="irow label">
                <span>VNINDEX</span>
                <span class="value"></span>
            </div>
            <div class="irow content">
                <span>
                    <i class=""></i>
                    <index></index>
                </span>
                <span class="per"></span>
            </div>
        </div>
        <div class="item co-HNX">
            <div class="irow label">
                <span>HNX</span>
                <span class="value"></span>
            </div>
            <div class="irow content">
                <span>
                    <i class=""></i>
                    <index></index>
                </span>
                <span class="per"></span>
            </div>
        </div>
        <div class="item co-VN30F1M">
            <div class="irow label">
                <span>VN30F1M</span>
                <span class="value"></span>
            </div>
            <div class="irow content">
                <span>
                    <i class=""></i>
                    <index></index>
                </span>
                <span class="per"></span>
            </div>
        </div>
        <div class="item co-VN30">
            <div class="irow label">
                <span>VN30</span>
                <span class="value"></span>
            </div>
            <div class="irow content">
                <span>
                    <i class=""></i>
                    <index></index>
                </span>
                <span class="per"></span>
            </div>
        </div>
        <div class="item co-UPCOM">
            <div class="irow label">
                <span>UPCOM</span>
                <span class="value"></span>
            </div>
            <div class="irow content">
                <span>
                    <i class=""></i>
                    <index></index>
                </span>
                <span class="per"></span>
            </div>
        </div>

        <div class="item co-Slave">
            <div class="irow label">
                <span>Bạc</span>
                <span class="value"></span>
            </div>
            <div class="irow content">
                <span>
                    <i class=""></i>
                    <index></index>
                </span>
                <span class="per"></span>
            </div>
        </div>
        <div class="item co-Oil">
            <div class="irow label">
                <span>Dầu Thô WTI</span>
                <span class="value"></span>
            </div>
            <div class="irow content">
                <span>
                    <i class=""></i>
                    <index></index>
                </span>
                <span class="per"></span>
            </div>
        </div>


        <div class="item co-BTC">
            <div class="irow label">
                <span><a target="_blank" href="coins-bitcoin.html">Bitcoin</a></span>
                <span class="value"></span>
            </div>
            <div class="irow content">
                <span>
                    <i class=""></i>
                    <index></index>
                </span>
                <span class="per"></span>
            </div>
        </div>

        <div class="item co-ETH">
            <div class="irow label">
                <span><a target="_blank" href="coins-ethereum.html">Ethereum</a></span>
                <span class="value"></span>
            </div>
            <div class="irow content">
                <span>
                    <i class=""></i>
                    <index></index>
                </span>
                <span class="per"></span>
            </div>
        </div>

        <div class="item co-BNB">
            <div class="irow label">
                <span><a target="_blank" href="coins-binancecoin.html">BNB</a></span>
                <span class="value"></span>
            </div>
            <div class="irow content">
                <span>
                    <i class=""></i>
                    <index></index>
                </span>
                <span class="per"></span>
            </div>
        </div>



    </div>
</div>