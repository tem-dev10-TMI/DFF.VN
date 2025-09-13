<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="card shadow-sm border-0">
  <div class="card-header bg-white d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Danh sách Tags</h5>
    <a href="<?=BASE_URL?>/admin.php?route=tags&action=create" class="btn btn-success btn-sm">
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
            <th>Slug</th>
            <th class="text-end">Hành động</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach($tags as $t): ?>
          <tr>
            <td><?=e($t['id'])?></td>
            <td><?=e($t['name'])?></td>
            <td><span class="text-muted"><?=e($t['slug'])?></span></td>
            <td class="text-end">
              <a class="btn btn-sm btn-outline-primary" 
                 href="<?=BASE_URL?>/admin.php?route=tags&action=edit&id=<?=$t['id']?>">
                <i class="bi bi-pencil-square"></i>
              </a>
              <a class="btn btn-sm btn-outline-danger" 
                 href="<?=BASE_URL?>/admin.php?route=tags&action=delete&id=<?=$t['id']?>" 
                 onclick="return confirm('Bạn có chắc muốn xóa tag này?');">
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
