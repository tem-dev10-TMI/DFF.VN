<?php include __DIR__ . '/../layout/auth_header.php'; ?>

<div class="d-flex align-items-center justify-content-center vh-100">
  <div class="col-md-4">
    <div class="card shadow p-4">
      <h3 class="mb-3 text-center">Đăng nhập Admin</h3>
      <?php if($msg = flash('error')): ?>
        <div class="alert alert-danger"><?= e($msg) ?></div>
      <?php endif; ?>
      <!-- Sửa action từ admin.php sang admin.php -->
      <form method="post" action="<?= BASE_URL ?>/admin.php?route=login&action=do">
        <div class="mb-3">
          <label class="form-label">Tên đăng nhập</label>
          <input type="text" name="username" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Mật khẩu</label>
          <input type="password" name="password" class="form-control" required>
        </div>
        <button class="btn btn-primary w-100">Đăng nhập</button>
      </form>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../layout/auth_footer.php'; ?>
