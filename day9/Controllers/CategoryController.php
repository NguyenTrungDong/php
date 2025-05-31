<?php
namespace Day9\Controllers;

use Day9\Models\Category;

class CategoryController {
    private $category;

    public function __construct() {
        $this->category = new Category();
    }

    public function index() {
        $categories = $this->category->readAll();
        $view_file = BASE_URL .'views/categories/index.php';
        require_once BASE_URL .'views/layouts/main.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];
            if ($this->category->create($name)) {
                header("Location: index.php?action=manage_categories");
                exit;
            }
        }
        $this->index();
    }

    public function edit($id) {
        $category = $this->category->readOne($id);
        if (!$category) {
            echo "Danh mục không tồn tại.";
            return;
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];
            if ($this->category->update($id, $name)) {
                header("Location: index.php?action=manage_categories");
                exit;
            }
        }
        $view_file = BASE_URL .'views/categories/edit.php';
        require_once BASE_URL .'views/layouts/main.php';
    }

    public function delete($id) {
        if ($this->category->delete($id)) {
            header("Location: index.php?action=manage_categories");
            exit;
        }
    }
}
?>