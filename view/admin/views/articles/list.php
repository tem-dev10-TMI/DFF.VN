<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="card shadow-sm border-0">
  <div class="card-header bg-white d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Danh sách bài viết</h5>
    <a href="<?=BASE_URL?>/index.php?route=articles&action=create" class="btn btn-success btn-sm">
      <i class="bi bi-plus-circle me-1"></i> Thêm mới
    </a>
  </div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Tiêu đề</th>
            <th>Chủ đề</th>
            <th>Tác giả</th>
            <th>Trạng thái</th>
            <th>Ngày tạo</th>
            <th class="text-end">Hành động</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach($articles as $a): ?>
          <tr>
            <td><?=e($a['id'])?></td>
            <td><?=e($a['title'])?></td>
            <td><?=e($a['topic_name'] ?? '')?></td>
            <td><?=e($a['author_name'] ?? '')?></td>
            <td>
              <?php if($a['status']=='public'): ?>
                <span class="badge bg-success">Công khai</span>
              <?php elseif($a['status']=='private'): ?>
                <span class="badge bg-secondary">Riêng tư</span>
              <?php elseif($a['status']=='follower_only'): ?>
                <span class="badge bg-info text-dark">Chỉ follower</span>
              <?php else: ?>
                <span class="badge bg-light text-dark"><?=e($a['status'])?></span>
              <?php endif; ?>
            </td>
            <td><?=date('d/m/Y', strtotime($a['created_at']))?></td>
            <td class="text-end">
              <a class="btn btn-sm btn-outline-primary" href="<?=BASE_URL?>/index.php?route=articles&action=edit&id=<?=$a['id']?>">
                <i class="bi bi-pencil-square"></i>
              </a>
              <a class="btn btn-sm btn-outline-danger" href="<?=BASE_URL?>/index.php?route=articles&action=delete&id=<?=$a['id']?>" 
                 onclick="return confirm('Bạn có chắc muốn xóa bài viết này?');">
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
