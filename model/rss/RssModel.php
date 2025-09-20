<?php
class RssModel
{
    /**
     * Lấy items từ nhiều feed (trả về mảng tương thích view)
     * @param array $feedUrls mảng url RSS
     * @param int $limit tổng số bài viết muốn lấy
     * @param int $cacheMinutes thời gian cache (phút)
     * @return array
     */
    public static function getMultipleFeeds(array $feedUrls, int $limit = 50, int $cacheMinutes = 15): array
    {
        $allItems = [];
        foreach ($feedUrls as $url) {
            $items = self::getFeedItems($url, $limit, $cacheMinutes);
            $allItems = array_merge($allItems, $items);
        }

        // Sắp xếp theo created_at giảm dần
        usort($allItems, function ($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });

        return array_slice($allItems, 0, $limit);
    }

    /**
     * Lấy items từ 1 RSS feed
     */
    public static function getFeedItems(string $feedUrl, int $limit = 50, int $cacheMinutes = 15): array
    {
        $cacheDir = __DIR__ . '/../../cache/rss';
        if (!is_dir($cacheDir)) @mkdir($cacheDir, 0755, true);

        $cacheFile = $cacheDir . '/' . md5($feedUrl) . '.json';

        // Dùng cache nếu còn hạn
        if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < $cacheMinutes * 60) {
            $json = file_get_contents($cacheFile);
            $data = json_decode($json, true);
            if (is_array($data)) return array_slice($data, 0, $limit);
        }

        // Fetch feed mới
        $xmlString = self::fetchUrl($feedUrl);
        if (!$xmlString) {
            // Fallback: nếu fetch thất bại nhưng có cache cũ, dùng tạm cache cũ
            if (file_exists($cacheFile)) {
                $json = file_get_contents($cacheFile);
                $data = json_decode($json, true);
                if (is_array($data)) return array_slice($data, 0, $limit);
            }
            return [];
        }

        libxml_use_internal_errors(true);
        $xml = simplexml_load_string($xmlString, 'SimpleXMLElement', LIBXML_NOCDATA);
        if (!$xml) {
            foreach (libxml_get_errors() as $err) error_log("RSS XML error: " . $err->message);
            libxml_clear_errors();
            return [];
        }

        $items = [];
        $count = 0;

        // RSS chuẩn
        if (isset($xml->channel->item)) {
            foreach ($xml->channel->item as $item) {
                if ($count >= $limit) break;
                $items[] = self::mapRssItem($item, $feedUrl);
                $count++;
            }
        }

        // Atom fallback
        if ($count == 0 && isset($xml->entry)) {
            foreach ($xml->entry as $entry) {
                if ($count >= $limit) break;
                $items[] = self::mapAtomItem($entry, $feedUrl);
                $count++;
            }
        }

        // Lưu cache
        if (!empty($items)) {
            file_put_contents($cacheFile, json_encode($items, JSON_UNESCAPED_UNICODE));
        }

