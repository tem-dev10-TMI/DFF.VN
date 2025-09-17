<?php
class TopicController {
    protected $model;

    public function __construct() {
        require_once __DIR__ . '/../../model/article/topicsmodel.php';
        require_once __DIR__ . '/../../model/article/articlesmodel.php';
        $this->model = new TopicsModel();
    }

    // Mặc định (list)
    public function admin(){ 
        $topics = $this->model->all(200); 
        include __DIR__ . '/../../view/admin/views/topics/list.php'; 
    }

    // Thêm mới
    public function create(){ 
        include __DIR__ . '/../../view/admin/views/topics/form.php'; 
    }

    public function store(){ 
        $this->model->create($_POST); 
        redirect(BASE_URL . '/admin.php?route=topics');
    }

    // Sửa
    public function edit($id){ 
        $topic = $this->model->find($id); 
        include __DIR__ . '/../../view/admin/views/topics/form.php'; 
    }

    public function update($id){ 
        $this->model->update($id,$_POST); 
        redirect(BASE_URL . '/admin.php?route=topics');
    }

    // Xóa
    public function delete($id){ 
        $this->model->delete($id); 
        redirect(BASE_URL . '/admin.php?route=topics');
    }

    // Chi tiết
    public function details_topic($id = null) {
        $id = $id ?? ($_GET['id'] ?? 0);
        $topic = $this->model->getTopicById($id);
        $articles = articlesmodel::getArticlesByTopicId($id, 10);

        if (!$topic) {
            echo "Chủ đề không tồn tại!";
            return;
        }

        ob_start();
        require __DIR__ . '/../../view/page/DetailsTopic.php';
        $content = ob_get_clean();

        $profile = false; 
        require __DIR__ . '/../../view/layout/main.php';
    }
}
