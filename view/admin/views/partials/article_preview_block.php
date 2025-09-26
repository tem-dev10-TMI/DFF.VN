<?php
/**
 * Partial: article_preview_block.php (FULL)
 * Biến có sẵn trong scope:
 *   $title, $summary, $mainImage, $sections, $content, $articleMedia
 *   BASE_URL (đã define) + Bootstrap 5 đã load.
 */

/* Helper nối BASE_URL cho path tương đối (ảnh/video) */
$mkUrl = function (?string $p) {
    $p = trim((string) $p);
    if ($p === '')
        return '';
    if (preg_match('~^(https?:)?//~i', $p))
        return $p; // http(s) hoặc //
    return rtrim(BASE_URL, '/') . '/' . ltrim($p, '/');
};
?>

<div class="article-preview card border-0 shadow-sm">
    <div class="card-body p-3 p-md-4">

        <!-- ===== TÓM TẮT ===== -->
        <?php if (!empty($summary) || !empty($mainImage)): ?>
            <h5 class="ap-block-title">TÓM TẮT</h5>

            <?php if (!empty($summary)): ?>
                <div class="ap-content mb-3"><?= nl2br(htmlspecialchars($summary)) ?></div>
            <?php endif; ?>

            <?php if (!empty($mainImage)): ?>
                <?php $src = $mkUrl($mainImage); ?>
                <figure class="ap-figure-hero">
                    <img class="ap-hero-img" src="<?= htmlspecialchars($src) ?>" alt="<?= htmlspecialchars($title) ?>">
                </figure>
            <?php endif; ?>

            <hr class="ap-sep">
        <?php endif; ?>

        <!-- ===== CÓ SECTIONS ===== -->
        <?php if (!empty($sections)): ?>
            <?php $i = 1;
            foreach ($sections as $sec): ?>
                <section class="ap-section mb-4" data-section-id="<?= (int) $sec['id'] ?>">
                    <h5 class="ap-block-title">
                        PHẦN <?= $i ?><?= !empty($sec['title']) ? ': ' . htmlspecialchars($sec['title']) : '' ?>
                    </h5>

                    <?php if (!empty($sec['content'])): ?>
                        <div class="ap-content mb-3"><?= nl2br(htmlspecialchars($sec['content'])) ?></div>
                    <?php endif; ?>

                    <?php if (!empty($sec['media'])): ?>
                        <div class="row row-cols-1 row-cols-md-2 g-3">
                            <?php foreach ($sec['media'] as $m):
                                $type = $m['type'] ?? $m['media_type'] ?? '';
                                $path = $m['url'] ?? $m['media_url'] ?? '';
                                $url = $mkUrl($path);
                                ?>
                                <?php if ($type === 'image'): ?>
                                    <div class="col d-flex justify-content-center">
                                        <figure class="ap-figure-thumb m-0">
                                            <div class="ap-thumb">
                                                <img class="ap-thumb-img mx-auto" src="<?= htmlspecialchars($url) ?>"
                                                    alt="<?= htmlspecialchars($m['caption'] ?? '') ?>">
                                            </div>
                                            <?php if (!empty($m['caption'])): ?>
                                                <figcaption class="ap-caption"><?= htmlspecialchars($m['caption']) ?></figcaption>
                                            <?php endif; ?>
                                        </figure>
                                    </div>
                                <?php elseif ($type === 'video'): ?>
                                    <?php
                                    $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                                    $mime = ['mp4' => 'video/mp4', 'webm' => 'video/webm', 'ogg' => 'video/ogg', 'ogv' => 'video/ogg'][$ext] ?? 'video/mp4';
                                    ?>
                                    <div class="col d-flex justify-content-center">
                                        <figure class="ap-figure-thumb m-0">
                                            <div class="ap-thumb">
                                                <video class="ap-thumb-video mx-auto" controls preload="metadata">
                                                    <source src="<?= htmlspecialchars($url) ?>" type="<?= htmlspecialchars($mime) ?>">
                                                </video>
                                            </div>
                                            <?php if (!empty($m['caption'])): ?>
                                                <figcaption class="ap-caption"><?= htmlspecialchars($m['caption']) ?></figcaption>
                                            <?php endif; ?>
                                        </figure>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </section>
                <?php $i++; ?>
                <hr class="ap-sep">
            <?php endforeach; ?>

            <!-- ===== KHÔNG CÓ SECTIONS (fallback) ===== -->
        <?php else: ?>

            <?php if (!empty($content) || !empty($articleMedia)): ?>
                <h5 class="ap-block-title">NỘI DUNG</h5>
            <?php endif; ?>

            <?php if (!empty($content)): ?>
                <div class="ap-content mb-3"><?= nl2br(htmlspecialchars($content)) ?></div>
            <?php endif; ?>

            <?php if (!empty($articleMedia)): ?>
                <div class="row row-cols-1 row-cols-md-2 g-3">
                    <?php foreach ($articleMedia as $m):
                        $type = $m['media_type'] ?? '';
                        $path = $m['url'] ?? $m['media_url'] ?? '';
                        $url = $mkUrl($path);
                        ?>
                        <?php if ($type === 'image'): ?>
                            <div class="col d-flex justify-content-center">
                                <figure class="ap-figure-thumb m-0">
                                    <div class="ap-thumb">
                                        <img class="ap-thumb-img mx-auto" src="<?= htmlspecialchars($url) ?>"
                                            alt="<?= htmlspecialchars($m['caption'] ?? '') ?>">
                                    </div>
                                    <?php if (!empty($m['caption'])): ?>
                                        <figcaption class="ap-caption"><?= htmlspecialchars($m['caption']) ?></figcaption>
                                    <?php endif; ?>
                                </figure>
                            </div>
                        <?php elseif ($type === 'video'): ?>
                            <?php
                            $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                            $mime = ['mp4' => 'video/mp4', 'webm' => 'video/webm', 'ogg' => 'video/ogg', 'ogv' => 'video/ogg'][$ext] ?? 'video/mp4';
                            ?>
                            <div class="col d-flex justify-content-center">
                                <figure class="ap-figure-thumb m-0">
                                    <div class="ap-thumb">
                                        <video class="ap-thumb-video mx-auto" controls preload="metadata">
                                            <source src="<?= htmlspecialchars($url) ?>" type="<?= htmlspecialchars($mime) ?>">
                                        </video>
                                    </div>
                                    <?php if (!empty($m['caption'])): ?>
                                        <figcaption class="ap-caption"><?= htmlspecialchars($m['caption']) ?></figcaption>
                                    <?php endif; ?>
                                </figure>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
                <hr class="ap-sep">
            <?php endif; ?>

        <?php endif; ?>

    </div>
