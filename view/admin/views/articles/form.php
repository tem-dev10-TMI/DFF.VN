<?php include __DIR__ . '/../layout/header.php'; ?>
<?php $isEdit = isset($article) && $article; ?>

<div class="row justify-content-center">
  <div class="col-lg-8">
    <div class="card shadow-sm border-0">
      <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><?= $isEdit ? 'Sửa bài viết' : 'Thêm bài viết' ?></h5>
      </div>
      <div class="card-body">

        <form method="post" enctype="multipart/form-data"
              action="<?= BASE_URL ?>/index.php?route=articles&action=<?= $isEdit ? 'update&id=' . $article['id'] : 'store' ?>">

          <div class="mb-3">
            <label class="form-label fw-semibold">Tiêu đề</label>
            <input name="title" class="form-control" value="<?= $isEdit ? e($article['title']) : '' ?>" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Slug</label>
            <input name="slug" class="form-control" value="<?= $isEdit ? e($article['slug']) : '' ?>">
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Chủ đề</label>
            <select name="topic_id" class="form-select">
              <option value="">-- Chọn topic --</option>
              <?php foreach ($topics as $t): ?>
                <option value="<?= $t['id'] ?>" <?= $isEdit && $article['topic_id'] == $t['id'] ? 'selected' : '' ?>>
                  <?= e($t['name']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Tác giả</label>
            <select name="author_id" class="form-select">
              <?php foreach ($users as $u): ?>
                <option value="<?= $u['id'] ?>" <?= $isEdit && $article['author_id'] == $u['id'] ? 'selected' : '' ?>>
                  <?= e($u['name']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Ảnh chính</label>
            <input type="file" name="main_image" class="form-control">
            <?php if($isEdit && !empty($article['main_image'])): ?>
              <div class="mt-2">
                <img src="<?=BASE_URL?>/uploads/<?=e($article['main_image'])?>" 
                     alt="Ảnh bài viết" class="img-thumbnail" style="max-height:150px;">
              </div>
            <?php endif; ?>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Nội dung</label>
            <textarea name="content" class="form-control" rows="8"><?= $isEdit ? e($article['content']) : '' ?></textarea>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Trạng thái</label>
            <select name="status" class="form-select">
              <option value="public" <?=$isEdit && $article['status']=='public'?'selected':''?>>Công khai</option>
              <option value="private" <?=$isEdit && $article['status']=='private'?'selected':''?>>Riêng tư</option>
              <option value="follower_only" <?=$isEdit && $article['status']=='follower_only'?'selected':''?>>Chỉ follower</option>
            </select>
          </div>

          <div class="text-end">
            <a href="<?=BASE_URL?>/index.php?route=articles" class="btn btn-secondary">Hủy</a>
            <button class="btn btn-primary"><?= $isEdit ? 'Cập nhật' : 'Tạo mới' ?></button>
          </div>
        </form>

      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
