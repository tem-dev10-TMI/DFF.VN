<?php if(!empty($_SESSION['user'])): ?>
  <span class="navbar-text me-3">
    Xin chào, <?=e($_SESSION['user']['username'])?>
  </span>
  <a class="btn btn-outline-light btn-sm" href="<?=BASE_URL?>/admin.php?route=logout">Đăng xuất</a>
<?php endif; ?>
</div> <!-- container -->
<script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/app.js"></script>
</body>
</html>
