<main class="main-content">
<style>
    /* Stock market data đè lên header nhưng ở vị trí dưới header */
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
      <input class="form-control" type="search" placeholder="Tìm kiếm xu hướng">
      <span class="input-group-append">
        <button><i class="fa fa-search"></i></button>
      </span>
    </div>

    <!-- Danh sách xu hướng -->
    <div class="list-trend">
      <!-- Box 1 -->
      <div class="box-trends">
        <h5>
          <a href="#">#360° Doanh nghiệp</a>
          <span class="c-note"><i class="fas fa-chart-line"></i> Top tìm kiếm nhiều nhất</span>
        </h5>
        <ul>
          <li><a href="#">HDBS tính tăng vốn </a> <span class="date"><i class="far fa-calendar-alt"></i> 1 giờ trước</span> <span class="name"><i class="far fa-user"></i> Hoàng Giang</span></li>
          <li><a href="#">Chứng khoán DSC bị phạt hơn 700 triệu đồng</a> <span class="date"><i class="far fa-calendar-alt"></i> 4 giờ trước</span> <span class="name"><i class="far fa-user"></i> Đức Anh</span></li>
          <li><a href="#">SSI sắp chi 2.000 tỷ đồng chia cổ tức</a> <span class="date"><i class="far fa-calendar-alt"></i> 5 giờ trước</span> <span class="name"><i class="far fa-user"></i> Tâm Đan</span></li>
          <li><a href="#">PV Power muốn tăng vốn lên 30.600 tỷ đồng</a> <span class="date"><i class="far fa-calendar-alt"></i> 20 giờ trước</span> <span class="name"><i class="far fa-user"></i> Ngọc Lan</span></li>
        </ul>
        <a class="more" href="#">Xem thêm</a>
      </div>

      <!-- Box 2 -->
      <div class="box-trends">
        <h5>
          <a href="#">#Vĩ mô</a>
          <span class="c-note"><i class="fas fa-chart-line"></i> Top chia sẻ nhiều nhất</span>
        </h5>
        <ul>
          <li><a href="#">Công an khuyến cáo người dân cảnh giác sau sự cố lộ dữ liệu CIC</a> <span class="date"><i class="far fa-calendar-alt"></i> 2 giờ trước</span> <span class="name"><i class="far fa-user"></i> Kiến thức Kinh tế</span></li>
          <li><a href="#">Ninh Bình: Cụ bà 77 tuổi với hóa đơn tiền điện 12 triệu</a> <span class="date"><i class="far fa-calendar-alt"></i> 5 giờ trước</span> <span class="name"><i class="far fa-user"></i> Phùng Thanh Khoa</span></li>
          <li><a href="#">Thủ tướng: Giao quân đội làm cầu Phong Châu tiết kiệm 300 tỷ</a> <span class="date"><i class="far fa-calendar-alt"></i> 20 giờ trước</span> <span class="name"><i class="far fa-user"></i> Phương Nhi</span></li>
          <li><a href="#">Giải phóng mặt bằng 3.160 ha cho dự án đường sắt</a> <span class="date"><i class="far fa-calendar-alt"></i> 21 giờ trước</span> <span class="name"><i class="far fa-user"></i> Phúc Nguyên</span></li>
        </ul>
        <a class="more" href="#">Xem thêm</a>
      </div>
    </div>
  </div>

</main>