<?php include __DIR__ . '/../layout/header.php'; ?>
<?php $isEdit = isset($topic) && $topic; ?>

<div class="row justify-content-center">
  <div class="col-lg-6">
    <div class="card shadow-sm border-0">
      <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><?= $isEdit ? 'Sửa Topic' : 'Thêm Topic' ?></h5>
      </div>
      <div class="card-body">

        <!-- Nếu có upload file thì thêm enctype -->
        <form method="post"
              action="<?= BASE_URL ?>/admin.php?route=topics&action=<?= $isEdit ? 'update&id='.$topic['id'] : 'store'?>"
              enctype="multipart/form-data">

          <div class="mb-3">
            <label class="form-label fw-semibold">Tên topic</label>
            <input name="name" class="form-control" value="<?= $isEdit? e($topic['name']) : '' ?>" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Slug</label>
            <input name="slug" class="form-control" value="<?= $isEdit? e($topic['slug']) : '' ?>">
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Mô tả</label>
            <textarea name="description" class="form-control" rows="4"><?= $isEdit? e($topic['description']) : '' ?></textarea>
          </div>

          <!-- (Tuỳ chọn) Cho phép upload file để tự tạo icon_url -->
         <div class="mb-3">
  <label class="form-label fw-semibold">Tải ảnh lên (file)</label>
  <input type="file" name="icon_file" id="icon_file" class="form-control" accept=".png,.jpg,.jpeg,.svg,.webp">
  <div class="form-text">Chọn ảnh PNG/JPG/SVG/WebP. Gợi ý ~ 64×64.</div>
</div>

<!-- Preview luôn có sẵn -->
<div class="mb-3">
  <label class="form-label fw-semibold d-block">Xem trước</label>
<?php
  $iconRel = ($isEdit && !empty($topic['icon_url'])) ? $topic['icon_url'] : '';

// chuẩn hoá: đảm bảo bắt đầu bằng '/public/...'
  if ($iconRel) {
    // nếu là 'public/...' thì thêm '/' ở đầu
    if (strpos($iconRel, 'public/') === 0) {
      $iconRel = '/' . $iconRel;              // -> '/public/...'
    }
    // nếu là '/topic_img/...' mà server bạn cần '/public', có thể thêm:
    // if (strpos($iconRel, '/topic_img/') === 0) { $iconRel = '/public' . $iconRel; }
  }


    // Ghép BASE_URL an toàn
    $iconSrc = $iconRel ? (rtrim(BASE_URL, '/') . $iconRel) : '';
  ?>
  <img id="icon_preview"
       src="<?= e($iconSrc) ?>"
       alt="icon preview"
       style="max-height:64px; border:1px solid #eee; border-radius:8px; padding:4px; background:#fff; <?= $iconSrc ? '' : 'display:none;' ?>">
</div>


       

          <div class="text-end">
            <a href="<?= BASE_URL ?>/admin.php?route=topics" class="btn btn-secondary">Hủy</a>
            <button class="btn btn-primary"><?= $isEdit ? 'Cập nhật' : 'Tạo mới' ?></button>
          </div>

        </form>

      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
 <script>
(function () {
  const input   = document.getElementById('icon_file');
  const preview = document.getElementById('icon_preview');
  let lastURL = null;

  if (!input || !preview) return;

  input.addEventListener('change', function () {
    const file = this.files && this.files[0];
    if (!file) {
      if (!preview.getAttribute('src')) preview.style.display = 'none';
      return;
    }

    const MAX = 2 * 1024 * 1024;
    if (file.size > MAX && file.type !== 'image/svg+xml') {
      alert('Ảnh quá lớn, vui lòng chọn ảnh ≤ 2MB.');
      this.value = '';
      return;
    }

    if (lastURL) { URL.revokeObjectURL(lastURL); lastURL = null; }

    const url = URL.createObjectURL(file);
    lastURL = url;
    preview.src = url;
    // bảo đảm hiện ra nếu trước đó bị ẩn
    preview.style.display = 'inline-block';
  });
})();

</script>
