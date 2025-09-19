<div class="hmain">
    <div class="main">
        <div class="header-top">
            <div class="m-menu "><span class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarNav5"
                    aria-controls="navbarNav5" aria-expanded="false"><a href="javascript:void(0)"><i
                            class="fas fa-bars"></i></a></span>
            </div>
            <div class="header-logo">
                <a href="home">
                    <img alt="" title="" src="public/img/logo/logo.jpg" /></a>
                <div class="box-search">
                    <div class="input-group" onkeypress="return OnEnter(event)">
                        <span class="input-group-append">
                            <button
                                class="btn btn-outline-secondary bg-white border-bottom-0 border rounded-pill ms-n5 btn-seach"

                                type="button"
                                onclick="doSearch()">
                                <i class="fa fa-search"></i>
                            </button>
                        </span>
                        <input
                            id="searchInput"
                            class="form-control border-end-0 border rounded-pill"
                            placeholder="Tìm kiếm"
                            type="search" />

                    </div>

                    <script>
                        function OnEnter(event) {
                            if (event.key === "Enter") {
                                doSearch();
                                return false; // chặn reload mặc định
                            }
                            return true;
                        }

                        function doSearch() {
                            const keyword = document.getElementById("searchInput").value.trim();
                            if (keyword) {
                                // chuyển trang sang search

                                window.location.href = "<?= BASE_URL ?>/search&q=" + encodeURIComponent(keyword);

                            }
                        }
                    </script>

                    <div class="header-info"><i class="far fa-clock"></i><span class="currentDate"> </span></div>
                </div>
            </div>

            <div class="header-right">
                <?php
                if (!isset($headerEvents)) {
                    require_once __DIR__ . '/../../model/event/Events.php';
                    if (!isset($pdo)) {
                        require_once __DIR__ . '/../../config/db.php';
                    }
                    global $pdo;
                    $eventModelHeader = new EventModel($pdo);
                    $headerEvents = $eventModelHeader->all(10);
                }
                $notifCount = (isset($headerEvents) && is_array($headerEvents)) ? count($headerEvents) : 0;
                ?>
                <ul>
                    <li><span><a href="#"><i class="fas fa-bars"></i></a></span> </li>
                    <li class="mnqtop"><span><a class="dropdown-toggle " data-bs-toggle="dropdown" aria-expanded="false"
                                title="Tạo mới" href="javascript:void(0)"><i class="fas fa-plus"></i></a>
                            <?php if (isset($_SESSION['user_id'])): ?>
                                <ul class="dropdown-menu hide">
                                    <li>
                                        <a style="position:relative" class="dropdown-item btquick" href="javascript:void(0)"
                                            data-bs-toggle="modal" data-bs-target="#createPostModal">
                                            <i class="fas fa-plus"></i>
                                            <span class="number"><i class="bi bi-lightning-charge-fill"></i></span>
                                            Viết bài nhanh
                                        </a>
                                    </li>
                                </ul>
                            <?php endif; ?>
                        </span>
                    </li>
                    <li class="n-chatbot">
                        <span>
                            <a href="javascript:void(0)" title="Chatbot" onclick="toggleChatbotBox()">
                                <i class="bi bi-chat-dots"></i>
                            </a>
                        </span>
                    </li>
                    <li class="n-alert"><span data-bs-toggle="collapse" data-bs-target="#id_alert"
                            aria-controls="id_alert" aria-expanded="false"><a href="javascript:void(0)"
                                title="Thông báo"><i class="fas fa-bell"></i></a> <span
                                class="number"><?= $notifCount ?></span>
                        </span>
                    </li>
                    <li class="top-pro ">
                        <!-- <span class="signin"><a module-load="signin" href="javascript:void(0)"><img
                                    src="vendor/dffvn/content/img/user.svg"></a> 
                        </span> -->
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <!-- Đã đăng nhập -->
                            <span class="dropdown signed" style="display: block;">
                                <a class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"
                                    href="javascript:void(0)">
                                    <?php
                                    // Ưu tiên lấy avatar từ session sau khi đăng nhập thành công
                                    $avatarUrl = $_SESSION['user_avatar_url']
                                        ?? ($_SESSION['user']['avatar_url'] ?? null);
                                    if (!$avatarUrl || trim((string) $avatarUrl) === '') {
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
                                    <li><a class="dropdown-item" href="home"><i class="fas fa-plus"></i> Viết bài</a></li>

                                    <li><a class="dropdown-item"
                                            href="<?= BASE_URL ?>/<?php if ($_SESSION['user_role'] == 'user' || $_SESSION['user_role'] == 'admin') {
                                                                        echo 'profile_user';
                                                                    } else {
                                                                        echo 'profile_business';
                                                                    } ?>"><i
                                                class="fas fa-user"></i> Profile</a></li>

                                    <li><a class="dropdown-item" href="javascript:void(0)" module-load="info"><i
                                                class="fas fa-info-circle"></i> Thông tin tài khoản</a></li>
                                    <?php if (!empty($_SESSION['user']['password_hash'])): ?>
                                        <li>
                                            <a class="dropdown-item" href="<?= BASE_URL ?>/change_password"
                                                data-bs-toggle="modal" data-bs-target="#changePassModal">
                                                <i class="fas fa-unlock"></i> Đổi mật khẩu
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                    <li>
                                        <!-- module-load="logout" cai nay trong the a dang xuat     -->
                                        <a class="dropdown-item" href="<?= BASE_URL ?>/logout"><i
                                                class="fas fa-sign-out-alt"></i> Đăng xuất</a>
                                    </li>
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
                </ul>
                <div class="tab-content" id="pills-tabContent">

                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                        aria-labelledby="pills-home-tab">
                        <div class="add" id="conte">
                            <h4 class="title-thongbao">Thông Báo</h4>
                        </div>
 
                        <?php if (!empty($headerEvents)): ?>
                            <ul class="list-unstyled" style="margin:10px 0;">
                                <?php foreach ($headerEvents as $ev): ?>
                                    <li style="margin-bottom:8px;">
                                        <a title="<?= htmlspecialchars($ev['title']) ?>"
                                            href="<?= BASE_URL ?>?url=event&id=<?= $ev['id'] ?>">
                                            <?= htmlspecialchars($ev['title']) ?>
                                        </a>
                                        <small class="text-muted" style="margin-left:6px;">
                                            <?= isset($ev['event_date']) ? date('d/m/Y H:i', strtotime($ev['event_date'])) : '' ?>
                                        </small>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <div class="p-3 text-muted">Chưa có sự kiện nào.</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="m-search"><span><a href="javascript:void(0)"><i class="fas fa-search"></i></a></span></div>

        </div>
    </div>
