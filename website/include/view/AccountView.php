<?php
require_once 'include/view/ViewBase.php';

class AccountView extends ViewBase {

    function __construct($db, $user, $request, $settings) {
        parent::__construct($db, $user, $request, $settings);
    }

    function showNormal($template) {
        $this->setParam('content', $template);

        $this->formatNormal();

        require $this->rootTemplatePath();
    }

    private function formatNormal() {        
        $this->setTitleParam(_('User account'));
        $this->setParam('heading', _('Account details'));
        $this->setParam('message', '');
        $this->setParam('is-admin', true);
    }
}