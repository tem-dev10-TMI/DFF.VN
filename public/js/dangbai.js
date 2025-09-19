
function convertToBusiness() {
  // Hiển thị modal xác nhận
  var convertModal = new bootstrap.Modal(document.getElementById('convertModal'));
  convertModal.show();

  // Đảm bảo modal có thể scroll
  setTimeout(function () {
    var modalBody = document.querySelector('#convertModal .modal-body');
    if (modalBody) {
      modalBody.style.maxHeight = 'calc(90vh - 140px)';
      modalBody.style.overflowY = 'auto';
    }
  }, 100);
}

function submitConversion() {
  var form = document.getElementById('convertForm');
  var formData = new FormData(form);

  // Check đã tick đồng ý chưa
  var agreeTerms = document.getElementById('agreeTerms').checked;
  if (!agreeTerms) {
    showNotification('Vui lòng đồng ý với điều khoản sử dụng!', 'warning');
    return;
  }

  // Loading button
  var submitBtn = event.target;
  var originalText = submitBtn.innerHTML;
  submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Đang xử lý...';
  submitBtn.disabled = true;

  // Gửi request đến PHP xử lý
  fetch('businessman_register.php', {
    method: 'POST',
    body: formData
  })
    .then(res => res.json())
    .then(data => {
      submitBtn.innerHTML = originalText;
      submitBtn.disabled = false;

      if (data.success) {
        showNotification('Đăng ký doanh nhân thành công, vui lòng chờ admin duyệt!', 'success');
        var convertModal = bootstrap.Modal.getInstance(document.getElementById('convertModal'));
        convertModal.hide();
        form.reset();
        window.location.reload();
      } else {
        showNotification(data.message || 'Đăng ký thất bại!', 'danger');
      }
    })
    .catch(err => {
      submitBtn.innerHTML = originalText;
      submitBtn.disabled = false;
      showNotification('Lỗi kết nối tới server!', 'danger');
      console.error(err);
    });
}


// Load bài viết từ PHP
function loadPosts() {
  // Lấy thẻ div chứa dữ liệu từ PHP
  const profileDataElement = document.getElementById('profileData');

  // Nếu không tìm thấy thẻ div này, dừng lại và báo lỗi để tránh sự cố
  if (!profileDataElement) {
    console.error('Lỗi nghiêm trọng: Không tìm thấy thẻ div#profileData. Không thể tải bài viết.');
    // Bạn có thể hiển thị một thông báo lỗi cho người dùng ở đây
    const postsContainer = document.getElementById('posts');
    if (postsContainer) {
      postsContainer.innerHTML = '<p class="text-danger text-center">Lỗi cấu hình trang. Không thể tải dữ liệu.</p>';
    }
    return;
  }

  // Đọc dữ liệu từ các thuộc tính data-*
  const profileCategory = profileDataElement.dataset.category;
  const userId = profileDataElement.dataset.userId;

  // Hiển thị loading indicator
  const loadingElement = document.getElementById('loadingPosts');
  if (loadingElement) {
    loadingElement.style.display = 'block';
  }

  fetch('api/loadPost', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    // Sử dụng dữ liệu đã đọc từ thẻ div
    body: JSON.stringify({
      profile_category: profileCategory,
      user_id: userId
    })
  })
    .then(response => response.json())
    .then(data => {
      // Ẩn loading indicator
      if (loadingElement) {
        loadingElement.style.display = 'none';
      }

      if (data && (data.success === true || typeof data.success === 'undefined')) {
        const posts = Array.isArray(data.posts) ? data.posts : (data.data && Array.isArray(data.data.posts) ? data.data.posts : []);
        // Giả sử bạn có hàm displayPosts(posts) để hiển thị bài viết
        displayPosts(posts);
      } else {
        console.error('Lỗi load bài viết:', data.message);
        // Hiển thị thông báo lỗi
        const postsContainer = document.getElementById('posts');
        postsContainer.innerHTML = `
          <div class="block-k">
            <div class="view-carde f-frame">
              <div class="text-center p-4">
                <p class="text-danger">Không thể tải bài viết. Vui lòng thử lại sau!</p>
                <button class="btn btn-outline-primary btn-sm" onclick="loadPosts()">
                  <i class="fas fa-refresh me-1"></i> Thử lại
                </button>
              </div>
            </div>
          </div>
        `;
      }
    })
    .catch(error => {
      // Ẩn loading indicator
      if (loadingElement) {
        loadingElement.style.display = 'none';
      }

      console.error('Lỗi fetch:', error);
      // Hiển thị thông báo lỗi
      const postsContainer = document.getElementById('posts');
      postsContainer.innerHTML = `
      <div class="block-k">
        <div class="view-carde f-frame">
          <div class="text-center p-4">
            <p class="text-danger">Có lỗi xảy ra khi tải bài viết!</p>
            <button class="btn btn-outline-primary btn-sm" onclick="loadPosts()">
              <i class="fas fa-refresh me-1"></i> Thử lại
            </button>
          </div>
        </div>
      </div>
    `;
    });
}

