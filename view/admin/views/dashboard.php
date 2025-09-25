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
        <p class="display-6 fw-bold"><?= intval($countUsers) ?></p>
        <a href="<?= BASE_URL ?>/admin.php?route=users" class="btn btn-outline-primary btn-sm">Quản lý</a>
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
        <p class="display-6 fw-bold"><?= intval($countArticles) ?></p>
        <a href="<?= BASE_URL ?>/admin.php?route=articles" class="btn btn-outline-success btn-sm">Quản lý</a>
      </div>
    </div>
  </div>

  <!-- Duyệt bài đăng -->
  <div class="col-md-3">
    <div class="card shadow-sm border-0 h-100">
      <div class="card-body text-center">
        <div class="mb-2 text-warning">
          <i class="bi bi-check-circle-fill" style="font-size:2.5rem;"></i>
        </div>
        <h5 class="card-title">Duyệt bài đăng</h5>
        <?php $pendingArticles = $pdo->query("SELECT COUNT(*) FROM articles WHERE status = 'pending'")->fetchColumn(); ?>
        <p class="display-6 fw-bold"><?= intval($pendingArticles) ?></p>
        <a href="<?= BASE_URL ?>/admin.php?route=article&action=reviewList" class="btn btn-outline-warning btn-sm">Quản
          lý</a>
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


  <div class="row g-4">
    <!-- Duyệt tài khoản -->
    <div class="col-md-3">
      <div class="card shadow-sm border-0 h-100">
        <div class="card-body text-center">
          <div class="mb-2 text-primary">
            <i class="bi bi-person-check-fill" style="font-size:2.5rem;"></i>
          </div>
          <h5 class="card-title">Duyệt tài khoản doanh nhân</h5>
          <?php
          $pendingAccounts = $pdo->query("
            SELECT COUNT(*) 
            FROM businessmen b 
            JOIN users u ON b.user_id = u.id 
            WHERE u.role = 'user'
        ")->fetchColumn();
          ?>
          <p class="display-6 fw-bold"><?= intval($pendingAccounts) ?></p>
          <a href="<?= BASE_URL ?>/admin.php?route=accounts&action=reviewList"
            class="btn btn-outline-danger btn-sm">Quản
            lý</a>
        </div>
      </div>
    </div>
    <!-- Tổng lượt truy cập -->
    <?php
    $ROOT = realpath(__DIR__ . '/../../..');
    require_once $ROOT . '/model/admin/VisitsModel.php';
    require_once $ROOT . '/model/admin/StatsModel.php';
    $totalViews = StatsModel::totalViews($pdo);
    $online = VisitsModel::onlineCount($pdo);
    ?>
    <div class="col-md-3">
      <div class="card shadow-sm border-0 h-100">
        <div class="card-body text-center">
          <div class="mb-2 text-primary">
            <i class="bi bi-eye-fill" style="font-size:2.5rem;"></i>
          </div>
          <h5 class="card-title">Tổng lượt truy cập</h5>
          <p class="display-6 fw-bold">
            <span id="totalViews"><?= number_format($totalViews) ?></span>
          </p>
        </div>
      </div>
    </div>

    <!-- Đang trực tuyến (5 phút gần nhất) -->
    <div class="col-md-3">
      <div class="card shadow-sm border-0 h-100">
        <div class="card-body text-center">
          <div class="mb-2 text-primary">
            <i class="bi bi-activity" style="font-size:2.5rem;"></i>
          </div>
          <h5 class="card-title">Đang trực tuyến</h5>
          <p class="display-6 fw-bold">
            <span id="onlineCount"><?= (int) $online ?></span>
          </p>
        </div>
      </div>
    </div>

  </div>

  <?php include __DIR__ . '/layout/footer.php'; ?>