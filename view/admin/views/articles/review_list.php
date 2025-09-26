<?php include __DIR__ . '/../layout/header.php'; ?>
<?php
// Tạo URL giữ lại query khác, chỉ thay p1/p2
function build_url(array $params): string
{
  $base = BASE_URL . '/admin.php';
  $q = array_merge($_GET, $params);
  return $base . '?' . http_build_query($q);
}

// Component pagination gọn
function render_pagination(int $current, int $totalPages, string $paramName): void
{
  if ($totalPages <= 1)
    return;
  echo '<nav><ul class="pagination pagination-sm justify-content-end my-2">';
  // prev
  $prev = max(1, $current - 1);
  $next = min($totalPages, $current + 1);
  $mkli = function ($label, $page, $active = false, $disabled = false) use ($paramName) {
    $cls = 'page-item';
    if ($active)
      $cls .= ' active';
    if ($disabled)
      $cls .= ' disabled';
    $url = htmlspecialchars(build_url([$paramName => $page]));
    echo '<li class="' . $cls . '"><a class="page-link" href="' . $url . '">' . $label . '</a></li>';
  };
  $mkli('&laquo;', 1, false, $current == 1);
  $mkli('&lsaquo;', $prev, false, $current == 1);

  // window
  $start = max(1, $current - 2);
  $end = min($totalPages, $current + 2);
  if ($start > 1)
    echo '<li class="page-item disabled"><span class="page-link">…</span></li>';
  for ($i = $start; $i <= $end; $i++)
    $mkli((string) $i, $i, $i == $current);
  if ($end < $totalPages)
    echo '<li class="page-item disabled"><span class="page-link">…</span></li>';

  $mkli('&rsaquo;', $next, false, $current == $totalPages);
  $mkli('&raquo;', $totalPages, false, $current == $totalPages);
  echo '</ul></nav>';
}
?>

