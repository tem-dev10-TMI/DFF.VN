<div class="collapse navbar-collapse m-menu-i" id="navbarNav5">
        <style>
            @media (max-width: 991.98px) {
                .m-menu-i {
                    max-height: calc(100vh - 60px);
                    overflow-y: auto;
                    overscroll-behavior: contain;
                    -webkit-overflow-scrolling: touch;
                    background: #fff;
                }
                .m-menu-i .line { margin: 10px 0; }
                .m-menu-i .top-item li { margin-bottom: 8px; }
            }
        </style>

        <ul class="">
            <li class="item"><svg class="home-icon" rpl="" fill="currentColor" height="20" icon-name="home-outline"
                    viewBox="0 0 20 20" width="20" xmlns="http://www.w3.org/2000/svg">
                    <!--?lit$03863286$--><!--?lit$03863286$-->
                    <path
                        d="m17.71 8.549 1.244.832v8.523a1.05 1.05 0 0 1-1.052 1.046H12.73a.707.707 0 0 1-.708-.707v-4.507c0-.76-1.142-1.474-2.026-1.474-.884 0-2.026.714-2.026 1.474v4.507a.71.71 0 0 1-.703.707H2.098a1.046 1.046 0 0 1-1.052-1.043V9.381l1.244-.835v9.158h4.44v-3.968c0-1.533 1.758-2.72 3.27-2.72s3.27 1.187 3.27 2.72v3.968h4.44V8.549Zm2.04-1.784L10.646.655a1.12 1.12 0 0 0-1.28-.008L.25 6.765l.696 1.036L10 1.721l9.054 6.08.696-1.036Z">
                    </path><!--?-->
                </svg>
                <a href="home" title="Trang chủ">Trang chủ</a>
            </li>
            <li class="item">
                <i class="bi bi-newspaper idiscuss"></i>
                <a href="news" title="Trang chủ">Mới nhất</a>
            </li>
            <li class="item">
                <i class="bi bi-box-arrow-up-right trend-icon"></i>
                <a href="trends" title="Trang chủ">Xu hướng</a>
            </li>
            

        </ul>
        <div class="line"></div>

        <label class="bg-tranparent">CHỦ ĐỀ</label>
        <?php if (!empty($topTopics)): ?>
        <ul class=" top-item">
            <?php foreach ($topTopics as $topic): ?>
            <li>
                <img src="<?= htmlspecialchars($topic['icon_url']) ?>" title="<?= htmlspecialchars($topic['name']) ?>" alt="<?= htmlspecialchars($topic['name']) ?>">
                <a title="<?= htmlspecialchars($topic['name']) ?>" href="details_topic?id=<?= htmlspecialchars($topic['id']) ?>">
                    <?= htmlspecialchars($topic['name']) ?>
                </a>
            </li>
            <?php endforeach; ?>
        </ul>
        <?php else: ?>
            <p>Chưa có chủ đề nào</p>
        <?php endif; ?>

        <?php if (!empty($moreTopics)): ?>
        <div id="m-collapseTopics" class="accordion-collapse collapse">
            <ul class=" top-item">
                <?php foreach ($moreTopics as $topic): ?>
                <li>
                    <img src="<?= htmlspecialchars($topic['icon_url']) ?>" title="<?= htmlspecialchars($topic['name']) ?>" alt="<?= htmlspecialchars($topic['name']) ?>">
                    <a title="<?= htmlspecialchars($topic['name']) ?>" href="details_topic?id=<?= htmlspecialchars($topic['id']) ?>">
                        <?= htmlspecialchars($topic['name']) ?>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="to-expend">
            <i class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#m-collapseTopics" aria-expanded="false" aria-controls="m-collapseTopics"></i>
        </div>
        <?php endif; ?>

        <div class="line"></div>
        <ul class=" about-c">
            <li><i class="bi bi-tv"></i>
                <a href="about#"> Về chúng tôi</a>
            </li>
            <li>
                <i class="bi bi-book"></i>
                <a href="about#gioithieu"> Chính sách nội dung</a>
            </li>
            <li><svg rpl="" fill="currentColor" height="20" icon-name="topic-law-outline" viewBox="0 0 20 20" width="20"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M2.3 8.625 3.621 5.31l1.324 3.315h1.346L4.256 3.53a1.37 1.37 0 0 1 1.362-1.28h8.764a1.37 1.37 0 0 1 1.362 1.28l-2.035 5.1h1.346l1.324-3.32L17.7 8.625h1.346l-2.061-5.16A2.62 2.62 0 0 0 14.382 1H5.618a2.62 2.62 0 0 0-2.606 2.465L.951 8.625H2.3Z">
                    </path>
                    <path
                        d="M6.617 10H.625a.625.625 0 0 0-.625.625 3.62 3.62 0 1 0 7.242 0A.625.625 0 0 0 6.617 10Zm-3 3a2.376 2.376 0 0 1-2.288-1.75h4.58A2.376 2.376 0 0 1 3.621 13h-.004Z">
                    </path>
                    <path
                        d="M19.375 10h-5.992a.624.624 0 0 0-.625.625 3.622 3.622 0 0 0 6.966 1.386c.182-.44.276-.91.276-1.386a.624.624 0 0 0-.625-.625Zm-3 3a2.376 2.376 0 0 1-2.288-1.75h4.576A2.375 2.375 0 0 1 16.379 13h-.004Z">
                    </path>
                    <path d="M10.625 5h-1.25v12.7H6.479v1.25h7.042V17.7h-2.896V5Z"></path>
                </svg>
                <a href="about#thuthap"> Chính sách riêng tư</a>
            </li>
            <li><i class="bi bi-badge-ad"></i>
                <a href="policy.html#advertisement"> Quảng cáo </a>
            </li>
        </ul>



    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var nav = document.getElementById('navbarNav5');
            if (!nav) return;
            nav.addEventListener('shown.bs.collapse', function () {
                document.body.style.overflow = 'hidden';
            });
            nav.addEventListener('hidden.bs.collapse', function () {
                document.body.style.overflow = '';
            });
        });
    </script>