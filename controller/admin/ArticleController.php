<?php
class ArticleController {
    protected $pdo; protected $model;
    public function __construct($pdo){ $this->pdo = $pdo; $this->model = new Article($pdo); }
    public function index(){ $articles = $this->model->listWithJoin(200); include __DIR__ . '/../views/articles/list.php'; }
    public function create(){ $topics = (new Topic($this->pdo))->all(200); $users = (new User($this->pdo))->all(200); include __DIR__ . '/../views/articles/form.php'; }
    public function store(){
        if(!empty($_FILES['main_image']['name'])){
            $fn = basename($_FILES['main_image']['name']);
            $target = UPLOADS_DIR . '/' . time() . '_' . $fn;
            move_uploaded_file($_FILES['main_image']['tmp_name'], $target);
            $_POST['main_image_url'] = str_replace(__DIR__ . '/..', '', $target);
        }
        $this->model->create($_POST);
        redirect(BASE_URL . '/index.php?route=articles');
    }
    public function edit($id){ $article = $this->model->find($id); $topics = (new Topic($this->pdo))->all(200); $users = (new User($this->pdo))->all(200); include __DIR__ . '/../views/articles/form.php'; }
    public function update($id){
        if(!empty($_FILES['main_image']['name'])){
            $fn = basename($_FILES['main_image']['name']);
            $target = UPLOADS_DIR . '/' . time() . '_' . $fn;
            move_uploaded_file($_FILES['main_image']['tmp_name'], $target);
            $_POST['main_image_url'] = str_replace(__DIR__ . '/..', '', $target);
        }
        $this->model->update($id, $_POST);
        redirect(BASE_URL . '/index.php?route=articles');
    }
    public function delete($id){ $this->model->delete($id); redirect(BASE_URL . '/index.php?route=articles'); }
}
