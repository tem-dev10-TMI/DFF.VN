<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bình luận</title>
  <!-- Font Awesome để có icon giống hình -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f4f6f9;
      margin: 0;
      padding: 20px;
    }

    .comment-box {
      width: 350px;
      background: #fff;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    .comment-header {
      background: #0d47a1;
      color: #fff;
      font-weight: bold;
      padding: 12px 16px;
      font-size: 18px;
    }

    .comment-list {
      max-height: 300px; 
      overflow-y: auto;
      padding: 10px 0;
    }

    .comment {
      display: flex;
      align-items: flex-start;
      padding: 10px 16px;
      border-bottom: 1px solid #eee;
    }

    .avatar {
      width: 32px;
      height: 32px;
      border-radius: 50%;
      background: #0d47a1;
      color: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
      margin-right: 12px;
    }

    .comment-content {
      flex: 1;
    }

    .comment-name {
      font-weight: bold;
      font-size: 14px;
    }

    .comment-time {
      font-size: 12px;
      color: #666;
      margin-left: 6px;
    }

    .comment-text {
      margin: 4px 0;
      font-size: 14px;
      line-height: 1.4;
    }

    .comment-actions {
      display: flex;
      align-items: center;
      font-size: 13px;
      color: #0d47a1;
      gap: 15px;
      margin-top: 4px;
    }

    .vote {
      display: flex;
      align-items: center;
      gap: 6px;
      color: #333;
    }

    .vote button {
      background: none;
      border: none;
      cursor: pointer;
      font-size: 16px;
      color: #555;
    }

    .vote span {
      font-size: 13px;
      color: #000;
    }

    .comment-footer {
      display: flex;
      align-items: center;
      border-top: 1px solid #ddd;
      padding: 10px;
      background: #f9f9f9;
    }

    .comment-footer input {
      flex: 1;
      border: none;
      outline: none;
      font-size: 14px;
      padding: 8px;
      border-radius: 20px;
      background: #fff;
      border: 1px solid #ddd;
    }

    .comment-footer button {
      background: #0d47a1;
      border: none;
      color: white;
      padding: 8px 12px;
      margin-left: 8px;
      border-radius: 50%;
      cursor: pointer;
      font-size: 16px;
    }
  </style>
</head>
<body>

  <div class="comment-box">
    <!-- Header -->
    <div class="comment-header">💬 Hi! DFF</div>

    <!-- Danh sách bình luận -->
    <div class="comment-list" id="commentList">
      <div class="comment">
        <div class="avatar">C</div>
        <div class="comment-content">
          <div>
            <span class="comment-name">Việt Chung</span>
            <span class="comment-time">1 giờ trước</span>
          </div>
          <div class="comment-text">
            Nếu nâng hạng thì cứ bank, chứng, thép mà múc thôi @@
          </div>
          <div class="comment-actions">
            <div class="vote">
              <button><i class="fa-regular fa-circle-up"></i></button>
              <span>0</span>
              <button><i class="fa-regular fa-circle-down"></i></button>
            </div>
            <span>Trả lời</span>
          </div>
        </div>
      </div>

      <div class="comment">
        <div class="avatar" style="background:#1565c0;">H</div>
        <div class="comment-content">
          <div>
            <span class="comment-name">Phạm Hải</span>
            <span class="comment-time">1 giờ trước</span>
          </div>
          <div class="comment-text">
            BTC, UBCK đang có chuyến công tác tại Anh, trong chương trình làm việc có gặp FTSE Russell. Hy vọng có tin vui...
          </div>
          <div class="comment-actions">
            <div class="vote">
              <button><i class="fa-regular fa-circle-up"></i></button>
              <span>0</span>
              <button><i class="fa-regular fa-circle-down"></i></button>
            </div>
            <span>Trả lời</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Footer nhập bình luận -->
    <div class="comment-footer">
      <input type="text" id="commentInput" placeholder="Viết bình luận...">
      <button id="sendBtn"><i class="fa-solid fa-paper-plane"></i></button>
    </div>
  </div>

  <script>
    const sendBtn = document.getElementById("sendBtn");
    const commentInput = document.getElementById("commentInput");
    const commentList = document.getElementById("commentList");

    sendBtn.addEventListener("click", () => {
      const text = commentInput.value.trim();
      if (text !== "") {
        // Tạo phần tử bình luận mới
        const comment = document.createElement("div");
        comment.classList.add("comment");
        
        comment.innerHTML = `
          <div class="avatar">U</div>
          <div class="comment-content">
            <div>
              <span class="comment-name">Bạn</span>
              <span class="comment-time">vừa xong</span>
            </div>
            <div class="comment-text">${text}</div>
            <div class="comment-actions">
              <div class="vote">
                <button><i class="fa-regular fa-circle-up"></i></button>
                <span>0</span>
                <button><i class="fa-regular fa-circle-down"></i></button>
              </div>
              <span>Trả lời</span>
            </div>
          </div>
        `;
        
        // Thêm vào đầu danh sách bình luận
        commentList.prepend(comment);

        // Xóa nội dung trong input
        commentInput.value = "";

        // Cuộn lên trên để thấy bình luận mới
        commentList.scrollTop = 0;
      }
    });
  </script>

</body>
</html>
