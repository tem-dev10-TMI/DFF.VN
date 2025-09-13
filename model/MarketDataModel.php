<?php
class MarketDataModel
{
    // TradingView API endpoints và symbols
    private static $symbols = [
        'VNINDEX' => 'VC:VNINDEX',
        'HNX' => 'VC:HNX',
        'VN30F1M' => 'VC:VN30F1M',
        'VN30' => 'VC:VN30',
        'UPCOM' => 'VC:UPCOM',
        'Silver' => 'TVC:SILVER',
        'Oil' => 'TVC:USOIL',
        'Bitcoin' => 'BINANCE:BTCUSDT',
        'Ethereum' => 'BINANCE:ETHUSDT',
        'BNB' => 'BINANCE:BNBUSDT'
    ];

    // Lấy dữ liệu từ TradingView API
    public static function getMarketData()
    {
        $marketData = [];
        
        foreach (self::$symbols as $name => $symbol) {
            $data = self::fetchTradingViewData($symbol);
            if ($data) {
                $marketData[$name] = [
                    'symbol' => $symbol,
                    'name' => $name,
                    'price' => $data['price'],
                    'change' => $data['change'],
                    'changePercent' => $data['changePercent'],
                    'isPositive' => $data['change'] >= 0
                ];
            }
        }
        
        return $marketData;
    }

    // Fetch dữ liệu từ TradingView
    private static function fetchTradingViewData($symbol)
    {
        // TradingView widget API
        $url = "https://scanner.tradingview.com/vietnam/scan";
        
        $postData = [
            "symbols" => [
                "tickers" => [$symbol],
                "query" => [
                    "types" => []
                ],
                "columns" => [
                    "name",
                    "close",
                    "change",
                    "change_abs",
                    "change_percent"
                ],
                "sort" => [
                    "sortBy" => "market_cap_basic",
                    "sortOrder" => "desc"
                ],
                "options" => [
                    "lang" => "en"
                ],
                "range" => [0, 1]
            ]
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200 && $response) {
            $data = json_decode($response, true);
            
            if (isset($data['data'][0]['d'])) {
                $item = $data['data'][0]['d'];
                return [
                    'price' => number_format($item[1], 2),
                    'change' => round($item[3], 2),
                    'changePercent' => round($item[4], 2)
                ];
            }
        }

        // Fallback: Tạo dữ liệu mẫu nếu API không hoạt động
        return self::getSampleData($symbol);
    }

    // Dữ liệu mẫu khi API không khả dụng
    private static function getSampleData($symbol)
    {
        $sampleData = [
            'VC:VNINDEX' => ['price' => '1,667.26', 'change' => 9.51, 'changePercent' => 0.57],
            'VC:HNX' => ['price' => '245.33', 'change' => 2.33, 'changePercent' => 0.96],
            'VC:VN30F1M' => ['price' => '276.51', 'change' => 5.5, 'changePercent' => 0.85],
            'VC:VN30' => ['price' => '1,859.00', 'change' => 10.37, 'changePercent' => 0.3],
            'VC:UPCOM' => ['price' => '1,865.45', 'change' => -0.01, 'changePercent' => 0.56],
            'TVC:SILVER' => ['price' => '110.09', 'change' => 0.68, 'changePercent' => -0.01],
            'TVC:USOIL' => ['price' => '42.83', 'change' => 0.32, 'changePercent' => 1.62],
            'BINANCE:BTCUSDT' => ['price' => '62.69', 'change' => 745.53, 'changePercent' => 0.51],
            'BINANCE:ETHUSDT' => ['price' => '115,974.00', 'change' => 271.52, 'changePercent' => 0.64],
            'BINANCE:BNBUSDT' => ['price' => '4,760.21', 'change' => 25.54, 'changePercent' => 5.7]
        ];

        return $sampleData[$symbol] ?? ['price' => '0.00', 'change' => 0, 'changePercent' => 0];
    }

    // Cache dữ liệu để tránh gọi API quá nhiều
    public static function getCachedMarketData()
    {
        $cacheFile = 'cache/market_data.json';
        $cacheTime = 60; // Cache 60 giây

        // Tạo thư mục cache nếu chưa có
        if (!file_exists('cache')) {
            mkdir('cache', 0755, true);
        }

        // Kiểm tra cache
        if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < $cacheTime) {
            $cachedData = json_decode(file_get_contents($cacheFile), true);
            if ($cachedData) {
                return $cachedData;
            }
        }

        // Lấy dữ liệu mới và cache
        $marketData = self::getMarketData();
        file_put_contents($cacheFile, json_encode($marketData));
        
        return $marketData;
    }
}
