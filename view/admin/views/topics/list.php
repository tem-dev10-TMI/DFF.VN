<?php include __DIR__ . '/../layout/header.php'; ?>

<?php
if (!function_exists('e')) {
  function e($s)
  {
    return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');
  }
}
/* Build URL giữ query và thay param mới */
function build_url(array $params): string
{
  $base = BASE_URL . '/admin.php';
  $q = array_merge($_GET, $params);
  return $base . '?' . http_build_query($q);
}
/* Pagination UI */
function render_pagination_ui(int $page, int $pages): void
{
  if ($pages <= 1)
    return;
  $prev = max(1, $page - 1);
  $next = min($pages, $page + 1);
  echo '<ul class="pagination modern justify-content-center flex-wrap">';
  $mk = function ($label, $p, $active = false, $disabled = false) {
    $cls = 'page-item';
    if ($active)
      $cls .= ' active';
    if ($disabled)
      $cls .= ' disabled';
    $url = e(build_url(['page' => $p]));
    echo '<li class="' . $cls . '"><a class="page-link" href="' . $url . '">' . $label . '</a></li>';
  };
  $mk('«', 1, false, $page == 1);
  $mk('‹', $prev, false, $page == 1);
  $start = max(1, $page - 2);
  $end = min($pages, $page + 2);
  if ($start > 1)
    echo '<li class="page-item disabled"><span class="page-link">…</span></li>';
  for ($i = $start; $i <= $end; $i++)
    $mk((string) $i, $i, $i == $page);
  if ($end < $pages)
    echo '<li class="page-item disabled"><span class="page-link">…</span></li>';
  $mk('›', $next, false, $page == $pages);
  $mk('»', $pages, false, $page == $pages);
  echo '</ul>';
}

/* Fallback nếu controller chưa set */
$page = $page ?? (int) ($_GET['page'] ?? 1);
$pages = $pages ?? 1;
$total = $total ?? (is_countable($topics ?? null) ? count($topics) : 0);
?>

<div class="card shadow-sm border-0">
  <div class="card-header bg-white d-flex justify-content-between align-items-center">
    <div class="d-flex align-items-center gap-2">
      <h5 class="mb-0">Danh sách Topics</h5>
      <span class="badge bg-light text-dark"><?= (int) $total ?> tổng</span>
    </div>
    <a href="<?= BASE_URL ?>/admin.php?route=topics&action=create" class="btn btn-success btn-sm">
      <i class="bi bi-plus-circle me-1"></i> Thêm mới
    </a>
  </div>

  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th style="width:80px">#</th>
            <th>Tên</th>
            <th>Slug</th>
            <th class="text-end" style="width:120px">Hành động</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($topics)):
            foreach ($topics as $t): ?>
              <tr>
                <td><?= e($t['id']) ?></td>
                <td><?= e($t['name']) ?></td>
                <td><span class="text-muted"><?= e($t['slug']) ?></span></td>
                <td class="text-end">
                  <a class="btn btn-sm btn-outline-primary"
                    href="<?= BASE_URL ?>/admin.php?route=topics&action=edit&id=<?= $t['id'] ?>">
                    <i class="bi bi-pencil-square"></i>
                  </a>
                  <a class="btn btn-sm btn-outline-danger"
                    href="<?= BASE_URL ?>/admin.php?route=topics&action=delete&id=<?= $t['id'] ?>"
                    onclick="return confirm('Bạn có chắc muốn xóa topic này?');">
                    <i class="bi bi-trash"></i>
                  </a>
                </td>
              </tr>
            <?php endforeach; else: ?>
            <tr>
              <td colspan="4" class="text-center text-muted py-4">Chưa có topic nào</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <!-- Phân trang -->
    <div class="py-3">
      <?php render_pagination_ui((int) $page, (int) $pages); ?>
    </div>
  </div>
</div>

<style>
  /* Pagination hiện đại & to (giống phần Users bạn đang dùng) */
  .pagination.modern {
    --pg-size: 46px;
    --pg-radius: 999px;
    --pg-fz: 1rem;
    --pg-pad-x: .95rem;
    --pg-border: #e5e7eb;
    --pg-bg: #fff;
    --pg-text: #0f172a;
    --pg-hover: #f1f5f9;
    --pg-active: #2563eb;
    --pg-active2: #1d4ed8;
    gap: .55rem;
    margin: 0;
  }

  .pagination.modern .page-item {
    display: inline-flex;
  }

  .pagination.modern .page-link {
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
    font-weight: 600;
    box-shadow: 0 1px 2px rgba(0, 0, 0, .04);
    transition: all .18s ease;
  }

  .pagination.modern .page-link:hover {
    background: var(--pg-hover);
    transform: translateY(-1px);
  }

  .pagination.modern .page-item.active .page-link {
    background: linear-gradient(180deg, var(--pg-active), var(--pg-active2));
    color: #fff;
    border-color: transparent;
    box-shadow: 0 6px 18px rgba(37, 99, 235, .25);
  }

  .pagination.modern .page-item.disabled .page-link {
    background: #f3f4f6;
    color: #9aa3af;
    cursor: not-allowed;
    transform: none;
  }

  /* Bảng gọn */
  .table> :not(caption)>*>* {
    vertical-align: middle;
  }
</style>

<?php include __DIR__ . '/../layout/footer.php'; ?>