// Hiển thị danh sách bài viết
function displayPosts(posts) {
  var postsContainer = document.getElementById('posts');
  postsContainer.innerHTML = '';

  if (posts.length === 0) {
    postsContainer.innerHTML = `
      <div class="block-k">
        <div class="view-carde f-frame">
          <div class="text-center p-4">
            <p>Chưa có bài viết nào. Hãy tạo bài viết đầu tiên!</p>
          </div>
        </div>
      </div>
    `;
    return;
  }

  posts.forEach(function (post) {
    var postElement = createPostElement(post);
    postsContainer.appendChild(postElement);
  });
}

// Tạo element bài viết theo cấu trúc Home


// Hàm xóa bài viết mới
function deletePost(postId, buttonElement) {
  if (!confirm('Bạn có chắc chắn muốn xóa bài viết này không? Hành động này không thể hoàn tác.')) {
    return;
  }

  // Tìm phần tử cha để xóa khỏi giao diện
  const postElement = buttonElement.closest('.block-k');

  fetch('/api/delete_post.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
    },
    body: `post_id=${postId}`
  })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        // Xóa bài viết khỏi giao diện một cách mượt mà
        postElement.style.transition = 'opacity 0.5s ease';
        postElement.style.opacity = '0';
        setTimeout(() => {
          postElement.remove();
        }, 500);
        // Bạn có thể thêm thông báo thành công ở đây, ví dụ: alert('Đã xóa bài viết thành công!');
      } else {
        alert('Lỗi: ' + data.message);
      }
    })
    .catch(error => {
      console.error('Error:', error);
      alert('Đã xảy ra lỗi kết nối. Vui lòng thử lại.');
    });
}


