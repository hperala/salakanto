<?php
require_once 'include/controller/ControllerBase.php';
require_once 'include/view/SourceView.php';

class SourceController extends ControllerBase {
    function handleOpen() {
        $view = new SourceView($this->db,
                               $this->user,
                               $this->request,
                               $this->settings);
        if ($this->request->type() == Request::OPEN) {
            $source = Source::createFromDb($this->db, $this->request->id());
            $view->showNormal('include/template/source_view.php', $source);
        } elseif ($this->request->type() == Request::OPEN_EDIT) {
            $source = Source::createFromDb($this->db, $this->request->id());
            $view->showEdit('include/template/source_view_edit.php', $source);
        } elseif ($this->request->type() == Request::OPEN_CREATE) {
            $source = Source::create($this->db);
            $view->showEdit('include/template/source_view_edit.php', $source);
        }
    }
}

$controller = new SourceController();
$controller->run();