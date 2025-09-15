<?php
class TopicController {
    protected $model;

    // KHÔNG nhận $pdo nữa
    public function __construct() {
        require_once __DIR__ . '/../../model/article/topicsmodel.php';
        require_once __DIR__ . '/../../model/article/articlesmodel.php';
        $this->model = new TopicsModel(); // Model sẽ tự dùng connect.php
    }

    public function admin(){ 
        $topics = $this->model->all(200); 
        include __DIR__ . '/../../view/admin/views/topics/list.php'; 
    }

    public function create(){ 
        include __DIR__ . '/../../view/admin/views/topics/form.php'; 
    }

    public function store(){ 
        $this->model->create($_POST); 
        redirect(BASE_URL . '/admin.php?route=topics'); 
    }

    public function edit($id){ 
        $topic = $this->model->find($id); 
        include __DIR__ . '/../../view/admin/views/topics/form.php'; 
    }

    public function update($id){ 
        $this->model->update($id,$_POST); 
        redirect(BASE_URL . '/admin.php?route=topics'); 
    }

    public function delete($id){ 
        $this->model->delete($id); 
        redirect(BASE_URL . '/admin.php?route=topics'); 
    }

    public function details_topic() {
        $id = $_GET['id'] ?? 0;
        $topic = $this->model->getTopicById($id);
        $articles = articlesmodel::getArticlesByTopicId($id, 10);
        if (!$topic) {
            echo "Chủ đề không tồn tại!";
            return;
        }

        ob_start();
        require_once 'view/page/DetailsTopic.php';
        $content = ob_get_clean();

        $profile = false; 
        require_once 'view/layout/main.php';
    }
}
