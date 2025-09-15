document.addEventListener("DOMContentLoaded", function () {
    let currentIndex = 10; // ban đầu đã hiển thị 10 bài
    const items = document.querySelectorAll("#articles-list .article-item");
    const loading = document.getElementById("loading");

    window.addEventListener("scroll", function () {
        // Khi cuộn gần chạm cuối trang
        if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 200) {
            if (currentIndex < items.length) {
                loading.style.display = "block";

                setTimeout(() => {
                    // Hiển thị thêm 10 bài tiếp theo
                    for (let i = currentIndex; i < currentIndex + 10 && i < items.length; i++) {
                        items[i].style.display = "block";
                    }
                    currentIndex += 10;
                    loading.style.display = "none";
                }, 500); // giả lập thời gian load
            }
        }
    });
});
