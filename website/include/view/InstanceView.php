<?php
require_once 'include/view/ViewBase.php';
require_once 'include/model/Instance.php';

class InstanceView extends ViewBase {

    function __construct($db, $user, $request, $settings) {
        parent::__construct($db, $user, $request, $settings);
    }

    function showNormal($template, $ins) {
        $this->setParam('content', $template);

        $this->formatNormal($ins);

        require $this->rootTemplatePath();
    }

    function showEdit($template, $ins) {
        $this->setParam('content', $template);

        $this->formatEdit($ins);

        require $this->rootTemplatePath();
    }

    private function formatNormal($ins) {
        $this->formatGeneral($ins);
    }

    private function formatEdit($ins) {
        $this->formatGeneral($ins);
        
        $this->setParam('sources', array(array('id' => '123',
                                               'text' => 'X'),
                                         array('id' => '234',
                                               'text' => 'L')));
    }
    
    private function formatGeneral($ins) {
        $this->setParam('item', $ins);
    
        $this->setTitleParam($ins->textHtml());
        $this->setParam('heading', $ins->textHtml());
    }
}