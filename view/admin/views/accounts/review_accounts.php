<?php include __DIR__ . '/../layout/header.php'; ?>

<?php
/* ================= Helpers ================= */
if (!function_exists('e')) {
  function e($s)
  {
    return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');
  }
}
function build_url(array $params): string
{
  $base = BASE_URL . '/admin.php';
  $q = array_merge($_GET, $params);
  return $base . '?' . http_build_query($q);
}
function render_pagination(int $current, int $totalPages, string $paramName): void
{
  if ($totalPages <= 1)
    return;
  $prev = max(1, $current - 1);
  $next = min($totalPages, $current + 1);

  echo '<ul class="pagination modern justify-content-center flex-wrap">';
  $mk = function ($label, $p, $active = false, $disabled = false) use ($paramName) {
    $cls = 'page-item';
    if ($active)
      $cls .= ' active';
    if ($disabled)
      $cls .= ' disabled';
    $url = e(build_url([$paramName => $p]));
    echo '<li class="' . $cls . '"><a class="page-link" href="' . $url . '">' . $label . '</a></li>';
  };
  $mk('«', 1, false, $current == 1);
  $mk('‹', $prev, false, $current == 1);

  $start = max(1, $current - 2);
  $end = min($totalPages, $current + 2);
  if ($start > 1)
    echo '<li class="page-item disabled"><span class="page-link">…</span></li>';
  for ($i = $start; $i <= $end; $i++)
    $mk((string) $i, $i, $i == $current);
  if ($end < $totalPages)
    echo '<li class="page-item disabled"><span class="page-link">…</span></li>';

  $mk('›', $next, false, $current == $totalPages);
  $mk('»', $totalPages, false, $current == $totalPages);
  echo '</ul>';
}

/* ================= Inputs ================= */
$perPage = 10;

// Trang danh sách chờ duyệt & đã duyệt
$pageP = max(1, (int) ($_GET['p1'] ?? 1));
$pageA = max(1, (int) ($_GET['p2'] ?? 1));
$offP = ($pageP - 1) * $perPage;
$offA = ($pageA - 1) * $perPage;

// Filters cho approved
$filters = [
  'name' => trim($_GET['name'] ?? ''),
  'email' => trim($_GET['email'] ?? ''),
  'from' => trim($_GET['from'] ?? ''), // YYYY-MM-DD
  'to' => trim($_GET['to'] ?? ''), // YYYY-MM-DD
];
// Chuẩn hoá ngày
foreach (['from', 'to'] as $k) {
  if ($filters[$k] && !preg_match('~^\d{4}-\d{2}-\d{2}$~', $filters[$k]))
    $filters[$k] = '';
}

/* ================= Query: Pending ================= */
$sqlP = "
  SELECT b.id, u.name, u.email, b.birth_year, b.nationality, b.education, b.position, b.created_at
  FROM businessmen b
  JOIN users u ON b.user_id = u.id
  WHERE b.status = 'pending'
  ORDER BY b.created_at DESC, b.id DESC
  LIMIT :lim OFFSET :off
";
$stP = $pdo->prepare($sqlP);
$stP->bindValue(':lim', $perPage, PDO::PARAM_INT);
$stP->bindValue(':off', $offP, PDO::PARAM_INT);
$stP->execute();
$pending = $stP->fetchAll(PDO::FETCH_ASSOC);

$pendingTotal = (int) $pdo->query("SELECT COUNT(*) FROM businessmen WHERE status = 'pending'")->fetchColumn();
$pendingPages = max(1, (int) ceil($pendingTotal / $perPage));

/* ================= Query: Approved (with filters) ================= */
$where = ["b.status = 'approved'"];
$params = [];

if ($filters['name'] !== '') {
  $where[] = "u.name LIKE :name";
  $params[':name'] = '%' . $filters['name'] . '%';
}
if ($filters['email'] !== '') {
  $where[] = "u.email LIKE :email";
  $params[':email'] = '%' . $filters['email'] . '%';
}
if ($filters['from'] !== '') {
  $where[] = "DATE(b.updated_at) >= :dfrom";
  $params[':dfrom'] = $filters['from'];
}
if ($filters['to'] !== '') {
  $where[] = "DATE(b.updated_at) <= :dto";
  $params[':dto'] = $filters['to'];
}
$whereSql = $where ? ('WHERE ' . implode(' AND ', $where)) : '';

