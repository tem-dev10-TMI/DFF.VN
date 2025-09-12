<?php include __DIR__ . '/../layout/header.php'; ?>
<?php $isEdit = isset($event) && $event; ?>
<h3><?=$isEdit ? 'Sửa event' : 'Thêm event'?></h3>
<form method="post" action="<?=BASE_URL?>/index.php?route=events&action=<?=$isEdit ? 'update&id='.$event['id'] : 'store'?>">
  <div class="mb-3"><label class="form-label">Title</label><input name="title" class="form-control" value="<?=$isEdit?e($event['title']):''?>" required></div>
  <div class="mb-3"><label class="form-label">Start date</label><input name="start_date" type="date" class="form-control" value="<?=$isEdit?e($event['start_date']):''?>"></div>
  <div class="mb-3"><label class="form-label">End date</label><input name="end_date" type="date" class="form-control" value="<?=$isEdit?e($event['end_date']):''?>"></div>
  <div class="mb-3"><label class="form-label">Description</label><textarea name="description" class="form-control"><?=$isEdit?e($event['description']):''?></textarea></div>
  <button class="btn btn-primary"><?=$isEdit ? 'Cập nhật' : 'Tạo'?></button>
</form>
<?php include __DIR__ . '/../layout/footer.php'; ?>
