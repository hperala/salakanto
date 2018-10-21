<?php
require_once 'include/view/ViewBase.php';
require_once 'include/model/Name.php';
require_once 'include/model/Instance.php';
require_once 'include/model/Markup.php';

class NameView extends ViewBase {

    function __construct($db, $user, $request, $settings) {
        parent::__construct($db, $user, $request, $settings);
    }

    function showNormal($template, $name, $instances) {
        $this->setParam('content', $template);

        $this->formatNormal($name, $instances);

        require $this->rootTemplatePath();
    }
    
    function showEdit($template, $name, $instances) {
        $this->setParam('content', $template);
    
        $this->formatEdit($name, $instances);
    
        require $this->rootTemplatePath();
    }

    private function formatNormal($name, $instances) {
        $this->formatGeneral($name, $instances);
    }
    
    private function formatEdit($name, $instances) {
        $this->formatGeneral($name, $instances);
        
        if ($this->request()->type() == Request::OPEN_EDIT) {
            $this->setParam('form_handler', 
                            'name.php?a=edit&amp;id='
                            . $this->request()->id());
            $this->setParam('heading', 
                            sprintf(_('Editing %s'), $name->titleHtml()));
        } else {
            $this->setParam('form_handler', 'name.php?a=edit');
            $this->setParam('heading', 'New name');
        }
        
        if ($name->error('title') !== '') {
            $this->setFieldError('title', 
                sprintf(Text::FIELD_ERROR, $name->error('title')));
        }
        if ($name->error('text') !== '') {
            $this->setFieldError('text',
                sprintf(Text::FIELD_ERROR, $name->error('text')));
        }
        
        $this->setParam('instance_list', 
                        array(array('text' => 'Galdrl', 
                                    'id' => '123')));
    }
    
    private function formatGeneral($name, $instances) {
        $this->setParam('item', $name);
        
        $this->setTitleParam($name->titleHtml());
        
        $this->setParam('heading', $name->titleHtml());
        $this->setParam('text', $name->textHtml());
        $this->setParam('index_coverage', $name->indexCoverageHtml());
        $this->setParam('other_coverage', $name->otherCoverageHtml());
        $this->setParam('distinct_forms', 
                        array('TBA'));
        $this->setParam('forms_by_source', array());
        $this->setParam('etyms', $this->formatInstancesWithNotes($instances));
        $this->setParam('secondary', '');
        $this->setParam('notes', '');
        /*$this->setParam('forms_by_source',
                        array(
                        array('items' => array(array('text' => 'Kalatriel', 
                                                     'note' => null, 
                                                     'ref' => 'X:123'),
                                               array('text' => 'Galdrel',
                                                     'note' => 'in “Aldariello nainië Lóriendesse”',
                                                     'ref' => 'X:3')),
                              'source_id' => '1',
                              'source_text' => 'Other (50s)',
                              'source_elaboration' => 'some note blaah blah'),
                        array('items' => array(array('text' => 'Simmeoni',
                                                     'note' => null,
                                                     'ref' => 'X:123'),
                                               array('text' => 'Simo',
                                                     'note' => 'in “Aldariello nainië Lóriendesse”',
                                                     'ref' => 'X:3')),
                              'source_id' => '1',
                              'source_text' => 'QaE',
                              'source_elaboration' => 'some note blaah blah')));
                                    
        $this->setParam('etyms', array(array('text' => 'Kalatriel',
                                             'translation' => 'jotain',
                                             'etymology' => 'The first note proposes a Q form Naldariel(lle) (√ÑGAL, √RIG-) and a S form Galadriel (although one might expect Galaðriel).',
                                             'source_id' => '1',
                                             'source_text' => 'Other (50s)',
                                             'source_elaboration' => 'some note blaah blah',
                                             'ref' => 'X:123'),
                                       array('text' => 'Galdrel',
                                             'translation' => 'jotain muuta',
                                             'etymology' => null,
                                             'source_id' => '1',
                                             'source_text' => 'Other (50s)',
                                             'source_elaboration' => null,
                                             'ref' => 'X:123'),
                                       array('text' => 'Galdrel',
                                             'translation' => null,
                                             'etymology' => '< gagaga + lalala',
                                             'source_id' => '1',
                                             'source_text' => 'Other (50s)',
                                             'source_elaboration' => 'some note blaah blah',
                                             'ref' => 'X:123')));
        $this->setParam('secondary', '<p>Kirja</p><p>Toinen kirja</p>');
        $this->setParam('notes', '<p>Huom.</p><p>Toinen huom.</p>');*/
    }

    private function formatInstancesWithNotes($instances) {
        $etyms = array();
        foreach ($instances as $ins) {
            if (!$ins->translation() && !$ins->etymology()) {
                continue;
            }

            $formatter = new Markup($ins->etymology());
            $etymologyHtml = '';
            if ($formatter->error() === Markup::OK) {
                $etymologyHtml = $formatter->html();
            }
            $etyms[] = array('text' => $ins->textHtml(),
                             'translation' => $ins->translationHtml(),
                             'etymology' => $etymologyHtml,
                             'source_id' => $ins->sourceId(),
                             'source_text' => $ins->sourceAbbreviationHtml(),
                             'source_elaboration' => $ins->sourceElaborationHtml(),
                             'ref' => $ins->refHtml());
        }
        return $etyms;
    }
}