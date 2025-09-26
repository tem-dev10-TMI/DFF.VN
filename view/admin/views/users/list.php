<?php
/** view/admin/views/users/list.php — FULL FILE **/

include __DIR__ . '/../layout/header.php';

/* ===== Helpers ===== */
if (!function_exists('e')) {
  function e($s)
  {
    return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');
  }
}

/** Build URL giữ lại query hiện tại và thay thế param mới */
function build_url(array $params): string
{
  $base = BASE_URL . '/admin.php';
  $q = array_merge($_GET, $params);
  return $base . '?' . http_build_query($q);
}

/** Pagination hiện đại (dựa vào $page, $pages) */
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

/* Lấy filters từ controller (fallback GET nếu chưa có) */
$filters = $filters ?? [
  'name' => trim($_GET['name'] ?? ''),
  'email' => trim($_GET['email'] ?? ''),
  'role' => trim($_GET['role'] ?? ''),
];
$page = $page ?? (int) ($_GET['page'] ?? 1);
$pages = $pages ?? 1;
$total = $total ?? (is_countable($users ?? null) ? count($users) : 0);
?>

<div class="card shadow-sm border-0">
  <div class="card-header bg-white d-flex justify-content-between align-items-center">
    <div class="d-flex align-items-center gap-2">
      <h5 class="mb-0">Danh sách người dùng</h5>
      <span class="badge bg-light text-dark"><?= (int) $total ?> kết quả</span>
    </div>
    <a href="<?= BASE_URL ?>/admin.php?route=users&action=create" class="btn btn-success">
      <i class="bi bi-plus-circle me-1"></i> Thêm mới
    </a>
  </div>

  <div class="card-body pb-0">
    <!-- Bộ lọc -->
    <form class="filter-modern" method="get" action="<?= BASE_URL ?>/admin.php">
      <input type="hidden" name="route" value="users">
      <input type="hidden" name="action" value="admin"><!-- đổi nếu action khác -->

      <div class="fm-row">
        <!-- Tên -->
        <div class="fm-field">
          <label class="fm-label">Tên</label>
          <div class="fm-input">
            <i class="bi bi-person"></i>
            <input name="name" placeholder="Nhập tên..." value="<?= e($filters['name'] ?? '') ?>">
          </div>
        </div>

        <!-- Email -->
        <div class="fm-field">
          <label class="fm-label">Email</label>
          <div class="fm-input">
            <i class="bi bi-envelope"></i>
            <input type="email" name="email" placeholder="name@domain.com" value="<?= e($filters['email'] ?? '') ?>">
          </div>
        </div>

        <!-- Role -->
        <div class="fm-field">
          <label class="fm-label">Role</label>
          <div class="btn-group fm-segment" role="group" aria-label="Role">
            <?php $r = $filters['role'] ?? ''; ?>
            <input type="radio" class="btn-check" name="role" id="role-all" value="" <?= $r === '' ? 'checked' : '' ?>>
            <label class="btn" for="role-all"><i class="bi bi-grid"></i> Tất cả</label>

            <input type="radio" class="btn-check" name="role" id="role-user" value="user" <?= $r === 'user' ? 'checked' : '' ?>>
            <label class="btn" for="role-user"><i class="bi bi-person-badge"></i> User</label>

            <input type="radio" class="btn-check" name="role" id="role-biz" value="businessmen" <?= $r === 'businessmen' ? 'checked' : '' ?>>
            <label class="btn" for="role-biz"><i class="bi bi-briefcase"></i> Businessmen</label>

            <input type="radio" class="btn-check" name="role" id="role-admin" value="admin" <?= $r === 'admin' ? 'checked' : '' ?>>
            <label class="btn" for="role-admin"><i class="bi bi-shield-lock"></i> Admin</label>
          </div>
        </div>

        <!-- Actions -->
        <div class="fm-actions">
          <button class="btn btn-primary fm-submit"><i class="bi bi-search me-1"></i>Lọc</button>
          <a class="btn btn-outline-secondary fm-reset" href="<?= BASE_URL ?>/admin.php?route=users&action=admin">
            <i class="bi bi-x-circle me-1"></i>Xoá lọc
          </a>
        </div>
      </div>

      <!-- Chips filter đang áp dụng -->
      <?php $hasFilter = ($filters['name'] ?? '') || ($filters['email'] ?? '') || ($filters['role'] ?? ''); ?>
      <?php if ($hasFilter): ?>
        <div class="fm-chips">
          <?php if (!empty($filters['name'])): ?>
            <a class="chip" href="<?= e(build_url(['name' => '', 'page' => 1])) ?>">
              <i class="bi bi-person"></i> Tên: “<?= e($filters['name']) ?>”
              <span class="x">&times;</span>
            </a>
          <?php endif; ?>
          <?php if (!empty($filters['email'])): ?>
            <a class="chip" href="<?= e(build_url(['email' => '', 'page' => 1])) ?>">
              <i class="bi bi-envelope"></i> Email: “<?= e($filters['email']) ?>”
              <span class="x">&times;</span>
            </a>
          <?php endif; ?>
          <?php if (!empty($filters['role'])): ?>
            <a class="chip" href="<?= e(build_url(['role' => '', 'page' => 1])) ?>">
              <i class="bi bi-tag"></i> Role: <?= e($filters['role']) ?>
              <span class="x">&times;</span>
            </a>
          <?php endif; ?>
        </div>
      <?php endif; ?>
    </form>


    <?php $hasFilter = ($filters['name'] ?? '') || ($filters['email'] ?? '') || ($filters['role'] ?? ''); ?>
    <?php if ($hasFilter): ?>
      <div class="mt-2 small text-muted">
        Đang lọc:
        <?php if (!empty($filters['name'])): ?>
          <span class="badge rounded-pill bg-light text-dark me-1">Tên: “<?= e($filters['name']) ?>”</span>
        <?php endif; ?>
        <?php if (!empty($filters['email'])): ?>
          <span class="badge rounded-pill bg-light text-dark me-1">Email: “<?= e($filters['email']) ?>”</span>
        <?php endif; ?>
        <?php if (!empty($filters['role'])): ?>
          <span class="badge rounded-pill bg-light text-dark me-1">Role: <?= e($filters['role']) ?></span>
        <?php endif; ?>
      </div>
    <?php endif; ?>
  </div>

  <div class="card-body p-0 mt-3">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th style="width:72px">#</th>
            <th>Tên</th>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
            <th>Ngày tạo</th>
            <th class="text-end" style="width:110px;">Hành động</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($users)):
            foreach ($users as $u): ?>
              <tr>
                <td><?= e($u['id']) ?></td>
                <td><?= e($u['name']) ?></td>
                <td><?= e($u['username']) ?></td>
                <td><?= e($u['email']) ?></td>
                <td>
                  <?php if ($u['role'] == 'admin'): ?>
                    <span class="badge bg-danger">Admin</span>
                  <?php elseif ($u['role'] == 'businessmen'): ?>
                    <span class="badge bg-warning text-dark">Businessmen</span>
                  <?php else: ?>
                    <span class="badge bg-secondary">User</span>
                  <?php endif; ?>
                </td>
                <td><?= e(date('d/m/Y', strtotime($u['created_at']))) ?></td>
                <td class="text-end">
                  <a class="btn btn-sm btn-outline-primary"
                    href="<?= BASE_URL ?>/admin.php?route=users&action=edit&id=<?= e($u['id']) ?>">
                    <i class="bi bi-pencil-square"></i>
                  </a>
                  <a class="btn btn-sm btn-outline-danger"
                    href="<?= BASE_URL ?>/admin.php?route=users&action=delete&id=<?= e($u['id']) ?>"
                    onclick="return confirm('Bạn có chắc muốn xóa người dùng này?');">
                    <i class="bi bi-trash"></i>
                  </a>
                </td>
              </tr>
            <?php endforeach; else: ?>
            <tr>
              <td colspan="7" class="text-center text-muted py-4">Không có người dùng phù hợp</td>
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
  /* ===== Filter v1 ===== */
  .badge.bg-light.text-dark {
    font-size: 1.05em !important;
  }

  .filter-modern {
    background: #fff;
    border: 1px solid #eef0f3;
    border-radius: 14px;
    padding: 16px;
    margin-bottom: 10px;
    box-shadow: 0 4px 18px rgba(2, 6, 23, .04);
  }

  /* Lưới: 2 input + Role + Actions trên cùng 1 hàng (desktop) */
  .fm-row {
    display: grid;
    align-items: end;
    gap: 14px;
    grid-template-columns: 1fr 1fr minmax(300px, 1.2fr) auto;
  }

  /* Ô nhập có icon */
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
    padding: 11px 12px;
    min-height: 48px;
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

  /* Role: segmented-control gọn, cao ngang input */
  .fm-segment {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 6px;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    background: #f8fafc;
    padding: 6px;
    min-height: 48px;
  }

  .fm-segment .btn {
    border: 0;
    background: transparent;
    color: #1f2937;
    font-weight: 600;
    border-radius: 10px;
    padding: .44rem .66rem;
    display: flex;
    align-items: center;
    gap: .4rem;
    line-height: 1;
    font-size: .95rem;
  }

  .fm-segment .btn:hover {
    background: #eef2f7;
  }

  .fm-segment .btn-check:checked+.btn {
    background: linear-gradient(180deg, #2563eb, #1d4ed8);
    color: #fff;
    box-shadow: 0 6px 16px rgba(37, 99, 235, .25);
  }

  .fm-segment .btn i {
    font-size: .95rem;
  }

  .fm-segment:focus-within {
    border-color: #93c5fd;
    box-shadow: 0 0 0 4px rgba(59, 130, 246, .15);
  }

  /* Nút hành động */
  .fm-actions {
    display: flex;
    gap: 10px;
    align-items: stretch;
    justify-content: flex-end;
  }

  .fm-submit,
  .fm-reset {
    border-radius: 12px;
    padding: .6rem 1.1rem;
    font-weight: 700;
  }

  /* Chips đang lọc */
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
    padding: .38rem .75rem;
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

  /* Responsive */
  @media (max-width: 992px) {
    .fm-row {
      grid-template-columns: 1fr 1fr;
    }

    .fm-actions {
      grid-column: 1 / -1;
      justify-content: stretch;
    }

    .fm-submit,
    .fm-reset {
      width: 100%;
    }
  }

  @media (max-width: 576px) {
    .fm-row {
      grid-template-columns: 1fr;
    }
  }
</style>





<?php include __DIR__ . '/../layout/footer.php'; ?>