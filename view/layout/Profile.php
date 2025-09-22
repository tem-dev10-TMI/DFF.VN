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

    <?php
    $avatarUrl = $user['avatar_url'] ?? null;
    if (!$avatarUrl || trim($avatarUrl) === '') {
      $avatarUrl = 'https://i.pinimg.com/1200x/83/0e/ea/830eea38f7a5d3d8e390ba560d14f39c.jpg';
    }
    ?>
    <img src="<?= htmlspecialchars($avatarUrl) ?>" class="avatar" alt="avatar">
    <!-- <img src="https://via.placeholder.com/120" class="avatar" alt="avatar"> -->
  </div>
  <div class="mt-5"></div>

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
        <input type="file" id="postImage" class="d-none" accept="image/*" onchange="previewImage(event)">
        <input type="file" id="postVideo" class="d-none" accept="video/*" onchange="previewVideo(event)">
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
    <div class="modal-content">

      <div class="modal-header bg-warning text-dark">
        <h5 class="modal-title" id="convertModalLabel">
          <i class="fas fa-building me-2"></i>Đăng ký tài khoản doanh nhân
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <!-- Thông tin hiện tại -->
        <!-- Cảnh báo -->
        <div class="alert alert-warning py-2">
          <i class="fas fa-exclamation-triangle me-2"></i>
          <strong>Lưu ý:</strong>
          <small class="d-block mt-1">
            Chuyển đổi sang doanh nhân • Cần thông tin hợp lệ • Xét duyệt 1-3 ngày • Một số tính năng bị hạn chế
          </small>
        </div>

        <!-- Form đăng ký doanh nhân -->
        <form id="convertForm" method="POST" action="<?= BASE_URL ?>/register_business">
          <input type="hidden" name="session_token" value="<?= htmlspecialchars($_SESSION['user']['session_token'] ?? '') ?>">
          <div class="row">
            <div class="col-md-6 mb-2">
              <label for="birthYear" class="form-label small">Năm sinh <span class="text-danger">*</span></label>
              <input type="number" min="1900" max="2099" class="form-control form-control-sm" id="birthYear" name="birth_year" required>
            </div>
            <div class="col-md-6 mb-2">
              <label for="nationality" class="form-label small">Quốc tịch <span class="text-danger">*</span></label>
              <input type="text" class="form-control form-control-sm" id="nationality" name="nationality" required>
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
          </div>
        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="fas fa-times me-1"></i>Hủy
        </button>
        <button type="submit" class="btn btn-warning" onclick="submitConversion()">
          <i class="fas fa-building me-1"></i>Chuyển đổi
        </button>
      </div>

    </div>
  </div>
</div>


<!-- Modal chỉnh sử thông tin người dùng  -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg" style="max-width: 650px;">
    <div class="modal-content">
      <div class="modal-header bg-warning text-dark">
        <h5 class="modal-title"><i class="fas fa-user-edit me-2"></i>Chỉnh sửa hồ sơ</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <form action="<?= BASE_URL ?>/edit_profile" method="POST" enctype="multipart/form-data">
        <div class="modal-body">
          <input type="hidden" name="session_token" value="<?= htmlspecialchars($_SESSION['user']['session_token'] ?? '') ?>">
          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label">Tên người dùng</label>
              <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($user['name'] ?? '') ?>">
            </div>

            <div class="col-md-6">
              <label class="form-label">Số điện thoại</label>
              <input type="text" class="form-control" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label">UserName</label>
              <input type="text" class="form-control" name="user_name" value="<?= htmlspecialchars($user['username'] ?? '') ?>">
            </div>

            <div class="col-md-6">
              <label class="form-label">Email</label>
              <input type="text" class="form-control" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>">
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label">Ảnh đại diện</label>
              <input type="file" class="form-control" name="avatar_file">
              <?php if (!empty($user['avatar_url'])): ?>
                <small class="text-muted">Ảnh hiện tại: <a href="<?= htmlspecialchars($user['avatar_url']) ?>" target="_blank">Xem</a></small>
              <?php endif; ?>
            </div>
            <div class="col-md-6">
              <label class="form-label">Ảnh bìa</label>
              <input type="file" class="form-control" name="cover_file">
              <?php if (!empty($user['cover_photo'])): ?>
                <small class="text-muted">Ảnh hiện tại: <a href="<?= htmlspecialchars($user['cover_photo']) ?>" target="_blank">Xem</a></small>
              <?php endif; ?>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">Mô tả bản thân</label>
            <textarea class="form-control" name="description" rows="3"><?= htmlspecialchars($user['description'] ?? '') ?></textarea>
          </div>

          <hr>

          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label">Tên hiển thị</label>
              <input type="text" class="form-control" name="display_name" value="<?= htmlspecialchars($profileUser['display_name'] ?? '') ?>">
            </div>
            <div class="col-md-6">
              <label class="form-label">Năm sinh</label>
              <input type="number" class="form-control" name="birth_year" value="<?= htmlspecialchars($profileUser['birth_year'] ?? '') ?>">
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">Nơi làm việc</label>
            <input type="text" class="form-control" name="workplace" value="<?= htmlspecialchars($profileUser['workplace'] ?? '') ?>">
          </div>

          <div class="mb-3">
            <label class="form-label">Nơi học tập</label>
            <input type="text" class="form-control" name="studied_at" value="<?= htmlspecialchars($profileUser['studied_at'] ?? '') ?>">
          </div>

          <div class="mb-3">
            <label class="form-label">Địa chỉ</label>
            <input type="text" class="form-control" name="live_at" value="<?= htmlspecialchars($profileUser['live_at'] ?? '') ?>">
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
          <button type="submit" class="btn btn-warning">Cập nhật</button>
        </div>
      </form>
    </div>
  </div>
</div>


<!-- Thông báo -->
<?php if (isset($_GET['msg'])): ?>
  <script>
    switch ("<?= $_GET['msg'] ?>") {
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