<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Top Doanh nghiệp</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      background: #f6f8fa;
    }

    footer {
      background: #fff;
      padding: 20px;
      border-top: 1px solid #ddd;
    }

    .top-company {
      max-width: 1200px;
      margin: 0 auto;
      position: relative;
    }

    .top-company h4 {
      font-size: 18px;
      font-weight: bold;
      color: #124889;
      margin-bottom: 15px;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .company-list {
      display: flex;
      gap: 15px;
      overflow-x: auto;
      scroll-behavior: smooth;
      scrollbar-width: none; /* Firefox */
    }

    .company-list::-webkit-scrollbar {
      display: none; /* Ẩn scrollbar */
    }

    .company-card {
      flex: 0 0 250px;
      background: #fff;
      border: 1px solid #ddd;
      border-radius: 8px;
      padding: 15px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }

    .company-header {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 10px;
    }

    .company-header img {
      width: 40px;
      height: 40px;
      object-fit: contain;
    }

    .company-name {
      font-size: 14px;
      font-weight: bold;
      color: #124889;
    }

    .company-type {
      font-size: 12px;
      color: #777;
    }

    .company-desc {
      font-size: 13px;
      margin-bottom: 10px;
    }

    .company-footer {
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .followers {
      font-size: 13px;
      color: #333;
    }

    .btn-follow {
      background: #fff;
      border: 1px solid #124889;
      border-radius: 20px;
      padding: 4px 12px;
      font-size: 13px;
      cursor: pointer;
      transition: 0.2s;
    }

    .btn-follow:hover {
      background: #124889;
      color: #fff;
    }

    /* Nút điều hướng */
    .nav-btn {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      background: #124889;
      color: #fff;
      border: none;
      border-radius: 50%;
      width: 35px;
      height: 35px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 18px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }

    .nav-btn:hover {
      background: #0e3466;
    }

    .nav-left { left: -15px; }
    .nav-right { right: -15px; }
  </style>
</head>
<body>

  <footer>
    <div class="top-company">
      <h4>Top doanh nghiệp 
        <span style="font-size:13px; font-weight:normal; color:#777;">Được tìm kiếm nhiều nhất</span>
      </h4>

      <!-- Nút điều hướng -->
      <button class="nav-btn nav-left" onclick="scrollLeft()">&#10094;</button>
      <button class="nav-btn nav-right" onclick="scrollRight()">&#10095;</button>

      <div class="company-list" id="companyList">
        <div class="company-card">
          <div class="company-header">
            <img src="https://upload.wikimedia.org/wikipedia/commons/0/0c/Vinhomes_logo.svg" alt="VHM">
            <div>
              <div class="company-name">VHM</div>
              <div class="company-type">Doanh nghiệp</div>
            </div>
          </div>
          <div class="company-desc">Công ty cổ phần Vinhomes</div>
          <div class="company-footer">
            <div class="followers">Theo dõi: <b>560</b></div>
            <button class="btn-follow">Theo dõi</button>
          </div>
        </div>

        <div class="company-card">
          <div class="company-header">
            <img src="https://upload.wikimedia.org/wikipedia/commons/2/28/Logo_VIB.png" alt="VIB">
            <div>
              <div class="company-name">VIB</div>
              <div class="company-type">Doanh nghiệp</div>
            </div>
          </div>
          <div class="company-desc">Ngân hàng TMCP Quốc tế Việt Nam</div>
          <div class="company-footer">
            <div class="followers">Theo dõi: <b>547</b></div>
            <button class="btn-follow">Theo dõi</button>
          </div>
        </div>

        <div class="company-card">
          <div class="company-header">
            <img src="https://upload.wikimedia.org/wikipedia/commons/6/65/Vietcombank_logo.png" alt="VCB">
            <div>
              <div class="company-name">VCB</div>
              <div class="company-type">Doanh nghiệp</div>
            </div>
          </div>
          <div class="company-desc">Ngân hàng TMCP Ngoại thương Việt Nam</div>
          <div class="company-footer">
            <div class="followers">Theo dõi: <b>620</b></div>
            <button class="btn-follow">Theo dõi</button>
          </div>
        </div>

        <div class="company-card">
          <div class="company-header">
            <img src="https://upload.wikimedia.org/wikipedia/commons/0/09/VietinBank_logo.png" alt="CTG">
            <div>
              <div class="company-name">CTG</div>
              <div class="company-type">Doanh nghiệp</div>
            </div>
          </div>
          <div class="company-desc">Ngân hàng TMCP Công thương Việt Nam</div>
          <div class="company-footer">
            <div class="followers">Theo dõi: <b>489</b></div>
            <button class="btn-follow">Theo dõi</button>
          </div>
        </div>
      </div>
    </div>
  </footer>

  <script>
    const companyList = document.getElementById('companyList');

    function scrollLeft() {
      companyList.scrollBy({ left: -270, behavior: 'smooth' });
    }

    function scrollRight() {
      companyList.scrollBy({ left: 270, behavior: 'smooth' });
    }
  </script>

</body>
</html>
