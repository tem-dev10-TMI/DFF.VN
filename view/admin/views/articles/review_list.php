<?php include __DIR__ . '/../layout/header.php'; ?>

<h2 class="mb-4">Bài viết chờ duyệt</h2>
<table class="table table-bordered table-striped">
  <thead>
    <tr>
      <th>Tiêu đề</th>
      <th>Tác giả</th>
      <th>Ngày gửi</th>
      <th>Hành động</th>
    </tr>
  </thead>
  <tbody>
    <?php if (!empty($articles)): ?>
      <?php foreach ($articles as $article): ?>
        <tr>
          <td><?= htmlspecialchars($article['title']) ?></td>
          <td><?= htmlspecialchars($article['author_name']) ?></td>
          <td><?= $article['published_at'] ?></td>
          <td>
            <button class="btn btn-view" onclick="toggleDetail(<?= $article['id'] ?>)">Xem</button>
            <a href="admin.php?route=article&action=reviewAction&id=<?= $article['id'] ?>&do=approved" class="btn btn-approve">Duyệt</a>
            <button class="btn btn-reject" onclick="openRejectModal(<?= $article['id'] ?>)">Từ chối</button>
          </td>
        </tr>
        <!-- Chi tiết bài viết -->
        <tr id="detail-<?= $article['id'] ?>" style="display:none;">
          <td colspan="4">
            <div class="detail-box">
              <h5><?= htmlspecialchars($article['title']) ?></h5>
              <p><strong>Tóm tắt:</strong> <?= nl2br(htmlspecialchars($article['summary'] ?? '')) ?></p>
              <p><strong>Nội dung:</strong></p>
              <div style="white-space: pre-wrap;"><?= $article['content'] ?></div>
            </div>
          </td>
        </tr>
      <?php endforeach; ?>
    <?php else: ?>
      <tr><td colspan="4" class="text-center">Không có bài viết chờ duyệt</td></tr>
    <?php endif; ?>
  </tbody>
</table>

<hr>

<h2>Bài viết đã duyệt</h2>
<table class="table table-bordered table-striped">
  <thead>
    <tr>
      <th>Tiêu đề</th>
      <th>Tác giả</th>
      <th>Admin duyệt</th>
      <th>Trạng thái</th>
      <th>Ngày duyệt</th>
      <th>Lý do</th>
      <th>Hành động</th>
    </tr>
  </thead>
  <tbody>
    <?php if (!empty($reviewedArticles)): ?>
      <?php foreach ($reviewedArticles as $r): ?>
        <tr>
          <td><?= htmlspecialchars($r['title']) ?></td>
          <td><?= htmlspecialchars($r['author_name']) ?></td>
          <td><?= htmlspecialchars($r['admin_name']) ?></td>
          <td>
            <?= $r['action'] === 'approved'
                ? '<span class="badge bg-success">Đã duyệt</span>'
                : '<span class="badge bg-danger">Từ chối</span>' ?>
          </td>
          <td><?= $r['reviewed_at'] ?></td>
          <td><?= htmlspecialchars($r['reason'] ?? '-') ?></td>
          <td>
            <button class="btn btn-view" onclick="toggleDetail('r<?= $r['id'] ?>')">Xem</button>
          </td>
        </tr>
        <!-- Chi tiết -->
        <tr id="detail-r<?= $r['id'] ?>" style="display:none;">
          <td colspan="7">
            <div class="detail-box">
              <h5><?= htmlspecialchars($r['title']) ?></h5>
              <p><strong>Tác giả:</strong> <?= htmlspecialchars($r['author_name']) ?></p>
              <p><strong>Admin duyệt:</strong> <?= htmlspecialchars($r['admin_name']) ?></p>
              <p><strong>Trạng thái:</strong> <?= $r['action'] === 'approved' ? 'Đã duyệt' : 'Từ chối' ?></p>
              <p><strong>Lý do:</strong> <?= htmlspecialchars($r['reason'] ?? '-') ?></p>
            </div>
          </td>
        </tr>
      <?php endforeach; ?>
    <?php else: ?>
      <tr><td colspan="7" class="text-center">Chưa có bài nào được duyệt</td></tr>
    <?php endif; ?>
  </tbody>
</table>

<!-- Modal nhập lý do từ chối -->
<div id="rejectModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeRejectModal()">&times;</span>
    <h3>Lý do từ chối</h3>
    <form id="rejectForm" method="POST">
      <textarea name="reason" id="rejectReason" placeholder="Nhập lý do..." required></textarea>
      <br>
      <button type="submit" class="btn btn-reject">Xác nhận từ chối</button>
    </form>
  </div>
</div>

<script>
let currentRejectId = null;

function toggleDetail(id) {
  const row = document.getElementById("detail-" + id);
  if (row && row.style.display === "table-row") {
    row.style.display = "none";
    return;
  }
  document.querySelectorAll("[id^='detail-']").forEach(el => el.style.display = "none");
  if (row) row.style.display = "table-row";
}

function openRejectModal(articleId) {
  currentRejectId = articleId;
  document.getElementById("rejectModal").style.display = "block";
  document.getElementById("rejectForm").action = `admin.php?route=article&action=reviewAction&id=${articleId}&do=rejected`;
}

function closeRejectModal() {
  document.getElementById("rejectModal").style.display = "none";
}
</script>

<style>
.detail-box {
  padding: 15px;
  background: #f9f9f9;
  border: 1px solid #ddd;
  border-radius: 5px;
}

/* Nút */
.btn { 
  padding: 6px 14px; 
  border: none; 
  border-radius: 4px; 
  margin-right: 6px; 
  cursor: pointer; 
  transition: transform 0.1s ease, opacity 0.2s;
}
.btn:hover { transform: scale(1.05); opacity: 0.9; }
.btn-view { background: #17a2b8; color: #fff; }
.btn-approve { background: #28a745; color: #fff; }
.btn-reject { background: #dc3545; color: #fff; }

/* Modal */
.modal {
  display: none;
  position: fixed;
  z-index: 1000;
  left: 0; top: 0;
  width: 100%; height: 100%;
  background: rgba(0,0,0,0.5);
}
.modal-content {
  background: #fff;
  margin: 10% auto;
  padding: 20px;
  border-radius: 6px;
  width: 400px;
}
.close {
  float: right;
  font-size: 20px;
  cursor: pointer;
}
textarea {
  width: 100%;
  height: 80px;
  margin-top: 10px;
  padding: 8px;
}
</style>

<?php include __DIR__ . '/../layout/footer.php'; ?>
