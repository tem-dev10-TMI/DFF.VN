<?php
class TagController {
    protected $pdo; protected $model;
    public function __construct($pdo){ $this->pdo = $pdo; $this->model = new Tag($pdo); }
    public function index(){ $tags = $this->model->all(500); include __DIR__ . '/../views/tags/list.php'; }
    public function create(){ include __DIR__ . '/../views/tags/form.php'; }
    public function store(){ $this->model->create($_POST); redirect(BASE_URL . '/index.php?route=tags'); }
    public function edit($id){ $tag = $this->model->find($id); include __DIR__ . '/../views/tags/form.php'; }
    public function update($id){ $this->model->update($id,$_POST); redirect(BASE_URL . '/index.php?route=tags'); }
    public function delete($id){ $this->model->delete($id); redirect(BASE_URL . '/index.php?route=tags'); }
}
