<?php include __DIR__ . '/layout/header.php'; ?>

<div class="row g-4">
  <!-- Người dùng -->
  <div class="col-md-3">
    <div class="card shadow-sm border-0 h-100">
      <div class="card-body text-center">
        <div class="mb-2 text-primary">
          <i class="bi bi-people-fill" style="font-size:2.5rem;"></i>
        </div>
        <h5 class="card-title">Người dùng</h5>
        <?php $countUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn(); ?>
        <p class="display-6 fw-bold"><?=intval($countUsers)?></p>
        <a href="<?=BASE_URL?>/admin.php?route=users" class="btn btn-outline-primary btn-sm">Quản lý</a>
      </div>
    </div>
  </div>

  <!-- Bài viết -->
  <div class="col-md-3">
    <div class="card shadow-sm border-0 h-100">
      <div class="card-body text-center">
        <div class="mb-2 text-success">
          <i class="bi bi-file-earmark-text-fill" style="font-size:2.5rem;"></i>
        </div>
        <h5 class="card-title">Bài viết</h5>
        <?php $countArticles = $pdo->query("SELECT COUNT(*) FROM articles")->fetchColumn(); ?>
        <p class="display-6 fw-bold"><?=intval($countArticles)?></p>
        <a href="<?=BASE_URL?>/admin.php?route=articles" class="btn btn-outline-success btn-sm">Quản lý</a>
      </div>
    </div>
  </div>

  <!-- Sự kiện -->
  <div class="col-md-3">
    <div class="card shadow-sm border-0 h-100">
      <div class="card-body text-center">
        <div class="mb-2 text-info">
          <i class="bi bi-calendar-event-fill" style="font-size:2.5rem;"></i>
        </div>
        <h5 class="card-title">Sự kiện</h5>
        <?php $countEvents = $pdo->query("SELECT COUNT(*) FROM events")->fetchColumn(); ?>
        <p class="display-6 fw-bold"><?= intval($countEvents) ?></p>
        <a href="<?= BASE_URL ?>/admin.php?route=events" class="btn btn-outline-info btn-sm">Quản lý</a>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/layout/footer.php'; ?>
