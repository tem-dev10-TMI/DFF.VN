document.addEventListener("DOMContentLoaded", function () {
    const allLis = document.querySelectorAll(
        ".block-k ul.nav-second-level li"
    );

    allLis.forEach(li => {
        const a = li.querySelector("a");
        if (a) {
            a.addEventListener("click", function () {
                // Xóa active ở tất cả
                allLis.forEach(item => item.classList.remove("active"));
                // Thêm active vào mục đang chọn
                li.classList.add("active");
            });
        }
    });
});
