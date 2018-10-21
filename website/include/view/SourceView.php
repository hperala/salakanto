<?php
require_once 'include/view/ViewBase.php';
require_once 'include/model/Source.php';

class SourceView extends ViewBase {

    function __construct($db, $user, $request, $settings) {
        parent::__construct($db, $user, $request, $settings);
    }

    function showNormal($template, $name) {
        $this->setParam('content', $template);

        $this->formatNormal($name);

        require $this->rootTemplatePath();
    }
    
    function showEdit($template, $name) {
        $this->setParam('content', $template);
    
        $this->formatEdit($name);
    
        require $this->rootTemplatePath();
    }

    private function formatNormal($source) {
        $this->formatGeneral($source);
    }
    
    private function formatEdit($source) {
        $this->formatGeneral($source);
        
        if ($this->request()->type() == Request::OPEN_EDIT) {
            $this->setParam('form_handler', 
                            'name.php?a=edit&amp;id='
                            . $this->request()->id());
            $this->setTitleParam(_('Editing source'));
            $this->setParam('heading',
                            sprintf(_('Editing %s'), 
                                    $source->abbreviationHtml()));
        } else {
            $this->setParam('form_handler', 'name.php?a=edit');
            $this->setTitleParam(_('New source'));
            $this->setParam('heading', _('New source'));
        }
        
        if ($source->error('title') !== '') {
            $this->setFieldError('title', 
                sprintf(Text::FIELD_ERROR, $source->error('title')));
        }
        if ($source->error('text') !== '') {
            $this->setFieldError('text',
                sprintf(Text::FIELD_ERROR, $source->error('text')));
        }
    }
    
    private function formatGeneral($source) {
        $this->setParam('item', $source);
        
        $this->setTitleParam($source->abbreviationHtml());
        $this->setParam('heading', $source->abbreviationHtml());
    }
}