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
            <form action="javascript:seekTo(to)">
                <label for="fname">Jump to (seconds)</label><br>
                <input type="text" id="to" name="to" value="20"><br>
            </form>

            <iframe height="300" width="400" src="https://www.tiktok.com/player/v1/6718335390845095173?&music_info=1&description=1" allow="fullscreen" title="test"></iframe>
            <br />

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
                <div class="container-lg app-shell p-3 p-md-4">
                    <div class="row g-4">
                        <!-- Cột TRÁI: danh sách video -->
                        <div class="col-lg-8 col-md-7 video-main">
                            <div class="vstack gap-4">
                                <!-- Video #1 -->
                                <div class="ratio-9x16 block rounded-2 overflow-hidden">
                                    <iframe src="https://www.tiktok.com/oembed?url=https://www.tiktok.com/@dff.vn/video/7550913252214066433?is_from_webapp=1&sender_device=pc"
                                        allow="autoplay; fullscreen"
                                        allowfullscreen
                                        class="w-100 h-100 border-0">
                                    </iframe>
                                </div>

                                <!-- Video #2 -->
                                <div class="ratio-9x16 block rounded-2 overflow-hidden">
                                    <blockquote class="tiktok-embed" cite="https://www.tiktok.com/@letuankhang2002/video/7527258883841101073" data-video-id="7527258883841101073" style="max-width: 605px;min-width: 325px;">
                                        <section> <a target="_blank" title="@letuankhang2002" href="https://www.tiktok.com/@letuankhang2002?refer=embed">@letuankhang2002</a> Tập cuối. <a title="letuankhang" target="_blank" href="https://www.tiktok.com/tag/letuankhang?refer=embed">#letuankhang</a> <a title="cellphones" target="_blank" href="https://www.tiktok.com/tag/cellphones?refer=embed">#Cellphones</a> <a target="_blank" title="♬ nhạc nền  - Lê Tuấn Khang" href="https://www.tiktok.com/music/nhạc-nền-Lê-Tuấn-Khang-7527259240907115281?refer=embed">♬ nhạc nền - Lê Tuấn Khang</a> </section>
                                    </blockquote>
                                    <script async src="https://www.tiktok.com/embed.js"></script>
                                </div>

                                <!-- Video #3 -->
                                <div class="ratio-9x16 block rounded-2 overflow-hidden">
                                    <iframe src="https://www.tiktok.com/player/v1/7552024696007445761?&music_info=1&description=1"
                                        allow="autoplay; fullscreen"
                                        allowfullscreen
                                        class="w-100 h-100 border-0">
                                    </iframe>
                                </div>
                            </div>
                        </div>

                        <!-- Cột PHẢI: đề xuất (không bị kéo xuống) -->
                        <div class="col-lg-4 col-md-5 d-none d-md-block video-suggest">
                            <div class="suggest sticky-top" style="top:-38px">
                                <div class="row row-cols-1 g-3">
                                    <div class="col">
                                        <div class="ratio-9x16 suggest-card overflow-hidden">
                                            <iframe src="https://www.tiktok.com/player/v1/7552024696007445761?&music_info=1&description=1"
                                                allow="autoplay; fullscreen"
                                                allowfullscreen
                                                class="w-100 h-100 border-0">
                                            </iframe>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="ratio-9x16 suggest-card overflow-hidden">
                                            <iframe src="https://www.tiktok.com/player/v1/7552024696007445761?&music_info=1&description=1"
                                                allow="autoplay; fullscreen"
                                                allowfullscreen
                                                class="w-100 h-100 border-0">
                                            </iframe>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="ratio-9x16 suggest-card overflow-hidden">
                                            <iframe src="https://www.tiktok.com/player/v1/7552024696007445761?&music_info=1&description=1"
                                                allow="autoplay; fullscreen"
                                                allowfullscreen
                                                class="w-100 h-100 border-0">
                                            </iframe>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
                        width="1200" height="630"
                    />

                    <div class="crypton-card__overlay" aria-hidden="true"></div>

                    <div class="crypton-card__content">
                        <h3 class="crypton-card__title">Crypton</h3>
                        <p class="crypton-card__desc">Tỷ giá • Biểu đồ • Tin tức</p>
                        <span class="crypton-card__cta">
                        Mở trang
                        <svg class="crypton-card__icon" width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M5 12h14M13 5l7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        </span>
                    </div>
                    </a>

                    <style>
                    .crypton-card {
                        --radius: 18px;
                        --shadow: 0 10px 30px rgba(0,0,0,.15);
                        --shadow-hover: 0 16px 40px rgba(0,0,0,.22);
                        position: relative;
                        display: block;
                        width: 100%;
                        max-width: 980px;
                        margin: 16px auto;
                        aspect-ratio: 21/9; /* Giữ tỉ lệ đẹp, responsive */
                        border-radius: var(--radius);
                        overflow: hidden;
                        box-shadow: var(--shadow);
                        text-decoration: none;
                        color: inherit;
                        transition: transform .25s ease, box-shadow .25s ease;
                        background: #0b1020;
                    }
                    .crypton-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-hover); }
                    .crypton-card:active { transform: translateY(0); }

                    .crypton-card:focus-visible {
                        outline: 3px solid #70b5ff;
                        outline-offset: 4px;
                    }

                    .crypton-card__img {
                        position: absolute; inset: 0;
                        width: 100%; height: 100%;
                        object-fit: cover;
                        filter: saturate(1.05) contrast(1.02);
                        transform: scale(1.02);
                    }

                    .crypton-card__overlay {
                        position: absolute; inset: 0;
                        background:
                        linear-gradient(180deg, rgba(4,6,20,.15) 0%, rgba(4,6,20,.55) 55%, rgba(4,6,20,.85) 100%),
                        radial-gradient(120% 120% at 100% 0%, rgba(0,140,255,.35) 0%, rgba(0,140,255,0) 60%);
                        pointer-events: none;
                    }

                    .crypton-card__content {
                        position: absolute; left: 24px; right: 24px; bottom: 22px;
                        display: grid; gap: 6px;
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
                        display: inline-flex; align-items: center; gap: 8px;
                        padding: 10px 14px;
                        font-weight: 600;
                        border-radius: 999px;
                        background: rgba(255,255,255,.1);
                        backdrop-filter: blur(4px);
                        transition: background .25s ease, gap .25s ease;
                        width: fit-content;
                    }
                    .crypton-card:hover .crypton-card__cta { background: rgba(255,255,255,.18); gap: 10px; }

                    .crypton-card__icon { transition: transform .25s ease; }
                    .crypton-card:hover .crypton-card__icon { transform: translateX(3px); }

                    @media (prefers-reduced-motion: reduce) {
                        .crypton-card, .crypton-card__icon { transition: none; }
                    }
                    </style>

                </div>
            </div>
            
        </div>
    </div>
    

</main>