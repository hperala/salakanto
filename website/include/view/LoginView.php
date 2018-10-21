<?php
require_once 'include/view/ViewBase.php';

class LoginView extends ViewBase {

    private $errorMessage = '';
    
    function __construct($db, $user, $request, $settings) {
        parent::__construct($db, $user, $request, $settings);
    }

    function showNormal($template) {
        $this->setParam('content', $template);

        $this->formatNormal();

        require $this->rootTemplatePath();
    }
    
    function showSignup($template) {        
        $this->setParam('content', $template);
    
        $this->formatSignup();
    
        require $this->rootTemplatePath();
    }

    function setErrorMessage($message) {
        $this->errorMessage = $message;
    }
    
    private function formatNormal() {
        $this->formatGeneral();
        
        $this->setTitleParam(_('Login'));
        $this->setParam('heading', _('Log in'));
    }
    
    private function formatSignup() {
        $this->formatGeneral();
        
        $this->setTitleParam(_('Sign up'));
        $this->setParam('heading', _('Sign up'));
        $this->setParam('root_exists', User::rootUserExists($this->db()));
        $this->setParam('can_create_root', !User::rootUserExists($this->db()));
        if (!User::rootUserExists($this->db())) {
            $this->setParam('create_root_checked', 'checked');
        } else {
            $this->setParam('create_root_checked', '');
        }
    }
    
    private function formatGeneral() {
        $this->setParam('error', $this->errorMessage);
    }
}