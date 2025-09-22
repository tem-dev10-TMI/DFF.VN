    <main class="main-content">



        <?php
        $isFollowing = false; // mặc định chưa theo dõi
        $followersCount = $stats['followers'] ?? 0;

        if (isset($_SESSION['user']['id']) && !empty($businessman['user_id'])) {
            require_once __DIR__ . '/../../model/user/UserFollowModel.php';
            $db = new connect();
            $pdo = $db->db;
            $followModel = new UserFollowModel($pdo);

            $isFollowing = $followModel->isFollowing($_SESSION['user']['id'], $businessman['user_id']);
        }
        if (!isset($businessman) || !is_array($businessman)) {
            // Nếu có id trên URL thì cố load dữ liệu (quick-fix)
            if (isset($_GET['id']) && is_numeric($_GET['id'])) {
                require_once __DIR__ . '/../../model/user/businessmenModel.php';
                $businessman = businessmenModel::getBusinessByUserId((int) $_GET['id']);
                $careers = !empty($businessman['businessman_id'])
                    ? businessmenModel::getCareersByBusinessmenId($businessman['businessman_id'])
                    : [];
                $stats = !empty($businessman['user_id'])
                    ? businessmenModel::getBusinessStats($businessman['user_id'])
                    : ['articles' => 0, 'followers' => 0, 'following' => 0, 'likes' => 0];
            } else {
                // fallback để tránh warnings
                $businessman = [
                    'name' => 'Chưa có tên',
                    'user_id' => 0,
                    'avatar_url' => '/Upload/img_static/profile_default.png',
                    'birth_year' => null,
                    'nationality' => null,
                    'education' => null,
                    'current_position' => null,
                    'user_description' => null,
                    'businessman_id' => null
                ];
                $careers = [];
                $stats = ['articles' => 0, 'followers' => 0, 'following' => 0, 'likes' => 0];
            }
        }

        ?>

        <!-- bài viết chính block start -->
        <div class="content-left cover-page" bis_skin_checked="1">

            <div class="block-k box-company-label">
                <div class="block-t p-t-20" bis_skin_checked="1">
                    <div class="block-t-top" bis_skin_checked="1">
                        <img alt="image" class="profile-img"
                            src="<?= $businessman['avatar_url'] ?>">
                    </div>
                    <div class="h-topic">
                        <ul>
                            <li class="alias">Doanh nhân</li>
                            <li>
                                <a href="#" class="nam-follow">
                            <li><a href="#" class="nam-follow"><?= e($businessman['name']) ?></a></li>

                            </a>
                            </li>
                            <div class="profile-actions" style="margin-top:15px;">
                                <a class="btn-follow" href="javascript:void(0)"
                                    data-user="<?= $businessman['user_id'] ?>"
                                    style="display:inline-block;padding:8px 16px;border-radius:6px;
              background:<?= $isFollowing ? '#6c757d' : '#28a745' ?>;
              color:#fff;font-weight:600;text-decoration:none;">
                                    <span class="follow-text"><?= $isFollowing ? "Đang theo dõi" : "Theo dõi" ?></span>
                                    (<span class="number"><?= intval($followersCount) ?></span>)
                                </a>
                            </div>


                        </ul>
                    </div>

                </div>
            </div>

            <div class="box-history" bis_skin_checked="1">
                <div class="persion-mmn" bis_skin_checked="1">
                    <ul>

                        <li>
                            <span>Họ và tên</span>
                            <div bis_skin_checked="1">
                                <pre><?= e($businessman['name'] ?? 'Chưa có tên') ?></pre>
                            </div>
                        </li>

                        <li>
                            <span>Ngày sinh</span>
                            <div bis_skin_checked="1">
                                <pre><?= e($businessman['birth_year'] ?? 'Chưa có ngày sinh') ?></pre>
                            </div>
                        </li>

                        <li>
                            <span>Quốc tịch</span>
                            <div bis_skin_checked="1">
                                <pre><?= e($businessman['nationality'] ?? 'Chưa có quốc tịch') ?></pre>
                            </div>
                        </li>

                        <li>
                            <span>Trình độ</span>
                            <div bis_skin_checked="1">
                                <pre><?= e($businessman['education'] ?? 'Chưa có trình độ') ?></pre>
                            </div>
                        </li>

                        <li>
                            <span>Chức vụ</span>
                            <div bis_skin_checked="1">
                                <pre><?= e($businessman['current_position'] ?? 'Chưa có chức vụ') ?></pre>
                            </div>
                        </li>

                        <li>
                            <span>Vị trí khác</span>
                            <div bis_skin_checked="1">
                                <pre><?= e($businessman['user_description'] ?? 'Chưa có mô tả') ?></pre>
                            </div>
                        </li>

                        <li>
                            <span>Quá trình công tác</span>
                            <ul class="ul-history">
                                <?php if (!empty($careers)): ?>
                                    <?php foreach ($careers as $career): ?>
                                        <li>
                                            <div class="cv-history" bis_skin_checked="1"></div>
                                            <h3><?= e($career['start_year'] ?? '') ?> - <?= e($career['end_year'] ?? 'nay') ?></h3>
                                            <div class="item" bis_skin_checked="1">
                                                <pre><?= e($career['position'] ?? '') ?>, <?= e($career['company'] ?? '') ?></pre>
                                                <pre><?= e($career['description'] ?? '') ?></pre>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <li>
                                        <pre>Chưa có quá trình công tác</pre>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </li>

                    </ul>
                </div>
            </div>
            <!-- ✅ KHUNG NHẬP BÌNH LUẬN -->
            <div class="comment-input-box"
                style="border:1px solid #ccc;border-radius:8px;padding:15px;margin-bottom:20px;background:#fafafa;">
                <h5 style="margin-top:0;margin-bottom:10px;">
                    <i class="fas fa-comments"></i> Viết bình luận
                </h5>
                <div style="display:flex;align-items:flex-start;gap:10px;">
                    <img src="/Upload/img_static//profile_638930336755560936.png"
                        style="width:40px;height:40px;border-radius:50%;object-fit:cover;">
                    <textarea id="comment-text"
                        placeholder="Bạn nghĩ gì về nội dung này?"
                        style="flex:1;min-height:60px;padding:8px;border:1px solid #ccc;border-radius:6px;"></textarea>
                    <button id="send-comment"
                        style="background:#007bff;color:#fff;border:none;border-radius:6px;padding:8px 14px;cursor:pointer;">
                        Gửi
                    </button>
                </div>
                <div id="comment-message" style="display:none;color:green;margin-top:8px;">Đã thêm bình luận</div>
            </div>

            <!-- Khung hiển thị bình luận -->
            <div class="comment-display-box"
                style="border:1px solid #ccc;border-radius:8px;padding:15px;background:#fff;display:none;">
                <h5 style="margin:0 0 10px 0;">Bình luận đã đăng</h5>
                <ul id="comment-list" style="list-style:none;margin:0;padding:0;"></ul>
            </div>


            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const sendBtn = document.getElementById('send-comment');
                    const textarea = document.getElementById('comment-text');
                    const message = document.getElementById('comment-message');
                    const displayBox = document.querySelector('.comment-display-box');
                    const commentList = document.getElementById('comment-list');

                    function createCommentItem(text) {
                        const li = document.createElement('li');
                        li.style.cssText = 'display:block;width:100%;margin-bottom:12px;'; // <- quan trọng
                        li.innerHTML = `
        <div style="display:flex;align-items:flex-start;gap:10px;">
        <img src="/Upload/img_static//profile_638930336755560936.png"
            style="width:40px;height:40px;border-radius:50%;object-fit:cover;">
        <div style="background:#f1f1f1;padding:10px;border-radius:6px;flex:1;">
            <span style="white-space:pre-wrap;">${text}</span>
        </div>
        </div>
    `;
                        return li;
                    }


                    // Lấy bình luận cũ từ localStorage, hiển thị cũ trước mới sau
                    const savedComments = JSON.parse(localStorage.getItem('comments') || '[]');
                    if (savedComments.length > 0) {
                        savedComments.forEach(text => commentList.prepend(createCommentItem(text)));
                        displayBox.style.display = 'block';
                    }

                    sendBtn.addEventListener('click', function() {
                        const text = textarea.value.trim();
                        if (!text) return;

                        // Lưu mới nhất lên đầu mảng để lần tải sau vẫn giữ thứ tự mới -> cũ
                        savedComments.unshift(text);
                        localStorage.setItem('comments', JSON.stringify(savedComments));

                        // Hiển thị mới nhất lên trên cùng
                        commentList.prepend(createCommentItem(text));
                        displayBox.style.display = 'block';

                        message.style.display = 'block';
                        setTimeout(() => {
                            message.style.display = 'none';
                        }, 1500);
                        textarea.value = '';
                    });
                });
            </script>








        </div>

        <!-- bài viết chính block end -->


        <div class="content-right">
            <div class="adv-banner">
                <a href="#" target="_blank" rel="nofollow">
                    <img src="<?= BASE_URL ?>/public/img/banner/Post4.jpg" alt="Banner" />
                </a>
            </div>

            <div class="adv-banner">
                <a href="#" target="_blank" rel="nofollow">
                    <img src="<?= BASE_URL ?>/public/img/banner/Post3.jpg" alt="Banner" />
                </a>
            </div>

            <div class="adv-banner">
                <a href="#" target="_blank" rel="nofollow">
                    <img src="<?= BASE_URL ?>/public/img/banner/Post1.jpg" alt="Banner" />
                </a>
            </div>

            <div class="adv-banner">
                <a href="#" target="_blank" rel="nofollow">
                    <img src="<?= BASE_URL ?>/public/img/banner/Post2.jpg" alt="Banner" />
                </a>
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

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const btn = document.querySelector(".btn-follow");
                if (!btn) return;

                btn.addEventListener("click", function() {
                    const userId = this.getAttribute("data-user");

                    fetch("<?= BASE_URL ?>/controller/account/toggle_follow.php", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/x-www-form-urlencoded"
                            },
                            body: "user_id=" + encodeURIComponent(userId),
                            credentials: "include"
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                this.querySelector(".follow-text").innerText =
                                    data.action === "follow" ? "Đang theo dõi" : "Theo dõi";
                                this.querySelector(".number").innerText = data.followers;

                                // đổi màu nút
                                if (data.action === "follow") {
                                    this.style.background = "#6c757d"; // xám
                                } else {
                                    this.style.background = "#28a745"; // xanh
                                }
                            } else {
                                alert(data.message || "Có lỗi xảy ra!");
                            }
                        })
                        .catch(err => {
                            console.error(err);
                            alert("Không thể kết nối đến server!");
                        });
                });
            });
        </script>


    </main>