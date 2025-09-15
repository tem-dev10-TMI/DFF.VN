<?php
class RssModel
{
    // Lấy items từ feed (trả về mảng maps tương thích view của bạn)
    public static function getFeedItems(string $feedUrl, int $limit = 50, int $cacheMinutes = 15): array
    {
        $cacheDir = __DIR__ . '/../../cache/rss';
        if (!is_dir($cacheDir)) {
            @mkdir($cacheDir, 0755, true);
        }
        $cacheFile = $cacheDir . '/' . md5($feedUrl) . '.json';

        // Dùng cache
        if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < $cacheMinutes * 60) {
            $json = file_get_contents($cacheFile);
            $data = json_decode($json, true);
            if (is_array($data)) return array_slice($data, 0, $limit);
        }

        $xmlString = self::fetchUrl($feedUrl);
        if (!$xmlString) return [];

        libxml_use_internal_errors(true);
        $xml = simplexml_load_string($xmlString, 'SimpleXMLElement', LIBXML_NOCDATA);
        if (!$xml) return [];

        $items = [];
        $count = 0;

        // RSS chuẩn
        if (isset($xml->channel->item)) {
            foreach ($xml->channel->item as $item) {
                if ($count >= $limit) break;

                $link = (string)$item->link;
                $description = (string)$item->description;
                $image = self::extractImageFromHtml($description) ?: (
                    isset($item->enclosure['url']) ? (string)$item->enclosure['url'] : null
                );
                // fallback media:content
                if (!$image && $item->children('media', true)->content) {
                    $image = (string)$item->children('media', true)->content->attributes()->url;
                }

                $pubDate = (string)$item->pubDate ?: '';
                $created_at = $pubDate ? date('Y-m-d H:i:s', strtotime($pubDate)) : date('Y-m-d H:i:s');

                $author_name = (string)$item->author ?: ((string)$item->children('dc', true)->creator ?: 'RSS Author');

                $items[] = [
                    'id' => md5($link),
                    'title' => (string)$item->title,
                    'summary' => self::makeSummary($description, 220),
                    'main_image_url' => $image,
                    'author_name' => $author_name,
                    'author_id' => 0,
                    'avatar_url' => '/vendor/dffvn/content/img/user.svg',
                    'created_at' => $created_at,
                    'upvotes' => 0,
                    'comment_count' => 0,
                    'link' => $link,
                    'is_rss' => true,
                ];

                $count++;
            }
        }

        // Atom feed fallback
        if (empty($items) && isset($xml->entry)) {
            foreach ($xml->entry as $entry) {
                if ($count >= $limit) break;

                $link = (string)$entry->link['href'] ?? (string)$entry->link;
                $description = (string)$entry->summary ?: (string)$entry->content;
                $image = self::extractImageFromHtml($description);
                $pubDate = (string)$entry->updated ?: (string)$entry->published;
                $created_at = $pubDate ? date('Y-m-d H:i:s', strtotime($pubDate)) : date('Y-m-d H:i:s');
                $author_name = (string)$entry->author->name ?: 'RSS Author';

                $items[] = [
                    'id' => md5($link),
                    'title' => (string)$entry->title,
                    'summary' => self::makeSummary($description, 220),
                    'main_image_url' => $image,
                    'author_name' => $author_name,
                    'author_id' => 0,
                    'avatar_url' => '/vendor/dffvn/content/img/user.svg',
                    'created_at' => $created_at,
                    'upvotes' => 0,
                    'comment_count' => 0,
                    'link' => $link,
                    'is_rss' => true,
                ];

                $count++;
            }
        }

        // Lưu cache
        file_put_contents($cacheFile, json_encode($items, JSON_UNESCAPED_UNICODE));

        return $items;
    }


    // Fetch bằng cURL (fallback file_get_contents)
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

    // Lấy ảnh đầu tiên trong HTML (description)
    private static function extractImageFromHtml(string $html): ?string
    {
        if (preg_match('/<img[^>]+src=[\'"]([^\'"]+)[\'"]/i', $html, $m)) {
            return $m[1];
        }
        return null;
    }

    // Tạo summary (strip tags + rút gọn)
    private static function makeSummary(string $html, int $len = 200): string
    {
        $text = trim(strip_tags(html_entity_decode($html)));
        if (mb_strlen($text) > $len) {
            return mb_substr($text, 0, $len) . '...';
        }
        return $text;
    }

    // (Tùy chọn) cố gắng tìm feed trên một trang HTML (tự động detect)
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
    // Lấy feed dưới dạng mảng thô (không xử lý)
    public static function getFeed($url)
    {
        $rss = @simplexml_load_file($url);
        if (!$rss) return [];

        $feed = [];
        foreach ($rss->channel->item as $item) {
            $feed[] = [
                'title' => (string)$item->title,
                'link' => (string)$item->link,
                'pubDate' => (string)$item->pubDate,
                'description' => (string)$item->description
            ];
        }
        return $feed;
    }
}
