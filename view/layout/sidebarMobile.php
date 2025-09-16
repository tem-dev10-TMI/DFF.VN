<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sidebar Mobile</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
  /* Sidebar */
  .sidebar {
    width: 250px;
    background: #1e1e1e;
    color: #fff;
    height: 100vh;
    position: fixed;
    top: 0;
    left: 0;
    transition: all 0.3s ease;
    overflow-y: auto;
    z-index: 1000;
  }

  /* Logo */
  .sidebar .logo {
    text-align: center;
    padding: 15px;
    border-bottom: 1px solid #333;
  }
  .sidebar .logo img {
    max-width: 120px;
    height: auto;
  }

  /* Menu */
  .sidebar ul {
    list-style: none;
    margin: 0;
    padding: 10px 0;
  }
  .sidebar ul li a {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 18px;
    text-decoration: none;
    color: #ddd;
    font-size: 15px;
    transition: background 0.2s, color 0.2s;
  }
  .sidebar ul li a:hover {
    background: #333;
    color: #fff;
  }
  .sidebar ul li i {
    width: 20px;
    text-align: center;
    font-size: 16px;
  }

  /* Section title */
  .sidebar ul li.section-title {
    font-size: 12px;
    text-transform: uppercase;
    color: #999;
    padding: 8px 18px;
    margin-top: 10px;
  }

  /* Toggle button */
  #toggleMenu {
    display: none; /* ẩn mặc định */
    position: fixed;
    top: 12px;
    left: 12px;
    background: #1e1e1e;
    color: #fff;
    border: none;
    padding: 8px 12px;
    font-size: 18px;
    cursor: pointer;
    z-index: 1100;
    border-radius: 4px;
  }

  /* Responsive: Mobile */
  @media (max-width: 768px) {
    .sidebar {
      left: -250px;
    }
    .sidebar.active {
      left: 0;
    }
    #toggleMenu {
      display: block; /* hiện toggle trên mobile */
    }
  }
</style>

  
</head>
<body>

<!-- Toggle button -->
<button id="toggleMenu"><i class="fas fa-bars"></i></button>

<!-- Sidebar -->
<nav class="sidebar">
  <div class="logo">
    <img src="public/img/logo.png" alt="Logo">
  </div>
  <ul>
    <li><a href="#"><i class="fas fa-chart-line"></i> Thị trường</a></li>
    <li><a href="#"><i class="fab fa-bitcoin"></i> Crypto</a></li>
    <li><a href="#"><i class="fas fa-industry"></i> 360° Doanh nghiệp</a></li>
    <li><a href="#"><i class="fas fa-wallet"></i> Tài chính</a></li>
    <li><a href="#"><i class="fas fa-building"></i> Nhà đất</a></li>
    <li><a href="#"><i class="fas fa-globe"></i> Quốc tế</a></li>
    <li><a href="#"><i class="fas fa-comments"></i> Thảo luận</a></li>

    <li class="section-title">Về</li>
    <li><a href="#"><i class="fas fa-users"></i> Về chúng tôi</a></li>
    <li><a href="#"><i class="fas fa-file-contract"></i> Chính sách nội dung</a></li>
    <li><a href="#"><i class="fas fa-shield-alt"></i> Chính sách riêng tư</a></li>
    <li><a href="#"><i class="fas fa-ad"></i> Quảng cáo</a></li>
  </ul>
</nav>


<script>
  const toggleBtn = document.getElementById("toggleMenu");
  const sidebar = document.querySelector(".sidebar");

  toggleBtn.addEventListener("click", () => {
    sidebar.classList.toggle("active");
  });
</script>
</body>
</html>