$sqlA = "
  SELECT b.id, u.name, u.email, b.birth_year, b.nationality, b.education, b.position, b.updated_at
  FROM businessmen b
  JOIN users u ON b.user_id = u.id
  $whereSql
  ORDER BY b.updated_at DESC, b.id DESC
  LIMIT :lim OFFSET :off
";
$stA = $pdo->prepare($sqlA);
foreach ($params as $k => $v)
  $stA->bindValue($k, $v);
$stA->bindValue(':lim', $perPage, PDO::PARAM_INT);
$stA->bindValue(':off', $offA, PDO::PARAM_INT);
$stA->execute();
$approved = $stA->fetchAll(PDO::FETCH_ASSOC);

$sqlCountA = "SELECT COUNT(*)
              FROM businessmen b
              JOIN users u ON b.user_id = u.id
              $whereSql";
$stC = $pdo->prepare($sqlCountA);
foreach ($params as $k => $v)
  $stC->bindValue($k, $v);
$stC->execute();
$approvedTotal = (int) $stC->fetchColumn();
$approvedPages = max(1, (int) ceil($approvedTotal / $perPage));

?>

<div class="container-xxl mt-2">
  <h3 class="mb-4">Quản lý tài khoản doanh nhân</h3>

  <!-- ========== PENDING ========== -->
  <div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between">
      <h5 class="mb-0"><i class="bi bi-hourglass-split me-2"></i> Tài khoản chờ duyệt</h5>
    </div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table align-middle mb-0">
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
              <th class="text-end">Hành động</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($pending)):
              foreach ($pending as $acc): ?>
                <tr>
                  <td><?= (int) $acc['id'] ?></td>
                  <td><?= e($acc['name']) ?></td>
                  <td><?= e($acc['email']) ?></td>
                  <td><?= e($acc['birth_year']) ?></td>
                  <td><?= e($acc['nationality']) ?></td>
                  <td><?= e($acc['education']) ?></td>
                  <td><?= e($acc['position']) ?></td>
                  <td><?= e($acc['created_at']) ?></td>
                  <td class="text-end text-nowrap">
                    <div class="d-inline-flex align-items-center gap-2 flex-nowrap">
                      <a href="<?= BASE_URL ?>/admin.php?route=accounts&action=approve&id=<?= (int) $acc['id'] ?>"
                        class="btn btn-sm btn-success">Duyệt</a>
                      <a href="<?= BASE_URL ?>/admin.php?route=accounts&action=reject&id=<?= (int) $acc['id'] ?>"
                        class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc muốn từ chối yêu cầu này?');">Từ
                        chối</a>
                    </div>
                  </td>
                </tr>
              <?php endforeach; else: ?>
              <tr>
                <td colspan="9" class="text-center text-muted py-4">Không có tài khoản nào chờ duyệt</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
    <div class="pager-bar">
      <div class="pager-meta">
        <i class="bi bi-list-check"></i>
        <span>Tổng</span> <strong><?= (int) $pendingTotal ?></strong> <span>bản ghi •</span>
        <span><?= (int) $pendingPages ?> trang</span>
      </div>
      <div class="pager-controls">
        <?php render_pagination($pageP, $pendingPages, 'p1'); ?>
      </div>
    </div>
  </div>

  <!-- ========== APPROVED + FILTER ========== -->
  <div class="card shadow-sm border-0">
    <div class="card-header bg-success text-white d-flex align-items-center justify-content-between">
      <h5 class="mb-0"><i class="bi bi-check2-all me-2"></i> Tài khoản doanh nhân đã duyệt</h5>
    </div>

    <div class="card-body pb-0">
      <!-- Filter modern -->
      <form class="filter-modern v2" method="get" action="<?= BASE_URL ?>/admin.php">
        <input type="hidden" name="route" value="<?= e($_GET['route'] ?? 'accounts') ?>">
        <input type="hidden" name="action" value="<?= e($_GET['action'] ?? 'index') ?>">
        <input type="hidden" name="p2" value="1"><!-- reset về trang 1 khi lọc -->

        <div class="fm-row">
          <div class="fm-field">
            <label class="fm-label">Tên</label>
            <div class="fm-input">
              <i class="bi bi-person"></i>
              <input name="name" placeholder="Nhập tên..." value="<?= e($filters['name']) ?>">
            </div>
          </div>

          <div class="fm-field">
            <label class="fm-label">Email</label>
            <div class="fm-input">
              <i class="bi bi-envelope"></i>
              <input type="email" name="email" placeholder="name@domain.com" value="<?= e($filters['email']) ?>">
            </div>
          </div>

          <div class="fm-field">
            <label class="fm-label">Từ ngày</label>
            <div class="fm-input">
              <i class="bi bi-calendar-event"></i>
              <input type="date" name="from" value="<?= e($filters['from']) ?>">
            </div>
          </div>

          <div class="fm-field">
            <label class="fm-label">Đến ngày</label>
            <div class="fm-input">
              <i class="bi bi-calendar2-event"></i>
              <input type="date" name="to" value="<?= e($filters['to']) ?>">
            </div>
          </div>

          <div class="fm-actions">
            <button class="btn btn-primary fm-submit">
              <i class="bi bi-search me-1"></i> Lọc
            </button>
            <a class="btn btn-outline-secondary fm-reset" href="<?= BASE_URL ?>/admin.php?route=accounts&action=index">
              <i class="bi bi-x-circle me-1"></i> Xoá lọc
            </a>
          </div>
        </div>

        <?php $hasFilter = ($filters['name'] || $filters['email'] || $filters['from'] || $filters['to']); ?>
        <?php if ($hasFilter): ?>
          <div class="fm-chips">
            <?php if ($filters['name']): ?>
              <a class="chip" href="<?= e(build_url(['name' => '', 'p2' => 1])) ?>">
                <i class="bi bi-person"></i> “<?= e($filters['name']) ?>” <span class="x">&times;</span>
              </a>
            <?php endif; ?>
            <?php if ($filters['email']): ?>
              <a class="chip" href="<?= e(build_url(['email' => '', 'p2' => 1])) ?>">
                <i class="bi bi-envelope"></i> “<?= e($filters['email']) ?>” <span class="x">&times;</span>
              </a>
            <?php endif; ?>
            <?php if ($filters['from']): ?>
              <a class="chip" href="<?= e(build_url(['from' => '', 'p2' => 1])) ?>">
                <i class="bi bi-calendar-event"></i> Từ: <?= e($filters['from']) ?> <span class="x">&times;</span>
              </a>
            <?php endif; ?>
            <?php if ($filters['to']): ?>
              <a class="chip" href="<?= e(build_url(['to' => '', 'p2' => 1])) ?>">
                <i class="bi bi-calendar2-event"></i> Đến: <?= e($filters['to']) ?> <span class="x">&times;</span>
              </a>
            <?php endif; ?>
          </div>
        <?php endif; ?>
      </form>
    </div>

    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table align-middle mb-0">
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
            <?php if (!empty($approved)):
              foreach ($approved as $acc): ?>
                <tr>
                  <td><?= (int) $acc['id'] ?></td>
                  <td><?= e($acc['name']) ?></td>
                  <td><?= e($acc['email']) ?></td>
                  <td><?= e($acc['birth_year']) ?></td>
                  <td><?= e($acc['nationality']) ?></td>
                  <td><?= e($acc['education']) ?></td>
                  <td><?= e($acc['position']) ?></td>
                  <td><?= e($acc['updated_at']) ?></td>
                </tr>
              <?php endforeach; else: ?>
              <tr>
                <td colspan="8" class="text-center text-muted py-4">Chưa có tài khoản phù hợp</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

    <div class="pager-bar">
      <div class="pager-meta">
        <i class="bi bi-clipboard-check"></i>
        <span>Tổng</span> <strong><?= (int) $approvedTotal ?></strong> <span>bản ghi •</span>
        <span><?= (int) $approvedPages ?> trang</span>
      </div>
      <div class="pager-controls">
        <?php render_pagination($pageA, $approvedPages, 'p2'); ?>
      </div>
    </div>
  </div>
