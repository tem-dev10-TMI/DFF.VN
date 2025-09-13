<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="card shadow-sm border-0">
  <div class="card-header bg-white d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Danh sách Media</h5>
    <a href="<?=BASE_URL?>/admin.php?route=media&action=create" class="btn btn-success btn-sm">
      <i class="bi bi-upload me-1"></i> Upload
    </a>
  </div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Tên file</th>
            <th>URL</th>
            <th>Kích thước</th>
            <th class="text-end">Hành động</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach($media as $m): ?>
          <tr>
            <td><?=e($m['id'])?></td>
            <td><?=e($m['filename'] ?? $m['url'])?></td>
            <td>
              <a href="<?=e($m['url'])?>" target="_blank" class="text-decoration-none">
                <i class="bi bi-link-45deg"></i> <?=basename($m['url'])?>
              </a>
            </td>
            <td>
              <?= !empty($m['size']) ? round($m['size']/1024, 2).' KB' : '<span class="text-muted">N/A</span>' ?>
            </td>
            <td class="text-end">
              <a class="btn btn-sm btn-outline-danger" 
                 href="<?=BASE_URL?>/admin.php?route=media&action=delete&id=<?=$m['id']?>" 
                 onclick="return confirm('Bạn có chắc muốn xóa media này?');">
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
