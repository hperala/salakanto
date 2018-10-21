<?php
require_once 'include/view/ViewBase.php';

class StaticView extends ViewBase {

    private $title = '';    
    private $heading = '';
    
    function __construct($db, $user, $request, $settings) {
        parent::__construct($db, $user, $request, $settings);
    }

    function showNormal($template) {
        $this->setParam('content', $template);

        $this->formatNormal();

        require $this->rootTemplatePath();
    }

    function setTitle($title) {
        $this->title = $title;
    }
    
    function setHeading($heading) {
        $this->heading = $heading;
    }
    
    function setSelectedNavLink($param) {
        $this->setParam($param, Text::CLASS_SELECTED);
    }
    
    private function formatNormal() {        
        $this->setTitleParam($this->title);
        $this->setParam('heading', $this->heading);
    }
}