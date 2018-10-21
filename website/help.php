<?php
require_once 'include/controller/ControllerBase.php';
require_once 'include/view/StaticView.php';
require_once 'include/model/Text.php';

class StaticController extends ControllerBase {
    function handleOpen() {
        $view = new StaticView($this->db,
                               $this->user,
                               $this->request,
                               $this->settings);
        $view->setHeading(_('Using Salakanto'));
        $view->setTitle(_('Help'));
        $view->setSelectedNavLink('selected-help');
        $view->showNormal('include/template/help_view.php');
    }
}

$controller = new StaticController();
$controller->run();