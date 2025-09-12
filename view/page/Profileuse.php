<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Mạng xã hội mini</title>
  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color: #f0f2f5; }

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
</head>
<body>

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

<!-- JavaScript -->
<script>
  // Load dữ liệu từ localStorage
  let postsData = JSON.parse(localStorage.getItem("postsData")) || [];

  function saveData() {
    localStorage.setItem("postsData", JSON.stringify(postsData));
  }

  // Render bài viết
  function renderPosts() {
    let postsDiv = document.getElementById("posts");
    postsDiv.innerHTML = "";
    postsData.forEach((post, index) => {
      let commentsHTML = post.comments.map(c => 
        `<div class="comment"><strong>${c.user}:</strong> ${c.text}</div>`).join("");

      let postHTML = `
        <div class="post-box">
          <div class="d-flex align-items-center mb-2">
            <img src="https://via.placeholder.com/40" class="rounded-circle me-2" width="40" height="40" alt="user">
            <div><strong>${post.user}</strong><br><small>${post.time}</small></div>
          </div>
          <p>${post.text}</p>
          ${post.image ? `<img src="${post.image}" class="post-img mb-2">` : ""}
          <div class="d-flex justify-content-between mb-2">
            <button class="btn btn-light btn-sm" onclick="likePost(${index}, this)">👍 Thích (<span class="like-count">${post.likes}</span>)</button>
            <button class="btn btn-light btn-sm" onclick="toggleComments(this)">💬 Bình luận</button>
            <button class="btn btn-light btn-sm" onclick="alert('Chia sẻ thành công!')">↗️ Chia sẻ</button>
          </div>
          <div class="comment-section d-none">
            <input type="text" class="form-control form-control-sm mb-2" placeholder="Viết bình luận..." 
                   onkeypress="submitComment(event,this,${index})">
            <div class="comments">${commentsHTML}</div>
          </div>
        </div>
      `;
      postsDiv.innerHTML += postHTML;
    });
  }

  // Thêm bài viết mới
  function addPost() {
    let text = document.getElementById("newPost").value.trim();
    let fileInput = document.getElementById("postImage");
    let file = fileInput.files[0];

    if (!text && !file) return;

    if (file) {
      let reader = new FileReader();
      reader.onload = function(e) {
        let newPost = {
          user: "Bạn",
          time: "Vừa xong",
          text: text,
          image: e.target.result,
          likes: 0,
          comments: []
        };
        postsData.unshift(newPost);
        saveData();
        renderPosts();
        document.getElementById("newPost").value = "";
        fileInput.value = "";
      };
      reader.readAsDataURL(file);
    } else {
      let newPost = {
        user: "Bạn",
        time: "Vừa xong",
        text: text,
        image: "",
        likes: 0,
        comments: []
      };
      postsData.unshift(newPost);
      saveData();
      renderPosts();
      document.getElementById("newPost").value = "";
    }
  }

  // Like
  function likePost(index, btn) {
    postsData[index].likes++;
    saveData();
    btn.querySelector(".like-count").textContent = postsData[index].likes;
  }

  // Toggle comment
  function toggleComments(btn) {
    let section = btn.closest(".post-box").querySelector(".comment-section");
    section.classList.toggle("d-none");
  }

  // Gửi comment
  function submitComment(event,input,index) {
    if (event.key === "Enter") {
      let text = input.value.trim();
      if (!text) return;
      postsData[index].comments.push({user:"Bạn", text:text});
      saveData();
      renderPosts();
    }
  }

  // Render khi load trang
  renderPosts();
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
