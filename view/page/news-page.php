<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Bài đăng - Mẫu</title>
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    :root{--bg:#f0f2f5;--card:#fff;--muted:#6b7280;--accent:#1877f2}
    body{font-family:Inter, system-ui, -apple-system, 'Segoe UI', Roboto, Arial; background:var(--bg); margin:0; padding:24px; color:#111}
    .container{max-width:820px;margin:0 auto}
    .card{background:var(--card);border-radius:10px;box-shadow:0 1px 2px rgba(16,24,40,0.05);padding:18px;margin-bottom:16px}
    .header{display:flex;align-items:center;gap:12px}
    .avatar{width:46px;height:46px;border-radius:50%;overflow:hidden}
    .avatar img{width:100%;height:100%;object-fit:cover}
    .meta{flex:1}
    .meta .name{font-weight:600}
    .meta .time{font-size:13px;color:var(--muted)}
    .follow{background:transparent;border:1px solid #e5e7eb;padding:6px 10px;border-radius:6px;font-weight:600;color:#111;cursor:pointer}
    .menu{color:var(--muted);font-size:18px;cursor:pointer}
    .post-title{font-size:20px;font-weight:700;margin:14px 0 6px}
    .post-text{color:#111;line-height:1.6;margin-bottom:12px}
    .post-image{width:100%;height:260px;background:#e6eefb;border-radius:8px;display:block;object-fit:cover;margin-bottom:12px}
    .info{display:flex;gap:12px;flex-wrap:wrap;color:var(--muted);font-size:13px;align-items:center}
    .info .chip{background:#f3f4f6;padding:6px 8px;border-radius:999px;display:flex;align-items:center;gap:6px}
    .actions{display:flex;gap:12px;border-top:1px solid #eef2f7;padding-top:12px;margin-top:12px}
    .btn{flex:1;border-radius:8px;padding:8px 10px;text-align:center;cursor:pointer;border:1px solid #e6edf8;background:#fff;display:flex;justify-content:center;align-items:center;gap:6px;font-weight:500;color:#374151;transition:0.2s}
    .btn.liked{color:var(--accent);border-color:var(--accent);background:#eff6ff}
    .reactions{display:flex;align-items:center;gap:8px;color:var(--muted);font-size:13px;margin-top:6px}
    .reactions .count{background:#eef2ff;padding:6px 8px;border-radius:999px;color:var(--accent);font-weight:700}
    .comment{display:flex;gap:10px;align-items:flex-start;margin-top:12px}
    .comment textarea{flex:1;min-height:60px;border-radius:8px;padding:10px;border:1px solid #e6edf8;resize:vertical}
    .comment .profile-small{width:36px;height:36px;border-radius:50%;overflow:hidden}
    .comment .profile-small img{width:100%;height:100%;object-fit:cover}
    .comment .send{background:var(--accent);color:#fff;border:none;padding:10px 14px;border-radius:8px;cursor:pointer}
    .comment-list{margin-top:12px;display:flex;flex-direction:column;gap:10px;
                  max-height:250px;overflow-y:auto;padding-right:6px;}
    .single-comment{display:flex;gap:10px;align-items:flex-start;background:#f9fafb;padding:10px;border-radius:8px}
    .single-comment .profile-small{width:40px;height:40px;border-radius:50%;overflow:hidden}
    .single-comment .profile-small img{width:100%;height:100%;object-fit:cover}
  </style>
</head>
<body>
  <div class="container">
    <div class="card">
      <!-- Header -->
      <div class="header">
        <div class="avatar"><img src="https://randomuser.me/api/portraits/men/32.jpg" alt="avatar"></div>
        <div class="meta">
          <div class="name">Thanh Tùng <span style="color:var(--muted);font-weight:500"><i class="fa-regular fa-clock"></i> 2 giờ trước</span></div>
          <div class="time">Thị trường</div>
        </div>
        <button class="follow"><i class="fa-regular fa-eye"></i> Theo dõi</button>
        <div class="menu"><i class="fa-solid fa-ellipsis"></i></div>
      </div>

      <!-- Bài viết -->
      <div class="post-title">S&P 500 lần đầu vượt mốc 6.600 điểm</div>
      <div class="post-text">
        Thị trường chứng khoán Mỹ tăng điểm sau khi Tổng thống Mỹ Donald Trump cho biết các cuộc đàm phán thương mại Mỹ - Trung đang diễn ra "rất tốt". Giới đầu tư cũng đang hưởng lợi từ dự báo GDP của Fed tăng trong quý II...
      </div>
      <img src="https://picsum.photos/800/400" class="post-image" alt="post image">

      <div class="info" style="margin-bottom:8px">
        <div class="chip"><i class="fa-solid fa-newspaper"></i> Tin tức • Thị trường</div>
        <div class="chip"><i class="fa-regular fa-user"></i> 24 người theo dõi</div>
        <div style="margin-left:auto;color:var(--muted);font-size:13px"><i class="fa-regular fa-bookmark"></i> Lưu</div>
      </div>

      <!-- Reactions -->
      <div class="reactions">
        <div id="like-count" class="count">6</div>
        <div>Thích</div>
        <div>·</div>
        <div id="comment-total">1 Bình luận</div>
      </div>

      <!-- Buttons -->
      <div class="actions">
        <button id="like-btn" class="btn"><i class="fa-regular fa-thumbs-up"></i> Thích</button>
        <button id="comment-btn" class="btn"><i class="fa-regular fa-comment"></i> Bình luận</button>
      </div>

      <!-- Danh sách bình luận -->
      <div id="comment-list" class="comment-list">
        <div class="single-comment">
          <div class="profile-small"><img src="https://randomuser.me/api/portraits/women/44.jpg"></div>
          <div>
            <div style="font-weight:600">Long</div>
            <div style="color:var(--muted);font-size:14px">Bài viết rất hữu ích, cảm ơn bạn đã chia sẻ!</div>
          </div>
        </div>
      </div>

      <!-- Ô nhập bình luận -->
      <div class="comment" id="comment-section">
        <div class="profile-small"><img src="https://randomuser.me/api/portraits/men/12.jpg"></div>
        <textarea id="comment-input" placeholder="Viết bình luận..."></textarea>
        <div><button class="send" id="send-btn"><i class="fa-regular fa-paper-plane"></i> Gửi</button></div>
      </div>
    </div>
  </div>

  <script>
    const likeBtn = document.getElementById('like-btn');
    const likeCount = document.getElementById('like-count');
    const commentBtn = document.getElementById('comment-btn');
    const commentInput = document.getElementById('comment-input');
    const commentSection = document.getElementById('comment-section');
    const sendBtn = document.getElementById('send-btn');
    const list = document.getElementById('comment-list');
    const commentTotal = document.getElementById('comment-total');

    let liked = false;
    let totalComments = 1;

    const names = ["An","Bình","Cường","Dương","Hà","Lan","Minh","Ngọc","Phúc","Tùng"];
    const avatars = [
      "https://randomuser.me/api/portraits/men/11.jpg",
      "https://randomuser.me/api/portraits/women/12.jpg",
      "https://randomuser.me/api/portraits/men/13.jpg",
      "https://randomuser.me/api/portraits/women/14.jpg",
      "https://randomuser.me/api/portraits/men/15.jpg",
      "https://randomuser.me/api/portraits/women/16.jpg",
      "https://randomuser.me/api/portraits/men/17.jpg",
      "https://randomuser.me/api/portraits/women/18.jpg"
    ];

    // Xử lý Like
    likeBtn.addEventListener('click', () => {
      liked = !liked;
      likeBtn.classList.toggle('liked', liked);
      if(liked){
        likeBtn.innerHTML = '<i class="fa-solid fa-thumbs-up"></i> Đã thích';
        likeCount.textContent = parseInt(likeCount.textContent) + 1;
      } else {
        likeBtn.innerHTML = '<i class="fa-regular fa-thumbs-up"></i> Thích';
        likeCount.textContent = parseInt(likeCount.textContent) - 1;
      }
    });

    // Focus khi bấm Bình luận
    commentBtn.addEventListener('click', () => {
      commentInput.focus();
      commentSection.scrollIntoView({behavior:'smooth'});
    });

    // Gửi bình luận
    sendBtn.addEventListener('click', () => {
      const text = commentInput.value.trim();
      if(text !== ''){
        const randomName = names[Math.floor(Math.random()*names.length)];
        const randomAvatar = avatars[Math.floor(Math.random()*avatars.length)];

        const div = document.createElement('div');
        div.classList.add('single-comment');
        div.innerHTML = `
          <div class="profile-small"><img src="${randomAvatar}"></div>
          <div>
            <div style="font-weight:600">${randomName}</div>
            <div style="color:var(--muted);font-size:14px">${text}</div>
          </div>
        `;
        list.appendChild(div);
        commentInput.value = '';

        totalComments++;
        commentTotal.textContent = totalComments + " Bình luận";

        // Tự động cuộn xuống
        list.scrollTop = list.scrollHeight;
      }
    });
  </script>
</body>
</html>
