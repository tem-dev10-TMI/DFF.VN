<?php

class MarketDataModel
{
    /**
     * Cấu hình các ký hiệu và nguồn dữ liệu tương ứng.
     */
    private static $symbols = [
        "BTC"      => ["symbol" => "BTCUSDT", "source" => "crypto"],
        "ETH"      => ["symbol" => "ETHUSDT", "source" => "crypto"],       
        "XRP"      => ["symbol" => "XRPUSDT", "source" => "crypto"],
        "BNB"      => ["symbol" => "BNBUSDT", "source" => "crypto"],
        "SOL"      => ["symbol" => "SOLUSDT", "source" => "crypto"],
        "USDC"     => ["symbol" => "USDCUSDT", "source" => "crypto"],
        "DOGE"     => ["symbol" => "DOGEUSDT", "source" => "crypto"],
        "TRX"      => ["symbol" => "TRXUSDT", "source" => "crypto"],
        "ADA"      => ["symbol" => "ADAUSDT", "source" => "crypto"],
    ];

    /**
     * Lấy dữ liệu thị trường từ các nguồn API khác nhau.
     * @return array Dữ liệu thị trường
     */
    public static function getMarketData(): array
    {
        $marketData = [];
        foreach (self::$symbols as $name => $info) {
            $data = null;
            switch ($info['source']) {
                case 'crypto':
                    $data = self::fetchCryptoData($info['symbol']);
                    break;                
            }

            if (!$data) {
                $data = [
                    "price"         => "---",
                    "change"        => "---",
                    "changePercent" => "---",
                    "isPositive"    => true,
                ];
            }

            $marketData[$name] = [
                "symbol"        => $info['symbol'],
                "name"          => $name,
                "price"         => $data['price'],
                "change"        => $data['change'],
                "changePercent" => $data['changePercent'],
                "isPositive"    => $data['isPositive'],
            ];
        }

        return $marketData;
    }

    /**
     * Lấy dữ liệu crypto từ Binance.
     * @param string $symbol Ký hiệu tiền điện tử
     * @return array|null Dữ liệu đã xử lý hoặc null nếu thất bại
     */
    private static function fetchCryptoData(string $symbol): ?array
    {
        $url = "https://api.binance.com/api/v3/ticker/24hr?symbol={$symbol}";
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 5,
        ]);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200 && $response) {
            $data = json_decode($response, true);
            if (isset($data['lastPrice'])) {
                return [
                    "price"         => (float) $data['lastPrice'],
                    "change"        => (float) $data['priceChange'],
                    "changePercent" => (float) $data['priceChangePercent'],
                    "isPositive"    => $data['priceChange'] >= 0,
                ];
            }
        }
        return null;
    }

    /**
     * Lấy dữ liệu từ TradingView.
     * @param string $symbol Ký hiệu chứng khoán/hàng hóa
     * @param string $market Tên thị trường (ví dụ: "america", "forex")
     * @return array|null Dữ liệu đã xử lý hoặc null nếu thất bại
     */
    
    /**
     * Lấy dữ liệu thị trường có sử dụng cache.
     * @param int $cacheTime Thời gian cache tính bằng giây.
     * @return array Dữ liệu thị trường
     */
    public static function getCachedMarketData(int $cacheTime = 60): array
    {
        $cacheFile = __DIR__ . "/../cache/market_data.json";

        if (!file_exists(dirname($cacheFile))) {
            mkdir(dirname($cacheFile), 0755, true);
        }

        if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < $cacheTime) {
            $cachedData = json_decode(file_get_contents($cacheFile), true);
            if (is_array($cachedData) && !empty($cachedData)) {
                return $cachedData;
            }
        }

        $marketData = self::getMarketData();
        file_put_contents($cacheFile, json_encode($marketData));

        return $marketData;
    }
}