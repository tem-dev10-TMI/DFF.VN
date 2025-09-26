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
                </div>
            </div>
        </div>
    </div>

</main>