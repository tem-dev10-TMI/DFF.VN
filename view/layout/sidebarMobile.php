<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sidebar Mobile Demo</title>

  <!-- Font Awesome cho icon -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background: #f9f9f9;
    }

    /* Sidebar */
    .sidebar-mobile {
      width: 250px;
      background: #fff;
      border-right: 1px solid #ddd;
      height: 100vh;
      position: fixed;
      top: 0;
      left: 0;
      overflow-y: auto;
      transition: transform 0.3s ease;
      z-index: 1000;
    }

    .sidebar-mobile ul {
      list-style: none;
      margin: 0;
      padding: 0;
    }

    .sidebar-mobile ul li a {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 12px 16px;
      text-decoration: none;
      color: #333;
      font-size: 15px;
    }

    .sidebar-mobile ul li a:hover {
      background: #f0f0f0;
    }

    .sidebar-mobile ul li.active a {
      background: #ffecec;
      color: #c00;
      font-weight: bold;
    }

    /* Icon */
    .icon {
      width: 20px;
      height: 20px;
      display: inline-block;
      text-align: center;
      vertical-align: middle;
      flex-shrink: 0;
    }

    .icon img.topic-thumb {
      width: 20px;
      height: 20px;
      object-fit: contain;
      display: inline-block;
      vertical-align: middle;
    }

    .icon .fallback {
      display: none;
      font-size: 18px;
      line-height: 20px;
      vertical-align: middle;
      color: inherit;
    }

    /* Tiêu đề section */
    .section-title {
      font-size: 12px;
      font-weight: bold;
      color: #777;
      margin-top: 10px;
      padding: 6px 16px;
      text-transform: uppercase;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .sidebar-mobile {
        transform: translateX(-100%);
      }
      .sidebar-mobile.open {
        transform: translateX(0);
      }
      .menu-toggle {
        display: block;
      }
    }

    /* Nút toggle */
    .menu-toggle {
      display: none;
      position: fixed;
      top: 10px;
      left: 10px;
      background: #c00;
      color: #fff;
      border: none;
      padding: 10px 14px;
      border-radius: 6px;
      cursor: pointer;
      z-index: 1001;
    }

    .content {
      margin-left: 250px;
      padding: 20px;
    }

    @media (max-width: 768px) {
      .content {
        margin-left: 0;
      }
    }
  </style>
</head>
<body>

  <!-- Nút toggle mobile -->
  <button class="menu-toggle" onclick="toggleSidebar()">
    <i class="fas fa-bars"></i> Menu
  </button>

  <!-- Sidebar -->
  <nav class="sidebar-mobile" id="sidebar">
    <ul>
      <li class="active"><a href="/"><span class="icon"><i class="fas fa-home"></i></span> <span>Trang chủ</span></a></li>
      <li><a href="/latest"><span class="icon"><i class="far fa-clock"></i></span> <span>Mới nhất</span></a></li>
      <li><a href="/trends"><span class="icon"><i class="fas fa-fire"></i></span> <span>Xu hướng</span></a></li>
      <li><a href="/comments"><span class="icon"><i class="far fa-comment-dots"></i></span> <span>Bình luận</span></a></li>

      <li class="section-title">Chủ đề</li>
      <li><a href="/topic/vi-mo"><span class="icon"><img class="topic-thumb" src="public/img/topic-vimo.svg" alt="Vĩ mô" onerror="this.style.display='none'; this.nextElementSibling.style.display='inline-block';"><i class="fas fa-chart-line fallback"></i></span> <span>Vĩ mô</span></a></li>
      <li><a href="/topic/thi-truong"><span class="icon"><img class="topic-thumb" src="public/img/topic-thitruong.svg" alt="Thị trường" onerror="this.style.display='none'; this.nextElementSibling.style.display='inline-block';"><i class="fas fa-chart-bar fallback"></i></span> <span>Thị trường</span></a></li>
      <li><a href="/topic/crypto"><span class="icon"><i class="fab fa-bitcoin"></i></span> <span>Crypto</span></a></li>
      <li><a href="/topic/360"><span class="icon"><i class="fas fa-industry"></i></span> <span>360° Doanh nghiệp</span></a></li>
      <li><a href="/topic/tai-chinh"><span class="icon"><i class="fas fa-wallet"></i></span> <span>Tài chính</span></a></li>
      <li><a href="/topic/nha-dat"><span class="icon"><i class="fas fa-building"></i></span> <span>Nhà đất</span></a></li>
      <li><a href="/topic/quoc-te"><span class="icon"><i class="fas fa-globe"></i></span> <span>Quốc tế</span></a></li>
      <li><a href="/topic/thao-luan"><span class="icon"><i class="fas fa-comments"></i></span> <span>Thảo luận</span></a></li>

      <li class="section-title">Về</li>
      <li><a href="/about"><span class="icon"><i class="fas fa-users"></i></span> <span>Về chúng tôi</span></a></li>
      <li><a href="/policy-content"><span class="icon"><i class="fas fa-file-contract"></i></span> <span>Chính sách nội dung</span></a></li>
      <li><a href="/privacy"><span class="icon"><i class="fas fa-shield-alt"></i></span> <span>Chính sách riêng tư</span></a></li>
      <li><a href="/ads"><span class="icon"><i class="fas fa-ad"></i></span> <span>Quảng cáo</span></a></li>
    </ul>
  </nav>

  <!-- Nội dung -->
  <div class="content">
    <h1>Demo Sidebar Mobile</h1>
    <p>Bấm <b>Menu</b> trên màn hình nhỏ để mở sidebar.</p>
  </div>

  <script>
    function toggleSidebar() {
      document.getElementById("sidebar").classList.toggle("open");
    }

    // Active item khi click
    document.addEventListener("DOMContentLoaded", function() {
      const lis = document.querySelectorAll(".sidebar-mobile ul li");
      lis.forEach(li => {
        const a = li.querySelector("a");
        if (a) {
          a.addEventListener("click", function() {
            lis.forEach(el => el.classList.remove("active"));
            li.classList.add("active");
          });
        }
      });
    });
  </script>
</body>
</html>
