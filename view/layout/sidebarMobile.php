<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Sidebar - Fix theo ảnh</title>

  <!-- Font Awesome (icon) -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

  <style>
    :root{
      --sidebar-w: 240px;
      --sidebar-collapsed-w: 64px;
      --bg: #0f1112;            /* nền sidebar */
      --item-bg-hover: rgba(255,255,255,0.02);
      --text: #dcdad6;          /* chữ */
      --muted: #9b9b9b;         /* chữ nhỏ / title */
      --sep: rgba(255,255,255,0.03);
      --accent: #e65b4f;
    }

    /* body demo */
    body{
      margin:0;
      font-family: "Helvetica Neue", Arial, sans-serif;
      background: #fff;
      min-height:100vh;
      color: var(--text);
    }

    /* ---------- SIDEBAR ---------- */
    .sidebar {
      width: var(--sidebar-w);
      background: var(--bg);
      color: var(--text);
      height: 100vh;
      position: fixed;
      left: 0;
      top: 0;
      overflow-y: auto;
      -webkit-overflow-scrolling: touch;
      box-shadow: 2px 0 0 0 rgba(0,0,0,0.6) inset;
      transition: width .22s ease, left .22s ease;
      z-index: 100;
    }

    /* Menu list */
    .sidebar .menu {
      list-style: none;
      margin: 0;
      padding: 8px 0;
    }

    .sidebar .menu li {
      margin: 0;
    }

    /* Link row (icon + text) */
    .sidebar .menu li a {
      display: flex;
      align-items: center;
      gap: 12px;
      text-decoration: none;
      color: var(--text);
      padding: 12px 18px;
      font-size: 15px;
      transition: background .12s ease, color .12s ease;
      border-bottom: 1px solid var(--sep);
    }

    /* Remove border for section-title and last spacer items */
    .sidebar .menu li.section-title,
    .sidebar .menu li.section-title + li a {
      border-top: none;
    }

    .sidebar .menu li a:hover {
      background: var(--item-bg-hover);
    }

    /* Icon box: circle or svg */
    .sidebar .menu li i.icon,
    .sidebar .menu li svg.icon {
      width: 36px;
      height: 36px;
      min-width: 36px;
      min-height: 36px;
      border-radius: 50%;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 16px;
      color: var(--text);
      background: rgba(255,255,255,0.02);
      border: 1px solid rgba(255,255,255,0.03);
    }

    /* Avatar images for topics */
    .topic-avatar {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      object-fit: cover;
      border: 1px solid rgba(255,255,255,0.04);
      box-shadow: 0 0 0 4px rgba(0,0,0,0.22) inset;
      background: #111;
    }

    /* Text area */
    .sidebar .menu li a .label {
      flex: 1;
      display: block;
      font-weight: 600;
      color: var(--text);
    }

    .sidebar .menu li a .label small {
      display:block;
      font-weight:400;
      color: var(--muted);
      font-size: 13px;
    }

    /* SECTION TITLE */
    .sidebar .menu li.section-title {
      text-align: center;
      color: var(--muted);
      font-size: 12px;
      letter-spacing: 1px;
      font-weight: 700;
      padding: 14px 18px;
      text-transform: uppercase;
      border-top: 1px solid var(--sep);
      border-bottom: 1px solid rgba(255,255,255,0.02);
      margin-top: 8px;
    }

    /* Active state */
    .sidebar .menu li.active > a {
      color: white;
      background: rgba(230,91,79,0.06);
    }
    .sidebar .menu li.active > a i.icon {
      border-color: var(--accent);
      color: var(--accent);
      box-shadow: 0 0 0 3px rgba(230,91,79,0.06);
    }

    /* ---------- RESPONSIVE: collapsed (icon-only) ---------- */
    @media (max-width: 480px) {
      .sidebar {
        width: var(--sidebar-collapsed-w);
      }

      /* hide labels */
      .sidebar .menu li a .label { display: none; }

      /* center icon vertically with increased top padding for first items */
      .sidebar .menu li a {
        justify-content: center;
        padding: 14px 0;
        border-bottom: none; /* neutral for icon-only */
      }

      /* show separators as small gaps (optional) */
      .sidebar .menu li + li { margin-top: 6px; }

      /* section title: show vertical text (mimic ảnh 1) */
      .sidebar .menu li.section-title {
        writing-mode: vertical-rl;
        transform: rotate(180deg);
        padding: 6px 0;
        font-size: 11px;
        letter-spacing: 2px;
      }

      /* make icon slightly smaller in collapsed */
      .sidebar .menu li i.icon,
      .topic-avatar {
        width: 28px;
        height: 28px;
        min-width: 28px;
        min-height: 28px;
        font-size: 14px;
      }
    }

    /* Small cosmetic: scrollbar thin on sidebar */
    .sidebar::-webkit-scrollbar{ width:8px; }
    .sidebar::-webkit-scrollbar-thumb{ background: rgba(255,255,255,0.03); border-radius:8px; }
  </style>
