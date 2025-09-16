<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h3>Quản lý Sự kiện</h3>
  <a href="<?= BASE_URL ?>/admin.php?route=events&action=create" class="btn btn-success">+ Thêm mới</a>
</div>

<table class="table table-striped table-bordered">
  <thead>
    <tr>
      <th>#</th>
      <th>Tiêu đề</th>
      <th>Ngày diễn ra</th>
      <th>Mã CK</th>
      <th>Link sự kiện</th>
      <th>Hành động</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($events as $ev): ?>
      <tr>
        <td><?= e($ev['id']) ?></td>
        <td><?= e($ev['title']) ?></td>
        <td><?= e($ev['event_date']) ?></td>
        <td><?= e($ev['stock_ticker'] ?? '') ?></td>
        <td>
          <?php if (!empty($ev['event_url'])): ?>
            <a href="<?= e($ev['event_url']) ?>" target="_blank">Xem</a>
          <?php endif; ?>
        </td>
        <td>
          <a class="btn btn-sm btn-primary" href="<?= BASE_URL ?>/admin.php?route=events&action=edit&id=<?= $ev['id'] ?>">Sửa</a>
          <a class="btn btn-sm btn-danger" 
             href="<?= BASE_URL ?>/admin.php?route=events&action=delete&id=<?= $ev['id'] ?>" 
             onclick="return confirm('Xóa sự kiện này?')">Xóa</a>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?php include __DIR__ . '/../layout/footer.php'; ?>