<div class="container-xxl">
  <div class="row g-4">
    <!-- PENDING -->
    <div class="col-12">
      <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between">
          <h4 class="mb-0"><i class="bi bi-hourglass-split me-2"></i>Bài viết chờ duyệt</h4>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th>Tiêu đề</th>
                  <th>Tác giả</th>
                  <th>Ngày gửi</th>
                  <th class="text-end">Hành động</th>
                </tr>
              </thead>
              <tbody>
                <?php if (!empty($articles)): ?>
                  <?php foreach ($articles as $article): ?>
                    <tr>
                      <td class="fw-semibold"><?= htmlspecialchars($article['title']) ?></td>
                      <td><?= htmlspecialchars($article['author_name']) ?></td>
                      <td><?= htmlspecialchars($article['created_at'] ?? $article['published_at'] ?? '') ?></td>
                      <td class="text-end">
                        <button class="btn btn-sm btn-info me-1" onclick="toggleDetail(<?= (int) $article['id'] ?>)">
                          <i class="bi bi-eye"></i> Xem
                        </button>
                        <a class="btn btn-sm btn-success me-1"
                          href="<?= BASE_URL ?>/admin.php?route=article&action=reviewAction&id=<?= (int) $article['id'] ?>&do=approved">
                          <i class="bi bi-check2-circle"></i> Duyệt
                        </a>
                        <button class="btn btn-sm btn-danger" onclick="openRejectModal(<?= (int) $article['id'] ?>)">
                          <i class="bi bi-x-circle"></i> Từ chối
                        </button>
                      </td>
                    </tr>

                    <?php
                    $full = $details[$article['id']] ?? ['article' => [], 'sections' => [], 'articleMedia' => []];
                    $a = $full['article'] ?? [];
                    $sections = $full['sections'] ?? [];
                    $articleMedia = $full['articleMedia'] ?? [];
                    $title = $a['title'] ?? '';
                    $summary = $a['summary'] ?? '';
                    $content = $a['content'] ?? '';
                    $mainImage = $a['main_image_url'] ?? '';
                    ?>

                    <tr id="detail-<?= (int) $article['id'] ?>" class="detail-row" style="display:none;">
                      <td colspan="4">
                        <?php include __DIR__ . '/../partials/article_preview_block.php'; ?>

                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="4" class="text-center text-muted py-4">Không có bài viết chờ duyệt</td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="pager-bar">
        <div class="pager-meta">
          <i class="bi bi-list-check"></i>
          <span>Tổng</span>
          <strong><?= (int) $paging['pendingTotal'] ?></strong>
          <span>bài •</span>
          <span><?= (int) $paging['pendingPages'] ?> trang</span>
        </div>
        <div class="pager-controls">
          <?php render_pagination($paging['pageP'], $paging['pendingPages'], 'p1'); ?>
        </div>
      </div>

    </div>

    <!-- REVIEWED -->
    <div class="col-12">
      <div class="card shadow-sm border-0">
        <div class="card-header bg-success text-white d-flex align-items-center justify-content-between">
          <h4 class="mb-0"><i class="bi bi-check2-all me-2"></i>Bài viết đã duyệt / đã xử lý</h4>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th>Tiêu đề</th>
                  <th>Tác giả</th>
                  <th>Admin</th>
                  <th>Trạng thái</th>
                  <th>Ngày</th>
                  <th class="text-end">Hành động</th>
                </tr>
              </thead>
              <tbody>
                <?php if (!empty($reviewedArticles)): ?>
                  <?php foreach ($reviewedArticles as $r): ?>
                    <tr>
                      <td class="fw-semibold"><?= htmlspecialchars($r['title']) ?></td>
                      <td><?= htmlspecialchars($r['author_name']) ?></td>
                      <td><?= htmlspecialchars($r['admin_name']) ?></td>
                      <td>
                        <?php if ($r['action'] === 'approved'): ?>
                          <span class="badge bg-success">Đã duyệt</span>
                        <?php elseif ($r['action'] === 'rejected'): ?>
                          <span class="badge bg-danger">Từ chối</span>
                        <?php else: ?>
                          <span class="badge bg-secondary"><?= htmlspecialchars($r['action']) ?></span>
                        <?php endif; ?>
                      </td>
                      <td><?= htmlspecialchars($r['reviewed_at']) ?></td>
                      <td class="text-end">
                        <button class="btn btn-sm btn-info me-1" onclick="toggleDetail('r<?= (int) $r['id'] ?>')">
                          <i class="bi bi-eye"></i> Xem
                        </button>
                        <button class="btn btn-sm btn-outline-danger"
                          onclick="openDeleteModal(<?= (int) $r['article_id'] ?>,'<?= htmlspecialchars(addslashes($r['title'])) ?>')">
                          <i class="bi bi-trash"></i> Xoá bài
                        </button>
                      </td>
                    </tr>

                    <?php
                    // detail giống pending (lấy sẵn từ controller)
                    $full = $reviewDetails[$r['id']] ?? ['article' => [], 'sections' => [], 'articleMedia' => []];
                    $a = $full['article'] ?? [];
                    $sections = $full['sections'] ?? [];
                    $articleMedia = $full['articleMedia'] ?? [];
                    $title = $a['title'] ?? '';
                    $summary = $a['summary'] ?? '';
                    $content = $a['content'] ?? '';
                    $mainImage = $a['main_image_url'] ?? '';
                    ?>

                    <tr id="detail-r<?= (int) $r['id'] ?>" class="detail-row" style="display:none;">
                      <td colspan="6">
                        <?php include __DIR__ . '/../partials/article_preview_block.php'; ?>

                        <div class="mt-3 small text-muted">
                          <strong>Trạng thái:</strong> <?= htmlspecialchars($r['action']) ?> —
                          <strong>Lý do:</strong> <?= htmlspecialchars($r['reason'] ?? '-') ?>
                        </div>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="6" class="text-center text-muted py-4">Chưa có bài nào</td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="pager-bar">
        <div class="pager-meta">
          <i class="bi bi-clipboard-check"></i>
          <span>Tổng</span>
          <strong><?= (int) $paging['reviewedTotal'] ?></strong>
          <span>bản ghi •</span>
          <span><?= (int) $paging['reviewedPages'] ?> trang</span>
        </div>
        <div class="pager-controls">
          <?php render_pagination($paging['pageR'], $paging['reviewedPages'], 'p2'); ?>
        </div>
      </div>


    </div>
  </div>
</div>

<!-- Modal: TỪ CHỐI -->
<div id="rejectModal" class="modal fade" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="rejectForm" method="POST" class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title"><i class="bi bi-x-circle me-2"></i>Lý do từ chối</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <textarea class="form-control" name="reason" id="rejectReason" rows="4" placeholder="Nhập lý do..."
          required></textarea>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Huỷ</button>
        <button type="submit" class="btn btn-danger">Xác nhận</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal: XOÁ BÀI -->
<div id="deleteModal" class="modal fade" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="deleteForm" method="POST" class="modal-content">
      <div class="modal-header bg-dark text-white">
        <h5 class="modal-title"><i class="bi bi-trash me-2"></i>Xoá bài viết</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="alert alert-warning mb-3">
          <i class="bi bi-exclamation-triangle me-2"></i>
          Hành động này sẽ xoá bài và dữ liệu liên quan (sections, media). Không thể khôi phục.
        </div>
        <div class="mb-2"><strong id="deleteTitle" class="text-danger"></strong></div>
        <textarea class="form-control" name="reason" id="deleteReason" rows="4" placeholder="Nhập lý do xoá..."
          required></textarea>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Huỷ</button>
        <button type="submit" class="btn btn-danger">Xoá ngay</button>
      </div>
    </form>
  </div>
</div>

