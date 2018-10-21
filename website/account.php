<?php
require_once 'include/controller/ControllerBase.php';
require_once 'include/view/AccountView.php';

class AccountController extends ControllerBase {
    function handleOpen() {
    	if (!$this->user->isLoggedIn()) {
            throw new Exception();
        }
        
        $view = new AccountView($this->db,
                                $this->user,
                                $this->request,
                                $this->settings);
        $view->showNormal('include/template/account_view.php');
    }
}

$controller = new AccountController();
$controller->run();