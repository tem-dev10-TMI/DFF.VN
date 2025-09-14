<?php
class MarketModel {
    public static function getMarkets() {
        return [
            ["symbol" => "SOLUSDT", "price" => 238.21, "change" => "+16.88 (7.08%)", "class" => "positive"],
            ["symbol" => "SUIUSDT", "price" => 3.67, "change" => "+0.04 (1.22%)", "class" => "positive"],
            ["symbol" => "ICPUSDT", "price" => 4.94, "change" => "+0.03 (0.69%)", "class" => "positive"],
            ["symbol" => "BTCUSDT", "price" => 115368.28, "change" => "+1064.85 (0.92%)", "class" => "positive"],
            ["symbol" => "AVAXUSDT", "price" => 28.82, "change" => "-0.26 (-0.89%)", "class" => "negative"],
            ["symbol" => "BNBUSDT", "price" => 908.14, "change" => "+13.45 (1.48%)", "class" => "positive"],
            ["symbol" => "TAOUSDT", "price" => 357.60, "change" => "+5.59 (1.56%)", "class" => "positive"],
            ["symbol" => "ADAUSDT", "price" => 0.90, "change" => "+0.02 (1.84%)", "class" => "positive"],
        ];
    }
}
