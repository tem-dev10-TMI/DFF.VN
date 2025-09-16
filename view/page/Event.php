<div class="content-left">
    <div class="block-k">
        <div class="detail">
            <!-- Tiêu đề sự kiện -->
            <h1><?= htmlspecialchars($event['title'] ?? 'Sự kiện'); ?></h1>

            <!-- Thời gian còn lại -->
            <div class="d-time">
                <?php if (!empty($event['event_date'])): ?>
                    <?php
                    $today = new DateTime();
                    $eventDate = new DateTime($event['event_date']);
                    $diff = $today->diff($eventDate);
                    $days = $diff->days;

                    if ($eventDate >= $today) {
                        echo "<span>Còn $days ngày</span>";
                    } else {
                        echo "<span>Đã diễn ra $days ngày trước</span>";
                    }
                    ?>
                <?php endif; ?>
            </div>

            <div class="line"></div>

            <!-- Nội dung sự kiện -->
            <article>
                <?php if (!empty($event['event_date'])): ?>
                    <p>- Ngày giao dịch không hưởng quyền: <?= date('d/m/Y', strtotime($event['event_date'])); ?></p>
                <?php endif; ?>

                <?php if (!empty($event['description'])): ?>
                    <p>- Lý do mục đích: <?= nl2br(htmlspecialchars($event['description'])); ?></p>
                <?php endif; ?>

                <?php if (!empty($event['stock_ticker'])): ?>
                    <p>- Mã chứng khoán: <?= htmlspecialchars($event['stock_ticker']); ?></p>
                <?php endif; ?>
            </article>

            <!-- Hidden ID -->
            <input type="hidden" id="hdd_id" value="<?= htmlspecialchars($event['id'] ?? ''); ?>">
        </div>
    </div>
</div>
