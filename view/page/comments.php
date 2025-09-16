<style>
    body {
        background: #f5f5f5;
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
    }

    .container {
        display: flex;
        min-height: 100vh;
    }

    .sidebar {
        width: 240px;
        background: #fff;
        border-right: 1px solid #eee;
        padding: 24px 12px 0 12px;
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .sidebar nav ul {
        list-style: none;
        padding: 0;
        margin: 0 0 24px 0;
    }

    .sidebar nav ul li {
        padding: 12px 0 12px 8px;
        font-size: 17px;
        color: #333;
        display: flex;
        align-items: center;
        gap: 12px;
        border-radius: 8px;
        cursor: pointer;
        transition: background 0.15s;
    }

    .sidebar nav ul li.active,
    .sidebar nav ul li:hover {
        background: #f2f7ff;
        font-weight: bold;
        color: #007bff;
    }

    .sidebar .topics h4 {
        margin: 10px 0 10px 0;
        font-size: 13px;
        color: #888;
        letter-spacing: 1px;
    }

    .sidebar .topics ul {
        list-style: none;
        padding: 0;
        margin: 0 0 24px 0;
    }

    .sidebar .topics ul li {
        padding: 7px 0 7px 0;
        color: #444;
        font-size: 15px;
        display: flex;
        align-items: center;
        gap: 8px;
        border-radius: 6px;
        transition: background 0.15s;
    }

    .sidebar .topics ul li:hover {
        background: #f5f5f5;
    }

    .topic-avatar {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 4px;
    }

    .plus {
        margin-left: auto;
        color: #888;
        font-size: 18px;
        font-weight: bold;
    }

    .sidebar .sidebar-links ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .sidebar .sidebar-links ul li {
        padding: 7px 0 7px 0;
        color: #888;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .main-content {
        flex: 1;
        padding: 40px 32px;
        display: flex;
        justify-content: center;
        align-items: flex-start;
    }

    .main-feed {
        width: 100%;
        max-width: 700px;
        display: flex;
        flex-direction: column;
        gap: 32px;
    }

    .card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        padding: 24px 24px 12px 24px;
        margin-bottom: 12px;
    }

    .card-header {
        display: flex;
        align-items: flex-start;
        gap: 16px;
    }

    .card-img {
        max-width: 160px;
        border-radius: 8px;
        object-fit: cover;
    }

    .card-title {
        font-size: 19px;
        font-weight: bold;
        color: #222222;
        margin-top: 2px;
    }

    .card-meta {
        margin: 10px 0 0 0;
        color: #888;
        font-size: 12px;
        font-weight: normal;
        display: flex;
        gap: 6px;
        align-items: center;
    }

    .card-comment {
        margin: 12px 0 0 0;
        display: flex;
        align-items: flex-start;
        gap: 8px;
        font-size: 15px;
        color: #222;
    }

    .comment-avatar-sm {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        object-fit: cover;
    }

    .card-more {
        margin: 10px auto 0 auto;
        color: #444;
        font-size: 15px;
        display: flex;
        align-items: flex-start;
        gap: 6px;
        width: fit-content;
    }


    .card-more a {
        color: #222;
        flex-direction: column;
        font-weight: bold;
        transition: color 0.15s;
    }

    .card-more a:hover {
        color: #007bff;
    }

    .big-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        padding: 0 0 12px 0;
        margin-bottom: 12px;
    }

    .big-card-content {
        background: #068802;
        border-radius: 16px;
        width: 600px;
        height: 400px;
        margin: 0;

        display: flex;
        justify-content: center;
        align-items: center;

    }

    .big-card-title {
        color: #fff;
        font-size: 26px;
        font-weight: bold;
        line-height: 1.3;
    }

    .comment-container {
        width: 100%;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        padding: 24px;
    }

    .comment-container h2 {
        margin-top: 0;
        color: #333;
    }

    .comment-form {
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-bottom: 32px;
    }

    .comment-form input,
    .comment-form textarea {
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 15px;
    }

    .comment-form textarea {
        resize: vertical;
        min-height: 80px;
    }

    .comment-form button {
        align-self: flex-end;
        background: #007bff;
        color: #fff;
        border: none;
        padding: 10px 24px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 15px;
        transition: background 0.2s;
    }

    .comment-form button:hover {
        background: #0056b3;
    }

    .comments-list {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .comment-item {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 8px 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .comment-avatar img {
        width: 36px;
        height: 36px;
        border-radius: 50%;
    }

    .comment-content {
        flex: 1;
        line-height: 1.4;
        display: flex;
        flex-direction: column;
    }

    .comment-author {
        font-weight: bold;
        color: #124889;
        margin-right: 6px;
        font-size: 14px;
    }

    .comment-date {
        color: #888;
        font-size: 12px;
    }

    .comment-content p {
        margin: 4px 0 0 0;
        font-size: 14px;
        color: #222;
    }

    .sidebar-right {
        width: 340px;
        background: #fff;
        border-left: 1px solid #eee;
        padding: 24px 16px 0 16px;
    }

    .hot-news h3 {
        margin: 0 0 16px 0;
        font-size: 20px;
        font-weight: bold;
        letter-spacing: 1px;
    }

    .hot-news ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .hot-news ul li {
        padding: 10px 0;
        border-bottom: 1px solid #f1f1f1;
        font-size: 15px;
        color: #222;
        display: flex;
        align-items: center;
        gap: 8px;
        position: relative;
    }

    .hot-news ul li:last-child {
        border-bottom: none;
    }

    .news-thumb {
        width: 48px;
        height: 32px;
        border-radius: 6px;
        object-fit: cover;
        margin-left: auto;
    }

    .dot {
        width: 8px;
        height: 8px;
        background: rgb(0, 119, 238);
        border-radius: 50%;
        display: inline-block;
        margin-right: 8px;
    }

    @media (max-width: 1100px) {
        .sidebar-right {
            display: none;
        }

        .sidebar {
            display: none;
        }

        .main-content {
            padding: 10px;
        }

        .main-feed {
            max-width: 100%;
        }
    }

    .cus-avatar {
        display: inline-flex;
        justify-content: center;
        align-items: center;

        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #03174f;
        color: white;
        font-size: 20px;
        font-weight: bold;
        font-family: Arial, sans-serif;
    }

    .custom-link {
        color: #222;
        text-decoration: none;
        font-weight: bold;
        transition: color 0.2s;
    }

    .custom-link-1 {
        color: white;
        text-decoration: none;
        font-weight: bold;
        transition: color 0.2s;
    }
</style>
</head>

<body>
    <div class="container">

        <!-- Main Content -->
        <main class="main-content">
            <div class="main-feed">

                <!-- Card 1 -->
                <div class="card">
                    <div class="card-header">
                        <img src="https://media.dff.vn//web/image/2025/9/ptd638931250677169797.jpg" class="card-img">
                        <div class="card-title">
                            <a href="/bai-viet-card-1.html" class="custom-link">
                                Ông Phan Đức Trung làm rõ quy định 6 tháng chuyển tài sản số về sàn được cấp phép
                            </a>
                            <div class="card-meta">
                                <span><i class="fa-regular fa-calendar"></i> 3 ngày</span>
                                <span><i class="fa-regular fa-user"></i> Lê Nguyên</span>
                            </div>
                        </div>
                    </div>

                    <div class="card-comment">
                        <img src="https://dff.vn/Upload/img_static/bannerL%C3%AA%20638597439913871161.png"
                            class="comment-avatar-sm">

                        <div class="comment-content">
                            <div class="comment-header">
                                <span class="comment-author">Lê Minh</span>
                                <span class="comment-date">3 ngày</span>
                            </div>
                            <p class="comment-text">
                                Giai đoạn này vẫn chưa token hóa chứng khoán, tôi chỉ đợi cái này
                            </p>
                        </div>
                    </div>
                    <div class="card-more">
                        <i class="fa-regular fa-comments"></i> <a href="#binhluan">Xem thêm bình luận</a>
                    </div>
                </div>
                <!-- Card 2 -->
                <div class="card big-card">
                    <div class="big-card-content" style="background:#068802;">
                        <div class="big-card-title">
                            <a href="/bai-viet-card-2.html" class="custom-link-1">
                                Hơn 24 tỷ USD vốn FDI "chảy" về Việt Nam 7 tháng đầu năm
                            </a>

                        </div>
                    </div>
                    <div class="card-meta">
                        <span><i class="fa-regular fa-calendar"></i> 29 ngày</span>
                        <span><i class="fa-regular fa-user"></i> Mai Hương</span>
                    </div>

                    <div class="card-comment">
                        <img src="https://dff.vn/Upload/img_static/bannerbin638590690085514318.jpg"
                            class="comment-avatar-sm">


                        <div class="comment-content">
                            <div class="comment-header">
                                <span class="comment-author">Mai Hương</span>
                                <span class="comment-date">29 ngày</span>
                            </div>
                            <p class="comment-text">
                                Theo Cục thống kê, Bộ Tài chính, tính đến hết tháng 7, tổng vốn
                                nước ngoài FDI đăng ký vào Việt Nam đạt hơn 24 tỷ USD, tăng 27,3% so với cùng kỳ năm
                                trước.<br><br>

                                Trong số các dự án được cấp phép mới, ngành công nghiệp chế biến chế tạo chiếm vị
                                trí
                                dẫn đầu với tổng số vốn đăng ký đạt 5,61 tỷ USD, chiếm 55,9%.
                                Đứng vị trí thứ 2 là hoạt động kinh doanh bất động sản, với 2,36 tỷ USD.
                                Các ngành còn lại chỉ chiếm hơn 20%.
                            </p>
                        </div>
                    </div>

                    <div class="card-more">
                        <i class="fa-regular fa-comments"></i> <a href="#binhluan">Xem thêm bình luận</a>
                    </div>
                </div>
                <!-- Card 3 -->
                <div class="card">
                    <div class="card-header">
                        <img src="https://media.dff.vn//web/image/2025/8/masan-crownx638905394655059455.jpg"
                            class="card-img">
                        <div class="card-title">
                            <a href="/bai-viet-card-3.html" class="custom-link">
                                Masan bất ngờ nâng sở hữu ở The CrownX, ‘siêu game’ IPO về đâu?
                            </a>
                            <div class="card-meta">
                                <span><i class="fa-regular fa-calendar"></i> 20:08 11/08</span>
                                <span><i class="fa-regular fa-user"></i> Thu Hoài</span>
                            </div>
                        </div>
                    </div>

                    <div class="card-comment">
                        <img src="https://dff.vn/Upload/img_static/bannerpav638590797168305489.jpg"
                            class="comment-avatar-sm">

                        <div class="comment-content">
                            <div class="comment-header">
                                <span class="comment-author">Ninh Giang</span>
                                <span class="comment-date">21:18 - 11/08</span>
                            </div>
                            <p class="comment-text">
                                Singha cũng đang exit rồi!
                            </p>
                        </div>
                    </div>
                    <div class="card-more">
                        <i class="fa-regular fa-comments"></i> <a href="#binhluan">Xem thêm bình luận</a>
                    </div>
                </div>
                <!-- Card 4 -->
                <div class="card big-card">
                    <div class="big-card-content" style="background:#ee05bb;">
                        <div class="big-card-title">
                            <a href="/bai-viet-card-4.html" class="custom-link-1">
                                Dư nợ tín dụng bất động sản tính đến hết tháng 5 đạt hơn 1,6 triệu tỷ đồng
                            </a>
                        </div>
                    </div>
                    <div class="card-meta">
                        <span><i class="fa-regular fa-calendar"></i> 09:57 - 01/08</span>
                        <span><i class="fa-regular fa-user"></i> Cao Lê Minh Long</span>
                    </div>
                    <div class="card-comment">
                        <img src="https://dff.vn/Upload/img_static/bannerbin638590690085514318.jpg"
                            class="comment-avatar-sm">

                        <div class="comment-content">
                            <div class="comment-header">
                                <span class="comment-author"> Cao Lê Minh Long</span>
                                <span class="comment-date"> 09:59 - 01/08</span>
                            </div>
                            <p class="comment-text">
                                Theo báo cáo của NHNN, tính đến 31/5/2025 dư nợ tín dụng đối với hoạt động kinh doanh
                                bất động sản đạt 1.640.682 tỷ đồng (tăng 36% so với cùng kỳ năm trước) và là mức cao
                                nhất kể từ năm 2023
                            </p>
                        </div>
                    </div>

                    <div class="card-more">
                        <i class="fa-regular fa-comments"></i> <a href="#binhluan">Xem thêm bình luận</a>
                    </div>
                </div>
                <!-- Card 5 -->
                <div class="card">
                    <div class="card-header">
                        <img src="https://media.dff.vn//web/image/2025/7/thiet-ke-chua-co-ten638895763520385652.png"
                            class="card-img">
                        <div class="card-title">
                            <a href="/bai-viet-card-5.html" class="custom-link">
                                Lợi nhuận "Big 4" ngân hàng quý 2/2025: VietinBank soán ngôi Vietcombank, BIDV lãi gần
                                7.000
                                tỷ đồng</a>
                            <div class="card-meta">
                                <span><i class="fa-regular fa-calendar"></i> 17:33 - 31/07</span>
                                <span><i class="fa-regular fa-user"></i> Mai Hương</span>
                            </div>
                        </div>
                    </div>

                    <div class="card-comment">
                        <span class="cus-avatar">H</span>
                        <div class="comment-content">
                            <div class="comment-header">
                                <span class="comment-author"> Phạm Hải</span>

                                <span class="comment-date"> 08:52 - 01/08</span>
                            </div>
                            <p class="comment-text">
                                DN thì khó khăn mà các bank lãi lớn quá
                            </p>
                        </div>
                    </div>
                    <div class="card-more">
                        <i class="fa-regular fa-comments"></i> <a href="#binhluan">Xem thêm bình luận</a>
                    </div>
                </div>
                <!-- Card 6 -->
                <div class="card">
                    <div class="card-header">
                        <img src="https://media.dff.vn//web/image/2025/7/dow-jones-1638895448436302265.jpg"
                            class="card-img">
                        <div class="card-title">
                            <a href="/bai-viet-card-6.html" class="custom-link">
                                Chứng khoán Mỹ giảm điểm khi Fed chưa sẵn sàng hạ lãi suất</a>
                            <div class="card-meta">
                                <span><i class="fa-regular fa-calendar"></i> 07:50 - 31/07 </span>
                                <span><i class="fa-regular fa-user"></i> Thanh Tùng</span>
                            </div>
                        </div>
                    </div>

                    <div class="card-comment">
                        <span class="cus-avatar">H</span>
                        <div class="comment-content">
                            <div class="comment-header">
                                <span class="comment-author"> Phạm Hải</span>

                                <span class="comment-date"> 09:59 - 31/07</span>
                            </div>
                            <p class="comment-text">
                                Không phải D. Trump muốn gì cũng được, Jerome Powell cứng đấy
                            </p>
                        </div>
                    </div>
                    <div class="card-more">
                        <i class="fa-regular fa-comments"></i> <a href="#binhluan">Xem thêm bình luận</a>
                    </div>
                </div>
                <!-- Card 7 -->
                <div class="card">
                    <div class="card-header">
                        <img src="https://media.dff.vn//web/image/2025/7/lg638894706737981988.png" class="card-img">
                        <div class="card-title">
                            <a href="/bai-viet-card-7.html" class="custom-link">
                                LG ký hợp đồng cung cấp pin 4,3 tỷ USD: Tesla là đối tác?</a>
                            <div class="card-meta">
                                <span><i class="fa-regular fa-calendar"></i> 11:12 - 30/0</span>
                                <span><i class="fa-regular fa-user"></i> Đức Anh</span>
                            </div>
                        </div>
                    </div>

                    <div class="card-comment">
                        <span class="cus-avatar">H</span>
                        <div class="comment-content">
                            <div class="comment-header">
                                <span class="comment-author"> Kim Huệ</span>

                                <span class="comment-date"> 15:56 - 30/07</span>
                            </div>
                            <p class="comment-text">
                                Giảm phụ thuộc vào nguồn pin Trung Quốc
                            </p>
                        </div>
                    </div>
                    <div class="card-more">
                        <i class="fa-regular fa-comments"></i> <a href="#binhluan">Xem thêm bình luận</a>
                    </div>
                </div>
                <!-- Card 8 -->


                <div class="card">
                    <div class="card-header">
                        <img src="https://media.dff.vn//web/image/2025/7/bbbb638894711684860491.png" class="card-img">
                        <div class="card-title">
                            <a href="/bai-viet-card-3.html" class="custom-link">
                                Chuyên gia: Chênh lệch thuế đối ứng giữa các quốc gia không thể khiến doanh nghiệp FDI
                                rời khỏi Việt Nam
                            </a>
                            <div class="card-meta">
                                <span><i class="fa-regular fa-calendar"></i> 11:25 - 30/07</span>
                                <span><i class="fa-regular fa-user"></i> Hạ Vy</span>
                            </div>
                        </div>
                    </div>

                    <div class="card-comment">
                        <img src="https://img.dff.vn/Image/2024/12/04/kinh-nghiem-du-lich-dam-van-long-ninh-binh-2-23220887.jpg"
                            class="comment-avatar-sm">

                        <div class="comment-content">
                            <div class="comment-header">
                                <span class="comment-author"> Phạm Mỹ Anh</span>
                                <span class="comment-date"> 15:45 - 30/07</span>
                            </div>
                            <p class="comment-text">
                                Vấn đề là VN cần tái cấu trúc lại nền kinh tế, đổi mới và nâng cấp chuỗi giá trị thì
                                vững và bền được

                            </p>
                        </div>
                    </div>
                    <div class="card-more">
                        <i class="fa-regular fa-comments"></i> <a href="#binhluan">Xem thêm bình luận</a>
                    </div>
                </div>
                <!-- Card 9 -->
                <div class="card">
                    <div class="card-header">
                        <img src="https://media.dff.vn//web/image/2025/7/lg638894706737981988.png" class="card-img">
                        <div class="card-title">
                            <a href="/bai-viet-card-7.html" class="custom-link">
                                LG ký hợp đồng cung cấp pin 4,3 tỷ USD: Tesla là đối tác?</a>
                            <div class="card-meta">
                                <span><i class="fa-regular fa-calendar"></i> 11:12 - 30/0</span>
                                <span><i class="fa-regular fa-user"></i> Đức Anh</span>
                            </div>
                        </div>
                    </div>

                    <div class="card-comment">
                        <span class="cus-avatar">H</span>
                        <div class="comment-content">
                            <div class="comment-header">
                                <span class="comment-author"> Kim Huệ</span>

                                <span class="comment-date"> 15:56 - 30/07</span>
                            </div>
                            <p class="comment-text">
                                Giảm phụ thuộc vào nguồn pin Trung Quốc
                            </p>
                        </div>
                    </div>
                    <div class="card-more">
                        <i class="fa-regular fa-comments"></i> <a href="#binhluan">Xem thêm bình luận</a>
                    </div>
                </div>
                <!-- Card 10-->
                <div class="card">
                    <div class="card-header">
                        <img src="https://media.dff.vn//web/image/2025/7/vni638893815108106606.jpg" class="card-img">
                        <div class="card-title">
                            <a href="/bai-viet-card-7.html" class="custom-link">
                                VN-Index và VN30 ngay lúc này. AE đã kịp chốt lời hay chưa?</a>
                            <div class="card-meta">
                                <span><i class="fa-regular fa-calendar"></i> 10:25 - 29/07</span>
                                <span><i class="fa-regular fa-user"></i> Hoàng Giang</span>
                            </div>
                        </div>
                    </div>

                    <div class="card-comment">
                        <img src="https://img.dff.vn/Image/2024/11/07/statictttc-171803591.jpg"
                            class="comment-avatar-sm">
                        <div class="comment-content">
                            <div class="comment-header">
                                <span class="comment-author"> Bình Minh </span>

                                <span class="comment-date"> 11:21 - 29/07</span>
                            </div>
                            <p class="comment-text">
                                Nhịp này chỉnh 1-2 phiên nữa mới đưa tt về trạng thái an toàn
                            </p>
                        </div>
                    </div>
                    <div class="card-more">
                        <i class="fa-regular fa-comments"></i> <a href="#binhluan">Xem thêm bình luận</a>
                    </div>
                </div>
                <!-- Card 11 -->
                <div class="card">
                    <div class="card-header">
                        <img src="https://media.dff.vn//web/image/2025/7/thuong-mai-dien-tu638889683999321067.jpg"
                            class="card-img">
                        <div class="card-title">
                            <a href="/bai-viet-card-11.html" class="custom-link">
                                Người Việt chi hơn 1.000 tỷ đồng mua hàng online mỗi ngày</a>
                            <div class="card-meta">
                                <span><i class="fa-regular fa-calendar"></i> 15:43 - 24/07</span>
                                <span><i class="fa-regular fa-user"></i> Ngọc Lan</span>
                            </div>
                        </div>
                    </div>

                    <div class="card-comment">
                        <span class="cus-avatar">N</span>
                        <div class="comment-content">
                            <div class="comment-header">
                                <span class="comment-author"> Bình Nguyên</span>

                                <span class="comment-date"> 10:01 - 26/07</span>
                            </div>
                            <p class="comment-text">
                                Các sàn TMĐT của các đại gia công nghệ VN ko thấy đâu nhỉ
                            </p>
                        </div>
                    </div>
                    <div class="card-more">
                        <i class="fa-regular fa-comments"></i> <a href="#binhluan">Xem thêm bình luận</a>
                    </div>
                </div>
                <!-- Card 12 -->
                <div class="card">
                    <div class="card-header">
                        <img src="https://media.dff.vn//web/image/2025/7/me-linh638889717861972657.jpg"
                            class="card-img">
                        <div class="card-title">
                            <a href="/bai-viet-card-12.html" class="custom-link">
                                Sun Group làm khu đô thị 15.000 tỷ đồng ở Mê Linh</a>
                            <div class="card-meta">
                                <span><i class="fa-regular fa-calendar"></i> 11:25 - 30/07</span>
                                <span><i class="fa-regular fa-user"></i> Hạ Vy</span>
                            </div>
                        </div>
                    </div>

                    <div class="card-comment">
                        <span class="cus-avatar">C</span>
                        <div class="comment-content">
                            <div class="comment-header">
                                <span class="comment-author"> Phạm Mỹ Anh</span>

                                <span class="comment-date"> 15:45 - 30/07</span>
                            </div>
                            <p class="comment-text">
                                Vấn đề là VN cần tái cấu trúc lại nền kinh tế, đổi mới và nâng cấp chuỗi giá trị thì
                                vững và bền được

                            </p>
                        </div>
                    </div>
                    <div class="card-more">
                        <i class="fa-regular fa-comments"></i> <a href="#binhluan">Xem thêm bình luận</a>
                    </div>
                </div>
                <!-- Card 13-->
                <div class="card">
                    <div class="card-header">
                        <img src="https://media.dff.vn//web/image/2025/5/sam1638838687082101988.jpg" class="card-img">
                        <div class="card-title">
                            <a href="/bai-viet-card-13.html" class="custom-link">
                                'Tất tần tật' về World Network và token $WLD - dự án của CEO OpenAI</a>
                            <div class="card-meta">
                                <span><i class="fa-regular fa-calendar"></i> 6:16 - 26/05</span>
                                <span><i class="fa-regular fa-user"></i> Vũ Đức</span>
                            </div>
                        </div>
                    </div>

                    <div class="card-comment">
                        <img src="https://dff.vn/Upload/img_static//profile_638889863079319764.png"
                            class="comment-avatar-sm">
                        <div class="comment-content">
                            <div class="comment-header">


                                <span class="comment-date"> 20:41 - 24/07</span>
                            </div>
                            <p class="comment-text">
                                Worldnetwork này và worldnetwork phát hành flash network trên Ch play có giống nhau ko
                                bạn
                            </p>
                        </div>
                    </div>
                    <div class="card-more">
                        <i class="fa-regular fa-comments"></i> <a href="#binhluan">Xem thêm bình luận</a>
                    </div>
                </div>
                <!-- Card 14 -->
                <div class="card big-card">
                    <div class="big-card-content" style="background:#7c71fb;">
                        <div class="big-card-title">
                            <a href="/bai-viet-card-14.html" class="custom-link-1">
                                Cổ phiếu VIC tăng kịch trần, vượt 100.000 đồng/cp,
                                vốn hóa tiến sát 15 tỷ USD
                            </a>
                        </div>
                    </div>
                    <div class="card-meta">
                        <span><i class="fa-regular fa-calendar"></i> 10:56 - 10/07</span>
                        <span><i class="fa-regular fa-user"></i> Cao Lê Minh Long</span>
                    </div>
                    <div class="card-comment">
                        <img src="https://dff.vn/Upload/img_static/bannerbin638676034452820681.jpg"
                            class="comment-avatar-sm">

                        <div class="comment-content">
                            <div class="comment-header">
                                <span class="comment-author">Thu Hoài</span>
                                <span class="comment-date"> 11:10 - 10/07</span>
                            </div>
                            <p class="comment-text">
                                Bác Vượng nói rồi - "mua vàng hay mua VIC thì mua VIC là đúng rồi"
                        </div>
                    </div>

                    <div class="card-more">
                        <i class="fa-regular fa-comments"></i> <a href="#binhluan">Xem thêm bình luận</a>
                    </div>
                </div>
                <!-- Card 15-->
                <div class="card">
                    <div class="card-header">
                        <img src="https://media.dff.vn//web/image/2025/7/nguyen-thi-mui638876539187923053.jpg"
                            class="card-img">
                        <div class="card-title">
                            <a href="/bai-viet-card-15.html" class="custom-link">
                                Bỏ "room" tín dụng: Cần một lộ trình...</a>
                            <div class="card-meta">
                                <span><i class="fa-regular fa-calendar"></i> 10:39 - 09/07</span>
                                <span><i class="fa-regular fa-user"></i> Thanh Huyền</span>
                            </div>
                        </div>
                    </div>

                    <div class="card-comment">
                        <img src="https://dff.vn/Upload/img_static/bannerpav638590797168305489.jpg"
                            class="comment-avatar-sm">
                        <div class="comment-content">
                            <div class="comment-header">
                                <span class="comment-author">Ninh Giang</span>
                                <span class="comment-date"> 15:29 - 09/07</span>
                            </div>
                            <p class="comment-text">
                                Vận hành theo cung - cầu cũng có cái hay về tính thị trường nhưng ngân hàng là một ngành
                                rất đặc thù.<br>
                                Bỏ room thì cũng cần có những công cụ đủ hữu hiệu để thay thế!
                            </p>
                        </div>
                    </div>
                    <div class="card-more">
                        <i class="fa-regular fa-comments"></i> <a href="#binhluan">Xem thêm bình luận</a>
                    </div>
                </div>
                <!-- Card 16-->
                <div class="card">
                    <div class="card-header">
                        <img src="https://media.dff.vn//web/image/2025/7/112638872285924333965.jpg" class="card-img">
                        <div class="card-title">
                            <a href="/bai-viet-card-16.html" class="custom-link">
                                JD.com, Ant Group thúc đẩy stablecoin nhân dân tệ, nhằm phá vỡ thế thống trị đồng
                                USD</a>
                            <div class="card-meta">
                                <span><i class="fa-regular fa-calendar"></i> 12:40 - 04/07</span>
                                <span><i class="fa-regular fa-user"></i> Quân Anh</span>
                            </div>
                        </div>
                    </div>

                    <div class="card-comment">
                        <img src="https://dff.vn/Upload/img_static//profile_638872390890896596.jpe"
                            class="comment-avatar-sm">
                        <div class="comment-content">
                            <div class="comment-header">
                                <span class="comment-author">Ninh Giang</span>
                                <span class="comment-date"> 15:20 - 04/07</span>
                            </div>
                            <p class="comment-text">
                                Đồng bạc xanh cũng chỉ là khái niệm diễn giải
                            </p>
                        </div>
                    </div>
                    <div class="card-more">
                        <i class="fa-regular fa-comments"></i> <a href="#binhluan">Xem thêm bình luận</a>
                    </div>
                </div>
                <!-- Card 17-->
                <div class="card">
                    <div class="card-header">
                        <img src="https://media.dff.vn//web/image/2025/5/sam1638838687082101988.jpg" class="card-img">
                        <div class="card-title">
                            <a href="/bai-viet-card-17.html" class="custom-link">
                                'Tất tần tật' về World Network và token $WLD - dự án của CEO OpenAI</a>
                            <div class="card-meta">
                                <span><i class="fa-regular fa-calendar"></i> 6:16 - 26/05</span>
                                <span><i class="fa-regular fa-user"></i> Vũ Đức</span>
                            </div>
                        </div>
                    </div>

                    <div class="card-comment">
                        <img src="https://dff.vn/Upload/img_static//profile_638889863079319764.png"
                            class="comment-avatar-sm">
                        <div class="comment-content">
                            <div class="comment-header">
                                <span class="comment-date"> 20:41 - 24/07</span>
                            </div>
                            <p class="comment-text">
                                Worldnetwork này và worldnetwork phát hành flash network trên Ch play có giống nhau ko
                                bạn
                            </p>
                        </div>
                    </div>
                    <div class="card-more">
                        <i class="fa-regular fa-comments"></i> <a href="#binhluan">Xem thêm bình luận</a>
                    </div>
                </div>
                <!-- Card 18-->
                <div class="card">
                    <div class="card-header">
                        <img src="https://media.dff.vn//web/image/2025/7/co-phieu-ck638870646585503238.jpg"
                            class="card-img">
                        <div class="card-title">
                            <a href="/bai-viet-card-18.html" class="custom-link">
                                Cổ phiếu ngành Chứng im hơi lặng tiếng từ hôm sập thuế đến giờ. Anh em đu chưa hay vẫn
                                còn nghi ngờ? 😆</a>
                            <div class="card-meta">
                                <span><i class="fa-regular fa-calendar"></i> 14:52 - 02/07</span>
                                <span><i class="fa-regular fa-user"></i> Việt Anh</span>
                            </div>
                        </div>
                    </div>

                    <div class="card-comment">
                        <img src="https://dff.vn/Upload/img_static//profile_638872390890896596.jpe"
                            class="comment-avatar-sm">
                        <div class="comment-content">
                            <div class="comment-header">
                                <span class="comment-author"> Thành Công</span>
                                <span class="comment-date"> 15:02 - 02/07</span>
                            </div>
                            <p class="comment-text">
                                Chứng nay cuối phiên kéo mạnh thiệt, 1.400 điểm thẳng tiến!!!
                            </p>
                        </div>
                    </div>
                    <div class="card-more">
                        <i class="fa-regular fa-comments"></i> <a href="#binhluan">Xem thêm bình luận</a>
                    </div>
                </div>
                <!-- Card 19 -->
                <div class="card big-card">
                    <div class="big-card-content" style="background:#005685;">
                        <div class="big-card-title">
                            <a href="/bai-viet-card-19.html" class="custom-link-1">
                                Sắp xếp lại giang sơn, tiến vào Kỷ nguyên mới: Ngày mai bắt đầu từ hôm nay...
                            </a>
                        </div>
                    </div>
                    <div class="card-meta">
                        <span><i class="fa-regular fa-calendar"></i> 07:49 - 01/07</span>
                        <span><i class="fa-regular fa-user"></i> Hoàng Giang</span>
                    </div>
                    <div class="card-comment">
                        <img src="https://dff.vn/Upload/img_static/bannerhuu638590914569523060.jpg"
                            class="comment-avatar-sm">

                        <div class="comment-content">
                            <div class="comment-header">
                                <span class="comment-author"> Vinh Trần</span>
                                <span class="comment-date"> 10:06 - 01/07</span>
                            </div>
                            <p class="comment-text">
                                VNIndex 1800 k?
                        </div>
                    </div>

                    <div class="card-more">
                        <i class="fa-regular fa-comments"></i> <a href="#binhluan">Xem thêm bình luận</a>
                    </div>
                </div>
                <!-- Card 20 -->
                <div class="card big-card">
                    <div class="big-card-content" style="background:#53ca56;">
                        <div class="big-card-title">
                            <a href="/bai-viet-card-20.html" class="custom-link-1">
                                VN-Index chốt tháng 6 ở mức 1.376 điểm, tăng 109 điểm so với đầu năm </a>
                        </div>
                    </div>
                    <div class="card-meta">
                        <span><i class="fa-regular fa-calendar"></i> 15:36 - 30/06</span>
                        <span><i class="fa-regular fa-user"></i> Mai Hương</span>
                    </div>
                    <div class="card-comment">
                        <img src="https://dff.vn/Upload/img_static/bannerhuu638590914569523060.jpg"
                            class="comment-avatar-sm">

                        <div class="comment-content">
                            <div class="comment-header">
                                <span class="comment-author"> Vinh Trần</span>
                                <span class="comment-date"> 15:38 - 30/06</span>
                            </div>
                            <p class="comment-text">
                                Năm nay đánh thơm đó chứ
                        </div>
                    </div>

                    <div class="card-more">
                        <i class="fa-regular fa-comments"></i> <a href="#binhluan">Xem thêm bình luận</a>
                    </div>
                </div>
                <!-- Card 21 -->
                <div class="card big-card">
                    <div class="big-card-content" style="background:#208331;">
                        <div class="big-card-title">
                            <a href="/bai-viet-card-21.html" class="custom-link-1">
                                Lãnh đạo NHNN: Tăng trưởng tín dụng đến 18/6 ước đạt 7,14%
                        </div>
                    </div>
                    <div class="card-meta">
                        <span><i class="fa-regular fa-calendar"></i> 15:16 - 27/06</span>
                        <span><i class="fa-regular fa-user"></i> Minh Long</span>
                    </div>
                    <div class="card-comment">
                        <img src="https://dff.vn/Upload/img_static/bannerfar638590818730509201.jpeg"
                            class="comment-avatar-sm">

                        <div class="comment-content">
                            <div class="comment-header">
                                <span class="comment-author"> Minh Long</span>
                                <span class="comment-date"> 15:17 - 27/06</span>
                            </div>
                            <p class="comment-text">
                                Theo ông Nguyễn Phi Lân, Vụ trưởng Vụ Dự báo, thống kê - Ổn định tiền tệ, tài chính Ngân
                                hàng Nhà nước (NHNN), tính đến ngày 18/6/2025, dư nợ tín dụng toàn hệ thống đạt 16,73
                                triệu tỷ đồng, tăng 7,14% so với cuối 2024.</p>
                        </div>
                    </div>

                    <div class="card-more">
                        <i class="fa-regular fa-comments"></i> <a href="#binhluan">Xem thêm bình luận</a>
                    </div>
                </div>
                <!-- Card 22-->
                <div class="card">
                    <div class="card-header">
                        <img src="https://media.dff.vn//web/image/2025/6/stablecoin1638865471802123853.jpg"
                            class="card-img">
                        <div class="card-title">
                            <a href="/bai-viet-card-22.html" class="custom-link">
                                🌍 Bản đồ stablecoin: Khu vực nào đang sử dụng USDT nhiều nhất?</a>
                            <div class="card-meta">
                                <span><i class="fa-regular fa-calendar"></i> 15:08 - 26/06</span>
                                <span><i class="fa-regular fa-user"></i> Lê Minh</span>
                            </div>
                        </div>
                    </div>

                    <div class="card-comment">
                        <img src="https://dff.vn/Upload/img_static/bannerfii638590812857749581.jpg"
                            class="comment-avatar-sm">
                        <div class="comment-content">
                            <div class="comment-header">
                                <span class="comment-author"> Thanh Huyền</span>
                                <span class="comment-date"> 15:18 - 26/06</span>
                            </div>
                            <p class="comment-text">
                                Cái đáng chú ý không phải là tổng lượng giao dịch, mà những giao dịch này được dùng vào
                                mục đích gì, hay cũng chỉ đơn giản là công cụ cho trader lướt sóng :3?
                            </p>
                        </div>
                    </div>
                    <div class="card-more">
                        <i class="fa-regular fa-comments"></i> <a href="#binhluan">Xem thêm bình luận</a>
                    </div>
                </div>
                <!-- Card 23 -->
                <div class="card big-card">
                    <div class="big-card-content" style="background:#005685;">
                        <div class="big-card-title">
                            <a href="/bai-viet-card-21.html" class="custom-link-1">
                                Dư nợ tín dụng 5 tháng đầu năm tăng 6,32%
                        </div>
                    </div>
                    <div class="card-meta">
                        <span><i class="fa-regular fa-calendar"></i> 10:30 - 25/06</span>
                        <span><i class="fa-regular fa-user"></i> Huy Invest</span>
                    </div>
                    <div class="card-comment">
                        <img src="https://dff.vn/Upload/img_static/bannerfar638590818730509201.jpeg"
                            class="comment-avatar-sm">

                        <div class="comment-content">
                            <div class="comment-header">
                                <span class="comment-author"> Huy Invest</span>
                                <span class="comment-date"> 10:31 - 25/06</span>
                            </div>
                            <p class="comment-text">
                                Theo báo cáo của NHNN, tính đến ngày 28/5, dư nợ tín dụng toàn nền kinh tế đạt hơn 16,6
                                triệu tỷ đồng, tăng 6,32% so với đầu năm.</p>
                        </div>
                    </div>

                    <div class="card-more">
                        <i class="fa-regular fa-comments"></i> <a href="#binhluan">Xem thêm bình luận</a>
                    </div>
                </div>
                <!-- Card 24-->
                <div class="card">
                    <div class="card-header">
                        <img src="https://media.dff.vn//web/image/2025/6/coinshare-1638862982071737344.jpg"
                            class="card-img">
                        <div class="card-title">
                            <a href="/bai-viet-card-24.html" class="custom-link">
                                Hơn 1 tỷ USD chảy vào các sản phẩm ETP Crypto trong tuần qua</a>
                            <div class="card-meta">
                                <span><i class="fa-regular fa-calendar"></i> 18:11 - 23/06 </span>
                                <span><i class="fa-regular fa-user"></i> Lê Minh</span>
                            </div>
                        </div>
                    </div>

                    <div class="card-comment">
                        <img src="https://dff.vn/Upload/img_static/bannerfii638590812857749581.jpg"
                            class="comment-avatar-sm">
                        <div class="comment-content">
                            <div class="comment-header">
                                <span class="comment-author"> Lê Minh</span>
                                <span class="comment-date"> 18:12 - 23/06</span>
                            </div>
                            <p class="comment-text">
                                Bất chấp Bitcoin và Ether rơi vào nhịp điều chỉnh, các quỹ đầu tư crypto toàn cầu vẫn
                                hút ròng 1,24 tỷ USD trong tuần qua, theo CoinShares.<br><br>

                                Với con số mới nhất này, tổng dòng vốn đổ vào các ETP crypto tính từ đầu năm (YTD) đã
                                lập kỷ lục mới 15,1 tỷ USD. Tổng tài sản đang được quản lý (AUM) cũng tăng nhẹ, từ 175,9
                                tỷ USD lên 176,3 tỷ USD.<br><br>

                                Dù giá Bitcoin giảm mạnh từ khoảng 108.800 USD xuống còn 103.000 USD trong tuần (theo
                                CoinGecko), các ETP liên quan đến BTC vẫn hút về 1,1 tỷ USD, đánh dấu tuần thứ hai liên
                                tiếp ghi nhận dòng vốn vào.<br><br>
                            </p>
                        </div>
                    </div>
                    <div class="card-more">
                        <i class="fa-regular fa-comments"></i> <a href="#binhluan">Xem thêm bình luận</a>
                    </div>
                </div>
                <!-- Card 25-->
                <div class="card">
                    <div class="card-header">
                        <img src="https://media.dff.vn//web/image/2025/5/sam1638838687082101988.jpg" class="card-img">
                        <div class="card-title">
                            <a href="/bai-viet-card-13.html" class="custom-link">
                                'Tất tần tật' về World Network và token $WLD - dự án của CEO OpenAI</a>
                            <div class="card-meta">
                                <span><i class="fa-regular fa-calendar"></i> 6:16 - 26/05</span>
                                <span><i class="fa-regular fa-user"></i> Vũ Đức</span>
                            </div>
                        </div>
                    </div>

                    <div class="card-comment">
                        <img src="https://dff.vn/Upload/img_static//profile_638889863079319764.png"
                            class="comment-avatar-sm">
                        <div class="comment-content">
                            <div class="comment-header">
                                <span class="comment-date"> 20:41 - 24/07</span>
                            </div>
                            <p class="comment-text">
                                Worldnetwork này và worldnetwork phát hành flash network trên Ch play có giống nhau ko
                                bạn
                            </p>
                        </div>
                    </div>
                    <div class="card-more">
                        <i class="fa-regular fa-comments"></i> <a href="#binhluan">Xem thêm bình luận</a>
                    </div>
                </div>
                <!-- Card 26 -->
                <div class="card big-card">
                    <div class="big-card-content" style="background:#005685;">
                        <div class="big-card-title">
                            <a href="/bai-viet-card-26.html" class="custom-link-1">
                                Việt Nam xuất siêu sang Mỹ 50 tỷ USD trong 5 tháng đầu năm, tăng 29% so với cùng kỳ
                        </div>
                    </div>
                    <div class="card-meta">
                        <span><i class="fa-regular fa-calendar"></i> 11:01 - 20/06</span>
                        <span><i class="fa-regular fa-user"></i> Minh Long</span>
                    </div>
                    <div class="card-comment">
                        <img src="https://dff.vn/Upload/img_static/bannerfar638590818730509201.jpeg "
                            class="comment-avatar-sm">

                        <div class="comment-content">
                            <div class="comment-header">
                                <span class="comment-author"> Minh Long</span>
                                <span class="comment-date"> 11:02 - 20/06</span>
                            </div>
                            <p class="comment-text">
                                Lũy kế 5 tháng đầu năm, tổng kim ngạch xuất, nhập khẩu hàng hóa của Việt Nam đạt 355,79
                                tỷ USD - tăng 15,7% so với cùng kỳ, cán cân thương mại hàng hóa xuất siêu 4,67 tỷ
                                USD.<br><br>

                                Phân theo thị trường, nước ta xuất siêu sang Mỹ 49,9 tỷ USD tăng 28,5% so với cùng kỳ;
                                xuất siêu sang EU 16,3 tỷ USD, tăng 16,0%; xuất siêu sang Nhật Bản 0,9 tỷ USD, tăng
                                74,8%.</p>
                        </div>
                    </div>

                    <div class="card-more">
                        <i class="fa-regular fa-comments"></i> <a href="#binhluan">Xem thêm bình luận</a>
                    </div>
                </div>
                <!-- Card 27-->
                <div class="card">
                    <div class="card-header">
                        <img src="https://media.dff.vn//web/image/2025/6/123638859557003975284.jpg" class="card-img">
                        <div class="card-title">
                            <a href="/bai-viet-card-27.html" class="custom-link">
                                Không ai hành động, nhưng mọi thứ đang dịch chuyển</a>
                            <div class="card-meta">
                                <span><i class="fa-regular fa-calendar"></i> 18:50 - 19/06</span>
                                <span><i class="fa-regular fa-user"></i> Hoàng Tùng </span>
                            </div>
                        </div>
                    </div>

                    <div class="card-comment">
                        <img src="https://dff.vn/Upload/img_static//profile_638889863079319764.png"
                            class="comment-avatar-sm">
                        <div class="comment-content">
                            <div class="comment-header">
                                <span class="comment-author"> Vinh Trần </span>
                                <span class="comment-date"> 22:59 - 19/06</span>
                            </div>
                            <p class="comment-text">
                                Bài hay quá, Sếp Tùng!
                            </p>
                        </div>
                    </div>
                    <div class="card-more">
                        <i class="fa-regular fa-comments"></i> <a href="#binhluan">Xem thêm bình luận</a>
                    </div>
                </div>
</body>

</html>