<?php
class EventDetailController {
    protected $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

   public function details($id) {
    if (!$id) {
        echo "<p>Không có sự kiện nào được chọn!</p>";
        return;
    }

    // nạp model
    require_once __DIR__ . '/../model/Event.php';
    $eventModel = new EventModel($this->pdo);
    $event = $eventModel->find($id);

    if (!$event) {
        echo "<p>Sự kiện không tồn tại!</p>";
        return;
    }

    // chỉ định view
    $viewFile = __DIR__ . '/../view/page/Event.php';

    // Gán nội dung view vào $content
    ob_start();
    include $viewFile;
    $content = ob_get_clean();

    // nhúng layout chính
    include __DIR__ . '/../view/layout/main.php';
}
}