<?php include __DIR__ . '/../layout/header.php'; ?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h3>Events</h3>
  <a href="<?=BASE_URL?>/admin.php?route=events&action=create" class="btn btn-success">Thêm mới</a>
</div>
<table class="table"><thead><tr><th>#</th><th>Title</th><th>Start</th><th>End</th><th>Hành động</th></tr></thead><tbody>
<?php foreach($events as $ev): ?>
<tr><td><?=e($ev['id'])?></td><td><?=e($ev['title'])?></td><td><?=e($ev['start_date'] ?? '')?></td><td><?=e($ev['end_date'] ?? '')?></td><td><a class="btn btn-sm btn-primary" href="<?=BASE_URL?>/admin.php?route=events&action=edit&id=<?=$ev['id']?>">Sửa</a> <a class="btn btn-sm btn-danger" href="<?=BASE_URL?>/admin.php?route=events&action=delete&id=<?=$ev['id']?>" data-confirm="Xóa event?">Xóa</a></td></tr>
<?php endforeach; ?>
</tbody></table>
<?php include __DIR__ . '/../layout/footer.php'; ?>
