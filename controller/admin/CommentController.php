<?php
class CommentController {
    protected $pdo; protected $model;
    public function __construct($pdo){ $this->pdo = $pdo; $this->model = new Comment($pdo); }
    public function admin(){ $comments = $this->model->all(500); include __DIR__ . '/../../view/admin/views/comments/list.php'; }
    public function approve($id){ $this->model->approve($id); redirect(BASE_URL . '/admin.php?route=comments'); }
    public function delete($id){ $this->model->delete($id); redirect(BASE_URL . '/admin.php?route=comments'); }
}
