<?php include __DIR__ . '/../layout/header.php'; ?>
<?php $isEdit = isset($event) && $event; ?>

<h3><?= $isEdit ? 'Sửa sự kiện' : 'Thêm sự kiện' ?></h3>

<form method="post" action="<?= BASE_URL ?>/admin.php?route=events&action=<?= $isEdit ? 'update&id='.$event['id'] : 'store' ?>">
  <div class="mb-3">
    <label class="form-label">Tiêu đề</label>
    <input name="title" class="form-control" value="<?= $isEdit ? e($event['title']) : '' ?>" required>
  </div>

  <div class="mb-3">
    <label class="form-label">Link sự kiện</label>
    <input name="event_url" class="form-control" value="<?= $isEdit ? e($event['event_url']) : '' ?>">
  </div>

  <div class="mb-3">
    <label class="form-label">Ngày diễn ra</label>
    <input name="event_date" type="date" class="form-control" value="<?= $isEdit ? e($event['event_date']) : '' ?>">
  </div>

  <div class="mb-3">
    <label class="form-label">Mã cổ phiếu</label>
    <input name="stock_ticker" class="form-control" value="<?= $isEdit ? e($event['stock_ticker']) : '' ?>">
  </div>

  <div class="mb-3">
    <label class="form-label">Mô tả</label>
    <textarea name="description" class="form-control"><?= $isEdit ? e($event['description']) : '' ?></textarea>
  </div>

  <button class="btn btn-primary"><?= $isEdit ? 'Cập nhật' : 'Tạo' ?></button>
</form>

<?php include __DIR__ . '/../layout/footer.php'; ?>
