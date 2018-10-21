<?php
require_once 'include/view/ViewBase.php';

class AdminView extends ViewBase {

    private $message = '';
    
    function __construct($db, $user, $request, $settings) {
        parent::__construct($db, $user, $request, $settings);
    }

    function showNormal($template) {
        $this->setParam('content', $template);

        $this->formatNormal();

        require $this->rootTemplatePath();
    }

    function setMessage($message) {
        $this->message = $message;
    }
    
    private function formatNormal() {
        $this->setTitleParam(_('Site administration'));
        $this->setParam('heading', _('Site administration'));
        if ($this->message != '') {
            $this->setParam('message', $this->message);
        } else {
            $this->setParam('message', 'a');
        }
        $this->setParam('is-admin', true);
        
        $this->setParam('users', array(array('text' => 'Pertti',
                                             'date' => '1.2.3456'),
                                       array('text' => 'Erkki',
                                             'date' => '12.23.1111')));
    }
}