<?php
require_once 'include/view/ViewBase.php';
require_once 'include/model/Instance.php';
require_once 'include/view/Pagination.php';

class TranslationsView extends ViewBase {

    function __construct($db, $user, $request, $settings) {
        parent::__construct($db, $user, $request, $settings);
    }

    function showNormal($template) {
        $this->setTitleParam(_('Forms with explicit translation'));
        $this->setParam('heading', _('Forms with explicit translation'));
        $this->setParam('content', $template);
        $this->setParam('selected-translations', Text::CLASS_SELECTED);
        $this->format();
        
        require $this->rootTemplatePath();
    }
    
    private function format() {
        $instances = Instance::createList($this->db(), 0, -1);
        $list = array();
        foreach ($instances as $ins) {
            if (!$ins->translation()) {
                continue;
            }
            
            $item = array();
            $item['id'] = $ins->nameIdHtml();
            $item['text'] = $ins->textHtml();
            $item['transl'] = $ins->translationHtml();
            $item['ref'] = $ins->refHtml();
            $list[] = $item;
        }
        $this->setParam('name_list', $list);
        $this->setParam('pagination', new Pagination(1, 2, 3));
    }
}