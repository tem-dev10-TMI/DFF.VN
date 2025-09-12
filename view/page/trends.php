<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Trang tin - mẫu có chat</title>
  <link rel="stylesheet" href="style.css">
  <style>
    body { background:#f6f8fa; font-family: Arial, Helvetica, sans-serif; padding: 20px; }
    .chat-row { display:flex; gap:8px; max-width:780px; margin:0 auto 18px; }
    .chat-input { flex:1; padding:10px 14px; border:1px solid #ccc; border-radius:20px; }
    .chat-send { background:#124889; color:white; border:none; padding:0 16px; border-radius:20px; cursor:pointer; }
    .chat-send:hover { background:#0e3466; }
    .main { max-width: 1200px; margin: 0 auto; display:flex; gap:20px; }
    .col { flex:1; min-width:300px; }
    .panel { background:white; border:1px solid #e6e8eb; border-radius:6px; padding:14px; }
    .panel-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:10px; }
    .panel-title { font-weight:700; color:#124889; font-size:18px; }
    .panel-list { list-style:none; margin:0; padding:0; }
    .item { display:flex; gap:10px; padding:10px 0; border-bottom:1px dashed #eee; align-items:flex-start; }
    .item:last-child { border-bottom:0; }
    .dot { width:8px; height:8px; background:#ec2323; border-radius:50%; margin-top:6px; flex:0 0 8px; }
    .item-content { flex:1; }
    .item-title { font-size:14px; color:#333; margin-bottom:6px; }
    .meta { font-size:12px; color:#888; display:flex; gap:10px; align-items:center; }
    @media (max-width:900px){ .main{flex-direction:column} }
  </style>
</head>
<body>
  <div class="chat-row">
    <input class="chat-input" placeholder="Nhập tin nhắn..." aria-label="chat" />
    <button class="chat-send">Gửi</button>
  </div>

  <div class="main">
    <div class="col">
      <div class="panel">
        <div class="panel-header">
          <div class="panel-title">#360° Doanh nghiệp</div>
          <div class="panel-meta">Top tìm kiếm nhiều nhất</div>
        </div>
        <ul class="panel-list">
          <li class="item">
            <div class="dot"></div>
            <div class="item-content">
              <div class="item-title"><a href="#">PV Power muốn tăng vốn lên 30.600 tỷ đồng</a></div>
              <div class="meta"><span>14 giờ trước</span> <span>Ngọc Lan</span></div>
            </div>
          </li>
          <li class="item">
            <div class="dot"></div>
            <div class="item-content">
              <div class="item-title"><a href="#">TVS sắp tăng vốn vượt 2.000 tỷ đồng</a></div>
              <div class="meta"><span>17 giờ trước</span> <span>Châu Anit</span></div>
            </div>
          </li>
          <li class="item">
            <div class="dot"></div>
            <div class="item-content">
              <div class="item-title"><a href="#">VinFast dự kiến tiêu hao 400 - 600 triệu USD mỗi quý để mở rộng</a></div>
              <div class="meta"><span>21 giờ trước</span> <span>Việt Anh</span></div>
            </div>
          </li>
          <li class="item">
            <div class="dot"></div>
            <div class="item-content">
              <div class="item-title"><a href="#">BSR muốn chia thưởng cổ phiếu 'khủng', đổi tên thành Lọc hóa dầu Việt Nam</a></div>
              <div class="meta"><span>1 ngày</span> <span>Ngọc Lan</span></div>
            </div>
          </li>
        </ul>
      </div>
    </div>

    <div class="col">
      <div class="panel">
        <div class="panel-header">
          <div class="panel-title">#Vi mô</div>
          <div class="panel-meta">Top chia sẻ nhiều nhất</div>
        </div>
        <ul class="panel-list">
          <li class="item">
            <div class="dot"></div>
            <div class="item-content">
              <div class="item-title"><a href="#">Ninh Bình: Cụ bà 77 tuổi sống đơn thân 'ngã ngửa' với hóa đơn tiền điện hơn 12 triệu đồng trong 1 tháng</a></div>
              <div class="meta"><span>3 phút trước</span> <span>Phùng Khanh</span></div>
            </div>
          </li>
          <li class="item">
            <div class="dot"></div>
            <div class="item-content">
              <div class="item-title"><a href="#">Thủ tướng: Giao quân đội làm cầu Phong Châu giúp tiết kiệm 300 tỷ đồng</a></div>
              <div class="meta"><span>2 giờ trước</span> <span>Phương Nhi</span></div>
            </div>
          </li>
          <li class="item">
            <div class="dot"></div>
            <div class="item-content">
              <div class="item-title"><a href="#">Giải phóng mặt bằng 3.160 ha cho dự án đường sắt Lào Cai - Hà Nội - Hải Phòng</a></div>
              <div class="meta"><span>15 giờ trước</span> <span>Phúc Nguyên</span></div>
            </div>
          </li>
          <li class="item">
            <div class="dot"></div>
            <div class="item-content">
              <div class="item-title"><a href="#">EVN đề xuất xử phạt lắp điện mặt trời áp mái không đăng ký</a></div>
              <div class="meta"><span>16 giờ trước</span> <span>Thanh Giang</span></div>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </div>

</body>
</html>
