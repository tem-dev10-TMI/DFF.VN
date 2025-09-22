@ -1,136 +0,0 @@
<?php
require_once __DIR__ . '/../../config/db.php';
$db = new connect();

// Lấy keyword nếu có
$q = isset($_GET['q']) ? trim($_GET['q']) : '';

// Lấy topics
$sqlTopics = "SELECT * FROM topics ORDER BY display_order ASC, created_at DESC";
$topics = $db->pdo->query($sqlTopics)->fetchAll(PDO::FETCH_ASSOC);

// Lấy bài viết theo topic, có tìm kiếm nếu $q != ''
$articlesByTopic = [];
foreach ($topics as $tp) {
    $tid = (int)$tp['id'];
    if ($q !== '') {
        $stmt = $db->pdo->prepare("
            SELECT a.id, a.title ,a.slug, a.created_at, u.name as author_name 
            FROM articles a
            JOIN users u ON a.author_id = u.id
            WHERE a.topic_id = ? 
              AND (a.title LIKE ? OR a.content LIKE ?)
            ORDER BY a.created_at DESC
            LIMIT 5
        ");
        $stmt->execute([$tid, "%$q%", "%$q%"]);
    } else {
        $stmt = $db->pdo->prepare("
            SELECT a.id, a.title, a.slug,  a.created_at, u.name as author_name 
            FROM articles a
            JOIN users u ON a.author_id = u.id
            WHERE a.topic_id = ?
            ORDER BY a.created_at DESC
            LIMIT 5
        ");
        $stmt->execute([$tid]);
    }
    $articlesByTopic[$tid] = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<main class="main-content">
<style>
    .top-stock {
        position: fixed !important;
        top: 66px !important;
        z-index: 10001 !important;
        width: 100% !important;
        height: 50px !important;
    }
    .trends-container { max-width:1200px; margin:0 auto; }
    .input-group { display:flex; max-width:600px; margin-bottom:20px; align-items:stretch; }
    .form-control { flex:1; padding:12px 15px; border:1px solid #ccc; border-radius:4px 0 0 4px; font-size:14px; height:auto; }
    .input-group-append button { border:1px solid #ccc; border-left:0; background:#fff; padding:0; border-radius:0 4px 4px 0; cursor:pointer; display:flex; align-items:center; justify-content:center; min-width:50px; height:100%; }
    .input-group-append button i { font-size:16px; color:#666; line-height:1; }
    .list-trend { display:grid; grid-template-columns:repeat(auto-fit, minmax(300px,1fr)); gap:20px; }
    .box-trends { background:#fff; border:1px solid #e6e8eb; border-radius:6px; padding:14px; }
    .box-trends h5 { font-size:16px; margin-bottom:10px; color:#124889; display:flex; justify-content:space-between; align-items:center; }
    .box-trends h5 a { text-decoration:none; color:#124889; }
    .box-trends ul { list-style:none; padding:0; margin:0; }
    .box-trends li { padding:8px 0; border-bottom:1px dashed #eee; }
    .box-trends li:last-child { border-bottom:0; }
    .box-trends li a { font-size:14px; text-decoration:none; color:#333; }
    .box-trends li a:hover { text-decoration:underline; color:#124889; }
    .date, .name { font-size:12px; color:#666; margin-right:10px; }
    .more { display:inline-block; margin-top:10px; font-size:13px; color:#124889; text-decoration:none; }
    .more:hover { text-decoration:underline; }
</style>

<div class="trends-container">
  <div class="input-group">
      <input id="trendSearch" class="form-control" type="search" placeholder="Tìm kiếm xu hướng" value="<?= htmlspecialchars($q) ?>">
      <span class="input-group-append">
        <button id="trendSearchBtn"><i class="fa fa-search"></i></button>
      </span>
  </div>

  <div class="list-trend">
      <?php if (!empty($topics)): ?>
        <?php foreach ($topics as $tp): ?>
          <?php $tid = (int)($tp['id'] ?? 0); $tname = $tp['name'] ?? ''; ?>
          <div class="box-trends">
            <h5>
              <a href="#">#<?= htmlspecialchars($tname) ?></a>
              <span class="c-note"><i class="fas fa-chart-line"></i> Top nội dung mới</span>
            </h5>
            <ul>
              <?php $list = $articlesByTopic[$tid] ?? []; ?>
              <?php if (!empty($list)): ?>
                <?php foreach ($list as $a): ?>
                  <li>
                    <a href="details_blog?id=<?= $a['id'] ?>"><?= htmlspecialchars($a['title']) ?></a>
                    <span class="date"><i class="far fa-calendar-alt"></i> <?= htmlspecialchars(date('d/m/Y H:i', strtotime($a['created_at'] ?? 'now'))) ?></span>
                    <span class="name"><i class="far fa-user"></i> <?= htmlspecialchars($a['author_name'] ?? '') ?></span>
                  </li>
                <?php endforeach; ?>
              <?php else: ?>
                <li>Chưa có dữ liệu.</li>
              <?php endif; ?>
            </ul>
            <a class="more" href="#">Xem thêm</a>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="box-trends"><ul><li>Chưa có chủ đề.</li></ul></div>
      <?php endif; ?>
  </div>
</div>

<script>
  const input = document.getElementById('trendSearch');
  const button = document.getElementById('trendSearchBtn');

  function doSearch() {
    let keyword = input.value.trim();
    let url = new URL(window.location.href);
    if (keyword) {
      url.searchParams.set('q', keyword);
    } else {
      url.searchParams.delete('q');
    }
    window.location.href = url.toString();
  }

  button.addEventListener('click', function(e) {
    e.preventDefault();
    doSearch();
  });

  input.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
      e.preventDefault();
      doSearch();
    }
  });
</script>
</main>