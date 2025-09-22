<?php
class MarketDataModel
{
    // Symbols
    private static $symbols = [
        "VNINDEX"   => "HOSE:VNINDEX",
        "HNX"       => "HNX:INDEX",
        "VN30"      => "HOSE:VN30",
        "VN30F1M"   => "HOSE:VN30F1M",
        "UPCOM"     => "UPCOM:INDEX",

        "Silver"    => "TVC:SILVER",
        "Oil"       => "TVC:USOIL",

        "Bitcoin"   => "BTCUSDT",
        "Ethereum"  => "ETHUSDT",
        "BNB"       => "BNBUSDT"
    ];

    // Lấy dữ liệu thị trường
    public static function getMarketData()
    {
        $marketData = [];

        foreach (self::$symbols as $name => $symbol) {
            if (in_array($name, ["Bitcoin", "Ethereum", "BNB"])) {
                $data = self::fetchCryptoData($symbol);
            } elseif (in_array($name, ["Silver", "Oil"])) {
                $data = self::fetchTradingViewData($symbol, "forex");
            } else { // VNINDEX, HNX, VN30, VN30F1M, UPCOM
                $data = self::fetchTradingViewData($symbol, "asia");
            }

            // Nếu không lấy được dữ liệu thật, để "---"
            if (!$data) {
                $data = [
                    "price" => "---",
                    "change" => "---",
                    "changePercent" => "---",
                    "isPositive" => true
                ];
            }

            $marketData[$name] = [
                "symbol" => $symbol,
                "name" => $name,
                "price" => $data['price'],
                "change" => $data['change'],
                "changePercent" => $data['changePercent'],
                "isPositive" => $data['isPositive']
            ];
        }

        return $marketData;
    }

    // Lấy crypto từ Binance
    private static function fetchCryptoData($symbol)
    {
        $url = "https://api.binance.com/api/v3/ticker/24hr?symbol={$symbol}";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200 && $response) {
            $data = json_decode($response, true);
            if (!empty($data['lastPrice'])) {
                return [
                    "price" => number_format($data['lastPrice'], 2),
                    "change" => round($data['priceChange'], 2),
                    "changePercent" => round($data['priceChangePercent'], 2),
                    "isPositive" => $data['priceChange'] >= 0
                ];
            }
        }
        return null;
    }

    // Lấy dữ liệu từ TradingView
    private static function fetchTradingViewData($symbol, $market = "asia")
    {
        $url = "https://scanner.tradingview.com/{$market}/scan";

        $postData = [
            "symbols" => [
                "tickers" => [$symbol],
                "query" => ["types" => []]
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
            "range" => [0, 1]
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "User-Agent: Mozilla/5.0"
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200 && $response) {
            $data = json_decode($response, true);
            if (isset($data['data'][0]['d']) && !empty($data['data'][0]['d'])) {
                $item = $data['data'][0]['d'];
                return [
                    "price" => number_format($item[1], 2),
                    "change" => round($item[3], 2),
                    "changePercent" => round($item[4], 2),
                    "isPositive" => $item[3] >= 0
                ];
            }
        }

        return null; // Không còn dữ liệu mẫu
    }

    // Cache dữ liệu
    public static function getCachedMarketData($cacheTime = 60)
    {
        $cacheFile = __DIR__ . "/../cache/market_data.json";

        if (!file_exists(dirname($cacheFile))) {
            mkdir(dirname($cacheFile), 0755, true);
        }

        if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < $cacheTime) {
            $cachedData = json_decode(file_get_contents($cacheFile), true);
            if ($cachedData) return $cachedData;
        }

        $marketData = self::getMarketData();
        file_put_contents($cacheFile, json_encode($marketData));

        return $marketData;
    }
}
