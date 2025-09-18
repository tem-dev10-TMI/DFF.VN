// --- CÀI ĐẶT THỜI GIAN ĐẾM NGƯỢC ---
// Thời gian đích theo giờ địa phương (của bạn)
const targetDateLocal = new Date("Sep 17, 2025 9:00:00");
// Chuyển sang timestamp UTC
const targetDateUTC = targetDateLocal.getTime() - (targetDateLocal.getTimezoneOffset() * 60000);
// Thêm 7 tiếng (7 * 60 * 60 * 1000 ms) để về múi giờ +7
const countDownDate = targetDateUTC + 7 * 60 * 60 * 1000;

// Cập nhật đồng hồ mỗi giây
const x = setInterval(function() {
    const now = new Date().getTime();
    const distance = countDownDate - now;

    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

    document.getElementById("days").innerText = String(days).padStart(2, '0');
    document.getElementById("hours").innerText = String(hours).padStart(2, '0');
    document.getElementById("minutes").innerText = String(minutes).padStart(2, '0');
    document.getElementById("seconds").innerText = String(seconds).padStart(2, '0');

    if (distance < 0) {
        clearInterval(x);
        document.querySelector(".content h1").innerText = "ĐÃ RA MẮT!";
        document.querySelector(".content p").innerText = "Chào mừng bạn đến với trang web của chúng tôi.";
        document.getElementById("countdown").style.display = "none";
    }
}, 1000);
