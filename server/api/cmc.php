<?php
// CoinMarketCap proxy (requires API key)
// Set CMC_API_KEY in server/config.php or environment

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');

$configPath = __DIR__ . '/../config.php';
if (file_exists($configPath)) require $configPath;

$apiKey = defined('CMC_API_KEY') ? CMC_API_KEY : (getenv('CMC_API_KEY') ?: '');
if (!$apiKey) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing CMC_API_KEY']);
    exit;
}

$endpoint = isset($_GET['endpoint']) ? $_GET['endpoint'] : '';

function cmcCall($url, $apiKey)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['X-CMC_PRO_API_KEY: ' . $apiKey]);
    $res = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err = curl_error($ch);
    curl_close($ch);
    if ($res === false) {
        http_response_code(500);
        echo json_encode(['error' => 'Curl error', 'details' => $err]);
        exit;
    }
    http_response_code($status);
    echo $res;
    exit;
}

switch ($endpoint) {
    case 'global':
        cmcCall('https://pro-api.coinmarketcap.com/v1/global-metrics/quotes/latest', $apiKey);
        break;
    case 'listings':
        $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;
        $convert = isset($_GET['convert']) ? urlencode($_GET['convert']) : 'USD';
        $url = 'https://pro-api.coinmarketcap.com/v1/cryptocurrency/listings/latest?limit=' . $limit . '&convert=' . $convert;
        cmcCall($url, $apiKey);
        break;
    case 'quotes':
        $symbols = isset($_GET['symbol']) ? urlencode($_GET['symbol']) : 'BTC,ETH';
        $convert = isset($_GET['convert']) ? urlencode($_GET['convert']) : 'USD';
        $url = 'https://pro-api.coinmarketcap.com/v1/cryptocurrency/quotes/latest?symbol=' . $symbols . '&convert=' . $convert;
        cmcCall($url, $apiKey);
        break;
    default:
        http_response_code(400);
        echo json_encode(['error' => 'Unknown endpoint']);
}
