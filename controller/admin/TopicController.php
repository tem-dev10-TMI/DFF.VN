<?php
class TopicController {
    protected $pdo; protected $model;
    public function __construct($pdo){ $this->pdo = $pdo; $this->model = new Topic($pdo); }
    public function admin(){ $topics = $this->model->all(200); include __DIR__ . '/../../view/admin/views/topics/list.php'; }
    public function create(){ include __DIR__ . '/../../view/admin/views/topics/form.php'; }
    public function store(){ $this->model->create($_POST); redirect(BASE_URL . '/admin.php?route=topics'); }
    public function edit($id){ $topic = $this->model->find($id); include __DIR__ . '/../../view/admin/views/topics/form.php'; }
    public function update($id){ $this->model->update($id,$_POST); redirect(BASE_URL . '/admin.php?route=topics'); }
    public function delete($id){ $this->model->delete($id); redirect(BASE_URL . '/admin.php?route=topics'); }
}
