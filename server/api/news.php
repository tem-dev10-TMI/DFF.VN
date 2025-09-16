<?php
// Optional CryptoPanic proxy (requires API key). Set CRYPTOPANIC_TOKEN in server/config.php or env.

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');

$configPath = __DIR__ . '/../config.php';
if (file_exists($configPath)) require $configPath;

$token = defined('CRYPTOPANIC_TOKEN') ? CRYPTOPANIC_TOKEN : (getenv('CRYPTOPANIC_TOKEN') ?: '');
if (!$token) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing CRYPTOPANIC_TOKEN']);
    exit;
}

$kind = isset($_GET['kind']) ? $_GET['kind'] : 'news'; // news or media
$curr = isset($_GET['currencies']) ? urlencode($_GET['currencies']) : '';
$regions = isset($_GET['regions']) ? urlencode($_GET['regions']) : '';

$url = 'https://cryptopanic.com/api/v1/posts/?auth_token=' . urlencode($token) . '&filter=rising&kind=' . urlencode($kind);
if ($curr) $url .= '&currencies=' . $curr;
if ($regions) $url .= '&regions=' . $regions;

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
http_response_code($status);
echo $res;
exit;
