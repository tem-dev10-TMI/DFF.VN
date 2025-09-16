<style>
.list_comment {
    list-style: none;
    padding: 0;
    margin: 0;
}
.chat-item {
    display: flex;
    padding: 12px;
    border-bottom: 1px solid #eee;
}
.chat-avatar {
    width: 40px;
    height: 40px;
    margin-right: 10px;
}
.chat-avatar img, .avatar-fallback {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #007bff;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
}
.chat-body {
    flex: 1;
}
.chat-meta {
    font-size: 14px;
    color: #555;
    margin-bottom: 5px;
}
.chat-name {
    font-weight: bold;
    margin-right: 8px;
}
.chat-time {
    font-size: 12px;
    color: #999;
}
.chat-content {
    font-size: 14px;
    margin-bottom: 6px;
}
.chat-actions {
    font-size: 13px;
    color: #555;
}
.chat-actions .vote-btn {
    border: none;
    background: none;
    cursor: pointer;
    margin: 0 3px;
}
.chat-actions .chat-reply {
    margin-left: 10px;
    color: #007bff;
    cursor: pointer;
}
</style>

<ul class="list_comment col-md-12">
    <?php foreach ($comments as $c): ?>
        <li class="chat-item">
            <div class="chat-avatar">
                <?php if (!empty($c['avatar_url'])): ?>
                    <img src="<?= htmlspecialchars($c['avatar_url']) ?>" alt="<?= htmlspecialchars($c['username']) ?>">
                <?php else: ?>
                    <span class="avatar-fallback"><?= strtoupper(substr($c['username'], 0, 1)) ?></span>
                <?php endif; ?>
            </div>

            <div class="chat-body">
                <div class="chat-meta">
                    <span class="chat-name"><?= htmlspecialchars($c['username']) ?></span>
                    <span class="chat-time"><?= date('H:i d/m/Y', strtotime($c['created_at'])) ?></span>
                </div>

                <div class="chat-content">
                    <?= nl2br(htmlspecialchars($c['content'])) ?>
                </div>

                <div class="chat-actions">
                    <button class="vote-btn">⬆</button>
                    <span class="vote-count"><?= (int)$c['upvotes'] ?></span>
                    <button class="vote-btn">⬇</button>
                    <a href="javascript:void(0)" class="chat-reply">Trả lời</a>
                </div>
            </div>
        </li>
    <?php endforeach; ?>
</ul>