</div>

<style>
  /* Filter modern v2 (đồng bộ UI trước) */
  .filter-modern.v2 {
    background: #fff;
    border: 1px solid #eef0f3;
    border-radius: 14px;
    padding: 16px;
    margin: 12px 12px 0;
    box-shadow: 0 2px 10px rgba(2, 6, 23, .03);
  }

  .fm-row {
    display: grid;
    grid-template-columns: repeat(4, 1fr) auto;
    gap: 14px;
    align-items: end;
  }

  .fm-field {
    display: flex;
    flex-direction: column;
  }

  .fm-label {
    font-weight: 700;
    font-size: .92rem;
    color: #0f172a;
    margin-bottom: 6px;
  }

  .fm-input {
    display: flex;
    align-items: center;
    gap: 10px;
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 10px 12px;
  }

  .fm-input:focus-within {
    border-color: #93c5fd;
    box-shadow: 0 0 0 4px rgba(59, 130, 246, .15);
  }

  .fm-input i {
    color: #64748b;
    font-size: 1.05rem;
  }

  .fm-input input {
    border: 0;
    background: transparent;
    outline: none;
    width: 100%;
    font-size: 1rem;
    color: #0f172a;
  }

  .fm-actions {
    display: flex;
    gap: 10px;
    align-items: center;
    justify-content: flex-end;
  }

  .fm-submit {
    padding: .6rem 1.1rem;
    border-radius: 12px;
    font-weight: 700;
  }

  .fm-reset {
    padding: .6rem .9rem;
    border-radius: 12px;
  }

  .fm-chips {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 12px;
  }

  .chip {
    display: inline-flex;
    align-items: center;
    gap: .4rem;
    background: #f1f5f9;
    color: #0f172a;
    border: 1px solid #e5e7eb;
    border-radius: 999px;
    padding: .35rem .7rem;
    text-decoration: none;
    font-weight: 600;
  }

  .chip:hover {
    background: #e2e8f0;
  }

  .chip .x {
    margin-left: .25rem;
    font-weight: 800;
    color: #334155;
  }

  @media (max-width: 992px) {
    .fm-row {
      grid-template-columns: 1fr 1fr;
      grid-auto-rows: auto;
    }
  }

  @media (max-width: 576px) {
    .fm-row {
      grid-template-columns: 1fr;
    }

    .fm-actions {
      justify-content: stretch;
    }

    .fm-submit,
    .fm-reset {
      width: 100%;
    }
  }

  /* Pagination hiện đại */
  .pagination.modern {
    --pg-gap: .5rem;
    --pg-size: 44px;
    --pg-radius: 999px;
    --pg-fz: 1rem;
    --pg-pad-x: .9rem;
    --pg-bg: #fff;
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
    font-size: var(--pg-fz);
    font-weight: 600;
    box-shadow: 0 1px 2px rgba(0, 0, 0, .04);
    transition: all .18s ease;
  }

  .pagination.modern .page-link:hover {
    background: var(--pg-hover-bg);
    color: var(--pg-hover-text);
    transform: translateY(-1px);
    box-shadow: 0 4px 10px rgba(2, 6, 23, .08);
  }

  .pagination.modern .page-item.active .page-link {
    background: var(--pg-active-bg);
    color: #fff;
    border-color: transparent;
    box-shadow: var(--pg-active-shadow);
    transform: translateY(-1px);
  }

  .pagination.modern .page-item.disabled .page-link {
    background: var(--pg-disabled-bg);
    color: var(--pg-disabled-text);
    border-color: var(--pg-border);
    cursor: not-allowed;
    box-shadow: none;
    transform: none;
  }

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

  .table> :not(caption)>*>* {
    padding: .9rem .75rem;
  }
</style>

<?php include __DIR__ . '/../layout/footer.php'; ?>