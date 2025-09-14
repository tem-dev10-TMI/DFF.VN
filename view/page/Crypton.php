<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DFF.VN - Mạng Xã Hội Kinh Tế Tài Chính</title>
    
    <!-- CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/styles.css">
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

    <!-- JS -->
    <script src="<?= BASE_URL ?>/public/js/script.js"></script>
</body>
</html>
