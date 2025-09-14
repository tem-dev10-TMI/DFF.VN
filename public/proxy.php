<?php
// Simple CORS-safe proxy for GET requests
// Usage: /proxy.php?url=<encoded-URL>

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$url = isset($_GET['url']) ? $_GET['url'] : '';
if (!$url) {
    http_response_code(400);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Missing url parameter']);
    exit;
}

// Security: allow only whitelisted hosts
$allowedHosts = [
    'api.binance.com',
    'financialmodelingprep.com',
    'stooq.com',
    'query1.finance.yahoo.com'
];

$parts = parse_url($url);
if (!$parts || !in_array($parts['host'], $allowedHosts, true)) {
    http_response_code(403);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Host not allowed']);
    exit;
}

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
curl_setopt($ch, CURLOPT_TIMEOUT, 15);

$resp = curl_exec($ch);
$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$ctype = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
$err = curl_error($ch);
curl_close($ch);

if ($resp === false) {
    http_response_code(502);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Upstream error', 'message' => $err]);
    exit;
}

http_response_code($status ?: 200);
header('Content-Type: ' . ($ctype ?: 'application/json; charset=utf-8'));
echo $resp;
?>


