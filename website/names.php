<?php
require_once 'include/controller/ControllerBase.php';
require_once 'include/model/Name.php';
require_once 'include/view/NamesView.php';

class NamesController extends ControllerBase {
    function handleOpen() {
        $list = Name::createList($this->db, 0, -1);
        $view = new NamesView($this->db,
                              $this->user,
                              $this->request,
                              $this->settings,
                              $list);
        $view->showNormal('include/template/names_view.php');
    }
}

$controller = new NamesController();
$controller->run();