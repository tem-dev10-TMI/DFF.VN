<div class="col-md-3">
  <div class="sidebar">
    <!-- Thông tin doanh nghiệp -->
    <div class="business-info">
      <h5 class="mb-3">Thông tin doanh nhân</h5>
      <div class="info-item mb-2">
        <strong>Họ và tên:</strong>
        <span class="text-muted"><?php echo htmlspecialchars($business['name'] ?? 'Chưa cập nhật'); ?></span>
      </div>
      <div class="info-item mb-2">
        <strong>Email:</strong>
        <span class="text-muted"><?php echo htmlspecialchars($business['email'] ?? 'Chưa cập nhật'); ?></span>
      </div>
      <div class="info-item mb-2">
        <strong>Số điện thoại:</strong>
        <span class="text-muted"><?php echo htmlspecialchars($business['phone'] ?? 'Chưa cập nhật'); ?></span>
      </div>

      <!-- Thông tin từ bảng businessmen -->
      <div class="info-item mb-2">
        <strong>Năm sinh:</strong>
        <span class="text-muted"><?php echo htmlspecialchars($business['birth_year'] ?? 'Chưa cập nhật'); ?></span>
      </div>
      <div class="info-item mb-2">
        <strong>Quốc tịch:</strong>
        <span class="text-muted"><?php echo htmlspecialchars($business['nationality'] ?? 'Chưa cập nhật'); ?></span>
      </div>
      <div class="info-item mb-2">
        <strong>Học vấn:</strong>
        <span class="text-muted"><?php echo htmlspecialchars($business['education'] ?? 'Chưa cập nhật'); ?></span>
      </div>
      <div class="info-item mb-2">
        <strong>Chức vụ hiện tại:</strong>
        <span class="text-muted"><?php echo htmlspecialchars($business['position'] ?? 'Chưa cập nhật'); ?></span>
      </div>
    </div>

    <!-- Thống kê -->
    <div class="business-stats mt-4">
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

    <!-- Nút hành động -->
    <div class="business-actions mt-4">
      <button
        class="btn btn-outline-primary btn-sm w-100 mb-2"
        data-bs-toggle="modal"
        data-bs-target="#editProfileModal">
        <i class="fas fa-edit me-1"></i> Chỉnh sửa thông tin
      </button>
      <button class="btn btn-outline-success btn-sm w-100 mb-2">
        <i class="fas fa-chart-line me-1"></i> Xem thống kê
      </button>
      <button class="btn btn-outline-info btn-sm w-100">
        <i class="fas fa-cog me-1"></i> Cài đặt
      </button>
    </div>
  </div>
</div>
<!-- Modal chỉnh sửa thông tin doanh nhân -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg" style="max-width: 650px;">
    <div class="modal-content">
      <div class="modal-header bg-warning text-dark">
        <h5 class="modal-title"><i class="fas fa-user-edit me-2"></i>Chỉnh sửa hồ sơ</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <form action="<?= BASE_URL ?>/edit_business" method="POST">
        <div class="modal-body">
          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label">Họ và tên</label>
              <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($business['name'] ?? '') ?>" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Email</label>
              <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($business['email'] ?? '') ?>" required>
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label">Số điện thoại</label>
              <input type="text" class="form-control" name="phone" value="<?= htmlspecialchars($business['phone'] ?? '') ?>" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Năm sinh</label>
              <input type="number" class="form-control" name="birth_year" value="<?= htmlspecialchars($business['birth_year'] ?? '') ?>">
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label">Quốc tịch</label>
              <input type="text" class="form-control" name="nationality" value="<?= htmlspecialchars($business['nationality'] ?? '') ?>">
            </div>
            <div class="col-md-6">
              <label class="form-label">Học vấn</label>
              <input type="text" class="form-control" name="education" value="<?= htmlspecialchars($business['education'] ?? '') ?>">
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">Chức vụ hiện tại</label>
            <input type="text" class="form-control" name="position" value="<?= htmlspecialchars($business['position'] ?? '') ?>">
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