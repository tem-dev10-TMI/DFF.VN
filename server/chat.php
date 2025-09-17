<?php
session_start();

// Giới hạn câu hỏi mỗi ngày
$limit = 10;
$today = date('Y-m-d');

// Nếu chưa có dữ liệu session thì khởi tạo
if (!isset($_SESSION['question_count'])) {
    $_SESSION['question_count'] = 0;
    $_SESSION['question_date'] = $today;
}

// Nếu đã sang ngày mới → reset lại
if ($_SESSION['question_date'] !== $today) {
    $_SESSION['question_count'] = 0;
    $_SESSION['question_date'] = $today;
}

// Nếu vượt quá giới hạn thì chặn luôn
if ($_SESSION['question_count'] >= $limit) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'reply' => '❌ Bạn đã đạt giới hạn 10 câu hỏi cho hôm nay. Vui lòng quay lại vào ngày mai.',
        'sources' => []
    ]);
    exit;
}

// Nếu chưa vượt thì tăng đếm
$_SESSION['question_count']++;
// server/chat.php — Backend proxy for Google Gemini API with simple RAG + Multiple Prompts

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$input = file_get_contents('php://input');
$data = json_decode($input, true);
if (!$data || !isset($data['message'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid payload']);
    exit;
}

$userMessage = trim((string)$data['message']);
$history = isset($data['history']) && is_array($data['history']) ? $data['history'] : [];

// Load API key
$configPath = __DIR__ . '/config.php';
if (!file_exists($configPath)) {
    http_response_code(500);
    echo json_encode(['error' => 'Missing server/config.php']);
    exit;
}
require $configPath; // defines GEMINI_API_KEY and GEMINI_MODEL

if (!defined('GEMINI_API_KEY') || !GEMINI_API_KEY) {
    http_response_code(500);
    echo json_encode(['error' => 'GEMINI_API_KEY not configured']);
    exit;
}

// Load prompts
require __DIR__ . '/prompts.php';
$systemPrompt = selectSystemPrompt($userMessage, $PROMPTS);

$model = defined('GEMINI_MODEL') && GEMINI_MODEL ? GEMINI_MODEL : 'gemini-1.5-pro';

// Retrieve: naive keyword retrieval from local KB
$kbDir = __DIR__ . '/../server/kb';
$kbDocs = [];
$sources = [];
if (is_dir($kbDir)) {
    $files = scandir($kbDir);
    foreach ($files as $f) {
        if ($f === '.' || $f === '..') continue;
        $path = $kbDir . '/' . $f;
        if (is_file($path)) {
            $content = file_get_contents($path);
            $kbDocs[$f] = $content;
        }
    }
}

function score_doc($text, $query)
{
    $q = mb_strtolower($query, 'UTF-8');
    $t = mb_strtolower($text, 'UTF-8');
    $keywords = preg_split('/\s+/', $q);
    $score = 0;
    foreach ($keywords as $w) {
        if (mb_strlen($w) < 3) continue;
        $score += substr_count($t, $w);
    }
    return $score;
}

arsort($kbDocs);
$ranked = [];
foreach ($kbDocs as $name => $content) {
    $ranked[$name] = score_doc($content, $userMessage);
}
arsort($ranked);
// $top = array_slice(array_keys($ranked), 0, 3);
// foreach ($top as $name) {
//     if (!isset($kbDocs[$name])) continue;
//     $snippet = mb_substr($kbDocs[$name], 0, 2000, 'UTF-8');
//     $sources[] = $name;
// }

// Detect timeframe intent and boost corresponding KB templates (daily/monthly/yearly)
$q = mb_strtolower($userMessage, 'UTF-8');
$wantDaily = (bool)preg_match('/\b(ngay|ngày|hom nay|hôm nay|24h|daily|today)\b/u', $q);
$wantMonthly = (bool)preg_match('/\b(thang|tháng|monthly|30\s*ngay|30\s*ngày|this month)\b/u', $q);
$wantYearly = (bool)preg_match('/\b(nam|năm|yearly|this year|12\s*thang|12\s*tháng)\b/u', $q);

// Re-rank by adding a boost to matching templates
$boosted = $ranked;
foreach (array_keys($kbDocs) as $name) {
    $bump = 0;
    if ($wantDaily && stripos($name, 'daily_template') !== false) $bump += 100;
    if ($wantMonthly && stripos($name, 'monthly_template') !== false) $bump += 100;
    if ($wantYearly && stripos($name, 'yearly_template') !== false) $bump += 100;
    if (!isset($boosted[$name])) $boosted[$name] = 0;
    $boosted[$name] += $bump;
}
arsort($boosted);
$top = array_slice(array_keys($boosted), 0, 3);
foreach ($top as $name) {
    if (!isset($kbDocs[$name])) continue;
    $sources[] = $name;
}

$contextParts = [];
foreach ($top as $name) {
    if (!isset($kbDocs[$name])) continue;
    // limit each doc to 1500 chars to reduce tokens
    $contextParts[] = "[" . $name . "]\n" . mb_substr($kbDocs[$name], 0, 1500, 'UTF-8');
}
$contextText = implode("\n\n---\n\n", $contextParts);
// hard cap total context length
if (mb_strlen($contextText, 'UTF-8') > 4500) {
    $contextText = mb_substr($contextText, 0, 4500, 'UTF-8');
}

// System prompt with strict safety and Vietnamese style guidance
$systemPrompt = <<<PROMPT
Bạn là Gemini Crypto Advisor, một trợ lý AI tiếng Việt cho thương mại điện tử và tiền ảo.
Nguyên tắc:
- Nếu người dùng hỏi bằng ngôn ngữ nào thì trả lời bằng ngôn ngữ đó.
- Không đưa lời khuyên đầu tư. Luôn nhắc người dùng tự nghiên cứu, rủi ro cao.
- Ưu tiên câu trả lời có cấu trúc: tóm tắt, chi tiết, bước hành động, lưu ý bảo mật.
- Khi nói về tích hợp thanh toán: nêu cổng, phí, rủi ro hoàn tiền, KYC/KYB, compliance.
- Chỉ trả lời trong phạm vi crypto, thanh toán, bảo mật, pháp lý cơ bản liên quan tới e-commerce.
- Nếu câu hỏi ngoài phạm vi: lịch sự từ chối và đề xuất chủ đề liên quan.
- Trích xuất thông tin từ NGỮ CẢNH sau. Nếu không chắc, nói "Tôi không chắc" và đề xuất bước xác minh.

Hướng dẫn phân tích thị trường theo thời gian:
- Daily (24h): dùng khung trong KB "market_daily_template" khi truy vấn về ngày/hôm nay/24h.
- Monthly (30 ngày): dùng khung trong KB "market_monthly_template" khi truy vấn về tháng.
- Yearly (12 tháng): dùng khung trong KB "market_yearly_template" khi truy vấn về năm/chu kỳ.

Định dạng trả lời:
1) Tóm tắt ngắn
2) Phân tích chính: gạch đầu dòng rõ ràng
3) Bước triển khai/kiểm tra
4) Cảnh báo bảo mật/tuân thủ
5) Nguồn tham khảo (nếu có)
PROMPT;

