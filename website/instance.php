<?php
require_once 'include/controller/ControllerBase.php';
require_once 'include/view/InstanceView.php';

class InstanceController extends ControllerBase {
    function handleOpen() {
        $view = new InstanceView($this->db,
                                 $this->user,
                                 $this->request,
                                 $this->settings);
        if ($this->request->type() == Request::OPEN) {
            $ins = Instance::createFromDb($this->db, $this->request->id());
            $view->showNormal('include/template/instance_view.php', $ins);
        } elseif ($this->request->type() == Request::OPEN_EDIT) {
            $ins = Instance::createFromDb($this->db, $this->request->id());
            $view->showEdit('include/template/instance_view_edit.php', $ins);
        } elseif ($this->request->type() == Request::OPEN_CREATE) {
            $ins = Instance::create($this->db);
            $view->showEdit('include/template/instance_view_edit.php', $ins);
        }
    }
}

$controller = new InstanceController();
$controller->run();