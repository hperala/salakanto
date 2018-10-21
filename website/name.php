<?php
require_once 'include/controller/ControllerBase.php';
require_once 'include/view/NameView.php';

class NameController extends ControllerBase {
    function handleOpen() {
        $view = new NameView($this->db,
                             $this->user,
                             $this->request,
                             $this->settings);
        if ($this->request->type() == Request::OPEN) {
            $name = Name::createFromDb($this->db, $this->request->id());
            $instances = Instance::createListByNameId($this->db, $name->id(), 0, -1);
            $view->showNormal('include/template/name_view.php', $name, $instances);
        } elseif ($this->request->type() == Request::OPEN_EDIT) {
            $name = Name::createFromDb($this->db, $this->request->id());
            $instances = Instance::createListByNameId($this->db, $name->id(), 0, -1);
            $view->showEdit('include/template/name_view_edit.php', $name, $instances);
        } elseif ($this->request->type() == Request::OPEN_CREATE) {
            $name = Name::create($this->db);
            $instances = array();
            $view->showEdit('include/template/name_view_edit.php', $name, $instances);
        }
    }
}

$controller = new NameController();
$controller->run();