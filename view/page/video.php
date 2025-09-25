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
        <!-- CỘT TRÁI: APP-SHELL (VIDEO CHÍNH + ĐỀ XUẤT) -->
        <div class="col-lg-8">
            <div class="container-lg app-shell p-3 p-md-4">
                <div class="row g-4">
                    <!-- Cột TRÁI: danh sách video -->
                    <div class="col-lg-8 col-md-7 video-main">
                        <div class="vstack gap-4">
                            <!-- Video #1 -->
                            <div class="ratio-9x16 block rounded-2 overflow-hidden">
                                <iframe src="https://www.tiktok.com/player/v1/7552024696007445761?&music_info=1&description=1" 
                                    allow="autoplay; fullscreen" 
                                    allowfullscreen 
                                    class="w-100 h-100 border-0">
                                </iframe>
                            </div>

                            <!-- Video #2 -->
                            <div class="ratio-9x16 block rounded-2 overflow-hidden">
                                <iframe src="https://www.tiktok.com/player/v1/7552024696007445761?&music_info=1&description=1" 
                                    allow="autoplay; fullscreen" 
                                    allowfullscreen 
                                    class="w-100 h-100 border-0">
                                </iframe>
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
            </div>
        </div>
    </div>
</div>

</main>