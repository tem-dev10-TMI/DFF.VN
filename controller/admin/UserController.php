<?php
class UserController
{
    protected PDO $pdo;
    protected User $model;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
        $this->model = new User($pdo);
    }

    // LIST + filter + pagination
    public function admin()
    {
        // --- Filters ---
        $filters = [
            'name' => trim($_GET['name'] ?? ''),
            'email' => trim($_GET['email'] ?? ''),
            'role' => trim($_GET['role'] ?? ''),
        ];

        // --- Pagination ---
        $perPage = 10;
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $offset = ($page - 1) * $perPage;

        // --- Data ---
        $users = $this->model->search($filters, $perPage, $offset);
        $total = $this->model->count($filters);
        $pages = max(1, (int) ceil($total / $perPage));

        // view variables: $users, $page, $pages, $filters, $total
        include __DIR__ . '/../../view/admin/views/users/list.php';
    }

    public function create()
    {
        include __DIR__ . '/../../view/admin/views/users/form.php';
    }

    public function store()
    {
        try {
            $this->model->create($_POST);
            flash('success', 'Tạo người dùng thành công');
            header("Location: " . BASE_URL . "/admin.php?route=users");
        } catch (Exception $e) {
            flash('error', $e->getMessage());
            header("Location: " . BASE_URL . "/admin.php?route=users&action=create");
        }
    }

    public function edit($id)
    {
        $user = $this->model->find($id);
        include __DIR__ . '/../../view/admin/views/users/form.php';
    }

    public function update($id)
    {
        try {
            $this->model->update($id, $_POST);
            flash('success', 'Cập nhật người dùng thành công');
            header("Location: " . BASE_URL . "/admin.php?route=users");
        } catch (Exception $e) {
            flash('error', $e->getMessage());
            header("Location: " . BASE_URL . "/admin.php?route=users&action=edit&id=" . $id);
        }
    }

    public function delete($id)
    {
        $this->model->delete($id);
        redirect(BASE_URL . '/admin.php?route=users');
    }
}
