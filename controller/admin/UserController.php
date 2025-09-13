<?php
class UserController {
    protected $pdo; protected $model;
    public function __construct($pdo){ $this->pdo = $pdo; $this->model = new User($pdo); }
    public function admin(){ $users = $this->model->all(200); include __DIR__ . '/../../view/admin/views/users/list.php'; }
    public function create(){ include __DIR__ . '/../../view/admin/views/users/form.php'; }
   public function store() {
    try {
        $this->model->create($_POST);
        header("Location: " . BASE_URL . "/admin.php?route=users");
    } catch (Exception $e) {
        flash('error', $e->getMessage());
        header("Location: " . BASE_URL . "/admin.php?route=users&action=create");
    }
}
    public function edit($id){ $user = $this->model->find($id); include __DIR__ . '/../../view/admin/views/users/form.php'; }
   public function update($id) {
    try {
        $this->model->update($id, $_POST);
        flash('success', 'Cập nhật người dùng thành công');
        header("Location: " . BASE_URL . "/admin.php?route=users");
    } catch (Exception $e) {
        flash('error', $e->getMessage());
        header("Location: " . BASE_URL . "/admin.php?route=users&action=edit&id=".$id);
    }
}
    public function delete($id){ $this->model->delete($id); redirect(BASE_URL . '/admin.php?route=users'); }
}
