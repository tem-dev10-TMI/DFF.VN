<?php
require_once __DIR__ . "/../model/MarketModel.php";

class CryptonController {
    public function index() {
        // Lấy dữ liệu từ model
        $markets = MarketModel::getMarkets();
        $currentDate = date("d/m/Y H:i");

        // Gửi qua view
        require_once __DIR__ . "/../view/page/Crypton.php";
    }
}
