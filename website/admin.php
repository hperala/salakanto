<?php
require_once 'include/controller/ControllerBase.php';
require_once 'include/view/AdminView.php';
require_once 'include/model/Importer.php';

class AdminController extends ControllerBase {
    function handleOpen() {
        if (!$this->user->isLoggedIn()) {
            throw new Exception();
        }

        $view = new AdminView($this->db,
                              $this->user,
                              $this->request,
                              $this->settings);
        $view->showNormal('include/template/admin_view.php');
    }

    function handleUpload() {
        if (!$this->user->isLoggedIn()) {
            throw new Exception();
        }

        $importer = new Importer($this->db);
        $importer->import($_FILES['file']['tmp_name']);
        $view = new AdminView($this->db,
                              $this->user,
                              $this->request,
                              $this->settings);
        $view->setMessage($_FILES['file']['tmp_name']);
        $view->showNormal('include/template/admin_view.php');
    }
}

$controller = new AdminController();
$controller->run();