<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gợi ý theo dõi</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f6f8fa;
      margin: 0;
      padding: 20px;
      display: flex;
      justify-content: flex-end; /* căn sang bên phải */
    }

    .follow-box {
      background: #fff;
      border: 1px solid #ddd;
      border-radius: 8px;
      padding: 12px;
      width: 280px;
    }

    .follow-box h4 {
      font-size: 16px;
      font-weight: 600;
      margin-bottom: 12px;
      color: #124889;
      border-left: 4px solid #124889;
      padding-left: 8px;
    }

    .follow-list {
      list-style: none;
      margin: 0;
      padding: 0;
    }

    .follow-item {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 8px 0;
      border-bottom: 1px solid #f0f0f0;
    }

    .follow-item:last-child {
      border-bottom: none;
    }

    .follow-item img {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      object-fit: cover;
      margin-right: 10px;
    }

    .follow-info {
      flex: 1;
      margin-left: 8px;
    }

    .follow-info .name {
      font-size: 14px;
      font-weight: 600;
      display: block;
    }

    .follow-info .count {
      font-size: 12px;
      color: #777;
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
  </style>
</head>
<body>

  <div class="follow-box">
    <h4>Gợi ý theo dõi</h4>
    <ul class="follow-list">
      <li class="follow-item">
        <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="avatar">
        <div class="follow-info">
          <span class="name">Huy Binh</span>
          <span class="count">Có 59 lượt theo dõi</span>
        </div>
        <button class="btn-follow">Theo dõi</button>
      </li>
      <li class="follow-item">
        <img src="https://randomuser.me/api/portraits/men/45.jpg" alt="avatar">
        <div class="follow-info">
          <span class="name">Cao Lê Minh Long</span>
          <span class="count">Có 51 lượt theo dõi</span>
        </div>
        <button class="btn-follow">Theo dõi</button>
      </li>
      <li class="follow-item">
        <img src="https://randomuser.me/api/portraits/women/65.jpg" alt="avatar">
        <div class="follow-info">
          <span class="name">Lê Minh</span>
          <span class="count">Có 46 lượt theo dõi</span>
        </div>
        <button class="btn-follow">Theo dõi</button>
      </li>
      <li class="follow-item">
        <img src="https://randomuser.me/api/portraits/women/12.jpg" alt="avatar">
        <div class="follow-info">
          <span class="name">Quỳnh Vy</span>
          <span class="count">Có 42 lượt theo dõi</span>
        </div>
        <button class="btn-follow">Theo dõi</button>
      </li>
      <li class="follow-item">
        <img src="https://randomuser.me/api/portraits/men/18.jpg" alt="avatar">
        <div class="follow-info">
          <span class="name">Hoàng Anh</span>
          <span class="count">Có 39 lượt theo dõi</span>
        </div>
        <button class="btn-follow">Theo dõi</button>
      </li>
    </ul>
  </div>

</body>
</html>
