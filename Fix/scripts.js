// --- CÀI ĐẶT THỜI GIAN ĐẾM NGƯỢC ---
// THAY ĐỔI NGÀY THÁNG NĂM Ở ĐÂY
const countDownDate = new Date("Sep 17, 2025 01:00:00").getTime();

// Cập nhật đồng hồ mỗi giây
const x = setInterval(function() {

    // Lấy thời gian hiện tại
    const now = new Date().getTime();

    // Tính khoảng cách thời gian còn lại
    const distance = countDownDate - now;

    // Tính toán ngày, giờ, phút, giây
    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

    // Hiển thị kết quả lên các element tương ứng
    document.getElementById("days").innerText = String(days).padStart(2, '0');
    document.getElementById("hours").innerText = String(hours).padStart(2, '0');
    document.getElementById("minutes").innerText = String(minutes).padStart(2, '0');
    document.getElementById("seconds").innerText = String(seconds).padStart(2, '0');

    // Nếu hết thời gian, hiển thị thông báo
    if (distance < 0) {
        clearInterval(x);
        document.querySelector(".content h1").innerText = "ĐÃ RA MẮT!";
        document.querySelector(".content p").innerText = "Chào mừng bạn đến với trang web của chúng tôi.";
        document.getElementById("countdown").style.display = "none";
    }
}, 1000);