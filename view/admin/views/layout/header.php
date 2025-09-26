<!doctype html>
<html lang="vi">
<?php
$route = $_GET['route'] ?? 'dashboard';
?>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Startmin Admin</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

  <style>
    .navbar-nav .nav-link.active {
      font-weight: 600;
      color: #0d6efd !important;
      background: rgba(13, 110, 253, 0.1);
      border-radius: .5rem;
    }
  </style>
</head>

<body>
  <nav class="navbar navbar-expand-lg bg-white shadow-sm">
    <div class="container-fluid">
      <!-- Logo -->
      <a class="navbar-brand fw-bold text-primary" href="<?= BASE_URL ?>/admin.php">
        <i class="bi bi-speedometer2 me-1"></i> MXH Admin
      </a>

      <!-- Toggle -->
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Menu -->
      <div class="collapse navbar-collapse" id="navMain">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item"><a class="nav-link <?= ($route == 'dashboard' ? 'active' : '') ?>"
              href="<?= BASE_URL ?>/admin.php?route=dashboard"><i class="bi bi-house-door me-1"></i> Dashboard</a></li>
          <li class="nav-item"><a class="nav-link <?= ($route == 'users' ? 'active' : '') ?>"
              href="<?= BASE_URL ?>/admin.php?route=users"><i class="bi bi-people me-1"></i> Users</a></li>
          <li class="nav-item"><a class="nav-link <?= ($route == 'article' ? 'active' : '') ?>"
              href="<?= BASE_URL ?>/admin.php?route=article&action=reviewList"><i
                class="bi bi-file-earmark-text me-1"></i> Articles</a>
          </li>
          <li class="nav-item"><a class="nav-link <?= ($route == 'topics' ? 'active' : '') ?>"
              href="<?= BASE_URL ?>/admin.php?route=topics"><i class="bi bi-bookmarks me-1"></i> Topics</a></li>
          <li class="nav-item"><a class="nav-link <?= ($route == 'tags' ? 'active' : '') ?>"
              href="<?= BASE_URL ?>/admin.php?route=tags"><i class="bi bi-tags me-1"></i> Tags</a></li>
        </ul>

        <!-- User info -->
        <?php if (!empty($_SESSION['user'])): ?>
          <div class="dropdown">
            <a class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" href="#" id="userMenu"
              data-bs-toggle="dropdown">
              <img
                src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['user']['username']) ?>&background=random"
                alt="avatar" width="32" height="32" class="rounded-circle me-2">
              <span class="fw-semibold"><?= e($_SESSION['user']['username']) ?></span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item" href="#">Profile</a></li>
              <li><a class="dropdown-item" href="<?= BASE_URL ?>/admin.php?route=logout"><i
                    class="bi bi-box-arrow-right me-1"></i> Đăng xuất</a></li>
            </ul>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </nav>

  <div class="container mt-4">