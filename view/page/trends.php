<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Replica: Tin tức - 360° Doanh nghiệp & Vĩ mô</title>
  <style>
    :root {
      --bg: #0f1720;
      --card: #0b1116;
      --muted: #9aa6b2;
      --accent: #00bcd4;
      --red: #e02b2b;
      --text: #e6f0f5;
      font-family: Inter, 'Segoe UI', Roboto, Arial, sans-serif;
    }

    html, body {
      height: 100%;
      margin: 0;
      background: linear-gradient(180deg, #081018 0%, #0b141a 100%);
      color: var(--text);
    }

    .search-bar {
      width: 100%;
      max-width: 1200px;
      margin: 20px auto 0 auto;
      padding: 12px 18px;
      box-sizing: border-box;
      display: flex;
      align-items: center;
      gap: 8px;
      background: var(--card);
      border-radius: 10px;
      border: 1px solid rgba(255, 255, 255, 0.05);
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.4);
    }

    .search-bar input {
      flex: 1;
      background: transparent;
      border: none;
      outline: none;
      font-size: 16px;
      color: var(--text);
    }

    .search-icon {
      font-size: 18px;
      color: var(--muted);
    }

    .wrap {
      max-width: 1200px;
      margin: 28px auto;
      padding: 18px;
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px;
    }

    .card {
      background: var(--card);
      border-radius: 10px;
      padding: 18px;
      box-shadow: 0 2px 0 rgba(0, 0, 0, 0.45) inset, 0 8px 30px rgba(2, 6, 11, 0.6);
      border: 1px solid rgba(255, 255, 255, 0.03);
      min-height: 420px;
      display: flex;
      flex-direction: column;
      gap: 12px;
    }

    .card .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .card h3 {
      margin: 0;
      font-size: 20px;
      color: var(--accent);
    }

    .card .small {
      font-size: 12px;
      color: var(--muted);
    }

    .list {
      margin-top: 6px;
      display: flex;
      flex-direction: column;
      gap: 12px;
    }

    .item {
      display: flex;
      gap: 12px;
      align-items: flex-start;
      padding: 8px;
      border-radius: 8px;
    }

    .bullet {
      width: 10px;
      height: 10px;
      border-radius: 50%;
      background: var(--red);
      margin-top: 6px;
      flex: 0 0 12px;
    }

    .content {
      flex: 1;
    }

    .title {
      color: #dbefff;
      font-size: 15px;
      margin: 0 0 6px 0;
      line-height: 1.2;
    }

    .meta {
      display: flex;
      gap: 12px;
      align-items: center;
      font-size: 12px;
      color: var(--muted);
    }

    .meta .dot {
      width: 4px;
      height: 4px;
      background: rgba(255, 255, 255, 0.06);
      border-radius: 50%;
    }

    .footer {
      margin-top: auto;
      display: flex;
      justify-content: flex-end;
    }

    .link {
      color: var(--muted);
      font-size: 13px;
      text-decoration: none;
    }

    @media (max-width: 900px) {
      .wrap {
        grid-template-columns: 1fr;
        max-width: 820px;
      }
    }
  </style>
</head>
<body>
  <!-- Thanh tìm kiếm xu hướng -->
  <header class="search-bar">
    <input type="text" placeholder="Tìm kiếm xu hướng" />
    <span class="search-icon">🔍</span>
  </header>

  <!-- Nội dung chính -->
  <div class="wrap">
    <section class="card">
      <div class="header">
        <h3>#360° Doanh nghiệp</h3>
        <div class="small">Top tìm kiếm nhiều nhất</div>
      </div>

      <div class="list">
        <div class="item">
          <div class="bullet"></div>
          <div class="content">
            <h4 class="title">Chứng khoán DSC bị phạt hơn 700 triệu đồng</h4>
            <div class="meta"><span>15 phút trước</span><span class="dot"></span><span>Đức Anh</span></div>
          </div>
        </div>

        <div class="item">
          <div class="bullet"></div>
          <div class="content">
            <h4 class="title">SSI sắp chi 2.000 tỷ đồng chia cổ tức tiền mặt cho cổ đông</h4>
            <div class="meta"><span>49 phút trước</span><span class="dot"></span><span>Tâm Đan</span></div>
          </div>
        </div>

        <div class="item">
          <div class="bullet"></div>
          <div class="content">
            <h4 class="title">PV Power muốn tăng vốn lên 30.600 tỷ đồng</h4>
            <div class="meta"><span>15 giờ trước</span><span class="dot"></span><span>Ngọc Lan</span></div>
          </div>
        </div>

        <div class="item">
          <div class="bullet"></div>
          <div class="content">
            <h4 class="title">TVS sắp tăng vốn vượt 2.000 tỷ đồng</h4>
            <div class="meta"><span>18 giờ trước</span><span class="dot"></span><span>Châu Anh</span></div>
          </div>
        </div>

        <div class="item">
          <div class="bullet"></div>
          <div class="content">
            <h4 class="title">VinFast dự kiến tiêu hao 400 - 600 triệu USD mỗi quý để mở rộng ra toàn cầu</h4>
            <div class="meta"><span>22 giờ trước</span><span class="dot"></span><span>Việt Anh</span></div>
          </div>
        </div>
      </div>

      <div class="footer"><a class="link" href="#">Xem thêm</a></div>
    </section>

    <section class="card">
      <div class="header">
        <h3>#Vĩ mô</h3>
        <div class="small">Top chia sẻ nhiều nhất</div>
      </div>

      <div class="list">
        <div class="item">
          <div class="bullet"></div>
          <div class="content">
            <h4 class="title">Ninh Bình: Cụ bà 77 tuổi sống đơn thân "ngã ngửa" với hóa đơn tiền điện hơn 12 triệu đồng trong 1 tháng</h4>
            <div class="meta"><span>1 giờ trước</span><span class="dot"></span><span>Phùng Thanh Khoa</span></div>
          </div>
        </div>

        <div class="item">
          <div class="bullet"></div>
          <div class="content">
            <h4 class="title">Thủ tướng: Giao quân đội làm cầu Phong Châu giúp tiết kiệm 300 tỷ đồng</h4>
            <div class="meta"><span>15 giờ trước</span><span class="dot"></span><span>Phương Nhi</span></div>
          </div>
        </div>

        <div class="item">
          <div class="bullet"></div>
          <div class="content">
            <h4 class="title">Giải phóng mặt bằng 3.160 ha cho dự án đường sắt Lào Cai – Hà Nội – Hải Phòng</h4>
            <div class="meta"><span>16 giờ trước</span><span class="dot"></span><span>Phúc Nguyên</span></div>
          </div>
        </div>

        <div class="item">
          <div class="bullet"></div>
          <div class="content">
            <h4 class="title">EVN đề xuất xử phạt lắp điện mặt trời áp mái không đăng ký</h4>
            <div class="meta"><span>17 giờ trước</span><span class="dot"></span><span>Thanh Giang</span></div>
          </div>
        </div>

        <div class="item">
          <div class="bullet"></div>
          <div class="content">
            <h4 class="title">CFO WiGroup: Chênh lệch lãi suất VND – USD thu hẹp, áp lực tỷ giá sẽ hạ nhiệt trong một năm tới</h4>
            <div class="meta"><span>1 ngày</span><span class="dot"></span><span>Thành Công</span></div>
          </div>
        </div>
      </div>

      <div class="footer"><a class="link" href="#">Xem thêm</a></div>
    </section>
  </div>
</body>
</html>
