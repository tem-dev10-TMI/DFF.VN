<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sidebar Mobile</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    <style>
  /* --- Sidebar Mobile --- */
  .sidebar-mobile {
    width: 240px;
    background: #f8f9fa;
    border-right: 1px solid #ddd;
    height: 100vh;
    overflow-y: auto;
    position: fixed;
    top: 0;
    left: 0;
    transition: all 0.3s ease;
  }

  .sidebar-mobile ul {
    list-style: none;
    margin: 0;
    padding: 0;
  }

  .sidebar-mobile ul li a {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 10px 14px;
    text-decoration: none;
    color: #333;
    font-size: 14px;
  }

  /* Active */
  .sidebar-mobile ul li.active a,
  .sidebar-mobile ul li.active i,
  .sidebar-mobile ul li.active svg {
    color: red;
    font-weight: 600;
  }
  .sidebar-mobile ul li.active i,
  .sidebar-mobile ul li.active svg {
    border: 2px solid red;
    border-radius: 6px;
    padding: 2px;
    display: inline-block;
  }

  /* Icon mặc định */
  .sidebar-mobile ul li i,
  .sidebar-mobile ul li svg {
    border: none;
    padding: 0;
    width: 18px;
    height: 18px;
    font-size: 18px;
    color: inherit;
  }

  .sidebar-mobile ul li img.topic-thumb {
    width: 18px;
    height: 18px;
    object-fit: contain;
  }

  /* Section Title */
  .sidebar-mobile ul li.section-title {
    font-size: 12px;
    font-weight: bold;
    color: #777;
    margin-top: 10px;
    padding: 8px 14px;
  }

  /* Responsive: dạng off-canvas (ẩn hoàn toàn dưới 480px) */
  @media (max-width: 480px) {
    .sidebar-mobile {
      left: -240px;
    }
    .sidebar-mobile.active {
      left: 0;
    }
  }

  /* Toggle button */
  #toggleMenu {
    position: fixed;
    top: 10px;
    left: 10px;
    background: #fff;
    border: 1px solid #ddd;
    padding: 6px 10px;
    font-size: 18px;
    cursor: pointer;
    z-index: 1001;
  }
</style>

  </style>
</head>
<body>

<!-- Toggle button -->
<button id="toggleMenu" aria-label="Mở menu">☰</button>

<!-- START: Sidebar -->
<nav class="sidebar-mobile" aria-label="Thanh điều hướng">
  <ul class="menu">
    <li><a href="/"><i class="fas fa-home"></i> <span>Trang chủ</span></a></li>
    <li><a href="/latest"><i class="far fa-clock"></i> <span>Mới nhất</span></a></li>
    <li><a href="/trends"><i class="fas fa-fire"></i> <span>Xu hướng</span></a></li>
    <li><a href="/comments"><i class="far fa-comment-dots"></i> <span>Bình luận</span></a></li>

    <li class="section-title">CHỦ ĐỀ</li>
    <li><a href="/topic/vi-mo"><img class="topic-thumb" src="public/img/topic-vimo.svg" alt=""> <span>Vĩ mô</span></a></li>
    <li><a href="/topic/thi-truong"><img class="topic-thumb" src="public/img/topic-thitruong.svg" alt=""> <span>Thị trường</span></a></li>
    <li><a href="/topic/crypto"><i class="fab fa-bitcoin"></i> <span>Crypto</span></a></li>
    <li><a href="/topic/360"><i class="fas fa-industry"></i> <span>360° Doanh nghiệp</span></a></li>
    <li><a href="/topic/tai-chinh"><i class="fas fa-wallet"></i> <span>Tài chính</span></a></li>
    <li><a href="/topic/nha-dat"><i class="fas fa-building"></i> <span>Nhà đất</span></a></li>
    <li><a href="/topic/quoc-te"><i class="fas fa-globe"></i> <span>Quốc tế</span></a></li>
    <li><a href="/topic/thao-luan"><i class="fas fa-comments"></i> <span>Thảo luận</span></a></li>

    <li class="section-title">VỀ</li>
    <li><a href="/about"><i class="fas fa-users"></i> <span>Về chúng tôi</span></a></li>
    <li><a href="/policy-content"><i class="fas fa-file-contract"></i> <span>Chính sách nội dung</span></a></li>
    <li><a href="/privacy"><i class="fas fa-shield-alt"></i> <span>Chính sách riêng tư</span></a></li>
    <li><a href="/ads"><i class="fas fa-ad"></i> <span>Quảng cáo</span></a></li>
  </ul>
</nav>
<!-- END: Sidebar -->

<script>
  document.addEventListener("DOMContentLoaded", function() {
    const sidebar = document.querySelector(".sidebar-mobile");
    const toggleBtn = document.getElementById("toggleMenu");
    const allLis = document.querySelectorAll(".sidebar-mobile ul li");
    const currentPath = window.location.pathname;

    // Toggle sidebar cho mobile nhỏ
    toggleBtn.addEventListener("click", () => {
      sidebar.classList.toggle("active");
    });

    // Auto active theo URL
    allLis.forEach(li => {
      const a = li.querySelector("a");
      if (a) {
        if (currentPath.startsWith(a.getAttribute("href"))) {
          li.classList.add("active");
        }
        a.addEventListener("click", function() {
          allLis.forEach(item => item.classList.remove("active"));
          li.classList.add("active");
        });
      }
    });
  });
</script>

</body>
</html>
