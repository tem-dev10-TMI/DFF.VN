<?php include __DIR__ . '/../layout/header.php'; ?>
<?php
// Chuẩn hoá dữ liệu
$topic = is_array($topic ?? null) ? $topic : [];
$defaults = ['id' => null, 'name' => '', 'slug' => '', 'description' => '', 'icon_url' => ''];
$topic = array_merge($defaults, $topic);
$isEdit = !empty($topic['id']);

$formAction = BASE_URL . '/admin.php?route=topics&action=' . ($isEdit ? ('update&id=' . (int) $topic['id']) : 'store');
$iconSrc = $topic['icon_url'] ? rtrim(BASE_URL, '/') . '/' . ltrim($topic['icon_url'], '/') : '';
?>

<div class="row justify-content-center">
  <div class="col-lg-7">
    <div class="card border-0 shadow-sm">
      <div class="card-header bg-white">
        <h5 class="mb-0"><?= $isEdit ? 'Sửa Topic' : 'Thêm Topic' ?></h5>
      </div>


      <div class="card-body">
        <?php if ($msg = flash('error')): ?>
          <div class="alert alert-danger mb-3"><?= e($msg) ?></div>
        <?php endif; ?>
        <?php if ($msg = flash('success')): ?>
          <div class="alert alert-success mb-3"><?= e($msg) ?></div>
        <?php endif; ?>

        <form method="post" action="<?= $formAction ?>" enctype="multipart/form-data" class="topic-form">
          <div class="row g-3">
            <div class="col-12">
              <label class="form-label fw-semibold">Tên topic</label>
              <input name="name" class="form-control form-control-lg" value="<?= e($topic['name']) ?>" required>
            </div>

            <div class="col-md-12">
              <label class="form-label fw-semibold">Slug</label>
              <input name="slug" class="form-control form-control-lg" placeholder="Để trống sẽ tự tạo"
                value="<?= e($topic['slug']) ?>">
            </div>

            <div class="col-12">
              <label class="form-label fw-semibold">Mô tả</label>
              <textarea name="description" class="form-control" rows="4"><?= e($topic['description']) ?></textarea>
            </div>

            <!-- Upload / Preview -->
            <div class="col-12">
              <label class="form-label fw-semibold">Tải icon (tùy chọn)</label>
              <div class="dropzone-like">
                <input type="file" name="icon_file" id="icon_file" accept=".png,.jpg,.jpeg,.svg,.webp">
                <div class="dz-in">
                  <i class="bi bi-cloud-arrow-up"></i>
                  <div>Kéo & thả ảnh vào đây hoặc <span class="link">chọn file</span></div>
                  <small class="text-muted">PNG/JPG/SVG/WebP • ≤ 2MB • Gợi ý 64×64</small>
                </div>
              </div>

              <div class="mt-3 d-flex align-items-center gap-3">
                <img id="icon_preview" src="<?= e($iconSrc) ?>" alt="icon" class="icon-preview"
                  style="<?= $iconSrc ? '' : 'display:none;' ?>">
                <div class="text-muted small">
                  <div><b>Preview</b> (từ URL hoặc file upload)</div>

                </div>
              </div>
            </div>
          </div>

          <div class="text-end mt-4">
            <a href="<?= BASE_URL ?>/admin.php?route=topics&action=index" class="btn btn-outline-secondary">Hủy</a>
            <button class="btn btn-primary px-4"><?= $isEdit ? 'Cập nhật' : 'Tạo mới' ?></button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<style>
  .topic-form .form-text code {
    color: #0f172a;
  }

  .dropzone-like {
    position: relative;
    border: 1px dashed #cfd8e3;
    border-radius: 12px;
    background: #f8fafc;
    min-height: 110px;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .dropzone-like input[type=file] {
    position: absolute;
    inset: 0;
    opacity: 0;
    cursor: pointer;
  }

  .dropzone-like .dz-in {
    text-align: center;
    color: #475569;
  }

  .dropzone-like .dz-in i {
    font-size: 1.6rem;
    display: block;
    margin-bottom: .25rem;
    color: #2563eb;
  }

  .dropzone-like .dz-in .link {
    color: #2563eb;
    text-decoration: underline;
  }

  .icon-preview {
    max-height: 72px;
    border: 1px solid #eef0f3;
    border-radius: 10px;
    padding: 6px;
    background: #fff;
  }
</style>

<script>
  (function () {
    const inputFile = document.getElementById('icon_file');
    const inputUrl = document.getElementById('icon_url');
    const preview = document.getElementById('icon_preview');

    // Preview khi đổi URL text
    if (inputUrl) {
      inputUrl.addEventListener('input', () => {
        const rel = (inputUrl.value || '').trim();
        if (!rel) { preview.style.display = 'none'; preview.src = ''; return; }
        const abs = '<?= rtrim(BASE_URL, "/") ?>' + '/' + rel.replace(/^\/+/, '');
        preview.src = abs; preview.style.display = 'inline-block';
      });
    }

    // Preview khi chọn file
    if (inputFile) {
      let lastURL = null;
      inputFile.addEventListener('change', function () {
        const f = this.files?.[0];
        if (!f) return;
        if (f.type !== 'image/svg+xml' && f.size > 2 * 1024 * 1024) {
          alert('Ảnh quá lớn (≤ 2MB).'); this.value = ''; return;
        }
        if (lastURL) URL.revokeObjectURL(lastURL);
        lastURL = URL.createObjectURL(f);
        preview.src = lastURL; preview.style.display = 'inline-block';
      });
    }
  })();
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>