// Build Gemini request
$parts = [];
// $parts[] = [ 'text' => $systemPrompt . "\n\nNGỮ CẢNH (KB):\n" . $contextText ];
// Insert real-time market snapshot (CoinGecko/CMC) when intent is market timeframe
$rtContext = '';
if ($wantDaily || $wantMonthly || $wantYearly) {
    // Old: Only CoinGecko
    // $cgGlobal = @file_get_contents(__DIR__ . '/api_proxy.php?type=global');
    // $cgMarkets = @file_get_contents(__DIR__ . '/api_proxy.php?type=markets');
    // New: Try CoinMarketCap first (if key available), then CoinGecko fallback
    $cmcGlobal = @file_get_contents(__DIR__ . '/api_proxy.php?type=cmc_global');
    $cmcList = @file_get_contents(__DIR__ . '/api_proxy.php?type=cmc_listings&limit=8');
    if ($cmcGlobal) {
        $rtContext .= "[cmc_global]\n" . mb_substr($cmcGlobal, 0, 3000, 'UTF-8') . "\n\n";
    }
    if ($cmcList) {
        $rtContext .= "[cmc_listings]\n" . mb_substr($cmcList, 0, 3000, 'UTF-8') . "\n\n";
    }
    if (!$cmcGlobal && !$cmcList) {
        $cgGlobal = @file_get_contents(__DIR__ . '/api_proxy.php?type=global');
        $cgMarkets = @file_get_contents(__DIR__ . '/api_proxy.php?type=markets');
        if ($cgGlobal) $rtContext .= "[coingecko_global]\n" . $cgGlobal . "\n\n";
        if ($cgMarkets) $rtContext .= "[coingecko_markets]\n" . mb_substr($cgMarkets, 0, 3000, 'UTF-8');
    }
}
$parts = [];
$parts[] = ['text' => $systemPrompt . "\n\nNGỮ CẢNH (KB):\n" . $contextText . ($rtContext ? "\n\nDỮ LIỆU THỜI GIAN GẦN ĐÂY:\n" . $rtContext : '')];

