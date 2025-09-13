<?php
class MediaController {
    protected $pdo; protected $model;
    public function __construct($pdo){ $this->pdo = $pdo; $this->model = new Media($pdo); }
    public function admin(){ $media = $this->model->all(500); include __DIR__ . '/../../view/admin/views/media/list.php'; }
    public function create(){ include __DIR__ . '/../views/media/form.php'; }
    public function store(){
        if(!empty($_FILES['file']['name'])){
            $fn = basename($_FILES['file']['name']);
            $target = UPLOADS_DIR . '/' . time() . '_' . $fn;
            move_uploaded_file($_FILES['file']['tmp_name'], $target);
            $this->model->create(['filename'=>$fn,'url'=>str_replace(__DIR__ . '/..','',$target),'mime_type'=>$_FILES['file']['type'],'size'=>$_FILES['file']['size']]);
        }
        redirect(BASE_URL . '/admin.php?route=media');
    }
    public function delete($id){ $this->model->delete($id); redirect(BASE_URL . '/admin.php?route=media'); }
}