</div>
<!-- khu tự trị header nha cái này để hiện thị header ở phía trên  -->
<!-- HTML !-->
<style>
    /* CSS */
    .button-85 {
        padding: 0.6em 2em;
        border: none;
        outline: none;
        color: rgb(255, 255, 255);
        background: #111;
        cursor: pointer;
        position: relative;
        z-index: 0;
        border-radius: 10px;
        user-select: none;
        -webkit-user-select: none;
        touch-action: manipulation;
    }

    .button-85:before {
        content: "";
        background: linear-gradient(45deg,
                #ff0000,
                #ff7300,
                #fffb00,
                #48ff00,
                #00ffd5,
                #002bff,
                #7a00ff,
                #ff00c8,
                #ff0000);
        position: absolute;
        top: -2px;
        left: -2px;
        background-size: 400%;
        z-index: -1;
        filter: blur(5px);
        -webkit-filter: blur(5px);
        width: calc(100% + 4px);
        height: calc(100% + 4px);
        animation: glowing-button-85 20s linear infinite;
        transition: opacity 0.3s ease-in-out;
        border-radius: 10px;
    }

    @keyframes glowing-button-85 {
        0% {
            background-position: 0 0;
        }

        50% {
            background-position: 400% 0;
        }

        100% {
            background-position: 0 0;
        }
    }

    .button-85:after {
        z-index: -1;
        content: "";
        position: absolute;
        width: 100%;
        height: 100%;
        background: #222;
        left: 0;
        top: 0;
        border-radius: 10px;
    }
</style>
<!-- Modal đăng nhập -->
<div class="modal" role="dialog" id="div_modal" aria-labelledby="myModalLabel" data-popup="true" data-popup-id="5560"
    aria-modal="true" tabindex="-1">

    <div class="modal-dialog modal-lg" style="width:450px">
        <div class="button-85">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" style="cursor: move;"><span class="core-popup-title">Đăng nhập </span></h4>
                <button type="button" class="close sh-popup-close"><i class="far fa-times-circle"></i></button>
            </div>
            <div class="modal-body" style="padding:10px 15px 10px">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="popup-area-msg">
                            <?php if (!empty($error)): ?>
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

                            <a class="color-logo" id="boxforgot" href="javascript:void(0)" onclick="showForgotModal()">Quên mật khẩu?</a> | <a class="color-logo" id="boxregister" href="javascript:void(0)" onclick="showRegisterModal()">Tạo tài khoản</a>

                        </div>
                        <div class="col-12">
                            <div class="f-submit">
                                <button type="submit" id="submit">Đăng nhập</button>
                            </div>
                        </div>
                        <div class="content-divider text-muted"> <span>OR</span> </div>

                        <div class="col-12">
                            <div class="input-social">
                                <button type="button" class="login-with-google-btn"
                                    onclick="window.location.href='<?= BASE_URL ?>/public/google-login.php'">
                                    Đăng nhập bằng Google
                                </button>
                            </div>
                        </div>
                        <div class="input-social" style="margin-top:10px;">
                            <button type="button" class="login-with-facebook-btn"
                                onclick="window.location.href='<?= BASE_URL ?>/public/facebook-login.php'" style="
                background-color:#1877f2;
                color:#fff;
                border:none;
                padding:10px 20px;
                border-radius:6px;
                font-size:14px;
                font-weight:bold;
                cursor:pointer;
                width:100%;
                transition: background-color 0.3s ease;
            " onmouseover="this.style.backgroundColor='#145dbf';" onmouseout="this.style.backgroundColor='#1877f2';">
                                Đăng nhập bằng Facebook
                            </button>
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
                        <input type="hidden" name="role" value="user">
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
                                <input name="usernamemodal" id="usernamemodal" type="text" class="form-control"
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
                                <input id="passwordmodal" name="password" type="password" class="form-control"
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
                                <button type="submit" id="submitmodal">Đăng ký</button>
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

<!-- Modal Đổi mật khẩu -->
<div class="modal fade" id="changePassModal" tabindex="-1" aria-labelledby="changePassLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="changePassLabel">Đổi mật khẩu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <!-- Thông báo từ controller -->
                <?php if (!empty($changePasswordMessage)): ?>
                    <div class="alert alert-<?= $messageType ?>">
                        <?= $changePasswordMessage ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="<?= BASE_URL ?>/change_password" id="changePassForm">
                    <div class="mb-3">
                        <label for="currentPassword" class="form-label">Mật khẩu hiện tại</label>
                        <input type="password" class="form-control" id="currentPassword" name="old_password" required>
                    </div>
                    <div class="mb-3">
                        <label for="newPassword" class="form-label">Mật khẩu mới</label>
                        <input type="password" class="form-control" id="newPassword" name="new_password" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirmPassword" class="form-label">Xác nhận mật khẩu mới</label>
                        <input type="password" class="form-control" id="confirmPassword" name="confirm_new_password"
                            required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary">Lưu</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<!-- Modal Quên mật khẩu -->
<div class="modal" role="dialog" id="forgot_modal" aria-labelledby="forgotModalLabel" data-popup="true" aria-modal="true" tabindex="-1">
    <div class="modal-dialog modal-lg" style="width:450px">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><span class="core-popup-title">Quên mật khẩu</span></h4>
                <button type="button" class="close sh-popup-close"><i class="far fa-times-circle"></i></button>
            </div>

            <div class="modal-body" style="padding:10px 15px 10px">

                <!-- Bước 1: Nhập email + mật khẩu -->
                <form id="forgot_step1">
                    <div class="f-register">
                        <div class="col-12">
                            <div class="title">Nhập email để nhận mã OTP đặt lại mật khẩu</div>
                        </div>
                        <div class="col-12">
                            <div class="input-group">
                                <div class="input-group-text"><i class="fas fa-at"></i></div>
                                <input name="email" id="forgot_email" type="email" class="form-control" placeholder="Email">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="input-group">
                                <div class="input-group-text"><i class="bi bi-lock"></i></div>
                                <input id="forgot_password" type="password" class="form-control" placeholder="Mật khẩu mới">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="input-group">
                                <div class="input-group-text"><i class="bi bi-lock"></i></div>
                                <input id="forgot_password_confirm" type="password" class="form-control" placeholder="Xác nhận mật khẩu">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="f-submit">
                                <button type="submit" class="btn btn-primary w-100">Gửi OTP</button>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Bước 2: Nhập OTP -->
                <form id="forgot_step2" style="display:none;">
                    <div class="f-register">
                        <div class="col-12">
                            <div class="title">Nhập mã OTP đã được gửi về email</div>
                        </div>
                        <div class="col-12">
                            <div class="input-group">
                                <div class="input-group-text"><i class="bi bi-key"></i></div>
                                <input id="forgot_otp" type="text" class="form-control" placeholder="Nhập mã OTP">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="f-submit">
                                <button type="submit" class="btn btn-success w-100">Xác nhận OTP</button>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Bước 3: Thành công -->
                <div id="forgot_step3" style="display:none; text-align:center;">
                    <h5>✅ Đặt lại mật khẩu thành công!</h5>
                    <p>Bạn có thể đăng nhập với mật khẩu mới.</p>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn bg-purple cmd-cancel btn-flat btn-footer btn-sm">
                    <span data-button="icon" class="fas fa-sign-out-alt"></span>
                    <span data-button="text">Thoát</span>
                </button>
            </div>
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

        // Lấy modal forgot password
        var forgotElement = document.getElementById('forgot_modal');
        var forgotModal = forgotElement ? new bootstrap.Modal(forgotElement) : null;

        // Lấy mobile modal nếu có để ẩn khi bật form đăng nhập/đăng ký
        var mobileElement = document.getElementById('mobileModal');
        var mobileModal = null;
        if (mobileElement && window.bootstrap && window.bootstrap.Modal) {
            mobileModal = bootstrap.Modal.getInstance(mobileElement) || new bootstrap.Modal(mobileElement);
        }

        // Hàm mở modal đăng nhập
        window.showLoginModal = function() {
            if (mobileModal) mobileModal.hide();
            if (registerModal) registerModal.hide(); // ẩn modal đăng ký nếu đang mở
            if (loginModal) loginModal.show(); // mở modal đăng nhập
        };

        // Hàm mở modal đăng ký
        window.showRegisterModal = function() {
            if (mobileModal) mobileModal.hide();
            if (loginModal) loginModal.hide(); // ẩn modal đăng nhập nếu đang mở
            if (registerModal) registerModal.show(); // mở modal đăng ký
        };

        // Hàm mở modal quên mật khẩu
        window.showForgotModal = function() {
            if (mobileModal) mobileModal.hide();
            if (loginModal) loginModal.hide();
            if (registerModal) registerModal.hide();
            if (forgotModal) forgotModal.show();
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


        $('#forgot_modal .sh-popup-close, #forgot_modal .cmd-cancel').on('click', function() {
            if (forgotModal) forgotModal.hide();
        });
    });
