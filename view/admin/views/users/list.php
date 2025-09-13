<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="card shadow-sm border-0">
  <div class="card-header bg-white d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Danh sách người dùng</h5>
    <a href="<?=BASE_URL?>/admin.php?route=users&action=create" class="btn btn-success btn-sm">
      <i class="bi bi-plus-circle me-1"></i> Thêm mới
    </a>
  </div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Tên</th>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
            <th>Ngày tạo</th>
            <th class="text-end">Hành động</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach($users as $u): ?>
          <tr>
            <td><?=e($u['id'])?></td>
            <td><?=e($u['name'])?></td>
            <td><?=e($u['username'])?></td>
            <td><?=e($u['email'])?></td>
            <td>
              <?php if($u['role']=='admin'): ?>
                <span class="badge bg-danger">Admin</span>
              <?php elseif($u['role']=='businessmen'): ?>
                <span class="badge bg-warning text-dark">Businessmen</span>
              <?php else: ?>
                <span class="badge bg-secondary">User</span>
              <?php endif; ?>
            </td>
            <td><?=date('d/m/Y', strtotime($u['created_at']))?></td>
            <td class="text-end">
              <a class="btn btn-sm btn-outline-primary" href="<?=BASE_URL?>/admin.php?route=users&action=edit&id=<?=$u['id']?>">
                <i class="bi bi-pencil-square"></i>
              </a>
              <a class="btn btn-sm btn-outline-danger" href="<?=BASE_URL?>/admin.php?route=users&action=delete&id=<?=$u['id']?>" 
                 onclick="return confirm('Bạn có chắc muốn xóa người dùng này?');">
                <i class="bi bi-trash"></i>
              </a>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
