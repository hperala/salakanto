<?php
require_once 'include/controller/ControllerBase.php';
require_once 'include/view/SourcesView.php';

class SourcesController extends ControllerBase {
    function handleOpen() {
        $view = new SourcesView($this->db,
                                $this->user,
                                $this->request,
                                $this->settings);
        $view->showNormal('include/template/sources_view.php');
    }
}

$controller = new SourcesController();
$controller->run();