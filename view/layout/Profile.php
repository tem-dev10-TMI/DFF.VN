
<style>
    .cover { 
      height: 200px; 
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      position: relative; 
      border-radius: 8px;
      margin-bottom: 80px;
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
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .sidebar { 
      background: white; 
      border-radius: 8px; 
      padding: 20px; 
      margin-bottom: 15px; 
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .post-box { 
      background: white; 
      border-radius: 8px; 
      padding: 20px; 
      margin-bottom: 20px; 
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
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

    /* CSS cho Profile Business */
    .business-info .info-item strong {
      color: #124889;
      font-size: 14px;
    }

    .business-info .info-item span {
      font-size: 13px;
    }

    .business-stats .stat-item {
      font-size: 14px;
    }

    .business-certificates .cert-item {
      font-size: 13px;
    }

    .business-actions .btn {
      font-size: 13px;
    }

    /* CSS cho Profile User */
    .user-info .info-item strong {
      color: #124889;
      font-size: 14px;
    }

    .user-info .info-item span {
      font-size: 13px;
    }

    .user-stats .stat-item {
      font-size: 14px;
    }

    .interest-tags .badge {
      font-size: 12px;
    }

    .user-actions .btn {
      font-size: 13px;
    }

    .user-actions .btn-warning {
      background-color: #ffc107;
      border-color: #ffc107;
      color: #000;
      font-weight: 500;
    }

    .user-actions .btn-warning:hover {
      background-color: #e0a800;
      border-color: #d39e00;
      color: #000;
    }

    /* Cải thiện khu vực tạo bài viết */
    .post-box textarea {
      border: 1px solid #e0e0e0;
      border-radius: 6px;
      resize: vertical;
      min-height: 100px;
    }

    .post-box textarea:focus {
      border-color: #124889;
      box-shadow: 0 0 0 0.2rem rgba(18, 72, 137, 0.25);
    }

    .post-box .btn-primary {
      background-color: #124889;
      border-color: #124889;
    }

    .post-box .btn-primary:hover {
      background-color: #0d3a6b;
      border-color: #0d3a6b;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .sidebar {
        margin-bottom: 20px;
      }
    }

    /* Modal responsive và scrollable */
    .modal-dialog {
      max-height: 90vh;
      margin: 1.75rem auto;
      overflow: hidden;
    }

    .modal-content {
      max-height: 90vh;
      display: flex;
      flex-direction: column;
      overflow: hidden;
    }

    .modal-body {
      flex: 1;
      overflow-y: auto;
      padding: 1rem;
      max-height: calc(90vh - 140px);
    }

    .modal-header {
      flex-shrink: 0;
      border-bottom: 1px solid #dee2e6;
    }

    .modal-footer {
      flex-shrink: 0;
      border-top: 1px solid #dee2e6;
    }

    /* Responsive cho modal */
    @media (max-width: 768px) {
      .modal-dialog {
        max-height: 95vh;
        margin: 0.5rem;
      }
      
      .modal-content {
        max-height: 95vh;
      }
      
      .modal-body {
        max-height: calc(95vh - 120px);
        padding: 0.75rem;
      }
    }

    /* Đảm bảo modal có thể scroll */
    .modal.show .modal-dialog {
      transform: none;
    }

    .modal-body {
      -webkit-overflow-scrolling: touch;
    }

    /* Cải thiện scrollbar cho modal */
    .modal-body::-webkit-scrollbar {
      width: 6px;
    }

    .modal-body::-webkit-scrollbar-track {
      background: #f1f1f1;
      border-radius: 3px;
    }

    .modal-body::-webkit-scrollbar-thumb {
      background: #c1c1c1;
      border-radius: 3px;
    }

    .modal-body::-webkit-scrollbar-thumb:hover {
      background: #a8a8a8;
    }

    /* Force modal to be scrollable */
    .modal {
      overflow-y: auto;
    }

    .modal-dialog {
      position: relative;
      width: auto;
      margin: 0.5rem;
      pointer-events: none;
    }

    .modal-content {
      position: relative;
      display: flex;
      flex-direction: column;
      width: 100%;
      pointer-events: auto;
      background-color: #fff;
      background-clip: padding-box;
      border: 1px solid rgba(0,0,0,.2);
      border-radius: 0.3rem;
      outline: 0;
    }

    /* Đảm bảo modal body có thể scroll */
    .modal-body {
      position: relative;
      flex: 1 1 auto;
      padding: 1rem;
      overflow-y: auto;
      overflow-x: hidden;
    }

    /* Fix cho Bootstrap modal */
    @media (min-width: 576px) {
      .modal-dialog {
        max-width: 800px;
        margin: 1.75rem auto;
      }
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

    <?php if($profile_category=='user' ){ require_once 'view/page/Profileuser.php'; } ?>
    <?php if($profile_category=='businessmen'){ require_once 'view/page/ProfileBusiness.php'; } ?>
    <!-- Main content -->
    <div class="col-md-9">
      <!-- Write post -->
      <div class="post-box mb-3">
        <div class="d-flex align-items-center mb-3">
          <img src="https://via.placeholder.com/40" class="rounded-circle me-2" alt="avatar" style="width: 40px; height: 40px;">
          <div>
            <h6 class="mb-0"><?php echo $profile_category == 'businessmen' ? 'CTCP Tài chính số' : 'Nguyễn Văn A'; ?></h6>
            <small class="text-muted"><?php echo $profile_category == 'businessmen' ? 'Doanh nghiệp' : 'Cá nhân'; ?></small>
          </div>
        </div>
        <textarea id="newPost" class="form-control mb-3" placeholder="Viết bài, chia sẻ, đặt câu hỏi..."></textarea>
        <div class="d-flex justify-content-between align-items-center">
          <div class="d-flex gap-2">
            <label class="btn btn-outline-secondary btn-sm mb-0" for="postImage">
              <i class="fas fa-image me-1"></i> Hình ảnh
            </label>
            <label class="btn btn-outline-secondary btn-sm mb-0" for="postVideo">
              <i class="fas fa-video me-1"></i> Video
            </label>
            <button class="btn btn-outline-secondary btn-sm">
              <i class="fas fa-link me-1"></i> Link
            </button>
          </div>
          <button class="btn btn-primary" onclick="addPost()">
            <i class="fas fa-paper-plane me-1"></i> Đăng bài
          </button>
        </div>
        <input type="file" id="postImage" class="d-none" accept="image/*">
        <input type="file" id="postVideo" class="d-none" accept="video/*">
      </div>

      <!-- Posts -->
      <div id="posts">
        <!-- Sample posts -->
        <div class="post-box mb-3">
          <div class="d-flex align-items-center mb-3">
            <img src="https://via.placeholder.com/40" class="rounded-circle me-2" alt="avatar" style="width: 40px; height: 40px;">
            <div>
              <h6 class="mb-0"><?php echo $profile_category == 'businessmen' ? 'CTCP Tài chính số' : 'Nguyễn Văn A'; ?></h6>
              <small class="text-muted">2 giờ trước</small>
            </div>
          </div>
          <p>Thị trường tài chính hôm nay có nhiều biến động tích cực. Các chỉ số chính đều tăng trưởng mạnh mẽ...</p>
          <div class="post-actions d-flex justify-content-between align-items-center mt-3">
            <div class="d-flex gap-3">
              <button class="btn btn-sm btn-outline-primary">
                <i class="fas fa-thumbs-up me-1"></i> 12
              </button>
              <button class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-comment me-1"></i> 5
              </button>
              <button class="btn btn-sm btn-outline-success">
                <i class="fas fa-share me-1"></i> Chia sẻ
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<style>
  /* Giới hạn chiều cao modal */
.modal-dialog {
    max-height: 90vh; /* không vượt quá 90% chiều cao màn hình */
    margin: auto;
}

/* Cho phần nội dung bên trong cuộn */
.modal-content {
    max-height: 80vh;
    overflow-y: auto; /* bật scroll dọc */
    overflow-x: hidden; /* tránh scroll ngang */
}

/* Header modal fix trên cùng */
.modal-header, .modal-footer {
    position: sticky;
    top: 0;
    z-index: 10;
    background: #fff; /* giữ nền trắng để không trong suốt */
}

</style>
<!-- Modal xác nhận chuyển đổi -->
<div class="modal fade" id="convertModal" tabindex="-1" aria-labelledby="convertModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-warning text-dark">
        <h5 class="modal-title" id="convertModalLabel">
          <i class="fas fa-building me-2"></i>Đăng ký tài khoản doanh nghiệp
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Thông tin hiện tại - Compact version -->
        <div class="row mb-3">
          <div class="col-md-6">
            <div class="card border-primary h-100">
              <div class="card-header bg-primary text-white py-2">
                <h6 class="mb-0"><i class="fas fa-user me-2"></i>Tài khoản hiện tại</h6>
              </div>
              <div class="card-body py-2">
                <div class="d-flex justify-content-between mb-1">
                  <small><strong>Loại:</strong></small>
                  <span class="badge bg-info">Cá nhân</span>
                </div>
                <div class="d-flex justify-content-between mb-1">
                  <small><strong>Bài viết:</strong></small>
                  <span class="badge bg-primary"><?php echo isset($user_posts) ? $user_posts : '0'; ?></span>
                </div>
                <div class="d-flex justify-content-between mb-1">
                  <small><strong>Theo dõi:</strong></small>
                  <span class="badge bg-success"><?php echo isset($user_followers) ? $user_followers : '0'; ?></span>
                </div>
                <div class="d-flex justify-content-between">
                  <small><strong>Đang theo:</strong></small>
                  <span class="badge bg-warning"><?php echo isset($user_following) ? $user_following : '0'; ?></span>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card border-warning h-100">
              <div class="card-header bg-warning text-dark py-2">
                <h6 class="mb-0"><i class="fas fa-building me-2"></i>Sau khi chuyển đổi</h6>
              </div>
              <div class="card-body py-2">
                <div class="d-flex justify-content-between mb-1">
                  <small><strong>Loại:</strong></small>
                  <span class="badge bg-warning">Doanh nghiệp</span>
                </div>
                <div class="d-flex justify-content-between mb-1">
                  <small><strong>Bài viết:</strong></small>
                  <span class="badge bg-primary"><?php echo isset($user_posts) ? $user_posts : '0'; ?></span>
                </div>
                <div class="d-flex justify-content-between mb-1">
                  <small><strong>Theo dõi:</strong></small>
                  <span class="badge bg-success"><?php echo isset($user_followers) ? $user_followers : '0'; ?></span>
                </div>
                <div class="d-flex justify-content-between">
                  <small><strong>Đang theo:</strong></small>
                  <span class="badge bg-warning"><?php echo isset($user_following) ? $user_following : '0'; ?></span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Cảnh báo - Compact version -->
        <div class="alert alert-warning py-2">
          <i class="fas fa-exclamation-triangle me-2"></i>
          <strong>Lưu ý:</strong>
          <small class="d-block mt-1">
            Chuyển đổi sang doanh nghiệp • Cần thông tin hợp lệ • Xét duyệt 1-3 ngày • Một số tính năng bị hạn chế
          </small>
        </div>

        <!-- Form đăng ký doanh nghiệp - Compact version -->
        <form id="convertForm" method="POST" action="controller/convertToBusiness.php">
          <div class="row">
            <div class="col-md-6">
              <div class="mb-2">
                <label for="companyName" class="form-label small">Tên công ty <span class="text-danger">*</span></label>
                <input type="text" class="form-control form-control-sm" id="companyName" name="company_name" placeholder="Nhập tên công ty" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-2">
                <label for="taxCode" class="form-label small">Mã số thuế <span class="text-danger">*</span></label>
                <input type="text" class="form-control form-control-sm" id="taxCode" name="tax_code" placeholder="Nhập mã số thuế" required>
              </div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-6">
              <div class="mb-2">
                <label for="businessField" class="form-label small">Lĩnh vực hoạt động <span class="text-danger">*</span></label>
                <select class="form-select form-select-sm" id="businessField" name="business_field" required>
                  <option value="">Chọn lĩnh vực</option>
                  <option value="fintech">Công nghệ tài chính</option>
                  <option value="banking">Ngân hàng</option>
                  <option value="investment">Đầu tư</option>
                  <option value="insurance">Bảo hiểm</option>
                  <option value="securities">Chứng khoán</option>
                  <option value="other">Khác</option>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-2">
                <label for="companySize" class="form-label small">Quy mô nhân sự</label>
                <select class="form-select form-select-sm" id="companySize" name="company_size">
                  <option value="">Chọn quy mô</option>
                  <option value="1-10">1-10 nhân viên</option>
                  <option value="11-50">11-50 nhân viên</option>
                  <option value="51-200">51-200 nhân viên</option>
                  <option value="201-500">201-500 nhân viên</option>
                  <option value="500+">Trên 500 nhân viên</option>
                </select>
              </div>
            </div>
          </div>

          <div class="mb-2">
            <label for="businessAddress" class="form-label small">Địa chỉ trụ sở <span class="text-danger">*</span></label>
            <textarea class="form-control form-control-sm" id="businessAddress" name="business_address" rows="2" placeholder="Nhập địa chỉ trụ sở chính" required></textarea>
          </div>

          <div class="mb-2">
            <label for="website" class="form-label small">Website công ty</label>
            <input type="url" class="form-control form-control-sm" id="website" name="website" placeholder="https://example.com">
          </div>

          <div class="form-check mb-2">
            <input class="form-check-input" type="checkbox" id="agreeTerms" name="agree_terms" required>
            <label class="form-check-label small" for="agreeTerms">
              Tôi đồng ý với <a href="#" class="text-primary">Điều khoản sử dụng</a> và <a href="#" class="text-primary">Chính sách bảo mật</a>
            </label>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="fas fa-times me-1"></i>Hủy
        </button>
        <button type="button" class="btn btn-warning" onclick="submitConversion()">
          <i class="fas fa-building me-1"></i>Chuyển đổi
        </button>
      </div>
    </div>
  </div>
</div>

<script>
function convertToBusiness() {
  // Hiển thị modal xác nhận
  var convertModal = new bootstrap.Modal(document.getElementById('convertModal'));
  convertModal.show();
  
  // Đảm bảo modal có thể scroll
  setTimeout(function() {
    var modalBody = document.querySelector('#convertModal .modal-body');
    if (modalBody) {
      modalBody.style.maxHeight = 'calc(90vh - 140px)';
      modalBody.style.overflowY = 'auto';
    }
  }, 100);
}

function submitConversion() {
  // Lấy form element
  var form = document.getElementById('convertForm');
  var formData = new FormData(form);

  // Kiểm tra validation trước khi submit
  var companyName = document.getElementById('companyName').value;
  var taxCode = document.getElementById('taxCode').value;
  var businessField = document.getElementById('businessField').value;
  var businessAddress = document.getElementById('businessAddress').value;
  var agreeTerms = document.getElementById('agreeTerms').checked;

  if (!companyName || !taxCode || !businessField || !businessAddress) {
    alert('Vui lòng điền đầy đủ thông tin bắt buộc!');
    return;
  }

  if (!agreeTerms) {
    alert('Vui lòng đồng ý với điều khoản sử dụng!');
    return;
  }

  // Hiển thị loading
  var submitBtn = event.target;
  var originalText = submitBtn.innerHTML;
  submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Đang xử lý...';
  submitBtn.disabled = true;

  // Submit form tới PHP
  fetch('controller/convertToBusiness.php', {
    method: 'POST',
    body: formData
  })
  .then(response => response.json())
  .then(data => {
    // Reset button
    submitBtn.innerHTML = originalText;
    submitBtn.disabled = false;

    if (data.success) {
      // Close modal
      var convertModal = bootstrap.Modal.getInstance(document.getElementById('convertModal'));
      convertModal.hide();
      
      // Show success message
      alert(data.message);
      
      // Reset form
      form.reset();
      
      // Reload page để cập nhật giao diện
      setTimeout(function() {
        window.location.reload();
      }, 1000);
    } else {
      // Show error message
      alert(data.message);
    }
  })
  .catch(error => {
    // Reset button
    submitBtn.innerHTML = originalText;
    submitBtn.disabled = false;
    
    // Show error message
    alert('Có lỗi xảy ra khi xử lý yêu cầu. Vui lòng thử lại sau!');
    console.error('Error:', error);
  });
}

function addPost() {
  var postContent = document.getElementById('newPost').value;
  if (!postContent.trim()) {
    alert('Vui lòng nhập nội dung bài viết!');
    return;
  }

  // Tạo element bài viết mới
  var postsContainer = document.getElementById('posts');
  var newPost = document.createElement('div');
  newPost.className = 'post-box mb-3';
  newPost.innerHTML = `
    <div class="d-flex align-items-center mb-3">
      <img src="https://via.placeholder.com/40" class="rounded-circle me-2" alt="avatar" style="width: 40px; height: 40px;">
      <div>
        <h6 class="mb-0"><?php echo $profile_category == 'businessmen' ? 'CTCP Tài chính số' : 'Nguyễn Văn A'; ?></h6>
        <small class="text-muted">Vừa xong</small>
      </div>
    </div>
    <p>${postContent}</p>
    <div class="post-actions d-flex justify-content-between align-items-center mt-3">
      <div class="d-flex gap-3">
        <button class="btn btn-sm btn-outline-primary">
          <i class="fas fa-thumbs-up me-1"></i> 0
        </button>
        <button class="btn btn-sm btn-outline-secondary">
          <i class="fas fa-comment me-1"></i> 0
        </button>
        <button class="btn btn-sm btn-outline-success">
          <i class="fas fa-share me-1"></i> Chia sẻ
        </button>
      </div>
    </div>
  `;

  // Thêm bài viết vào đầu danh sách
  postsContainer.insertBefore(newPost, postsContainer.firstChild);

  // Xóa nội dung textarea
  document.getElementById('newPost').value = '';
}

// Đảm bảo modal scroll được khi mở
document.addEventListener('DOMContentLoaded', function() {
  var convertModal = document.getElementById('convertModal');
  if (convertModal) {
    convertModal.addEventListener('shown.bs.modal', function() {
      var modalBody = this.querySelector('.modal-body');
      if (modalBody) {
        modalBody.style.maxHeight = 'calc(90vh - 140px)';
        modalBody.style.overflowY = 'auto';
        modalBody.style.overflowX = 'hidden';
      }
    });
  }
});
</script>