// Add conversation history (truncate for token safety)
$maxHistory = 6;
$trimmedHistory = array_slice($history, max(0, count($history) - $maxHistory));
foreach ($trimmedHistory as $turn) {
    $role = $turn['role'] === 'user' ? 'user' : 'model';
    $parts[] = ['text' => ($role === 'user' ? 'Người dùng: ' : 'Trợ lý: ') . $turn['content']];
}

// Add user message
$parts[] = ['text' => 'Người dùng: ' . $userMessage];

function buildPayload($parts)
{
    return [
        'contents' => [['parts' => array_map(function ($p) {
            return ['text' => $p['text']];
        }, $parts)]],
        'generationConfig' => [
            'temperature' => 0.2,
            'topP' => 0.9,
            'topK' => 40,
            'maxOutputTokens' => 768,
        ],
        'safetySettings' => [
            ['category' => 'HARM_CATEGORY_DANGEROUS_CONTENT', 'threshold' => 'BLOCK_ONLY_HIGH'],
            ['category' => 'HARM_CATEGORY_HATE_SPEECH', 'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'],
            ['category' => 'HARM_CATEGORY_HARASSMENT', 'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'],
            ['category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT', 'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'],
            ['category' => 'HARM_CATEGORY_CIVIC_INTEGRITY', 'threshold' => 'BLOCK_LOW_AND_ABOVE'],
        ],
    ];
}

function callGemini($model, $payload)
{
    $apiUrl = 'https://generativelanguage.googleapis.com/v1beta/models/' . urlencode($model) . ':generateContent?key=' . urlencode(GEMINI_API_KEY);
    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err = curl_error($ch);
    curl_close($ch);
    return [$status, $response, $err];
}

$payload = buildPayload($parts);
$status = 0;
$response = null;
$err = null;
list($status, $response, $err) = callGemini($model, $payload);

