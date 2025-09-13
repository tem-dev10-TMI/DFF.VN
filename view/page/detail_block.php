<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Bài Post Tổng Hợp + Icon</title>
  <!-- Font Awesome để lấy icon -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f0f2f5;
      margin: 0;
      padding: 20px;
    }
    .post {
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      padding: 15px;
      max-width: 600px;
      margin: auto;
    }
    .post-header {
      display: flex;
      align-items: center;
      margin-bottom: 10px;
    }
    .avatar {
      width: 45px;
      height: 45px;
      border-radius: 50%;
      margin-right: 10px;
      object-fit: cover;
    }
    .author-info strong {
      display: block;
      font-size: 15px;
    }
    .author-info span {
      color: #777;
      font-size: 12px;
    }
    .post-title {
      font-weight: bold;
      font-size: 16px;
      margin: 10px 0;
    }
    .post-content {
      font-size: 14px;
      color: #333;
      margin-bottom: 10px;
      white-space: pre-line;
    }
    .post-image {
      width: 100%;
      border-radius: 10px;
      margin-bottom: 10px;
    }
    .related {
      background: #f9f9f9;
      border-left: 3px solid #0073e6;
      padding: 10px;
      margin-bottom: 10px;
    }
    .related p {
      margin: 5px 0;
      font-size: 14px;
    }
    .tags span {
      background: #eef3f8;
      color: #0073e6;
      padding: 3px 8px;
      border-radius: 15px;
      margin-right: 5px;
      font-size: 12px;
    }
    .reactions {
      display: flex;
      justify-content: space-around;
      font-size: 13px;
      color: #555;
      margin-top: 10px;
      border-top: 1px solid #eee;
      padding-top: 8px;
    }
    .reaction-btn {
      cursor: pointer;
      padding: 5px 10px;
      border-radius: 5px;
      transition: background 0.2s;
    }
    .reaction-btn:hover {
      background: #f0f0f0;
    }
    .comment-box {
      margin-top: 10px;
      border-top: 1px solid #eee;
      padding-top: 8px;
    }
    .comment {
      display: flex;
      margin-top: 8px;
    }
    .comment img {
      width: 30px;
      height: 30px;
      border-radius: 50%;
      margin-right: 8px;
    }
    .comment-content {
      background: #f0f2f5;
      padding: 6px 10px;
      border-radius: 12px;
      font-size: 13px;
    }
  </style>
</head>
<body>
  <div id="app"></div>

  <script>
    // ==== DỮ LIỆU JSON ====
    const post = {
      "postId": "z7004329174227",
      "author": {
        "id": "user_001",
        "name": "Thành Công",
        "profileUrl": "#",
        "avatarUrl": "https://via.placeholder.com/45",
        "role": "CEO WiGroup"
      },
      "createdAt": "2025-09-11T09:11:00+07:00",
      "title": "CEO WiGroup: Chênh lệch lãi suất VND – USD thu hẹp, áp lực tỷ giá sẽ hạ nhiệt trong một năm tới",
      "content": "Khoảng cách lãi suất giữa VND và USD đang dần thu hẹp, thặng dư thương mại được cải thiện và chính sách điều hành vàng có chuyển biến mới… là những yếu tố được kỳ vọng sẽ giúp giảm áp lực lên tỷ giá trong thời gian tới.\n\nHiện tỷ giá đã tăng khoảng 3,7–3,8% từ đầu năm, tức vẫn còn dư địa tăng thêm khoảng 1–2% nữa, khá hạn hẹp nếu xét về mức điều chỉnh tỷ giá trung bình hằng năm.",
      "media": [
        {
          "id": "media_001",
          "type": "image",
          "url": "https://via.placeholder.com/600x300",
          "alt": "CEO WiGroup phát biểu về tình hình tỷ giá VND - USD"
        }
      ],
      "relatedTopics": [
        { "id": "topic_001", "title": "Đồng USD trên thế giới giảm, song tỷ giá trong nước chưa hạ nhiệt", "url": "#" },
        { "id": "topic_002", "title": "Tỷ giá thêm nóng", "url": "#" },
        { "id": "topic_003", "title": "Chuyên gia: Áp lực tỷ giá có thể kéo dài đến năm 2026", "url": "#" }
      ],
      "tags": ["Trần Ngọc Bảo", "Tỷ giá", "USD"],
      "reactions": { "likes": 3, "shares": 6, "comments": 1 },
      "comments": [
        {
          "id": "cmt_001",
          "author": {
            "id": "user_002",
            "name": "Người dùng A",
            "avatarUrl": "https://via.placeholder.com/30"
          },
          "content": "Bài viết rất hay!",
          "createdAt": "2025-09-11T10:00:00+07:00",
          "likes": 2,
          "replies": []
        }
      ]
    };

    // ==== HIỂN THỊ RA HTML ====
    const app = document.getElementById("app");

    app.innerHTML = `
      <div class="post">
        <div class="post-header">
          <img src="${post.author.avatarUrl}" alt="avatar" class="avatar">
          <div class="author-info">
            <strong>${post.author.name}</strong>
            <span>${new Date(post.createdAt).toLocaleString("vi-VN")}</span>
          </div>
        </div>
        <div class="post-title">${post.title}</div>
        <div class="post-content">${post.content}</div>
        ${post.media.map(m => `<img src="${m.url}" alt="${m.alt}" class="post-image">`).join("")}
        <div class="related">
          <strong><i class="fa-solid fa-link"></i> Chủ đề liên quan:</strong>
          ${post.relatedTopics.map(t => `<p>🔹 <a href="${t.url}">${t.title}</a></p>`).join("")}
        </div>
        <div class="tags">
          <i class="fa-solid fa-tags"></i> 
          ${post.tags.map(tag => `<span>${tag}</span>`).join("")}
        </div>
        <div class="reactions">
          <div class="reaction-btn"><i class="fa-solid fa-thumbs-up"></i> ${post.reactions.likes}</div>
          <div class="reaction-btn"><i class="fa-solid fa-share"></i> ${post.reactions.shares} Chia sẻ</div>
          <div class="reaction-btn"><i class="fa-solid fa-comment"></i> ${post.reactions.comments} Bình luận</div>
        </div>
        <div class="comment-box">
          <strong><i class="fa-solid fa-comments"></i> Bình luận:</strong>
          ${post.comments.map(c => `
            <div class="comment">
              <img src="${c.author.avatarUrl}" alt="${c.author.name}">
              <div class="comment-content">
                <strong>${c.author.name}</strong><br>
                ${c.content}
              </div>
            </div>
          `).join("")}
        </div>
      </div>
    `;
  </script>
</body>
</html>
