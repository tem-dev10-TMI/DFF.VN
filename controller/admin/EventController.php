<?php
class EventController {
    protected $pdo;
    protected $model;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->model = new Event($pdo);
    }

    //  Hiển thị danh sách sự kiện
    public function admin() {
        $events = $this->model->all(200);
        $view = __DIR__ . '/../../view/admin/views/events/list.php';
        if (file_exists($view)) {
            include $view;
        } else {
            echo "<p class='text-danger'>Không tìm thấy file view: $view</p>";
        }
    }
    //  Form thêm mới
    public function create() {
        $event = null;
        $view = __DIR__ . '/../../view/admin/views/events/form.php';
        if (file_exists($view)) {
            include $view;
        } else {
            echo "<p class='text-danger'>Không tìm thấy file view: $view</p>";
        }
    }

    //  Lưu sự kiện mới
    public function store() {
        $this->model->create($_POST);
        redirect(BASE_URL . '/admin.php?route=events');
    }

    //  Form sửa
    public function edit($id) {
        $event = $this->model->find($id);
        if (!$event) {
            redirect(BASE_URL . '/admin.php?route=events');
        }
        $view = __DIR__ . '/../../view/admin/views/events/form.php';
        if (file_exists($view)) {
            include $view;
        } else {
            echo "<p class='text-danger'>Không tìm thấy file view: $view</p>";
        }
    }

    //  Cập nhật
    public function update($id) {
        $this->model->update($id, $_POST);
        redirect(BASE_URL . '/admin.php?route=events');
    }

    //  Xóa
    public function delete($id) {
        $this->model->delete($id);
        redirect(BASE_URL . '/admin.php?route=events');
    }
}
