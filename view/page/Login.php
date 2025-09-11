<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Đăng nhập & Quản lý tài khoản</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #000000;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .container {
      background: #fff;
      padding: 20px 30px;
      border-radius: 12px;
      box-shadow: 0px 4px 8px rgba(0,0,0,0.2);
      width: 380px;
      max-height: 95vh;
      overflow-y: auto;
    }
    h2, h3 {
      text-align: center;
      margin-bottom: 15px;
    }
    label {
      font-weight: bold;
    }
    input {
      width: 100%;
      padding: 10px;
      margin: 8px 0;
      border: 1px solid #ccc;
      border-radius: 6px;
    }
    .checkbox-group {
      margin: -5px 0 10px 0;
      font-size: 14px;
    }
    .checkbox-group input {
      width: auto;
      margin-right: 5px;
    }
    button {
      width: 100%;
      padding: 10px;
      background: #4CAF50;
      color: white;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      margin-top: 5px;
    }
    button:hover { background: #45a049; }
    .google-btn {
      margin-top: 10px;
      background: #db4437;
    }
    .google-btn:hover { background: #c33c2f; }
    .toggle {
      text-align: center;
      margin-top: 10px;
    }
    .toggle a {
      color: #007BFF;
      cursor: pointer;
      text-decoration: none;
    }
    .error {
      color: red;
      font-size: 14px;
    }
    .msg { text-align:center; margin-top:8px; font-size:14px; color:#333; }
    .profile {
      text-align: center;
    }
    .profile img {
      border-radius: 50%;
      width: 80px;
      height: 80px;
      margin-bottom: 10px;
    }
  </style>
  <!-- Firebase -->
  <script src="https://www.gstatic.com/firebasejs/9.22.2/firebase-app-compat.js"></script>
  <script src="https://www.gstatic.com/firebasejs/9.22.2/firebase-auth-compat.js"></script>
  <script>
    // === DÁN firebaseConfig của bạn vào đây ===
    const firebaseConfig = {
      apiKey: "YOUR_API_KEY",
      authDomain: "YOUR_PROJECT.firebaseapp.com",
      projectId: "YOUR_PROJECT_ID",
      storageBucket: "YOUR_PROJECT.appspot.com",
      messagingSenderId: "YOUR_SENDER_ID",
      appId: "YOUR_APP_ID"
    };
    firebase.initializeApp(firebaseConfig);
    const auth = firebase.auth();
  </script>
</head>
<body>
  <div class="container">

    <!-- Form đăng nhập -->
    <div id="loginForm">
      <h2>Đăng nhập</h2>
      <form>
        <label for="login-username">Email</label>
        <input type="email" id="login-username" required>
        
        <label for="login-password">Mật khẩu</label>
        <input type="password" id="login-password" required>
        <div class="checkbox-group">
          <input type="checkbox" id="show-login-pass" onclick="togglePassword('login-password')">
          <label for="show-login-pass">Hiện mật khẩu</label>
        </div>
        
        <button type="button" onclick="loginEmail()">Đăng nhập</button>
        <button type="button" class="google-btn" onclick="loginGoogle()">Đăng nhập bằng Google</button>
      </form>
      <div id="loginMsg" class="msg"></div>
      <div class="toggle">
        <a onclick="showForgot()">Quên mật khẩu?</a><br>
        Chưa có tài khoản? <a onclick="showRegister()">Đăng ký</a>
      </div>
    </div>

    <!-- Form đăng ký -->
    <div id="registerForm" style="display:none;">
      <h2>Đăng ký</h2>
      <form onsubmit="return validateRegister()">
        <label for="reg-username">Tên hiển thị</label>
        <input type="text" id="reg-username" required>
        
        <label for="reg-email">Email</label>
        <input type="email" id="reg-email" required>
        
        <label for="reg-password">Mật khẩu</label>
        <input type="password" id="reg-password" required>
        <div class="checkbox-group">
          <input type="checkbox" id="show-reg-pass" onclick="togglePassword('reg-password')">
          <label for="show-reg-pass">Hiện mật khẩu</label>
        </div>
        
        <label for="reg-confirm">Xác nhận mật khẩu</label>
        <input type="password" id="reg-confirm" required>
        <div class="checkbox-group">
          <input type="checkbox" id="show-reg-confirm" onclick="togglePassword('reg-confirm')">
          <label for="show-reg-confirm">Hiện mật khẩu</label>
        </div>
        
        <p class="error" id="errorMessage"></p>
        
        <button type="button" onclick="register()">Đăng ký</button>
      </form>
      <div id="regMsg" class="msg"></div>
      <div class="toggle">
        Đã có tài khoản? <a onclick="showLogin()">Đăng nhập</a>
      </div>
    </div>

    <!-- Form quên mật khẩu -->
    <div id="forgotForm" style="display:none;">
      <h2>Quên mật khẩu</h2>
      <form>
        <label for="forgot-email">Nhập email của bạn</label>
        <input type="email" id="forgot-email" required>
        <button type="button" onclick="resetPass()">Gửi yêu cầu đặt lại</button>
      </form>
      <div id="forgotMsg" class="msg"></div>
      <div class="toggle">
        <a onclick="showLogin()">Quay lại đăng nhập</a>
      </div>
    </div>

    <!-- Trang hồ sơ -->
    <div id="profilePage" style="display:none;">
      <div class="profile">
        <img id="userPhoto" src="" alt="Avatar">
        <h3 id="userName"></h3>
        <p id="userEmail"></p>
        <button onclick="logout()">Đăng xuất</button>
      </div>

      <h3>Cập nhật thông tin</h3>
      <form>
        <label for="update-name">Tên hiển thị mới</label>
        <input type="text" id="update-name" placeholder="Nhập tên mới">

        <label for="update-photo">Link ảnh đại diện</label>
        <input type="text" id="update-photo" placeholder="URL hình ảnh">

        <button type="button" onclick="updateProfile()">Cập nhật</button>
      </form>
      <div id="updateMsg" class="msg"></div>

      <h3>Đổi mật khẩu</h3>
      <form>
        <label for="new-password">Mật khẩu mới</label>
        <input type="password" id="new-password" required>
        <div class="checkbox-group">
          <input type="checkbox" id="show-new-pass" onclick="togglePassword('new-password')">
          <label for="show-new-pass">Hiện mật khẩu</label>
        </div>

        <button type="button" onclick="changePassword()">Đổi mật khẩu</button>
      </form>
      <div id="passMsg" class="msg"></div>
    </div>

  </div>

  <script>