</head>
<body>

<nav class="sidebar" aria-label="Thanh điều hướng chính">
  <ul class="menu">
    <!-- top items -->
    <li><a href="/"><i class="fa-solid fa-house icon"></i><span class="label">Trang chủ</span></a></li>
    <li><a href="/latest"><i class="fa-regular fa-clock icon"></i><span class="label">Mới nhất</span></a></li>
    <li><a href="/trends"><i class="fa-solid fa-arrow-trend-up icon"></i><span class="label">Xu hướng</span></a></li>
    <li><a href="/comments"><i class="fa-regular fa-comment-dots icon"></i><span class="label">Bình luận</span></a></li>

    <!-- section -->
    <li class="section-title">Chủ đề</li>

    <!-- topic items (avatar + text) -->
    <li><a href="/topic/vi-mo">
        <img class="topic-avatar" src="public/img/topic-vimo.svg" alt="Vĩ mô">
        <span class="label">Vĩ mô</span>
      </a></li>

    <li><a href="/topic/thi-truong">
        <img class="topic-avatar" src="public/img/topic-thitruong.svg" alt="Thị trường">
        <span class="label">Thị trường</span>
      </a></li>

    <li><a href="/topic/crypto">
        <i class="fa-brands fa-bitcoin icon"></i>
        <span class="label">Crypto</span>
      </a></li>

    <li><a href="/topic/360">
        <img class="topic-avatar" src="public/img/topic-360.svg" alt="360 Doanh nghiệp">
        <span class="label">360° Doanh nghiệp</span>
      </a></li>

    <li><a href="/topic/tai-chinh">
        <img class="topic-avatar" src="public/img/topic-taichinh.svg" alt="Tài chính">
        <span class="label">Tài chính</span>
      </a></li>

    <li><a href="/topic/nha-dat">
        <img class="topic-avatar" src="public/img/topic-nhadat.svg" alt="Nhà đất">
        <span class="label">Nhà đất</span>
      </a></li>

    <li><a href="/topic/quoc-te">
        <img class="topic-avatar" src="public/img/topic-quoc-te.svg" alt="Quốc tế">
        <span class="label">Quốc tế</span>
      </a></li>

    <li><a href="/topic/thao-luan">
        <i class="fa-solid fa-comments icon"></i>
        <span class="label">Thảo luận</span>
      </a></li>

    <!-- ABOUT section -->
    <li class="section-title">Về</li>

    <li><a href="/about"><i class="fa-solid fa-users icon"></i><span class="label">Về chúng tôi</span></a></li>
    <li><a href="/policy-content"><i class="fa-regular fa-file-lines icon"></i><span class="label">Chính sách nội dung</span></a></li>
    <li><a href="/privacy"><i class="fa-solid fa-shield icon"></i><span class="label">Chính sách riêng tư</span></a></li>
    <li><a href="/ads"><i class="fa-solid fa-bullhorn icon"></i><span class="label">Quảng cáo</span></a></li>
  </ul>
</nav>

<!-- Demo content to the right so you can scroll test -->
<div style="margin-left:var(--sidebar-w); padding:24px;">
  <h1>Demo nội dung trang</h1>
  <p>Thay nội dung phía trên nếu muốn. Sidebar cố định bên trái.</p>
  <p>Resize trình duyệt xuống <strong>≤480px</strong> để xem chế độ collapsed (icon-only) giống ảnh 1.</p>
</div>

<script>
  // Auto active theo URL (so sánh startsWith để xử lý query string)
  document.addEventListener('DOMContentLoaded', function(){
    const current = window.location.pathname || '/';
    const links = document.querySelectorAll('.sidebar .menu li a');

    links.forEach(a => {
      const href = a.getAttribute('href') || '';
      // tránh active cho section-title (nó không có a)
      if(href && current.startsWith(href)){
        const li = a.closest('li');
        if(li) li.classList.add('active');
      }

      // click để set active tạm thời trên client
      a.addEventListener('click', function(){
        document.querySelectorAll('.sidebar .menu li').forEach(x => x.classList.remove('active'));
        const li = a.closest('li');
        if(li) li.classList.add('active');
      });
    });
  });
</script>

</body>
</html>
