
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
  fetch('api/businessman_register', {
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


// Cache bài viết để mở modal edit nhanh
window.__postCache = window.__postCache || {};

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
    // Lưu cache
    window.__postCache[post.id] = post;

    var postElement = createPostElement(post);
    postsContainer.appendChild(postElement);
  });
}

// Tạo element bài viết theo cấu trúc Home


// Hàm xóa bài viết (giữ cấu trúc cũ, thêm fallback theo ID)
function deletePost(postId, buttonElement) {
  if (!confirm('Bạn có chắc chắn muốn xóa bài viết này không? Hành động này không thể hoàn tác.')) {
    return;
  }

  const $btn = buttonElement;

  // ===== Tìm container bài viết =====
  // 1) Ưu tiên theo id="post-<id>"
  const byIdEl = document.getElementById(`post-${postId}`);
  // 2) Fallback: ancestor theo các class cũ
  const byClosestEl =
    $btn.closest(`[data-post-id="${postId}"]`) ||
    $btn.closest('[data-post-id]') ||
    $btn.closest('.post-item, .article-item, .view-carde, .block-k');

  // Chọn phần tử sẽ xoá: ưu tiên #post-<id> nếu có
  const postElement = byIdEl || byClosestEl;

  // ===== Lấy session token =====
  let token = window.userSessionToken || '';
  if (!token) {
    const nearForm = $btn.closest('form');
    const nearInput = nearForm && nearForm.querySelector('input[name="session_token"]');
    if (nearInput && nearInput.value) token = nearInput.value;
  }
  if (!token) {
    const anyInput = document.querySelector('input[name="session_token"]');
    if (anyInput && anyInput.value) token = anyInput.value;
  }
  if (!token) {
    const meta = document.querySelector('meta[name="session-token"]');
    if (meta && meta.content) token = meta.content;
  }
  if (!token) {
    alert('Phiên làm việc không hợp lệ. Vui lòng tải lại trang.');
    return;
  }

  // ===== Khóa nút khi đang xoá =====
  const oldHtml = $btn.innerHTML;
  const hadDisabledProp = Object.prototype.hasOwnProperty.call($btn, 'disabled') || ('disabled' in $btn);
  const oldDisabled = hadDisabledProp ? $btn.disabled : undefined;
  if (hadDisabledProp) $btn.disabled = true;
  $btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Đang xóa...';

  const body = new URLSearchParams();
  body.set('post_id', String(postId));
  body.set('session_token', token);

  fetch('api/deletePost', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
      'X-Requested-With': 'XMLHttpRequest'
    },
    body: body.toString()
  })
    .then(async (res) => {
      if (res.status === 204) return { success: true };
      let data = null;
      try { data = await res.json(); } catch (_) { }
      if (!res.ok) {
        throw new Error((data && (data.message || data.error)) || ('HTTP ' + res.status));
      }
      return data || { success: true };
    })
    .then((data) => {
      const ok =
        data === true ||
        data?.success === true ||
        data?.success === 'true' ||
        data?.status === 'success' ||
        data?.code === 0 ||
        data?.result === 'ok';

      if (!ok) throw new Error(data?.message || 'Không thể xóa bài viết.');

      // Xoá khỏi UI (ưu tiên phần tử theo id="post-<id>")
      if (postElement) {
        postElement.style.transition = 'opacity .4s ease';
        postElement.style.opacity = '0';
        setTimeout(() => postElement.remove(), 400);
      } else {
        // Trường hợp hiếm: không tìm thấy container => thử xoá thẳng theo ID lần nữa
        const fallbackEl = document.getElementById(`post-${postId}`);
        if (fallbackEl) fallbackEl.remove();
      }
    })
    .catch((err) => {
      console.error('deletePost error:', err);
      alert('Lỗi: ' + err.message);
    })
    .finally(() => {
      if (hadDisabledProp) $btn.disabled = oldDisabled;
      $btn.innerHTML = oldHtml;
    });
}