function createPostElement(post) {
  const profileDataElement = document.getElementById('profileData');

  // Nếu không tìm thấy thẻ div này, dừng lại và báo lỗi để tránh sự cố
  if (!profileDataElement) {
    console.error('Lỗi nghiêm trọng: Không tìm thấy thẻ div#profileData. Không thể tải bài viết.');
    const postsContainer = document.getElementById('posts');
    if (postsContainer) {
      postsContainer.innerHTML = '<p class="text-danger text-center">Lỗi cấu hình trang. Không thể tải dữ liệu.</p>';
    }
    return document.createElement('div'); // Trả về một element rỗng để không làm crash vòng lặp
  }

  const userId = profileDataElement.dataset.userId;

  var postDiv = document.createElement('div');
  postDiv.className = 'block-k';
  postDiv.setAttribute('id', `post-${post.id}`);

  // --- START FIX ---
  // Đảm bảo post.content là một chuỗi để tránh lỗi
  const safeContent = post.content || '';
  const safeTitle = post.title || safeContent.substring(0, 70);
  // --- END FIX ---

  const deleteButtonHtml = (userId && post.author_id == userId)
    ? `<div class="post-actions">
           <span class="delete-post-btn" onclick="deletePost(${post.id}, this)" title="Xóa bài viết">
               <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                   <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                   <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
               </svg>
           </span>
       </div>`
    : '';

  postDiv.innerHTML = `
    <div class="view-carde f-frame">
      <div class="provider">
        <img class="logo" alt="Avatar" src="${post.avatar || 'https://i.pinimg.com/1200x/83/0e/ea/830eea38f7a5d3d8e390ba560d14f39c.jpg'}">
        <div class="p-covers">
          <span class="badge ${renderStatusBadgeClass(post.status)}" style="margin-right:6px;" title="${post.review_reason ? escapeHtml(post.review_reason) : ''}">${renderStatusText(post.status)}</span>
          <span class="name" title="${escapeHtml(post.author_name)}">
            <a href="/profile.html?q=${post.author_id || post.user_id}" title="${escapeHtml(post.author_name)}">${escapeHtml(post.author_name)}</a>
          </span>
          <span class="date">${post.time_ago || ''}</span>
        </div>
        ${deleteButtonHtml}
      </div>

      <div class="title">
        <a title="${escapeHtml(safeTitle)}" href="/post-${post.id}.html">${escapeHtml(safeTitle)}</a>
      </div>

      ${post.status && (post.status.toLowerCase() === 'rejected' || post.status.toLowerCase() === 'reject') && post.review_reason ? `
      <div class="mt-1 mb-2">
        <div class="alert alert-danger py-1 px-2 m-0">
          <small><strong>Lý do:</strong> ${escapeHtml(post.review_reason)}</small>
        </div>
      </div>
      ` : ''}

      <div class="sapo">
        ${safeContent}
        ${(safeContent && safeContent.length > 100) ? `<a href="/post-${post.id}.html" class="d-more">Xem thêm</a>` : ''}
      </div>

      ${post.video_url ? `
      <div class="mt-2 mb-2">
        <video controls style="width: 100%; border-radius: 8px; background-color: #000;">
          <source src="${post.video_url}" type="video/mp4">
          Trình duyệt của bạn không hỗ trợ thẻ video.
        </video>
      </div>
      ` : ''}

      ${post.image ? `<img class="h-img" src="${post.image}" title="${escapeHtml(safeTitle)}" alt="${escapeHtml(safeTitle)}" border="0">` : ''}

      <div class="item-bottom">
        <div class="bt-cover com-like" data-id="${post.id}">
            <span class="for-up" onclick="toggleLike(${post.id})"><svg rpl="" data-voted="false" data-type="up" fill="currentColor" height="16" icon-name="upvote-fill" viewBox="0 0 20 20" width="16" xmlns="http://www.w3.org/2000/svg"><path d="M18.706 8.953 10.834.372A1.123 1.123 0 0 0 10 0a1.128 1.128 0 0 0-.833.368L1.29 8.957a1.249 1.249 0 0 0-.171 1.343 1.114 1.114 0 0 0 1.007.7H6v6.877A1.125 1.125 0 0 0 7.123 19h5.754A1.125 1.125 0 0 0 14 17.877V11h3.877a1.114 1.114 0 0 0 1.005-.7 1.251 1.251 0 0 0-.176-1.347Z"></path></svg></span>
            <span class="value" data-old="${post.likes_count || 0}">${post.likes_count || 0}</span>
            <span class="for-down" onclick="toggleDislike(${post.id})"><svg rpl="" data-voted="false" data-type="down" fill="currentColor" height="16" icon-name="downvote-fill" viewBox="0 0 20 20" width="16" xmlns="http://www.w3.org/2000/svg"><path d="M18.88 9.7a1.114 1.114 0 0 0-1.006-.7H14V2.123A1.125 1.125 0 0 0 12.877 1H7.123A1.125 1.125 0 0 0 6 2.123V9H2.123a1.114 1.114 0 0 0-1.005.7 1.25 1.25 0 0 0 .176 1.348l7.872 8.581a1.124 1.124 0 0 0 1.667.003l7.876-8.589A1.248 1.248 0 0 0 18.88 9.7Z"></path></svg></span>
        </div>
        <div class="button-ar">
            <a href="/post-${post.id}.html#anc_comment" onclick="showComments(${post.id})"><svg rpl="" aria-hidden="true" class="icon-comment" fill="currentColor" height="15" icon-name="comment-outline" viewBox="0 0 20 20" width="15" xmlns="http://www.w3.org/2000/svg"><path d="M7.725 19.872a.718.718 0 0 1-.607-.328.725.725 0 0 1-.118-.397V16H3.625A2.63 2.63 0 0 1 1 13.375v-9.75A2.629 2.629 0 0 1 3.625 1h12.75A2.63 2.63 0 0 1 19 3.625v9.75A2.63 2.63 0 0 1 16.375 16h-4.161l-4 3.681a.725.725 0 0 1-.489.191ZM3.625 2.25A1.377 1.377 0 0 0 2.25 3.625v9.75a1.377 1.377 0 0 0 1.375 1.375h4a.625.625 0 0 1 .625.625v2.575l3.3-3.035a.628.628 0 0 1 .424-.165h4.4a1.377 1.377 0 0 0 1.375-1.375v-9.75a1.377 1.377 0 0 0-1.374-1.375H3.625Z"></path></svg>
            <span>${post.comments_count || 0}</span></a>
        </div>
        <div class="button-ar">
            <div class="dropdown home-item">
                <i class="far fa-share-square"></i><span data-bs-toggle="dropdown" aria-expanded="false">Chia sẻ</span>
                <ul class="dropdown-menu">
                    <li><i class="bi bi-link-45deg"></i> <a class="dropdown-item copylink" data-url="/post-${post.id}.html" href="javascript:void(0)">Copy link</a></li>
                    <li><i class="bi bi-facebook"></i> <a class="dropdown-item sharefb" data-url="/post-${post.id}.html" href="javascript:void(0)">Share FB</a></li>
                </ul>
            </div>
        </div>
      </div>
    </div>
  `;
  return postDiv;
}

