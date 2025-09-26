<?php
// Start session nếu chưa có
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../time.php';
?>

<script>
  // Pass the session token from PHP to a global JavaScript variable
  window.userSessionToken = "<?= htmlspecialchars($_SESSION['user']['session_token'] ?? '') ?>";
</script>

<style>
  .cover {
    height: 200px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    position: relative;
    border-radius: 8px;
    margin-bottom: 80px;
  }

  .modal-footer {
    padding-bottom: 60px;
    /* đẩy nút lên một chút */
  }

  .avatar {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    border: 4px solid white;
    position: absolute;
    bottom: -60px;
    left: 50%;
    transform: translateX(-50%);
    background-color: #fff;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  }

  .cover-img {
    width: 100%;
    /* Chiếm toàn bộ chiều rộng của div cha */
    height: 100%;
    /* Chiếm toàn bộ chiều cao của div cha */
    object-fit: cover;
    /* Cắt ảnh để vừa vặn mà không làm méo ảnh */
    border-radius: 8px;
    /* Bo góc giống với div cha */
  }

  .sidebar {
    background: white;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 15px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  }

  .post-box {
    background: white;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  }

  .post-img {
    max-width: 100%;
    border-radius: 8px;
    margin-top: 10px;
  }

  .comment {
    font-size: 14px;
    margin-top: 5px;
  }

  /* CSS cho Profile User */
  .user-info .info-item strong {
    color: #124889;
    font-size: 14px;
  }

  .user-info .info-item span {
    font-size: 13px;
  }

  .user-stats .stat-item {
    font-size: 14px;
  }

  .interest-tags .badge {
    font-size: 12px;
  }

  .user-actions .btn {
    font-size: 13px;
  }

  .user-actions .btn-warning {
    background-color: #ffc107;
    border-color: #ffc107;
    color: #000;
    font-weight: 500;
  }

  .user-actions .btn-warning:hover {
    background-color: #e0a800;
    border-color: #d39e00;
    color: #000;
  }

  /* Responsive */
  @media (max-width: 768px) {
    .sidebar {
      margin-bottom: 20px;
    }
  }
</style>

<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

// kết nối DB
require_once __DIR__ . '/../../config/db.php';
$db = new connect();
$pdo = $db->db;

// Lấy user_id từ session
$user_id = $_SESSION['user']['id'] ?? null;

// Mặc định là chưa có request
$hasBusinessRequest = false;

// Chỉ kiểm tra khi là tài khoản user & đã đăng nhập
if ($profile_category == 'user' && $user_id) {
  try {
    // Kiểm tra bảng có tồn tại không trước khi query
    $check = $pdo->query("SHOW TABLES LIKE 'businessmen_requests'");
    if ($check->rowCount() > 0) {
      $stmt = $pdo->prepare("
                SELECT id 
                FROM businessmen_requests 
                WHERE user_id = ? AND status = 'pending'
            ");
      $stmt->execute([$user_id]);
      $hasBusinessRequest = $stmt->fetchColumn();
    }
  } catch (PDOException $e) {
    // Ghi log nếu cần
    error_log("Lỗi khi kiểm tra businessmen_requests: " . $e->getMessage());
  }
}
?>

<div class="container mt-3">
  <!-- Cover -->
  <div class="cover">

      <?php
      // Lấy cover photo từ session hoặc database
      $coverUrl = $_SESSION['user']['cover_photo'] ?? $_SESSION['user_cover_photo'] ?? null;
      if (!$coverUrl || trim($coverUrl) === '') {
        // Nếu không có cover photo, giữ background gradient
        $coverUrl = null;
      }
      ?>

      <?php if ($coverUrl): ?>
      <!-- Cover Image -->
      <img src="<?= htmlspecialchars($coverUrl) ?>?t=<?= time() ?>" class="cover-img" alt="cover" style="width: 100%; height: 100%; object-fit: cover; border-radius: 8px; position: absolute; top: 0; left: 0;">
    <?php endif; ?>

    
    <?php
    // Lấy avatar từ session nếu vừa upload, nếu không thì lấy từ database
    $avatarUrl = $_SESSION['user']['avatar_url'] ?? $user['avatar_url'] ?? '';
    if (!$avatarUrl || trim($avatarUrl) === '') {
      $avatarUrl = 'https://i.pinimg.com/1200x/83/0e/ea/830eea38f7a5d3d8e390ba560d14f39c.jpg';
    }

    // Lấy cover từ session hoặc database
    $coverUrl = $_SESSION['user']['cover_photo'] ?? $user['cover_photo'] ?? '';
    if (!$coverUrl || trim($coverUrl) === '') {
      $coverUrl = 'https://via.placeholder.com/800x250?text=Default+Cover';
    }
    ?>

    <!-- Cover -->
    <img src="<?= htmlspecialchars($coverUrl) ?>?t=<?= time() ?>" class="cover-img" alt="cover">

    <!-- Avatar -->
    <div class="avatar-box">
      <img src="<?= htmlspecialchars($avatarUrl) ?>" class="avatar" alt="avatar">
    </div>
  </div>
  <style>
    /* Định nghĩa animation cho hiệu ứng gradient */
    .user-gradient-name-profile {
      /* Kích thước và trọng lượng chữ sẽ được kế thừa từ thẻ cha (H4) */

      /* Hiệu ứng gradient cho chữ */
      display: inline-block;
      /* Cần thiết để background-clip hoạt động đúng */
      color: transparent;
      -webkit-background-clip: text;
      background-clip: text;
      -webkit-text-fill-color: transparent;

      /* Định nghĩa màu và kích thước cho gradient */
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
          /* Tím vũ trụ (lặp lại) */
        );
      background-size: 400% 400%;

      /* Animation */
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
  <div class="text-center" style="margin-top: -20px;">
    <h4 class="fw-bold mb-0 user-gradient-name-profile">
      <?= htmlspecialchars($_SESSION['user']['name'] ?? 'Tên người dùng') ?>
      <?php if (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'businessmen'): ?>
        <i class="fas fa-check-circle text-primary" title="Tài khoản doanh nhân đã xác minh"></i>
      <?php endif; ?>
    </h4>

    <p class="text-muted mb-3">
      @<?= htmlspecialchars($_SESSION['user']['username'] ?? 'username') ?>
    </p>
  </div>

  <!-- Hidden data for JavaScript -->
  <div id="profileData"
    data-category="<?= htmlspecialchars($profile_category) ?>"
    data-user-id="<?= htmlspecialchars($_SESSION['user']['id'] ?? '') ?>"
    style="display: none;">
  </div>

  <div class="row mt-5">
    <!-- Sidebar -->
    <div class="col-md-3">
      <div class="sidebar">
        <!-- Thông tin cá nhân -->
        <div class="user-info">
          <h5 class="mb-3">Thông tin cá nhân</h5>
          <div class="info-item mb-2">
            <strong>Họ tên:</strong>
            <span class="text-muted"><?php echo htmlspecialchars($profileUser['name'] ?? 'Chưa cập nhật'); ?></span>
          </div>

          <div class="info-item mb-2">
            <strong>Email:</strong>
            <span class="text-muted"><?php echo htmlspecialchars($profileUser['email'] ?? 'Chưa cập nhật'); ?></span>
          </div>

          <div class="info-item mb-2">
            <strong>Nghề nghiệp:</strong>
            <span class="text-muted"><?php echo htmlspecialchars($profileUser['workplace'] ?? 'Chưa cập nhật'); ?></span>
          </div>

          <div class="info-item mb-2">
            <strong>Địa chỉ:</strong>
            <span class="text-muted"><?php echo htmlspecialchars($profileUser['live_at'] ?? 'Chưa cập nhật'); ?></span>
          </div>

          <div class="info-item mb-2">
            <strong>Tham gia:</strong>
            <span class="text-muted"><?php echo date("d/m/Y", strtotime($profileUser['user_created_at'])); ?></span>
          </div>
        </div>

        <!-- Thống kê cá nhân -->
        <div class="user-stats mt-4">
          <h6 class="mb-3">Thống kê</h6>
          <div class="stat-item d-flex justify-content-between mb-2">
            <span>Bài viết:</span>
            <span class="badge bg-primary"><?php echo $stats['articles']; ?></span>
          </div>
          <div class="stat-item d-flex justify-content-between mb-2">
            <span>Người theo dõi:</span>
            <span class="badge bg-success"><?php echo $stats['followers']; ?></span>
          </div>
          <div class="stat-item d-flex justify-content-between mb-2">
            <span>Đang theo dõi:</span>
            <span class="badge bg-info"><?php echo $stats['following']; ?></span>
          </div>
          <div class="stat-item d-flex justify-content-between mb-2">
            <span>Lượt thích:</span>
            <span class="badge bg-warning"><?php echo number_format($stats['likes']); ?></span>
          </div>
        </div>

        <!-- Sở thích/Chuyên môn -->
        <div class="user-interests mt-4">
          <h6 class="mb-3">Sở thích</h6>
          <div class="interest-tags">
            <span class="badge bg-light text-dark me-1 mb-1">#Đầu tư</span>
            <span class="badge bg-light text-dark me-1 mb-1">#Crypto</span>
            <span class="badge bg-light text-dark me-1 mb-1">#Chứng khoán</span>
            <span class="badge bg-light text-dark me-1 mb-1">#Fintech</span>
            <span class="badge bg-light text-dark me-1 mb-1">#Blockchain</span>
          </div>
        </div>

        <!-- Nút hành động -->
        <div class="user-actions mt-4">
          <button class="btn btn-outline-primary btn-sm w-100 mb-2" data-bs-toggle="modal" data-bs-target="#editProfileModal">
            <i class="fas fa-edit me-1"></i> Chỉnh sửa hồ sơ
          </button>
          <button class="btn btn-outline-success btn-sm w-100 mb-2">
            <i class="fas fa-share-alt me-1"></i> Chia sẻ hồ sơ
          </button>

          <?php if ($profile_category == 'user'): ?>
            <!-- Chỉ hiện khi là tài khoản thường -->
            <?php if ($profile_category == 'user'): ?>
              <?php if (empty($hasBusinessRequest)): ?>
                <!-- Chỉ hiện khi là user thường và chưa gửi request -->
                <button class="btn btn-warning btn-sm w-100 mb-2" onclick="convertToBusiness()">
                  <i class="fas fa-building me-1"></i> Chuyển thành doanh nhân
                </button>
              <?php else: ?>
                <!-- Nếu đã gửi yêu cầu thì báo chờ -->
                <div class="alert alert-warning py-2 text-center mb-2">
                  ⏳ Đã gửi yêu cầu, vui lòng chờ admin duyệt
                </div>
              <?php endif; ?>
            <?php endif; ?>
          <?php endif; ?>

          <a href="<?= BASE_URL ?>/saved_articles" class="btn btn-primary btn-sm w-100 mb-2">
            <i class="fas fa-bookmark me-1"></i> Bài viết đã lưu
          </a>

          <button class="btn btn-outline-info btn-sm w-100">
            <i class="fas fa-cog me-1"></i> Cài đặt
          </button>
        </div>
      </div>
    </div>

    <!-- Main content - CHỈ HIỂN THỊ BÀI VIẾT ĐÃ LƯU -->
    <div class="col-md-9">
      <!-- Header cho bài viết đã lưu -->
      <div class="post-box p-3 rounded-3 bg-white shadow-sm mb-3">
        <div class="d-flex justify-content-between align-items-center">
          <h4 class="mb-0"><i class="fas fa-bookmark me-2"></i>Bài viết đã lưu</h4>
          <span class="badge bg-primary"><?php echo count($savedArticles); ?> bài viết</span>
        </div>
      </div>

      <!-- Danh sách bài viết đã lưu -->
      <?php if (!empty($savedArticles)): ?>
        <div id="saved-articles-list">
          <?php foreach ($savedArticles as $article): ?>
            <div class="block-k article-item">
              <div class="view-carde f-frame">
                <div class="provider">
                  <?php
                  $authorAvatar = $article['avatar_url'] ?? 'https://i.pinimg.com/1200x/83/0e/ea/830eea38f7a5d3d8e390ba560d14f39c.jpg';
                  ?>
                  <img class="logo" alt="" src="<?= htmlspecialchars($authorAvatar) ?>">
                  <div class="p-covers">
                    <span class="name">
                      <a href="<?= BASE_URL ?>/view_profile?id=<?= $article['author_id'] ?>">
                        <?= htmlspecialchars($article['author_name']) ?>
                      </a>
                    </span>
                    <span class="date"><?= timeAgo($article['created_at']) ?></span>
                  </div>
                </div>

                <div class="title">
                  <a href="<?= BASE_URL . '/details_blog/' . $article['slug'] ?>" target="_self">
                    <?= htmlspecialchars($article['title']) ?>
                  </a>
                </div>

                <div class="sapo">
                  <?= htmlspecialchars($article['summary']) ?>
                  <a href="<?= 'details_blog/' . $article['slug'] ?>" class="d-more" target="_self">
                    Xem thêm
                  </a>
                </div>

                <?php if (!empty($article['main_image_url'])) : ?>
                  <img class="h-img" src="<?= htmlspecialchars($article['main_image_url']) ?>" alt="<?= htmlspecialchars($article['title']) ?>">
                <?php endif; ?>

                <!-- Actions -->
                <div class="item-bottom">
                  <div class="button-ar">
                    <div class="dropdown home-item">
                      <span data-bs-toggle="dropdown">Chia sẻ</span>
                      <ul class="dropdown-menu">
                        <?php
                        $shareUrl = BASE_URL . '/details_blog/' . urlencode($article['slug']);
                        ?>
                        <li><a class="dropdown-item copylink" data-url="<?= $shareUrl ?>" href="javascript:void(0)">Copy link</a></li>
                        <li><a class="dropdown-item sharefb" data-url="<?= $shareUrl ?>" href="javascript:void(0)">Share FB</a></li>
                      </ul>
                    </div>
                    
                    <!-- Nút bỏ lưu -->
                    <button class="btn btn-outline-danger btn-sm ms-2 unsave-article" 
                            data-article-id="<?= $article['id'] ?>"
                            data-article-slug="<?= $article['slug'] ?>">
                      <i class="fas fa-bookmark"></i> Bỏ lưu
                    </button>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <div class="block-k">
          <div class="view-carde f-frame">
            <div class="text-center p-4">
              <i class="fas fa-bookmark fa-3x text-muted mb-3"></i>
              <h5>Chưa có bài viết nào được lưu</h5>
              <p class="text-muted">Hãy lưu những bài viết bạn quan tâm để xem lại sau này.</p>
              <a href="<?= BASE_URL ?>" class="btn btn-primary">Khám phá bài viết</a>
            </div>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Xử lý bỏ lưu bài viết
    document.querySelectorAll('.unsave-article').forEach(button => {
        button.addEventListener('click', function() {
            const articleId = this.getAttribute('data-article-id');
            const articleSlug = this.getAttribute('data-article-slug');
            
            if (confirm('Bạn có chắc chắn muốn bỏ lưu bài viết này?')) {
                // Gọi API bỏ lưu
                fetch('<?= BASE_URL ?>/controller/ArticleSaveController.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'article_id=' + encodeURIComponent(articleId)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        // Xóa bài viết khỏi danh sách
                        const articleItem = this.closest('.article-item');
                        articleItem.remove();
                        
                        // Cập nhật số lượng bài viết
                        const badge = document.querySelector('.badge.bg-primary');
                        const currentCount = parseInt(badge.textContent);
                        badge.textContent = currentCount - 1 + ' bài viết';
                        
                        // Nếu không còn bài viết nào, hiển thị thông báo
                        if (currentCount - 1 === 0) {
                            const articlesList = document.getElementById('saved-articles-list');
                            articlesList.innerHTML = `
                                <div class="block-k">
                                    <div class="view-carde f-frame">
                                        <div class="text-center p-4">
                                            <i class="fas fa-bookmark fa-3x text-muted mb-3"></i>
                                            <h5>Chưa có bài viết nào được lưu</h5>
                                            <p class="text-muted">Hãy lưu những bài viết bạn quan tâm để xem lại sau này.</p>
                                            <a href="<?= BASE_URL ?>" class="btn btn-primary">Khám phá bài viết</a>
                                        </div>
                                    </div>
                                </div>
                            `;
                        }
                        
                        alert('Đã bỏ lưu bài viết');
                    } else {
                        alert('Có lỗi xảy ra: ' + data.msg);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra khi bỏ lưu bài viết');
                });
            }
        });
    });

    // Xử lý chia sẻ
    document.addEventListener('click', function(event) {
        const target = event.target;

        // Copy Link
        if (target.classList.contains('copylink')) {
            event.preventDefault();
            const urlToCopy = target.getAttribute('data-url');
            if (urlToCopy) {
                navigator.clipboard.writeText(urlToCopy).then(() => {
                    alert('Đã sao chép link!');
                }).catch(err => {
                    console.error('Lỗi khi sao chép: ', err);
                    alert('Không thể sao chép link.');
                });
            }
        }

        // Share to Facebook
        if (target.classList.contains('sharefb')) {
            event.preventDefault();
            const urlToShare = target.getAttribute('data-url');
            if (urlToShare) {
                const facebookShareUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(urlToShare)}`;
                window.open(facebookShareUrl, 'facebook-share-dialog', 'width=800,height=600');
            }
        }
    });
});
</script>