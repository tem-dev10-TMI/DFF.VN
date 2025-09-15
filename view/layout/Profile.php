<style>
  .cover {
    height: 200px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    position: relative;
    border-radius: 8px;
    margin-bottom: 80px;
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

  /* Fix cho Bootstrap modal */
  @media (min-width: 576px) {
    .modal-dialog {
      max-width: 800px;
      margin: 1.75rem auto;
    }
  }
</style>
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
      require_once 'view/page/Profileuser.php';
    } ?>
    <?php if ($profile_category == 'businessmen') {
      require_once 'view/page/ProfileBusiness.php';
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
          <button class="btn btn-primary" onclick="addPost()">
            <i class="fas fa-paper-plane me-1"></i> Đăng bài
          </button>
        </div>
        <!-- Input hidden -->
        <input type="file" id="postImage" class="d-none" accept="image/*" onchange="previewImage(event)">
        <input type="file" id="postVideo" class="d-none" accept="video/*">
      </div>

      <!-- Preview ảnh -->
      <div id="imagePreview" class="mt-2"></div>

      <!-- Posts -->
      <!-- Danh sách bài viết -->
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
          <i class="fas fa-building me-2"></i>Đăng ký tài khoản doanh nghiệp
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Thông tin hiện tại - Compact version -->
        <div class="row mb-3">
          <div class="col-md-6">
            <div class="card border-primary h-100">
              <div class="card-header bg-primary text-white py-2">
                <h6 class="mb-0"><i class="fas fa-user me-2"></i>Tài khoản hiện tại</h6>
              </div>
              <div class="card-body py-2">
                <div class="d-flex justify-content-between mb-1">
                  <small><strong>Loại:</strong></small>
                  <span class="badge bg-info">Cá nhân</span>
                </div>
                <div class="d-flex justify-content-between mb-1">
                  <small><strong>Bài viết:</strong></small>
                  <span class="badge bg-primary"><?php echo isset($user_posts) ? $user_posts : '0'; ?></span>
                </div>
                <div class="d-flex justify-content-between mb-1">
                  <small><strong>Theo dõi:</strong></small>
                  <span class="badge bg-success"><?php echo isset($user_followers) ? $user_followers : '0'; ?></span>
                </div>
                <div class="d-flex justify-content-between">
                  <small><strong>Đang theo:</strong></small>
                  <span class="badge bg-warning"><?php echo isset($user_following) ? $user_following : '0'; ?></span>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card border-warning h-100">
              <div class="card-header bg-warning text-dark py-2">
                <h6 class="mb-0"><i class="fas fa-building me-2"></i>Sau khi chuyển đổi</h6>
              </div>
              <div class="card-body py-2">
                <div class="d-flex justify-content-between mb-1">
                  <small><strong>Loại:</strong></small>
                  <span class="badge bg-warning">Doanh nghiệp</span>
                </div>
                <div class="d-flex justify-content-between mb-1">
                  <small><strong>Bài viết:</strong></small>
                  <span class="badge bg-primary"><?php echo isset($user_posts) ? $user_posts : '0'; ?></span>
                </div>
                <div class="d-flex justify-content-between mb-1">
                  <small><strong>Theo dõi:</strong></small>
                  <span class="badge bg-success"><?php echo isset($user_followers) ? $user_followers : '0'; ?></span>
                </div>
                <div class="d-flex justify-content-between">
                  <small><strong>Đang theo:</strong></small>
                  <span class="badge bg-warning"><?php echo isset($user_following) ? $user_following : '0'; ?></span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Cảnh báo - Compact version -->
        <div class="alert alert-warning py-2">
          <i class="fas fa-exclamation-triangle me-2"></i>
          <strong>Lưu ý:</strong>
          <small class="d-block mt-1">
            Chuyển đổi sang doanh nghiệp • Cần thông tin hợp lệ • Xét duyệt 1-3 ngày • Một số tính năng bị hạn chế
          </small>
        </div>

        <!-- Form đăng ký doanh nghiệp - Compact version -->
        <form id="convertForm" method="POST" action="controller/convertToBusiness.php">
          <div class="row">
            <div class="col-md-6">
              <div class="mb-2">
                <label for="companyName" class="form-label small">Tên công ty <span class="text-danger">*</span></label>
                <input type="text" class="form-control form-control-sm" id="companyName" name="company_name" placeholder="Nhập tên công ty" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-2">
                <label for="taxCode" class="form-label small">Mã số thuế <span class="text-danger">*</span></label>
                <input type="text" class="form-control form-control-sm" id="taxCode" name="tax_code" placeholder="Nhập mã số thuế" required>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="mb-2">
                <label for="businessField" class="form-label small">Lĩnh vực hoạt động <span class="text-danger">*</span></label>
                <select class="form-select form-select-sm" id="businessField" name="business_field" required>
                  <option value="">Chọn lĩnh vực</option>
                  <option value="fintech">Công nghệ tài chính</option>
                  <option value="banking">Ngân hàng</option>
                  <option value="investment">Đầu tư</option>
                  <option value="insurance">Bảo hiểm</option>
                  <option value="securities">Chứng khoán</option>
                  <option value="other">Khác</option>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-2">
                <label for="companySize" class="form-label small">Quy mô nhân sự</label>
                <select class="form-select form-select-sm" id="companySize" name="company_size">
                  <option value="">Chọn quy mô</option>
                  <option value="1-10">1-10 nhân viên</option>
                  <option value="11-50">11-50 nhân viên</option>
                  <option value="51-200">51-200 nhân viên</option>
                  <option value="201-500">201-500 nhân viên</option>
                  <option value="500+">Trên 500 nhân viên</option>
                </select>
              </div>
            </div>
          </div>

          <div class="mb-2">
            <label for="businessAddress" class="form-label small">Địa chỉ trụ sở <span class="text-danger">*</span></label>
            <textarea class="form-control form-control-sm" id="businessAddress" name="business_address" rows="2" placeholder="Nhập địa chỉ trụ sở chính" required></textarea>
          </div>

          <div class="mb-2">
            <label for="website" class="form-label small">Website công ty</label>
            <input type="url" class="form-control form-control-sm" id="website" name="website" placeholder="https://example.com">
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
        <button type="button" class="btn btn-warning" onclick="submitConversion()">
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
          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label">Tên người dùng</label>
              <input type="text" class="form-control" name="user_name" value="<?= htmlspecialchars($user['name'] ?? '') ?>">
            </div>
            <div class="col-md-6">
              <label class="form-label">Số điện thoại</label>
              <input type="text" class="form-control" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
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
        window.location.href = "<?= BASE_URL ?>/profileUser";
        break;
      case "profile_failed":
        alert("❌ Cập nhật thất bại, vui lòng thử lại.");
        window.location.href = "<?= BASE_URL ?>/profileUser";
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
      case "password_changed":
        alert("🔑 Mật khẩu đã được đổi thành công!");
        window.location.href = "<?= BASE_URL ?>/profileUser";
        break;
    }
  </script>
