<?php
class EventController {
    protected $pdo; protected $model;
    public function __construct($pdo){ $this->pdo = $pdo; $this->model = new Event($pdo); }
    public function index(){ $events = $this->model->all(200); include __DIR__ . '/../views/events/list.php'; }
    public function create(){ include __DIR__ . '/../views/events/form.php'; }
    public function store(){ $this->model->create($_POST); redirect(BASE_URL . '/index.php?route=events'); }
    public function edit($id){ $event = $this->model->find($id); include __DIR__ . '/../views/events/form.php'; }
    public function update($id){ $this->model->update($id,$_POST); redirect(BASE_URL . '/index.php?route=events'); }
    public function delete($id){ $this->model->delete($id); redirect(BASE_URL . '/index.php?route=events'); }
}
