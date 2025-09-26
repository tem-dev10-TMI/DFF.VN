<?php
// server/check_comment.php — Backend for comment analysis using AI
// Requirements: PHP 7.4+, curl enabled.

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
if (!$data || (!isset($data['comment']) && !isset($data['content']))) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid payload']);
    exit;
}

if (!$data || !isset($data['content'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid payload']);
    exit;
}

// Support both 'comment' and 'content' fields
$comment = trim((string)($data['comment'] ?? $data['content'] ?? ''));

// Check if comment is empty
if (empty($comment)) {
    http_response_code(400);
    echo json_encode(['error' => 'Empty comment']);
    exit;
}

// Load API key from config
$configPath = __DIR__ . '/config.php';
if (!file_exists($configPath)) {
    http_response_code(500);
    echo json_encode(['error' => 'Missing checkCmt/config.php']);
    exit;
}
require $configPath;

if (!defined('GEMINI_API_KEY') || !GEMINI_API_KEY) {
    http_response_code(500);
    echo json_encode(['error' => 'GEMINI_API_KEY not configured']);
    exit;
}

$model = defined('GEMINI_MODEL') && GEMINI_MODEL ? GEMINI_MODEL : 'gemini-1.5-pro';

// Step 1: User comments - Display comment first
// (This is handled by frontend)

// Step 2: AI initiates check - Initial multi-faceted analysis
$initialAnalysisResult = performInitialAnalysis($comment, $model);

// Step 3: Result determination - Check if violation found
if ($initialAnalysisResult['isViolation']) {
    // Path 2: Known trending violation
    if ($initialAnalysisResult['isKnownViolation']) {
        $result = $initialAnalysisResult;
        $result['action'] = 'report_error';
        $result['analysisMethod'] = 'Known Trending Violation';
    } else {
        // Path 3: Suspected new violation - Need detailed re-check
        $detailedAnalysisResult = performDetailedRecheck($comment, $model);

        if ($detailedAnalysisResult['isViolation']) {
            if ($detailedAnalysisResult['isNewViolation']) {
                // Outcome 7a: New violation confirmed
                learnNewKeywords($detailedAnalysisResult['suggestedKeywords'], $detailedAnalysisResult['violationType']);
                $result = $detailedAnalysisResult;
                $result['action'] = 'auto_add_to_trending_and_report';
                $result['analysisMethod'] = 'New Violation - Auto Added to Trending';
            } else {
                // Outcome 7b: Known trending violation confirmed
                $result = $detailedAnalysisResult;
                $result['action'] = 'report_error';
                $result['analysisMethod'] = 'Known Trending Violation (After Recheck)';
            }
        } else {
            // No violation found after detailed recheck
            $result = $detailedAnalysisResult;
            $result['action'] = 'display_normal';
            $result['analysisMethod'] = 'No Violation After Detailed Check';
        }
    }
} else {
    // Path 1: No violation found (Case 1)
    $result = $initialAnalysisResult;
    $result['action'] = 'display_normal';
    $result['analysisMethod'] = 'No Violation Found';
}

echo json_encode($result);

function loadViolationPatterns()
{
    $kbDir = __DIR__ . '/kb';
    $violations = [];

    // Load insult keywords
    $insultFile = $kbDir . '/01_insult_keywords.txt';
    if (file_exists($insultFile)) {
        $keywords = extractKeywordsFromFile($insultFile);
        $violations['insult'] = [
            'keywords' => $keywords,
            'severity' => 'medium',
            'type' => 'Lăng mạ, sỉ nhục'
        ];
    }

    // Load political keywords
    $politicalFile = $kbDir . '/02_political_keywords.txt';
    if (file_exists($politicalFile)) {
        $keywords = extractKeywordsFromFile($politicalFile);
        $violations['political'] = [
            'keywords' => $keywords,
            'severity' => 'high',
            'type' => 'Chống phá nhà nước'
        ];
    }

    // Load threat keywords
    $threatFile = $kbDir . '/03_threat_keywords.txt';
    if (file_exists($threatFile)) {
        $keywords = extractKeywordsFromFile($threatFile);
        $violations['threat'] = [
            'keywords' => $keywords,
            'severity' => 'high',
            'type' => 'Đe dọa, bạo lực'
        ];
    }

    // Load spam keywords
    $spamFile = $kbDir . '/04_spam_keywords.txt';
    if (file_exists($spamFile)) {
        $keywords = extractKeywordsFromFile($spamFile);
        $violations['spam'] = [
            'keywords' => $keywords,
            'severity' => 'low',
            'type' => 'Spam/Quảng cáo'
        ];
    }

    return $violations;
}

function extractKeywordsFromFile($filePath)
{
    $content = file_get_contents($filePath);
    $keywords = [];

    // Extract keywords from lines starting with "-"
    $lines = explode("\n", $content);
    foreach ($lines as $line) {
        $line = trim($line);
        if (strpos($line, '- ') === 0) {
            $keyword = trim(substr($line, 2));
            if (!empty($keyword)) {
                $keywords[] = $keyword;
            }
        }
    }

    return $keywords;
}

function analyzeWithKeywords($comment)
{
    $comment = mb_strtolower($comment, 'UTF-8');

    // Load violation patterns from KB files
    $violations = loadViolationPatterns();

    $detectedViolations = [];
    $maxSeverity = 'low';

    foreach ($violations as $category => $config) {
        foreach ($config['keywords'] as $keyword) {
            if (mb_strpos($comment, $keyword) !== false) {
                $detectedViolations[] = [
                    'category' => $category,
                    'keyword' => $keyword,
                    'severity' => $config['severity'],
                    'type' => $config['type']
                ];

                // Update max severity
                if ($config['severity'] === 'high') {
                    $maxSeverity = 'high';
                } elseif ($config['severity'] === 'medium' && $maxSeverity !== 'high') {
                    $maxSeverity = 'medium';
                }
            }
        }
    }

    if (!empty($detectedViolations)) {
        $violationTypes = array_unique(array_column($detectedViolations, 'type'));
        $details = 'Phát hiện từ khóa: ' . implode(', ', array_column($detectedViolations, 'keyword'));

        return [
            'isViolation' => true,
            'violationType' => implode(', ', $violationTypes),
            'severity' => $maxSeverity,
            'details' => $details,
            'detectedKeywords' => array_column($detectedViolations, 'keyword')
        ];
    }

    return [
        'isViolation' => false,
        'violationType' => null,
        'severity' => null,
        'details' => 'Không phát hiện từ khóa vi phạm',
        'detectedKeywords' => []
    ];
}

function analyzeWithAI($comment, $model)
{
    // Load AI training instructions from KB
    $trainingFile = __DIR__ . '/kb/05_ai_training.txt';
    $systemPrompt = '';

    if (file_exists($trainingFile)) {
        $systemPrompt = file_get_contents($trainingFile);
    } else {
        // Fallback prompt if KB file doesn't exist
        $systemPrompt = <<<PROMPT
Bạn là một hệ thống phân tích bình luận chuyên nghiệp cho tiếng Việt. 
Nhiệm vụ của bạn là phân tích bình luận và xác định xem nó có vi phạm các quy tắc không.

Các loại vi phạm cần phát hiện:
1. LĂNG MẠ, SỈ NHỤC
2. CHỐNG PHÁ NHÀ NƯỚC  
3. ĐE DỌA, BẠO LỰC
4. SPAM/QUẢNG CÁO

Hãy phân tích bình luận và trả lời theo định dạng JSON:
{
    "isViolation": true/false,
    "violationType": "Loại vi phạm nếu có",
    "severity": "low/medium/high", 
    "details": "Mô tả chi tiết về vi phạm",
    "confidence": 0.0-1.0
}

Chỉ trả lời JSON, không thêm text khác.
PROMPT;
    }

    $parts = [
        ['text' => $systemPrompt . "\n\nBình luận cần phân tích: " . $comment]
    ];

    $payload = [
        'contents' => [['parts' => array_map(function ($p) {
            return ['text' => $p['text']];
        }, $parts)]],
        'generationConfig' => [
            'temperature' => 0.1,
            'topP' => 0.8,
            'topK' => 20,
            'maxOutputTokens' => 200,
        ],
        'safetySettings' => [
            ['category' => 'HARM_CATEGORY_DANGEROUS_CONTENT', 'threshold' => 'BLOCK_ONLY_HIGH'],
            ['category' => 'HARM_CATEGORY_HATE_SPEECH', 'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'],
            ['category' => 'HARM_CATEGORY_HARASSMENT', 'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'],
            ['category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT', 'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'],
        ],
    ];

    $apiUrl = 'https://generativelanguage.googleapis.com/v1beta/models/' . urlencode($model) . ':generateContent?key=' . urlencode(GEMINI_API_KEY);
    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($status === 200 && $response) {
        $json = json_decode($response, true);
        if (isset($json['candidates'][0]['content']['parts'][0]['text'])) {
            $aiResponse = $json['candidates'][0]['content']['parts'][0]['text'];
            $aiResult = json_decode($aiResponse, true);

            if ($aiResult && isset($aiResult['isViolation'])) {
                return [
                    'aiAnalysis' => true,
                    'confidence' => $aiResult['confidence'] ?? 0.8,
                    'aiDetails' => $aiResult['details'] ?? 'Phân tích bởi AI'
                ];
            }
        }
    }

    return [
        'aiAnalysis' => false,
        'confidence' => 0.5,
        'aiDetails' => 'Không thể phân tích bằng AI'
    ];
}

function analyzeWithMultiAI($comment, $model)
{
    $aiPrompts = loadMultiAIPrompts();
    $results = [];

    // Run multiple AI analyses
    foreach ($aiPrompts as $analysisType => $prompt) {
        try {
            $fullPrompt = $prompt . "\n\nBình luận cần phân tích: \"$comment\"";
            $response = callGeminiAPI($fullPrompt, $model);

            if ($response && isset($response['candidates'][0]['content']['parts'][0]['text'])) {
                $aiResult = $response['candidates'][0]['content']['parts'][0]['text'];
                $results[$analysisType] = parseAIResponse($aiResult, $analysisType);
            }
        } catch (Exception $e) {
            error_log("AI Analysis Error ($analysisType): " . $e->getMessage());
            $results[$analysisType] = ['isViolation' => false, 'confidence' => 0];
        }
    }

    // Combine results from multiple AI analyses
    return combineAIResults($results);
}

function loadAIPrompt(string $type = 'general'): string
{
    switch ($type) {
        case 'contextual':
            return "Bạn là chuyên gia phân tích NGỮ CẢNH tiếng Việt. Trả về JSON {isViolation, type, severity, details, confidence}.";
        case 'linguistic':
            return "Bạn là nhà NGÔN NGỮ HỌC tiếng Việt. Trả về JSON {isViolation, type, severity, details, confidence}.";
        case 'emotional':
            return "Bạn là chuyên gia TÂM LÝ/CẢM XÚC. Trả về JSON {isViolation, type, severity, details, confidence}.";
        case 'intent':
            return "Bạn là chuyên gia phân tích Ý ĐỊNH. Trả về JSON {isViolation, type, severity, details, confidence}.";
        case 'cultural':
            return "Bạn là chuyên gia VĂN HÓA Việt Nam. Trả về JSON {isViolation, type, severity, details, confidence}.";
        default:
            return "Bạn là hệ thống phân tích bình luận. Trả về JSON {isViolation, type, severity, details, confidence}.";
    }
}

function loadMultiAIPrompts()
{
    $promptsFile = __DIR__ . '/kb/06_multi_ai_prompts.txt';
    if (!file_exists($promptsFile)) {
        return [
            'contextual' => loadAIPrompt('contextual'),
            'linguistic' => loadAIPrompt('linguistic'),
            'emotional'  => loadAIPrompt('emotional'),
            'intent'     => loadAIPrompt('intent'),
            'cultural'   => loadAIPrompt('cultural'),
        ];
    }

    $content = file_get_contents($promptsFile);
    $sections = explode('## ', $content);
    $prompts = [];

    foreach ($sections as $section) {
        if (empty(trim($section))) continue;

        $lines = explode("\n", $section);
        $type = trim($lines[0]);
        $prompt = trim(implode("\n", array_slice($lines, 1)));

        if (!empty($type) && !empty($prompt)) {
            $prompts[$type] = $prompt;
        }
    }

    return $prompts;
}

function parseAIResponse($aiResponse, $analysisType = 'general')
{
    // Try to extract JSON from response
    $jsonMatch = [];
    if (preg_match('/\{[^}]+\}/', $aiResponse, $jsonMatch)) {
        $result = json_decode($jsonMatch[0], true);
        if ($result && isset($result['isViolation'])) {
            return [
                'isViolation' => $result['isViolation'],
                'type' => $result['type'] ?? 'Vi phạm nội dung',
                'severity' => $result['severity'] ?? 'medium',
                'details' => $result['details'] ?? 'Phân tích bởi AI',
                'confidence' => $result['confidence'] ?? 0.5,
                'analysisType' => $analysisType
            ];
        }
    }

    // Fallback parsing if JSON extraction fails
    $isViolation = false;
    $confidence = 0.3;

    // Look for violation indicators in text
    $violationKeywords = ['vi phạm', 'xúc phạm', 'lăng mạ', 'chống phá', 'đe dọa'];
    foreach ($violationKeywords as $keyword) {
        if (mb_strpos(mb_strtolower($aiResponse), $keyword) !== false) {
            $isViolation = true;
            $confidence = 0.6;
            break;
        }
    }

    return [
        'isViolation' => $isViolation,
        'type' => $isViolation ? 'Vi phạm nội dung' : null,
        'severity' => $isViolation ? 'medium' : null,
        'details' => $aiResponse,
        'confidence' => $confidence,
        'analysisType' => $analysisType
    ];
}

function combineAIResults($results)
{
    $violationScore = 0;
    $totalAnalyses = count($results);
    $violationTypes = [];
    $confidences = [];
    $details = [];
    $analysisTypes = [];

    foreach ($results as $analysisType => $result) {
        $analysisTypes[] = $analysisType;

        if ($result['isViolation']) {
            $weight = getAnalysisWeight($analysisType);
            $violationScore += ($result['confidence'] ?? 0.5) * $weight;
            $confidences[] = $result['confidence'] ?? 0.5;

            if (isset($result['type'])) {
                $violationTypes[] = $result['type'];
            }

            if (isset($result['details'])) {
                $details[] = "[$analysisType] " . $result['details'];
            }
        }
    }

    // Calculate weighted average confidence
    $avgConfidence = !empty($confidences) ? array_sum($confidences) / count($confidences) : 0;

    // Determine if violation based on multiple criteria
    $isViolation = false;
    $severity = 'low';

    if ($violationScore >= 0.6) {
        $isViolation = true;
        $severity = 'high';
    } elseif ($violationScore >= 0.4) {
        $isViolation = true;
        $severity = 'medium';
    } elseif ($violationScore >= 0.2) {
        $isViolation = true;
        $severity = 'low';
    }

    if ($isViolation) {
        return [
            'isViolation' => true,
            'violationType' => implode(', ', array_unique($violationTypes)) ?: 'Vi phạm nội dung',
            'severity' => $severity,
            'details' => implode('; ', $details) ?: 'Phát hiện nội dung không phù hợp',
            'confidence' => $avgConfidence,
            'analysisCount' => $totalAnalyses,
            'violationScore' => $violationScore,
            'analysisTypes' => $analysisTypes
        ];
    }

    return [
        'isViolation' => false,
        'violationType' => null,
        'severity' => null,
        'details' => 'Không phát hiện vi phạm qua phân tích đa AI',
        'confidence' => $avgConfidence,
        'analysisCount' => $totalAnalyses,
        'violationScore' => $violationScore,
        'analysisTypes' => $analysisTypes
    ];
}

function getAnalysisWeight($analysisType)
{
    $weights = [
        'intent' => 1.2,      // Ý định có trọng số cao nhất
        'emotional' => 1.1,   // Tác động cảm xúc
        'contextual' => 1.0,  // Ngữ cảnh
        'linguistic' => 0.9,  // Ngôn ngữ học
        'cultural' => 0.8     // Văn hóa
    ];

    return $weights[$analysisType] ?? 1.0;
}

function callGeminiAPI($prompt, $model)
{
    $parts = [
        ['text' => $prompt]
    ];

    $payload = [
        'contents' => [['parts' => array_map(function ($p) {
            return ['text' => $p['text']];
        }, $parts)]],
        'generationConfig' => [
            'temperature' => 0.1,
            'topP' => 0.8,
            'topK' => 20,
            'maxOutputTokens' => 300,
        ],
        'safetySettings' => [
            ['category' => 'HARM_CATEGORY_DANGEROUS_CONTENT', 'threshold' => 'BLOCK_ONLY_HIGH'],
            ['category' => 'HARM_CATEGORY_HATE_SPEECH', 'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'],
            ['category' => 'HARM_CATEGORY_HARASSMENT', 'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'],
            ['category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT', 'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'],
        ],
    ];

    $apiUrl = 'https://generativelanguage.googleapis.com/v1beta/models/' . urlencode($model) . ':generateContent?key=' . urlencode(GEMINI_API_KEY);
    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($status === 200 && $response) {
        return json_decode($response, true);
    }

    return null;
}

function combineResults($keywordResult, $multiAIResult)
{
    // If both keyword and AI analysis suggest violation, use the more severe one
    if ($keywordResult['isViolation'] && $multiAIResult['isViolation']) {
        $keywordSeverity = $keywordResult['severity'];
        $aiSeverity = $multiAIResult['severity'];

        // Determine final severity
        $finalSeverity = $keywordSeverity;
        if (($aiSeverity === 'high' && $keywordSeverity !== 'high') ||
            ($aiSeverity === 'medium' && $keywordSeverity === 'low')
        ) {
            $finalSeverity = $aiSeverity;
        }

        return [
            'isViolation' => true,
            'violationType' => $multiAIResult['violationType'],
            'severity' => $finalSeverity,
            'details' => $multiAIResult['details'] . ' | Từ khóa: ' . implode(', ', $keywordResult['detectedKeywords']),
            'detectedKeywords' => $keywordResult['detectedKeywords'],
            'confidence' => max($keywordResult['confidence'] ?? 0.8, $multiAIResult['confidence']),
            'analysisMethod' => 'Multi-AI + Keywords',
            'violationScore' => $multiAIResult['violationScore'],
            'analysisCount' => $multiAIResult['analysisCount']
        ];
    }

    // If only one method suggests violation, use that result
    if ($keywordResult['isViolation']) {
        return array_merge($keywordResult, [
            'analysisMethod' => 'Keywords Only',
            'confidence' => 0.8
        ]);
    }

    if ($multiAIResult['isViolation']) {
        return array_merge($multiAIResult, [
            'analysisMethod' => 'Multi-AI Only',
            'detectedKeywords' => []
        ]);
    }

    // Neither suggests violation
    return [
        'isViolation' => false,
        'violationType' => null,
        'severity' => null,
        'details' => 'Không phát hiện vi phạm',
        'detectedKeywords' => [],
        'confidence' => $multiAIResult['confidence'],
        'analysisMethod' => 'Multi-AI + Keywords',
        'violationScore' => $multiAIResult['violationScore'],
        'analysisCount' => $multiAIResult['analysisCount']
    ];
}

function analyzeWithDeepAI($comment, $model)
{
    $deepPrompts = loadDeepAnalysisPrompts();
    $results = [];

    // Run deep analysis with all specialized AI
    foreach ($deepPrompts as $analysisType => $prompt) {
        try {
            $fullPrompt = $prompt . "\n\nBình luận cần phân tích: \"$comment\"";
            $response = callGeminiAPI($fullPrompt, $model);

            if ($response && isset($response['candidates'][0]['content']['parts'][0]['text'])) {
                $aiResult = $response['candidates'][0]['content']['parts'][0]['text'];
                $results[$analysisType] = parseDeepAIResponse($aiResult, $analysisType);
            }
        } catch (Exception $e) {
            error_log("Deep AI Analysis Error ($analysisType): " . $e->getMessage());
            $results[$analysisType] = ['isViolation' => false, 'confidence' => 0];
        }
    }

    // Combine results from deep analysis
    return combineDeepAIResults($results);
}

function loadDeepAnalysisPrompts()
{
    $promptsFile = __DIR__ . '/kb/07_deep_analysis_prompts.txt';
    if (!file_exists($promptsFile)) {
        return [];
    }

    $content = file_get_contents($promptsFile);
    $sections = explode('## ', $content);
    $prompts = [];

    foreach ($sections as $section) {
        if (empty(trim($section))) continue;

        $lines = explode("\n", $section);
        $type = trim($lines[0]);
        $prompt = trim(implode("\n", array_slice($lines, 1)));

        if (!empty($type) && !empty($prompt)) {
            $prompts[$type] = $prompt;
        }
    }

    return $prompts;
}

function parseDeepAIResponse($aiResponse, $analysisType = 'general')
{
    // Try to extract JSON from response
    $jsonMatch = [];
    if (preg_match('/\{[^}]+\}/', $aiResponse, $jsonMatch)) {
        $result = json_decode($jsonMatch[0], true);
        if ($result && isset($result['isViolation'])) {
            return [
                'isViolation' => $result['isViolation'],
                'type' => $result['type'] ?? 'Vi phạm nội dung',
                'severity' => $result['severity'] ?? 'medium',
                'details' => $result['details'] ?? 'Phân tích chuyên sâu bởi AI',
                'confidence' => $result['confidence'] ?? 0.5,
                'suggestedKeywords' => $result['suggestedKeywords'] ?? [],
                'analysisReason' => $result['analysisReason'] ?? '',
                'analysisType' => $analysisType
            ];
        }
    }

    // Fallback parsing if JSON extraction fails
    $isViolation = false;
    $confidence = 0.3;
    $suggestedKeywords = [];

    // Look for violation indicators in text
    $violationKeywords = ['vi phạm', 'xúc phạm', 'lăng mạ', 'chống phá', 'đe dọa'];
    foreach ($violationKeywords as $keyword) {
        if (mb_strpos(mb_strtolower($aiResponse), $keyword) !== false) {
            $isViolation = true;
            $confidence = 0.6;
            break;
        }
    }

    return [
        'isViolation' => $isViolation,
        'type' => $isViolation ? 'Vi phạm nội dung' : null,
        'severity' => $isViolation ? 'medium' : null,
        'details' => $aiResponse,
        'confidence' => $confidence,
        'suggestedKeywords' => $suggestedKeywords,
        'analysisReason' => '',
        'analysisType' => $analysisType
    ];
}

function combineDeepAIResults($results)
{
    $violationScore = 0;
    $totalAnalyses = count($results);
    $violationTypes = [];
    $confidences = [];
    $details = [];
    $analysisTypes = [];
    $allSuggestedKeywords = [];
    $analysisReasons = [];

    foreach ($results as $analysisType => $result) {
        $analysisTypes[] = $analysisType;

        if ($result['isViolation']) {
            $weight = getDeepAnalysisWeight($analysisType);
            $violationScore += ($result['confidence'] ?? 0.5) * $weight;
            $confidences[] = $result['confidence'] ?? 0.5;

            if (isset($result['type'])) {
                $violationTypes[] = $result['type'];
            }

            if (isset($result['details'])) {
                $details[] = "[$analysisType] " . $result['details'];
            }

            if (isset($result['suggestedKeywords'])) {
                $allSuggestedKeywords = array_merge($allSuggestedKeywords, $result['suggestedKeywords']);
            }

            if (isset($result['analysisReason'])) {
                $analysisReasons[] = "[$analysisType] " . $result['analysisReason'];
            }
        }
    }

    // Calculate weighted average confidence
    $avgConfidence = !empty($confidences) ? array_sum($confidences) / count($confidences) : 0;

    // Determine if violation based on multiple criteria
    $isViolation = false;
    $severity = 'low';

    if ($violationScore >= 0.7) {
        $isViolation = true;
        $severity = 'high';
    } elseif ($violationScore >= 0.5) {
        $isViolation = true;
        $severity = 'medium';
    } elseif ($violationScore >= 0.3) {
        $isViolation = true;
        $severity = 'low';
    }

    if ($isViolation) {
        return [
            'isViolation' => true,
            'violationType' => implode(', ', array_unique($violationTypes)) ?: 'Vi phạm nội dung',
            'severity' => $severity,
            'details' => implode('; ', $details) ?: 'Phát hiện nội dung không phù hợp',
            'confidence' => $avgConfidence,
            'analysisCount' => $totalAnalyses,
            'violationScore' => $violationScore,
            'analysisTypes' => $analysisTypes,
            'suggestedKeywords' => array_unique($allSuggestedKeywords),
            'analysisReasons' => implode('; ', $analysisReasons),
            'analysisMethod' => 'Deep AI Analysis',
            'detectedKeywords' => array_unique($allSuggestedKeywords)
        ];
    }

    return [
        'isViolation' => false,
        'violationType' => null,
        'severity' => null,
        'details' => 'Không phát hiện vi phạm qua phân tích chuyên sâu',
        'confidence' => $avgConfidence,
        'analysisCount' => $totalAnalyses,
        'violationScore' => $violationScore,
        'analysisTypes' => $analysisTypes,
        'suggestedKeywords' => [],
        'analysisReasons' => '',
        'analysisMethod' => 'Deep AI Analysis',
        'detectedKeywords' => []
    ];
}

function getDeepAnalysisWeight($analysisType)
{
    $weights = [
        'deep_political' => 1.3,     // Chính trị có trọng số cao nhất
        'deep_psychological' => 1.2, // Tâm lý học
        'deep_social' => 1.1,       // Xã hội học
        'deep_linguistic' => 1.0,   // Ngôn ngữ học
        'deep_contextual' => 0.9    // Ngữ cảnh
    ];

    return $weights[$analysisType] ?? 1.0;
}

function learnNewKeywords($keywords, $violationType)
{
    $learnedFile = __DIR__ . '/kb/08_learned_keywords.txt';

    // Determine which category to add keywords to
    $category = '';
    switch ($violationType) {
        case 'Lăng mạ, sỉ nhục':
            $category = 'insult';
            break;
        case 'Chống phá nhà nước':
            $category = 'political';
            break;
        case 'Đe dọa, bạo lực':
            $category = 'threat';
            break;
        case 'Spam/Quảng cáo':
            $category = 'spam';
            break;
        default:
            $category = 'insult'; // Default to insult category
    }

    // Read existing learned keywords
    $content = file_get_contents($learnedFile);

    // Add new keywords to the appropriate section
    $timestamp = date('Y-m-d H:i:s');
    $newKeywords = '';

    foreach ($keywords as $keyword) {
        $newKeywords .= "- $keyword\n";
    }

    // Find the appropriate section and add keywords
    $sectionHeader = '';
    switch ($category) {
        case 'insult':
            $sectionHeader = '## Lăng mạ, sỉ nhục - Tự học';
            break;
        case 'political':
            $sectionHeader = '## Chống phá nhà nước - Tự học';
            break;
        case 'threat':
            $sectionHeader = '## Đe dọa, bạo lực - Tự học';
            break;
        case 'spam':
            $sectionHeader = '## Spam/Quảng cáo - Tự học';
            break;
    }

    // Add new keywords with timestamp
    $newContent = "\n# Học từ: $timestamp\n";
    $newContent .= "# Loại vi phạm: $violationType\n";
    $newContent .= "$newKeywords\n";

    // Append to file
    file_put_contents($learnedFile, $newContent, FILE_APPEND | LOCK_EX);

    // Log the learning
    error_log("Learned new keywords: " . implode(', ', $keywords) . " for violation type: $violationType");

    // Add notification about auto-adding to trending
    $notification = "\n# AUTO-ADDED TO TRENDING: $timestamp\n";
    $notification .= "# Violation Type: $violationType\n";
    $notification .= "# Keywords: " . implode(', ', $keywords) . "\n";
    $notification .= "# Status: Successfully added to trending database\n\n";

    file_put_contents($learnedFile, $notification, FILE_APPEND | LOCK_EX);
}

function loadLearnedKeywords()
{
    $learnedFile = __DIR__ . '/kb/08_learned_keywords.txt';
    if (!file_exists($learnedFile)) {
        return [];
    }

    $content = file_get_contents($learnedFile);
    $keywords = [];

    // Extract keywords from learned file
    $lines = explode("\n", $content);
    foreach ($lines as $line) {
        $line = trim($line);
        if (strpos($line, '- ') === 0) {
            $keyword = trim(substr($line, 2));
            if (!empty($keyword)) {
                $keywords[] = $keyword;
            }
        }
    }

    return $keywords;
}

function performInitialAnalysis($comment, $model)
{
    // Step 2: Initial multi-faceted analysis
    $keywordResult = analyzeWithKeywords($comment);
    $multiAIResult = analyzeWithMultiAI($comment, $model);

    // Combine results to determine if violation found
    if ($keywordResult['isViolation']) {
        // Known trending violation detected
        $result = combineResults($keywordResult, $multiAIResult);
        $result['isKnownViolation'] = true;
        return $result;
    } elseif ($multiAIResult['isViolation']) {
        // Suspected new violation - needs detailed recheck
        $result = $multiAIResult;
        $result['isKnownViolation'] = false;
        return $result;
    } else {
        // No violation found
        return [
            'isViolation' => false,
            'isKnownViolation' => false,
            'violationType' => null,
            'severity' => null,
            'details' => 'Không phát hiện vi phạm trong phân tích ban đầu',
            'confidence' => 0.8,
            'analysisMethod' => 'Initial Multi-faceted Analysis',
            'detectedKeywords' => []
        ];
    }
}

function performDetailedRecheck($comment, $model)
{
    // Step 6: Very careful, detailed analysis from many aspects
    $deepAIResult = analyzeWithDeepAI($comment, $model);

    if ($deepAIResult['isViolation']) {
        // Check if this is a new violation or known violation
        $keywordResult = analyzeWithKeywords($comment);

        if ($keywordResult['isViolation']) {
            // Known trending violation confirmed after detailed recheck
            $result = combineResults($keywordResult, $deepAIResult);
            $result['isNewViolation'] = false;
            $result['analysisMethod'] = 'Detailed Recheck - Known Violation Confirmed';
        } else {
            // New violation confirmed
            $result = $deepAIResult;
            $result['isNewViolation'] = true;
            $result['analysisMethod'] = 'Detailed Recheck - New Violation Confirmed';
        }

        return $result;
    } else {
        // No violation found after detailed recheck
        return [
            'isViolation' => false,
            'isNewViolation' => false,
            'violationType' => null,
            'severity' => null,
            'details' => 'Không phát hiện vi phạm sau khi kiểm tra kỹ lưỡng',
            'confidence' => 0.9,
            'analysisMethod' => 'Detailed Recheck - No Violation',
            'detectedKeywords' => [],
            'suggestedKeywords' => []
        ];
    }
}
