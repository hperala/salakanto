<?php
require_once 'include/controller/ControllerBase.php';
require_once 'include/view/TranslationsView.php';

class TranslationsController extends ControllerBase {
    function handleOpen() {
        $view = new TranslationsView($this->db,
                                     $this->user,
                                     $this->request,
                                     $this->settings);
        $view->showNormal('include/template/translations_view.php');
    }
}

$controller = new TranslationsController();
$controller->run();