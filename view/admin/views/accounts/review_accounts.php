<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="container mt-4">
  <h3 class="mb-4">Quản lý tài khoản doanh nhân</h3>

  <!-- Danh sách chờ duyệt -->
  <h5 class="mb-3">Tài khoản chờ duyệt</h5>
  <table class="table table-bordered">
    <thead class="table-light">
      <tr>
        <th>ID</th>
        <th>Tên</th>
        <th>Email</th>
        <th>Năm sinh</th>
        <th>Quốc tịch</th>
        <th>Học vấn</th>
        <th>Chức vụ</th>
        <th>Ngày đăng ký</th>
        <th>Hành động</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $stmt = $pdo->query("
        SELECT b.id, u.name, u.email, b.birth_year, b.nationality, b.education, b.position, b.created_at
        FROM businessmen b
        JOIN users u ON b.user_id = u.id
        WHERE b.status = 'pending'
      ");
      $pending = $stmt->fetchAll(PDO::FETCH_ASSOC);
      ?>

      <?php if (!empty($pending)): ?>
        <?php foreach ($pending as $acc): ?>
          <tr>
            <td><?= $acc['id'] ?></td>
            <td><?= htmlspecialchars($acc['name']) ?></td>
            <td><?= htmlspecialchars($acc['email']) ?></td>
            <td><?= $acc['birth_year'] ?></td>
            <td><?= $acc['nationality'] ?></td>
            <td><?= $acc['education'] ?></td>
            <td><?= $acc['position'] ?></td>
            <td><?= $acc['created_at'] ?></td>
            <td>
              <a href="admin.php?route=accounts&action=approve&id=<?= $acc['id'] ?>"
                 class="btn btn-sm btn-success">Duyệt</a>
              <a href="admin.php?route=accounts&action=reject&id=<?= $acc['id'] ?>"
                 class="btn btn-sm btn-danger"
                 onclick="return confirm('Bạn có chắc muốn từ chối yêu cầu này?');">
                 Từ chối
              </a>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr><td colspan="9" class="text-center">Không có tài khoản nào chờ duyệt</td></tr>
      <?php endif; ?>
    </tbody>
  </table>

  <!-- Danh sách doanh nhân -->
  <h5 class="mb-3 mt-5">Tài khoản doanh nhân</h5>
  <table class="table table-bordered">
    <thead class="table-light">
      <tr>
        <th>ID</th>
        <th>Tên</th>
        <th>Email</th>
        <th>Năm sinh</th>
        <th>Quốc tịch</th>
        <th>Học vấn</th>
        <th>Chức vụ</th>
        <th>Ngày duyệt</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $stmt = $pdo->query("
        SELECT b.id, u.name, u.email, b.birth_year, b.nationality, b.education, b.position, b.updated_at
        FROM businessmen b
        JOIN users u ON b.user_id = u.id
        WHERE b.status = 'approved'
      ");
      $approved = $stmt->fetchAll(PDO::FETCH_ASSOC);
      ?>

      <?php if (!empty($approved)): ?>
        <?php foreach ($approved as $acc): ?>
          <tr>
            <td><?= $acc['id'] ?></td>
            <td><?= htmlspecialchars($acc['name']) ?></td>
            <td><?= htmlspecialchars($acc['email']) ?></td>
            <td><?= $acc['birth_year'] ?></td>
            <td><?= $acc['nationality'] ?></td>
            <td><?= $acc['education'] ?></td>
            <td><?= $acc['position'] ?></td>
            <td><?= $acc['updated_at'] ?></td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr><td colspan="8" class="text-center">Chưa có tài khoản doanh nhân nào</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
