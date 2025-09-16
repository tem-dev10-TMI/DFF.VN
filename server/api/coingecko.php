<?php
// Lightweight proxy to CoinGecko public API (no key required)
// Avoid CORS and standardize responses for the frontend/backend.

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');

$endpoint = isset($_GET['endpoint']) ? $_GET['endpoint'] : '';

function getJson($url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $res = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err = curl_error($ch);
    curl_close($ch);
    if ($res === false) {
        http_response_code(500);
        echo json_encode(['error' => 'Curl error', 'details' => $err]);
        exit;
    }
    if ($status < 200 || $status >= 300) {
        http_response_code($status);
        echo $res;
        exit;
    }
    echo $res;
    exit;
}

switch ($endpoint) {
    case 'global':
        getJson('https://api.coingecko.com/api/v3/global');
        break;
    case 'markets':
        $vs = isset($_GET['vs_currency']) ? urlencode($_GET['vs_currency']) : 'usd';
        $per = isset($_GET['per_page']) ? intval($_GET['per_page']) : 10;
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $url = 'https://api.coingecko.com/api/v3/coins/markets?vs_currency=' . $vs . '&order=market_cap_desc&per_page=' . $per . '&page=' . $page . '&sparkline=false&price_change_percentage=24h';
        getJson($url);
        break;
    case 'simple_price':
        $ids = isset($_GET['ids']) ? urlencode($_GET['ids']) : 'bitcoin,ethereum';
        $vs = isset($_GET['vs_currency']) ? urlencode($_GET['vs_currency']) : 'usd';
        $url = 'https://api.coingecko.com/api/v3/simple/price?ids=' . $ids . '&vs_currencies=' . $vs . '&include_24hr_change=true';
        getJson($url);
        break;
    default:
        http_response_code(400);
        echo json_encode(['error' => 'Unknown endpoint']);
        break;
}


