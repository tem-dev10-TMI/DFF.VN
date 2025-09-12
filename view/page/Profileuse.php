
<style>
    

    .cover { 
      height: 200px; 
      background-color: #d9d9d9; 
      position: relative; 
    }

    .avatar { 
      width: 120px; 
      height: 120px; 
      border-radius: 50%; 
      border: 4px solid white;
      position: absolute; 
      bottom: -60px; 
      left: 50%; 
      transform: translateX(-50%);
      background-color: #fff; 
    }

    .sidebar { 
      background: white; 
      border-radius: 8px; 
      padding: 15px; 
      margin-bottom: 15px; 
    }

    .post-box { 
      background: white; 
      border-radius: 8px; 
      padding: 15px; 
      margin-bottom: 20px; 
    }

    .post-img { 
      max-width: 100%; 
      border-radius: 8px; 
      margin-top: 10px;
    }

    .comment { 
      font-size: 14px; 
      margin-top: 5px; 
    }
  </style>
<div class="container mt-3">
  <!-- Cover -->
  <div class="cover">
    <img src="https://via.placeholder.com/120" class="avatar" alt="avatar">
  </div>
  <div class="mt-5"></div>

  <div class="row mt-5">
    <!-- Sidebar -->
    <div class="col-md-3">
      <div class="sidebar">
        <h6><strong>Giới thiệu</strong></h6>
        <p>📍 Làm việc tại...</p>
        <p>📚 Từng học tại...</p>
        <p>🏠 Sống tại...</p>
      </div>
      <div class="sidebar">
        <p>📝 Bài viết mới</p>
        <p>📤 Bài đã xuất bản</p>
        <p>⏳ Bài chờ biên tập</p>
        <p>📌 Bài đã lưu tạm</p>
      </div>
    </div>

    <!-- Main content -->
    <div class="col-md-9">
      <!-- Write post -->
      <div class="post-box mb-3">
        <textarea id="newPost" class="form-control mb-2" placeholder="Viết bài, chia sẻ, đặt câu hỏi..."></textarea>
        <input type="file" id="postImage" class="form-control mb-2" accept="image/*">
        <button class="btn btn-primary btn-sm" onclick="addPost()">Đăng</button>
      </div>

      <!-- Posts -->
      <div id="posts"></div>
    </div>
  </div>
</div>