function createPostElement(post) {
  const profileDataElement = document.getElementById('profileData');
  const userId = profileDataElement ? profileDataElement.dataset.userId : null;

  const postDiv = document.createElement('div');
  postDiv.className = 'block-k article-item';
  postDiv.setAttribute('id', `post-${post.id}`);

  const safeContent = post.content || '';
  const safeTitle = post.title || safeContent.substring(0, 70);

  // ===== FIX slug undefined (giữ cấu trúc hiển thị) =====
  // Ưu tiên các key hay gặp từ API, nếu vẫn trống thì tự slugify từ title
  const rawSlug =
    (post && (post.slug || post.article_slug || post.seo_slug)) || '';

  const slugify = (s) => {
    return String(s || '')
      .normalize('NFD').replace(/[\u0300-\u036f]/g, '')   // bỏ dấu
      .toLowerCase()
      .replace(/[^a-z0-9]+/g, '-')                       // thay non-word = '-'
      .replace(/^-+|-+$/g, '')                           // bỏ '-' đầu/cuối
      .substring(0, 120);
  };

  const slug = (rawSlug && String(rawSlug).trim()) || slugify(safeTitle);

  // Nếu có slug => /details_blog/<slug>, không thì fallback route theo id
  const articleLink = slug
    ? `details_blog/${encodeURIComponent(slug)}`
    : `/post-${post.id}.html`;

  let statusBadgeHtml = '';
  if (userId && post.author_id == userId && post.status) {
    let badgeClass = '';
    let badgeText = '';
    switch (post.status) {
      case 'pending': badgeClass = 'bg-warning text-dark'; badgeText = 'Chờ duyệt'; break;
      case 'public': badgeClass = 'bg-success'; badgeText = 'Công khai'; break;
    }
    if (badgeText) {
      statusBadgeHtml = `<div class="article-status-badge" style="margin-bottom: 8px; margin-top: 5px;"><span class="badge ${badgeClass}">${badgeText}</span></div>`;
    }
  }

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
    const editButtonHtml = (userId && post.author_id == userId)
    ? `<span class="edit-post-btn ms-2" onclick="openEditPost(${post.id}, this)" title="Sửa bài viết">
         <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
              class="bi bi-pencil-square" viewBox="0 0 16 16">
           <path d="M15.502 1.94a.5.5 0 0 1 0 .706l-1 
                    1a.5.5 0 0 1-.708 0L13 3.207l1-1a.5.5 
                    0 0 1 .707 0l.795.733z"/>
           <path fill-rule="evenodd" 
                 d="M1 13.5V16h2.5l7.086-7.086-2.5-2.5L1 13.5zm11.854-8.146a.5.5 0 0 0-.708-.708l-1 
                 1a.5.5 0 1 0 .708.708l1-1z"/>
           <path fill-rule="evenodd" 
                 d="M1 4.5A1.5 1.5 0 0 1 2.5 3h6a.5.5 0 0 1 0 1h-6A.5.5 0 0 0 2 4.5v9A.5.5 0 0 0 2.5 14h9a.5.5 0 0 0 
                 .5-.5v-6a.5.5 0 0 1 1 0v6A1.5 1.5 0 0 1 11.5 15h-9A1.5 1.5 0 0 1 1 13.5v-9z"/>
         </svg>
       </span>`
    : '';
  
  postDiv.innerHTML = `
    <div class="view-carde f-frame">
        <div class="provider">
            <img class="logo" alt="Avatar" src="${post.avatar || 'https://i.pinimg.com/1200x/83/0e/ea/830eea38f7a5d3d8e390ba560d14f39c.jpg'}">
            <div class="p-covers">
                <span class="name" title="${escapeHtml(post.author_name)}">
                    <a href="/DFF.VN/view_profile?id=${post.author_id || post.user_id}">${escapeHtml(post.author_name)}</a>
                </span>
                <span class="date">${post.time_ago || ''}</span>
            </div>
            ${deleteButtonHtml}
            ${editButtonHtml}
        </div>

        ${statusBadgeHtml}

        <div class="title">
            <a title="${escapeHtml(safeTitle)}" href="${articleLink}">${escapeHtml(safeTitle)}</a>
        </div>

        <div class="sapo">
            ${safeContent}
            ${(safeContent && safeContent.length > 100) ? `<a href="${articleLink}" class="d-more">Xem thêm</a>` : ''}
        </div>

        ${post.image ? `<img class="h-img" src="${post.image}" title="${escapeHtml(safeTitle)}" alt="${escapeHtml(safeTitle)}" border="0">` : ''}

        <div class="item-bottom">
            <div class="bt-cover com-like" data-id="${post.id}">
                <span class="for-up" onclick="toggleLike(${post.id})"><svg rpl="" data-voted="false" data-type="up" fill="currentColor" height="16" icon-name="upvote-fill" viewBox="0 0 20 20" width="16" xmlns="http://www.w3.org/2000/svg"><path d="M18.706 8.953 10.834.372A1.123 1.123 0 0 0 10 0a1.128 1.128 0 0 0-.833.368L1.29 8.957a1.249 1.249 0 0 0-.171 1.343 1.114 1.114 0 0 0 1.007.7H6v6.877A1.125 1.125 0 0 0 7.123 19h5.754A1.125 1.125 0 0 0 14 17.877V11h3.877a1.114 1.114 0 0 0 1.005-.7 1.251 1.251 0 0 0-.176-1.347Z"></path></svg></span>
                <span class="value" data-old="${post.likes_count || 0}">${post.likes_count || 0}</span>
                <span class="for-down" onclick="toggleDislike(${post.id})"><svg rpl="" data-voted="false" data-type="down" fill="currentColor" height="16" icon-name="downvote-fill" viewBox="0 0 20 20" width="16" xmlns="http://www.w3.org/2000/svg"><path d="M18.88 9.7a1.114 1.114 0 0 0-1.006-.7H14V2.123A1.125 1.125 0 0 0 12.877 1H7.123A1.125 1.125 0  0 0 6 2.123V9H2.123a1.114 1.114 0 0 0-1.005.7 1.25 1.25 0 0 0 .176 1.348l7.872 8.581a1.124 1.124 0 0 0 1.667.003l7.876-8.589A1.248 1.248 0 0 0 18.88 9.7Z"></path></svg></span>
            </div>
            <div class="button-ar">
                <a href="/post-${post.id}.html#anc_comment" onclick="showComments(${post.id})"><svg rpl="" aria-hidden="true" class="icon-comment" fill="currentColor" height="15" icon-name="comment-outline" viewBox="0 0 20 20" width="15" xmlns="http://www.w3.org/2000/svg"><path d="M7.725 19.872a.718.718 0 0 1-.607-.328.725.725 0 0 1-.118-.397V16H3.625A2.63 2.63 0 0 1 1 13.375v-9.75A2.629 2.629 0 0 1 3.625 1h12.75A2.63 2.63 0 0 1 19 3.625v9.75A2.63 2.63 0 0 1 16.375 16h-4.161l-4 3.681a.725.725 0 0 1-.489.191ZM3.625 2.25A1.377 1.377 0 0 0 2.25 3.625v9.75a1.377 1.377 0 0 0 1.375 1.375h4a.625.625 0 0 1 .625.625v2.575l3.3-3.035a.628.628 0  0 1 .424-.165h4.4a1.377 1.377 0 0 0 1.375-1.375v-9.75a1.377 1.377 0 0 0-1.374-1.375H3.625Z"></path></svg>
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
(function attachSubmit() {
  const submitBtn = document.querySelector('.post-box button.btn-success.rounded-pill');
  if (submitBtn) submitBtn.addEventListener('click', addPost);
})();
let __isPosting = false;

function addPost(e) {
  if (e && typeof e.preventDefault === 'function') e.preventDefault();

  // Lấy input trước, VALIDATE NGAY
  const formEl = document.getElementById('postForm'); // có thể null ở profile
  const title = (document.getElementById('postTitle')?.value || '').trim();
  const summary = (document.getElementById('postSummary')?.value || '').trim();
  const topicId = (document.getElementById('topicSelect')?.value || '').trim();

  // Gộp content từ sections để đảm bảo có nội dung (nếu bạn đang dùng UI sections)
  const sectionNodes = document.querySelectorAll('#sectionsWrap .section-item');
  const contentParts = [];
  sectionNodes.forEach(node => {
    const t = (node.querySelector('input[type="text"]')?.value || '').trim();
    const c = (node.querySelector('textarea')?.value || '').trim();
    if (t) contentParts.push(t);
    if (c) contentParts.push(c);
  });
  const combinedContent = contentParts.join('\n\n');

  // ---- VALIDATION SỚM (return ngay, không làm gì thêm) ----
  if (!title || !summary || !topicId) {
    showNotification('Vui lòng nhập tiêu đề, tóm tắt và chọn chủ đề!', 'warning');
    return false; // đảm bảo dừng hẳn
  }
  if (!combinedContent || combinedContent.length < 10) {
    showNotification('Vui lòng nhập nội dung cho các phần (tối thiểu 10 ký tự).', 'warning');
    return false;
  }
  if (__isPosting) return false;

  // Lấy nút submit an toàn (profile trước, modal sau)
  const submitBtn = document.getElementById('btnSubmitPost')
    || document.querySelector('.post-box button.btn-success.rounded-pill');
  const original = submitBtn ? submitBtn.innerHTML : '';

  // BẮT ĐẦU chuẩn bị request sau khi đã validate
  __isPosting = true;
  if (submitBtn) {
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Đang đăng...';
    submitBtn.disabled = true;
  }

  const fd = new FormData();

  // session token (nếu có)
  const tokenInput = formEl?.querySelector('input[name="session_token"]')
    || document.querySelector('input[name="session_token"]');
  if (tokenInput) fd.append('session_token', tokenInput.value);

  // Gửi fields bắt buộc
  fd.append('title', title);
  fd.append('summary', summary);
  fd.append('topic_id', topicId);
  fd.append('content', combinedContent);

  // Gửi cấu trúc sections (nếu UI có)
  const sections = [];
  sectionNodes.forEach((node, idx) => {
    const position = idx + 1;
    const titleS = (node.querySelector('input[type="text"]')?.value || '').trim();
    const contentS = (node.querySelector('textarea')?.value || '').trim();
    sections.push({ position, title: titleS, content: contentS });

    const fileInput = node.querySelector('input.section-file');
    if (fileInput?.files?.length) {
      Array.from(fileInput.files).forEach(f => {
        fd.append(`section_media_${position}[]`, f);
      });
    }
  });
  if (sections.length) {
    fd.append('sections_json', JSON.stringify(sections));
  }

  // Ảnh cover (nếu có trường cover)
  const coverEl = document.getElementById('postCoverImage');
  if (coverEl?.files?.[0]) {
    fd.append('main_image_url', coverEl.files[0]);
  }

  fetch('api/addPost', { method: 'POST', body: fd })
    .then(r => r.json())
    .then(data => {
      if (submitBtn) {
        submitBtn.innerHTML = original;
        submitBtn.disabled = false;
      }
      __isPosting = false;

      if (data && data.success) {
        // reset form tuỳ ý
        document.getElementById('postTitle').value = '';
        document.getElementById('postSummary').value = '';
        document.getElementById('topicSelect').value = '';
        if (coverEl) coverEl.value = '';

        // reset sections (giữ 1 phần trống)
        const wrap = document.getElementById('sectionsWrap');
        if (wrap) {
          wrap.innerHTML = `
            <div class="card border-0 shadow-sm section-item" data-index="1">
              <div class="card-header bg-success-subtle d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                  <span class="badge bg-success text-white rounded-pill" style="min-width:2rem">1</span>
                  <strong>Phần 1</strong>
                </div>
                <div class="d-flex align-items-center gap-2">
                  <button type="button" class="btn btn-outline-success btn-sm section-add-media" data-type="image">
                    <i class="fas fa-image me-1"></i> Ảnh
                  </button>
                  <button type="button" class="btn btn-outline-success btn-sm section-add-media" data-type="video">
                    <i class="fas fa-video me-1"></i> Video
                  </button>
                  <button type="button" class="btn btn-outline-danger btn-sm d-none section-remove">
                    <i class="fas fa-trash-alt"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <div class="mb-3">
                  <label class="form-label fw-semibold">Tiêu đề phần 1</label>
                  <input type="text" class="form-control" placeholder="Nhập tiêu đề phần 1..." required>
                </div>
                <div class="mb-3">
                  <input type="file" class="d-none section-file" accept="image/*,video/*">
                  <div class="media-preview border rounded p-3 text-center">Chưa chọn ảnh/video.</div>
                </div>
                <div class="mb-2">
                  <label class="form-label fw-semibold">Nội dung phần 1</label>
                  <textarea class="form-control" rows="4" placeholder="Nhập nội dung phần 1..." required></textarea>
                </div>
              </div>
            </div>`;
        }

        if (typeof loadPosts === 'function') loadPosts();
        showNotification(data.message || 'Đăng bài thành công!', 'success');
      } else {
        showNotification('Lỗi: ' + ((data && data.message) ? data.message : 'Không xác định'), 'danger');
      }
    })
    .catch(err => {
      if (submitBtn) {
        submitBtn.innerHTML = original;
        submitBtn.disabled = false;
      }
      __isPosting = false;
      console.error('Fetch error:', err);
      showNotification('Có lỗi xảy ra khi gửi request!', 'danger');
    });

  return false; // để chắc chắn không submit mặc định
}


// Preview nhiều ảnh
function previewImage(event) {
  const preview = document.getElementById('imagePreview');

  // Không xoá tất cả files trong input
  // preview.innerHTML = ''; // <-- chỉ xoá preview, không reset files

  const files = event.target.files;

  Array.from(files).forEach(file => {
    const wrapper = document.createElement('div');
    wrapper.style.position = 'relative';
    wrapper.style.display = 'inline-block';
    wrapper.style.marginRight = '8px';

    const img = document.createElement('img');
    img.src = URL.createObjectURL(file);
    img.style.width = '100px';
    img.style.height = '100px';
    img.style.objectFit = 'cover';
    img.classList.add('rounded', 'mb-2');

    const btnRemove = document.createElement('button');
    btnRemove.type = 'button';
    btnRemove.innerHTML = '&times;';
    btnRemove.classList.add('btn', 'btn-sm', 'btn-danger');
    btnRemove.style.position = 'absolute';
    btnRemove.style.top = '2px';
    btnRemove.style.right = '2px';
    btnRemove.onclick = () => {
      wrapper.remove();
      removeFileFromInput('postImage', file);
    };

    wrapper.appendChild(img);
    wrapper.appendChild(btnRemove);
    preview.appendChild(wrapper);
  });
}

function previewVideo(event) {
  const preview = document.getElementById('videoPreview');
  const files = event.target.files;

  Array.from(files).forEach(file => {
    const wrapper = document.createElement('div');
    wrapper.style.position = 'relative';
    wrapper.style.width = '150px';
    wrapper.style.height = '100px';
    wrapper.style.marginRight = '8px';

    const video = document.createElement('video');
    video.src = URL.createObjectURL(file);
    video.controls = true;
    video.style.width = '100%';
    video.style.height = '100%';
    video.classList.add('rounded');

    const btnRemove = document.createElement('button');
    btnRemove.type = 'button';
    btnRemove.innerHTML = '&times;';
    btnRemove.classList.add('btn', 'btn-sm', 'btn-danger');
    btnRemove.style.position = 'absolute';
    btnRemove.style.top = '2px';
    btnRemove.style.right = '2px';
    btnRemove.onclick = () => {
      wrapper.remove();
      removeFileFromInput('postVideo', file);
    };

    wrapper.appendChild(video);
    wrapper.appendChild(btnRemove);
    preview.appendChild(wrapper);
  });
}

// Hàm xóa 1 file khỏi input type="file" mà vẫn giữ các file khác
function removeFileFromInput(inputId, fileToRemove) {
  const input = document.getElementById(inputId);
  const dt = new DataTransfer();
  Array.from(input.files)
    .filter(f => f !== fileToRemove)
    .forEach(f => dt.items.add(f));
  input.files = dt.files;
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
  if (!profileDataElement) {
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
// ======= Modal Edit: tạo 1 lần khi cần =======
function ensureEditModal() {
  if (document.getElementById('editPostModal')) return;

  const modalHtml = `
  <div class="modal fade" id="editPostModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Sửa bài viết</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
        </div>
        <div class="modal-body">
          <form id="editPostForm" class="needs-validation" novalidate enctype="multipart/form-data">
            <input type="hidden" id="editPostId">

            <div class="mb-3">
              <label class="form-label fw-bold">Tiêu đề</label>
              <input type="text" id="editPostTitle" class="form-control" placeholder="Tiêu đề..." required>
            </div>

            <div class="mb-3">
              <label class="form-label fw-bold">Tóm tắt</label>
              <textarea id="editPostSummary" class="form-control" rows="3" placeholder="Tóm tắt ngắn..." required></textarea>
            </div>

            <div class="mb-3">
              <label class="form-label fw-bold">Chủ đề</label>
              <select id="editTopicSelect" class="form-select" required></select>
            </div>

            <div class="mb-3">
              <label class="form-label fw-bold">Ảnh bìa (cover)</label>
              <input type="file" id="editPostCover" class="form-control" accept="image/*">
              <div id="editCoverPreview" class="mt-2" style="min-height:60px;"></div>
            </div>

            <div class="mb-3">
              <label class="form-label fw-bold">Nội dung</label>
              <textarea id="editPostContent" class="form-control" rows="6" placeholder="Nội dung..."></textarea>
            </div>

            <!-- giữ token để gọi API -->
            <input type="hidden" name="session_token"
                   value="${(document.querySelector('input[name="session_token"]')?.value || '')}">
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Huỷ</button>
          <button type="button" id="btnSaveEditPost" class="btn btn-primary">
            <i class="fas fa-save me-1"></i> Lưu thay đổi
          </button>
        </div>
      </div>
    </div>
  </div>`;

  document.body.insertAdjacentHTML('beforeend', modalHtml);

  // Preview cover trong modal edit
  const coverInput = document.getElementById('editPostCover');
  const coverPreview = document.getElementById('editCoverPreview');
  if (coverInput) {
    coverInput.addEventListener('change', (e) => {
      coverPreview.innerHTML = '';
      const f = e.target.files && e.target.files[0];
      if (!f) return;
      const url = URL.createObjectURL(f);
      const img = document.createElement('img');
      img.src = url;
      img.style.maxWidth = '240px';
      img.style.maxHeight = '140px';
      img.alt = 'cover preview';
      img.className = 'rounded border';
      coverPreview.appendChild(img);
    });
  }

  // Lưu bài viết
  document.getElementById('btnSaveEditPost').addEventListener('click', submitEditPost);
}

// ======= Mở modal + nạp dữ liệu vào form =======
function openEditPost(postId, btnEl) {
  ensureEditModal();

  // Lấy post từ cache; nếu không có bạn có thể tự fetch chi tiết
  const post = (window.__postCache && window.__postCache[postId]) ? window.__postCache[postId] : null;

  // Nếu không có cache, tạo khung trống để user tự sửa (hoặc bạn có thể fetch api/getPost ở đây)
  fillEditForm(postId, post);

  const modal = new bootstrap.Modal(document.getElementById('editPostModal'));
  modal.show();
}

function fillEditForm(postId, post) {
  const idEl      = document.getElementById('editPostId');
  const titleEl   = document.getElementById('editPostTitle');
  const sumEl     = document.getElementById('editPostSummary');
  const topicSel  = document.getElementById('editTopicSelect');
  const contentEl = document.getElementById('editPostContent');
  const coverPrev = document.getElementById('editCoverPreview');

  idEl.value      = postId;
  titleEl.value   = (post?.title || '').trim();
  sumEl.value     = (post?.summary || '').trim();
  contentEl.value = (post?.content || '').trim();

  // Clone options từ #topicSelect sẵn có ở form tạo bài viết (để đúng danh mục)
  topicSel.innerHTML = '';
  const srcSel = document.getElementById('topicSelect');
  if (srcSel) {
    topicSel.innerHTML = srcSel.innerHTML;
    // set value nếu có
    if (post?.topic_id) topicSel.value = String(post.topic_id);
  } else {
    // fallback nếu không có select gốc
    const opt = document.createElement('option');
    opt.value = post?.topic_id || '';
    opt.textContent = post?.topic_name || 'Chủ đề hiện tại';
    topicSel.appendChild(opt);
    if (post?.topic_id) topicSel.value = String(post.topic_id);
  }

  // Hiện ảnh cover hiện tại nếu có
  coverPrev.innerHTML = '';
  const currentCover = post?.image || post?.main_image_url;
  if (currentCover) {
    const img = document.createElement('img');
    img.src = currentCover;
    img.style.maxWidth = '240px';
    img.style.maxHeight = '140px';
    img.alt = 'current cover';
    img.className = 'rounded border';
    coverPrev.appendChild(img);
  }
}

// ======= Gửi form edit lên backend =======
// Bạn CHƯA có controller, nên tui để endpoint 'api/updatePost' (fallback 'api/editPost')
// Backend nên nhận: post_id, title, summary, topic_id, content, main_image_url (file), session_token
function submitEditPost() {
  const modalEl  = document.getElementById('editPostModal');
  const formEl   = document.getElementById('editPostForm');

  const postId   = document.getElementById('editPostId').value.trim();
  const title    = document.getElementById('editPostTitle').value.trim();
  const summary  = document.getElementById('editPostSummary').value.trim();
  const topicId  = document.getElementById('editTopicSelect').value.trim();
  const content  = document.getElementById('editPostContent').value.trim();
  const coverEl  = document.getElementById('editPostCover');

  if (!postId || !title || !summary || !topicId) {
    showNotification('Vui lòng nhập đủ Tiêu đề, Tóm tắt và Chủ đề!', 'warning');
    return;
  }
  // KHÔNG bắt buộc content ở FE nữa (BE đã fallback). Nhưng nhắc nhẹ:
  if (!content || content.length < 10) {
    console.warn('Nội dung đang trống hoặc ngắn; backend sẽ giữ nguyên content cũ.');
  }

  // token
  let token = window.userSessionToken || (formEl.querySelector('input[name="session_token"]')?.value || '');
  if (!token) {
    const anyInput = document.querySelector('input[name="session_token"]');
    if (anyInput && anyInput.value) token = anyInput.value;
  }
  if (!token) {
    showNotification('Phiên làm việc không hợp lệ. Vui lòng tải lại trang.', 'danger');
    return;
  }

  const btn = document.getElementById('btnSaveEditPost');
  const oldHtml = btn.innerHTML;
  btn.disabled = true;
  btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Đang lưu...';

  const fd = new FormData();
  fd.append('post_id', postId);
  fd.append('title', title);
  fd.append('summary', summary);
  fd.append('topic_id', topicId);
  fd.append('content', content);
  fd.append('session_token', token);
  if (coverEl?.files?.[0]) {
    fd.append('main_image_url', coverEl.files[0]);
  }

  // Thử nhiều endpoint để phù hợp router hiện tại
  const tryEndpoints = ['api/editArticle', 'api/editPost', 'api/updatePost'];

  (function tryNext(i) {
    if (i >= tryEndpoints.length) throw new Error('Không tìm thấy endpoint cập nhật bài viết.');

    fetch(tryEndpoints[i], { method: 'POST', body: fd })
      .then(async (res) => {
        let data = null;
        try { data = await res.json(); } catch(_) {}
        if (!res.ok) {
          if (res.status === 404) return tryNext(i + 1);
          const msg = (data && (data.message || data.error)) || ('HTTP ' + res.status);
          throw new Error(msg);
        }
        return data || { success: true };
      })
      .then((data) => {
        const ok = data === true || data?.success === true || data?.success === 'true'
          || data?.status === 'success' || data?.code === 0 || data?.result === 'ok';
        if (!ok) throw new Error(data?.message || 'Cập nhật thất bại.');

        const inst = bootstrap.Modal.getInstance(modalEl);
        if (inst) inst.hide();

        showNotification('Đã cập nhật bài viết!', 'success');
        if (typeof loadPosts === 'function') loadPosts();
      })
      .catch((err) => {
        showNotification('Lỗi: ' + err.message, 'danger');
        console.error('editPost error:', err);
      })
      .finally(() => {
        btn.disabled = false;
        btn.innerHTML = oldHtml;
      });
  })(0);
}

