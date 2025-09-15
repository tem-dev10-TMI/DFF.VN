<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DFF.VN - Mạng Xã Hội Kinh Tế Tài Chính</title>

    <!-- CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/style_of_crypton.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body>
    <!-- Header -->
    <header class="header">
        <div class="header-content">
            <!-- Logo -->
            <div class="logo-section">
                <div class="logo">DFF.VN</div>
                <div class="tagline">MẠNG XÃ HỘI KINH TẾ - TÀI CHÍNH</div>
            </div>

            <!-- Search -->
            <div class="search-section">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Tìm kiếm" id="mainSearch">
                </div>
            </div>

            <!-- Date -->
            <div class="date-section">
                <span id="currentDate"><?= htmlspecialchars($currentDate) ?></span>
            </div>

            <!-- User menu -->
            <div class="user-section">
                <button class="icon-btn"><i class="fas fa-bars"></i></button>
                <button class="icon-btn"><i class="fas fa-plus"></i></button>
                <button class="icon-btn notification-btn">
                    <i class="fas fa-bell"></i>
                    <span class="notification-badge">4</span>
                </button>
                <div class="user-avatar">A</div>
            </div>
        </div>
    </header>

    <!-- Market Overview -->
    <section class="market-overview">
        <div class="market-scroll">
            <?php if (!empty($markets)): ?>
                <?php foreach ($markets as $m): ?>
                    <div class="market-item <?= $m['class'] ?>" data-symbol="<?= htmlspecialchars($m['symbol']) ?>">
                        <div class="symbol"><?= htmlspecialchars($m['symbol']) ?></div>
                        <div class="price"><?= number_format($m['price'], 2) ?></div>
                        <div class="change"><?= htmlspecialchars($m['change']) ?></div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Không có dữ liệu thị trường.</p>
            <?php endif; ?>
        </div>
    </section>
    <main class="main-content">
        <!-- Left Panel - Chart -->
        <div class="chart-panel">
            <div class="chart-header">
                <div class="chart-tabs">
                    <button class="tab-btn">Tổng quan</button>
                    <button class="tab-btn">Xu hướng</button>
                    <button class="tab-btn active">Về Bitcoin</button>
                    <button class="tab-btn">Liên hệ</button>
                </div>

                <div class="chart-controls">
                    <div class="symbol-search">
                        <i class="fas fa-search"></i>
                        <input type="text" value="BTCUSDT" id="symbolSearch">
                        <button class="add-btn"><i class="fas fa-plus"></i></button>
                    </div>

                    <div class="exchange-select">
                        <label for="providerSelect">Sàn:</label>
                        <select id="providerSelect">
                            <option value="binance" selected>Binance (Crypto)</option>
                            <option value="fmp">FMP (Stocks)</option>
                            <option value="stooq">Stooq (Stocks)</option>
                        </select>
                    </div>

                    <div class="timeframe-buttons">
                        <button class="time-btn">1m</button>
                        <button class="time-btn">30m</button>
                        <button class="time-btn active">15m</button>
                        <button class="time-btn">1h</button>
                    </div>

                    <div class="chart-tools">
                        <button class="tool-btn active"><i class="fas fa-chart-line"></i></button>
                        <button class="tool-btn"><i class="fas fa-chart-bar"></i></button>
                        <button class="tool-btn">Indicators</button>
                    </div>
                </div>
            </div>

            <div class="chart-info">
                <div class="symbol-info">
                    <span id="symbolInfoText">Bitcoin / TetherUS · 15 · Binance</span>
                </div>
                <div class="ohlc-info">
                    <span>O 115,504.63</span>
                    <span>H 115,557.49</span>
                    <span>L 115,368.28</span>
                    <span>C 115,368.29</span>
                </div>
                <div class="price-change">
                    <span class="change negative">-136.33 (-0.12%)</span>
                </div>
                <div class="volume">
                    <span>Vol - BTC 63</span>
                </div>
            </div>

            <div class="chart-container">
                <div id="tradingview_chart">
                    <div class="loading-indicator">
                        <div class="spinner"></div>
                        <p>Đang tải biểu đồ...</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Panel - Sidebar -->
        <div class="sidebar">
            <div class="crypto-search">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Tra cứu crypto: BTC, ETH, SOL, BNB,..." id="cryptoSearch">
                </div>
            </div>

            <div class="bitcoin-converter">
                <div class="converter-header">
                    <i class="fab fa-bitcoin"></i>
                    <span>Bitcoin</span>
                </div>
                <div class="converter-body">
                    <input type="number" value="1" id="btcAmount">
                    <div class="converter-arrow">
                        <i class="fas fa-exchange-alt"></i>
                    </div>
                    <div class="converter-to">
                        <i class="fas fa-flag-usa"></i>
                        <span>USD</span>
                    </div>
                    <div class="converter-result" id="btcResult">115,530.00</div>
                </div>
            </div>

            <div class="comment-section">
                <div class="comment-input">
                    <div class="user-avatar-small">A</div>
                    <input type="text" placeholder="Viết bài, chia sẻ, đặt câu hỏi...">
                    <button class="image-upload"><i class="fas fa-image"></i></button>
                </div>

                <div class="comments-header">
                    <i class="fas fa-comments"></i>
                    <span>Bình luận</span>
                </div>

                <div class="comments-list" id="commentsList">
                    <!-- Comments will be loaded here -->
                </div>
            </div>

            <button class="scroll-top" id="scrollTop">
                <i class="fas fa-arrow-up"></i>
            </button>
        </div>
    </main>
    <!-- JS -->
    <script src="<?= BASE_URL ?>/public/js/script.js"></script>
</body>

</html>