<?php endif; ?>


<script>
  function convertToBusiness() {
    // Hiển thị modal xác nhận
    var convertModal = new bootstrap.Modal(document.getElementById('convertModal'));
    convertModal.show();

    // Đảm bảo modal có thể scroll
    setTimeout(function() {
      var modalBody = document.querySelector('#convertModal .modal-body');
      if (modalBody) {
        modalBody.style.maxHeight = 'calc(90vh - 140px)';
        modalBody.style.overflowY = 'auto';
      }
    }, 100);
  }

  function submitConversion() {
    // Lấy form element
    var form = document.getElementById('convertForm');
    var formData = new FormData(form);

    // Kiểm tra validation trước khi submit
    var companyName = document.getElementById('companyName').value;
    var taxCode = document.getElementById('taxCode').value;
    var businessField = document.getElementById('businessField').value;
    var businessAddress = document.getElementById('businessAddress').value;
    var agreeTerms = document.getElementById('agreeTerms').checked;

    if (!companyName || !taxCode || !businessField || !businessAddress) {
      alert('Vui lòng điền đầy đủ thông tin bắt buộc!');
      return;
    }

    if (!agreeTerms) {
      alert('Vui lòng đồng ý với điều khoản sử dụng!');
      return;
    }

    // Hiển thị loading
    var submitBtn = event.target;
    var originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Đang xử lý...';
    submitBtn.disabled = true;

    // Submit form tới PHP
    fetch('controller/test-api-profile/convertToBusiness.php', {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        // Reset button
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;

        if (data.success) {
          // Close modal
          var convertModal = bootstrap.Modal.getInstance(document.getElementById('convertModal'));
          convertModal.hide();

          // Show success message
          alert(data.message);

          // Reset form
          form.reset();

          // Reload page để cập nhật giao diện
          setTimeout(function() {
            window.location.reload();
          }, 1000);
        } else {
          // Show error message
          alert(data.message);
        }
      })
      .catch(error => {
        // Reset button
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;

        // Show error message
        alert('Có lỗi xảy ra khi xử lý yêu cầu. Vui lòng thử lại sau!');
        console.error('Error:', error);
      });
  }

  // Load bài viết từ PHP
  function loadPosts() {
    // Hiển thị loading indicator
    var loadingElement = document.getElementById('loadingPosts');
    if (loadingElement) {
      loadingElement.style.display = 'block';
    }

    fetch('api/loadPost', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          profile_category: '<?php echo $profile_category; ?>',
          user_id: '<?php echo isset($user_id) ? $user_id : 0; ?>'
        })
      })
      .then(response => response.json())
      .then(data => {
        // Ẩn loading indicator
        if (loadingElement) {
          loadingElement.style.display = 'none';
        }

        if (data && (data.success === true || typeof data.success === 'undefined')) {
          var posts = Array.isArray(data.posts) ? data.posts : (data.data && Array.isArray(data.data.posts) ? data.data.posts : []);
          displayPosts(posts);
        } else {
          console.error('Lỗi load bài viết:', data.message);
          // Hiển thị thông báo lỗi
          var postsContainer = document.getElementById('posts');
          postsContainer.innerHTML = `
        <div class="block-k">
          <div class="view-carde f-frame">
            <div class="text-center p-4">
              <p class="text-danger">Không thể tải bài viết. Vui lòng thử lại sau!</p>
              <button class="btn btn-outline-primary btn-sm" onclick="loadPosts()">
                <i class="fas fa-refresh me-1"></i> Thử lại
              </button>
            </div>
          </div>
        </div>
      `;
        }
      })
      .catch(error => {
        // Ẩn loading indicator
        if (loadingElement) {
          loadingElement.style.display = 'none';
        }

        console.error('Lỗi fetch:', error);
        // Hiển thị thông báo lỗi
        var postsContainer = document.getElementById('posts');
        postsContainer.innerHTML = `
      <div class="block-k">
        <div class="view-carde f-frame">
          <div class="text-center p-4">
            <p class="text-danger">Có lỗi xảy ra khi tải bài viết!</p>
            <button class="btn btn-outline-primary btn-sm" onclick="loadPosts()">
              <i class="fas fa-refresh me-1"></i> Thử lại
            </button>
          </div>
        </div>
      </div>
    `;
      });
  }

  // Hiển thị danh sách bài viết
  function displayPosts(posts) {
    var postsContainer = document.getElementById('posts');
    postsContainer.innerHTML = '';

    if (posts.length === 0) {
      postsContainer.innerHTML = `
      <div class="block-k">
        <div class="view-carde f-frame">
          <div class="text-center p-4">
            <p>Chưa có bài viết nào. Hãy tạo bài viết đầu tiên!</p>
          </div>
        </div>
      </div>
    `;
      return;
    }

    posts.forEach(function(post) {
      var postElement = createPostElement(post);
      postsContainer.appendChild(postElement);
    });
  }

  // Tạo element bài viết theo cấu trúc Home
  function createPostElement(post) {
    var postDiv = document.createElement('div');
    postDiv.className = 'block-k';
    postDiv.innerHTML = `
    <div class="view-carde f-frame">
      <div class="provider">
        <img class="logo" alt="" src="${post.avatar || 'https://i.pinimg.com/1200x/83/0e/ea/830eea38f7a5d3d8e390ba560d14f39c.jpg'}">
        <div class="p-covers">
          <span class="badge ${renderStatusBadgeClass(post.status)}" style="margin-right:6px;" title="${post.review_reason ? post.review_reason.replace(/"/g,'&quot;') : ''}">${renderStatusText(post.status)}</span>
          <span class="name" title="">
            <a href="/profile.html?q=${post.author_id || post.user_id}" title="${post.author_name}">${post.author_name}</a>
          </span>
          <span class="date">${post.time_ago}</span>
        </div>
      </div>

      <div class="title">
        <a title="${post.title || post.content.substring(0, 50)}" href="/post-${post.id}.html">${post.title || post.content.substring(0, 50)}</a>
      </div>
      ${post.status && (post.status.toLowerCase()==='rejected' || post.status.toLowerCase()==='reject') && post.review_reason ? `
      <div class="mt-1 mb-2">
        <div class="alert alert-danger py-1 px-2 m-0">
          <small><strong>Lý do:</strong> ${escapeHtml(post.review_reason)}</small>
        </div>
      </div>
      ` : ''}
      <div class="sapo">
        ${post.content}
        ${post.content.length > 100 ? '<a href="/post-' + post.id + '.html" class="d-more">Xem thêm</a>' : ''}
      </div>

      ${post.image ? `<img class="h-img" src="${post.image}" title="${post.title || 'Post image'}" alt="${post.title || 'Post image'}" border="0">` : ''}

      <div class="item-bottom">
        <div class="bt-cover com-like" data-id="${post.id}">
          <span class="for-up" onclick="toggleLike(${post.id})">
            <svg rpl="" data-voted="false" data-type="up" fill="currentColor" height="16"
              icon-name="upvote-fill" viewBox="0 0 20 20" width="16"
              xmlns="http://www.w3.org/2000/svg">
              <path
                d="M18.706 8.953 10.834.372A1.123 1.123 0 0 0 10 0a1.128 1.128 0 0 0-.833.368L1.29 8.957a1.249 1.249 0 0 0-.171 1.343 1.114 1.114 0 0 0 1.007.7H6v6.877A1.125 1.125 0 0 0 7.123 19h5.754A1.125 1.125 0 0 0 14 17.877V11h3.877a1.114 1.114 0 0 0 1.005-.7 1.251 1.251 0 0 0-.176-1.347Z">
              </path>
            </svg>
          </span>
          <span class="value" data-old="${post.likes_count || 0}">${post.likes_count || 0}</span>
          <span class="for-down" onclick="toggleDislike(${post.id})">
            <svg rpl="" data-voted="false" data-type="down" fill="currentColor" height="16"
              icon-name="downvote-fill" viewBox="0 0 20 20" width="16"
              xmlns="http://www.w3.org/2000/svg">
              <path
                d="M18.88 9.7a1.114 1.114 0 0 0-1.006-.7H14V2.123A1.125 1.125 0 0 0 12.877 1H7.123A1.125 1.125 0 0 0 6 2.123V9H2.123a1.114 1.114 0 0 0-1.005.7 1.25 1.25 0 0 0 .176 1.348l7.872 8.581a1.124 1.124 0 0 0 1.667.003l7.876-8.589A1.248 1.248 0 0 0 18.88 9.7Z">
              </path>
            </svg>
          </span>
        </div>
        <div class="button-ar">
          <a href="/post-${post.id}.html#anc_comment" onclick="showComments(${post.id})">
            <svg rpl="" aria-hidden="true" class="icon-comment" fill="currentColor"
              height="15" icon-name="comment-outline" viewBox="0 0 20 20" width="15"
              xmlns="http://www.w3.org/2000/svg">
              <path
                d="M7.725 19.872a.718.718 0 0 1-.607-.328.725.725 0 0 1-.118-.397V16H3.625A2.63 2.63 0 0 1 1 13.375v-9.75A2.629 2.629 0 0 1 3.625 1h12.75A2.63 2.63 0 0 1 19 3.625v9.75A2.63 2.63 0 0 1 16.375 16h-4.161l-4 3.681a.725.725 0 0 1-.489.191ZM3.625 2.25A1.377 1.377 0 0 0 2.25 3.625v9.75a1.377 1.377 0 0 0 1.375 1.375h4a.625.625 0 0 1 .625.625v2.575l3.3-3.035a.628.628 0 0 1 .424-.165h4.4a1.377 1.377 0 0 0 1.375-1.375v-9.75a1.377 1.377 0 0 0-1.374-1.375H3.625Z">
              </path>
            </svg>
            <span>${post.comments_count || 0}</span>
          </a>
        </div>
        <div class="button-ar">
          <div class="dropdown home-item">
            <i class="far fa-share-square"></i><span data-bs-toggle="dropdown"
              aria-expanded="false">Chia sẻ</span>
            <ul class="dropdown-menu">
              <li><i class="bi bi-link-45deg"></i> <a class="dropdown-item copylink"
                  data-url="/post-${post.id}.html"
                  href="javascript:void(0)">Copy link</a></li>
              <li><i class="bi bi-facebook"></i> <a class="dropdown-item sharefb"
                  data-url="/post-${post.id}.html"
                  href="javascript:void(0)">Share FB</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  `;
    return postDiv;
  }

  function renderStatusText(status) {
    var s = (status || '').toLowerCase();
    if (s === 'public' || s === 'published') return 'Công khai';
    if (s === 'pending' || s === 'review') return 'Chờ duyệt';
    if (s === 'rejected' || s === 'reject') return 'Rejected';
    if (s === 'draft') return 'Nháp';
    if (s === 'private') return 'Riêng tư';
    return status || 'Chờ duyệt';
  }

  function renderStatusBadgeClass(status) {
    var s = (status || '').toLowerCase();
    if (s === 'public' || s === 'published') return 'bg-success';
    if (s === 'pending' || s === 'review') return 'bg-warning text-dark';
    if (s === 'rejected' || s === 'reject') return 'bg-danger';
    if (s === 'draft') return 'bg-secondary';
    if (s === 'private') return 'bg-dark';
    return 'bg-warning text-dark';
  }

  function escapeHtml(str) {
    if (!str) return '';
    return String(str)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#039;');
  }

  // Submit bài viết mới
  function addPost() {
    var postTitle = document.getElementById('postTitle').value.trim();
    var postSummary = document.getElementById('postSummary').value.trim();
    var postContent = document.getElementById('newPost').value.trim();

    if (!postTitle || !postContent) {
      alert('Vui lòng nhập tiêu đề và nội dung!');
      return;
    }

    var submitBtn = document.querySelector('.post-box .btn-primary');
    var originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Đang đăng...';
    submitBtn.disabled = true;

    var formData = new FormData();
    formData.append('title', postTitle);
    formData.append('summary', postSummary);
    formData.append('content', postContent);
    formData.append('topic_id', 1); // tạm fix cứng, hoặc để user chọn

    var imageFile = document.getElementById('postImage').files[0];
    if (imageFile) {
      formData.append('main_image_url', imageFile);
    }

    fetch('api/addPost', {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;

        if (data && data.success) {
          document.getElementById('postTitle').value = '';
          document.getElementById('postSummary').value = '';
          document.getElementById('newPost').value = '';
          document.getElementById('postImage').value = '';
          document.getElementById('imagePreview').innerHTML = '';

          // Refresh danh sách bài viết
          loadPosts();
          showNotification(data.message || 'Đăng bài thành công!', 'success');
        } else {
          alert('Lỗi: ' + (data && data.message ? data.message : 'Không xác định'));
        }
      })
      .catch(error => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
        console.error("Fetch error:", error);
        alert("Có lỗi xảy ra khi gửi request!");
      });
  }

  // Xem trước ảnh trước khi đăng
  function previewImage(event) {
    const preview = document.getElementById('imagePreview');
    preview.innerHTML = ''; // Xóa preview cũ

    const file = event.target.files[0];
    if (file) {
      const img = document.createElement('img');
      img.src = URL.createObjectURL(file);
      img.classList.add('img-fluid', 'rounded', 'mt-2');
      img.style.maxHeight = '200px';
      preview.appendChild(img);
    }
  }

  // Like/Unlike bài viết
  function toggleLike(postId) {
    fetch('controller/test-api-profile/toggleLike.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          post_id: postId,
          user_id: '<?php echo isset($user_id) ? $user_id : 0; ?>',
          action: 'like'
        })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          // Reload bài viết để cập nhật số like
          loadPosts();
        } else {
          console.error('Lỗi like:', data.message);
        }
      })
      .catch(error => {
        console.error('Lỗi fetch like:', error);
      });
  }

  // Dislike bài viết
  function toggleDislike(postId) {
    fetch('controller/test-api-profile/toggleLike.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          post_id: postId,
          user_id: '<?php echo isset($user_id) ? $user_id : 0; ?>',
          action: 'dislike'
        })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          // Reload bài viết để cập nhật số like
          loadPosts();
        } else {
          console.error('Lỗi dislike:', data.message);
        }
      })
      .catch(error => {
        console.error('Lỗi fetch dislike:', error);
      });
  }

  // Hiển thị comment
  function showComments(postId) {
    // TODO: Implement comment modal
    console.log('Show comments for post:', postId);
  }

  // Chia sẻ bài viết
  function sharePost(postId) {
    // TODO: Implement share functionality
    console.log('Share post:', postId);
  }

  // Hiển thị thông báo
  function showNotification(message, type = 'info') {
    // Tạo element thông báo
    var notification = document.createElement('div');
    notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
    ${message}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  `;

    document.body.appendChild(notification);

    // Tự động ẩn sau 3 giây
    setTimeout(function() {
      if (notification.parentNode) {
        notification.parentNode.removeChild(notification);
      }
    }, 3000);
  }

  // Đảm bảo modal scroll được khi mở và auto-load bài viết
  document.addEventListener('DOMContentLoaded', function() {
    // Auto-load bài viết khi trang load
    loadPosts();

    // Modal scroll setup
    var convertModal = document.getElementById('convertModal');
    if (convertModal) {
      convertModal.addEventListener('shown.bs.modal', function() {
        var modalBody = this.querySelector('.modal-body');
        if (modalBody) {
          modalBody.style.maxHeight = 'calc(90vh - 140px)';
          modalBody.style.overflowY = 'auto';
          modalBody.style.overflowX = 'hidden';
        }
      });
    }
  });
</script>