</div>

<style>
    /* Nền trắng – chữ đen, đọc rõ */
    .article-preview.card {
        background: #fff;
        color: #0f0f0f;
        border-radius: 14px;
    }

    .ap-content {
        color: #111;
        font-weight: 500;
        white-space: pre-wrap;
        line-height: 1.65;
        font-size: .98rem;
    }

    /* Tiêu đề khối (TÓM TẮT, PHẦN N, NỘI DUNG) */
    .ap-block-title {
        font-weight: 800;
        font-size: 1.05rem;
        letter-spacing: .2px;
        margin: .25rem 0 .85rem;
        position: relative;
        padding-left: .85rem;
        color: #0d0d0d;
    }

    .ap-block-title::before {
        content: "";
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 6px;
        background: #0d6efd;
        border-radius: 4px;
    }

    /* Vạch ngăn cách lớn giữa các khối */
    .ap-sep {
        border: 0;
        height: 2px;
        background: linear-gradient(90deg, transparent, #e5e7eb, transparent);
        margin: 1rem 0 1.25rem;
    }

    /* Ảnh main (hero) căn giữa */
    .ap-figure-hero {
        margin: 0 0 1rem;
        display: flex;
        justify-content: center;
    }

    .ap-hero-img {
        max-width: 720px;
        width: 100%;
        height: auto;
        margin: 0 auto;
        border-radius: 12px;
        border: 1px solid #eee;
        box-shadow: 0 4px 16px rgba(0, 0, 0, .06);
        display: block;
    }

    /* Thumb ảnh/video: luôn căn giữa, không dùng position absolute */
    .ap-figure-thumb {
        text-align: center;
        width: 100%;
        max-width: 520px;
    }

    /* giới hạn bề rộng -> nhìn gọn và nằm giữa */
    .ap-thumb {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 160px;
        background: #f8f9fa;
        border: 1px solid #eee;
        border-radius: 12px;
        overflow: hidden;
        padding: 6px;
        margin-left: auto;
        margin-right: auto;
    }

    .ap-thumb-img,
    .ap-thumb-video {
        max-width: 100%;
        max-height: 260px;
        object-fit: contain;
        /* không cắt ảnh, luôn giữa khung */
        display: block;
        margin-left: auto;
        margin-right: auto;
    }

    .ap-caption {
        font-size: .86rem;
        color: #444;
        line-height: 1.35;
        margin-top: .4rem;
        text-align: center;
    }

    /* Mobile chỉnh gọn hơn */
    @media (max-width: 575.98px) {
        .ap-figure-thumb {
            max-width: 100%;
        }

        .ap-thumb {
            min-height: 120px;
            padding: 4px;
        }

        .ap-thumb-img,
        .ap-thumb-video {
            max-height: 200px;
        }
    }
</style>