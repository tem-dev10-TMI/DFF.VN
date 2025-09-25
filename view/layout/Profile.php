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

  /* CSS cho Profile Business */
  .business-info .info-item strong {
    color: #124889;
    font-size: 14px;
  }

  .business-info .info-item span {
    font-size: 13px;
  }

  .business-stats .stat-item {
    font-size: 14px;
  }

  .business-certificates .cert-item {
    font-size: 13px;
  }

  .business-actions .btn {
    font-size: 13px;
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

  /* Cải thiện khu vực tạo bài viết */
  .post-box textarea {
    border: 1px solid #e0e0e0;
    border-radius: 6px;
    resize: vertical;
    min-height: 100px;
  }

  .post-box textarea:focus {
    border-color: #124889;
    box-shadow: 0 0 0 0.2rem rgba(18, 72, 137, 0.25);
  }

  .post-box .btn-primary {
    background-color: #124889;
    border-color: #124889;
  }

  .post-box .btn-primary:hover {
    background-color: #0d3a6b;
    border-color: #0d3a6b;
  }

  /* Responsive */
  @media (max-width: 768px) {
    .sidebar {
      margin-bottom: 20px;
    }
  }

  /* Modal responsive và scrollable */
  .modal-dialog {
    max-height: 90vh;
    margin: 1.75rem auto;
    overflow: hidden;
  }

  .modal-content {
    max-height: 90vh;
    display: flex;
    flex-direction: column;
    overflow: hidden;
  }

  .modal-body {
    flex: 1;
    overflow-y: auto;
    padding: 1rem;
    max-height: calc(90vh - 140px);
  }

  .modal-header {
    flex-shrink: 0;
    border-bottom: 1px solid #dee2e6;
  }

  .modal-footer {
    flex-shrink: 0;
    border-top: 1px solid #dee2e6;
  }

  /* Responsive cho modal */
  @media (max-width: 768px) {
    .modal-dialog {
      max-height: 95vh;
      margin: 0.5rem;
    }

    .modal-content {
      max-height: 95vh;
    }

    .modal-body {
      max-height: calc(95vh - 120px);
      padding: 0.75rem;
    }
  }

  /* Đảm bảo modal có thể scroll */
  .modal.show .modal-dialog {
    transform: none;
  }

  .modal-body {
    -webkit-overflow-scrolling: touch;
  }

  /* Cải thiện scrollbar cho modal */
  .modal-body::-webkit-scrollbar {
    width: 6px;
  }

  .modal-body::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
  }

  .modal-body::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
  }

  .modal-body::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
  }

  /* Force modal to be scrollable */
  .modal {
    overflow-y: auto;
  }

  .modal-dialog {
    position: relative;
    width: auto;
    margin: 0.5rem;
    pointer-events: none;
  }

  .modal-content {
    position: relative;
    display: flex;
    flex-direction: column;
    width: 100%;
    pointer-events: auto;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid rgba(0, 0, 0, .2);
    border-radius: 0.3rem;
    outline: 0;
  }

  /* Đảm bảo modal body có thể scroll */
  .modal-body {
    position: relative;
    flex: 1 1 auto;
    padding: 1rem;
    overflow-y: auto;
    overflow-x: hidden;
  }

  /* Fix for Bootstrap modal */
  @media (min-width: 576px) {
    .modal-dialog {
      max-width: 800px;
      margin: 1.75rem auto;
    }
  }

  .delete-post-icon {
    position: absolute;
    top: 10px;
    right: 10px;
    cursor: pointer;
    color: #dc3545;
    /* Bootstrap danger color */
    font-size: 1.2em;
    z-index: 1;
  }

  .delete-post-icon:hover {
    color: #bd2130;
    /* Darker red on hover */
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
<<<<<<< HEAD
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

=======
>>>>>>> parent of ef01db3 (update_avatarbusiness)
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

    <?php if ($profile_category == 'user') {
      require_once  __DIR__ . '/../page/ProfileUser.php';
    } ?>
    <?php if ($profile_category == 'businessmen') {
      require_once __DIR__ . '/../page/ProfileBusiness.php';
    } ?>
    <!-- Main content -->
    <div class="col-md-9">
      <!-- Write post -->
      <div class="post-box mb-3">
        <div class="d-flex align-items-center mb-3">
          <?php
          $avatarUrl = $user['avatar_url'] ?? null;
          if (!$avatarUrl || trim($avatarUrl) === '') {
            $avatarUrl = 'https://i.pinimg.com/1200x/83/0e/ea/830eea38f7a5d3d8e390ba560d14f39c.jpg';
          }
          ?>
          <img src="<?= htmlspecialchars($avatarUrl) ?>" class="rounded-circle me-2" alt="avatar" style="width: 40px; height: 40px;">
          <div>
            <h6 class="mb-0">
              <?php
              if ($profile_category == 'businessmen') {
                echo htmlspecialchars($business['name'] ?? 'Doanh nhân');
              } else {
                echo htmlspecialchars($profileUser['name'] ?? 'Người dùng');
              }
              ?>
            </h6>
            <small class="text-muted"><?php echo $profile_category == 'businessmen' ? 'Doanh nghiệp' : 'Cá nhân'; ?></small>
          </div>
        </div>
        <!-- Tiêu đề -->
        <input type="text" id="postTitle" class="form-control mb-2" placeholder="Nhập tiêu đề bài viết...">

        <!-- Tóm tắt -->
        <textarea id="postSummary" class="form-control mb-2" rows="2" placeholder="Tóm tắt ngắn gọn nội dung..."></textarea>

        <!-- Nội dung chính -->
        <textarea id="newPost" class="form-control mb-3" rows="4" placeholder="Nội dung chính của bài viết..."></textarea>

        <div class="mb-2">
          <label for="topicSelect" class="form-label">Chọn chủ đề:</label>
          <select class="form-select" id="topicSelect" name="topic_id" required>
            <option value="">-- Chọn chủ đề --</option>
            <?php foreach ($topics as $topic): ?>
              <option value="<?= $topic['id'] ?>"><?= htmlspecialchars($topic['name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <!-- Thanh công cụ -->
        <div class="d-flex justify-content-between align-items-center post-box">
          <div class="d-flex gap-2">
            <label class="btn btn-outline-secondary btn-sm mb-0" for="postImage">
              <i class="fas fa-image me-1"></i> Hình ảnh
            </label>
            <label class="btn btn-outline-secondary btn-sm mb-0" for="postVideo">
              <i class="fas fa-video me-1"></i> Video
            </label>
            <button class="btn btn-outline-secondary btn-sm" type="button">
              <i class="fas fa-link me-1"></i> Link
            </button>
          </div>
          <button class="btn btn-primary m-2" onclick="addPost()">
            <i class="fas fa-paper-plane me-1"></i> Đăng bài
          </button>
        </div>
        <!-- Input hidden -->
        <input type="hidden" name="session_token" value="<?= htmlspecialchars($_SESSION['user']['session_token'] ?? '') ?>">
        <input type="file" id="postImage" class="d-none" accept="image/*" multiple onchange="previewImage(event)">
        <input type="file" id="postVideo" class="d-none" accept="video/*" multiple onchange="previewVideo(event)">

      </div>

      <!-- Preview ảnh -->
      <div id="imagePreview" class="mt-2"></div>
      <div id="videoPreview" class="mt-2"></div>

      <!-- Posts -->
      <!-- Danh sách bài viết -->
      <div id="profileData"
        data-category="<?= $profile_category ?>"
        data-user-id="<?= isset($user_id) ? $user_id : 0 ?>">
      </div>
      <div id="posts">
        <div class="block-k" id="loadingPosts">
          <div class="view-carde f-frame">
            <div class="text-center p-4">
              <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Đang tải...</span>
              </div>
              <p class="mt-2 text-muted">Đang tải bài viết...</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<style>
  /* Giới hạn chiều cao modal */
  .modal-dialog {
    max-height: 90vh;
    /* không vượt quá 90% chiều cao màn hình */
    margin: auto;
  }

  /* Cho phần nội dung bên trong cuộn */
  .modal-content {
    max-height: 80vh;
    overflow-y: auto;
    /* bật scroll dọc */
    overflow-x: hidden;
    /* tránh scroll ngang */
  }

  /* Header modal fix trên cùng */
  .modal-header,
  .modal-footer {
    position: sticky;
    top: 0;
    z-index: 10;
    background: #fff;
    /* giữ nền trắng để không trong suốt */
  }
</style>

<!-- Modal xác nhận chuyển đổi -->
<div class="modal fade" id="convertModal" tabindex="-1" aria-labelledby="convertModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

      <!-- Header -->
      <div class="modal-header <?php echo $checkPendingBusiness ? 'bg-info text-white' : 'bg-warning text-dark'; ?>">
        <h5 class="modal-title d-flex align-items-center gap-2" id="convertModalLabel">
          <?php if ($checkPendingBusiness): ?>
            <i class="fas fa-hourglass-half"></i>
            Hồ sơ doanh nhân — Đang xét duyệt
          <?php else: ?>
            <i class="fas fa-building"></i>
            Đăng ký tài khoản doanh nhân
          <?php endif; ?>
        </h5>
        <button type="button" class="btn-close <?php echo $checkPendingBusiness ? 'btn-close-white' : ''; ?>" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <?php if ($checkPendingBusiness): ?>
          <!-- STATE: PENDING -->
          <div class="p-3">
            <div class="alert alert-info d-flex align-items-start gap-3 mb-3">
              <i class="fas fa-info-circle fs-4 mt-1"></i>
              <div>
                <strong>Hồ sơ của bạn đang được xét duyệt.</strong>
                <div class="small mt-1">
                  Vui lòng đợi khoảng <strong>1–2 ngày</strong> để chúng tôi kiểm tra.
                  Khi hoàn tất, hệ thống sẽ gửi thông báo cho bạn.
                </div>
              </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
              <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-3">
                  <div class="rounded-circle bg-light p-3">
                    <i class="fas fa-user-tie fs-3 text-primary"></i>
                  </div>
                  <div>
                    <div class="fw-semibold">Trạng thái hồ sơ</div>
                    <div class="badge bg-warning text-dark">Đang xét duyệt</div>
                  </div>
                </div>

                <ul class="list-unstyled mb-0 small">
                  <li class="d-flex align-items-start gap-2 mb-2">
                    <i class="fas fa-check-circle mt-1"></i>
                    Thông tin đã được gửi thành công.
                  </li>
                  <li class="d-flex align-items-start gap-2 mb-2">
                    <i class="fas fa-user-shield mt-1"></i>
                    Bộ phận kiểm duyệt sẽ xác minh tính hợp lệ (xác minh: đối chiếu thông tin cơ bản).
                  </li>
                  <li class="d-flex align-items-start gap-2">
                    <i class="fas fa-bell mt-1"></i>
                    Bạn sẽ nhận thông báo khi có kết quả (email/notification).
                  </li>
                </ul>
              </div>
            </div>

            <div class="text-center mt-3 small text-muted">
              Cần hỗ trợ? <a href="<?= BASE_URL ?>/support" class="text-decoration-none">Liên hệ hỗ trợ</a>.
            </div>
          </div>
        <?php else: ?>
          <!-- STATE: REGISTER (giữ nguyên form của master, có nâng giao diện nhẹ) -->
          <!-- Cảnh báo -->
          <div class="alert alert-warning py-2 mb-3">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Lưu ý:</strong>
            <small class="d-block mt-1">
              Chuyển đổi sang doanh nhân • Cần thông tin hợp lệ • Xét duyệt 1–3 ngày • Một số tính năng bị hạn chế
            </small>
          </div>

          <!-- Form đăng ký doanh nhân -->
          <form id="convertForm" method="POST" action="<?= BASE_URL ?>/register_business" class="needs-validation" novalidate>
            <input type="hidden" name="session_token" value="<?= htmlspecialchars($_SESSION['user']['session_token'] ?? '') ?>">
            <div class="row">
              <div class="col-md-6 mb-2">
                <label for="birthYear" class="form-label small">Năm sinh <span class="text-danger">*</span></label>
                <input type="number" min="1900" max="2099" class="form-control form-control-sm" id="birthYear" name="birth_year" required>
                <div class="invalid-feedback small">Vui lòng nhập năm sinh hợp lệ.</div>
              </div>
              <div class="col-md-6 mb-2">
                <label for="nationality" class="form-label small">Quốc tịch <span class="text-danger">*</span></label>
                <input type="text" class="form-control form-control-sm" id="nationality" name="nationality" required>
                <div class="invalid-feedback small">Vui lòng nhập quốc tịch.</div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 mb-2">
                <label for="education" class="form-label small">Học vấn</label>
                <input type="text" class="form-control form-control-sm" id="education" name="education" placeholder="VD: Cử nhân Kinh tế">
              </div>
              <div class="col-md-6 mb-2">
                <label for="position" class="form-label small">Chức vụ</label>
                <input type="text" class="form-control form-control-sm" id="position" name="position" placeholder="VD: CEO, Founder">
              </div>
            </div>

            <div class="form-check mb-2">
              <input class="form-check-input" type="checkbox" id="agreeTerms" name="agree_terms" required>
              <label class="form-check-label small" for="agreeTerms">
                Tôi đồng ý với <a href="#" class="text-primary">Điều khoản sử dụng</a> và <a href="#" class="text-primary">Chính sách bảo mật</a>
              </label>
              <div class="invalid-feedback small">Vui lòng đồng ý điều khoản.</div>
            </div>
          </form>
        <?php endif; ?>
      </div>

      <!-- Footer -->
      <div class="modal-footer">
        <?php if ($checkPendingBusiness): ?>
          <button type="button" class="btn btn-info text-white" data-bs-dismiss="modal">
            <i class="fas fa-times me-1"></i>Đóng
          </button>

        <?php else: ?>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="fas fa-times me-1"></i>Hủy
          </button>
          <button type="submit" class="btn btn-warning" onclick="submitConversion()">
            <i class="fas fa-building me-1"></i>Chuyển đổi
          </button>
        <?php endif; ?>
      </div>

    </div>
  </div>
</div>


<!-- Modal chỉnh sử thông tin người dùng  -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-fullscreen-md-down modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header bg-warning text-dark">
        <h5 class="modal-title"><i class="fas fa-user-edit me-2"></i>Chỉnh sửa hồ sơ</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <form action="<?= BASE_URL ?>/edit_profile" method="POST" enctype="multipart/form-data">
        <div class="modal-body">
          <input type="hidden" name="session_token" value="<?= htmlspecialchars($_SESSION['user']['session_token'] ?? '') ?>">

          <h6 class="text-muted mb-3">Thông tin tài khoản</h6>
          <div class="row g-3 mb-4">
            <div class="col-md-6">
              <label class="form-label">Tên hiển thị</label>
              <input type="text" class="form-control" name="display_name" value="<?= htmlspecialchars($user['name'] ?? '') ?>" placeholder="Tên bạn muốn mọi người thấy">
            </div>
            <div class="col-md-6">
              <label class="form-label">Username (Không thể đổi)</label>
              <input type="text" class="form-control" name="user_name" value="<?= htmlspecialchars($user['username'] ?? '') ?>" readonly>
              <small class="form-text text-muted">Dùng để đăng nhập.</small>
            </div>
            <div class="col-md-6">
              <label class="form-label">Email</label>
              <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" placeholder="example@email.com">
            </div>
            <div class="col-md-6">
              <label class="form-label">Số điện thoại</label>
              <input type="tel" class="form-control" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" placeholder="Số di động của bạn">
            </div>
          </div>

          <h6 class="text-muted mb-3">Thông tin cá nhân</h6>
          <div class="row g-3 mb-4">
            <div class="col-12">
              <label class="form-label">Mô tả bản thân</label>
              <textarea class="form-control" name="description" rows="3" placeholder="Vài dòng giới thiệu về bạn..."><?= htmlspecialchars($user['description'] ?? '') ?></textarea>
            </div>
            <div class="col-md-6">
              <label class="form-label">Năm sinh</label>
              <input type="number" class="form-control" name="birth_year" value="<?= htmlspecialchars($user['birth_year'] ?? '') ?>" placeholder="Ví dụ: 1995">
            </div>
            <div class="col-md-6">
              <label class="form-label">Địa chỉ</label>
              <input type="text" class="form-control" name="live_at" value="<?= htmlspecialchars($user['live_at'] ?? '') ?>" placeholder="Thành phố bạn đang sống">
            </div>
            <div class="col-md-6">
              <label class="form-label">Nơi làm việc</label>
              <input type="text" class="form-control" name="workplace" value="<?= htmlspecialchars($user['workplace'] ?? '') ?>" placeholder="Tên công ty">
            </div>
            <div class="col-md-6">
              <label class="form-label">Nơi học tập</label>
              <input type="text" class="form-control" name="studied_at" value="<?= htmlspecialchars($user['studied_at'] ?? '') ?>" placeholder="Tên trường học">
            </div>
          </div>

          <h6 class="text-muted mb-3">Thay đổi hình ảnh</h6>
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Ảnh đại diện mới</label>
              <input type="file" class="form-control" name="avatar_file" accept="image/*">
              <?php if (!empty($user['avatar_url'])): ?>
                <div class="mt-2">
                  <small class="text-muted">Ảnh hiện tại:</small><br>
                  <img src="<?= htmlspecialchars($user['avatar_url']) ?>" alt="Avatar" class="img-thumbnail" width="80">
                </div>
              <?php endif; ?>
            </div>
            <div class="col-md-6">
              <label class="form-label">Ảnh bìa mới</label>
              <input type="file" class="form-control" name="cover_file" accept="image/*">
              <?php if (!empty($user['cover_photo'])): ?>
                <div class="mt-2">
                  <small class="text-muted">Ảnh hiện tại:</small><br>
                  <img src="<?= htmlspecialchars($user['cover_photo']) ?>" alt="Cover photo" class="img-thumbnail" width="120">
                </div>
              <?php endif; ?>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
          <button type="submit" class="btn btn-warning">Lưu thay đổi</button>
        </div>
      </form>
    </div>
  </div>
</div>


<!-- Thông báo -->
<?php if (isset($_GET['msg'])): ?>
  <script>
    switch ("<?= $_GET['msg'] ?>") {
      case "invalid_token":
        alert("❌ Phiên làm việc không hợp lệ hoặc đã hết hạn. Vui lòng tải lại trang và thử lại.");
        window.location.href = "<?= BASE_URL ?>/profile_user";
        break;
      case "profile_updated":
        alert("📝 Thông tin cá nhân đã được cập nhật thành công!");
        window.location.href = "<?= BASE_URL ?>/profile_user";
        break;
      case "profile_failed":
        alert("❌ Cập nhật thất bại, vui lòng thử lại.");
        window.location.href = "<?= BASE_URL ?>/profile_user";
        break;
      case "business_updated":
        alert("📝 Thông tin doanh nhân đã được cập nhật thành công!");
        window.location.href = "<?= BASE_URL ?>/profile_business";
        break;
      case "business_failed":
        alert("❌ Cập nhật thất bại, vui lòng thử lại.");
        window.location.href = "<?= BASE_URL ?>/profile_business";
        break;
      case "career_updated":
        alert("📝 Quá trình công tác đã được cập nhật thành công!");
        window.location.href = "<?= BASE_URL ?>/profile_business";
        break;
      case "career_failed":
        alert("❌ Cập nhật thất bại, vui lòng thử lại.");
        window.location.href = "<?= BASE_URL ?>/profile_business";
        break;
      case "user_updated":
        alert("📝 Đăng kí doanh nhân thành công!");
        window.location.href = "<?= BASE_URL ?>/profile_business";
        break;
      case "user_failed":
        alert("❌ Đăng kí doanh nhân thất bại, vui lòng thử lại.");
        window.location.href = "<?= BASE_URL ?>/profile_business";
        break;
      case "password_changed":
        alert("🔑 Mật khẩu đã được đổi thành công!");
        window.location.href = "<?= BASE_URL ?>/profile_user";
        break;
      case "password_changed_failed":
        alert("🔑 Lỗi khi đổi mật khẩu, vui lòng thử lại!");
        window.location.href = "<?= BASE_URL ?>/profile_user";
        break;
    }
    const currentUserId = <?= json_encode($_SESSION['user']['id'] ?? null) ?>;
  </script>
<?php endif; ?>