<?php include __DIR__ . '/../layout/header.php'; ?>

<h2 class="mb-4">Bài viết chờ duyệt</h2>
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Tiêu đề</th>
            <th>Tác giả</th>
            <th>Ngày gửi</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($articles as $article): ?>
        <tr>
            <td><?= htmlspecialchars($article['title']) ?></td>
            <td><?= htmlspecialchars($article['author_name']) ?></td>
            <td><?= $article['published_at'] ?></td>
            <td>
                <a href="admin.php?route=article&action=reviewAction&id=<?= $article['id'] ?>&do=approved" class="btn btn-success btn-sm">Duyệt</a>
                <a href="admin.php?route=article&action=reviewAction&id=<?= $article['id'] ?>&do=rejected" class="btn btn-danger btn-sm">Từ chối</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include __DIR__ . '/../layout/footer.php'; ?>
