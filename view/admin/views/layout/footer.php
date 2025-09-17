<?php if (!empty($_SESSION['user'])): ?>
  <span class="navbar-text me-3">
    Xin chào,
    <?= e($_SESSION['user']['name'] ?? $_SESSION['user']['username']) ?>
  </span>
  <a class="btn btn-outline-light btn-sm" href="<?= BASE_URL ?>/admin.php?route=logout">Đăng xuất</a>
<?php endif; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>