</script>

<script>
    document.getElementById("forgot_step1").addEventListener("submit", function(e) {
        e.preventDefault();
        let email = document.getElementById("forgot_email").value;
        let pass = document.getElementById("forgot_password").value;
        let pass2 = document.getElementById("forgot_password_confirm").value;

        if (pass !== pass2) {
            alert("Mật khẩu nhập lại không khớp!");
            return;
        }

        fetch("send-otp.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    email: email,
                    pass: pass
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === "success") {
                    document.getElementById("forgot_step1").style.display = "none";
                    document.getElementById("forgot_step2").style.display = "block";
                } else {
                    alert(data.msg);
                }
            });
    });

    document.getElementById("forgot_step2").addEventListener("submit", function(e) {
        e.preventDefault();
        let email = document.getElementById("forgot_email").value;
        let otp = document.getElementById("forgot_otp").value;

        fetch("verify-otp.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    email: email,
                    otp: otp
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === "success") {
                    document.getElementById("forgot_step2").style.display = "none";
                    document.getElementById("forgot_step3").style.display = "block";
                } else {
                    alert(data.msg);
                }
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

<!-- Chatbot Box -->
<div id="chatbot-box"
    style="display:none; position:fixed; bottom:80px; right:30px; width:350px; max-width:95vw; z-index:9999; background:#fff; border-radius:12px; box-shadow:0 4px 24px rgba(0,0,0,0.15); border:1px solid #eee;">
    <main class="chat">
        <!-- Header -->
        <header class="chat-header">
            <div class="agent">
                <div class="agent-avatar">🤖</div>
                <div class="agent-info">
                    <div class="agent-name">Chatbot TMI</div>
                    <div class="status" id="status">Sẵn sàng</div>
                </div>
            </div>
            <button class="chat-close" onclick="toggleChatbotBox()">✖</button>
        </header>

        <!-- Nội dung chat -->
        <section id="messages" class="messages" aria-live="polite">
            <div class="msg">
                <div class="avatar">🤖</div>
                <div class="bubble">
                    <div class="meta">Chatbot TMI</div>
                    <div class="content">
                        Chào bạn! Tôi là Chatbot TMI. Tôi có thể giúp gì cho bạn?
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="composer">
            <form id="chat-form">
                <textarea id="input" rows="1" placeholder="Nhập tin nhắn..." required></textarea>
                <div class="toolbar">
                    <button type="submit" class="send">Gửi</button>
                </div>
            </form>
        </footer>
    </main>
</div>

<script>
    // Toggle hiển thị hộp chatbot
    function toggleChatbotBox() {
        const box = document.getElementById("chatbot-box");
        if (box) { // Đảm bảo phần tử tồn tại trước khi thao tác
            if (box.style.display === "none" || box.style.display === "") {
                box.style.display = "block";
            } else {
                box.style.display = "none";
            }
        }
    }
    // Khởi tạo biến DOM
    const messagesEl = document.getElementById('messages'); // container chứa tất cả message
    const formEl = document.getElementById('chat-form'); // form gửi message
    const inputEl = document.getElementById('input'); // textarea / input cho người dùng
    const statusEl = document.getElementById('status'); // phần hiển thị trạng thái (ví dụ: Đang suy nghĩ...)
    // Lưu trữ lịch sử cuộc hội thoại
    const conversation = [];
    // Tăng/giảm chiều cao textarea theo nội dung
    function autoGrow(el) {
        el.style.height = 'auto';
        el.style.height = Math.min(el.scrollHeight, 160) + 'px';
    }
    // Trả về chuỗi thời gian theo locale 'vi-VN'
    function nowIso() {
        return new Date().toLocaleString('vi-VN');
    }
    // Hàm tạo và hiển thị một message vào DOM
    function renderMessage(role, html, sources = []) {
        const wrapper = document.createElement('div');
        wrapper.className = 'msg' + (role === 'user' ? ' user' : '');

        const avatar = document.createElement('div');
        avatar.className = 'avatar';
        avatar.textContent = role === 'user' ? '🧑' : '🤖';

        const bubble = document.createElement('div');
        bubble.className = 'bubble' + (role === 'user' ? ' user' : '');

        const meta = document.createElement('div');
        meta.className = 'meta';
        meta.textContent = (role === 'user' ? 'Bạn' : 'Chatbot TMI') + ' • ' + nowIso();

        const content = document.createElement('div');
        content.className = 'content';
        content.innerHTML = html;

        bubble.appendChild(meta);
        bubble.appendChild(content);

        if (sources && sources.length > 0) {
            const ul = document.createElement('ul');
            ul.className = 'source-list';
            sources.forEach((s) => {
                const li = document.createElement('li');
                li.textContent = s;
                ul.appendChild(li);
            });
            bubble.appendChild(ul);
        }

        wrapper.appendChild(avatar);
        wrapper.appendChild(bubble);
        messagesEl.appendChild(wrapper);
        messagesEl.scrollTop = messagesEl.scrollHeight;
    }
    // Cập nhật trạng thái phía dưới input
    function setStatus(text) {
        statusEl.textContent = text;
    }
    // Hàm chính: gửi message lên server và xử lý phản hồi
    async function sendMessage(text) {
        setStatus('Đang suy nghĩ...');
        renderMessage('user', escapeHtml(text));
        const sendBtn = document.querySelector('.send');
        if (sendBtn) sendBtn.disabled = true;

        const payload = {
            message: text,
            history: conversation,
        };

        try {
            const res = await fetch('server/chat.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(payload),
            });

            if (!res.ok) {
                const errText = await res.text();
                throw new Error('HTTP ' + res.status + ': ' + errText);
            }

            const data = await res.json();
            const answer = data.reply || 'Xin lỗi, tôi chưa có câu trả lời.';
            const html = markdownToHtml(answer);
            renderMessage('assistant', html, data.sources || []);

            conversation.push({
                role: 'user',
                content: text
            });
            conversation.push({
                role: 'assistant',
                content: answer
            });
        } catch (e) {
            console.error(e);
            renderMessage('assistant', '❌ Lỗi: ' + escapeHtml(e.message));
        } finally {
            setStatus('Sẵn sàng');
            if (sendBtn) sendBtn.disabled = false;
        }
    }
    // Escape các ký tự HTML để tránh XSS khi render bằng innerHTML
    function escapeHtml(str) {
        return str
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }
    // Chuyển Markdown rất cơ bản sang HTML
    function markdownToHtml(md) {
        // Minimal MD to HTML: paragraphs, bold, italic, code, links, lists
        let html = md;
        html = escapeHtml(html);
        html = html.replace(/\*\*(.*?)\*\*/g, '<strong>$1<\/strong>');
        html = html.replace(/\*(.*?)\*/g, '<em>$1<\/em>');
        html = html.replace(/`([^`]+)`/g, '<code>$1<\/code>');
        html = html.replace(/\[(.*?)\]\((.*?)\)/g, '<a href="$2" target="_blank" rel="nofollow noopener">$1<\/a>');
        // lists
        html = html.replace(/(^|\n)-\s+(.*?)(?=\n(?!-\s)|$)/gs, (m) => {
            const items = m.trim().split(/\n-\s+/).map(s => s.replace(/^(-\s+)/, ''));
            return '<ul>' + items.map(i => '<li>' + i + '<\/li>').join('') + '<\/ul>';
        });
        html = html.replace(/\n\n/g, '<br><br>');
        return html;
    }

    // Handle form
    inputEl.addEventListener('input', () => autoGrow(inputEl));
    formEl.addEventListener('submit', (e) => {
        e.preventDefault();
        const text = inputEl.value.trim();
        if (!text) return;
        inputEl.value = '';
        autoGrow(inputEl);
        sendMessage(text);
    });

    // Suggest buttons
    document.querySelectorAll('.suggest').forEach(btn => {
        btn.addEventListener('click', () => {
            sendMessage(btn.textContent);
        });
    });

    // Mobile sidebar toggle
    const sidebar = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebar-overlay');
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');

    function toggleSidebar() {
        sidebar.classList.toggle('open');
        sidebarOverlay.classList.toggle('active');
    }

    function closeSidebar() {
        sidebar.classList.remove('open');
        sidebarOverlay.classList.remove('active');
    }

    mobileMenuBtn.addEventListener('click', toggleSidebar);
    sidebarOverlay.addEventListener('click', closeSidebar);

    // Close sidebar when clicking suggest buttons on mobile
    document.querySelectorAll('.suggest').forEach(btn => {
        btn.addEventListener('click', () => {
            if (window.innerWidth <= 980) {
                closeSidebar();
            }
        });
    });

    // Handle window resize
    window.addEventListener('resize', () => {
        if (window.innerWidth > 980) {
            closeSidebar();
        }
    });
</script>