// If quota exceeded, try smarter handling with backoff and payload shrinking
// Old logic:
// if ($status === 429) {
//     $fallbackModel = 'gemini-1.5-flash';
//     if ($model !== $fallbackModel) {
//         list($status, $response, $err) = callGemini($fallbackModel, $payload);
//     }
// }
if ($status === 429) {
    // Try to parse retry delay from response (seconds)
    $retrySeconds = 5;
    $respObj = json_decode($response, true);
    if (isset($respObj['error']['details'])) {
        foreach ($respObj['error']['details'] as $d) {
            if (isset($d['@type']) && strpos($d['@type'], 'RetryInfo') !== false && isset($d['retryDelay'])) {
                // retryDelay like '9s'
                if (preg_match('/(\d+)s/', $d['retryDelay'], $m)) {
                    $retrySeconds = max(1, (int)$m[1]);
                }
            }
        }
    }

    // Cap wait to avoid long server sleeps
    $retrySeconds = min($retrySeconds, 6);
    sleep($retrySeconds);

    // Rebuild a smaller payload to reduce input tokens
    // Comment: previous payload used up to 3 docs and 6 history turns
    $smallerParts = $parts; // clone
    // Cut system+context if too long (keep last 3500 chars)
    if (isset($smallerParts[0]['text'])) {
        $t = $smallerParts[0]['text'];
        if (mb_strlen($t, 'UTF-8') > 3500) {
            $smallerParts[0]['text'] = mb_substr($t, -3500, null, 'UTF-8');
        }
    }
    // Re-cut history to 3 most recent exchanges
    $reduced = [];
    $count = 0;
    for ($i = count($smallerParts) - 1; $i >= 0; $i--) {
        // keep user and assistant lines after the first system/context
        if ($i === 0) {
            continue;
        }
        $reduced[] = $smallerParts[$i];
        $count++;
        if ($count >= 6) break; // 3 exchanges = 6 lines (user+assistant)
    }
    $reduced = array_reverse($reduced);
    $smaller = [];
    if (isset($smallerParts[0])) $smaller[] = $smallerParts[0];
    $smaller = array_merge($smaller, $reduced);

    $smallPayload = [
        'contents' => [['parts' => array_map(function ($p) {
            return ['text' => $p['text']];
        }, $smaller)]],
        'generationConfig' => [
            'temperature' => 0.2,
            'topP' => 0.9,
            'topK' => 40,
            'maxOutputTokens' => 640,
        ],
        'safetySettings' => [
            ['category' => 'HARM_CATEGORY_DANGEROUS_CONTENT', 'threshold' => 'BLOCK_ONLY_HIGH'],
            ['category' => 'HARM_CATEGORY_HATE_SPEECH', 'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'],
            ['category' => 'HARM_CATEGORY_HARASSMENT', 'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'],
            ['category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT', 'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'],
            ['category' => 'HARM_CATEGORY_CIVIC_INTEGRITY', 'threshold' => 'BLOCK_LOW_AND_ABOVE'],
        ],
    ];

    list($status, $response, $err) = callGemini($model, $smallPayload);

    if ($status === 429) {
        // Fallback to flash model
        $fallbackModel = 'gemini-1.5-flash';
        if ($model !== $fallbackModel) {
            // brief wait
            usleep(600000);
            list($status, $response, $err) = callGemini($fallbackModel, $smallPayload);
        }
    }

    if ($status === 429) {
        echo json_encode([
            'reply' => "Bạn đã đạt giới hạn tần suất hoặc dung lượng miễn phí tạm thời. Hãy đợi khoảng {$retrySeconds}s rồi thử lại.\n\nMẹo: rút gọn câu hỏi, hỏi từng ý; hoặc chuyển mặc định sang model 'gemini-1.5-flash' trong server/config.php để ổn định hơn.",
            'sources' => [],
        ]);
        exit;
    }
}

// Handle model overload 503 with retry + fallback
// Previous behavior:
// if ($status < 200 || $status >= 300) { http_response_code($status); echo json_encode([...]); exit; }
if ($status === 503) {
    // Small backoff then retry same model
    usleep(800000); // 0.8s
    list($status, $response, $err) = callGemini($model, $payload);
    if ($status === 503) {
        // Try fallback model
        $fallbackModel = 'gemini-1.5-flash';
        if ($model !== $fallbackModel) {
            usleep(800000);
            list($status, $response, $err) = callGemini($fallbackModel, $payload);
        }
    }
    if ($status === 503) {
        // Graceful degradation: return friendly message as success so UI shows guidance
        echo json_encode([
            'reply' => "Hệ thống mô hình đang quá tải (503). Vui lòng thử lại sau 30–60 giây.\n\nMẹo: rút gọn câu hỏi, hỏi từng ý; hoặc chuyển sang câu hỏi khác trong lúc chờ.",
            'sources' => [],
        ]);
        exit;
    }
}

// Call Gemini
if ($response === false) {
    http_response_code(500);
    echo json_encode(['error' => 'Curl error: ' . $err]);
    exit;
}

