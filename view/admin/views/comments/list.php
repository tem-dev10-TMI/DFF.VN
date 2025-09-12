<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="card shadow-sm border-0">
  <div class="card-header bg-white d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Danh sách Bình luận</h5>
  </div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Tác giả</th>
            <th>Nội dung</th>
            <th>Trạng thái</th>
            <th class="text-end">Hành động</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach($comments as $c): ?>
          <tr>
            <td><?=e($c['id'])?></td>
            <td><?=e($c['author_name'] ?? $c['author'] ?? '')?></td>
            <td><?=e($c['content'] ?? '')?></td>
            <td>
              <?php if(!empty($c['approved'])): ?>
                <span class="badge bg-success">Đã duyệt</span>
              <?php else: ?>
                <span class="badge bg-warning text-dark">Chưa duyệt</span>
              <?php endif; ?>
            </td>
            <td class="text-end">
              <?php if(empty($c['approved'])): ?>
                <a class="btn btn-sm btn-outline-success" 
                   href="<?=BASE_URL?>/index.php?route=comments&action=approve&id=<?=$c['id']?>">
                  <i class="bi bi-check-circle"></i>
                </a>
              <?php endif; ?>
              <a class="btn btn-sm btn-outline-danger" 
                 href="<?=BASE_URL?>/index.php?route=comments&action=delete&id=<?=$c['id']?>"
                 onclick="return confirm('Bạn có chắc muốn xóa bình luận này?');">
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
