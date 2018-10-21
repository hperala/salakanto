<?php
require_once 'include/controller/ControllerBase.php';
require_once 'include/view/LoginView.php';
require_once 'include/view/AccountView.php';

class LoginController extends ControllerBase {
    
    function handleOpen() {
        $view = $this->createLoginView();
        if ($this->request->action() == 'signup') {
            $this->showSignupView($view);
        } else if ($this->request->action() == 'logout') {
            $this->logoutPosted($view);        
        } else {
            $view->showNormal('include/template/login_view.php');
        }
    }
    
    function handleCreate() {
        $view = $this->createLoginView();
        if ($this->request->action() == 'signup') {
            $this->signupPosted($view);
        } else {
            $this->loginPosted($view);
        }
    }
    
    private function logoutPosted($view) {
        $this->user->logout();
        $url = $this->request->baseUrl();
        header("Location: $url");
    }
    
    private function signupPosted($view) {
        $username = $this->request->param('username');
        $password = $this->request->param('password');
        
        $res = User::isValidUsername($username);
        if (!$res['success']) {
            $this->showSignupFailedView($res['message']);
            return;
        }
        $res = User::isValidPassword($password);
        if (!$res['success']) {
            $this->showSignupFailedView($res['message']);
            return;
        }
        
        if ($this->request->hasParam('create_root')) {
            if ($this->request->param('root_password') === ROOT_PASS) {
                $user = $this->createUser(
                        array('name' => $username,
                              'passwd' => $password,
                              'user_type_id' => USER_TYPE_ID_ROOT));
                $this->showAccountView($user);
                return;
            } else {
                $this->showSignupFailedView(_('The root creation password is incorrect.'));
                return;
            }
        } else {
            $user = $this->createUser(
                    array('name' => $username,
                          'passwd' => $password,
                          'user_type_id' => USER_TYPE_ID_USER));
            $this->showAccountView($user);
            return;
        }
    }
    
    private function loginPosted($view) {
        $username = $this->request->param('username');
        $password = $this->request->param('password');
        
        $result = $this->user->login($username, $password);
        if ($result['success']) {
            $url = $this->request->baseUrl();
            header("Location: $url");
        } else {
            $view->setErrorMessage($result['message']);
            $view->showNormal('include/template/login_view.php');
        }
    }
    
    private function createLoginView() {
        return new LoginView($this->db,
                             $this->user,
                             $this->request,
                             $this->settings);
    }
    
    private function showSignupFailedView($message) {
        $view = $this->createLoginView();
        $view->setErrorMessage($message);
        $this->showSignupView($view);
    }
    
    private function showSignupView($view) {
        $view->showSignup('include/template/login_view_signup.php');
    }
    
    private function showAccountView($user) {
        $view = new AccountView($this->db,
                                $user,
                                $this->request,
                                $this->settings);
        $view->showNormal('include/template/account_view.php');
    }
    
    private function createUser($values) {
        $user = User::createFromValues($this->db, $values);
        $user->saveAsNew();
        $user->loginWithoutCheck();
        return $user;
    }
}

$controller = new LoginController();
$controller->run();