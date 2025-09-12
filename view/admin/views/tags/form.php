<?php include __DIR__ . '/../layout/header.php'; ?>
<?php $isEdit = isset($tag) && $tag; ?>

<div class="row justify-content-center">
  <div class="col-lg-6">
    <div class="card shadow-sm border-0">
      <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><?= $isEdit ? 'Sửa Tag' : 'Thêm Tag' ?></h5>
      </div>
      <div class="card-body">

        <form method="post" action="<?=BASE_URL?>/index.php?route=tags&action=<?= $isEdit ? 'update&id='.$tag['id'] : 'store'?>">

          <div class="mb-3">
            <label class="form-label fw-semibold">Tên tag</label>
            <input name="name" class="form-control" 
                   value="<?=$isEdit?e($tag['name']):''?>" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Slug</label>
            <input name="slug" class="form-control" 
                   value="<?=$isEdit?e($tag['slug']):''?>">
          </div>

          <div class="text-end">
            <a href="<?=BASE_URL?>/index.php?route=tags" class="btn btn-secondary">Hủy</a>
            <button class="btn btn-primary"><?=$isEdit ? 'Cập nhật' : 'Tạo mới'?></button>
          </div>
        </form>

      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
