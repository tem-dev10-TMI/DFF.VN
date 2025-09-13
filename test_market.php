<?php
require_once 'model/MarketDataModel.php';

echo "Testing MarketDataModel...\n";

try {
    $data = MarketDataModel::getCachedMarketData();
    echo "Market data count: " . count($data) . "\n";
    
    if (!empty($data)) {
        echo "First item key: " . array_keys($data)[0] . "\n";
        echo "VNINDEX data: " . print_r($data['VNINDEX'] ?? 'Not found', true) . "\n";
    } else {
        echo "No market data found\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