function renderStatusText(status) {
  var s = (status || '').toLowerCase();
  if (s === 'public' || s === 'published') return 'Công khai';
  if (s === 'pending' || s === 'review') return 'Chờ duyệt';
  if (s === 'rejected' || s === 'reject') return 'Rejected';
  if (s === 'draft') return 'Nháp';
  if (s === 'private') return 'Riêng tư';
  return status || 'Chờ duyệt';
}

function renderStatusBadgeClass(status) {
  var s = (status || '').toLowerCase();
  if (s === 'public' || s === 'published') return 'bg-success';
  if (s === 'pending' || s === 'review') return 'bg-warning text-dark';
  if (s === 'rejected' || s === 'reject') return 'bg-danger';
  if (s === 'draft') return 'bg-secondary';
  if (s === 'private') return 'bg-dark';
  return 'bg-warning text-dark';
}

function escapeHtml(str) {
  if (!str) return '';
  return String(str)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#039;');
}

// Submit bài viết mới
function addPost() {
  var postTitle = document.getElementById('postTitle').value.trim();
  var postSummary = document.getElementById('postSummary').value.trim();
  var postContent = document.getElementById('newPost').value.trim();
  var postTopic = document.getElementById('topicSelect').value;


  if (!postTitle || !postContent || !postTopic) {
    showNotification('Vui lòng nhập tiêu đề, nội dung và chọn chủ đề!', 'warning');
    return;
  }

  var submitBtn = document.querySelector('.post-box .btn-primary');
  var originalText = submitBtn.innerHTML;
  submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Đang đăng...';
  submitBtn.disabled = true;

  var formData = new FormData();
  formData.append('title', postTitle);
  formData.append('summary', postSummary);
  formData.append('content', postContent);
  formData.append('topic_id', postTopic); // tạm fix cứng, hoặc để user chọn

  var imageFile = document.getElementById('postImage').files[0];
  if (imageFile) {
    formData.append('main_image_url', imageFile);
  }

  var videoFile = document.getElementById('postVideo').files[0];
  if (videoFile) {
    formData.append('post_video', videoFile);
  }

  fetch('api/addPost', {
    method: 'POST',
    body: formData
  })
    .then(response => response.json())
    .then(data => {
      submitBtn.innerHTML = originalText;
      submitBtn.disabled = false;

      if (data && data.success) {
        document.getElementById('postTitle').value = '';
        document.getElementById('postSummary').value = '';
        document.getElementById('newPost').value = '';
        document.getElementById('topicSelect').value = '';
        document.getElementById('postImage').value = '';
        document.getElementById('imagePreview').innerHTML = '';

        // Refresh danh sách bài viết
        loadPosts();
        showNotification(data.message || 'Đăng bài thành công!', 'success');
      } else {
        showNotification('Lỗi: ' + (data && data.message ? data.message : 'Không xác định'), 'danger');
      }
    })
    .catch(error => {
      submitBtn.innerHTML = originalText;
      submitBtn.disabled = false;
      console.error("Fetch error:", error);
      showNotification("Có lỗi xảy ra khi gửi request!", "danger");
    });
}

