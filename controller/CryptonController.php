<?php
require_once "model/MarketModel.php";

class CryptonController {
    public function index() {
        // Lấy dữ liệu từ model
        $markets = MarketModel::getMarkets();
        $currentDate = date("d/m/Y H:i");

        // Gửi qua view
        require_once "view/page/Crypton.php";
    }
}
