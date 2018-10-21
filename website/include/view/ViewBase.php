<?php

class ViewBase {
	private $params = array();
	private $errors = array();
	private $db;
	private $user;
	private $request;
	private $settings;
	
	protected function __construct($db, $user, $request, $settings) {
	    $this->db = $db;
	    $this->user = $user;
	    $this->request = $request;
	    $this->settings = $settings;
	    $this->setParam('header', 'include/template/header.php');
	    $this->setParam('footer', 'include/template/footer.php');
	    $this->setParam('is_logged_in', $this->user->isLoggedIn());
	    if (User::isLoggedIn()) {
	        $this->setParam('username', $this->user->nameHtml());
	    } else {
	        $this->setParam('username', '');
	    }
	    $this->setParam('allow_signup', $this->isSignupAllowed());
	    $this->setParam('selected-index', '');
	    $this->setParam('selected-names', '');
	    $this->setParam('selected-translations', '');
	    $this->setParam('selected-sources', '');
	    $this->setParam('selected-help', '');
	}
	
	protected function rootTemplatePath() {
	    return 'include/template/root.php';
	}
	
	protected function param($key) {
        return $this->params[$key];	     
	}
	
	protected function setParam($key, $value) {
	     $this->params[$key] = $value;
	}
	
	protected function setTitleParam($title) {
	    $this->setParam('title',
	                    sprintf(_('%s title'), $title));
	}
	
	protected function clearParams() {
	    $this->params = array();
	}
	
	protected function fieldError($key) {
	    if (isset($this->errors[$key])) {
	        return $this->errors[$key];
	    } else {
	        return '';
	    }
	}
	
	protected function setFieldError($key, $value) {
	    $this->errors[$key] = $value;
	}
	
	protected function clearFieldErrors() {
	    $this->errors = array();
	}
	
	protected function db() {
	    return $this->db;
	}
	
	protected function user() {
	    return $this->user;
	}
	
	protected function request() {
	    return $this->request;
	}
	
	protected function text() {
	    return $this->text;
	}
	
	protected function isSignupAllowed() {
	    // Creating the root user must be possible even when oridinary signup 
	    // is turned off
	    //
	    return !User::rootUserExists($this->db)
	           || $this->settings->allowSignup();
	}
}