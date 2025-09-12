<?php include __DIR__ . '/../layout/header.php'; ?>
<?php $isEdit = isset($user) && $user; ?>

<div class="row justify-content-center">
  <div class="col-lg-6">
    <div class="card shadow-sm border-0">
      <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><?=$isEdit ? 'Sửa người dùng' : 'Thêm người dùng'?></h5>
      </div>
      <div class="card-body">

        <!-- Thông báo -->
        <?php if($msg = flash('error')): ?>
          <div class="alert alert-danger"><?=e($msg)?></div>
        <?php endif; ?>
        <?php if($msg = flash('success')): ?>
          <div class="alert alert-success"><?=e($msg)?></div>
        <?php endif; ?>

        <!-- Form -->
        <form method="post" action="<?=BASE_URL?>/index.php?route=users&action=<?=$isEdit ? 'update&id='.$user['id'] : 'store'?>">
          <div class="mb-3">
            <label class="form-label fw-semibold">Tên</label>
            <input name="name" class="form-control" value="<?=$isEdit?e($user['name']):''?>" required>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Username</label>
            <input name="username" class="form-control" value="<?=$isEdit?e($user['username']):''?>" required>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Email</label>
            <input name="email" type="email" class="form-control" value="<?=$isEdit?e($user['email']):''?>" required>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Mật khẩu <small class="text-muted">(để trống nếu không đổi)</small></label>
            <input name="password" type="password" class="form-control">
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Role</label>
            <select name="role" class="form-select">
              <option value="user" <?=$isEdit && $user['role']=='user'?'selected':''?>>User</option>
              <option value="businessmen" <?=$isEdit && $user['role']=='businessmen'?'selected':''?>>Businessmen</option>
              <option value="admin" <?=$isEdit && $user['role']=='admin'?'selected':''?>>Admin</option>
            </select>
          </div>
          <div class="text-end">
            <a href="<?=BASE_URL?>/index.php?route=users" class="btn btn-secondary">Hủy</a>
            <button class="btn btn-primary"><?=$isEdit ? 'Cập nhật' : 'Tạo mới'?></button>
          </div>
        </form>

      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
