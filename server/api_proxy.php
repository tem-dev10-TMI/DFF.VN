<?php
// Simple internal proxy to reuse our API endpoints from PHP without CORS issues.
// Usage: api_proxy.php?type=global|markets|price|news

header('Content-Type: text/plain; charset=utf-8');

$type = isset($_GET['type']) ? $_GET['type'] : '';

function fetch($url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $res = curl_exec($ch);
    curl_close($ch);
    return $res === false ? '' : $res;
}

switch ($type) {
    case 'global':
        echo fetch('./api/coingecko.php?endpoint=global');
        break;
    case 'markets':
        $per = isset($_GET['per_page']) ? intval($_GET['per_page']) : 8;
        echo fetch('./api/coingecko.php?endpoint=markets&per_page=' . $per);
        break;
    case 'price':
        $ids = isset($_GET['ids']) ? $_GET['ids'] : 'bitcoin,ethereum';
        echo fetch('./api/coingecko.php?endpoint=simple_price&ids=' . urlencode($ids));
        break;
    case 'cmc_global':
        echo fetch('./api/cmc.php?endpoint=global');
        break;
    case 'cmc_listings':
        $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;
        echo fetch('./api/cmc.php?endpoint=listings&limit=' . $limit);
        break;
    case 'cmc_quotes':
        $symbol = isset($_GET['symbol']) ? urlencode($_GET['symbol']) : 'BTC,ETH';
        echo fetch('./api/cmc.php?endpoint=quotes&symbol=' . $symbol);
        break;
    case 'news':
        echo fetch('./api/news.php');
        break;
    default:
        echo '';
}


