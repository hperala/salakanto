<?php
require_once 'include/view/ViewBase.php';
require_once 'include/model/Source.php';
require_once 'include/view/Pagination.php';

class SourcesView extends ViewBase {

    function __construct($db, $user, $request, $settings) {
        parent::__construct($db, $user, $request, $settings);
    }

    function showNormal($template) {
        $this->setTitleParam(_('Sources'));
        $this->setParam('heading', _('Sources'));
        $this->setParam('content', $template);
        $this->setParam('selected-sources', Text::CLASS_SELECTED);
        $this->format();
        
        require $this->rootTemplatePath();
    }
    
    private function format() {
        $rows = array();
        $list = Source::createList($this->db(), 0, -1);
        foreach ($list as $item) {
            $row = array();
            $row['has_edit_button'] = $this->param('is_logged_in');
            $row['id'] = $item->id();
            $row['text'] = $item->abbreviationHtml();
            $rows[] = $row;
        }
        $this->setParam('source_list', $rows);
        $this->setParam('pagination', new Pagination(1, 2, 3));
    }
}