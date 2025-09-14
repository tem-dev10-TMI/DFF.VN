<?php include __DIR__ . '/../layout/header.php'; ?>
<?php $isEdit = isset($topic) && $topic; ?>

<div class="row justify-content-center">
  <div class="col-lg-6">
    <div class="card shadow-sm border-0">
      <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><?= $isEdit ? 'Sửa Topic' : 'Thêm Topic' ?></h5>
      </div>
      <div class="card-body">

        <form method="post" action="<?=BASE_URL?>/admin.php?route=topics&action=<?= $isEdit ? 'update&id='.$topic['id'] : 'store'?>">

          <div class="mb-3">
            <label class="form-label fw-semibold">Tên topic</label>
            <input name="name" class="form-control" value="<?=$isEdit?e($topic['name']):''?>" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Slug</label>
            <input name="slug" class="form-control" value="<?=$isEdit?e($topic['slug']):''?>">
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Mô tả</label>
            <textarea name="description" class="form-control" rows="4"><?=$isEdit?e($topic['description']):''?></textarea>
          </div>

          <div class="text-end">
            <a href="<?=BASE_URL?>/admin.php?route=topics" class="btn btn-secondary">Hủy</a>
            <button class="btn btn-primary"><?= $isEdit ? 'Cập nhật' : 'Tạo mới' ?></button>
          </div>

        </form>

      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
