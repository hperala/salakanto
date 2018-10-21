<?php
require_once 'include/view/ViewBase.php';

class MainView extends ViewBase {

    function __construct($db, $user, $request, $settings) {
         parent::__construct($db, $user, $request, $settings);
    }
    
    function showNormal($template) {
        $this->setParam('title', _('Salakanto'));
        $this->setParam('heading', _('Main View'));
        $this->setParam('selected-index', 
                        Text::CLASS_SELECTED);
        $this->setParam('content', $template);
        require $this->rootTemplatePath();
    }
}