<?php
require_once 'include/controller/ControllerBase.php';
require_once 'include/view/MainView.php';

class MainController extends ControllerBase {
    function handleOpen() {
        $view = new MainView($this->db, 
                             $this->user, 
                             $this->request,
                             $this->settings);
        $view->showNormal('include/template/main_view.php');
    }
}

$controller = new MainController();
$controller->run();