<script>
  // Bootstrap modal helpers
  let rejectModal = new bootstrap.Modal(document.getElementById('rejectModal'));
  let deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));

  function toggleDetail(id) {
    const row = document.getElementById('detail-' + id);
    document.querySelectorAll('.detail-row').forEach(el => {
      if (el !== row) el.style.display = 'none';
    });
    if (row) row.style.display = (row.style.display === 'table-row') ? 'none' : 'table-row';
  }

  function openRejectModal(articleId) {
    document.getElementById('rejectForm').action =
      "<?= BASE_URL ?>/admin.php?route=article&action=reviewAction&id=" + articleId + "&do=rejected";
    document.getElementById('rejectReason').value = '';
    rejectModal.show();
  }

  function openDeleteModal(articleId, title) {
    document.getElementById('deleteForm').action =
      "<?= BASE_URL ?>/admin.php?route=article&action=deleteAction&id=" + articleId;
    document.getElementById('deleteReason').value = '';
    document.getElementById('deleteTitle').innerText = 'Bài: ' + (title || ('#' + articleId));
    deleteModal.show();
  }
</script>

<style>
  .card-header h4 {
    font-weight: 700;
  }

  .dcontent img {
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, .06);
  }

  .section-media-grid figure,
  .article-media-grid figure {
    margin: 0;
  }

  .detail-box {
    padding: 16px;
    background: #fff;
    border: 1px solid #eee;
    border-radius: 10px;
  }

  .article-section h6 {
    font-weight: 700;
    margin-bottom: .5rem;
  }

  .table> :not(caption)>*>* {
    padding: .9rem .75rem;
  }

  /* ====== Pagination hiện đại & to ====== */
  .pager-controls .pagination {
    --pg-gap: .5rem;
    --pg-size: 44px;
    /* chiều cao tối thiểu (>= 44px chuẩn mobile) */
    --pg-radius: 999px;
    /* pill */
    --pg-fz: 1rem;
    /* cỡ chữ */
    --pg-pad-x: .9rem;
    /* padding ngang cho số */
    --pg-bg: #ffffff;
    --pg-border: #e5e7eb;
    --pg-text: #0f172a;
    --pg-hover-bg: #f1f5f9;
    --pg-hover-text: #0b1220;
    --pg-active-bg: linear-gradient(180deg, #2563eb, #1d4ed8);
    --pg-active-shadow: 0 6px 18px rgba(37, 99, 235, .25);
    --pg-disabled-bg: #f3f4f6;
    --pg-disabled-text: #9aa3af;

    margin: 0;
    gap: var(--pg-gap);
  }

  .pager-controls .page-item {
    display: inline-flex;
  }

  .pager-controls .page-link {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: var(--pg-size);
    height: var(--pg-size);
    padding: 0 var(--pg-pad-x);
    border-radius: var(--pg-radius);
    border: 1px solid var(--pg-border);
    background: var(--pg-bg);
    color: var(--pg-text);
    font-size: var(--pg-fz);
    font-weight: 600;
    box-shadow: 0 1px 2px rgba(0, 0, 0, .04);
    transition: all .18s ease;
  }

  .pager-controls .page-link:hover {
    background: var(--pg-hover-bg);
    color: var(--pg-hover-text);
    transform: translateY(-1px);
    box-shadow: 0 4px 10px rgba(2, 6, 23, .08);
  }

  .pager-controls .page-item.active .page-link {
    background: var(--pg-active-bg);
    color: #fff;
    border-color: transparent;
    box-shadow: var(--pg-active-shadow);
    transform: translateY(-1px);
  }

  .pager-controls .page-item.disabled .page-link {
    background: var(--pg-disabled-bg);
    color: var(--pg-disabled-text);
    border-color: var(--pg-border);
    cursor: not-allowed;
    box-shadow: none;
    transform: none;
  }

  /* Dấu … trông đẹp hơn */
  .pager-controls .page-item.disabled .page-link span,
  .pager-controls .page-item.disabled .page-link {
    font-weight: 600;
  }

  /* Nút đầu/cuối nhỏ hơn một chút nhưng vẫn 44px */
  .pager-controls .page-item:first-child .page-link,
  .pager-controls .page-item:last-child .page-link {
    font-weight: 700;
  }

  /* Thanh thông tin + bố cục */
  .pager-bar {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: .9rem;
    padding: .8rem 1rem;
    border-top: 1px solid #eef0f3;
    background: #fafbfc;
    border-radius: 0 0 14px 14px;
  }

  .pager-meta {
    display: flex;
    align-items: center;
    gap: .45rem;
    color: #475569;
    font-size: .975rem;
  }

  .pager-meta i {
    font-size: 1.05rem;
    color: #2563eb;
  }

  .pager-meta strong {
    color: #0f172a;
    font-weight: 800;
  }

  /* Mobile: căn giữa */
  @media (max-width:576px) {
    .pager-controls {
      width: 100%;
      display: flex;
      justify-content: center;
    }

    .pager-meta {
      width: 100%;
      justify-content: center;
    }

    .pager-controls .pagination {
      --pg-size: 46px;
      --pg-fz: 1.05rem;
    }
  }
</style>


<?php include __DIR__ . '/../layout/footer.php'; ?>