// Old: return raw error to client
// if ($status < 200 || $status >= 300) {
//     http_response_code($status);
//     echo json_encode(['error' => 'Gemini API error', 'details' => json_decode($response, true)]);
//     exit;
// }
// New: handle specific statuses above; for other non-2xx, still forward error
/* if ($status < 200 || $status >= 300) {
    http_response_code($status);
    echo json_encode(['error' => 'Gemini API error', 'details' => json_decode($response, true)]);
    exit;
} */

if ($status < 200 || $status >= 300) {
    http_response_code($status);
    echo json_encode(['error' => 'Gemini API error', 'details' => json_decode($response, true)]);
    exit;
}

$json = json_decode($response, true);
$reply = '';

$json = json_decode($response, true);
$reply = '';
/* if (isset($json['candidates'][0]['content']['parts'][0]['text'])) {
    $reply = $json['candidates'][0]['content']['parts'][0]['text'];
} else if (isset($json['promptFeedback']['blockReason'])) {
    $reply = 'Yêu cầu bị chặn: ' . $json['promptFeedback']['blockReason'];
} else {
    $reply = 'Xin lỗi, tôi không tạo được câu trả lời lúc này.';
} */

if (isset($json['candidates'][0]['content']['parts'][0]['text'])) {
    $reply = $json['candidates'][0]['content']['parts'][0]['text'];
} else if (isset($json['promptFeedback']['safetyRatings'])) {
    $blockedCategory = '';
    // Lặp qua các đánh giá an toàn để tìm lý do bị chặn
    foreach ($json['promptFeedback']['safetyRatings'] as $rating) {
        if (isset($rating['blockReason'])) {
            $blockedCategory = $rating['category'];
            break;
        }
    }

    // Ánh xạ mã lỗi sang thông điệp thân thiện
    switch ($blockedCategory) {
        case 'HARM_CATEGORY_DANGEROUS_CONTENT':
            $reply = 'Rất tiếc, yêu cầu của bạn bị chặn vì có thể liên quan đến nội dung nguy hiểm.';
            break;
        case 'HARM_CATEGORY_HATE_SPEECH':
            $reply = 'Rất tiếc, yêu cầu của bạn bị chặn vì có thể liên quan đến nội dung thù địch.';
            break;
        case 'HARM_CATEGORY_HARASSMENT':
            $reply = 'Rất tiếc, yêu cầu của bạn bị chặn vì có thể liên quan đến nội dung quấy rối.';
            break;
        case 'HARM_CATEGORY_SEXUALLY_EXPLICIT':
            $reply = 'Rất tiếc, yêu cầu của bạn bị chặn vì có thể liên quan đến nội dung nhạy cảm.';
            break;
        case 'HARM_CATEGORY_CIVIC_INTEGRITY':
            $reply = 'Rất tiếc, yêu cầu của bạn bị chặn vì có thể liên quan đến nội dung về chính trị hoặc thông tin sai lệch.';
            break;
        default:
            $reply = 'Yêu cầu của bạn đã bị chặn vì lý do an toàn. Vui lòng thử lại với một câu hỏi khác.';
            break;
    }
} else {
    $reply = 'Xin lỗi, tôi không tạo được câu trả lời lúc này. Vui lòng thử lại sau.';
}

// load metadata nếu có
$metaPath = __DIR__ . '/../server/kb/metadata.json';
$kbMeta = [];
if (file_exists($metaPath)) {
    $kbMeta = json_decode(file_get_contents($metaPath), true) ?? [];
}

// chuyển đổi sources thành public labels
$publicSources = [];
foreach ($sources as $s) {
    if (isset($kbMeta[$s])) {
        $publicSources[] = $kbMeta[$s];
    } else {
        // fallback: ẩn số/đuôi, chuyển underscores->space, loại bỏ tiền tố số
        $base = pathinfo($s, PATHINFO_FILENAME); // remove extension
        $base = preg_replace('/^\d+[_\s-]*/', '', $base); // remove leading digits like "02_"
        $base = str_replace('_', ' ', $base);
        $publicSources[] = ucfirst($base);
    }
}

// trả về publicSources (thay vì file names)
echo json_encode([
    'reply' => $reply,
    'sources' => $publicSources,
]);


/* echo json_encode([
    'reply' => $reply,
    'sources' => $sources,
]); */
