<main class="main-content">
    <style>
        /* ====== LAYOUT / SHELL ====== */
        .app-shell {
            overflow: hidden;
        }

        .video-main {
            margin-top: 0;
        }

        .video-suggest {
            margin-top: 0;
        }

        /* ====== RATIO 9:16 CHUẨN ====== */
        .ratio-9x16 {
            position: relative;
            width: 100%
        }

        .ratio-9x16::before {
            content: "";
            display: block;
            padding-top: 177.78%
        }

        .ratio-9x16>* {
            position: absolute;
            inset: 0
        }

        /* ====== PLAY BTN ====== */
        .play-btn {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center
        }

        .play-btn .triangle {
            width: 0;
            height: 0;
            border-left: 28px solid #fff;
            border-top: 18px solid transparent;
            border-bottom: 18px solid transparent;
            opacity: .95
        }

        .block {
            background: #b5bcc4
        }

        /* ====== SUGGEST CARDS (nhỏ, không scroll) ====== */
        .suggest .suggest-card {
            border-radius: .5rem;
            overflow: hidden;
            background: #b5bcc4
        }

        .suggest .overlay {
            background: linear-gradient(to top, rgba(0, 0, 0, .75) 30%, rgba(0, 0, 0, 0) 60%);
            color: #fff;
            padding: .5rem;
            font-size: 12px;
            display: flex;
            align-items: end
        }

        .suggest h6 {
            font-size: 13px;
            margin: 0 0 .25rem 0
        }

        .suggest p {
            font-size: 12px;
            margin: 0 0 .25rem 0
        }

        .suggest .tags {
            gap: .25rem
        }

        .suggest .tags span {
            font-size: 11px
        }

        .suggest .ratio-9x16 {
            max-height: 180px
        }

        .suggest .ratio-9x16>* {
            max-height: none
        }

        @media (max-width: 991.98px) {
            .ads-col {
                display: none
            }
        }
    </style>
    <div class="container-fluid">
        <div class="row">


            <script>
                // Receive messages 
                window.addEventListener('message', (event) => {
                    // do something
                });

                // Send messages
                function seekTo(to) {
                    const iframe = document.querySelector("iframe");
                    iframe.contentWindow.postMessage({
                        type: "seekTo",
                        value: Number(to.value),
                        "x-tiktok-player": true
                    }, '*');
                }
            </script>
            <!-- CỘT TRÁI: APP-SHELL (VIDEO CHÍNH + ĐỀ XUẤT) -->
            <div class="col-lg-8">
            <style>
        /* ===== FONT & NỀN (Giữ nguyên từ code của bạn) ===== */
        @import url('https://fonts.googleapis.com/css2?family=Fredoka:wght@700&display=swap');

        .cartoon-coming-soon {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 80vh;
            background: linear-gradient(135deg, #ffe259, #ffa751); /* Vàng cam tươi sáng */
            border-radius: 2rem;
            padding: 2rem;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            isolation: isolate; /* Để z-index hoạt động đúng */
        }

        /* ===== NỘI DUNG CHỮ (Giữ nguyên và tinh chỉnh) ===== */
        .cartoon-content {
            text-align: center;
            color: #fff;
            font-family: 'Fredoka', sans-serif;
            z-index: 2; /* Nổi lên trên nhân vật */
            position: relative;
        }

        .cartoon-content h1 {
            font-size: clamp(2.5rem, 8vw, 4rem); /* Responsive hơn */
            color: #fff;
            text-shadow: 3px 5px 0 #ff8c00, 5px 7px 0 rgba(0, 0, 0, 0.2); /* Đổ bóng đậm chất hoạt hình */
            animation: bounceText 1.5s ease-in-out infinite;
        }

        .cartoon-content p {
            font-size: 1.25rem;
            margin-top: 1rem;
            color: #fffaf0;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.2);
        }

        @keyframes bounceText {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        /* ===== NHÂN VẬT FINN MỚI BẰNG CSS ===== */
        .finn-character {
            position: absolute;
            bottom: 10%;
            left: -200px; /* Xuất phát từ ngoài màn hình */
            width: 150px;
            height: 250px;
            z-index: 1; /* Nằm dưới text */
            animation: runAcrossScreen 10s linear infinite;
        }
        
        /* Các bộ phận */
        .finn-character .head, .finn-character .torso, .finn-character .backpack,
        .finn-character .arm, .finn-character .leg {
            position: absolute;
            background-color: #ffe4c4; /* Màu da */
        }

        .finn-character .head {
            width: 80px;
            height: 90px;
            background-color: #fff; /* Mũ trắng */
            border-radius: 45% 45% 40% 40%;
            top: 0;
            left: 35px;
            border: 2px solid #333;
            animation: body-bob 0.4s ease-in-out infinite;
        }
        .finn-character .head::before, .finn-character .head::after { /* Tai mũ */
            content: '';
            position: absolute;
            width: 20px;
            height: 25px;
            background-color: #fff;
            border: 2px solid #333;
            border-radius: 50% 50% 0 0;
            top: -15px;
        }
        .finn-character .head::before { left: 5px; }
        .finn-character .head::after { right: 5px; }

        .finn-character .face {
            position: absolute;
            width: 50px;
            height: 40px;
            background-color: #ffe4c4;
            border-radius: 0 0 40% 40%;
            bottom: 0;
            left: 13px;
        }
        .finn-character .eye {
            position: absolute;
            width: 8px;
            height: 8px;
            background-color: #333;
            border-radius: 50%;
            top: 15px;
        }
        .finn-character .eye.left { left: 10px; }
        .finn-character .eye.right { right: 10px; }

        .finn-character .torso {
            width: 60px;
            height: 70px;
            background-color: #41a6f6; /* Áo xanh */
            top: 85px;
            left: 45px;
            border-radius: 10px;
            border: 2px solid #333;
            animation: body-bob 0.4s ease-in-out infinite;
        }

        .finn-character .backpack {
            width: 70px;
            height: 60px;
            background-color: #81c03e; /* Balo xanh lá */
            top: 80px;
            left: 35px;
            border-radius: 15px;
            border: 2px solid #333;
            z-index: -1;
        }

        .finn-character .arm {
            width: 15px;
            height: 60px;
            border-radius: 10px;
            border: 2px solid #333;
            transform-origin: top center;
        }

        .finn-character .leg {
            width: 18px;
            height: 60px;
            border: 2px solid #333;
            transform-origin: top center;
        }
        .finn-character .leg::before { /* Quần */
            content: '';
            position: absolute;
            width: 100%;
            height: 25px;
            background-color: #2b3a88; /* Quần xanh đậm */
        }
         .finn-character .leg::after { /* Vớ */
            content: '';
            position: absolute;
            width: 100%;
            height: 15px;
            background-color: #fff;
            bottom: 0;
        }
        .finn-character .foot {
            position: absolute;
            width: 30px;
            height: 12px;
            background-color: #333;
            border-radius: 5px;
            bottom: -10px;
            left: -5px;
        }
        
        /* Vị trí các chi */
        .finn-character .arm.left { top: 90px; left: 35px; animation: run-arm-left 0.4s ease-in-out infinite; }
        .finn-character .arm.right { top: 90px; left: 100px; animation: run-arm-right 0.4s ease-in-out infinite; }
        .finn-character .leg.left { top: 150px; left: 50px; animation: run-leg-left 0.4s ease-in-out infinite; }
        .finn-character .leg.right { top: 150px; left: 70px; animation: run-leg-right 0.4s ease-in-out infinite; }

        /* ===== KEYFRAMES ANIMATION ===== */

        /* Animation chạy ngang màn hình */
        @keyframes runAcrossScreen {
            0% { transform: translateX(0) scaleX(1); }
            48% { transform: translateX(120vw) scaleX(1); }
            50% { transform: translateX(120vw) scaleX(-1); } /* Quay đầu */
            98% { transform: translateX(0) scaleX(-1); }
            100% { transform: translateX(0) scaleX(1); } /* Quay đầu lại */
        }
        
        /* Animation nhún nhảy */
        @keyframes body-bob {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }

        /* Animation các chi */
        @keyframes run-arm-left {
            0%, 100% { transform: rotate(45deg); }
            50% { transform: rotate(-45deg); }
        }
        @keyframes run-arm-right {
            0%, 100% { transform: rotate(-45deg); }
            50% { transform: rotate(45deg); }
        }
        @keyframes run-leg-left {
            0%, 100% { transform: rotate(-30deg); }
            50% { transform: rotate(30deg); }
        }
        @keyframes run-leg-right {
            0%, 100% { transform: rotate(30deg); }
            50% { transform: rotate(-30deg); }
        }

    </style>

    <div class="cartoon-coming-soon">
        <div class="cartoon-content">
            <h1>SẮP RA MẮT</h1>
            <p>
                Finn và Jake đang trên đường mang tính năng mới đến cho bạn.
                Quay lại ngay nhé!
            </p>
        </div>

        <div class="finn-character">
            <div class="backpack"></div>
            <div class="head">
                <div class="face">
                    <div class="eye left"></div>
                    <div class="eye right"></div>
                </div>
            </div>
            <div class="torso"></div>
            <div class="arm left"></div>
            <div class="arm right"></div>
            <div class="leg left">
                <div class="foot"></div>
            </div>
            <div class="leg right">
                <div class="foot"></div>
            </div>
        </div>
    </div>



                <!-- ===== KẾT THÚC: DÁN CODE TỚI ĐÂY ===== -->
            </div>

            <!-- CỘT PHẢI: QUẢNG CÁO (DIV RIÊNG) -->
            <div class="col-lg-3 ads-col">
                <div class="content-right">
                    <div class="adv-banner mb-3">
                        <a href="#" target="_blank" rel="nofollow">
                            <img src="<?= BASE_URL ?>/public/img/banner/Post4.jpg" alt="Banner" class="img-fluid rounded-2" />
                        </a>
                    </div>
                    <div class="adv-banner mb-3">
                        <a href="#" target="_blank" rel="nofollow">
                            <img src="<?= BASE_URL ?>/public/img/banner/Post3.jpg" alt="Banner" class="img-fluid rounded-2" />
                        </a>
                    </div>
                    <div class="adv-banner mb-3">
                        <a href="#" target="_blank" rel="nofollow">
                            <img src="<?= BASE_URL ?>/public/img/banner/Post1.jpg" alt="Banner" class="img-fluid rounded-2" />
                        </a>
                    </div>
                    <div class="adv-banner mb-3">
                        <a href="#" target="_blank" rel="nofollow">
                            <img src="<?= BASE_URL ?>/public/img/banner/Post2.jpg" alt="Banner" class="img-fluid rounded-2" />
                        </a>
                    </div>
                    <!-- Crypton Card -->
                    <a class="crypton-card" href="<?= BASE_URL ?>/crypton" aria-label="Đi đến trang Crypton">
                        <img
                            class="crypton-card__img"
                            src="<?= BASE_URL ?>/public/img/Crypton.png"
                            alt="Crypto — đi đến trang Crypton"
                            loading="lazy"
                            width="1200" height="630" />

                        <div class="crypton-card__overlay" aria-hidden="true"></div>

                        <div class="crypton-card__content">
                            <h3 class="crypton-card__title">Crypton</h3>
                            <p class="crypton-card__desc">Tỷ giá • Biểu đồ • Tin tức</p>
                            <span class="crypton-card__cta">
                                Mở trang
                                <svg class="crypton-card__icon" width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <path d="M5 12h14M13 5l7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </span>
                        </div>
                    </a>

                    <style>
                        .crypton-card {
                            --radius: 18px;
                            --shadow: 0 10px 30px rgba(0, 0, 0, .15);
                            --shadow-hover: 0 16px 40px rgba(0, 0, 0, .22);
                            position: relative;
                            display: block;
                            width: 100%;
                            max-width: 980px;
                            margin: 16px auto;
                            aspect-ratio: 21/9;
                            /* Giữ tỉ lệ đẹp, responsive */
                            border-radius: var(--radius);
                            overflow: hidden;
                            box-shadow: var(--shadow);
                            text-decoration: none;
                            color: inherit;
                            transition: transform .25s ease, box-shadow .25s ease;
                            background: #0b1020;
                        }

                        .crypton-card:hover {
                            transform: translateY(-2px);
                            box-shadow: var(--shadow-hover);
                        }

                        .crypton-card:active {
                            transform: translateY(0);
                        }

                        .crypton-card:focus-visible {
                            outline: 3px solid #70b5ff;
                            outline-offset: 4px;
                        }

                        .crypton-card__img {
                            position: absolute;
                            inset: 0;
                            width: 100%;
                            height: 100%;
                            object-fit: cover;
                            filter: saturate(1.05) contrast(1.02);
                            transform: scale(1.02);
                        }

                        .crypton-card__overlay {
                            position: absolute;
                            inset: 0;
                            background:
                                linear-gradient(180deg, rgba(4, 6, 20, .15) 0%, rgba(4, 6, 20, .55) 55%, rgba(4, 6, 20, .85) 100%),
                                radial-gradient(120% 120% at 100% 0%, rgba(0, 140, 255, .35) 0%, rgba(0, 140, 255, 0) 60%);
                            pointer-events: none;
                        }

                        .crypton-card__content {
                            position: absolute;
                            left: 24px;
                            right: 24px;
                            bottom: 22px;
                            display: grid;
                            gap: 6px;
                            color: #eef3ff;
                        }

                        .crypton-card__title {
                            margin: 0;
                            font-size: clamp(20px, 3.2vw, 28px);
                            font-weight: 700;
                            letter-spacing: .2px;
                        }

                        .crypton-card__desc {
                            margin: 0 0 6px 0;
                            font-size: clamp(13px, 2vw, 15px);
                            opacity: .9;
                        }

                        .crypton-card__cta {
                            display: inline-flex;
                            align-items: center;
                            gap: 8px;
                            padding: 10px 14px;
                            font-weight: 600;
                            border-radius: 999px;
                            background: rgba(255, 255, 255, .1);
                            backdrop-filter: blur(4px);
                            transition: background .25s ease, gap .25s ease;
                            width: fit-content;
                        }

                        .crypton-card:hover .crypton-card__cta {
                            background: rgba(255, 255, 255, .18);
                            gap: 10px;
                        }

                        .crypton-card__icon {
                            transition: transform .25s ease;
                        }

                        .crypton-card:hover .crypton-card__icon {
                            transform: translateX(3px);
                        }

                        @media (prefers-reduced-motion: reduce) {

                            .crypton-card,
                            .crypton-card__icon {
                                transition: none;
                            }
                        }
                    </style>

                </div>
            </div>

        </div>
    </div>


</main>