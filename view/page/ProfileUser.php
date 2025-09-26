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