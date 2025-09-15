<style>
    ul li.active svg,
    ul li.active i {
        color: red;
        border: 2px solid red;
        border-radius: 6px;
        padding: 2px;
        display: inline-block;
    }

    ul li svg,
    ul li i {
        border: none;
        padding: 0;
        color: inherit;
    }
</style>

<!-- START: Mobile sidebar menu (thay thế phần menu hiện tại) -->
<nav class="sidebar-mobile">
  <ul class="menu">
    <li><a href="/"><i class="fas fa-home icon"></i> <span>Trang chủ</span></a></li>
    <li><a href="/latest"><i class="far fa-clock icon"></i> <span>Mới nhất</span></a></li>
    <li><a href="/trends"><i class="fas fa-fire icon"></i> <span>Xu hướng</span></a></li>
    <li><a href="/comments"><i class="far fa-comment-dots icon"></i> <span>Bình luận</span></a></li>

    <li class="section-title">CHỦ ĐỀ</li>
    <li><a href="/topic/vi-mo"><img class="topic-thumb" src="public/img/topic-vimo.svg" alt=""> <span>Vĩ mô</span></a></li>
    <li><a href="/topic/thi-truong"><img class="topic-thumb" src="public/img/topic-thitruong.svg" alt=""> <span>Thị trường</span></a></li>
    <li><a href="/topic/crypto"><i class="fab fa-bitcoin icon"></i> <span>Crypto</span></a></li>
    <li><a href="/topic/360"><i class="fas fa-industry icon"></i> <span>360° Doanh nghiệp</span></a></li>
    <li><a href="/topic/tai-chinh"><i class="fas fa-wallet icon"></i> <span>Tài chính</span></a></li>
    <li><a href="/topic/nha-dat"><i class="fas fa-building icon"></i> <span>Nhà đất</span></a></li>
    <li><a href="/topic/quoc-te"><i class="fas fa-globe icon"></i> <span>Quốc tế</span></a></li>
    <li><a href="/topic/thao-luan"><i class="fas fa-comments icon"></i> <span>Thảo luận</span></a></li>

    <li class="section-title">VỀ</li>
    <li><a href="/about"><i class="fas fa-users icon"></i> <span>Về chúng tôi</span></a></li>
    <li><a href="/policy-content"><i class="fas fa-file-contract icon"></i> <span>Chính sách nội dung</span></a></li>
    <li><a href="/privacy"><i class="fas fa-shield-alt icon"></i> <span>Chính sách riêng tư</span></a></li>
    <li><a href="/ads"><i class="fas fa-ad icon"></i> <span>Quảng cáo</span></a></li>
  </ul>
</nav>
<!-- END: Mobile sidebar menu -->

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const allLis = document.querySelectorAll("ul li");
        allLis.forEach(li => {
            const a = li.querySelector("a");
            if (a) {
                a.addEventListener("click", function(e) {
                    allLis.forEach(item => item.classList.remove("active"));
                    li.classList.add("active");
                });
            }
        });
    });
</script>