// Xem trước ảnh trước khi đăng
function previewImage(event) {
  const preview = document.getElementById('imagePreview');
  preview.innerHTML = ''; // Xóa preview cũ

  const file = event.target.files[0];
  if (file) {
    const img = document.createElement('img');
    img.src = URL.createObjectURL(file);
    img.classList.add('img-fluid', 'rounded', 'mt-2');
    img.style.maxHeight = '200px';
    preview.appendChild(img);
  }
}

// Hiển thị tên video đã chọn
function previewVideo(event) {
  const preview = document.getElementById('videoPreview');
  preview.innerHTML = ''; // Xóa preview cũ

  const file = event.target.files[0];
  if (file) {
    const fileNameDiv = document.createElement('div');
    fileNameDiv.classList.add('alert', 'alert-info', 'py-2', 'mt-2');
    fileNameDiv.innerHTML = `
        <i class="fas fa-video me-2"></i>
        Đã chọn video: <strong>${file.name}</strong>
        <button type="button" class="btn-close" onclick="clearVideoPreview()" style="font-size: 0.75rem; float: right;"></button>
      `;
    preview.appendChild(fileNameDiv);
  }
}

function clearVideoPreview() {
  document.getElementById('postVideo').value = ''; // Xóa file đã chọn
  document.getElementById('videoPreview').innerHTML = ''; // Xóa hiển thị
}

// Like/Unlike bài viết
function toggleLike(postId) {
  fetch('controller/test-api-profile/toggleLike.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({
      post_id: postId,
      user_id: '<?php echo isset($user_id) ? $user_id : 0; ?>',
      action: 'like'
    })
  })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        // Reload bài viết để cập nhật số like
        loadPosts();
      } else {
        console.error('Lỗi like:', data.message);
      }
    })
    .catch(error => {
      console.error('Lỗi fetch like:', error);
    });
}

// Dislike bài viết
function toggleDislike(postId) {
  fetch('controller/test-api-profile/toggleLike.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({
      post_id: postId,
      user_id: '<?php echo isset($user_id) ? $user_id : 0; ?>',
      action: 'dislike'
    })
  })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        // Reload bài viết để cập nhật số like
        loadPosts();
      } else {
        console.error('Lỗi dislike:', data.message);
      }
    })
    .catch(error => {
      console.error('Lỗi fetch dislike:', error);
    });
}

// Hiển thị comment
function showComments(postId) {
  // TODO: Implement comment modal
  console.log('Show comments for post:', postId);
}

// Chia sẻ bài viết
function sharePost(postId) {
  // TODO: Implement share functionality
  console.log('Share post:', postId);
}

// Hiển thị thông báo
function showNotification(message, type = 'info') {
  // Tạo element thông báo
  var notification = document.createElement('div');
  notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
  notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
  notification.innerHTML = `
    ${message}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  `;

  document.body.appendChild(notification);

  // Tự động ẩn sau 3 giây
  setTimeout(function () {
    if (notification.parentNode) {
      notification.parentNode.removeChild(notification);
    }
  }, 3000);
}

// Đảm bảo modal scroll được khi mở và auto-load bài viết
document.addEventListener('DOMContentLoaded', function () {
  // Auto-load bài viết khi trang load
  const profileDataElement = document.getElementById('profileData');
  if(!profileDataElement){
    return;
  }
  loadPosts();

  // Modal scroll setup
  var convertModal = document.getElementById('convertModal');
  if (convertModal) {
    convertModal.addEventListener('shown.bs.modal', function () {
      var modalBody = this.querySelector('.modal-body');
      if (modalBody) {
        modalBody.style.maxHeight = 'calc(90vh - 140px)';
        modalBody.style.overflowY = 'auto';
        modalBody.style.overflowX = 'hidden';
      }
    });
  }
});
