<?php
require_once 'include/view/ViewBase.php';
require_once 'include/model/Name.php';
require_once 'include/view/Pagination.php';

class NamesView extends ViewBase {

	private $itemList;
	
    function __construct($db, $user, $request, $settings, $itemList) {
        parent::__construct($db, $user, $request, $settings);
        $this->itemList = $itemList;
    }

    function showNormal($template) {
        $this->setTitleParam(_('All names'));
        $this->setParam('heading', _('All Names'));
        $this->setParam('content', $template);
        $this->setParam('selected-names', Text::CLASS_SELECTED);
        $this->format();
        
        require $this->rootTemplatePath();
    }
    
    private function format() {
        $rows = array();
        foreach ($this->itemList as $item) {
            $row = array();
            $row['has_edit_button'] = $this->param('is_logged_in');
            $row['id'] = $item->id();
            $row['text'] = $item->titleHtml();
            $row['translations'] = $item->numTranslationsHtml();
            $row['notes'] = $item->numEtymologiesHtml();
            $rows[] = $row;
        }
        $this->setParam('name_list', $rows);
        $this->setParam('pagination', new Pagination(1, 2, 3));
    }
}