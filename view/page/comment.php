<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Khung Bình Luận</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f5f5f5;
      margin: 0;
      padding: 20px;
    }
    .comment-wrapper {
      width: 600px;
      max-width: 100%;
      margin: auto;
      background: #fff;
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    .comment-section {
      height: 400px;
      overflow-y: auto;
      scrollbar-width: thin;
      scrollbar-color: #888 #f1f1f1;
      margin-bottom: 15px;
    }
    .comment-section::-webkit-scrollbar {
      width: 8px;
    }
    .comment-section::-webkit-scrollbar-track {
      background: #f1f1f1;
      border-radius: 10px;
    }
    .comment-section::-webkit-scrollbar-thumb {
      background: #888;
      border-radius: 10px;
    }
    .comment-section::-webkit-scrollbar-thumb:hover {
      background: #555;
    }
    .comment {
      display: flex;
      align-items: flex-start;
      margin-bottom: 15px;
    }
    .avatar {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      margin-right: 10px;
    }
    .comment-box {
      background: #f0f2f5;
      padding: 10px;
      border-radius: 10px;
      width: 100%;
      position: relative;
    }
    .comment-header {
      font-weight: bold;
      margin-bottom: 5px;
    }
    .comment-actions {
      font-size: 13px;
      color: #555;
      margin-top: 5px;
    }
    .comment-actions button {
      background: none;
      border: none;
      cursor: pointer;
      margin-right: 10px;
      color: #333;
    }
    .comment-actions button:hover {
      color: #000;
    }
    .reply-section {
      margin-left: 50px;
      margin-top: 10px;
    }
    .reply-input {
      display: none;
      margin-top: 5px;
    }
    .reply-input textarea {
      width: 100%;
      padding: 5px;
      border-radius: 6px;
      border: 1px solid #ccc;
      resize: none;
    }
    .reply-input button {
      margin-top: 5px;
      padding: 5px 10px;
      border: none;
      border-radius: 6px;
      background: #007bff;
      color: white;
      cursor: pointer;
    }
    .reply-input button:hover {
      background: #0056b3;
    }
    .new-comment {
      display: flex;
      align-items: flex-start;
      gap: 10px;
    }
    .new-comment textarea {
      width: 100%;
      padding: 8px;
      border-radius: 8px;
      border: 1px solid #ccc;
      resize: none;
    }
    .new-comment button {
      padding: 8px 12px;
      border: none;
      border-radius: 6px;
      background: #28a745;
      color: white;
      cursor: pointer;
    }
    .new-comment button:hover {
      background: #1e7e34;
    }
  </style>
</head>
<body>
  <div class="comment-wrapper">
    <div class="comment-section" id="commentSection">
      <div class="comment">
        <img src="https://i.pravatar.cc/40?img=1" class="avatar" alt="avatar">
        <div class="comment-box">
          <div class="comment-header">Nguyễn Văn A</div>
          <div class="comment-content">Bài viết này rất hữu ích, cảm ơn bạn!</div>
          <div class="comment-actions">
            <button onclick="toggleReply(this)"><i class="fa fa-reply"></i> Trả lời</button>
            <button onclick="likeComment(this)"><i class="fa fa-thumbs-up"></i> <span>0</span></button>
            <button onclick="dislikeComment(this)"><i class="fa fa-thumbs-down"></i> <span>0</span></button>
          </div>
          <div class="reply-input">
            <textarea placeholder="Viết câu trả lời..."></textarea>
            <button onclick="sendReply(this)">Gửi</button>
          </div>
          <div class="reply-section"></div>
        </div>
      </div>
    </div>
    <div class="new-comment">
      <img src="https://i.pravatar.cc/40?img=2" class="avatar" alt="avatar">
      <textarea id="newCommentText" placeholder="Viết bình luận..."></textarea>
      <button onclick="addNewComment()">Gửi</button>
    </div>
  </div>

  <script>
    function toggleReply(button) {
      const replyBox = button.closest('.comment-box').querySelector('.reply-input');
      replyBox.style.display = replyBox.style.display === 'block' ? 'none' : 'block';
    }

    function sendReply(button) {
      const replyBox = button.closest('.reply-input');
      const textarea = replyBox.querySelector('textarea');
      const replyText = textarea.value.trim();
      if (replyText === '') return;

      const replySection = button.closest('.comment-box').querySelector('.reply-section');
      const newReply = document.createElement('div');
      newReply.classList.add('comment');
      newReply.innerHTML = `
        <img src="https://i.pravatar.cc/40?img=${Math.floor(Math.random()*70)}" class="avatar" alt="avatar">
        <div class="comment-box">
          <div class="comment-header">Người dùng</div>
          <div class="comment-content">${replyText}</div>
          <div class="comment-actions">
            <button onclick="likeComment(this)"><i class='fa fa-thumbs-up'></i> <span>0</span></button>
            <button onclick="dislikeComment(this)"><i class='fa fa-thumbs-down'></i> <span>0</span></button>
            <button onclick="editComment(this)"><i class='fa fa-pen'></i> Sửa</button>
            <button onclick="deleteComment(this)"><i class='fa fa-trash'></i> Xóa</button>
          </div>
        </div>`;
      replySection.appendChild(newReply);
      textarea.value = '';
      replyBox.style.display = 'none';
    }

    function likeComment(button) {
      const countSpan = button.querySelector('span');
      let count = parseInt(countSpan.textContent);
      countSpan.textContent = count + 1;
    }

    function dislikeComment(button) {
      const countSpan = button.querySelector('span');
      let count = parseInt(countSpan.textContent);
      countSpan.textContent = count + 1;
    }

    function deleteComment(button) {
      const comment = button.closest('.comment');
      comment.remove();
    }

    function editComment(button) {
      const commentBox = button.closest('.comment-box');
      const content = commentBox.querySelector('.comment-content');
      const newText = prompt("Sửa bình luận:", content.textContent);
      if (newText !== null && newText.trim() !== '') {
        content.textContent = newText;
      }
    }

    function addNewComment() {
      const textArea = document.getElementById('newCommentText');
      const text = textArea.value.trim();
      if (text === '') return;

      const commentSection = document.getElementById('commentSection');
      const newComment = document.createElement('div');
      newComment.classList.add('comment');
      newComment.innerHTML = `
        <img src="https://i.pravatar.cc/40?img=${Math.floor(Math.random()*70)}" class="avatar" alt="avatar">
        <div class="comment-box">
          <div class="comment-header">Người dùng</div>
          <div class="comment-content">${text}</div>
          <div class="comment-actions">
            <button onclick="toggleReply(this)"><i class='fa fa-reply'></i> Trả lời</button>
            <button onclick="likeComment(this)"><i class='fa fa-thumbs-up'></i> <span>0</span></button>
            <button onclick="dislikeComment(this)"><i class='fa fa-thumbs-down'></i> <span>0</span></button>
            <button onclick="editComment(this)"><i class='fa fa-pen'></i> Sửa</button>
            <button onclick="deleteComment(this)"><i class='fa fa-trash'></i> Xóa</button>
          </div>
          <div class="reply-input">
            <textarea placeholder="Viết câu trả lời..."></textarea>
            <button onclick="sendReply(this)">Gửi</button>
          </div>
          <div class="reply-section"></div>
        </div>`;
      commentSection.appendChild(newComment);
      textArea.value = '';
      commentSection.scrollTop = commentSection.scrollHeight;
    }
  </script>
</body>
</html>