        return $items;
    }

    private static function mapRssItem($item, $feedUrl): array
    {
        $link = (string)($item->link ?? '#');
        $description = (string)($item->description ?? '');
        $image = self::extractImageFromHtml($description);

        // Kiểm tra enclosure & media:content
        if (!$image && isset($item->enclosure['url'])) $image = (string)$item->enclosure['url'];
        $media = $item->children('media', true);
        if (!$image && isset($media->content) && isset($media->content->attributes()->url)) $image = (string)$media->content->attributes()->url;

        $pubDate = (string)($item->pubDate ?? '');
        $created_at = $pubDate ? date('Y-m-d H:i:s', strtotime($pubDate)) : date('Y-m-d H:i:s');

        $author_name = self::getAuthorName($item, $feedUrl);

        return [
            'id' => md5($link),
            'title' => html_entity_decode((string)($item->title ?? ''), ENT_QUOTES, 'UTF-8'),
            'summary' => self::makeSummary($description, 220),
            'main_image_url' => $image,
            'author_name' => $author_name,
            'author_id' => self::getAuthorIdByFeed($feedUrl), // <-- SỬA Ở ĐÂY
            'avatar_url' => self::getAvatarByFeed($feedUrl),
            'created_at' => $created_at,
            'upvotes' => 0,
            'comment_count' => 0,
            'link' => $link,
            'is_rss' => true,
        ];
    }

    private static function mapAtomItem($entry, $feedUrl): array
    {
        $link = '#';
        if (isset($entry->link)) {
            $link = isset($entry->link['href']) ? (string)$entry->link['href'] : (string)$entry->link;
        }

        $description = (string)($entry->summary ?? $entry->content ?? '');
        $image = self::extractImageFromHtml($description);
        $pubDate = (string)($entry->updated ?? $entry->published ?? '');
        $created_at = $pubDate ? date('Y-m-d H:i:s', strtotime($pubDate)) : date('Y-m-d H:i:s');

        $author_name = self::getAuthorName($entry, $feedUrl);

        return [
            'id' => md5($link),
            'title' => html_entity_decode((string)($entry->title ?? ''), ENT_QUOTES, 'UTF-8'),
            'summary' => self::makeSummary($description, 220),
            'main_image_url' => $image,
            'author_name' => $author_name,
            'author_id' => self::getAuthorIdByFeed($feedUrl), // <-- SỬA Ở ĐÂY
            'avatar_url' => self::getAvatarByFeed($feedUrl),
            'created_at' => $created_at,
            'upvotes' => 0,
            'comment_count' => 0,
            'link' => $link,
            'is_rss' => true,
        ];
    }

    // Lấy avatar theo feed
    private static function getAvatarByFeed($feedUrl): string
    {
        if (strpos($feedUrl, 'baochinhphu') !== false) return 'public/img/avatar/baochinhphu.png';
        if (strpos($feedUrl, 'thanhnien') !== false) return 'public/img/avatar/thanhnien.png';
        if (strpos($feedUrl, 'vnexpress.net') !== false) return 'public/img/avatar/vnexpress.png';
        if (strpos($feedUrl, 'tuoitre.vn') !== false) return 'public/img/avatar/tuoitre.png';
        if (strpos($feedUrl, 'cafef.vn') !== false) return 'public/img/avatar/cafef.png';
        if (strpos($feedUrl, 'vietnamnet.vn') !== false) return 'public/img/avatar/vietnamnet.png';
        if (strpos($feedUrl, 'dantri.com.vn') !== false) return 'public/img/avatar/dantri.png';
        if (strpos($feedUrl, 'znews.vn') !== false) return 'public/img/avatar/zingnews.png'; // zingnews.vn cũ
        
        return 'public/img/avatar/default.png';
    }

    // Lấy author name theo feed
    private static function getAuthorName($item, $feedUrl): string
    {
        if (strpos($feedUrl, 'baochinhphu') !== false) return 'Báo Chính Phủ';
        if (strpos($feedUrl, 'thanhnien') !== false) return 'Thanh Niên';
        if (strpos($feedUrl, 'vnexpress.net') !== false) return 'VNExpress';
        if (strpos($feedUrl, 'tuoitre.vn') !== false) return 'Tuổi Trẻ';
        if (strpos($feedUrl, 'cafef.vn') !== false) return 'CafeF';
        if (strpos($feedUrl, 'vietnamnet.vn') !== false) return 'Vietnamnet';
        if (strpos($feedUrl, 'dantri.com.vn') !== false) return 'Dân Trí';
        if (strpos($feedUrl, 'znews.vn') !== false) return 'Zing News'; // zingnews.vn cũ
        return 'RSS';
    }

    // HÀM MỚI: Lấy author ID theo feed
    private static function getAuthorIdByFeed($feedUrl): int
    {
        if (strpos($feedUrl, 'baochinhphu') !== false) return 66;
        if (strpos($feedUrl, 'thanhnien') !== false) return 67;
        if (strpos($feedUrl, 'vnexpress.net') !== false) return 94;
        if (strpos($feedUrl, 'tuoitre.vn') !== false) return 92;
        if (strpos($feedUrl, 'cafef.vn') !== false) return 70;
        if (strpos($feedUrl, 'vietnamnet.vn') !== false) return 93;
        if (strpos($feedUrl, 'dantri.com.vn') !== false) return 72;
        if (strpos($feedUrl, 'znews.vn') !== false) return 73;
        
        return 99; // ID mặc định cho các nguồn RSS khác
    }

    // Fetch URL bằng cURL/fallback
    private static function fetchUrl(string $url): ?string
    {
        if (function_exists('curl_init')) {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            $res = curl_exec($ch);
            $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            return ($res && $code >= 200 && $code < 400) ? $res : null;
        } else {
            $opts = ['http' => ['timeout' => 10, 'user_agent' => 'PHP']];
            $context = stream_context_create($opts);
            return @file_get_contents($url, false, $context) ?: null;
        }
    }

    private static function extractImageFromHtml(string $html): ?string
    {
        if (preg_match('/<img[^>]+src=[\'"]([^\'"]+)[\'"]/i', $html, $m)) return $m[1];
        return null;
    }

    private static function makeSummary(string $html, int $len = 200): string
    {
        $text = trim(strip_tags(html_entity_decode($html)));
        return mb_strlen($text) > $len ? mb_substr($text, 0, $len) . '...' : $text;
    }
}
