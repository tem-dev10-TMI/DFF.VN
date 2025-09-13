<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="row justify-content-center">
  <div class="col-lg-6">
    <div class="card shadow-sm border-0">
      <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Upload Media</h5>
      </div>
      <div class="card-body">

        <form method="post" enctype="multipart/form-data" 
              action="<?=BASE_URL?>/admin.php?route=media&action=store">

          <div class="mb-3">
            <label class="form-label fw-semibold">Chọn file</label>
            <input type="file" name="file" class="form-control" required>
            <div class="form-text">Hỗ trợ: hình ảnh, video, tài liệu…</div>
          </div>

          <div class="text-end">
            <a href="<?=BASE_URL?>/admin.php?route=media" class="btn btn-secondary">Hủy</a>
            <button class="btn btn-primary">Upload</button>
          </div>
        </form>

      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
