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



    <!-- bài viết chính block start -->
    <div class="content-left cover-page">
       <div class="block-k box-write openModalcreatePost">
            <a href="javascript:void(0)" class="img-own"> <img src="https://dff.vn/vendor/dffvn/content/img/user.svg">
            </a>
            <div class="input-group box-search">
                <div data-bs-toggle="modal"><span>Viết
                        bài, chia sẻ, đặt câu hỏi…</span></div>

            </div>
            <img alt="Viết bài, chia sẻ, đặt câu hỏi" module-load="loadwrite"
                src="https://dff.vn/vendor/dffvn/content/img/img_small.jpg" width="30">
        </div>
        <script>
            document.querySelector(".openModalcreatePost").addEventListener("click", function () {
                <?php if (isset($_SESSION['user_id'])): ?>
                    // Nếu đã đăng nhập thì mở modal
                    var myModal = new bootstrap.Modal(document.getElementById('createPostModal'));
                    myModal.show();
                <?php else: ?>
                    // Nếu chưa đăng nhập thì chuyển sang login hoặc cảnh báo
                    alert("Bạn cần đăng nhập để viết bài.");
                <?php endif; ?>
            });

        </script>



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
                                 <a href="<?= BASE_URL ?>/details_blog/<?= $article['slug'] ?>" 
   title="<?= htmlspecialchars($article['title']) ?>">
   <?= htmlspecialchars($article['title']) ?>
</a>


                                </span><span class="date"> <?= timeAgo($article['created_at']) ?></span>
                            </div>
                        </div>

                        <div class="title">
                            <a title="<?= htmlspecialchars($article['title']) ?>"
                                href="<?= BASE_URL ?>/details_blog/<?= $article['slug'] ?>">
                                <?= htmlspecialchars($article['title']) ?>
                            </a>
                        </div>
                        <div class="sapo">
                            <?= htmlspecialchars($article['summary']) ?>
                            <a href="<?= BASE_URL ?>/details_blog/<?= $article['slug'] ?>" class="d-more">Xem thêm</a>
                        </div>


                        <?php if (!empty($article['main_image_url'])): ?>
                            <img class="h-img" src="<?= htmlspecialchars($article['main_image_url']) ?>"
                                title="<?= htmlspecialchars($article['title']) ?>" alt="<?= htmlspecialchars($article['title']) ?>" border="0">
                        <?php endif; ?>

                        <div class="item-bottom">
   

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
    </div>

    <style>
        .gradient-animated {
            /* Tạo hiệu ứng gradient chuyển màu liên tục mượt mà */
            background: linear-gradient(270deg, #ff6a00, #ee0979, #00d2ff, #3a1c71);
            background-size: 800% 800%;
            animation: gradientShift 30s linear infinite alternate;
        }

        @keyframes gradientShift {
            0% {
                background-position: 0% 50%;
            }

            100% {
                background-position: 100% 50%;
            }
        }
    </style>



</main>