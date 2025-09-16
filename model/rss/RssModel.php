<?php
class RssModel
{
    // Lấy items từ feed (trả về mảng maps tương thích view)
    public static function getFeedItems(string $feedUrl, int $limit = 50, int $cacheMinutes = 15): array
    {
        $cacheDir = __DIR__ . '/../../cache/rss';
        if (!is_dir($cacheDir)) {
            @mkdir($cacheDir, 0755, true);
        }
        $cacheFile = $cacheDir . '/' . md5($feedUrl) . '.json';

        // Dùng cache nếu còn hạn
        if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < $cacheMinutes * 60) {
            $json = file_get_contents($cacheFile);
            $data = json_decode($json, true);
            if (is_array($data)) return array_slice($data, 0, $limit);
        }

        // Fetch feed mới
        $xmlString = self::fetchUrl($feedUrl);
        if (!$xmlString) return [];

        libxml_use_internal_errors(true);
        $xml = simplexml_load_string($xmlString, 'SimpleXMLElement', LIBXML_NOCDATA);
        if (!$xml) {
            foreach (libxml_get_errors() as $err) {
                error_log("RSS XML error: " . $err->message);
            }
            libxml_clear_errors();
            return [];
        }

        $items = [];
        $count = 0;

        // RSS feed chuẩn
        if (isset($xml->channel->item)) {
            foreach ($xml->channel->item as $item) {
                if ($count >= $limit) break;
                $items[] = self::mapRssItem($item);
                $count++;
            }
        }

        // Atom feed fallback
        if ($count == 0 && isset($xml->entry)) {
            foreach ($xml->entry as $entry) {
                if ($count >= $limit) break;
                $items[] = self::mapAtomItem($entry);
                $count++;
            }
        }

        // Lưu cache nếu có item
        if (!empty($items)) {
            file_put_contents($cacheFile, json_encode($items, JSON_UNESCAPED_UNICODE));
        }

        return $items;
    }

    // Map RSS item sang mảng
    private static function mapRssItem($item): array
    {
        $link = (string)$item->link;
        $description = (string)$item->description;
        $image = self::extractImageFromHtml($description) ?: (
            isset($item->enclosure['url']) ? (string)$item->enclosure['url'] : null
        );
        // media:content fallback
        $media = $item->children('media', true);
        if (!$image && isset($media->content)) {
            $image = (string)$media->content->attributes()->url;
        }

        $pubDate = (string)$item->pubDate ?: '';
        $created_at = $pubDate ? date('Y-m-d H:i:s', strtotime($pubDate)) : date('Y-m-d H:i:s');
        $author_name = (string)$item->author ?: ((string)$item->children('dc', true)->creator ?: 'Báo Chính Phủ');

        return [
            'id' => md5($link),
            'title' => (string)$item->title,
            'summary' => self::makeSummary($description, 220),
            'main_image_url' => $image,
            'author_name' => $author_name,
            'author_id' => 66,
            'avatar_url' => 'public/img/avatar/baochinhphu.png',
            'created_at' => $created_at,
            'upvotes' => 0,
            'comment_count' => 0,
            'link' => $link,
            'is_rss' => true,
        ];
    }

    // Map Atom item sang mảng
    private static function mapAtomItem($entry): array
    {
        $link = (string)($entry->link['href'] ?? $entry->link);
        $description = (string)($entry->summary ?: $entry->content);
        $image = self::extractImageFromHtml($description);
        $pubDate = (string)($entry->updated ?: $entry->published);
        $created_at = $pubDate ? date('Y-m-d H:i:s', strtotime($pubDate)) : date('Y-m-d H:i:s');
        $author_name = (string)($entry->author->name ?: 'Doanh Nhân');

        return [
            'id' => md5($link),
            'title' => (string)$entry->title,
            'summary' => self::makeSummary($description, 220),
            'main_image_url' => $image,
            'author_name' => $author_name,
            'author_id' => 66,
            'avatar_url' => 'public/img/avatar/baochinhphu.png',
            'created_at' => $created_at,
            'upvotes' => 0,
            'comment_count' => 0,
            'link' => $link,
            'is_rss' => true,
        ];
    }

    // Fetch URL bằng cURL, fallback file_get_contents
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

    // Lấy ảnh đầu tiên từ HTML
    private static function extractImageFromHtml(string $html): ?string
    {
        if (preg_match('/<img[^>]+src=[\'"]([^\'"]+)[\'"]/i', $html, $m)) {
            return $m[1];
        }
        return null;
    }

    // Tạo summary từ HTML
    private static function makeSummary(string $html, int $len = 200): string
    {
        $text = trim(strip_tags(html_entity_decode($html)));
        if (mb_strlen($text) > $len) {
            return mb_substr($text, 0, $len) . '...';
        }
        return $text;
    }

    // (Tùy chọn) tự động detect feed từ trang HTML
    public static function findRssLinksOnPage(string $pageUrl): array
    {
        $html = self::fetchUrl($pageUrl);
        if (!$html) return [];
        $results = [];
        if (preg_match_all('/<link[^>]+rel=[\'"]alternate[\'"][^>]*>/i', $html, $links)) {
            foreach ($links[0] as $link) {
                if (preg_match('/type=[\'"](application\/rss\+xml|application\/atom\+xml)[\'"]/i', $link)) {
                    if (preg_match('/href=[\'"]([^\'"]+)[\'"]/i', $link, $m)) {
                        $href = $m[1];
                        if (!preg_match('#^https?://#', $href)) {
                            $href = rtrim($pageUrl, '/') . '/' . ltrim($href, '/');
                        }
                        $results[] = $href;
                    }
                }
            }
        }
        return array_unique($results);
    }

    // Lấy feed thô (không xử lý)
    public static function getFeedRaw(string $url): array
    {
        $rss = @simplexml_load_file($url);
        if (!$rss) return [];

        $feed = [];
        if (isset($rss->channel->item)) {
            foreach ($rss->channel->item as $item) {
                $feed[] = [
                    'title' => (string)$item->title,
                    'link' => (string)$item->link,
                    'pubDate' => (string)$item->pubDate,
                    'description' => (string)$item->description
                ];
            }
        }
        return $feed;
    }
}
